<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Insumo extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];

    public function produtoRastreavelInsumos()
    {
        return $this->hasMany(ProdutoRastreavelInsumo::class);
    }

    public function rastreabilidadeIngredientes()
    {
        return $this->hasMany(RastreabilidadeIngrediente::class);
    }
}
