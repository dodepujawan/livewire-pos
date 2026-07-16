<?php

namespace App\Services;

use App\Models\Menu;
use Illuminate\Support\Facades\Auth;

class SidebarService
{
    /**
     * Get menu tree for sidebar based on user permissions
     */
    public function getMenuTree(): array
    {
        $user = Auth::user();

        if (!$user) {
            return [];
        }

        // Get all active menus that should show in sidebar
        $menus = Menu::active()
            ->inSidebar()
            ->ordered()
            ->get();

        // Filter menus based on user permissions
        $filteredMenus = $menus->filter(function ($menu) use ($user) {
            // If menu has no permission requirement, show it
            if (empty($menu->permission_name)) {
                return true;
            }

            // Check if user has the required permission using Spatie API
            return $user->can($menu->permission_name);
        });

        // Build menu tree with parent-child hierarchy
        return $this->buildMenuTree($filteredMenus);
    }

    /**
     * Build hierarchical menu tree from flat menu list
     */
    private function buildMenuTree($menus): array
    {
        $menuMap = [];
        $rootMenus = [];

        // First pass: create menu map
        foreach ($menus as $menu) {
            $menuMap[$menu->route_name] = [
                'route_name' => $menu->route_name,
                'display_name' => $menu->display_name,
                'icon' => $menu->icon,
                'sort_order' => $menu->sort_order,
                'parent_route_name' => $menu->parent_route_name,
                'children' => [],
            ];
        }

        // Second pass: build hierarchy
        foreach ($menuMap as $routeName => $menuData) {
            if (empty($menuData['parent_route_name'])) {
                // Root menu
                $rootMenus[] = &$menuMap[$routeName];
            } elseif (isset($menuMap[$menuData['parent_route_name']])) {
                // Child menu - add to parent's children
                $menuMap[$menuData['parent_route_name']]['children'][] = &$menuMap[$routeName];
            }
        }

        // Sort root menus by sort_order
        usort($rootMenus, function ($a, $b) {
            return ($a['sort_order'] ?? 0) <=> ($b['sort_order'] ?? 0);
        });

        // Sort children menus by sort_order
        foreach ($rootMenus as &$rootMenu) {
            if (!empty($rootMenu['children'])) {
                usort($rootMenu['children'], function ($a, $b) {
                    return ($a['sort_order'] ?? 0) <=> ($b['sort_order'] ?? 0);
                });
            }
        }

        return $rootMenus;
    }
}
