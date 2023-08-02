<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImmunizationRecord extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string, mixed>
     */
    protected $fillable = [
        'infant_id',
        'barangay_id',
        'vaccine_id',
        'dose_number',
        'immunization_date',
        'administered_by',
        'remarks',
    ];

    /**
     * Define the relationship between ImmunizationRecord and Infant.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function infant()
    {
        return $this->belongsTo(Infant::class);
    }

    /**
     * Define the relationship between ImmunizationRecord and Vaccine.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vaccine()
    {
        return $this->belongsTo(Vaccine::class);
    }

    /**
     * Define the relationship between ImmunizationRecord and Barangay.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function barangay()
    {
        return $this->belongsTo(Barangay::class);
    }

    /**
     * Define the relationship between ImmunizationRecord and User (Health Worker).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function administeredBy()
    {
        return $this->belongsTo(User::class, 'administered_by');
    }
}
