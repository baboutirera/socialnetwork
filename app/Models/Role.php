<?php

namespace App\Models;

use App\Traits\HasPermissions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasPermissions;

    public function permissions() {
        return $this->belongsToMany(Permission::class, 'roles_permissions');
    }

    public function hasPermissionTo(...$permissions) {
        return $this->permissions()->WhereIn('slug', $permissions)->count();
    }

    public function scopeDeveloper($query) {
        return $this->where('slug', 'developer');
    }

    public function scopeAdmin($query) {
        return $this->where('slug', 'admin');
    }
}
