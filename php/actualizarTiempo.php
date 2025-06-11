<?php
  require_once './conexion.php';
  $con = new Conexion();
if($_SERVER['REQUEST_METHOD'] == 'POST'){
  $datos = json_decode(file_get_contents("php://input"), true);
  if(@$datos['function'] == 'update'){
    try {

      $idHabito = (int) $datos['id'];
      $duracion = (int) $datos['tiempoRestante'];

      $sql = "UPDATE habitos SET tiempoRealizado = $duracion, realizadoHoy = 1 WHERE id = $idHabito";
      $con->query($sql);

      header('HTTP/1.1 200 Bad Request');
    } catch (mysqli_sql_exception $e) {
      echo $e;
      header('HTTP/1.1 400 Bad Request');
    }
  }else if (@$datos['function'] == 'get') {
    try {

      $idHabito = (int) $datos['id'];

      $sql = "SELECT * FROM habitos WHERE id = $idHabito";
      $result = $con->query($sql);
      $habito = $result->fetch_all(MYSQLI_ASSOC);

      echo json_encode($habito);
      header('HTTP/1.1 200 Bad Request');
    } catch (mysqli_sql_exception $e) {
      echo $e;
      header('HTTP/1.1 400 Bad Request');
    }
  }else if (@$datos['function'] == 'updateCompletoDia') {
    try {
      $idHabito = (int) $datos['id'];
      $duracion = 0;
      $realizado = (int) $datos['realizado'];

      $sql = "UPDATE habitos SET tiempoRealizado = $duracion, realizadoHoy = 0, realizadoFrecuencia = $realizado, nivel = $realizado WHERE id = $idHabito";
      $con->query($sql);

      header('HTTP/1.1 200 Bad Request');
    } catch (mysqli_sql_exception $e) {
      echo $e;
      header('HTTP/1.1 400 Bad Request');
    }

  }
}
?>