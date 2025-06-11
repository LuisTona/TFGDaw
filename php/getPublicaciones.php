<?php
  require_once './conexion.php';
  $con = new Conexion();

  if($_SERVER['REQUEST_METHOD'] == 'POST'){
    try {
      $datos = json_decode(file_get_contents("php://input"), true);
      $userName =  $datos['user'];
      $sql = "SELECT * FROM users WHERE name = '$userName'";
      $user = $con->query($sql)->fetch_assoc();
      $idUser = (int) $user['id'];

      $limit = 10;
      $offset = $datos['pagina'];
      $sqlPublicaciones = "
        SELECT p.*, u.name AS autor_nombre, u.imagenUser AS foto_perfil, h.nombre AS nombre_habito, n.url_imagen AS imagen
        FROM publicaciones p
        JOIN users u ON p.id_user = u.id
        JOIN habitslist h ON p.id_habito = h.id
        JOIN niveles n ON p.id_imagen = n.id
        WHERE p.id_user = $idUser
          OR p.id_user IN (
              SELECT id_userSeguidor
              FROM seguidores
              WHERE id_user = $idUser
          )
        ORDER BY p.fecha DESC
        LIMIT $limit OFFSET $offset
        ";

      $result = $con->query($sqlPublicaciones);
      $publicaciones = $result->fetch_all(MYSQLI_ASSOC);
      foreach ($publicaciones as &$publicacion) {
        $idPublicacion = $publicacion['id'];

        $sqlComentarios = "
          SELECT c.*, u.name AS autor_comentario, u.imagenUser AS foto_perfil
          FROM comentarios c
          JOIN users u ON c.id_user = u.id
          WHERE c.id_publicacion = $idPublicacion
          ORDER BY c.fecha ASC
          LIMIT 3";

        $result = $con->query($sqlComentarios);
        if($result){
          $comentarios = $result->fetch_all(MYSQLI_ASSOC);
        }else{
          $comentarios = ['a'];
        }
        $publicacion['comentarios'] = $comentarios;
      }
      unset($publicacion);

      echo json_encode($publicaciones);
      header('HTTP/1.1 200 OK');

    } catch (mysqli_sql_exception $e) {
      echo $e;
      header('HTTP/1.1 400 Bad Request');
    }
  }
?>