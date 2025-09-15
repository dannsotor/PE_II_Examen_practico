# Pasos para ejecutar el sistema con Docker

Hola chicos, a continuación están los comandos que deben ejecutar para poder iniciar el proyecto.

<strong>NOTA:</strong> Todos los comandos se ejecutan en la raiz del proyecto.

### Construir la imagen de Docker
    
    docker-compose build

### Levantar los contenedores en segundo plano

    docker-compose up -d

Luego solo queda acceder con esta URL: http://localhost:8080/

Si quieren verificar si la conexion a la base de datos es exitosa, vayan a estar URL: http://localhost:8080/presentation/testConnection.php
