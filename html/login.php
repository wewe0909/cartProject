<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart Prototype</title>
    <link rel="stylesheet" href="css\main.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/main.js" defer></script> <!-- Referencia al archivo JS -->
</head>
<body>
    <div class="overlay"></div>
    <fieldset class="whole-container">
        <fieldset id="header">
            <h2>E-Commerce Cart Prototype</h2>
            <fieldset id="header-divs">
                <div id="header-status">
                    <?php echo $_SESSION['sessionStatus'];?>
                    <p>Estado del usuario</p>
                </div>
                <div id="header-cart">
                    <!-- Botón de acceso al CARRITO / ERROR si no hay sesión activa:  -->
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
                    <!-- <a href="main.php?page=login"><button class="icon-button" id="access-user"></button></a> -->
                </div>
            </fieldset>
        </fieldset>
        <a href="main.php?page=catalog"><button id="go-back"></button></a>
        <!-- Mensaje de error:  -->
        <?php
        // Mostrar el mensaje de sesión, si existe
        if (isset($_SESSION['err']) and (isset($_SESSION['errClass']))) {
            echo '<fieldset id="msg" class="' . htmlspecialchars($_SESSION['errClass'], ENT_QUOTES, 'UTF-8') . '">';
            echo htmlspecialchars($_SESSION['err'], ENT_QUOTES, 'UTF-8');
            echo '</fieldset>';
            if ($_SESSION['exec'] == 1){
                echo '<script>
                setTimeout(function() {
                    window.location.href = "main.php?page=catalog";
                }, 1000); 
                </script>';
            }
            unset($_SESSION['err']);
            unset($_SESSION['errClass']);
            }
        ?>
        <!---------------------------->
        <fieldset class="regular">
            <form class="login-form" name="login-form" action="main.php?action=login" method="POST">
                <div class="form-items">
                    <label>Nombre:</label>
                    <input type="text" placeholder="username" name="username">
                    <div class="space-gap"></div>
                    <label>Contraseña:</label>
                    <input type="password" placeholder="password" name="password">
                <div class="space-gap"></div>
                </div>
                <div class="item-right">
                    <input type="submit" value="Login">
                </div>
            </form>
        </fieldset>
        </fieldset>
    </fieldset>
</body>
</html>