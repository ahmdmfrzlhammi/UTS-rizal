<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    public $appends = ['picture_url'];
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getPictureUrlAttribute()
    {
        return $this->image? asset('storage/' . $this->image) : null;

}
}