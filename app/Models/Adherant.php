<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Adherant extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function departement(): BelongsTo
    {
        return $this->belongsTo(Departement::class);
    }


    public function commune(): BelongsTo
    {
        return $this->belongsTo(Commune::class);
    }

    public function arrondissement(): BelongsTo
    {
        return $this->belongsTo(Arrondissement::class);
    }

    public function quartier(): BelongsTo
    {
        return $this->belongsTo(Quartier::class);
    }


    public function activate(){


    }




}
