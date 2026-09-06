<?php

namespace App\Modules\Permission\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'action',
        'page_id',
        'group_name',
        'description',
    ];

    protected $casts = [
        'page_id' => 'integer',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(\App\Modules\Role\Models\Role::class, 'role_permission');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\User::class, 'user_permission');
    }

    public function pages(): BelongsToMany
    {
        return $this->belongsToMany(\App\Modules\Page\Models\Page::class, 'page_permission');
    }

    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeByGroup($query, $group)
    {
        return $query->where('group_name', $group);
    }
}
