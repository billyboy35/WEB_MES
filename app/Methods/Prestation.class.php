<?php

namespace App\Methods;
use \App\SQL;

class Prestation Extends SQL  {

    Public $id;
    Public $CODE;
    Public $ORDRE;
    Public $LABEL;
    Public $TYPE;
    Public $TAUX_H;
    Public $MARGE;
    Public $COLOR;
    Public $IMAGE;
    Public $PROVIDER_ID;

    Public $PrestationList;

    public function GETPrestation($id_GET_PRESTATION){

        $Prestation = $this->GetQuery('SELECT '. TABLE_ERP_PRESTATION .'.id,
                                                '. TABLE_ERP_PRESTATION .'.CODE,
                                                '. TABLE_ERP_PRESTATION .'.ORDRE,
                                                '. TABLE_ERP_PRESTATION .'.LABEL,
                                                '. TABLE_ERP_PRESTATION .'.TYPE,
                                                '. TABLE_ERP_PRESTATION .'.TAUX_H,
                                                '. TABLE_ERP_PRESTATION .'.MARGE,
                                                '. TABLE_ERP_PRESTATION .'.COLOR,
                                                '. TABLE_ERP_PRESTATION .'.IMAGE,
                                                '. TABLE_ERP_PRESTATION .'.PROVIDER_ID
                                             FROM `'. TABLE_ERP_PRESTATION .'`
								              WHERE id=\''. $id_GET_PRESTATION .'\'', true, 'App\Methods\Prestation');
        return $Prestation;
    }

    public function GetPrestationList($IdData=0, $Select = true, $AllData = "all"){

        $SQLClause ='';
        if($AllData == "productive"){
            $SQLClause ='WHERE TYPE IN ( 1, 7)';
        }
        elseif($AllData == "material"){
            $SQLClause ='WHERE TYPE IN ( 2, 3, 4, 5, 6, 8)';
        }

        $this->PrestationList ='';
        $query='SELECT '. TABLE_ERP_PRESTATION .'.Id,
									'. TABLE_ERP_PRESTATION .'.CODE,
									'. TABLE_ERP_PRESTATION .'.ORDRE,
									'. TABLE_ERP_PRESTATION .'.LABEL,
									'. TABLE_ERP_PRESTATION .'.TYPE,
									'. TABLE_ERP_PRESTATION .'.TAUX_H,
									'. TABLE_ERP_PRESTATION .'.MARGE,
									'. TABLE_ERP_PRESTATION .'.COLOR,
									'. TABLE_ERP_PRESTATION .'.IMAGE
									FROM `'. TABLE_ERP_PRESTATION .'`
                                    '. $SQLClause  .'
                                    ORDER BY ORDRE';
                                    
        if($Select){
            foreach ($this->GetQuery($query) as $data){
            
                $this->PrestationList .='<option value="'. $data->Id .'-'. $data->TYPE .'" '. selected($IdData, $data->Id) .'>'. $data->LABEL .'</option>';
            }
            
            return  $this->PrestationList;
        }else {
            return  $this->GetQuery($query);
        } 
    }

    public function GetPrestationCheckedList($IdData){
        $IdData = explode(",", $IdData);
        $query='SELECT id, CODE, LABEL   FROM '. TABLE_ERP_PRESTATION .'  ';
        $this->ProviderCheckedList = '';
		foreach ($this->GetQuery($query) as $data){
            if(in_array($data->id,$IdData)){
                $checked = 'checked';
            }
            else{
                $checked = '';
            }

            $this->ProviderCheckedList .='<tr><td><input type="checkbox" '. $checked .' value="'. $data->id .'" name="PRESTATION_ID[]" /><label for="PRESTATION_ID">'. $data->CODE .' - '. $data->LABEL .'</label></td></tr>';
		}
        
        return  $this->ProviderCheckedList;
    }
}
