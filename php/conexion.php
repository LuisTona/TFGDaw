<?php
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    header("Allow: GET, POST, OPTIONS, PUT, DELETE");
    
    Class Conexion extends mysqli {
        private $host = 'localhost';
        private $db = 'goHabits';
        private $user = 'root';
        private $pass = '';
    
        public function __construct(){
            try{
                parent::__construct($this->host,$this->user,$this->pass,$this->db);
            }catch(mysqli_sql_exception $e){
                echo"Error: {$e->getMessage()}";
                exit;
            }
        }
    }

    
?>
