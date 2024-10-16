<!-- http://localhost:8080/CartProject/com/promo/promo.php -->
<?php
// libxml_use_internal_errors(true); 

function getPromoDiscount($code){ // returns: array: tax_info[]
    $exec = 0;
    $err = "No ha sido posible realizar un descuento.";
    $xml_path = '../../xmldb/promos.xml';
    // --------------------------------------------------------------- 
    $discount = null;
    if(file_exists($xml_path)){
        $promos = simplexml_load_file($xml_path);
        foreach($promos->promo as $promo){
            if((string)$promo->attributes()->code === $code){
                $discount = (int)$code->discount;
                $exec = 1;
                $err = null;
                break;
            }
        }
    }
    return [$exec, $err, $discount];
}
?> 