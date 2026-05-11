<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;
        protected $fillable = [
        'name',
    ];

    public function roles()
{
    return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
}
}


