<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaccine extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string, mixed>
     */
    protected $fillable = [
        'name',
        'short_name',
    ];

    /**
     * Define the relationship between Vaccine and VaccineDoses.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vaccineDoses()
    {
        return $this->hasMany(VaccineDose::class, 'vaccine_id');
    }
}
