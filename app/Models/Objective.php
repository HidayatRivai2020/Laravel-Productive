<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objective extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'content_id',
        'description',
        'image',
        'status',
    ];

    /**
     * Objective belongs to a Content.
     */
    public function content()
    {
        return $this->belongsTo(Content::class, 'content_id', 'id');
    }
}
