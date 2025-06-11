<?php
    require_once './conexion.php';
    $con = new Conexion();

    //añadir un usuario
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $datos = json_decode(file_get_contents("php://input"), true);

        if($datos != null){

            @$nombre = $datos['nombre'];
            @$mail = $datos['correo'];
            @$pass = $datos['pass'];

            try {

                // Busqueda de usuarios
                $info = "SELECT name, email FROM users WHERE name = '$nombre' OR email = '$mail' ";
                $result = $con->query($info);
                $users = $result->fetch_assoc();
                if($users['name'] != null ){
                    header("HTTP/1.1 405 Duplicate username");
                    die();
                }else if($users['email'] != null){
                    header("HTTP/1.1 406 Duplicate mail");
                    die();
                }

                try {
                    $password = password_hash($pass, PASSWORD_BCRYPT);
                    $sql = "INSERT INTO users (name, password, email)
                            VALUES('$nombre','$password', '$mail')";
                    $con->query($sql);
                    header("HTTP/1.1 201 Created");
                } catch (mysqli_sql_exception $e) {
                    header("HTTP/1.1 413 Bad Request");
                    die();
                }


            } catch (mysqli_sql_exception $e) {
                header("HTTP/1.1 412 Reject conexion");
                die();
            }

        }else{
            header("HTTP/1.1 411 No data");
            die();
        }

        exit;
    }else{
        header('HTTP/1.1 433 Not server request');
    }
?>