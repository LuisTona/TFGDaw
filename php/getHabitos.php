<?php
  require_once './conexion.php';
  $con = new Conexion();
  if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $datos = json_decode(file_get_contents("php://input"), true);
    if(@$datos['user']){
      try {
        $userName =  $datos['user'];

        $stmt = $con->prepare("SELECT * FROM users WHERE name = ?");
        $stmt->bind_param("s", $userName);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        $idUser = (int) $user['id'];
        $sql="SELECT h.*, hl.nombre AS habitoName, n.url_imagen,
            (SELECT MIN(experiencia)
              FROM niveles
              WHERE experiencia > h.nivel
            ) AS experiencia
              FROM habitos h
              JOIN habitslist hl ON h.habito = hl.id
              JOIN niveles n ON n.experiencia = (
                  SELECT MAX(experiencia)
                  FROM niveles
                  WHERE experiencia <= h.nivel
              ) WHERE h.user_id = $idUser";
        $result = $con->query($sql);
        $habitos = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($habitos);
        header('HTTP/1.1 200 Bad Request');
      } catch (mysqli_sql_exception $e) {
        echo $e;
        header('HTTP/1.1 400 Bad Request');
      }
    }
  }
?>