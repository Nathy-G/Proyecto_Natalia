/**
 * Funciones auxiliares de javascripts 
 */
function confirmarBorrar(nombre,id){
  if (confirm("¿Quieres eliminar el cliente:  "+nombre+"?"))
  {
   document.location.href="?orden=Borrar&id="+id;
  }
}


//Funcion para obtener las coordenadas (latitud y longitud) según la IP
async function obtenerCoordenadas(dir_ip) {
  let resu = [];
  let respuesta = await fetch("http://ip-api.com/json/"+dir_ip+"?fields=status,lat,lon");
  let coordenadas = await respuesta.json()

  if (coordenadas.status != "success") {
    return null;
  }


  resu.push(coordenadas.lat);
  resu.push(coordenadas.lon);

  return resu;
}

//Función para generar el mapa
async function mapa() {
  let dir_ip = document.getElementsByName("ip_address")[0].value;
  const  coordenadas = await obtenerCoordenadas(dir_ip);

  //Coordenadas por defecto si al obtener da "fail"
  let latitud = 0;
  let longitud = 0;
  let zoom = 1;

  if (coordenadas != null) {
    latitud = coordenadas[0];
    longitud = coordenadas[1];
    zoom = 13;
  }

  var map = L.map('map').setView([latitud, longitud], zoom);

  L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
  }).addTo(map);
  
  //Se colocará el markador solo si ha tenido éxito la obtención de las coordenadas
  if (latitud!=0 && longitud!=0) {
    L.marker([latitud, longitud]).addTo(map)
    .openPopup();
  }

}


function gestionandoevento(evento) {
  if (document.readyState == 'complete') {
    console.log(evento.srcElement.location);
    let url_final = evento.srcElement.location.search;

    if (url_final.includes('detalles') ||url_final.includes('Detalles')) mapa();
    if (url_final.includes('modificar') || url_final.includes('Modificar')) mapa();

  }
}


document.addEventListener('readystatechange',gestionandoevento,false);
