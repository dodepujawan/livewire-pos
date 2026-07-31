<?php

namespace App\Services;

use Spatie\Permission\Models\Permission;

class PermissionMatrixService
{
    public static function build(): array
    {
        $matrix = [];

        foreach (Permission::orderBy('name')->get() as $permission) {
            $segments = explode('.', $permission->name);
            $action = array_pop($segments);
            $resource = implode('.', $segments);
            if (
                in_array($resource, self::$ignoredResources, true)
            ) {
                continue;
            }
            $label = ucwords(str_replace('.', ' ', $resource));
            if (!isset($matrix[$resource])) {
                $matrix[$resource] = [
                    'resource' => $resource,
                    'label' => $label,
                    'actions' => [],
                ];
            }
            $matrix[$resource]['actions'][$action] = $permission->name;
        }
        return $matrix;
    }

    protected static array $ignoredResources = [
        'login',
        'logout',
        'register',
        'password',
        'storage',
        'storage.local',
        'default-livewire',
        'auth.permission',
        'auth.register',
    ];
}
