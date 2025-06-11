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
                $user = $datos['user'];
                $sql = "SELECT nombre, nombre_usuario, correo_electronico, telefono, localidad, organizacion FROM organizadores WHERE nombre_usuario = '$user'";
                $result = $con->query($sql);
                $trueUser = $result->fetch_assoc();
                
                if($trueUser == ''){
                    $sql = "SELECT nombre, nombre_usuario, correo_electronico, localidad FROM usuarios WHERE nombre_usuario = '$user'";
                    $result = $con->query($sql);
                    $trueUser = $result->fetch_assoc();
                    header('HTTP/1.1 200 Ok');
                    echo json_encode($trueUser);
                    
                }else if($trueUser != null){
                    header('HTTP/1.1 200 Ok');
                    echo json_encode($trueUser);
               
                }else{
                    header('HTTP/1.1 410 Unkown User');    
                    die();
                }

            } catch (mysqli_sql_exception $e) {
                header('HTTP/1.1 400 Bad Request');    
                die();
            }            
        }else{
            header('HTTP/1.1 401 Bad Request');
            die();
        }
    }else if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        $datos = json_decode(file_get_contents("php://input"), true);

        

            $codificada = $datos['token'];
            $claveSecreta = 'goHabitsUserToken';
    
            $decodificada =JWT::decode($codificada, new key($claveSecreta, 'HS256'));

            $id = $decodificada->data->id;
            $rol = $decodificada->data->rol;
            $userName = $datos['datos']['userName'];
            $name = $datos['datos']['name'];
            $mail = $datos['datos']['mail'];
            $provincia = $datos['datos']['provincia'];
            @$tlfn = $datos['datos']['tlfn'];
            @$culbName = $datos['datos']['clubName'];

            try {
                if($rol != "Organizador"){
                $sql = "UPDATE usuarios SET nombre = '$name', nombre_usuario = '$userName', correo_electronico = '$mail',  localidad = '$provincia'
                        WHERE id = '$id'";
                $con->query($sql);
                }else{
                    $sql = "UPDATE organizadores SET nombre = '$name', nombre_usuario = '$userName', correo_electronico = '$mail', telefono = '$tlfn', localidad = '$provincia', organizacion = '$culbName'
                            WHERE id = '$id'";
                    $con->query($sql);
                }
            } catch (mysqli_sql_exception $e) {
                header('HTTP/1.1 401 Bad Request');
                die();
            }
       
        // if(@$datos['pass'] != ''){

        //     header('HTTP/1.1 409 Entro asqui');
        //     $codificada = $datos['token'];
        //     $claveSecreta = 'goHabitsUserToken';
    
        //     $decodificada =JWT::decode($codificada, new key($claveSecreta, 'HS256'));

        //     $id = $decodificada->data->id;

        //     try {
        //     // aqui vamos a comprobar que la contraseña actual es la correcta, en caso de no serlo devolvemos un error
        //         $info = "SELECT contraseña FROM usuarios WHERE id = '$id' ";
        //         $result = $con->query($info);
        //         $user = $result->fetch_assoc();

        //         $pass = $datos['pass']['pasAct']; 
                
        //         if(count($user) >= 0){
        //             $contra = $user['contraseña'];
        //             $password = password_verify($pass, $contra);
        //             if($password != 1){
        //                 header('HTTP/1.1 409 Incorrect credentials');
        //                 die();
        //             }
        //         }else{
        //             header('HTTP/1.1 400 Bad Request');
        //             die();
        //         }

        //         try {
        //             // Una vez se ha comprobado hacemos el cambio de contraseña al usuario
        //             $pass = $datos['pass']['newPas'];
        //             $password = password_hash($pass, PASSWORD_BCRYPT);
        //             $change = "UPDATE usuarios SET `contraseña` = '$password' WHERE `id` = '$id' ";

        //             $con->query($change);
        //         } catch (mysqli_sql_exception $e) {
        //             header('HTTP/1.1 400 Bad Request');
        //             die();
        //         }
        //     }catch (mysqli_sql_exception $e) {
        //         header('HTTP/1.1 400 Bad Request');
        //         die();
        //     }
        // }
    }else{
        header('HTTP/1.1 400 Bad Request');
        die();
    }
?>