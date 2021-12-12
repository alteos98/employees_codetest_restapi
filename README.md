# employees_codetest_restapi

Para esta API REST se han implementado los 3 endpoints descritos en la actividad.

## GET /employees_codetest_restapi/api/v1/employees.php

Se recibe un JSON con el listado de empleados con el departamento, cargo y salario actuales. Ordenado por fecha de contratación (de más reciente a más antigua) y limitado a 50.

Con el servidor levantado y el proyecto funcionando en el localhost, un ejemplo sería enviar una petición GET a la siguiente URL: http://localhost/employees_codetest_restapi/api/v1/employees.php

## GET /employees_codetest_restapi/api/v1/employees.php?emp_no=<insertar número de empleado>

Se recibe un JSON con los datos del empleado indicado con el departamento, cargo y salario actuales.

Con el servidor levantado y el proyecto funcionando en el localhost, un ejemplo sería enviar una petición GET a la siguiente URL: http://localhost/employees_codetest_restapi/api/v1/employees.php?emp_no=10550

## POST /employees_codetest_restapi/api/v1/employees.php

Se ha de enviar la petición con un JSON que contenga los datos del empleado a añadir en la BBDD.

Con el servidor levantado y el proyecto funcionando en el localhost, un ejemplo sería enviar una petición POST a la siguiente URL: http://localhost/employees_codetest_restapi/api/v1/employees.php acompañada del JSON con los datos del empleado.

Un ejemplo de JSON para enviar junto a la petición POST sería:

```JSON
{
    "first_name" : "Carlos",
    "last_name" : "Serrano",
    "gender" : "M",
    "birth_date" : "1998-01-01",
    "title" : "Engineer",
    "salary" : 35000,
    "dept_no" : "d005"
}
```

## Levantar el servidor

Yo he hecho uso de XAMPP ya que contiene Apache + PHP + MySQL.

### Paso 1

Clonar el proyecto dentro de la carpeta xampp/htdocs.

### Paso 2

Arrancar el servicio de Apache.

### Paso 3

Arrancar el servicio de MySQL.

### Paso 4

Añadir en MySQL la BBDD del ejercicio, ya sea por CMD o por phpMyAdmin. Este paso se puede omitir si queremos partir de una BBDD vacía e ir añadiendo los empleados por nuestra cuenta.

### Paso 5

API REST lista para usarse.
