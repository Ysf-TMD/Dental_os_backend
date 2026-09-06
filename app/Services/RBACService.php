<?php

namespace App\Services;

use App\Models\User;
use App\Modules\Permission\Models\Permission;
use App\Modules\Page\Models\Page;
use App\Modules\Role\Models\Role;
use Illuminate\Support\Collection;

class RBACService
{
    public function checkUserPermission(User $user, string $permission): bool
    {
        return $user->hasPermission($permission);
    }

    public function checkUserPageAccess(User $user, string $pagePath): bool
    {
        $page = Page::findByPath($pagePath);
        
        if (!$page || !$page->is_active) {
            return false;
        }

        $requiredPermissions = $page->permissions;
        
        if ($requiredPermissions->isEmpty()) {
            return true; // Page publique
        }

        $userPermissions = $user->getAllPermissions();
        
        return $userPermissions->pluck('id')->intersect($requiredPermissions->pluck('id'))->isNotEmpty();
    }

    public function getUserPermissions(User $user): Collection
    {
        return $user->getAllPermissions();
    }

    public function getRolePermissions(int $roleId): Collection
    {
        return Role::findOrFail($roleId)->permissions()->get();
    }

    public function getPagePermissions(int $pageId): Collection
    {
        return Page::findOrFail($pageId)->permissions()->get();
    }

    public function getAccessiblePagesForUser(User $user): Collection
    {
        return Page::getAccessiblePagesForUser($user);
    }

    public function syncPagePermissions(int $pageId, array $permissionIds): void
    {
        $page = Page::findOrFail($pageId);
        $page->permissions()->sync($permissionIds);
    }

    public function syncRolePermissions(int $roleId, array $permissionIds): void
    {
        $role = Role::findOrFail($roleId);
        $role->permissions()->sync($permissionIds);
    }

    public function syncUserPermissions(int $userId, array $permissionIds): void
    {
        $user = User::findOrFail($userId);
        $user->permissions()->sync($permissionIds);
    }

    public function createPermission(array $data): Permission
    {
        return Permission::create($data);
    }

    public function createPage(array $data): Page
    {
        return Page::create($data);
    }

    public function updatePage(int $pageId, array $data): Page
    {
        $page = Page::findOrFail($pageId);
        $page->update($data);
        return $page;
    }

    public function deletePage(int $pageId): void
    {
        Page::findOrFail($pageId)->delete();
    }
}
