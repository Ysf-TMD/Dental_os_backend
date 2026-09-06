<?php

namespace App\Modules\Page\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'path',
        'component',
        'icon',
        'group_name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(\App\Modules\Permission\Models\Permission::class, 'page_permission');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public static function findByPath(string $path): ?self
    {
        return static::where('path', $path)->first();
    }

    public static function getAccessiblePagesForUser($user)
    {
        $userPermissions = $user->getAllPermissions();
        $permissionIds = $userPermissions->pluck('id');

        return static::whereHas('permissions', function ($query) use ($permissionIds) {
            $query->whereIn('permissions.id', $permissionIds);
        })->where('is_active', true)->get();
    }
}
