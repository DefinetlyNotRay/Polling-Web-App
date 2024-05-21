<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Division;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'password',
        'role',
        'api_token ',
        'token_expiry',
        'division_id'
    ];
    public function division()
    {
        return $this->belongsTo(Division::class);
    }
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
    public function choices()
    {
        return $this->hasMany(Choice::class);
    }

      /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'token_expiry' => 'datetime', // Cast token_expiry to datetime
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
}