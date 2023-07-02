<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class Submissions implements ToCollection, WithHeadingRow
{
    public function collection(Collection $collection)
    {
        //
    }
}
