<?php
    require_once './conexion.php';
    $con = new Conexion();

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        
        $datos = json_decode(file_get_contents("php://input"), true);
        if(@$datos['id']){
            $id = $datos['id'];
            try {
                $sql = "SELECT * FROM carreras WHERE `id` = '$id'";
                $results = $con->query($sql);
                $carreras = $results->fetch_all(MYSQLI_ASSOC);
                header('HTTP/1.1 200 OK');
                echo json_encode($carreras);
            } catch (mysqli_sql_exception $e) {
                echo $e;
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
                $idRace = $datos['carrera'];
                $sql = "UPDATE carreras SET `nombre`='$nombre',`provincia`='$provincia',`distancia`='$distancia',`desnivel`='$desnivel',`tipo`='$tipo',`dificultad`='$dificultad',
                                        `duracion`='$duracion',`descripcion`='$descripcion',`fecha_realizacion`='$fecha_realizacion' WHERE `id` = '$idRace'";
                $con->query($sql);                        
                header('HTTP/1.1 201 Created');
            } catch (mysqli_sql_exception $e) {
                echo $e;
                header('HTTP/1.1 401 Bad Request');
                die();
            }
        }
    }
?>