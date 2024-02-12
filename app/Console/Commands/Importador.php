<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class Importador implements ToModel
{

    public function model(array $row)
    {
        return $row;
    }
}