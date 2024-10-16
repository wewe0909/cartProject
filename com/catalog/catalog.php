<!-- http://localhost:8080/CartProject/com/catalog/catalog.php -->
<?php
// libxml_use_internal_errors(true); 
function getProductInfo($product_id){ // returns: exec, err, / product_info 
    $exec = 0;
    $err = null;
    $xml_path = '../../xmldb/catalog.xml';
    $product_info = array(); // array: product_id, name, price, stock
    // --------------------------------------------------------------- 
    $catalog = simplexml_load_file($xml_path);
    if($catalog){
        foreach($catalog->product as $product){
            if($product->id == $product_id){
                $product_info[] = array(
                    'id' => (string)$product->id,
                    'name' => (string)$product->name,
                    'price' => (float)$product->price,
                    'stock' => (int)$product->stock
                );
            }
        }
    } else {
        return [$exec, $err, $product_info];
    }
}
function checkProductStock($product_info, $quantity){
    $enough = 0;
    $stock = $product_info['stock'];
    if($stock >= $quantity){
        $enough = 1; 
    } 
    return [$stock, $enough]; 
}
function updateProductStock($product_info,$quantity){
    $exec = 0;
    $err = "Error actualizando el stock del producto.";
    $xml_path = '../../xmldb/catalog.xml';
    // --------------------------------------------------------------- 
    [$stock, $enough] = checkProductStock($product_info, $quantity);
    $product_id = $product_info['id'];
    if ($enough == 1){
        $catalog = simplexml_load_file($xml_path);
        $newStock = ($product_info['stock'] - $quantity);
        if($catalog){
            foreach($catalog->product as $product){
                if($product->id == $product_id){
                    $product->stock = $newStock;
                    $exec = 1; 
                    $err = null;
                    break;
                }
            }
        }
    } else {
        $err = "No hay stock suficiente.";
    }
    return  [$exec, $err];
}
?>