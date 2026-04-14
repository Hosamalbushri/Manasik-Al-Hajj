<?php

namespace Webkul\User\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Webkul\User\Contracts\User as UserContract;

class User extends Authenticatable implements UserContract
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'image',
        'password',
        'api_token',
        'role_id',
        'status',
        'view_permission',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'api_token',
        'remember_token',
    ];

    /**
     * Get image url for the product image.
     */
    public function image_url()
    {
        if (! $this->image) {
            return;
        }

        return Storage::url($this->image);
    }

    /**
     * Get image url for the product image.
     */
    public function getImageUrlAttribute()
    {
        return $this->image_url();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $array = parent::toArray();

        $array['image_url'] = $this->image_url;

        return $array;
    }

    /**
     * Get the role that owns the user.
     */
    public function role()
    {
        return $this->belongsTo(RoleProxy::modelClass());
    }

    /**
     * The groups that belong to the user.
     */
    public function groups()
    {
        return $this->belongsToMany(GroupProxy::modelClass(), 'user_groups');
    }

    /**
     * Checks if user has permission to perform certain action.
     *
     * @param  string  $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        if ($this->role->permission_type == 'custom' && ! $this->role->permissions) {
            return false;
        }

        $permissions = $this->role->permissions ?? [];

        if (in_array($permission, $permissions, true)) {
            return true;
        }

        if ($permission === 'settings.web_theme' && in_array('settings.web_theme.homepage', $permissions, true)) {
            return true;
        }

        if ($permission === 'settings.web_theme.homepage' && in_array('settings.web_theme', $permissions, true)) {
            return true;
        }

        if ($permission === 'map_locations' && (
            in_array('map_locations', $permissions, true)
            || in_array('settings.map_locations', $permissions, true)
            || in_array('settings.web_theme.homepage', $permissions, true)
            || in_array('settings.web_theme', $permissions, true)
        )) {
            return true;
        }

        if ($permission === 'settings.map_locations' && (
            in_array('map_locations', $permissions, true)
            || in_array('settings.map_locations', $permissions, true)
            || in_array('settings.web_theme.homepage', $permissions, true)
            || in_array('settings.web_theme', $permissions, true)
        )) {
            return true;
        }

        $mapLegacy = [
            'map_locations.create' => 'settings.map_locations.create',
            'map_locations.edit' => 'settings.map_locations.edit',
            'map_locations.delete' => 'settings.map_locations.delete',
        ];
        if (isset($mapLegacy[$permission]) && in_array($mapLegacy[$permission], $permissions, true)) {
            return true;
        }

        $mapLegacyReverse = array_flip($mapLegacy);
        if (isset($mapLegacyReverse[$permission]) && in_array($mapLegacyReverse[$permission], $permissions, true)) {
            return true;
        }

        if ($permission === 'adhkar_duas' && (
            in_array('adhkar_duas.dua_sections', $permissions, true)
            || in_array('adhkar_duas.duas', $permissions, true)
            || in_array('settings.dua_sections', $permissions, true)
            || in_array('settings.duas', $permissions, true)
        )) {
            return true;
        }

        if ($permission === 'hajj_rites' && (
            in_array('hajj_rites', $permissions, true)
            || in_array('hajj_rites.create', $permissions, true)
            || in_array('hajj_rites.edit', $permissions, true)
            || in_array('hajj_rites.delete', $permissions, true)
            || in_array('adhkar_duas.hajj_rites', $permissions, true)
            || in_array('adhkar_duas.hajj_rites.create', $permissions, true)
            || in_array('adhkar_duas.hajj_rites.edit', $permissions, true)
            || in_array('adhkar_duas.hajj_rites.delete', $permissions, true)
        )) {
            return true;
        }

        $adhkarLegacy = [
            'adhkar_duas.dua_sections' => 'settings.dua_sections',
            'adhkar_duas.dua_sections.create' => 'settings.dua_sections.create',
            'adhkar_duas.dua_sections.edit' => 'settings.dua_sections.edit',
            'adhkar_duas.dua_sections.delete' => 'settings.dua_sections.delete',
            'adhkar_duas.duas' => 'settings.duas',
            'adhkar_duas.duas.create' => 'settings.duas.create',
            'adhkar_duas.duas.edit' => 'settings.duas.edit',
            'adhkar_duas.duas.delete' => 'settings.duas.delete',
            'hajj_rites' => 'settings.duas',
            'hajj_rites.create' => 'settings.duas.create',
            'hajj_rites.edit' => 'settings.duas.edit',
            'hajj_rites.delete' => 'settings.duas.delete',
            'adhkar_duas.hajj_rites' => 'settings.duas',
            'adhkar_duas.hajj_rites.create' => 'settings.duas.create',
            'adhkar_duas.hajj_rites.edit' => 'settings.duas.edit',
            'adhkar_duas.hajj_rites.delete' => 'settings.duas.delete',
        ];
        if (isset($adhkarLegacy[$permission]) && in_array($adhkarLegacy[$permission], $permissions, true)) {
            return true;
        }

        return false;
    }
}
