<?php
define('TAMAÑOMAXFICHERO', 500000);
require_once "vendor/autoload.php";


function crudAccesoControl($login,$pass) : bool {
    $db = AccesoDatos::getModelo();
    return $db->accesoControl($login,$pass);
}

function crudRol($login) : int {
    $db = AccesoDatos::getModelo();
    return $db->getUsuario($login);
}


function crudBorrar ($id){    
    $db = AccesoDatos::getModelo();
    $resu = $db->borrarCliente($id);
    if ( $resu){
         $_SESSION['msg'] = " El usuario ".$id. " ha sido eliminado.";
    } else {
         $_SESSION['msg'] = " Error al eliminar el usuario ".$id.".";
    }
}

function crudTerminar(){
    AccesoDatos::closeModelo();
    session_destroy();
}
 
function crudAlta(){
    $cli = new Cliente();
    $orden= "Nuevo";
    include_once "app/views/formulario.php";
}

function crudDetalles($id){
    $db = AccesoDatos::getModelo();
    $cli = $db->getCliente($id);
    include_once "app/views/detalles.php";
}

function crudDetallesSiguiente($id){ 
    $db = AccesoDatos::getModelo();
    $cli = $db->getCliente($id);

    $ordenar = $_SESSION['ordenar'];
    $dato = $cli->$ordenar;

    $posId = $db->getClienteSiguiente($ordenar,$dato);
    
    if($posId != false){
        $cli = $db->getCliente($posId->id);
    }

    include_once "app/views/detalles.php";
}

function crudDetallesAnterior($id){ 
    $db = AccesoDatos::getModelo();
    $cli = $db->getCliente($id);

    $ordenar = $_SESSION['ordenar'];
    $dato = $cli->$ordenar;

    $preId = $db->getClienteAnterior($ordenar,$dato);

    if($preId != false){
        $cli = $db->getCliente($preId->id);
    }

    include_once "app/views/detalles.php";
}


function crudModificar($id){
    $db = AccesoDatos::getModelo();
    $cli = $db->getCliente($id);
    $orden="Modificar";
    include_once "app/views/formularioAltaModi.php";
}

function crudModificarSiguiente($id){ 
    $db = AccesoDatos::getModelo();
    $cli = $db->getCliente($id);

    $ordenar = $_SESSION['ordenar'];
    $dato = $cli->$ordenar; 

    $posId = $db->getClienteSiguiente($ordenar,$dato);
   
    if($posId != false){
        $cli = $db->getCliente($posId->id);
    }

    $orden="Modificar";
    include_once "app/views/formularioAltaModi.php";
}

function crudModificarAnterior($id){ 
    $db = AccesoDatos::getModelo();
    $cli = $db->getCliente($id);

   $ordenar = $_SESSION['ordenar'];
    $dato = $cli->$ordenar;

    $preId = $db->getClienteAnterior($ordenar,$dato);

    if($preId != false){
        $cli = $db->getCliente($preId->id);
    }
    
    $orden="Modificar";
    include_once "app/views/formularioAltaModi.php";
}

function crudPostAlta(){
    limpiarArrayEntrada($_POST); //Evito la posible inyección de código
  
    $cli = new Cliente();
    $cli->id            =$_POST['id'];
    $cli->first_name    =$_POST['first_name'];
    $cli->last_name     =$_POST['last_name'];
    $cli->email         =$_POST['email'];	
    $cli->gender        =$_POST['gender'];
    $cli->ip_address    =$_POST['ip_address'];
    $cli->telefono      =$_POST['telefono'];
    $db = AccesoDatos::getModelo();

    $tipo =  $_FILES['foto']['type'];
    $tam = $_FILES['foto']['size'];
     
    //Email
    if (!filter_var($cli->email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['msg'] = "El email no es válido.";
        return false;
    }
    //Comprobar que no se repite el correo electrónico
    $db = AccesoDatos::getModelo();
    if($db->getEmailRepe($cli->email,$cli->id)) {
        $_SESSION['msg'] = "El email ".$cli->email." ya existe. ";
        return false;
    }
    
    //IP
    if (!filter_var($cli->ip_address, FILTER_VALIDATE_IP)) {
        $_SESSION['msg'] = "La dirección IP no es válida.";
        return false;
    }
    
    //Telefono
    if ( !preg_match('/^\d{3}-\d{3}-\d{4}$/', $cli->telefono)) {
        $_SESSION['msg'] = "El número de teléfono no es válido.";
        return false;
    }

    //Imagen
    if ( $tipo != "" && $tipo != "image/jpeg" && $tipo != "image/png") {
        $_SESSION['msg'] = "El fichero no es jpeg/png";
        return false;
    } 
    if ($tam != "" && $tam > TAMAÑOMAXFICHERO) {
        $_SESSION['msg'] = "El tamaño supera los 500Kb";
        return false;
    } 

    if($db->addCliente($cli)){
        $_SESSION['msg'] = "El cliente se ha añadido correctamete";
    }  else {
        $_SESSION['msg'] = "Error: el cliente no se ha podido añadir";

    }
}


function crudPostModificar(){
    limpiarArrayEntrada($_POST); //Evito la posible inyección de código
    $cli = new Cliente();

    $cli->id            =$_POST['id'];
    $cli->first_name    =$_POST['first_name'];
    $cli->last_name     =$_POST['last_name'];
    $cli->email         =$_POST['email'];	
    $cli->gender        =$_POST['gender'];
    $cli->ip_address    =$_POST['ip_address'];
    $cli->telefono      =$_POST['telefono'];
    $db = AccesoDatos::getModelo();

    $tipo =  $_FILES['foto']['type'];
    $tam = $_FILES['foto']['size'];
     
    //Email
    if (!filter_var($cli->email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['msg'] = "El email no es válido.";
        return false;
    }
    //Comprobar que no se repite el correo electrónico
    $db = AccesoDatos::getModelo();
    if($db->getEmailRepe($cli->email,$cli->id)) {
        $_SESSION['msg'] = "El email ".$cli->email." ya existe. ";
        return false;
    }
    
    //IP
    if (!filter_var($cli->ip_address, FILTER_VALIDATE_IP)) {
        $_SESSION['msg'] = "La dirección IP no es válida.";
        return false;
    }
    
    //Telefono
    if ( !preg_match('/^\d{3}-\d{3}-\d{4}$/', $cli->telefono)) {
        $_SESSION['msg'] = "El número de teléfono no es válido.";
        return false;
    }

    if ( $tipo != "" && $tipo != "image/jpeg" && $tipo != "image/png") {
        $_SESSION['msg'] = "El fichero no es jpeg/png";
        return false;
    } 
    if ($tam != "" && $tam > TAMAÑOMAXFICHERO) {
        $_SESSION['msg'] = "El tamaño supera los 500Kb";
        return false;
    } 

    if($db->modCliente($cli)){
        $_SESSION['msg'] = "El cliente se ha modificado correctamete";
    }  else {
        $_SESSION['msg'] = "Error: el cliente no se ha podido modificar";

    }
}

//Seleccionar la bandera según IP
function crudBanderaIp($ip) : string {
    $pais_JSON = file_get_contents("http://ip-api.com/json/$ip?fields=status,countryCode,query");
    $pais = json_decode($pais_JSON,true);
    if($pais['status']=="success") {
        $code = strtolower($pais['countryCode']);
        return "https://flagcdn.com/$code.svg";
    }

    return "https://flagcdn.com/un.svg";
}

function generarPDF($id) {
    ob_start();
    $db = AccesoDatos::getModelo();
    $cli = $db->getCliente($id);
    include_once "app/views/detallespdf.php";
    $contenido = ob_get_clean();

    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML($contenido);
    $mpdf->Output(); 
}
