<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;

class RouteSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'route:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize Laravel named routes into system_routes table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $routes = Route::getRoutes();

        foreach ($routes as $route) {

            if (!$route->getName()) {
                continue;
            }

            $this->line($route->getName());

        }
    }
}
