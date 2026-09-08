<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'full_name', 'username', 'email', 'phone', 'password',
        'role_id', 'status', 'profile_image', 'must_change_password',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'must_change_password' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function isOwner(): bool
    {
        return $this->role?->name === 'Owner';
    }

    public function isCashier(): bool
    {
        return $this->role?->name === 'Cashier';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function getAuthIdentifierName(): string
    {
        return 'id';
    }
}
