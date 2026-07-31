<?php

namespace App\Console\Commands;

use App\Models\SystemRoute;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;

class PermissionSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'framework:permission-sync';

    /**
     * The console command description.
     */
    protected $description = 'Synchronize system routes into Spatie permissions';

    /**
     * Route Action => Permission Action
     */
    private array $actionMap = [
        'list'      => 'view',
        'show'      => 'view',

        'create'    => 'create',
        'store'     => 'create',

        'edit'      => 'update',
        'update'    => 'update',

        'destroy'   => 'delete',
        'delete'    => 'delete',

        'print'     => 'print',
        'export'    => 'export',
        'import'    => 'import',
    ];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $routePermissions = SystemRoute::query()
            ->pluck('route_name')
            ->map(fn ($route) => $this->convertPermission($route))
            ->unique()
            ->values();

        $created = 0;

        foreach ($routePermissions as $permissionName) {

            $permission = Permission::findOrCreate(
                $permissionName,
                'web'
            );

            if ($permission->wasRecentlyCreated) {
                $created++;
            }
        }

        // Hapus permission route yang sudah tidak ada
        Permission::query()
            ->where('guard_name', 'web')
            ->whereDoesntHave('roles')
            ->whereNotIn('name', $routePermissions)
            ->delete();

        $this->newLine();

        $this->info('Permission Synchronization Completed');

        $this->line('Permission : ' . $routePermissions->count());
        $this->line('Created    : ' . $created);

        return self::SUCCESS;
    }

    /**
     * Convert Route Name into Permission Name
     *
     * master.barang.list
     * =>
     * master.barang.view
     */
    private function convertPermission(string $routeName): string
    {
        $segments = explode('.', $routeName);

        if (count($segments) < 2) {
            return $routeName;
        }

        $action = array_pop($segments);

        $segments[] = $this->actionMap[$action] ?? $action;

        return implode('.', $segments);
    }
}
