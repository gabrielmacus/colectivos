<!doctype html>
<html lang="es">
<head>
 <?php include("includes/templates/header.php"); ?>
</head>
<style>
  
</style>
<body data-ng-app="app" data-ng-controller="ctrl">
   <div class="main-container">

       <?php

       include ("includes/templates/{$site}/{$action}.php");
      //echo $mustache->render(readTemplate("{$site}/{$action}.php"),$dataToSkin);
    ?>
   </div>
</body>
</html>