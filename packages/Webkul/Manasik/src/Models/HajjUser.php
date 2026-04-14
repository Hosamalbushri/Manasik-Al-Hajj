<?php

namespace Webkul\Manasik\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;
use Webkul\Manasik\Contracts\HajjUser as HajjUserContract;

class HajjUser extends Authenticatable implements HajjUserContract
{
    use HasFactory, Notifiable;

    protected $table = 'manasik_hajj_users';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'birth_date',
        'address',
        'password',
        'status',
        'locale',
        'preferences',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'birth_date' => 'date',
            'password' => 'hashed',
            'status' => 'boolean',
            'preferences' => 'array',
            'manasik_guide_completions_count' => 'integer',
        ];
    }

    /**
     * @return BelongsToMany<Dua, $this>
     */
    public function favoriteDuas(): BelongsToMany
    {
        return $this->belongsToMany(
            Dua::class,
            'manasik_hajj_user_dua_favorites',
            'manasik_hajj_user_id',
            'manasik_dua_id'
        )->withTimestamps();
    }

    /**
     * @return list<int>
     */
    public function favoriteDuaIds(): array
    {
        if (! Schema::hasTable('manasik_hajj_user_dua_favorites')) {
            return [];
        }

        return $this->favoriteDuas()
            ->pluck('manasik_duas.id')
            ->map(fn ($id): int => (int) $id)
            ->values()
            ->all();
    }

    /**
     * @return array{notify_prayer: bool, notify_hajj: bool, notify_news: bool, theme: string}
     */
    public function resolvedPreferences(): array
    {
        $p = is_array($this->preferences) ? $this->preferences : [];
        $theme = strtolower((string) ($p['theme'] ?? 'light'));

        return [
            'notify_prayer' => (bool) ($p['notify_prayer'] ?? true),
            'notify_hajj' => (bool) ($p['notify_hajj'] ?? true),
            'notify_news' => (bool) ($p['notify_news'] ?? false),
            'theme' => in_array($theme, ['light', 'dark'], true) ? $theme : 'light',
        ];
    }

    public function isActive(): bool
    {
        return (bool) $this->status;
    }
}
