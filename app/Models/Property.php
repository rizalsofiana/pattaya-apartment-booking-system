<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\Attributes\Translatable;

#[Fillable(['name', 'slug', 'address', 'is_active'])]
#[Translatable(['name'])]
#[Table('properties')]
class Property extends Model
{
    public function room(): HasMany
    {
        return $this->hasMany(Room::class);
    }
}
