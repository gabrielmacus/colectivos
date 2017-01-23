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

        $sql="SELECT p.*,l.* FROM lineas l LEFT JOIN lineas_paradas lp ON lp.linea=l.lineaId LEFT JOIN paradas p ON lp.parada=p.paradaId";
        if($id)
        {
            $sql.=" WHERE l.lineaId={$id}";
        }

        $lineas=array();

        if($res=$db->query($sql))
        {
            $res=$res->fetch_all(1);

            foreach ($res as $linea)
            {
                $lineaId=$linea["lineaId"];

                $lineas[$lineaId]["lineaNombre"]=$linea["lineaNombre"];
                $lineas[$lineaId]["lineaNumero"]=$linea["lineaNumero"];
                $lineas[$lineaId]["lineaDescripcion"]=$linea["lineaDescripcion"];
                $lineas[$lineaId]["lineaId"]=$lineaId;

                if($linea["paradaId"])
                {


                    $parada["reverse"]=$linea["paradaReverse"];
                    $parada["lat"]=$linea["paradaLat"];
                    $parada["lng"]=$linea["paradaLng"];
                    $parada["descripcion"]=$linea["paradaDescripcion"];
                    $parada["id"]=$linea["paradaId"];
                    $lineas[$lineaId]["paradas"][]=$parada;


                }


            }

            $lineas= array_values($lineas);

            $result["success"]=true;
            $result["data"]=$lineas;
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
            $sql="REPLACE INTO lineas SET ";

            foreach ($_POST as $k=>$v)
            { if(!is_array($v))
            {
                $sql.="{$k}='{$v}',";
                }
            }
            $sql=rtrim($sql,",");

            if($id)
            {
                $sql.=",lineaId={$id}";
            }

            $result["sql"]=$sql;
            if($res = $db->query($sql))
            {

                $sql="REPLACE INTO lineas_paradas ( `linea`, `parada`,parada_order) values ";

                $paradas=$_POST["paradas"];

                $i=1;
                $result["dat"]=$paradas;
                foreach ($paradas as $parada)
                {
                    $sql.=" ({$db->insert_id},{$parada},{$i}),";
                    $i++;
                }

                $sql=rtrim($sql,",");
                $result["sql"]=$sql;
                if($db->query($sql))
                {
                    $result["success"]=$db->insert_id;
                }
                else
                {
                    $result["error"]=$db->errno;
                }


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

        $sql ="DELETE FROM lineas WHERE lineaId={$id}";

        if($res = $db->query($sql))
        {
            $result["success"]=true;
        }
        else
        {
            $result["error"]=$db->errno;
        }

        break;
    case 'report':


        if(!file_put_contents("temp/loc_{$id}.json",json_encode($_POST)))
        {
            $result["error"]=true;
        }
        else
        {
            $result["success"]=true;
        }

        break;
    case 'track':


        if(!$data= json_decode(file_get_contents("temp/loc_{$id}.json"),true))
        {
            $result["error"]=true;

        }
        else
        {
            $result["success"]=true;
            $result["data"]=$data;
        }

        break;
}


echo json_encode($result);


