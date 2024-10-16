<!-- http://localhost:8080/CartProject/com/cart/cart.php -->
<?php
// libxml_use_internal_errors(true); 
// require_once '/com/catalog/catalog.php';
// require_once 'com/user/user.php'; 
// require_once 'com/connection/connection.php'; 
// require_once 'com/tax/tax.php'; 
// require_once 'com/promo/promo.php'; 

function purchaseCart($user_id, $shipping, $code) {
    $exec = 0; 
    [$exec, $err, $cart_info] = getCartInfo($user_id, $shipping, $code);
    if ($exec == 1 && !empty($cart_info)) {
        foreach ($cart_info as $product) {
            $product_id = $product['product_id'];
            $quantity = $product['quantity'];
            [$exec, $err, $product_info] = getProductInfo($product_id);
            if ($exec == 1 && $product_info) {
                [$exec, $err] = updateProductStock($product_info, $quantity);
                if ($exec != 1) {
                    return [$exec, $err];
                }
            } else {
                return [$exec, $err];
            }
        }
        $exec = 1;
        $err = null; 
    }
    return [$exec, $err];
}
function getCartTotal($user_id, $shipping, $code){
    $exec = 0;
    $err = "Error al obtener la información de la cesta.";
    $cartTotal = null;
    $cartTotalTaxed = null; 
    // --------------------------------------------------------------- 
    [$exec, $err, $user_info] = getUserInfo($user_id);
    [$exec, $err, $cart_info] = getCartInfo($user_id);
    if(!empty($cart_info) and $exec == 1){
        foreach ($cart_info as $product) {
            $cartTotal += $product['price_total'];
        }
        if($shipping){
            $cartTotal = ($shipping + $cartTotal);
        }
        if($code){
            [$exec, $err, $discount] = getPromoDiscount($code);
            if ($exec == 1 and $discount){
                $discount = (float)($discount / 100);
                $deduced = ($CartTotal * $discount);
                $cartTotal = ($CartTotal - $deduced);
            }
        }
        [$exec, $err, $tax_info] = getTaxInfo($user_info);
        if(!empty($tax_info)){
            $tax = $tax_info['appliedTax'];
            $tax = (float)($tax / 100);
            $taxed = ($cartTotal * $tax);
            $cartTotal = ($cartTotal + $taxed);
        }
        $exec = 1;
        $err = null;
    }
    return [$exec, $err, $cartTotal, $cartTotalTaxed];
}
function getCartInfo($user_id){ // returns: exec, err, / cart_info 
    $exec = 0;
    $err = "Error al obtener la información de la cesta.";
    $xml_path = '../../xmldb/carts.xml';
    $cart_info = array(); // array: per.product_id(name, quantity, price_unit, price_total)
    // --------------------------------------------------------------- 
    $carts = simplexml_load_file($xml_path);
    if($carts){
        echo "Carts cargado correctamente";
        $userCart = null;
        foreach ($carts->cart as $cart) {
            if((string)$cart->attributes()->user === $user_id) {
                $userCart = $cart;
                foreach ($userCart->products->product as $product) {
                    $cart_info[] = array(
                        'product_id' => (string)$product->attributes()->id,
                        'name' => (string)$product->name,
                        'quantity' => (int)$product->quantity,
                        'price_unit' => (float)$product->price_unit,
                        'price_total' => (float)$product->price_total,
                    );
                }
                $exec = 1; 
                $err = null; 
                break; 
            }
        }
    }
    return [$exec, $err, $cart_info];
}
function addToCart($product_id, $user_id, $quantity){ // returns: exec, err 
    $exec = 0; 
    $err = "Error al añadir el producto.";
    $xml_path = '../../xmldb/carts.xml';
    // --------------------------------------------------------------- 
    if($user_id){
        [$exec, $err, $product_info] = getProductInfo($product_id);
        if (!empty($product_info) and $exec == 1) {
            // Se revisará si el fichero cesta existe, y si existe,
            // que el usuario tenga su cesta. Caso contrario, se genera lo necesario.
            [$exec, $err] = createCarts(); 
            if ($exec == 1){
                [$exec, $err, $cart_info] = getCartInfo($user_id);
                if ($exec == 0){
                    [$exec, $err] = addNewCart($user_id);
                } 
                $carts = simplexml_load_file($xml_path);
                foreach ($cart_info as $product) {
                    if ($product['product_id'] == $product_id){
                        $price_unit = $product['price'];
                        break;
                    } 
                }
                // Añadir producto de 0
                if (!productInCart($cart_info, $product_id)){
                    [$stock, $enough] = checkProductStock($product_info, $quantity);
                    if($enough == 0){
                        $err = "No hay stock suficiente. Puedes añadir un máximo de " . $stock;
                    } else {
                        $price_total = ($price_unit * $quantity);
                        foreach ($carts->cart as $cart) {
                            if((string)$cart->attributes()->user === $user_id){
                                $userCart = $cart;
                                $products = $userCart->products;
                                $product = $products->addChild('product');
                                $product->addAttribute('id', $product_id);
                                $product->addChild('name', $product_info['name']);
                                $product->addChild('quantity', $quantity); 
                                $product->addChild('price_unit', $product_info['price']);
                                $product->addChild('price_total', $price_total);
                                break;
                            }
                        }
                    }
                } else { 
                    // Modificar producto existente
                    [$exec, $err] = updateProductCart($user_id, $product_id, $cart_info, $carts, $product_info, $quantity);
                } 
                if($exec == 1){
                    $err = null;
                    $carts->asXML($xml_path);
                }
            }
        } else {
            $err = "Producto no válido.";
        }
    } else {
        $err = "Has de estar conectado para añadir productos a la cesta.";
    }
    return [$exec, $err];
}
function removeProductCart($user_id, $product_id){  // returns: exec, err 
    $exec = 0; 
    $err = "Error eliminando el artículo de la cesta.";
    $xml_path = '../../xmldb/carts.xml';
    // --------------------------------------------------------------- 
    $carts = simplexml_load_file($xml_path);
    foreach ($carts->cart as $cart) {
        if((string)$cart->attributes()->user === $user_id){
            $userCart = $cart;
            break;
        }
    }
    $products = $userCart->products;
    foreach ($products->product as $product){
        if((string)$product->attributes()->id === $product_id){
            $dom = dom_import_simplexml($product);
            $dom->parentNode->removeChild($dom);
            break;
        }
    }
    [$exec, $err, $cart_info] = getCartInfo($user_id);
    if (!productInCart($cart_info, $product_id)){
        $exec = 1; 
        $err = null;
        $carts->asXML($xml_path);
    }
    return [$exec, $err];
}
function resetCart($user_id){  // returns: exec, err 
    $exec = 0; 
    $err = "Error reseteando la cesta.";
    $xml_path = '../../xmldb/carts.xml';
    // --------------------------------------------------------------- 
    $carts = simplexml_load_file($xml_path);
    foreach ($carts->cart as $cart) {
        if((string)$cart->attributes()->user === $user_id){
            $dom = dom_import_simplexml($cart);
            $dom->parentNode->removeChild($dom);
            break;
        }
    }
    $carts->asXML($xml_path);
    [$exec, $err, $cart_info] = getCartInfo($user_id);
    if (!empty($cart_info)){
        $exec = 1; 
    }
    return [$exec, $err];
}   
function updateProductCart($user_id, $product_id, $cart_info, $carts, $product_info, $quantity){  // returns: exec, err 
    $exec = 0;
    $err = "Error actualizando la cesta.";
    // --------------------------------------------------------------- 
    foreach ($cart_info as $product){
        if ($product['product_id'] == $product_id){
            $cart_quantity = $product['quantity']; 
            break; 
        } 
    }
    $quantity = ($cart_quantity + $quantity); 
    [$stock, $enough] = checkProductStock($product_info, $quantity);
    if($enough == 0){
        $err = "No hay stock suficiente. Puedes añadir un máximo de " . $stock;
    } else {
        $price_total = ($price_unit * $quantity);
        foreach ($carts->cart as $cart) {
            if((string)$cart->attributes()->user === $user_id){
                $userCart = $cart;
                break;
            }
        }
        $products = $userCart->products;
        foreach ($products->product as $product){
            if((string)$product->attributes()->id === $product_id){
                $product->quantity = $quantity; 
                $product->price_total =  $price_total; 
                break;
            }
        }
        // Idealmente se revisaría si existe un producto con estas caracterísiticas en la cesta 
        // de este det. usuario... pero asignamos correcto directamente:
        $exec = 1; 
    }
    return [$exec, $err];
}
function productInCart($cart_info, $product_id){ // returns: bool
    $found = 0;
    // --------------------------------------------------------------- 
    if (!empty($cart_info)) { 
        foreach ($cart_info as $product) {
            if ($product['product_id'] == $product_id){
                $found = 1; 
                break;
            } 
        }
    }
    return $found;
}
function createCarts(){  // returns: exec, err 
    $exec = 0;
    $err = "Error al crear el archivo de cestas.";
    $xml_path = '../../xmldb/carts.xml';
    // --------------------------------------------------------------- 
    if(!file_exists($xml_path)){
        $carts = new SimpleXMLElement('<carts></carts>');
        $carts->asXML($xml_path);
    }
    if(file_exists($xml_path)){
        $exec = 1; 
        $err = null;
    }
    return [$exec, $err];
}
function addNewCart($user_id){  // returns: exec, err 
    $exec = 0;
    $err = "Error al crear la cesta del usuario.";
    $xml_path = '../../xmldb/carts.xml';
    // --------------------------------------------------------------- 
    $carts = simplexml_load_file($xml_path);
    $newCart = $carts->addChild('cart');
    $newCart->addAttribute('user',$user_id);
    $products = $newCart->addChild('products');
    [$exec, $err, $cart_info] = getCartInfo($user_id);
    if ($exec == 1){
        $err = null;
    }
    return [$exec, $err];
}
