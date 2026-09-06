<?php

namespace App\Modules\Role\Controllers;

use App\Modules\Role\Models\Role;
use App\Modules\Role\Resources\RoleResource;
use App\Modules\Shared\Controllers\BaseCrudController;
use Illuminate\Http\Request;
use Dompdf\Dompdf;

class RoleController extends BaseCrudController
{
    protected string $model = Role::class;

    protected string $resource = RoleResource::class;

    protected array $searchable = ["name", "display_name", "description"];

    protected array $filterable = ["is_active"];

    public function publicRoles()
    {
        $roles = Role::where('is_active', true)
            ->orderBy('display_name')
            ->get(['id', 'name', 'display_name', 'description']);

        return response()->json([
            'data' => $roles
        ]);
    }

    protected function rules(bool $updating = false): array
    {
        $rules = [
            "display_name" => $updating ? ["sometimes", "string", "min:2", "max:100"] : ["required", "string", "min:2", "max:100"],
            "description" => ["nullable", "string", "max:500"],
            "is_active" => ["sometimes", "boolean"]
        ];

        if ($updating) {
            $rules["name"] = ["sometimes", "string", "max:100", "unique:roles,name," . request()->route("role")];
        } else {
            $rules["name"] = ["required", "string", "max:100", "unique:roles,name"];
        }

        return $rules;
    }

    protected function applyFilters(\Illuminate\Database\Eloquent\Builder $query, \Illuminate\Http\Request $request): void
    {
        // Exclude super_admin from the list
        $query->where('name', '!=', 'super_admin');

        if ($request->filled("users_count")) {
            $query->withCount("users")->having("users_count", $request->query('users_count'));
        }
        if ($request->filled("has_description")) {
            if ($request->boolean("has_description")) {
                $query->whereNotNull("description")->where("description", '!=', '');
            } else {
                $query->where(function ($q) {
                    $q->whereNull("description")->orWhere("description", '');
                });
            }
        }
    }

    protected function prepareData(array $data): array
    {
        if (!isset($data["name"]) && isset($data["display_name"])) {
            $data["name"] = strtolower(str_replace(" ", "_", $data["display_name"]));
        }
        return $data;
    }

    public function assignToUser(Request $request, $roleId, $userId)
    {
        $role = Role::findOrFail($roleId);
        $user = \App\Models\User::findOrFail($userId);

        $user->roles()->syncWithoutDetaching([$roleId]);
        return response()->json([
            "message" => "Rôle assigné avec succès",
            "role" => new RoleResource($role),
            "user" => $user->only(["id", "name", "email"])
        ]);
    }

    public function removeFromUser(Request $request, $roleId, $userId)
    {
        $role = Role::findOrFail($roleId);
        $user = \App\Models\User::findOrFail($userId);

        $user->roles()->detach([$roleId]);
        return response()->json([
            "message" => "Rôle retiré avec succès",
        ]);
    }

    public function users($roleId)
    {
        $role = Role::with('users')->findOrFail($roleId);
        return response()->json([
            "role" => new RoleResource($role),
            "users" => $role->users->map(function ($user) {
                return [
                    "id" => $user->id,
                    "name" => $user->name,
                    "email" => $user->email,
                    "assigned_at" => $user->pivot->created_at,
                ];
            })
        ]);
    }

    public function syncPermissions(Request $request, $roleId)
    {
        $request->validate([
            'permission_ids' => 'required|array',
            'permission_ids.*' => 'exists:permissions,id',
        ]);

        $role = Role::findOrFail($roleId);
        $role->permissions()->sync($request->permission_ids);

        return response()->json([
            'message' => 'Permissions synchronisées avec succès',
            'role' => new RoleResource($role->load('permissions'))
        ]);
    }

    public function getPermissions($roleId)
    {
        $role = Role::with('permissions')->findOrFail($roleId);
        return response()->json([
            'data' => $role->permissions
        ]);
    }

    public function syncPages(Request $request, $roleId)
    {
        $request->validate([
            'page_ids' => 'required|array',
            'page_ids.*' => 'exists:pages,id',
        ]);

        $role = Role::findOrFail($roleId);
        
        // Sync pages through permissions
        // First, get all permissions for the selected pages
        $pageIds = $request->page_ids;
        $permissions = \App\Modules\Permission\Models\Permission::whereIn('page_id', $pageIds)->pluck('id');
        
        // Sync these permissions to the role
        $role->permissions()->sync($permissions);

        return response()->json([
            'message' => 'Pages synchronisées avec succès',
            'role' => new RoleResource($role->load('permissions'))
        ]);
    }

    public function getPages($roleId)
    {
        $role = Role::with('permissions')->findOrFail($roleId);

        // Get pages from permissions
        $pageIds = $role->permissions->pluck('page_id')->filter()->unique();
        $pages = \App\Modules\Page\Models\Page::whereIn('id', $pageIds)->get();

        return response()->json([
            'data' => $pages
        ]);
    }

    public function export(Request $request)
    {
        $ids = $request->input('ids', []);
        $columns = $request->input('columns', ['id', 'name', 'display_name', 'description', 'is_active', 'created_at', 'updated_at']);
        $format = $request->input('format', 'csv');

        // If no columns specified, use all available columns
        if (empty($columns)) {
            $columns = ['id', 'name', 'display_name', 'description', 'is_active', 'created_at', 'updated_at'];
        }

        $query = Role::query();

        if (!empty($ids)) {
            $query->whereIn('id', $ids);
        }

        $roles = $query->get();

        $data = [];
        $data[] = $columns;

        foreach ($roles as $role) {
            $row = [];
            foreach ($columns as $column) {
                $value = $role->$column ?? '';
                if ($column === 'is_active') {
                    $value = $value ? 'true' : 'false';
                }
                $row[] = $value;
            }
            $data[] = $row;
        }

        $filename = 'roles_export_' . date('Y-m-d_H-i-s');

        if ($format === 'csv') {
            $filename .= '.csv';
            $handle = fopen('php://temp', 'r+');

            foreach ($data as $row) {
                fputcsv($handle, $row);
            }

            rewind($handle);
            $content = stream_get_contents($handle);
            fclose($handle);

            return response($content)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        } elseif ($format === 'xlsx') {
            $filename .= '.xlsx';

            // Generate Excel XML format (SpreadsheetML)
            $xml = '<?xml version="1.0" encoding="UTF-8"?>';
            $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"';
            $xml .= ' xmlns:o="urn:schemas-microsoft-com:office:office"';
            $xml .= ' xmlns:x="urn:schemas-microsoft-com:office:excel"';
            $xml .= ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"';
            $xml .= ' xmlns:html="http://www.w3.org/TR/REC-html40">';
            $xml .= '<Worksheet ss:Name="Sheet1">';
            $xml .= '<Table>';

            foreach ($data as $row) {
                $xml .= '<Row>';
                foreach ($row as $cell) {
                    $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars($cell) . '</Data></Cell>';
                }
                $xml .= '</Row>';
            }

            $xml .= '</Table>';
            $xml .= '</Worksheet>';
            $xml .= '</Workbook>';

            return response($xml)
                ->header('Content-Type', 'application/vnd.ms-excel')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        } elseif ($format === 'pdf') {
            $filename .= '.pdf';

            $appName = config('app.name', 'Application');
            $exportDate = date('d/m/Y H:i:s');

            // Generate HTML for PDF with styling
            $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #4f46e5;
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        .header p {
            color: #666;
            margin: 5px 0 0 0;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        thead {
            background-color: #4f46e5;
            color: white;
        }
        th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
            border: 1px solid #4f46e5;
        }
        td {
            padding: 10px;
            border: 1px solid #e5e7eb;
            font-size: 13px;
        }
        tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        tbody tr:hover {
            background-color: #f3f4f6;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>' . htmlspecialchars($appName) . '</h1>
        <p>Export des Rôles</p>
    </div>
    <table>
        <thead>';

            // Header row
            $html .= '<tr>';
            foreach ($data[0] as $header) {
                $html .= '<th>' . htmlspecialchars($header) . '</th>';
            }
            $html .= '</tr></thead><tbody>';

            // Data rows (skip header)
            for ($i = 1; $i < count($data); $i++) {
                $html .= '<tr>';
                foreach ($data[$i] as $cell) {
                    $html .= '<td>' . htmlspecialchars($cell) . '</td>';
                }
                $html .= '</tr>';
            }

            $html .= '</tbody></table>
    <div class="footer">
        <p>Date d\'export: ' . $exportDate . '</p>
        <p>Nombre d\'enregistrements: ' . (count($data) - 1) . '</p>
    </div>
</body>
</html>';

            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();

            $pdfContent = $dompdf->output();

            return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Content-Length', strlen($pdfContent));
        }

        return response()->json(['error' => 'Format not supported'], 400);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();
        $handle = fopen($path, 'r');

        $header = fgetcsv($handle);
        $imported = 0;
        $errors = [];

        while (($row = fgetcsv($handle)) !== false) {
            try {
                $roleData = [
                    'name' => $row[1] ?? null,
                    'display_name' => $row[2] ?? null,
                    'description' => $row[3] ?? null,
                    'is_active' => isset($row[4]) ? filter_var($row[4], FILTER_VALIDATE_BOOLEAN) : true,
                ];

                $existingRole = Role::where('name', $roleData['name'])->first();

                if ($existingRole) {
                    $existingRole->update($roleData);
                } else {
                    Role::create($roleData);
                }

                $imported++;
            } catch (\Exception $e) {
                $errors[] = [
                    'row' => $imported + 1,
                    'error' => $e->getMessage(),
                ];
            }
        }

        fclose($handle);

        return response()->json([
            'imported' => $imported,
            'errors' => $errors,
        ]);
    }
}
