# DWES_Alejandro-Galan-Varo
## Índice
1. [Estructura del Proyecto](#estructura-del-proyecto)
2. [Explicación de los Ficheros](#explicación-de-los-ficheros)

## Estructura del Proyecto
El proyecto está organizado en los siguientes directorios y archivos:
DWES_Alejandro-Galan-Varo/
├── .env
├── .gitignore
├── .htaccess
├── assets/
│   ├── css/
│   │   └── styles.css
│   └── img/
│       ├── escapeAkrapovic.png
│       ├── escapeFordpuma.webp
│       └── ...
├── autoload.php
├── config.php
├── controllers/
│   ├── CarritoController.php
│   ├── CategoriaController.php
│   ├── ErrorController.php
│   ├── PedidoController.php
│   ├── ProductoController.php
│   └── UsuarioController.php
├── database/
│   └── database.sql
├── helpers/
│   └── Utils.php
├── index.php
├── lib/
│   └── BaseDatos.php
├── models/
│   ├── Categoria.php
│   ├── Pedido.php
│   ├── Producto.php
│   └── Usuario.php
├── README.md
└── views/
    ├── carrito/
    ├── categoria/
    │   ├── administrar.php
    │   ├── crear.php
    │   └── productos.php
    ├── layout/
    │   ├── footer.php
    │   └── header.php
    ├── pedido/
    ├── producto/
    │   ├── crear.php
    │   ├── editar.php
    │   ├── gestion.php
    │   └── recomendados.php
    └── usuario/
        ├── administrarAdmin.php
        ├── editarUsuario.php
        ├── login.php
        └── registrarse.php


## Explicación de los Ficheros

### assets/
- **css/styles.css**: Contiene los estilos CSS utilizados en el proyecto.
- **img/**: Directorio que almacena las imágenes utilizadas en el proyecto.

### controllers/
- **CarritoController.php**: Controlador para gestionar las acciones relacionadas con el carrito de compras.
- **CategoriaController.php**: Controlador para gestionar las acciones relacionadas con las categorías de productos.
- **ErrorController.php**: Controlador para manejar las páginas de error.
- **PedidoController.php**: Controlador para gestionar las acciones relacionadas con los pedidos.
- **ProductoController.php**: Controlador para gestionar las acciones relacionadas con los productos.
- **UsuarioController.php**: Controlador para gestionar las acciones relacionadas con los usuarios.

### database/
- **database.sql**: Archivo SQL para la creación y configuración de la base de datos.

### helpers/
- **Utils.php**: Contiene funciones de utilidad que se utilizan en varias partes del proyecto.

### lib/
- **BaseDatos.php**: Clase para gestionar la conexión y operaciones con la base de datos.

### models/
- **Categoria.php**: Modelo que representa la entidad "Categoría" en la base de datos.
- **Pedido.php**: Modelo que representa la entidad "Pedido" en la base de datos.
- **Producto.php**: Modelo que representa la entidad "Producto" en la base de datos.
- **Usuario.php**: Modelo que representa la entidad "Usuario" en la base de datos.

### views/
- **carrito/**: Directorio que contiene las vistas relacionadas con el carrito de compras.
- **categoria/**: Directorio que contiene las vistas relacionadas con las categorías de productos.
- **layout/**: Directorio que contiene las vistas de layout como header y footer.
- **pedido/**: Directorio que contiene las vistas relacionadas con los pedidos.
- **producto/**: Directorio que contiene las vistas relacionadas con los productos.
- **usuario/**: Directorio que contiene las vistas relacionadas con los usuarios.

### Archivos raíz
- **autoload.php**: Script de autoload para cargar automáticamente las clases de los controladores y modelos.
- **config.php**: Archivo de configuración que define constantes como la configuración de la base de datos y rutas.
- **index.php**: Archivo principal que maneja las solicitudes y carga los controladores y vistas correspondientes.
- **.htaccess**: Archivo de configuración de Apache para la reescritura de URLs y manejo de errores.
- **.env**: Archivo que contiene las variables de entorno para la configuración de la base de datos.
- **README.md**: Archivo de documentación del proyecto.# TGF-Rutas
