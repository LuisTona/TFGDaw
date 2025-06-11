<?php
    require_once './conexion.php';
    $con = new Conexion();

    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        try {
            $sql = 'SELECT * FROM carreras';
            $results = $con->query($sql);
            $carreras = $results->fetch_all(MYSQLI_ASSOC);
            header('HTTP/1.1 200 OK');
            echo json_encode($carreras);
        } catch (mysqli_sql_exception $e) {
            header('HTTP/1.1 400 Bad Request');
        }
    }else if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $datos = json_decode(file_get_contents("php://input"), true);
        
        if(@$datos['user']){
            $user = $datos['user'];
            try {
                
                $idUser = $con->query("SELECT id FROM usuarios WHERE nombre_usuario = '$user'")->fetch_assoc();
                $id = $idUser['id'];
                $sql = "SELECT nombre FROM carreras WHERE id_creador = '$id'";
                $results = $con->query($sql);
                $carreras = $results->fetch_all(MYSQLI_ASSOC);

                header('HTTP/1.1 200 OK');
                echo json_encode($carreras);

            } catch (mysqli_sql_exception $e) {
                header('HTTP/1.1 400 Bad Request');
            }
        }else if(@$datos['data']){
            $nombre = $datos['data']['raceName'];
            $provincia = $datos['data']['provincia'];
            $distancia = $datos['data']['long'];
            $desnivel = $datos['data']['desnivel'];
            $tipo = $datos['data']['tipo'];
            $dificultad = $datos['data']['dificultad'];
            $duracion = $datos['data']['duracion'];
            $descripcion = $datos['data']['descripcion'];
            $fecha_realizacion = $datos['data']['fecha_realizacion'];
            $fecha_creacion = date("Y-m-d");
            try {
                $user = $datos['nombUser'];
                
                $sql = "SELECT id FROM organizadores WHERE nombre_usuario = '$user' ";
                $result = $con->query($sql);
                $id = $result->fetch_assoc();
                
                if($id == null || $id == 0){
                    header('HTTP/1.1 420 Invalid permissons');
                }
                
            } catch (mysqli_sql_exception $e) {
                echo $e;
                header('HTTP/1.1 401 User not Found');
            }

            try {
                $sql = "INSERT INTO carreras (nombre, provincia, distancia, desnivel, tipo, dificultad, duracion, descripcion, fecha_realizacion, fecha_creacion)
                        VALUES('$nombre', '$provincia', '$distancia', '$desnivel', '$tipo', '$dificultad', '$duracion', '$descripcion', '$fecha_realizacion','$fecha_creacion')";
                $con->query($sql);                        
                header('HTTP/1.1 201 Created');
            } catch (mysqli_sql_exception $e) {
                echo $e;
                header('HTTP/1.1 401 Bad Request');
                die();
            }
            echo $id;
        }
        print_r($datos['data']);
    }
?>