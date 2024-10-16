<!-- http://localhost:8080/CartProject/com/connection/connection.php -->
<!-- http://localhost:8080/CartProject/com/connection/connection.php?user=user1&pwd=password123 -->
 <?php
// libxml_use_internal_errors(true); 
// require_once 'com\user\user.php';

function writeConnection($user_id){
    $exec = 0;
    $err = "Error al gestionar la conexión.";
    $xml_path = 'xmldb/connections.xml';
    $users_xml_path = 'xmldb/users.xml';
    // --------------------------------------------------------------- 
    if(!file_exists($xml_path)){
        $connections = new SimpleXMLElement('<connections></connections>');
    } else {
        $connections = simplexml_load_file($xml_path);
    }
    try{
        $connection = $connections->addChild('connection');
        $connection->addChild('user_id', $user_id);
        $connection->addChild('date', date('Y-m-d H:i:s'));
        $connections->asXML($xml_path);
        $exec = 1; 
        $err = null;
    } catch (Exception $e){
        $err = "Error al escribir la conexión.";
        return [$exec, $err];
    }
    if($exec==1){
        $newStatus = 1;
        try{
            $users = simplexml_load_file($users_xml_path);
            foreach($users->user as $user){
                if($user->user_id == $user_id){
                    $user->status = $newStatus;
                    $exec = 1;
                    break;
                }
            }
        } catch (Exception $e) {
            $exec = 0; 
            $err = "Error al actualizar el estado en el usuario.";
            [$exec, $err];
        }
    }
    $users->asXML($users_xml_path);
    return [$exec, $err];
}

function unsetConnection($user_id){
    // * Esta función se ejecuta cada vez que la página carga.
    // Se ocupa de que las conexiones >= 5 minutos tengan al usuario
    // con status = 0. 
    // * No devuelve nada. 
    $connections_xml_path = 'xmldb/connections.xml';
    $users_xml_path = 'xmldb/users.xml';
    // --------------------------------------------------------------- 
    $connections = simplexml_load_file($connections_xml_path);
    $users = simplexml_load_file($users_xml_path);
    foreach ($connections->connection as $connection) {
        $currentTime = time();
        $connectionTime = strtotime($connection->date);
        $expirationTime = $connectionTime + (5 * 60);
        // ↑ El usuario no podrá volver a loggearse sin desloggear
        // hasta pasados 5 min. 
        if ($currentTime > $expirationTime) {
            $user_id = $connection->user_id; 
            [$exec, $err, $user_info] = getUserInfo($user_id);
            if (!empty($user_info)){
                if($user_info['status'] == 1){
                    [$exec, $err] = modUserStatus($user_id);
                }
            } 
        }
    }
}
?>
