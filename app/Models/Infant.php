<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Infant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string, mixed>
     */
    protected $fillable = [
        'name',
        'sex',
        'birth_date',
        'family_serial_number',
        'barangay_id',
        'weight',
        'length',
        'father_name',
        'mother_name',
        'contact_number',
        'complete_address',
    ];

    /**
     * Define the relationship between Infant and Barangay.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function barangay()
    {
        return $this->belongsTo(Barangay::class);
    }

    /**
     * Define the relationship between Infant and ImmunizationRecords.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function immunizationRecords()
    {
        return $this->hasMany(ImmunizationRecord::class, 'infant_id');
    }
}
