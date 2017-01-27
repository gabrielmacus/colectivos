<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 25/01/2017
 * Time: 02:15 AM
 */
?>
<style>
    
    a{
        text-decoration: none;
    }
    .paradas,.lineas
    {
        -webkit-transition: ease-out 500ms;
        -moz-transition: ease-out 500ms;
        -ms-transition: ease-out 500ms;
        -o-transition: ease-out 500ms;
        transition: ease-out 500ms;
    }
    
    .paradas:hover,.lineas:hover
    {
        -webkit-transform: scale(1.2);
        -moz-transform: scale(1.2);
        -ms-transform: scale(1.2);
        -o-transform: scale(1.2);
        transform: scale(1.2);
    }
    .paradas h2,.lineas h2
    {
        -webkit-transition: ease-out 500ms;
        -moz-transition: ease-out 500ms;
        -ms-transition: ease-out 500ms;
        -o-transition: ease-out 500ms;
        transition: ease-out 500ms;
        position: relative;
        bottom: -80px;
        z-index: -10;
        opacity: 0;
        font-weight: bold;
    }


    .paradas:hover h2,.lineas:hover h2
    {
        bottom: 0px;
        color: #3a3a3a;
        font-size: 40px;
        opacity: 1;
    }

</style>

<div style="display: flex; flex-direction: column;justify-content: center;height: 100%" >
    <div class="w3-row-padding" style="position: relative;bottom: 50px;">

        <a  class="w3-col s12 m6 paradas w3-center"  href="paradas.php">
            <h2>Paradas</h2>
            <img style="width:60%" src="http://www.stapgef.org/sites/default/files/icons/location-icon.png">
        </a>


        <a class="w3-col s12 m6 lineas w3-center "  href="lineas.php">

            <h2>Lineas</h2>
                <img style="width:60%" src="icons/School-Bus.png">




        </a>
    </div>

</div>


