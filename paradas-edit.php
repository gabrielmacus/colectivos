<?php
$site="paradas";
$action="add";
$id =$_GET["id"];
if(!is_numeric($id))
{
    exit();
}

$urlSave="paradas-data.php?act=add&id={$id}";
$urlOnSave="paradas.php";
$isEdit=true;

require("/includes/autoload.php");
require("/includes/templates/estructura.php");


