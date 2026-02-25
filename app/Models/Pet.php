<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'name',
        'species',
        'breed',
        'birth_date',
        'gender',
        'color',
        'weight',
        'medical_notes',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'weight' => 'decimal:2',
    ];

    /**
     * Get the client that owns the pet.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the age of the pet in years and months.
     */
    public function getAgeAttribute(): string
    {
        if (!$this->birth_date) {
            return 'No especificada';
        }

        $birthDate = Carbon::parse($this->birth_date);
        $now = Carbon::now();
        
        // Calcular edad total en meses: 1 año = 12 meses
        $totalMonths = $birthDate->diffInMonths($now);
        $years = intdiv($totalMonths, 12);
        $months = $totalMonths % 12;

        if ($totalMonths === 0) {
            return 'Recién nacido';
        }

        if ($years === 0) {
            return $months === 1 ? '1 mes' : "$months meses";
        }

        if ($months === 0) {
            return $years === 1 ? '1 año' : "$years años";
        }

        $yearsText = $years === 1 ? '1 año' : "$years años";
        $monthsText = $months === 1 ? '1 mes' : "$months meses";
        return "$yearsText y $monthsText";
    }
}
