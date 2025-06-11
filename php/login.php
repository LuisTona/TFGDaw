<?php
    require_once './conexion.php';

    $con = new Conexion();
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        
        $datos = json_decode(file_get_contents("php://input"), true);

        if($datos != null){
            
            $userName = $datos['username'];
            $pass = $datos['logPass'];
            try {
                $info = "SELECT name, password FROM users WHERE name = '$userName' ";
                
                $result = $con->query($info);
                $user = $result->fetch_assoc();

                if(count($user) >= 0){
                    $contra = $user['password'];
                    $password = password_verify($pass, $contra);

                    if($user['name'] != $userName || $password != 1){
                        header("HTTP/1.1 409 Incorrect Credentials");
                        die();
                    }else{
                        header("HTTP/1.1  200 OK");
                    }

                }else{
                    header("HTTP/1.1 408 User Not Created");
                }

                    
                
            } catch (mysqli_sql_exception $e) {
                header("HTTP/1.1 400 Bad Request");
            }
        }
    }
?>