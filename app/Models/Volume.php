<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

// use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;

class Volume extends Model
{
    use HasFactory;

    // protected $table = 'volume';
    protected $collection = 'volumes';

    protected $guarded = [];

    // protected $fillable = ['exercicio', 'musculo', 'residual', 'series'];
    // protected $dates = ['data'];

    protected $fillable = ['exerc_id', 'exercicio', 'musculo', 'residual', 'series', 'data', 'user_id'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
