CREATE DATABASE sistema_venta;
USE sistema_venta;

CREATE TABLE usuarios (
    usr_id INT AUTO_INCREMENT PRIMARY KEY,
    usr_email VARCHAR(100) NOT NULL UNIQUE,
    usr_nombre VARCHAR(100) NOT NULL,
    usr_contrasena VARCHAR(255) NOT NULL,
    usr_telefono VARCHAR(15) NOT NULL, 
    usr_rol ENUM('admin', 'usuario') NOT NULL,
    usr_fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE clientes (
    cli_id INT AUTO_INCREMENT PRIMARY KEY,
    cli_tipo ENUM('usuario', 'invitado') NOT NULL,
    cli_usr_id INT NULL,
    cli_nombre VARCHAR(100) NOT NULL,
    cli_email VARCHAR(100) NOT NULL,
    FOREIGN KEY (cli_usr_id) REFERENCES usuarios(usr_id)
);

-- Nota : Añadir como clave foranea el producto cuando se cree la tabla de productos
CREATE TABLE ventas (
    ven_id INT AUTO_INCREMENT PRIMARY KEY,
    ven_cli_id INT NOT NULL,
    ven_fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ven_total DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (ven_cli_id) REFERENCES clientes(cli_id)
);

-- Añadir la tabla de productos

CREATE TABLE productos(
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    nomb_product varchar (100) not null,
    precio_product DECIMAL (10,2) not null,
    stock_product int not null,
    categoria int not null,
    descripcion varchar(255) not null,
    estado ENUM('disponible', 'agotado', 'descontinuado') DEFAULT 'disponible',
    img_product VARCHAR(255) NOT NULL
);

--tabla ventas - productos

CREATE TABLE detalle_venta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ven_id INT NOT NULL,
    product_id INT NOT NULL,
    cantidad INT NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,

    FOREIGN KEY (ven_id) REFERENCES ventas(ven_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    FOREIGN KEY (product_id) REFERENCES productos(product_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);