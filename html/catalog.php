<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart Prototype</title>
    <link rel="stylesheet" href="css\main.css">
</head>
<body>
    <div class="overlay"></div>
    <fieldset class="whole-container">
        <fieldset id="header">
            <h2>E-Commerce Cart Prototype</h2>
            <fieldset id="header-divs">
                <div id="header-status">
                    <p>Estado del usuario</p>
                </div>
                <div id="header-cart">
                    <a href="main.php?page=cart"><button class="icon-button" id="access-cart"></button></a>
                    <!-- Botón de acceso a página de LOGIN / LOGOUT:  -->
                    <?php
                    if($_SESSION['sessionStatus'] == 1){
                        echo '<form id=#sessionLogout action="' . htmlspecialchars($_SESSION['href_userAction'], ENT_QUOTES, 'UTF-8') . '" method="POST">
                        <button type="submit" class="'. htmlspecialchars($_SESSION['userAction'], ENT_QUOTES, 'UTF-8') .'"></button>
                        </form>';
                    }
                    echo '<a href="' . htmlspecialchars($_SESSION['href_userAction'], ENT_QUOTES, 'UTF-8') . '">
                    <button class="icon-button" id="' . htmlspecialchars($_SESSION['userAction'], ENT_QUOTES, 'UTF-8') . '"></button>
                    </a>'
                    ;
                    ?>
                </div>
            </fieldset>
        </fieldset>
        <!-- <fieldset class="popup-msg ok">
            Añadido el producto x, actualizado a cantidad x
        </fieldset> -->
        <fieldset id="product-view-display">
            <div id="row">
                <div class="product-view">
                    <span class="product-image"></span><img src="resources/products/piña.jpg"></span>
                    <button class="product-button" id="add-product">Añadir</button>
                </div>
                <div class="product-view">
                    <span class="product-image"></span><img src="resources/products/cereza.jpg"></span>
                    <button class="product-button" id="add-product">Añadir</button>
                </div>
                <div class="product-view">
                    <span class="product-image"></span><img src="resources/products/manzana.png"></span>
                    <button class="product-button" id="add-product">Añadir</button>
                </div>
            </div>
            <div id="row">
                <div class="product-view">
                    <span class="product-image"></span><img src="resources/products/aguacate.jpg"></span>
                    <button class="product-button" id="add-product">Añadir</button>
                </div>
                <div class="product-view">
                    <span class="product-image"></span><img src="resources/products/chuleta.jpg"></span>
                    <button class="product-button" id="add-product">Añadir</button>
                </div>
                <div class="product-view">
                    <span class="product-image"></span><img src="resources/products/gato.jpg"></span>
                    <button class="product-button" id="add-product">Añadir</button>
                </div>
            </div>
        </fieldset>
        <!-- <fieldset class="regular">
            <p>Algo</p>
        </fieldset> -->
    </fieldset>
</body>
</html>