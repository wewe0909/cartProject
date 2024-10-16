<!-- http://localhost:8080/CartProject/com/tax/tax.php -->
<?php
// libxml_use_internal_errors(true); 
// require_once 'com\user\user.php'; 

function getTaxInfo($user_info){ // returns: array: tax_info[]
    $exec = 0;
    $err = "No ha sido posible obtener el valor del impuesto añadido.";
    $xml_path = '../../xmldb/taxes.xml';
    $tax_info = array(); 
    // --------------------------------------------------------------- 
    if(file_exists($xml_path)){
        $taxes = simplexml_load_file($xml_path);
        $userRegion = user_info['region'];
        foreach($taxes->region as $region){
            if($region->name == $userRegion){
                $tax_info = array(
                    'regionName' => (string)$region->name,
                    'appliedTax' => (int)$region->tax
                );
                break;
            }
            if(!empty($tax_info)){
                $exec = 1;
                $err = null;
            }
        }
    }
    return [$exec, $err, $tax_info];
}

?> 