<?php

namespace App\Modules\Patients\Controllers;

use App\Modules\Patients\Models\Patient;
use App\Modules\Patients\Models\PatientDocument;
use App\Modules\Patients\Resources\PatientDocumentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PatientDocumentController
{
    public function store(Request $request, $patientId)
    {
        $request->validate([
            'file' => ['required', 'file', 'max:10240'], // Max 10MB
            'type' => ['required', 'in:cin,passport,xray,scan,photo,prescription,signed_consent'],
        ]);

        $patient = Patient::findOrFail($patientId);
        $file = $request->file('file');
        $type = $request->input('type');

        // Store file
        $path = $file->store("patients/{$patientId}", 'public');

        // Create document record
        $document = $patient->documents()->create([
            'type' => $type,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        return new PatientDocumentResource($document);
    }

    public function destroy($id)
    {
        $document = PatientDocument::findOrFail($id);

        // Delete file from storage
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        // Delete record
        $document->delete();

        return response()->noContent();
    }
}
