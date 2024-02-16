<!-- Vista de Detalles -->
<form>
    <input type="hidden"  name="id" value="<?=$cli->id ?>">
    <button onclick="location.href='./'" > Inicio </button>
    <button type="submit" name="nav-detalles" value="Imprimir">Imprimir cliente</button>
</form>
<table>    
    <tr>
    <td rowspan="7">
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
        <td>id:</td> 
        <td><input type="number" name="id" value="<?=$cli->id ?>"  readonly ></td>
        <td rowspan="7">
            <img src="<?= crudBanderaIp($cli->ip_address) ?>" style="width: 150px; height: auto;">
        </td> 
        
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
    <tr>
        <td colspan="4">
            <div id="map" style="height: 200px; width: 700px;">
            </div>
        </td>
        
    </tr>
 </table>
 
<form>
    <input type="hidden"  name="id" value="<?=$cli->id ?>">
    <button type="submit" name="nav-detalles" value="Anterior"> Anterior << </button>
    <button type="submit" name="nav-detalles" value="Siguiente"> Siguiente >> </button>
</form> 

<script>
    // Obtener la dirección IP del elemento de entrada
    let dir_ip = document.getElementsByName("ip_address")[0].value;

    // Obtener las coordenadas utilizando la función obtenerCoordenadas, que parece ser una llamada asíncrona
    try {
        const coordenadas = await obtenerCoordenadas(dir_ip);

        // Coordenadas por defecto si la obtención falla
        let latitud = 0;
        let longitud = 0;
        let zoom = 1;

        // Actualizar las coordenadas si la obtención fue exitosa
        if (coordenadas != null) {
            latitud = coordenadas[0];
            longitud = coordenadas[1];
            zoom = 13;
        }
        // Crear el mapa con Leaflet
        var map = L.map('map').setView([latitud, longitud], zoom);

        // Añadir capa de mapa base de OpenStreetMap
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
    
        // Colocar marcador si las coordenadas son válidas
        if (latitud != 0 && longitud != 0) {
            L.marker([latitud, longitud]).addTo(map)
            .openPopup();
        }
    } catch (error) {
    // Manejar errores en la obtención de coordenadas
    console.error("Error al obtener las coordenadas:", error);
    }
</script>