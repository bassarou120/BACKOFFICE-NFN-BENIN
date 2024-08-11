<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function users():HasMany
    {

        return $this->hasMany(User::class);
    }
}
