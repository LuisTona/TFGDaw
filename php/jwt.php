<?php
    require_once './conexion.php';
    $con = new Conexion();

    require '../vendor/autoload.php';
    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        try {

            $datos = json_decode(file_get_contents("php://input"), true);
            try {
                $sql = "SELECT * FROM users WHERE name = '$datos[username]'";
                $result = $con->query($sql);
                $user = $result->fetch_assoc();
            } catch (mysqli_sql_exception $e) {
                header("HTTP/1.1 404 Not Found");
            }

            $claveSecreta = 'goHabitsUserToken';
            $time = time();

            $data=array(
                'id' => $user['id'],
                'usuario' => $user['name'],
            );

            $payload = array(
                'ait'=>$time,
                'exp'=>$time +3600,
                'data'=>$data
            );

            $codificada =JWT::encode($payload, $claveSecreta, 'HS256');
            header("Content-type:Application/Json");
            $token = array(
                'codificada' => $codificada,
                'user' => $user['name'],
            );

            echo json_encode($token);
            // $decodificada =JWT::decode($codificada, new key($claveSecreta, 'HS256'));

            // echo "<br>";
            // echo "codificada -----> $codificada";
            // echo "<br>";
            // print_r ( $decodificada);
            // echo "<br>";
            // print_r ($decodificada->data->usuario);

        } catch (mysqli_sql_exception $e) {
            header('HTTP/1.1 400 Bad request');
        }
    }

?>