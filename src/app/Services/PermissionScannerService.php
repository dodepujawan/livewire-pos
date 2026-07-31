<?php

namespace App\Services;

use Illuminate\Support\Facades\Route;
use ReflectionClass;

class PermissionScannerService
{
    public function scan(): array
    {
        $permissions = [];

        foreach (Route::getRoutes() as $route) {

            $handler = $route->getAction('uses');

            // Controller
            if (is_string($handler) && str_contains($handler, '@')) {

                [$class] = explode('@', $handler);

                if (! class_exists($class)) {
                    continue;
                }

                $reflection = new ReflectionClass($class);

                if (! $reflection->hasProperty('additionalPermissions')) {
                    continue;
                }

                $property = $reflection->getProperty('additionalPermissions');

                $property->setAccessible(true);

                $permissions = array_merge(
                    $permissions,
                    $property->getValue(new $class)
                );

                continue;
            }

            // Livewire MFC
            if (is_string($handler) && str_starts_with($handler, 'pages::')) {

                $permissions = array_merge(
                    $permissions,
                    $this->scanLivewire($handler)
                );
            }
        }

        return array_unique($permissions);
    }

    protected function scanLivewire(string $handler): array
    {
        $handler = str_replace('pages::', '', $handler);

        $path = resource_path(
            'views/pages/' .
            str_replace('.', '/', $handler) .
            '/' .
            basename($handler) .
            '.php'
        );

        if (! file_exists($path)) {
            return [];
        }

        $component = include $path;

        $reflection = new ReflectionClass($component);

        if (! $reflection->hasProperty('additionalPermissions')) {
            return [];
        }

        $property = $reflection->getProperty('additionalPermissions');

        $property->setAccessible(true);

        return $property->getValue($component);
    }
}
