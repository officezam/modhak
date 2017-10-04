<?php
session_start(); 
require_once ('db.php');
require_once ('helper_func.php');

$conn = connect($config);
if ( ! $conn ) die('Couldn\'t connected to db!');