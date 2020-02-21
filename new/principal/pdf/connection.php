<?php
include("configs.php");
global $config;

$config['con'] = mysqli_connect($config['WEB_DB']['HOST'],$config['WEB_DB']['USERNAME'],$config['WEB_DB']['PASSWORD'],$config['WEB_DB']['DATABASE']) or die('Unable To connect');



?>