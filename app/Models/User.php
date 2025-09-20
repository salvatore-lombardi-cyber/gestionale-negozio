<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'avatar',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Verifica se l'utente ha un ruolo specifico
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role || $this->role === 'super_admin';
    }

    /**
     * Verifica se l'utente ha uno dei ruoli specificati
     */
    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles) || $this->role === 'super_admin';
    }

    /**
     * Verifica se l'utente è amministratore
     */
    public function isAdmin(): bool
    {
        return $this->hasAnyRole(['admin', 'super_admin']);
    }

    /**
     * Verifica se l'utente può configurare il sistema
     */
    public function canConfigure(): bool
    {
        return $this->hasAnyRole(['admin', 'configuratore', 'super_admin']);
    }
}
