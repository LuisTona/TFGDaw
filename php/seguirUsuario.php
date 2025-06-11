  <?php
    require_once './conexion.php';
    $con = new Conexion();
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $datos = json_decode(file_get_contents("php://input"), true);
    if(@$datos['user']){
      try {

        $userName = $datos['user'];
        $sql = "SELECT * FROM users WHERE name = '$userName'";
        $user = $con->query($sql)->fetch_assoc();
        $idUser = (int) $user['id'];

        $userName = $datos['seguir'];
        $sql = "SELECT * FROM users WHERE name = '$userName'";
        $user = $con->query($sql)->fetch_assoc();
        $idUserSeguido = (int) $user['id'];

        $sql = "SELECT 1 FROM seguidores WHERE id_user = $idUser AND id_userSeguidor = $idUserSeguido ;";
        $result = $con->query($sql);

        if($datos['function'] == 'seguir'){
          if($result->num_rows == 0){
            $sql = "INSERT INTO seguidores (id_user, id_userSeguidor) VALUES ( $idUser, $idUserSeguido)";
            $con->query($sql);

            header('HTTP/1.1 201 Created');
          }else{
            $sql = "DELETE FROM seguidores WHERE id_user = $idUser AND id_userSeguidor= $idUserSeguido";
            $con->query($sql);
            header('HTTP/1.1 202 Deleted');
          }
        }else if($datos['function'] == 'comprobar'){
          if($result->num_rows > 0){
            header('HTTP/1.1 201 Checked');
          }else{
            header('HTTP/1.1 200 Ok');
          }

        }
      } catch (mysqli_sql_exception $e) {
        echo $e;
        header('HTTP/1.1 400 Bad Request');
      }
    }
  }
  ?>