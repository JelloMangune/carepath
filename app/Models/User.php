<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'user_type',
        'barangay_id', // Add the 'barangay' attribute to the fillable array
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
        'email_verified_at' => 'datetime',
        'user_type' => 'int', // Cast the 'user_type' attribute to integer
    ];

    /**
     * Define the relationship between User and Barangay.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function barangay()
    {
        return $this->belongsTo(Barangay::class);
    }

    /**
     * Define the relationship between User and ImmunizationRecords.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function immunizationRecords()
    {
        return $this->hasMany(ImmunizationRecord::class, 'administered_by');
    }
}
