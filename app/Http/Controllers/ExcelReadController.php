<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel;
use App\ExcelModel;

class ExcelReadController extends Controller
{

    public function __construct()
    {
        $this->ExcelModel = new ExcelModel();
    }

    public function excelReader(){

        $result = \Excel::load(base_path().'/documents/Final-List.xlsx', function($reader){})->get();
        $result->each(function($row)
        {
            $Number = trim(str_replace('-','',$row->phone));
            $Number = trim(str_replace('+','',$Number));
            $data = [
                'first_name' => $row->first_name,
                'last_name' => $row->last_name,
                'address' => $row->address,
                'phone' => $Number,
                'email' => $row->email,
            ];
            $this->ExcelModel->create($data);
        });
    }




}
