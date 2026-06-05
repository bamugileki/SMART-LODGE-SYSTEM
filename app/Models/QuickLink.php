<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuickLink extends Model
{
    protected $fillable = [
        'label', 'url', 'icon', 'group', 'section', 'roles', 'order', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSection($query, string $section)
    {
        return $query->where('section', $section)->active()->orderBy('order');
    }

    public function scopeForRoles($query, ?array $roles)
    {
        if (empty($roles)) {
            return $query->whereNull('roles');
        }
        return $query->where(function ($q) use ($roles) {
            $q->whereNull('roles');
            foreach ($roles as $role) {
                $q->orWhere('roles', 'like', "%{$role}%");
            }
        });
    }

    public static function getLinks(string $section, ?array $roles = null)
    {
        $query = static::section($section);
        if ($roles !== null) {
            $query->forRoles($roles);
        }
        return $query->get();
    }
}
