<?php
session_start();
define ('FPAG',10); // Número de filas por página


require_once 'app/helpers/util.php';
require_once 'app/config/configDB.php';
require_once 'app/models/Cliente.php';
require_once 'app/models/Usuario.php';
require_once 'app/models/AccesoDatosPDO.php';
require_once 'app/controllers/crudclientes.php';

$contenido = "";
$msg = "";

if (!isset($_SESSION['intentos'])) {
    $_SESSION['intentos'] = 0;
}

if (!isset($_SESSION["acceso"]) && $_SESSION['intentos'] < 3) {
    if (!isset($_GET["pass"])) {
        include "app/views/login.php";
        exit();
    } else if (!crudAccesoControl($_GET['login'],$_GET['pass'])) {
        $msg = "Login o contraseña incorrectos";
        $_SESSION['intentos']++;
        include "app/views/login.php";
        exit();
    } else {
        $_SESSION["acceso"] = "si";
        $_SESSION['rol'] = crudRol($_GET['login']);
        header("Refresh:0");
    }
} else if ($_SESSION['intentos'] >= 3) {
    include "app/views/bloqueo.php";
    exit();
}


//---- PAGINACIÓN ----
$midb = AccesoDatos::getModelo();
$totalfilas = $midb->numClientes();
if ( $totalfilas % FPAG == 0){
    $posfin = $totalfilas - FPAG;
} else {
    $posfin = $totalfilas - $totalfilas % FPAG;
}

if ( !isset($_SESSION['posini']) ){
  $_SESSION['posini'] = 0;
}
$posAux = $_SESSION['posini'];

//Ordenación por campos
if ( !isset($_SESSION['ordenar']) ){
    $_SESSION['ordenar'] = 'id';
}
if ( !isset($_SESSION['autofocus']) ){
    $_SESSION['autofocus'] = 'first_name';
}

// Borro cualquier mensaje "
$_SESSION['msg']=" ";

ob_start(); // La salida se guarda en el bufer
if ($_SERVER['REQUEST_METHOD'] == "GET" ){
    
    // Proceso las ordenes de navegación
    if ( isset($_GET['nav'])) {
        switch ( $_GET['nav']) {
            case "Primero"  : $posAux = 0; break;
            case "Siguiente": $posAux +=FPAG; if ($posAux > $posfin) $posAux=$posfin; break;
            case "Anterior" : $posAux -=FPAG; if ($posAux < 0) $posAux =0; break;
            case "Ultimo"   : $posAux = $posfin;
        }
        $_SESSION['posini'] = $posAux;
    }


     // Proceso las ordenes de navegación en detalles
    if ( isset($_GET['nav-detalles']) && isset($_GET['id']) ) {
     switch ( $_GET['nav-detalles']) {
        case "Siguiente": crudDetallesSiguiente($_GET['id']); 
            break;
        case "Anterior" : crudDetallesAnterior($_GET['id']); 
            break;
        case "Imprimir" : generarPDF($_GET['id']); 
            break;
        
        }
    }

    if ( isset($_GET['nav-modificar']) && isset($_GET['id']) ) {
        switch ( $_GET['nav-modificar']) {
            case "Siguiente": crudModificarSiguiente($_GET['id']); 
                break;
            case "Anterior" : crudModificarAnterior($_GET['id']); 
                break;

        }
    }

    // Proceso de ordenes de CRUD clientes
    if ( isset($_GET['orden'])){
        switch ($_GET['orden']) {
            case "Nuevo"    : crudAlta(); break;
            case "Borrar"   : crudBorrar   ($_GET['id']); break;
            case "Modificar": crudModificar($_GET['id']); break;
            case "Detalles" : crudDetalles ($_GET['id']);break;
            //case "Terminar" : crudTerminar(); break;
        }
    }

    //Ordenado
    if ( isset($_GET['ordenar'])){
        $_SESSION['ordenar'] = $_GET['ordenar'];
    }

    //Cerrar sesión
    // Procesar modo ordenacion
    if (isset($_GET['terminar'])) {
        session_destroy();
        header("Location:index.php");
    }
} 

// POST Formulario de alta o de modificación
else {
    if (  isset($_POST['orden'])){
         switch($_POST['orden']) {
             case "Nuevo"    : crudPostAlta(); break;
             case "Modificar": crudPostModificar(); break;
             case "Detalles":; // No hago nada
        }
    }
}


// Si no hay nada en la buffer 
// Cargo genero la vista con la lista por defecto
if ( ob_get_length() == 0){
    $db = AccesoDatos::getModelo();
    $posini = $_SESSION['posini'];
    $ordenar = $_SESSION['ordenar'];
    $tvalores = $db->getClientes($posini,FPAG,$ordenar);
    $_SESSION['rol'] == 1 ? require_once "app/views/list.php" : require_once "app/views/list_1.php"; 
    //variable $contenido   
}
$contenido = ob_get_clean();
$msg = $_SESSION['msg'];
// Muestro la página principal con el contenido generado
require_once "app/views/principal.php";