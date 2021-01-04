<?php

namespace App\Study;
use \App\SQL;

class Unit Extends SQL  {

    Public $id;
    Public $CODE;
    Public $LABEL;
    Public $TYPE;

    Public $UnitList;

    public function GETPrestation($id_GET){

        $Unit = $this->GetQuery('SELECT '. TABLE_ERP_UNIT .'.id,
                                                '. TABLE_ERP_UNIT .'.CODE,
                                                '. TABLE_ERP_UNIT .'.LABEL,
                                                '. TABLE_ERP_UNIT .'.TYPE
                                             FROM `'. TABLE_ERP_PRESTATION .'`
								              WHERE id=\''. $id_GET .'\'', true, 'App\Study\Unit');
        return $Unit;
    }

    public function GetUnitList($IdData=0, $Select = true){

          $query='SELECT '. TABLE_ERP_UNIT .'.id,
                            '. TABLE_ERP_UNIT .'.CODE,
                            '. TABLE_ERP_UNIT .'.LABEL,
                            '. TABLE_ERP_UNIT .'.TYPE
                            FROM `'. TABLE_ERP_UNIT .'`
                            ORDER BY TYPE';
         if($Select){
		     foreach ($this->GetQuery($query) as $data){
                 $this->UnitList .='<option value="'. $data->id .'" '. selected($IdData, $data->id) .'>'. $data->LABEL .'</option>';
             }
              return  $this->UnitList;
        }else {
            return  $this->GetQuery($query);
        }
    }
}
