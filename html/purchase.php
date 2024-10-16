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
                    <a href="main.php?page=login"><button class="icon-button" id="access-user"></button></a>
                </div>
            </fieldset>
        </fieldset>
        <button id="go-back"></button>
        <fieldset class="regular">
            <h3>¡Gracias por su compra!</h3><br>
            <div class="container-success">
                <img class="success" src="../resources/approval.svg"> 
            </div>
            <div class="summary">
                <p>Resumen de su pedido:</p>
                <div id="cart-total-amount">
                    <label>Total (IVA y envío incluído):</label>
                    <span id="cart-amount">28.33€</span>
                    <div>
                        <label>Importe total de los artículos SIN IVA:</label>
                        <span id="cart-untaxed-import">23.50€</span>
                    </div>
                    <div>
                        <label>Importe total de los artículos CON IVA en:</label>
                        <span id="cart-taxed-region">España</span>
                        <span id="cart-taxed-import">28.33€</span>
                    </div>
                </div>
                    <div class="space-gap"></div>
                    <div class="item-right">
                        <input type="submit" value="Regresar">
                </div>
            </div>
        </fieldset>
        </fieldset>
    </fieldset>
</body>
</html>