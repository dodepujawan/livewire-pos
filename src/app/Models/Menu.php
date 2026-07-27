<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $fillable = [
        'parent_id',
        'system_route_id',
        'title',
        'icon',
        'sort_order',
        'is_sidebar',
    ];

    protected $casts = [
        'is_sidebar' => 'boolean',
    ];

    /**
     * Parent Menu
     */
    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')
            ->orderBy('sort_order');
    }

    public function systemRoute()
    {
        return $this->belongsTo(SystemRoute::class);
    }
}
