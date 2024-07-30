<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamImage extends Model
{
    protected $table = 'teamimages';

    protected $fillable = [
        'team_id',
        'source',
    ];
}
