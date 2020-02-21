<?php


$a = '1';
$b = &$a;
echo $b = "2$b";
//echo $a;
//.", ".$b;
?>