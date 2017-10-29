<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Excel;
use App\ExcelModel;

class ExcelReadController extends Controller
{

    public function __construct()
    {
        $this->ExcelModel = new ExcelModel();
    }

    public function excelReader(){

//        Excel::selectSheets('sheet1','LIFE MEMBERS')->load();
        $result = Excel::load(public_path().'/csvFiles/Member-till-April.xls', function($reader){})->get();

        $result->each(function($row)
        {
            //dd($row);
            //dd($row->first_name);

            $Number = trim(str_replace('-','',$row->phone));
            $Number = trim(str_replace('+','',$Number));
            $data = [
                'first_name' => $row->first_name,
                'last_name' => $row->last_name,
                'address' => $row->address,
                'phone' => $row->phone,
                'email' => $row->email,
            ];
            $this->ExcelModel->create($data);
        });
        echo '<h1> Updated Record </h1>';
    }




}
