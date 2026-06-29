# 📦 Sistema de Registro de Productos

![PHP 8.5](https://img.shields.io/badge/PHP-8.5-777BB4?logo=php&logoColor=white)
![PostgreSQL 18.3](https://img.shields.io/badge/PostgreSQL-18.3-4169E1?logo=postgresql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-Nativo-F7DF1E?logo=javascript&logoColor=black)

Aplicación web para registrar productos, validar sus datos y almacenarlos en
PostgreSQL. Incluye carga dinámica de bodegas, sucursales y monedas, validaciones
en el navegador y el servidor, y envío del formulario mediante AJAX.

## ✨ Funcionalidades

- Registro de productos con código único.
- Carga de bodegas y monedas desde la base de datos.
- Carga de sucursales según la bodega seleccionada.
- Validaciones equivalentes en JavaScript y PHP.
- Asociación de dos o más materiales a cada producto.
- Guardado transaccional mediante PDO y consultas preparadas.
- Respuestas de los endpoints en formato JSON.

## 🛠️ Tecnologías

- HTML5 y CSS3, sin frameworks.
- JavaScript nativo con `fetch`.
- PHP 8.5 con PDO.
- PostgreSQL 18.3.

## 📋 Requisitos

- PHP 8.5 disponible en `PATH`.
- PostgreSQL 18.3 en ejecución.
- Extensiones PHP `PDO`, `pdo_pgsql` y `pgsql` habilitadas.
- Git para clonar el repositorio.
- pgAdmin 4 o `psql` para preparar la base de datos.

Comprueba las versiones y extensiones desde una terminal:

```powershell
php --version
php -m
psql --version
```

En la salida de `php -m` deben aparecer `PDO`, `pdo_pgsql` y `pgsql`.

## 🚀 Instalación

### 1. Clonar el repositorio

```powershell
git clone https://github.com/ffariascubillos/productos-biobio.git
cd productos-biobio
```

### 2. Crear e importar la base de datos

La base debe llamarse `productos_biobio`. Importa primero el esquema y después
los datos iniciales.

#### Opción A: pgAdmin 4

1. Conecta pgAdmin al servidor local de PostgreSQL.
2. Crea una base de datos llamada `productos_biobio` con codificación UTF-8.
3. Abre **Query Tool** sobre la nueva base de datos.
4. Ejecuta el contenido de `src/sql/schema.sql`.
5. Ejecuta el contenido de `src/sql/seed.sql`.

#### Opción B: psql

Ejecuta estos comandos desde la carpeta del proyecto:

```powershell
psql -U postgres -c "CREATE DATABASE productos_biobio WITH ENCODING 'UTF8';"
psql -U postgres -d productos_biobio -f src/sql/schema.sql
psql -U postgres -d productos_biobio -f src/sql/seed.sql
```

PostgreSQL solicitará la contraseña del usuario cuando corresponda. Si `psql`
no se reconoce como comando, agrégalo al `PATH` o utiliza pgAdmin.

### 3. Configurar la conexión

Abre `src/api/config.php` y ajusta los valores según tu instalación:

```php
$host = 'localhost';
$port = '5432';
$dbname = 'productos_biobio';
$user = 'postgres';
$password = 'tu_contraseña';
```

No publiques credenciales reales si utilizas el proyecto fuera de un entorno
local.

### 4. Iniciar la aplicación

Desde la raíz del proyecto ejecuta:

```powershell
php -S 127.0.0.1:8000
```

Abre [http://127.0.0.1:8000](http://127.0.0.1:8000) en el navegador. Para detener
el servidor, vuelve a la terminal y presiona `Ctrl+C`.

## 🗂️ Estructura principal

```text
index.html
src/
├── api/                 Endpoints PHP y conexión PDO
├── assets/
│   ├── css/             Estilos de la aplicación
│   └── js/              Validaciones y comunicación AJAX
└── sql/                 Esquema y datos iniciales de PostgreSQL
```

## ✅ Comprobación básica

1. Confirma que las listas de bodegas y monedas se carguen al abrir la página.
2. Selecciona una bodega y comprueba que aparezcan sus sucursales.
3. Registra un producto válido con al menos dos materiales.
4. Comprueba que el formulario se limpie después del registro.
5. Intenta repetir el código y verifica que el sistema lo rechace.

## 👤 Autor

**Felipe Farías Cubillos**

[ffariascubillos@gmail.com](mailto:ffariascubillos@gmail.com)
