<?php
    require_once './conexion.php';
    $con = new Conexion();

    require '../vendor/autoload.php';
    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $datos = json_decode(file_get_contents("php://input"), true);

        if($datos != null){
            try {
                
                $codificada = $datos['token'];
                $claveSecreta = 'goHabitsUserToken';

                $decodificada =JWT::decode($codificada, new key($claveSecreta, 'HS256'));

                $id = $decodificada->data->id;
                $rol = $decodificada->data->rol;

                try {
                    if($rol != 'Organizador'){
                        $sql ="DELETE FROM usuarios WHERE `id` = '$id'";
                        $con->query($sql);
                    }else{
                        $sql ="DELETE FROM organizadores WHERE `id` = '$id'";
                        $con->query($sql);
                    }
                    header('HTTP/1.1 200 OK');
                } catch (mysqli_sql_exception $e) {
                    header("HTTP/1.1 401 Not found");
                }
            } catch (mysqli_sql_exception $e) {
                header('HTTP/1.1 400 Bad Request');
            }
        }
    }
?>
