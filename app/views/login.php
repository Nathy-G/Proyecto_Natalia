<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>CRUD DE USUARIOS</title>
    <link href="web/css/default.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="web/js/funciones.js"></script>
</head>
<body>
    <div id="container">
        <div id="header">
            <h1>Acceso de Usuario</h1>
        </div>
        <div id="content">
            <form method="get">
                <?= $msg ?><br>
                Login: <input type="text" name="login"> 
                Password: <input type="text" name="pass"> 
                <input type="submit" value="Enviar">
            </form>
        </div>
    </div>
</body>
