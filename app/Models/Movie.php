<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = ['title','description','director_id','category_id','cover_image'];

    public function director() {
        return $this->belongsTo(Director::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function actors() {
        return $this->belongsToMany(Actor::class);
    }
}
