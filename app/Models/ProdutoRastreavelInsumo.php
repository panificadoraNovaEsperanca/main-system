<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdutoRastreavelInsumo extends Model
{
    use HasFactory;

    protected $table = 'produto_rastreavel_insumos';

    protected $fillable = [
        'produto_rastreavel_id',
        'insumo_id',
        'ordem'
    ];

    public function produtoRastreavel()
    {
        return $this->belongsTo(ProdutoRastreavel::class);
    }

    public function insumo()
    {
        return $this->belongsTo(Insumo::class);
    }
}
