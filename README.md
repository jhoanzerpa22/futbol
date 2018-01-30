# Futbol
Verificar si un jugador es estrella o no
# Instalacion
Solo ejecutar el comando composer install
Importar la Base de Datos del sistema con nombre futbol.sql
# Uso
URI -> /star/
Method -> POST
Request -> history
Recibe una variable json del historico de partidos jugados de un jugador
Example
{ "history": ["GPPEAG", "EGEAAG", "PAGEPG", "PGGGAE", "AEEEEG", "GPAPPA"] }
Response -> json
Da como respuesta si el juegador es estrella o no

URI -> /stats/
Method -> GET
Muestra en formato json las estadisticas de los jugadores estrellas almacenados en el sistema
Response -> json
{ "count_star":20, "count_regular":100, "ratio":0.2 }