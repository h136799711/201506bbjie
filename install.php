<?php
$n = 1;
$total = 129.0;
$value = 100.0;

$E = 0;

while($n < $value){
    $E = ($n/$total) * ($value - $n) + (1 - $n/$total) * ($n);
    echo "n= ".$n.' e='.$E.'<br/>';
    $n++;
}

$cost = 30;
$get = 100;
$probability = 30.0;
$total = 129;
$my_number = array();



$test  = 1;
$total = 5;
$get_cnt =0 ;

$total_cost = $total * $cost;
$total_get = 0;

while($test < $total){
    $my_number = array();
    $tmp = 1;
    while($probability > $tmp){
        array_push($my_number,mt_rand(1,129));
        $tmp ++;
    }

    $first = mt_rand(1,129);

    if(in_array($first,$my_number)){
        $get_cnt++;
        echo "<br/>中奖号码".$first.",你中奖了!<BR/>";
        $total_get += 100;
    }


    $test ++;
}

echo '<BR/>总中奖'.($get_cnt).'次数<br/>';
echo '<BR/>总花费'.($total_cost).'<br/>';
echo '<BR/>总获得'.($total_get).'<br/>';


