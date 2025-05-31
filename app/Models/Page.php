<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = ['title', 'slug', 'seo'];

    public function blocks()
    {
        return $this->hasMany(Block::class)->orderBy('order');
    }
}