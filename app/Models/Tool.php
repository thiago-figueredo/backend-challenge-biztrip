<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Tool extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $timestamps = false;
    public $incrementing = true;
    protected $fillable = [
        "title",
        "link",
        "description",
        "tags"
    ];

    protected function getTagsAttribute(string $tags)
    {
        return json_decode($tags);
    }

    protected function setTagsAttribute(array $tags)
    {
        $this->attributes["tags"] = json_encode($tags);
    }
}
