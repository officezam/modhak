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
        $result = Excel::load(public_path().'/csvFiles/Final.xlsx', function($reader){})->get();

        $result->each(function($row)
        {
            //dd($row);
            //dd($row->first_name);

            $Number = trim(str_replace('-','',$row->phone));
            $Number = trim(str_replace('+','',$Number));
            $Number = trim(str_replace(' ','',$Number));
            echo $Number.'<br>';
            $data = [
                'first_name' => $row->first_name,
                'last_name' => $row->last_name,
                'address' => $row->address,
                'phone' => $Number,
                'email' => $row->email,
            ];
            $this->ExcelModel->create($data);
        });
        echo '<h1> Updated Record </h1>';
    }


    public function excelCeater(){

        $data = $this->ExcelModel->get();
        $EmailArr = array();

        foreach ($data as $value):
            $EmailArr[] = array($value->email);
        endforeach;

        $data = $EmailArr;

        Excel::create('Filename', function($excel) use($data) {

            $excel->sheet('Sheetname', function($sheet) use($data) {
                $sheet->fromArray($data);
            });

        })->export('csv');
        dd($EmailArr);
        $result = Excel::load(public_path().'/csvFiles/Outlook.csv', function($reader){})->get();
        $result->each(function($row)
        {
            $sheetData = $row->email;
            if (strpos($sheetData, '@') !== false) {
                $EmailArr[] = [$sheetData];
                $data = [
                    'first_name' => 'amir',
                    'last_name' => 'Shahzad',
                    'address' => '123',
                    'phone' => '12334',
                    'email' => $sheetData,
                ];
                $this->ExcelModel->create($data);
            }else{
                $ExtraDataArr[] = [$sheetData];
            }
        });
    }

}
