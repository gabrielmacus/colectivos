<?php


/**
 * Created by PhpStorm.
 * User: Gabriel
 * Date: 04/01/2017
 * Time: 03:28 PM
 */
class Usuario extends Data
{
     
    function __construct($data, $config, $db)
    {
        parent::__construct($data, $config, $db);
    }

    function isInvalidData($data)
    {

        $parametrosObligatorios =array("nombre","apellido");

        $ret = array();


        foreach ($data as $clave=>$valor)
        {

            if(in_array($clave,$parametrosObligatorios) &&(!isset($valor)||empty($valor)) )
            {
                $ret['info'][]="{$clave}";

            } else
            {

                switch ($clave) {
                    case 'nombre':
                    case 'apellido':
                        if (strlen($valor) > 60) {
                            $ret['info'][] = $clave;
                        }


                        break;
                }
                }
            }

        if ( isset($ret["info"]) )
        {
            return $ret['info'];
        }


        return false;
    }
}