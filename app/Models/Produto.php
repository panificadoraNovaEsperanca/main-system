<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produto extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];

    protected $casts = [
        'precos' => 'json',
    ];

    public function categoria(){
        return $this->hasOne(Categoria::class,'id','categoria_id');
    }
}
