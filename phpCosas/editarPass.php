<?php
    require_once './conexion.php';
    $con = new Conexion();
    
    require '../vendor/autoload.php';
    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;
    $datos = json_decode(file_get_contents("php://input"), true);

     $codificada = $datos['token'];
     $claveSecreta = 'goHabitsUserToken';

     $decodificada =JWT::decode($codificada, new key($claveSecreta, 'HS256'));

     $id = $decodificada->data->id;
     $rol = $decodificada->data->rol;

     try {
        // aqui vamos a comprobar que la contraseña actual es la correcta, en caso de no serlo devolvemos un error
        // Busqueda de organizadores 
        $infoOrg = "SELECT contraseña FROM organizadores WHERE id = $id ";
        $resultorg = $con->query($infoOrg);
        $usersOrg = $resultorg->fetch_assoc();
        // Busqueda de usuarios normales
        $infoUse = "SELECT contraseña FROM usuarios WHERE id = $id";
        $resultUse = $con->query($infoUse);
        $usersUse = $resultUse->fetch_assoc();    

        $pass = $datos['oldPass']; 
         
        if($rol == "Organizador"){
            $contra = $usersOrg['contraseña'];
            $password = password_verify($pass, $contra);
            if($password != 1){
                header('HTTP/1.1 419 Incorrect credentials');
                die();
            }
            
            try {
                // Una vez se ha comprobado hacemos el cambio de contraseña al usuario
                $pass = $datos['pass'];
                $password = password_hash($pass, PASSWORD_BCRYPT);
                $change = "UPDATE organizadores SET `contraseña` = '$password' WHERE `id` = '$id' ";
    
                $con->query($change);
            } catch (mysqli_sql_exception $e) {
                header('HTTP/1.1 400 Bad Request');
                die();
            }
        }else{
            $contra = $usersUse['contraseña'];
            $password = password_verify($pass, $contra);
            if($password != 1){
                header('HTTP/1.1 409 Incorrect credentials');
                die();
            }
            try {
                // Una vez se ha comprobado hacemos el cambio de contraseña al usuario
                $pass = $datos['pass'];
                $password = password_hash($pass, PASSWORD_BCRYPT);
                $change = "UPDATE usuarios SET `contraseña` = '$password' WHERE `id` = '$id' ";
    
                $con->query($change);
            } catch (mysqli_sql_exception $e) {
                header('HTTP/1.1 400 Bad Request');
                die();
            }
        }
    }catch (mysqli_sql_exception $e) {
        header('HTTP/1.1 400 Bad Request');
        die();
    }
?>