<?php
  require_once './conexion.php';
  $con = new Conexion();
  if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $datos = json_decode(file_get_contents("php://input"), true);
    if(@$datos['user']){
      try {
        $userName =  $datos['user'];

        $stmt = $con->prepare("SELECT name, email, imagenUser FROM users WHERE name = ?");
        $stmt->bind_param("s", $userName);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        echo json_encode($user);

      } catch (mysqli_sql_exception $e) {
        header('HTTP/1.1 400 Bad Request');
        echo $e;
      }
    }
  }
?>