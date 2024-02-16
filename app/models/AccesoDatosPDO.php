<?php

/*
 * Acceso a datos con BD Usuarios : 
 * Usando la librería PDO *******************
 * Uso el Patrón Singleton :Un único objeto para la clase
 * Constructor privado, y métodos estáticos 
 */

class AccesoDatos {
    
    private static $modelo = null;
    private $dbh = null;
    
    public static function getModelo(){
        if (self::$modelo == null){
            self::$modelo = new AccesoDatos();
        }
        return self::$modelo;
    }
    
    

   // Constructor privado  Patron singleton
   
    private function __construct(){
        try {
            $dsn = "mysql:host=".DB_SERVER.";dbname=".DATABASE.";charset=utf8";
            $this->dbh = new PDO($dsn,DB_USER,DB_PASSWD);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e){
            echo "Error de conexión ".$e->getMessage();
            exit();
        }  

    }

    // Cierro la conexión anulando todos los objectos relacioanado con la conexión PDO (stmt)
    public static function closeModelo(){
        if (self::$modelo != null){
            $obj = self::$modelo;
            // Cierro la base de datos
            $obj->dbh = null;
            self::$modelo = null; // Borro el objeto.
        }
    }


    // Devuelvo cuantos filas tiene la tabla

    public function numClientes ():int {
      $result = $this->dbh->query("SELECT id FROM Clientes");
      $num = $result->rowCount();  
      return $num;
    } 
      
    // SELECT Devuelvo un usuario o false
    public function getCliente (int $id) {
        $cli = false;
        $stmt_cli   = $this->dbh->prepare("select * from Clientes where id=:id");
        $stmt_cli->setFetchMode(PDO::FETCH_CLASS, 'Cliente');
        $stmt_cli->bindParam(':id', $id);
        if ( $stmt_cli->execute() ){
             if ( $obj = $stmt_cli->fetch()){
                $cli= $obj;
            }
        }
        return $cli;
    }
    
    //Ejercicio 2: ORDENAR-> actualizar getClientes --> obtener clientes ordenados
    // SELECT Devuelvo la lista de Usuarios
    public function getClientes ($primero,$cuantos,$ordenar):array {
        $tuser = [];
        // Crea la sentencia preparada
    // echo "<h1> $primero : $cuantos  </h1>";
        $stmt_usuarios  = $this->dbh->prepare("select * from Clientes ORDER BY $ordenar limit $primero,$cuantos");
        // Si falla termina el programa
        $stmt_usuarios->setFetchMode(PDO::FETCH_CLASS, 'Cliente');
    
        if ( $stmt_usuarios->execute() ){
            while ( $user = $stmt_usuarios->fetch()){
            $tuser[]= $user;
            }
        }
                // Devuelvo el array de objetos
        return $tuser;
    }

    public function getClienteSiguiente($ordenar,$dato){
        $cli = false;
        $stmt_cli   = $this->dbh->prepare("select id from Clientes where $ordenar > ? ORDER BY $ordenar limit 1");
        
        $stmt_cli->bindParam(1,$dato);
        $stmt_cli->setFetchMode(PDO::FETCH_CLASS, 'Cliente');
        
        if ( $stmt_cli->execute() ){
            if ( $obj = $stmt_cli->fetch()){
               $cli= $obj;
            }
        }

        return $cli;
    }  
    
    public function getClienteAnterior($ordenar,$dato){
        $cli = false;
        $stmt_cli   = $this->dbh->prepare("select id from Clientes where $ordenar < ? ORDER BY $ordenar DESC limit 1");
        
        $stmt_cli->bindParam(1,$dato);
        $stmt_cli->setFetchMode(PDO::FETCH_CLASS, 'Cliente');
        
        if ( $stmt_cli->execute() ){
            if ( $obj = $stmt_cli->fetch()){
              $cli= $obj;
            }
        }
        return $cli;
    }

    // UPDATE TODO
    public function modCliente($cli):bool{ 
        $stmt_moduser   = $this->dbh->prepare("update Clientes set first_name=:first_name,last_name=:last_name".
        ",email=:email,gender=:gender, ip_address=:ip_address,telefono=:telefono WHERE id=:id");
        $stmt_moduser->bindValue(':first_name', $cli->first_name);
        $stmt_moduser->bindValue(':last_name'   ,$cli->last_name);
        $stmt_moduser->bindValue(':email'       ,$cli->email);
        $stmt_moduser->bindValue(':gender'      ,$cli->gender);
        $stmt_moduser->bindValue(':ip_address'  ,$cli->ip_address);
        $stmt_moduser->bindValue(':telefono'    ,$cli->telefono);
        $stmt_moduser->bindValue(':id'          ,$cli->id);

        $stmt_moduser->execute();
        $resu = ($stmt_moduser->rowCount () == 1);
        return $resu;
    }

  
    //INSERT 
    public function addCliente($cli):bool{
       
        // El id se define automáticamente por autoincremento.
        $stmt_crearcli  = $this->dbh->prepare(
            "INSERT INTO `Clientes`( `first_name`, `last_name`, `email`, `gender`, `ip_address`, `telefono`)".
            "Values(?,?,?,?,?,?)");
        $stmt_crearcli->bindValue(1,$cli->first_name);
        $stmt_crearcli->bindValue(2,$cli->last_name);
        $stmt_crearcli->bindValue(3,$cli->email);
        $stmt_crearcli->bindValue(4,$cli->gender);
        $stmt_crearcli->bindValue(5,$cli->ip_address);
        $stmt_crearcli->bindValue(6,$cli->telefono);    
        $stmt_crearcli->execute();
        $resu = ($stmt_crearcli->rowCount () == 1);
        return $resu;
    }

   
    //DELETE 
    public function borrarCliente(int $id):bool {
        $stmt_boruser   = $this->dbh->prepare("delete from Clientes where id =:id");

        $stmt_boruser->bindValue(':id', $id);
        $stmt_boruser->execute();
        $resu = ($stmt_boruser->rowCount () == 1);
        return $resu;
        
    }

    //Ejercicio 3: Chequear datos
    public function getEmailRepe ($email, $id) {
        $cli = false;
        $resu = false;
        $stmt_cli   = $this->dbh->prepare("select * from Clientes where email=:email");
        $stmt_cli->bindParam(':email', $email);
        $stmt_cli->setFetchMode(PDO::FETCH_CLASS, 'Cliente');
        
        if ( $stmt_cli->execute() ){ 
           
            if ( $obj = $stmt_cli->fetch()){
                $cli= $obj;
                if(($stmt_cli->rowCount () == 1) && $id == $cli->id){
                    
                    $resu = false;
                }else{
                    $resu = true;
                }
            } else{
                $resu = false;
            }
        }
        return $resu;
    }

    function accesoControl($login,$pass) : bool {
        $stmt = $this->dbh->prepare("SELECT login, password FROM Usuario WHERE login= ? and password = ?");
        
        $stmt->bindValue(1, $login);
        $stmt->bindValue(2, $pass);
        
        $stmt->execute();

        //Si no existe el login devuelve falso
        if ($stmt->rowCount() != 1) {
            return false;
        }

        $pass= $stmt->fetch()[1];
        return $pass;
    }



    /*function accesoControl($login,$pass) : bool {
        $stmt = $this->dbh->prepare("SELECT login, password FROM Usuario WHERE login=:login");
        
        //acceso a la api con el login y login
        $stmt->bindValue(':login', $login);
        
        $stmt->execute();

        //Si no existe el login devuelve falso
        if ($stmt->rowCount() != 1) {
            return false;
        }

        $pass_base= $stmt->fetch()[1];
        return md5($pass)===$pass_base;
    } */


    function getUsuario($login) : int {
        $stmt = $this->dbh->prepare("SELECT rol FROM Usuario WHERE login=:login");
        
        //acceso a la api con el login y login
        $stmt->bindValue(':login', $login);
        $stmt->execute();

        return $stmt->fetch()[0];
    } 
    
    // Evito que se pueda clonar el objeto. (SINGLETON)
    public function __clone()
    { 
        trigger_error('La clonación no permitida', E_USER_ERROR); 
    }

    
}


