<!-- http://localhost:8080/CartProject/com/user/user.php -->
<?php
// libxml_use_internal_errors(true); 
// require_once '..\connection\connection.php'; 
function getUserInfo($user_id){ // returns: array: user_info
    $exec = 0;
    $err = "El usuario no existe o las credenciales son incorrectas.";
    $xml_path = 'xmldb/users.xml';
    $user_info = array(); // array: user_id, password, status, region
    // --------------------------------------------------------------- 
    $users = simplexml_load_file($xml_path);
    if($users){
        foreach($users->user as $user){
            if($user->user_id == $user_id){
                $user_info = array(
                    'id' => (string)$user->user_id,
                    'password' => (string)$user->password,
                    'status' => (bool)$user->status,
                    'region' => (string)$user->region,
                );
                if(!empty($user_info)){
                    $exec = 1;
                    $err = null;
                }
            }
        }
    } else {
        $err = "Error al acceder al fichero de usuarios.";
    }
    return [$exec, $err, $user_info];
}
function isUserConnected($user_info){ // returns: bool 
    $exec = 0; 
    $err = "Datos del usuario inaccesibles.";
    // --------------------------------------------------------------- 
    if (!empty($user_info)){
        if ($user_info['status'] == 1) {
            $status = true;
        } else {
            $status = false;
        }
    } 
    if (isset($status)){
        $exec = 1; 
        $err = null; 
    } else {
        $status = null;
    }
    return [$exec, $err, $status];
}
function modUserStatus($user_id){
    $exec = 0;
    $err = "Error desconectando al usuario.";
    $xml_path = 'xmldb/users.xml';
    // --------------------------------------------------------------- 
    $users = simplexml_load_file($xml_path);
    $newStatus = 0; 
    if($users){
        foreach($users->user as $user){
            if($user->user_id == $user_id){
                $user->status = $newStatus;
                $users->asXML($xml_path);
                break;
            }
        }
        [$exec, $err, $user_info] = getUserInfo($user_id);
        if ($exec == 1){
            [$exec, $err, $status] = isUserConnected($user_info);
            if(!$status){
                $err = null; 
                $exec = 1;
            } else {
                $exec = 0;
            }
        }
    } 
    return [$exec, $err];
}
function userLogin($user_id, $password){
    $exec = 0; 
    $err = "Error al iniciar sesión.";
    // --------------------------------------------------------------- 
    if (isset($user_id) && isset($password)) {
        [$exec, $err, $user_info] = getUserInfo($user_id);
        if (!empty($user_info)){
            if ($password == $user_info['password']){
                [$exec, $err, $status] = isUserConnected($user_info);
                if ($exec == 1){
                    if (!$status){
                        [$exec, $err] = writeConnection($user_id);
                        if ($exec == 1){
                            $exec = 1; 
                            $err = null; 
                        }
                    } else {
                        $exec = 0; 
                        $err = "El usuario " . $user_id . " ya está conectado.";
                    }
                }
            } else {
                $err = "Contraseña incorrecta.";
                $exec = 0;
            }
        }
    } 
    return [$exec, $err];
}
function userLogout($user_id){
    $exec = 0; 
    $err = "Error al desconectar el usuario.";
    // --------------------------------------------------------------- 
    if (isset($user_id)) {
        [$exec, $err, $user_info] = getUserInfo($user_id);
        if (!empty($user_info) and $exec == 1){
            [$exec, $err, $status] = isUserConnected($user_info);
            if ($exec == 1){
                if ($status){
                    [$exec, $err] = modUserStatus($user_id);
                    if ($exec == 1){
                        $exec = 1;
                        $err = null;
                    }
                } else {
                    $exec = 0; 
                    $err = "El usuario" . $user_id . "ya está desconectado (inactividad o ejecución).";
                }
            }
        }
    }
    return [$exec, $err];
}
?>