<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Volume extends Model
{
    use HasFactory;

    protected $table = 'volume';

    protected $guarded = [];

    protected $fillable = ['exercicio', 'musculo', 'residual', 'series'];

    protected $dates = ['data'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
