<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RastreabilidadeLote extends Model
{
    use HasFactory;

    protected $table = 'rastreabilidade_lotes';

    protected $fillable = [
        'produto_rastreavel_id',
        'lote',
        'data_producao',
        'responsavel',
        'nao_produzido',
        'user_id',
        'ultima_edicao_em',
        'ultima_edicao_por'
    ];

    protected $casts = [
        'data_producao' => 'date',
        'nao_produzido' => 'boolean',
        'ultima_edicao_em' => 'datetime'
    ];

    public function produtoRastreavel()
    {
        return $this->belongsTo(ProdutoRastreavel::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function ultimaEdicaoPor()
    {
        return $this->belongsTo(\App\Models\User::class, 'ultima_edicao_por');
    }

    public function ingredientes()
    {
        return $this->hasMany(RastreabilidadeIngrediente::class);
    }
}
