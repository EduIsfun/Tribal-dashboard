<?php
function scrub_input($input){
	$input = trim($input);
	$input = preg_replace('/\s+/', ' ', $input); // remove multiple spaces
	$input = str_replace("“", "\"", $input); // dumb any smartquotes
	$input = str_replace("”", "\"", $input); // dumb any smartquotes
	$input = str_replace("(", " ( ", $input); // isolate parenthesis 
	$input = str_replace(")", " ) ", $input); // isolate parenthesis 
	$input = str_replace("\"", " \" ", $input); // isolate quotes
	$input = preg_replace("|<script>|", "", $input);
	$input = preg_replace("|</script>|", "", $input);
	$input = preg_replace("|<style>|", "", $input);
	$input = preg_replace("|</style>|", "", $input);
	$input = preg_replace("|<html>|", "", $input);
	$input = preg_replace("|</html>|", "", $input);
	$input = preg_replace("|<head>|", "", $input);
	$input = preg_replace("|</head>|", "", $input);
	$input = preg_replace("|\'|", "'", $input);
	return $input;
}
?>