<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoleCommune extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function commune(): BelongsTo
    {
        return $this->belongsTo(Commune::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
