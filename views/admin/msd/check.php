<?php 
session_start();

include_once __DIR__ .'../../../../src/Config/constants.php';
include_once __DIR__ . '../../../../vendor/autoload.php';

$type = trim($_GET['type'] ?? '');
$id = $_GET['contract_id'] ?? '';


if ( $type == TEMP_LIGHTING ) {

     // Code block for TEMP_LIGHTING look ID in temporary_lighting table
    echo $id = $_GET['contract_id'];

    header("location:TempLighting.php?id=$id");


} elseif ( $type == TRANS_RENT ){

    // Code block for TRANS RENT look up id in TRANSFORMER_RENTAL table

    echo $id = $_GET['contract_id'];

    header("location:TransRent.php?id=$id");

}
