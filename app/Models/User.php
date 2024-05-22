<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Division;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

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
        'api_token ',
        'token_expiry',
        'role',
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
    // protected $attributes = [
    //     // Set default values to null or some other constant
    //     'token_expiry' => null,
    //     'api_token' => null
    // ];

    // // Constructor method
    // public function __construct(array $attributes = [])
    // {
    //     parent::__construct($attributes);

    //     // Set dynamic default values
    //     $this->attributes['token_expiry'] = now()->addHours(24);
    //     $this->attributes['api_token'] = Str::random(60);
    // }

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