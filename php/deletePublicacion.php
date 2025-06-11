<?php
  require_once './conexion.php';
  $con = new Conexion();

  if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $datos = json_decode(file_get_contents("php://input"), true);
    if(@$datos['id']){
      try {
        $idHabito = $datos['id'];
        $sql = "DELETE FROM habitos WHERE id = $idHabito";
        $con->query($sql);
        header('HTTP/1.1 200 Bad Request');
      } catch (mysqli_sql_exception $e) {
        echo $e;
        header('HTTP/1.1 400 Bad Request');
      }
    }
}
?>