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
        <a href="main.php?page=catalog"><button id="go-back"></button></a>
        <fieldset class="regular">
            <form class="cart-form" name="cart-form">
                <div class="form-items">
                    <label>Lista de artículos:</label>
                    <div class="space-gap"></div>
                    <div id="cart-products">
                            <!-- En caso de que no haya productos, generará un 
                            fieldset class con el mensaje adecuado -->
                            <table>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio unitario</th>
                                    <th>Precio total</th>
                                    <th>Modificar</th>
                                    <th>Eliminar</th>
                                </tr>
                                <tr>
                                    <td>Manzana</td>
                                    <td>5</td>
                                    <td>1,20</td>
                                    <td>6,00</td>
                                    <td>
                                        <select id="cart-product-amount">
                                            <!-- Se rellena por defecto con la cantidad del artículo -->
                                            <!-- El límite es el stock disponible -->
                                            <option value="" selected>5</option> 
                                        </select>
                                    </td>
                                    <td><span id="delete"></td>
                                </tr>
                                <tr>
                                    <td>Aguacate</td>
                                    <td>5</td>
                                    <td>3,50</td>
                                    <td>17,50</td>
                                    <td>
                                        <select id="cart-product-amount">
                                            <!-- Se rellena por defecto con la cantidad del artículo -->
                                            <!-- El límite es el stock disponible -->
                                            <option value="" selected>5</option> 
                                        </select>
                                    </td>
                                    <td><span id="delete"></td>
                                </tr>
                            </table>
                        </div>
                        <div class="space-gap"></div>
                        <div>
                            <input type="text" placeholder="Código promocional">
                            <button id="cart-promo-update">Revisar</button>
                        </div>
                        <div class="space-gap"></div>
                    <div>
                        <select name="cart-shipping">
                            <option value="" selected>Escoge el método de envío</option>
                            <option value="01">Pick-up point (4,99€)</option>
                            <option value="02">Home delivery (5,99€)</option>
                            <option value="03">Store pick-up (0,00€)</option>
                        </select>
                    </div>
                    </div>
                    <div class="space-gap"></div>
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
                            <button name="cart-reset">Resetear</button>
                            <input type="submit" value="Comprar">
                    </div>
                </div>
            </form>
        </fieldset>
        </fieldset>
    </fieldset>
</body>
</html>