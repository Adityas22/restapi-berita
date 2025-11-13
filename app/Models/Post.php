<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    //
    use HasFactory;
    use SoftDeletes;
    // protected $table = 'posts';

    protected $fillable = [
        'title',
        'content',
        'author',
        'image'
    ];

    public function writer():BelongsTo
    {
        return $this->belongsTo(User::class, 'author', 'id');
    }

    public function comments():HasMany
    {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }
}