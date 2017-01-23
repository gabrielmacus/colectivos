<?php
$site="lineas";
$action="add";
$id =$_GET["id"];
if(!is_numeric($id))
{
    exit();
}

$urlSave="lineas-data.php?act=add&id={$id}";
$urlOnSave="lineas.php";
$isEdit=true;

require("/includes/autoload.php");
require("/includes/templates/estructura.php");


