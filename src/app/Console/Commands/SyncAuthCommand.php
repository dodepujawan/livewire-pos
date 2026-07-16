<?php

namespace App\Console\Commands;

use App\Models\Menu;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SyncAuthCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-auth {--dry-run : Show what would be done without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Livewire routes to menus table';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting route synchronization...');

        $dryRun = $this->option('dry-run');

        // Get all routes
        $allRoutes = Route::getRoutes();

        // Filter Livewire routes with names
        $livewireRoutes = collect($allRoutes)->filter(function ($route) {
            return $this->isLivewireRoute($route) && $route->getName() !== null;
        });

        // Filter out ignorable routes
        $filteredRoutes = $livewireRoutes->filter(function ($route) {
            return !$this->isIgnorableRoute($route->getName());
        });

        $this->info("Found {$filteredRoutes->count()} Livewire routes to sync");

        if ($dryRun) {
            $this->warn('DRY RUN MODE - No changes will be made');
        }

        // Get current active menus from database
        $currentMenus = Menu::all()->keyBy('route_name');
        $currentRouteNames = $currentMenus->keys()->toArray();

        // Get route names from filtered routes
        $newRouteNames = $filteredRoutes->map->getName()->toArray();

        // Routes to add (new routes not in database)
        $routesToAdd = array_diff($newRouteNames, $currentRouteNames);

        // Routes to reactivate (inactive routes that exist again)
        $inactiveMenus = Menu::where('is_active', false)->get();
        $routesToReactivate = [];
        foreach ($inactiveMenus as $menu) {
            if (in_array($menu->route_name, $newRouteNames)) {
                $routesToReactivate[] = $menu->route_name;
            }
        }

        // Routes to deactivate (active routes that no longer exist)
        $routesToDeactivate = array_diff($currentRouteNames, $newRouteNames);

        $this->info("Routes to add: " . count($routesToAdd));
        $this->info("Routes to reactivate: " . count($routesToReactivate));
        $this->info("Routes to deactivate: " . count($routesToDeactivate));

        if ($dryRun) {
            $this->newLine();
            $this->info('Routes that would be added:');
            foreach ($routesToAdd as $routeName) {
                $this->line("  - {$routeName}");
            }

            $this->newLine();
            $this->info('Routes that would be reactivated:');
            foreach ($routesToReactivate as $routeName) {
                $this->line("  - {$routeName}");
            }

            $this->newLine();
            $this->info('Routes that would be deactivated:');
            foreach ($routesToDeactivate as $routeName) {
                $this->line("  - {$routeName}");
            }

            return self::SUCCESS;
        }

        // Process routes to add
        foreach ($routesToAdd as $routeName) {
            $route = $filteredRoutes->first(fn ($r) => $r->getName() === $routeName);
            
            $menu = Menu::create([
                'route_name' => $routeName,
                'permission_name' => $this->generatePermissionName($routeName),
                'display_name' => $this->generateDisplayName($routeName),
                'group' => $this->generateGroup($routeName),
                'icon' => null,
                'sort_order' => 0,
                'is_metadata_manual' => false,
                'is_active' => true,
                'show_in_sidebar' => true,
                'parent_route_name' => null,
            ]);

            $this->info("Created menu: {$routeName}");
        }

        // Process routes to reactivate
        foreach ($routesToReactivate as $routeName) {
            $menu = Menu::where('route_name', $routeName)->first();
            if ($menu) {
                $menu->update(['is_active' => true]);
                $this->info("Reactivated menu: {$routeName}");
            }
        }

        // Process routes to deactivate
        foreach ($routesToDeactivate as $routeName) {
            $menu = Menu::where('route_name', $routeName)->first();
            if ($menu) {
                $menu->update(['is_active' => false]);
                $this->info("Deactivated menu: {$routeName}");
            }
        }

        // Sync permissions to Spatie
        $this->syncPermissions($dryRun);

        // Display summary
        $this->newLine();
        $this->info('=== SYNCHRONIZATION SUMMARY ===');
        $this->line("Total routes found: {$filteredRoutes->count()}");
        $this->line("Menus created: " . count($routesToAdd));
        $this->line("Menus reactivated: " . count($routesToReactivate));
        $this->line("Menus deactivated: " . count($routesToDeactivate));
        $this->line("Total active menus: " . Menu::where('is_active', true)->count());
        $this->newLine();
        $this->info('Route synchronization completed successfully!');

        return self::SUCCESS;
    }

    /**
     * Check if route is a Livewire route
     */
    protected function isLivewireRoute($route): bool
    {
        $action = $route->getAction('uses');
        $middleware = $route->getMiddleware();
        
        // Check if route uses Livewire middleware
        if (in_array('web', $middleware) || in_array('auth', $middleware)) {
            // Check if action is a Livewire component
            if (is_string($action)) {
                // Check for Livewire component class pattern
                if (preg_match('/@/', $action)) {
                    [$controller, $method] = explode('@', $action);
                    
                    // Check if controller extends Livewire component
                    if (class_exists($controller)) {
                        $reflection = new \ReflectionClass($controller);
                        $parentClass = $reflection->getParentClass();
                        
                        // Check if parent is Livewire component
                        while ($parentClass) {
                            if (str_contains($parentClass->getName(), 'Livewire')) {
                                return true;
                            }
                            $parentClass = $parentClass->getParentClass();
                        }
                    }
                }
                
                // Fallback: check for Livewire or ⚡ in action string
                return str_contains($action, 'Livewire') || str_contains($action, '\⚡');
            }
        }

        return false;
    }

    /**
     * Check if route should be ignored
     */
    protected function isIgnorableRoute(string $routeName): bool
    {
        $ignorableRoutes = Config::get('auth_sync.ignorable_routes', []);

        foreach ($ignorableRoutes as $pattern) {
            if (Str::is($pattern, $routeName)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Generate permission name from route name
     */
    protected function generatePermissionName(string $routeName): string
    {
        $routePermissionMap = Config::get('auth_sync.route_permission_map', []);
        $defaultMapping = Config::get('auth_sync.default_mapping', []);

        // Check explicit mapping first
        if (isset($routePermissionMap[$routeName])) {
            return $routePermissionMap[$routeName];
        }

        // Use default mapping pattern
        foreach ($defaultMapping as $pattern => $replacement) {
            if (Str::is($pattern, $routeName)) {
                $module = $this->extractModule($routeName);
                return str_replace('{module}', $module, $replacement);
            }
        }

        // Fallback: use route name as permission name
        return $routeName;
    }

    /**
     * Extract module name from route name
     */
    protected function extractModule(string $routeName): string
    {
        $parts = explode('.', $routeName);
        return $parts[0] ?? $routeName;
    }

    /**
     * Generate display name from route name
     */
    protected function generateDisplayName(string $routeName): string
    {
        $parts = explode('.', $routeName);
        
        // Convert to title case
        $titleParts = array_map(function ($part) {
            return Str::title(str_replace(['-', '_'], ' ', $part));
        }, $parts);

        return implode(' ', $titleParts);
    }

    /**
     * Generate group from route name
     */
    protected function generateGroup(string $routeName): string
    {
        $groupRules = Config::get('auth_sync.group_rules', []);
        $module = $this->extractModule($routeName);

        // Check explicit group rule
        if (isset($groupRules[$module])) {
            return $groupRules[$module];
        }

        // Fallback: use title case of module
        return Str::title($module);
    }

    /**
     * Sync permissions to Spatie
     */
    protected function syncPermissions(bool $dryRun): void
    {
        $this->newLine();
        $this->info('Syncing permissions to Spatie...');

        // Get all active menus
        $activeMenus = Menu::active()->get();
        
        $permissionsCreated = 0;
        $permissionsAssigned = 0;

        // Get default role configuration
        $defaultRoleName = Config::get('auth_sync.default_role', 'admin');
        $autoAssign = Config::get('auth_sync.auto_assign_to_default_role', true);
        $roleBlacklist = Config::get('auth_sync.role_blacklist', []);

        // Get default role if auto-assign is enabled
        $defaultRole = null;
        if ($autoAssign) {
            $defaultRole = Role::where('name', $defaultRoleName)->first();
            if (!$defaultRole) {
                $this->warn("Default role '{$defaultRoleName}' not found. Skipping auto-assign.");
            }
        }

        foreach ($activeMenus as $menu) {
            $permissionName = $menu->permission_name;

            // Check if permission already exists
            $existingPermission = Permission::where('name', $permissionName)->first();

            if (!$existingPermission) {
                if ($dryRun) {
                    $this->line("  Would create permission: {$permissionName}");
                    $permissionsCreated++;
                } else {
                    // Create permission
                    Permission::create(['name' => $permissionName]);
                    $this->line("  Created permission: {$permissionName}");
                    $permissionsCreated++;

                    // Auto-assign to default role
                    if ($defaultRole && !in_array($defaultRoleName, $roleBlacklist)) {
                        $defaultRole->givePermissionTo($permissionName);
                        $this->line("    Assigned to role: {$defaultRoleName}");
                        $permissionsAssigned++;
                    }
                }
            }
        }

        if ($dryRun) {
            $this->newLine();
            $this->info("Permissions that would be created: {$permissionsCreated}");
            if ($defaultRole && !in_array($defaultRoleName, $roleBlacklist)) {
                $this->info("Permissions that would be assigned to {$defaultRoleName}: {$permissionsAssigned}");
            }
        } else {
            $this->newLine();
            $this->info("Permissions created: {$permissionsCreated}");
            if ($defaultRole && !in_array($defaultRoleName, $roleBlacklist)) {
                $this->info("Permissions assigned to {$defaultRoleName}: {$permissionsAssigned}");
            }
        }
    }
}
