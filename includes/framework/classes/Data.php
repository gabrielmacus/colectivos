<?php

abstract class Data
{

    function __construct($data,$config,$db)
    {
        try
        {



            $id= $config["id"];

            $act = $config["act"]  ;
           // $type=$config["type"]  ;


            $this->result["success"]=false;
            $this->result["error"]=false;


            if(( (!isset($id) ||empty($id)) && $act!='list' && $act!='add' ) || (!isset($act) ||empty($act) )/*||(!isset($type) ||empty($type))*/)
            {

                $this->result["error"]=true;




            }
            else
            {


               

                if($this->result["info"]=$this->isInvalidData($data))
               {
            
                $this->result["error"]=true;



                //hay error

               }
                else
                {

                    $collection =$db->selectCollection(strtolower(get_class($this)));

                    switch($act)
                    {
                        case 'add':

                            if($opResult=$collection->insert($data))
                            {
                                $this->result["success"]=true;
                            }
                            else
                            {
                                $this->result["error"]=true;
                            }

                            break;
                        case 'edit':

                            if($opResult=$collection->update(array("_id"=>new MongoId($id)),array('$set' => $data)))
                            {
                                $this->result["success"]=true;
                            }
                            else
                            {
                                $this->result["error"]=true;


                            }

                            break;

                        case 'delete':
                            if($opResult=$collection->remove(array("_id"=>new MongoId($id))))
                            {
                                $this->result["success"]=true;
                            }
                            else
                            {
                                $this->result["error"]=true;

                            }


                            break;

                        case 'list':



                            $opResult= ($id)?$collection->find(array("_id"=>new MongoId($id))):$collection->find(array());



                            if($opResult)
                            {
                                $this->result["success"]=true;

                                $this->result["data"]=iterator_to_array($opResult,false);
                            }
                            else
                            {
                                $this->result["error"]=true;
                            }




                            break;

                    }


                    $this->result["info"]=$opResult;

                }



            }





        }
        catch (Exception $e)
        {
            $this->result["error"]=true;
            $this->result["info"]=array("err"=>$e->getCode(),"errmsg"=>$e->getMessage());
        }


    }

    function  isInvalidData($data)
    {
        return false;

    }


    public function getObject() {
    return $this->result;
  }
}



