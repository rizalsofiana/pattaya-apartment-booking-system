<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\Attributes\Translatable;
use Spatie\Translatable\HasTranslations;

#[Fillable(['name', 'icon_class'])]
#[Translatable(['name'])]
#[Table('amenities')]
class Amenity extends Model
{
    use HasFactory, HasTranslations;

    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'room_amenity');
    }
}
