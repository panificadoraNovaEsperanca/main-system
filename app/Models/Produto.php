<?php

namespace App\Models;

use App\Enums\TipoLancamento;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produto extends Model
{
    use SoftDeletes;
    protected $casts = [
        'informacao_nutricional' => 'array'
    ];
    protected $fillable = [
        "nome",
        "unidade_medida",
        "preco_custo",
        "preco_venda",
        "categoria_id",
        "marca_id",
        "fornecedor_id",
        "descricao",
        "informacao_nutricional",
        "created_by",
    ];
    use HasFactory;

    public function responsavel()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function fornecedor()
    {
        return $this->hasOne(Fornecedor::class, 'id', 'fornecedor_id')->withTrashed();
    }
    public function marca()
    {
        return $this->hasOne(Marca::class, 'id', 'marca_id')->withTrashed();
    }

    public function categoria()
    {
        return $this->hasOne(Categoria::class, 'id', 'categoria_id')->withTrashed();
    }

    public function scopeIndex($query)
    {
        return $query->with(['fornecedor', 'marca', 'responsavel'])
            ->orderBy('id')
            ->when(request()->has('search') && request()->search != '', function ($query) {
                $request = request()->all();
                return $query->where('nome', 'like', '%' . $request['search'] . '%')
                    ->orWhere('descricao', 'like', '%' . $request['search'] . '%')
                    ->orWhereHas('responsavel', function ($query2) use ($request) {
                        $query2->where('nome', 'like', '%' . $request['search'] . '%');
                    })->orWhereHas('categoria', function ($query3) use ($request) {
                        $query3->where('nome', 'like', '%' . $request['search'] . '%');
                    });
            })
            ->withTrashed()
            ->paginate(request()->paginacao ?? 10);
    }

    public function scopeIndexHome($query, int $categoria_id)
    {
        $a = $query->with(['categoria'])
        ->when(request()->has('search'), function ($query) {
            return  $query->where('nome', 'like', '%' . request()->search . '%');
        })->where('categoria_id','=', $categoria_id)->get();
        return $a;
    }
}
