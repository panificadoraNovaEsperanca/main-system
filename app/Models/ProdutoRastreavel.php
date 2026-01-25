<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdutoRastreavel extends Model
{
    use HasFactory;

    protected $table = 'produtos_rastreaveis';

    protected $fillable = [
        'produto_id',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean'
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    public function insumos()
    {
        return $this->hasMany(ProdutoRastreavelInsumo::class)->orderBy('ordem');
    }

    public function lotes()
    {
        return $this->hasMany(RastreabilidadeLote::class);
    }
}
