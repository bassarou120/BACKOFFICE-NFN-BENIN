<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Commune extends Model
{
    use HasFactory;
    protected $fillable = [ 'adherants_count_genre'];
    protected $guarded=[];



    public function departement(): BelongsTo
    {
        return $this->belongsTo(Departement::class);
    }

    public function adherants():HasMany
    {
        return $this->hasMany(Adherant::class);
    }


    // Define a custom attribute for the count
    public function getAdherantMasculinCountAttribute()
    {
        return $this->adherants()->count();
    }


    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }


}
