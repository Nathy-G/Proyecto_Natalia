<!-- Vista de PDF-->
<div>
<table>
    <tr>
        <td rowspan="8">
            <img src="<?= crudBanderaIp($cli->ip_address) ?>" style="width: 150px; height: auto;">
        </td> 
        <td rowspan="8">
        <?php 
            $imagen = "app/uploads/" . str_pad($cli->id, 8, '0', STR_PAD_LEFT) . ".jpg";
            $robothash = str_pad($cli->id, 9, '0', STR_PAD_LEFT) .".png";

            if (file_exists($imagen)) {
                echo '<img src="' . $imagen . '" alt="Imagen del cliente">';
            } else {
                echo '<img src="https://robohash.org/'.$robothash.' alt="Imagen por defecto">';
            }
            ?>
        </td>
    </tr>
    <tr>
        <td>id:</td> 
        <td><input type="number" name="id" value="<?=$cli->id ?>"  readonly > </td>
    </tr>
    <tr>
        <td>first_name:</td> 
        <td><input type="text" name="first_name" value="<?=$cli->first_name ?>" readonly > </td>
    </tr>
    <tr>
        <td>last_name:</td> 
        <td><input type="text" name="last_name" value="<?=$cli->last_name ?>" readonly ></td>
    </tr>
    <tr>
        <td>email:</td> 
        <td><input type="email" name="email" value="<?=$cli->email ?>"   readonly  ></td>
    </tr>
    <tr>
        <td>gender</td> 
        <td><input type="text" name="gender" value="<?=$cli->gender ?>" readonly ></td>
    </tr>
    <tr>
        <td>ip_address:</td> 
        <td><input type="text" name="ip_address" value="<?=$cli->ip_address ?>" readonly ></td>
    </tr>
    <tr>
        <td>telefono:</td> 
        <td><input type="tel" name="telefono" value="<?=$cli->telefono ?>" readonly ></td>
    </tr>
 </table>
 
</div>


