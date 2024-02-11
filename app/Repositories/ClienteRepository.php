<?php
namespace App\Repositories;

use App\Models\Cliente;

class ClienteRepository{
    private Cliente $clientes;

    public function __construct(Cliente $clientes)
    {
        $this->clientes = $clientes;
    }

    public function getAll(){
        return $this->clientes->withTrashed()->paginate(request()->paginacao ?? 10);
    }
}