<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RastreabilidadeIngrediente extends Model
{
    use HasFactory;

    protected $table = 'rastreabilidade_ingredientes';

    protected $fillable = [
        'rastreabilidade_lote_id',
        'insumo_id',
        'marca',
        'lote_ingrediente',
        'validade_original',
        'data_abertura',
        'validade_apos_aberto',
        'caracteristica_sensorial'
    ];

    protected $casts = [
        'validade_original' => 'date',
        'data_abertura' => 'date',
        'validade_apos_aberto' => 'date'
    ];

    public function rastreabilidadeLote()
    {
        return $this->belongsTo(RastreabilidadeLote::class);
    }

    public function insumo()
    {
        return $this->belongsTo(Insumo::class);
    }
}
