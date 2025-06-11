<?php
  require_once './conexion.php';
  $con = new Conexion();
  // Aqui se recoge la lista de habitos para utilizar en el select
  // el else se utiliza para publicar el nuevo habito si el usuario lo desea
  if($_SERVER['REQUEST_METHOD'] == 'GET'){
    try {
        $sql = 'SELECT * FROM habitsList';
        $results = $con->query($sql);
        $habitos = $results->fetch_all(MYSQLI_ASSOC);
        header('HTTP/1.1 200 OK');
        echo json_encode($habitos);
    } catch (mysqli_sql_exception $e) {
        header('HTTP/1.1 400 Bad Request');
      }
  }else if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $datos = json_decode(file_get_contents("php://input"), true);
    if(@$datos['user']){
      try {
        $userName =  $datos['user'];
        $sql = "SELECT * FROM users WHERE name = '$userName'";
        $user = $con->query($sql)->fetch_assoc();

        $idUser = (int) $user['id'];
        $comentario = $datos['comentario'];
        $id_imagen = (int) $datos['id_imagen'];
        $id_habito = (int) $datos['id_habito'];
        $sql = "SELECT id
          FROM habitos
          WHERE user_id = $idUser
          ORDER BY desde DESC
          LIMIT 1";
        $results = $con->query($sql);
        $idPublicacion = $results->fetch_assoc();
        $idPublicacion = (int) $idPublicacion['id'];
        $sql = "INSERT INTO publicaciones (id_user, comentario, id_imagen, id_habito, id_publicacion_habito)
        VALUES($idUser, '$comentario', $id_imagen, $id_habito, $idPublicacion)";
        $con->query($sql);
        header('HTTP/1.1 200 Bad Request');
      } catch (mysqli_sql_exception $e) {
        echo $e;
        header('HTTP/1.1 400 Bad Request');
      }
    }
  }
?>