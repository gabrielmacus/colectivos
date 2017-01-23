<?php
/**
 * Created by PhpStorm.
 * User: Gabriel
 * Date: 04/01/2017
 * Time: 04:49 PM
 */

function readTemplate($name)
{
    return file_get_contents("includes/templates/".$name);
}