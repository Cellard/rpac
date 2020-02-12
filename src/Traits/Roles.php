<?php

/**
 *  Трэйт контроля доступа для модели User на основе ролей
 */

namespace Trunow\Rpac\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Str;
use Trunow\Rpac\Role;

/**
 * Trait Rpacable
 * @package Trunow\Rpac\Traits
 *
 * @property Collection|Role[] $roles
 */
trait Roles
{
    /**
     * User belongs to many roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    /**
     * Check if User play given role(s)
     *
     * @param string|array $role
     * @return bool
     */
    public function playRole($role)
    {
        return count((array)$role) === $this->roles->whereIn('slug', $role)->count();
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function (User $user) {
            if(!$user->api_token) $user->api_token = Str::random(60);
        });
    }

}
