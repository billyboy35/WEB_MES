<?php

namespace App\Order;
use \App\SQL;

class OrderAcknowledgment Extends SQL  {

    Public $id;
    Public $CODE;
    Public $LABEL;
    Public $ORDER_ID;
    Public $COMPANY_ID;
    Public $CONTACT_ID;
    Public $ADRESSE_ID;
    Public $FACTURATION_ID;
    Public $COND_REG_COMPANY_ID;
    Public $COND_REG_LABEL;
    Public $MODE_REG_COMPANY_ID;
    Public $MODE_REG_LABEL;
    Public $ECHEANCIER_ID;
    Public $ECHEANCIER_LABEL;
    Public $TRANSPORT_ID;
    Public $TRANSPORT_LABEL;
    Public $DATE;
    Public $ETAT;
    Public $CREATOR_ID;
    Public $INCOTERM;
    Public $COMMENT;
    Public $CUSTOMER_LABEL;
    Public $NOM_CREATOR;
    Public $PRENOM_CREATOR;
    Public $NOM_RESP_COM;
    Public $PRENOM_RESP_COM;
    Public $NOM_RESP_TECH;
    Public $PRENOM_RESP_TECH;

    Public $OrderAcknowledgment;

    public function NewOrderAcknowledgment($CODE, $ORDER_ID, $UserID){

        $NewOrderAcknowledgment = $this->GetInsert("INSERT INTO ". TABLE_ERP_ORDER_ACKNOWLEGMENT ." VALUE ('0',
                                                                                    '". $CODE ."',
                                                                                    '". $ORDER_ID ."',
                                                                                    '',
                                                                                    '',
                                                                                    NOW(),
                                                                                    '1',
                                                                                    '". $UserID ."',
                                                                                    '1',
                                                                                    '')");
        return $NewOrderAcknowledgment;
    }

    public function GETOrderAcknowledgment($id_GET){

        if($this-> GETOrderAcknowledgmentCount($id_GET) == 0){ header('Location: index.php?page=order'); }

        $OrderAcknowledgment = $this->GetQuery('SELECT '. TABLE_ERP_ORDER_ACKNOWLEGMENT .'.id,
                                        '. TABLE_ERP_ORDER_ACKNOWLEGMENT .'.CODE,
                                        '. TABLE_ERP_ORDER_ACKNOWLEGMENT .'.ORDER_ID,
                                        '. TABLE_ERP_ORDER_ACKNOWLEGMENT .'.LABEL,
                                        '. TABLE_ERP_ORDER_ACKNOWLEGMENT .'.LABEL_INDICE,
                                        '. TABLE_ERP_ORDER_ACKNOWLEGMENT .'.DATE,
                                        '. TABLE_ERP_ORDER_ACKNOWLEGMENT .'.ETAT,
                                        '. TABLE_ERP_ORDER_ACKNOWLEGMENT .'.CREATOR_ID,
                                        '. TABLE_ERP_ORDER_ACKNOWLEGMENT .'.INCOTERM,
                                        '. TABLE_ERP_ORDER_ACKNOWLEGMENT .'.COMENT,
                                        '. TABLE_ERP_EMPLOYEES .'.NOM AS NOM_CREATOR,
                                        '. TABLE_ERP_EMPLOYEES .'.PRENOM AS PRENOM_CREATOR,
                                        TABLE_ERP_EMPPLOYEES_RESP_COM.NOM AS NOM_RESP_COM,
                                        TABLE_ERP_EMPPLOYEES_RESP_COM.PRENOM  AS PRENOM_RESP_COM,
                                        TABLE_ERP_EMPPLOYEES_RESP_TECH.NOM AS NOM_RESP_TECH,
                                        TABLE_ERP_EMPPLOYEES_RESP_TECH.PRENOM  AS PRENOM_RESP_TECH,
                                        '. TABLE_ERP_ORDER .'.COMPANY_ID,
                                        '. TABLE_ERP_ORDER .'.CONTACT_ID,
                                        '. TABLE_ERP_ORDER .'.ADRESSE_ID,
                                        '. TABLE_ERP_ORDER .'.FACTURATION_ID,
                                        '. TABLE_ERP_ORDER .'.RESP_COM_ID,
                                        '. TABLE_ERP_ORDER .'.RESP_TECH_ID,
                                        '. TABLE_ERP_ORDER .'.REFERENCE,
                                        '. TABLE_ERP_ORDER .'.COND_REG_COMPANY_ID,
                                        '. TABLE_ERP_ORDER .'.MODE_REG_COMPANY_ID,
                                        '. TABLE_ERP_ORDER .'.ECHEANCIER_ID,
                                        '. TABLE_ERP_ORDER .'.TRANSPORT_ID,
                                        '. TABLE_ERP_COMPANES .'.LABEL As CUSTOMER_LABEL,
                                        '. TABLE_ERP_CONDI_REG .'.LABEL AS COND_REG_LABEL,
                                        '. TABLE_ERP_MODE_REG .'.LABEL AS MODE_REG_LABEL,
                                        '. TABLE_ERP_ECHEANCIER_TYPE .'.LABEL AS ECHEANCIER_LABEL,
                                        '. TABLE_ERP_TRANSPORT .'.LABEL AS TRANSPORT_LABEL
                                        FROM `'. TABLE_ERP_ORDER_ACKNOWLEGMENT .'`
                                        LEFT JOIN `'. TABLE_ERP_ORDER .'` ON `'. TABLE_ERP_ORDER_ACKNOWLEGMENT .'`.`ORDER_ID` = `'. TABLE_ERP_ORDER .'`.`id`
                                            LEFT JOIN `'. TABLE_ERP_EMPLOYEES .'` ON `'. TABLE_ERP_ORDER_ACKNOWLEGMENT .'`.`CREATOR_ID` = `'. TABLE_ERP_EMPLOYEES .'`.`idUSER`
                                            LEFT JOIN `'. TABLE_ERP_EMPLOYEES .'` AS  TABLE_ERP_EMPPLOYEES_RESP_COM ON `'. TABLE_ERP_ORDER .'`.`RESP_COM_ID` =  TABLE_ERP_EMPPLOYEES_RESP_COM.`idUSER`
                                            LEFT JOIN `'. TABLE_ERP_EMPLOYEES .'` AS TABLE_ERP_EMPPLOYEES_RESP_TECH ON `'. TABLE_ERP_ORDER .'`.`RESP_TECH_ID` = TABLE_ERP_EMPPLOYEES_RESP_TECH.`idUSER`
                                            LEFT JOIN `'. TABLE_ERP_COMPANES .'` ON `'. TABLE_ERP_ORDER .'`.`COMPANY_ID` = `'. TABLE_ERP_COMPANES .'`.`id`
                                            LEFT JOIN `'. TABLE_ERP_CONDI_REG .'` ON `'. TABLE_ERP_ORDER .'`.`COND_REG_COMPANY_ID` = `'. TABLE_ERP_CONDI_REG .'`.`id`
                                            LEFT JOIN `'. TABLE_ERP_MODE_REG .'` ON `'. TABLE_ERP_ORDER .'`.`MODE_REG_COMPANY_ID` = `'. TABLE_ERP_MODE_REG .'`.`id`
                                            LEFT JOIN `'. TABLE_ERP_ECHEANCIER_TYPE .'` ON `'. TABLE_ERP_ORDER .'`.`ECHEANCIER_ID` = `'. TABLE_ERP_ECHEANCIER_TYPE .'`.`id`
                                            LEFT JOIN `'. TABLE_ERP_TRANSPORT .'` ON `'. TABLE_ERP_ORDER .'`.`TRANSPORT_ID` = `'. TABLE_ERP_TRANSPORT .'`.`id`
                                        WHERE '. TABLE_ERP_ORDER_ACKNOWLEGMENT .'.id = \''. $id_GET.'\' ', true, 'App\Order\OrderAcknowledgment');
        return $OrderAcknowledgment;
    }

    public function GETOrderAcknowledgmentCount($ID = null, $Clause = null){
        
        if($ID != null){
            $Clause = 'WHERE id = \''. $ID .'\'';
        }

        $OrderAcknowledgmentCount =  $this->GetCount(TABLE_ERP_ORDER_ACKNOWLEGMENT,'id', $Clause);
        return $OrderAcknowledgmentCount;
    }

    public function GETOrderAcknowledgmentList($IdData=0, $Select = true, $OrderId=0){

        If($OrderId>0){
            $Clause = 'WHERE ORDER_ID = '. $OrderId .' ';
        }

        $this->OrderAcknowledgmentList ='';
        $query='SELECT '. TABLE_ERP_ORDER_ACKNOWLEGMENT .'.id,
                        '. TABLE_ERP_ORDER_ACKNOWLEGMENT .'.CODE,
                        '. TABLE_ERP_ORDER_ACKNOWLEGMENT .'.LABEL,
                        '. TABLE_ERP_ORDER_ACKNOWLEGMENT .'.ETAT,
                        '. TABLE_ERP_COMPANES .'.LABEL As CUSTOMER_LABEL
                FROM '. TABLE_ERP_ORDER_ACKNOWLEGMENT .'
                  LEFT JOIN `'. TABLE_ERP_ORDER .'` ON `'. TABLE_ERP_ORDER_ACKNOWLEGMENT .'`.`ORDER_ID` = `'. TABLE_ERP_ORDER .'`.`id`
                  LEFT JOIN `'. TABLE_ERP_COMPANES .'` ON `'. TABLE_ERP_ORDER .'`.`COMPANY_ID` = `'. TABLE_ERP_COMPANES .'`.`id`
                  '. $Clause .'
                ORDER BY  '. TABLE_ERP_ORDER_ACKNOWLEGMENT .'.id DESC';
              
        if($Select){
            foreach ($this->GetQuery($query) as $data){
            
                $this->OrderAcknowledgmentList .='<option value="'. $data->Id .'" '. selected($IdData, $data->id) .'>'. $data->CODE .'</option>';
            }

            return  $this->OrderAcknowledgmentList;

        }else {
            return  $this->GetQuery($query);
        }  
    }
}

class OrderAcknowledgmentLines Extends OrderAcknowledgment  {

    Public $id;
    Public $ORDER_ACKNOWLEGMENT_ID;
    Public $ORDER_ID;
    Public $ORDRE;
    Public $ORDER_LINE_ID;
    Public $ARTICLE_CODE;
    Public $LABEL;
    Public $LABEL_INDICE;
    Public $QT;
    Public $UNIT_ID;
    Public $PRIX_U;
    Public $DISCOUNT;
    Public $TVA_ID;
    Public $DELAIS;
    Public $ORDER_ACKNOWLEGMENT_CODE;
    Public $ORDER_CODE;
    Public $TAUX;
    Public $LABEL_TVA;
    Public $LABEL_UNIT;
    Public $ETAT;

    Public $OrderAcknowledgmentLines;

    public function NewOrderacknowledgmentlines($IdOrderAcknowledgment, $OrderID, $Order, $orderLineId){

        $NewOrderacknowledgmentlines = $this->GetInsert("INSERT INTO ". TABLE_ERP_ORDER_ACKNOWLEGMENT_LINES ." VALUE ('0',
                                                                                        '". $IdOrderAcknowledgment ."',
                                                                                        '". addslashes($OrderID) ."',
                                                                                        '". addslashes($Order) ."',
                                                                                        '". addslashes($orderLineId) ."',
                                                                                        '1')");


        return $NewOrderacknowledgmentlines;
    }

    public function UpdateOrderacknowledgmentlines($id, $ORDRE){

        $UpdateOrderacknowledgmentlines = $this->GetUpdate("UPDATE  ". TABLE_ERP_ORDER_ACKNOWLEGMENT_LINES ." SET 	ORDRE='". addslashes($ORDRE) ."' WHERE id='". addslashes($id)."'");

        return $UpdateOrderacknowledgmentlines;
    }

    public function GETOrderacknowledgmentlinesList($IdData=0, $Select = true, $IdOrderAcknowledgment=0){

        $this->OrderacknowledgmentlinesList ='';
        $Clause = 'WHERE '. TABLE_ERP_ORDER_ACKNOWLEGMENT_LINES .'.ORDER_ACKNOWLEGMENT_ID = \''. $IdOrderAcknowledgment.'\'';
        if($IdOrderAcknowledgment===0){
            $Clause = '';
        }
        $query='SELECT  '. TABLE_ERP_ORDER_ACKNOWLEGMENT_LINES .'.id,
                        '. TABLE_ERP_ORDER_ACKNOWLEGMENT_LINES .'.ORDER_ACKNOWLEGMENT_ID,
                        '. TABLE_ERP_ORDER_ACKNOWLEGMENT_LINES .'.ORDER_ID,
                        '. TABLE_ERP_ORDER_ACKNOWLEGMENT_LINES .'.ORDRE,
                        '. TABLE_ERP_ORDER_ACKNOWLEGMENT_LINES .'.ORDER_LINE_ID,
                        '. TABLE_ERP_ORDER_ACKNOWLEGMENT_LINES .'.ETAT,
                        '. TABLE_ERP_ORDER_LIGNE .'.ARTICLE_CODE,
                        '. TABLE_ERP_ORDER_LIGNE .'.LABEL,
                        '. TABLE_ERP_ORDER_LIGNE .'.QT,
                        '. TABLE_ERP_ORDER_LIGNE .'.UNIT_ID,
                        '. TABLE_ERP_ORDER_LIGNE .'.PRIX_U,
                        '. TABLE_ERP_ORDER_LIGNE .'.DISCOUNT,
                        '. TABLE_ERP_ORDER_LIGNE .'.TVA_ID,
                        '. TABLE_ERP_ORDER_LIGNE .'.DELAIS,
                        '. TABLE_ERP_ORDER_ACKNOWLEGMENT .'.CODE AS ORDER_ACKNOWLEGMENT_CODE,
                        '. TABLE_ERP_ORDER .'.CODE AS ORDER_CODE,
                        '. TABLE_ERP_TVA .'.TAUX,
                        '. TABLE_ERP_TVA .'.LABEL AS LABEL_TVA,
                        '. TABLE_ERP_UNIT .'.LABEL AS LABEL_UNIT
                        FROM '. TABLE_ERP_ORDER_ACKNOWLEGMENT_LINES .'
                            LEFT JOIN `'. TABLE_ERP_ORDER .'` ON `'. TABLE_ERP_ORDER_ACKNOWLEGMENT_LINES .'`.`ORDER_ID` = `'. TABLE_ERP_ORDER .'`.`id`
                            LEFT JOIN `'. TABLE_ERP_ORDER_LIGNE .'` ON `'. TABLE_ERP_ORDER_ACKNOWLEGMENT_LINES .'`.`ORDER_LINE_ID` = `'. TABLE_ERP_ORDER_LIGNE .'`.`id`
                            LEFT JOIN `'. TABLE_ERP_TVA .'` ON `'. TABLE_ERP_ORDER_LIGNE .'`.`TVA_ID` = `'. TABLE_ERP_TVA .'`.`id`
                            LEFT JOIN `'. TABLE_ERP_UNIT .'` ON `'. TABLE_ERP_ORDER_LIGNE .'`.`UNIT_ID` = `'. TABLE_ERP_UNIT .'`.`id`
                            LEFT JOIN `'. TABLE_ERP_ORDER_ACKNOWLEGMENT .'` ON `'. TABLE_ERP_ORDER_ACKNOWLEGMENT_LINES .'`.`ORDER_ACKNOWLEGMENT_ID` = `'. TABLE_ERP_ORDER_ACKNOWLEGMENT .'`.`id` 
                            '. $Clause.'
                        ORDER BY '. TABLE_ERP_ORDER_ACKNOWLEGMENT_LINES .'.ORDRE ';
          
        if($Select){
            foreach ($this->GetQuery($query) as $data){
            
                $this->OrderacknowledgmentlinesList .='<option value="'. $data->id .'" '. selected($IdData, $data->id) .'>'. $data->ORDER_ACKNOWLEGMENT_CODE .' - '. $data->CODE .'</option>';
            }

            return  $this->OrderacknowledgmentlinesList;

        }else {
            
            return  $this->GetQuery($query);
        }  
    }
}