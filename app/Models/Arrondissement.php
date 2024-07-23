<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Arrondissement extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function commune(): BelongsTo
    {
        return $this->belongsTo(Commune::class);
    }
}
