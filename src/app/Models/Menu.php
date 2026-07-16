<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menus';

    protected $fillable = [
        'route_name',
        'permission_name',
        'display_name',
        'group',
        'icon',
        'sort_order',
        'is_metadata_manual',
        'is_active',
        'show_in_sidebar',
        'parent_route_name',
    ];

    protected $casts = [
        'is_metadata_manual' => 'boolean',
        'is_active' => 'boolean',
        'show_in_sidebar' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Scope untuk filter menu yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk filter menu yang ditampilkan di sidebar
     */
    public function scopeInSidebar($query)
    {
        return $query->where('show_in_sidebar', true);
    }

    /**
     * Scope untuk filter menu yang metadata-nya masih auto-generated
     */
    public function scopeAutoMetadata($query)
    {
        return $query->where('is_metadata_manual', false);
    }

    /**
     * Scope untuk filter menu yang metadata-nya sudah di-edit manual
     */
    public function scopeManualMetadata($query)
    {
        return $query->where('is_metadata_manual', true);
    }

    /**
     * Scope untuk filter menu berdasarkan group
     */
    public function scopeByGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Scope untuk urutkan berdasarkan sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
