<?php
session_start(); 
// -------------------------------------------------------------------------
// Gestión de valores de sesión
// -------------------------------------------------------------------------
if (!isset($_SESSION['sessionStatus'])) {
    $_SESSION['sessionStatus'] = 0; 
    $_SESSION['userInfo'] = null;   
}
if (!isset($_SESSION['sessionStatus']) or $_SESSION['sessionStatus'] == 0){
    $_SESSION['href_userAction'] = 'main.php?page=login';
    $_SESSION['userAction'] = 'access-user';
} else {
    $_SESSION['href_userAction'] = 'main.php?action=logout';
    $_SESSION['userAction'] = 'access-user-logout';
}
// -------------------------------------------------------------------------
// Gestión de errores
// -------------------------------------------------------------------------
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// -------------------------------------------------------------------------
// Inclusión de ficheros externos 
// -------------------------------------------------------------------------
include_once('com/catalog/catalog.php');
include_once('com/user/user.php');
include_once('com/promo/promo.php');
include_once('com/tax/tax.php');
include_once('com/cart/cart.php');
include_once('com/connection/connection.php');
// -------------------------------------------------------------------------
// Gestión de páginas
// -------------------------------------------------------------------------
$currentPage = 'catalog';
$page = isset($_GET['page']) ? $_GET['page'] : $currentPage; 
// Rutas a los archivos HTML
$htmlFiles = [
    'login' => __DIR__ . '/html/login.php',
    'cart' => __DIR__ . '/html/cart.php',
    'catalog' => __DIR__ . '/html/catalog.php',
    'main' => __DIR__ . '/html/main.php',
    'purchase' => __DIR__ . '/html/purchase.php',
];
if (array_key_exists($page, $htmlFiles) && file_exists($htmlFiles[$page])) {
    include($htmlFiles[$page]);
} else {
    echo "<center><h1>Error 404 :-(</h1></center>";
}
// -------------------------------------------------------------------------
// Gestión del action 
// -------------------------------------------------------------------------
if (isset($_GET['action'])){
    $action = $_GET['action'];
    switch ($action) {
        // case 'access-user':
        //     continue;
        // -------------------------------------------------------------------------------------
        case 'login':
            $currentPage = 'login';
            // Verificar la entrada de campos del form y ejecutar la función de login: 
            if (isset($_POST['username']) || isset($_POST['password'])) { 
                if (empty($_POST['username']) || empty($_POST['password'])) {
                    $_SESSION['err'] = "Todos los campos son obligatorios."; 
                    $_SESSION['errClass'] = "popupmsg err"; 
                    header("Location: main.php?page=login");
                    exit(); 
                } else {
                    [$exec, $err] = userLogin($_POST['username'], $_POST['password']);
                    if ($exec == 1) {
                        $_SESSION['exec'] = $exec; 
                        $_SESSION['err'] = "Te has conectado con éxito. Volviendo a la tienda...";
                        $_SESSION['errClass'] = "popupmsg ok"; 
                        // Obtener la información del usuario tras iniciar sesión (para posteriores acciones):
                        [$exec, $err, $user_info] = getUserInfo($_POST['username']);
                        if (($exec == 1) and (!empty($user_info))){
                            $_SESSION['userInfo'] = $user_info;
                            $_SESSION['sessionStatus'] = 1;
                        }
                        header("Location: main.php?page=login"); 
                        exit(); 
                    } else {
                        $_SESSION['exec'] = $exec; 
                        $_SESSION['err'] = $err; 
                        $_SESSION['errClass'] = "popupmsg err"; 
                        // header("Location: main.php?page=login");
                        exit(); 
                    }
                }
            } else {
                $_SESSION['exec'] = $exec; 
                $_SESSION['err'] = "No se han ingresado credenciales.";
                $_SESSION['errClass'] = "popupmsg err"; 
                header("Location: main.php?page=login");
                exit(); 
            }
            break;
        // -------------------------------------------------------------------------------------
        // action="main.php?action=logout"
        case 'logout':
            if ((!empty($_SESSION['userInfo'])) and $_SESSION['sessionStatus'] == 1){
                [$exec, $err] = userLogout($_SESSION['userInfo']['id']);
                if($exec == 1){
                    $_SESSION['err'] = "Desconectado con éxito.";
                    $_SESSION['errClass'] = "popupmsg ok"; 
                    $_SESSION['sessionStatus'] = 0; 
                    echo $_SESSION['sessionStatus'] = 0;
                    // session_unset(); 
                    // session_destroy(); 
                    exit();
                }
                header("Location: main.php?page=catalog"); 
            }
            break;
        // -------------------------------------------------------------------------------------
        case 'add_to_cart':
            if (isset($_GET['item_id'])) {
                addToCart($_GET['item_id']);
            } else {
                echo "No item ID provided.";
            }
            break;
        // -------------------------------------------------------------------------------------
        case 'remove_from_cart':
            if (isset($_GET['item_id'])) {
                removeFromCart($_GET['item_id']);
            } else {
                echo "No item ID provided.";
            }
            break;
        // -------------------------------------------------------------------------------------
        case 'view_cart':
            viewCart();
            break;
        // -------------------------------------------------------------------------------------
        default:
            echo "Invalid action!";
    }
}
?>