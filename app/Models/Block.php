<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    protected $fillable = ['title', 'page_id', 'content', 'order'];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}