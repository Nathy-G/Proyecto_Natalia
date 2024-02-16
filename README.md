ACESSOS:
ROL 1: login: Mathew + password: 8d84bf9e0e6c6d7850adbf4e03c67e6a
ROL 0: login: Paten + password: ae0e200a7bf49b3f6d19897a366fded5

1)	Se ha arreglado la paginación entre clientes, tanto hacia delante como hacia atrás.  Esto se puede ver en AcessoDatosPDO.php --> getClienteAnterior/Siguiente.

2)	Hemos añadido la variable de $_SESSION para que se guarde el tipo de ordenación y poder así poder pasar de “página” en la lista de clientes.

3)	Modificar un cliente sin tocar el email, nos daba un error de que el email ya existía (repetición de email). Por ello hemos añadido en AcessoDatosPDO.php una nueva función getEmailRepe donde chequeamos tanto el email con la id.

4)	Se consigue en curdclientes.php (crudPostAlta  y crudPostModificar ) que se controle un fichero jpg o png, además de tel tamaño máximo de 500kb pero no subirlo a la carpeta Uploads

6)  A través de la dirección ip de los clientes, podemos acceder al país y con ello a las banderas. Se desarrolla en crudclientes.php con la función crudBanderaIp y se muestra en el fichero detalles.php.

7)	En detalles.php hemos añadido la opción de imprimir lo información del cliente. Se desarrolla el código en crudcliente.php --> generarPDF. El archivo detallespdf.php  muestra cómo se imprime.

8 y 9) Se ha creado los ficheros:
Usuario.php y usuario.sql para la creación de los usuarios de acceso.
login.php para el acceso.
list_1.php para la vista del rol 0.
bloqueo.php para mostrar que se ha llegado al número de intentos y ha de reiniciar el navegador.

Para poder comprobar los roles, usar los siguientes accesos:
ROL 1: login: Mathew + password: 8d84bf9e0e6c6d7850adbf4e03c67e6a
ROL 0: login: Paten + password: ae0e200a7bf49b3f6d19897a366fded5

Se añade el botón “Cerrar sesión” para poder entrar con otro usuario.

10) Se añade script en detalles.php para poder acceder a la localización del cliente a través de su dirección ip. En caso de que la conexión falle, se ha añadido coordenadas.
