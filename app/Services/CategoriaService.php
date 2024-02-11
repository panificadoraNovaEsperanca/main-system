<?php

namespace App\Services;

use App\Http\Requests\CategoriaRequest;
use App\Models\Categoria;
use App\Repositories\CategoriaRepository;
use Illuminate\Support\Facades\Storage;

class CategoriaService
{
    public static function store(CategoriaRequest $request, CategoriaRepository $categoriaRepository)
    {
        $imageLink = Storage::putFile('imagensCategoria', $request->url_capa);
        $request['imageLink'] =  ENV('APP_URL') . '/' . $imageLink;
        $categoriaRepository->store($request);
    }

    public static function update(CategoriaRequest $request, int $categoria_id, CategoriaRepository $categoriaRepository)
    {
        $categoria = Categoria::findOrFail($categoria_id);
        if (isset($request->url_capa)) {
            $nameOldFile = explode('/', $categoria->url_capa);
            if (Storage::disk('imagensCategoria')->exists(end($nameOldFile))) {
                Storage::disk('imagensCategoria')->delete(end($nameOldFile));
            }
            $imageLink = Storage::putFile('imagensCategoria', $request->url_capa);
            $request['imageLink'] =  ENV('APP_URL') . '/' . $imageLink;
        }

        $categoriaRepository->update($categoria, $request);
    }
}
