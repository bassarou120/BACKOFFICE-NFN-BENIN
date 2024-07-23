<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quartier extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function arrondissement(): BelongsTo
    {
        return $this->belongsTo(Arrondissement::class);
    }





}
