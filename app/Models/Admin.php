<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'forget_password_token',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    public function scopeNotSuperAdmin($query)
    {
        return $query->where('is_super_admin', 0);
    }

    public static function getPermissionGroup()
    {
        $permission_group = DB::table('permissions')
            ->select('group_name as name')->orderBy('group_name')
            ->groupBy('group_name')
            ->get();

        return $permission_group;
    }

    public static function getpermissionsByGroupName($group_name)
    {
        $permissions = DB::table('permissions')
            ->select('name', 'id')
            ->where('group_name', $group_name)
            ->get();

        return $permissions;
    }

    public static function getPermissionGroupsWithPermissions()
    {
        return DB::table('permissions')
            ->select('group_name', 'id', 'name')
            ->orderBy('group_name')
            ->get()
            ->groupBy('group_name');
    }

    public static function roleHasPermission($role, $permissions)
    {
        $hasPermission = true;
        foreach ($permissions as $permission) {
            if (! $role->hasPermissionTo($permission->name)) {
                $hasPermission = false;

                return $hasPermission;
            }
        }

        return $hasPermission;
    }

    public function getImageUrlAttribute()
    {;
        $setting = cache('setting');
        $value = $this->attributes['image'];

        // check if file is exists
        if ($value && !file_exists(public_path($value))) {
            if (str_contains($value, 'https:/')) {
                $value = $value;
            } else {
                $value = $this->media?->path;
                if ($value) {
                    $value = asset($value);
                }
            }
        } else if ($value) {
            $value = asset($value);
        }
        return $value ? $value : asset($setting->default_avatar);
    }
}
