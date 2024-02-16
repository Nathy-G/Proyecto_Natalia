<!-- Vista ROL 0 -->
<form>
    <button type="submit" name="terminar" > Cerrar Sesión </button>
    <br>
</form>
<table>
    <tr>
        <th><a href="?ordenar=id"> id </a></th>
        <th><a href="?ordenar=first_name"> first_name </a></th>
        <th><a href="?ordenar=email"> email</a></th>
        <th><a href="?ordenar=gender">gender</a></th>
        <th><a href="?ordenar=ip_address">ip_address </a></th>
        <th><a href="?ordenar=telefono">teléfono</a></th>
    </tr>
    <?php foreach ($tvalores as $valor): ?>
    <tr>
        <td><?= $valor->id ?> </td>
        <td><?= $valor->first_name ?> </td>
        <td><?= $valor->email ?> </td>
        <td><?= $valor->gender ?> </td>
        <td><?= $valor->ip_address ?> </td>
        <td><?= $valor->telefono ?> </td>
        <td><a href="?orden=Detalles&id=<?=$valor->id?>" >Detalles</a></td>

    </tr>
    <?php endforeach ?>
</table>

<form>
    <br>
    <button type="submit" name="nav" value="Primero"> << </button>
    <button type="submit" name="nav" value="Anterior"> < </button>
    <button type="submit" name="nav" value="Siguiente"> > </button>
    <button type="submit" name="nav" value="Ultimo"> >> </button>
</form>
