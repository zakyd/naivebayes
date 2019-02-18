@extends('template')

@section('title')
Naive Bayes
@endsection

@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">    
<link rel="stylesheet" href="\css\table.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

@endsection

@section('body')
<h1>
    Tabel Data Train
</h1>
<div style="margin-left:10%;margin-right: 10%;">
    <button onclick="window.location.href='/'">Kembali</button>
    <button onclick="formKlasifikasi()" style="float:right">Klasifikasi</button>
</div>
<div id="formKlasifikasi" class="formKlasifikasi" style="display:none">
    <h2>
        Form Klasifikasi
    </h2>
    <div class="tabelInput">
        {{-- <form action="/klasifikasi" method="POST"> --}}
            {{-- <input type="hidden" name="csv" value="{{ json_encode($csv) }}"/> --}}
            <table class="form">
                <tr>
                    <th>
                        Atribut
                    </th>
                    <th>
                        Value
                    </th>
                    <th>
                        Parameter
                    </th>
                    <th>
                        Target
                    </th>
                </tr>
            @php
                $params = $csv[0];
                foreach ($params as $p) {
                    echo "<tr>";
                    echo "<td>$p</td>";
                    echo "<td><input id='v$p' name='v$p' type='text'></td>";
                    echo "<td><input id='p$p' name='p$p' type='checkbox' checked></td>";
                    echo "<td><input id='t$p' name='target' type='radio' value='$p' checked></td>";
                    echo "</tr>";
                }
            @endphp
            </table>
        {{-- </form> --}}
    </div>
    <br>
    <button onclick="reset()">Reset</button>
    <button onclick="klasifikasi()" style="float:right">Klasifikasi</button>
    <div id= "value" class="value">
        <p id="textValue">none</p>
    </div>
</div>
<div class="centered">
    <table class="table">
        @php
        $index1=0;
        foreach ($csv as $row) {
            if ($index1==0) {
                echo "<tr>";
                foreach ($row as $col) {
                    echo "<th>".$col."</th>";
                }
                echo "</tr>";
            }else{
                echo "<tr>";
                foreach ($row as $col) {
                    echo "<td>".$col."</td>";
                }
                echo "</tr>";
            }
            $index1++;
        }
        @endphp
    </table>
</div>
<script>
    function formKlasifikasi() {
        var x = document.getElementById("formKlasifikasi");
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }
    function klasifikasi() {
        var theTarget = null;
        var iTarget = null;
        @php
            $params = $csv[0];
            echo "var array = new Array(".(sizeof($csv)-1).").fill(0);";
            echo "var urut = new Array(".(sizeof($csv)-1).");";
            echo "var input = new Array(".sizeof($params).");";
            echo "var csv = ".json_encode($csv).";";
            $index = 0;
            foreach($params as $p){
                echo "if(document.getElementById('p".$p."').checked){input[".$index."] = document.getElementById('v".$p."').value;}else{input[".$index."] = null;}";
                echo "if(document.getElementById('t".$p."').checked){theTarget=document.getElementById('t".$p."').value;iTarget=".$index.";}";
                $index++;
            }
        @endphp
        
        $.ajax({
            type: 'POST',
            url: "/calculate",
            data: {
                _token: '{{csrf_token()}}',
                csv : csv,
                input : input,
                target : {
                    "index" : iTarget,
                    "value" : theTarget
                }
            },
            success:function(data) {
                var d = JSON.parse(data);
                console.log(d.p);
                console.log(d.P);
                console.log(d.value);
                document.getElementById("textValue").innerHTML = d.value;
                document.getElementById("value").style.display = "block";
            }
        });

    }
    function reset() {
        @php
            $params = $csv[0];
            foreach($params as $p){
                echo "document.getElementById('v".$p."').value='';";
                echo "document.getElementById('p".$p."').checked=true;";
            }
        @endphp
        document.getElementById("value").style.display = "none";
    }
</script>
@endsection
