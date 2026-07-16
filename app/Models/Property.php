<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\Attributes\Translatable;
use Spatie\Translatable\HasTranslations;

#[Fillable(['name', 'slug', 'address', 'is_active'])]
#[Translatable(['name'])]
#[Table('properties')]
class Property extends Model
{
    use HasFactory, HasTranslations;
    
    public function room(): HasMany
    {
        return $this->hasMany(Room::class);
    }
}
