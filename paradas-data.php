<?php
/**
 * Created by PhpStorm.
 * User: Luis Garcia
 * Date: 16/01/2017
 * Time: 01:24 AM
 */

require("/includes/autoload.php");

function validateData($data)
{
    return true;
}
$result["error"]=false;
$result["success"]=false;
$act=$_GET["act"];
$id=$_GET["id"];

    switch ($act)
    {
        case 'list':

            $sql="SELECT p.*,l.* FROM paradas p LEFT JOIN lineas_paradas lp ON lp.parada=p.paradaId LEFT JOIN lineas l ON lp.linea=l.lineaId";
            if($id)
            {
                $sql.=" WHERE p.paradaId={$id}";
            }

            $paradas=array();

            if($res=$db->query($sql))
            {
                $res=$res->fetch_all(1);

                foreach ($res as $parada)
                {
                    $paradaId=$parada["paradaId"];

                    $paradas[$paradaId]["paradaLat"]=$parada["paradaLat"];
                    $paradas[$paradaId]["paradaLng"]=$parada["paradaLng"];
                    $paradas[$paradaId]["paradaReverse"]=$parada["paradaReverse"];
                    $paradas[$paradaId]["paradaDescripcion"]=$parada["paradaDescripcion"];
                    $paradas[$paradaId]["paradaId"]=$paradaId;

                    if($parada["lineaId"])
                    {
                        $linea["nombre"]=$parada["lineaNombre"];
                        $linea["numero"]=$parada["lineaNumero"];
                        $linea["descripcion"]=$parada["lineaDescripcion"];
                        $linea["id"]=$parada["lineaId"];
                        $paradas[$paradaId]["lineas"][]=$linea;
                    }


                }

                $paradas= array_values($paradas);
                $result["success"]=true;
                $result["data"]=$paradas;
            }
            else
            {
                $result["error"]=$db->errno;
            }



            break;

        case 'add':

       $validateData=validateData($_POST) ;
        if($validateData===true)
        {
            $sql="REPLACE INTO paradas SET ";

            foreach ($_POST as $k=>$v)
            {
                if(!is_array($v))
                {
                    $sql.="{$k}='{$v}',";
                }

            }
            $sql=rtrim($sql,",");

            if($id)
            {
                $sql.=",paradaId={$id}";
            }

            $result["sql"]=$sql;
            if($res = $db->query($sql))
            {

                $result["success"]=$db->insert_id;

                /*
                 * Descomentar si quiero poder asociar lineas a las paradas
                 *
                $sql="REPLACE INTO lineas_paradas ( `linea`, `parada`) values ";

                foreach ($_POST["lineas"] as $linea)
                {
                    $sql.=" ({$linea},{$db->insert_id}),";
                }

                $sql=rtrim($sql,",");


                $result["sql"]=$sql;
                if($res=$db->query($sql))
                {
                    $result["success"]=$db->insert_id;
                }
                else
                {
                    $result["error"]=$db->errno;
                }
                */


            }
            else
            {
                $result["error"]=$db->errno;
            }







        }
        else
        {
            $result["error"]=$validateData;
        }

            break;

        case 'del':

            $sql ="DELETE FROM paradas WHERE paradaId={$id}";

            if($res = $db->query($sql))
            {
                $result["success"]=true;
            }
            else
            {
                $result["error"]=$db->errno;
            }

            break;
    }


echo json_encode($result);


