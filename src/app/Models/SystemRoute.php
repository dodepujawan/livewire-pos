<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemRoute extends Model
{
    protected $fillable = [
        'route_name',
        'uri',
        'method',
        'action',
        'last_sync_at',
    ];

    protected $casts = [
        'last_sync_at' => 'datetime',
    ];

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }
}
