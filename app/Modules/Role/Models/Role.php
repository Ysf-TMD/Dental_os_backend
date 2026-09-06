<?php

namespace App\Modules\Role\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\User::class, 'role_user');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(\App\Modules\Permission\Models\Permission::class, 'role_permission');
    }

    public function pages(): BelongsToMany
    {
        return $this->belongsToMany(\App\Modules\Page\Models\Page::class, 'page_role');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function getUsersCountAttribute(): int
    {
        return $this->users()->count();
    }

    protected static function booted(): void
    {
        static::creating(function (Role $role) {
            if (empty($role->name)) {
                $role->name = strtolower(str_replace(' ', '_', $role->display_name));
            }
        });
    }
}
