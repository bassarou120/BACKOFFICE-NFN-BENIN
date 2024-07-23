<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    protected $guarded=[];

    // Define the constant
    const ROLE_ADMINISTRATOR = 'Administrateur';



    public function communes(): BelongsToMany
    {
        return $this->belongsToMany(Commune::class);
    }
}
