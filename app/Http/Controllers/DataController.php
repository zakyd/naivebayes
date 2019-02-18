<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataController extends Controller
{
    public function viewTable(Request $request)
    {
        if(isset($request->file)){
            $file = $request->file('file');
            $data['csv'] = array_map('str_getcsv', file($file));
            // dd($data['csv']);
            return view('viewtable', $data);
        }else{
            redirect('/');
        }
    }

    public function calculate(Request $request)
    {
        $input = ($request->input);
        $csv = ($request->csv);
        $target = ($request->target);
        $class = array();
        $p = array();

        //define class
        for ($i = 1; $i < sizeof($csv); $i++) { 
            if (!in_array($csv[$i][$target["index"]],$class)) {
                array_push($class,$csv[$i][$target["index"]]);
            }
        }

        for ($i = 0; $i  < sizeof($input); $i ++) { 
            if ($input[$i] != null && $i != $target["index"]) {
                for ($j = 0; $j  < sizeof($class); $j ++) { 
                    $Xk = 0;
                    $Ci = 0;
                    for ($k = 1; $k  < sizeof($csv); $k ++) { 
                        if ($class[$j]==$csv[$k][$target["index"]]) {
                            $Ci++;
                            if ($input[$i]==$csv[$k][$i]) {
                                $Xk++;
                            }
                        }
                    }
                    // [target][parameter]
                    $p[$target["value"]."=".$class[$j]][$csv[0][$i]."=".$input[$i]] = $Xk/$Ci;
                }
            }
        }
        $P = array();
        foreach ($class as $c) {
            $mult = array_product($p[$target["value"]."=".$c]);
            $val = 0;
            for ($i = 1; $i  < sizeof($csv); $i ++) { 
                if ($c == $csv[$i][$target["index"]]) {
                    $val++;
                }
            }
            $P[$target["value"]."=".$c] = $mult*$val/(sizeof($csv)-1);
        }

        $value = array_search(max($P), $P);

        $data = (object) array();
        $data->p = $p;
        $data->P = $P;
        $data->value = $value;

        echo json_encode($data);
    }
}
