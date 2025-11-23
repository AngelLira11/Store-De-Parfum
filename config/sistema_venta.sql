SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
SET NAMES utf8mb4;

CREATE TABLE `clientes` (
  `cli_id` int(11) NOT NULL,
  `cli_tipo` enum('usuario','invitado') NOT NULL,
  `cli_usr_id` int(11) DEFAULT NULL,
  `cli_nombre` varchar(100) NOT NULL,
  `cli_email` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `clientes` (`cli_id`, `cli_tipo`, `cli_usr_id`, `cli_nombre`, `cli_email`) VALUES
(1, 'usuario', 1, 'ADMIN', 'admin@storedeparfum.com'),
(2, 'usuario', 2, 'Miguel', 'miguelalvarez_m@outlook.com'),
(3, 'invitado', NULL, 'Ei Ramos', 'joleraga3133@gmail.com');

CREATE TABLE `detalle_venta` (
  `id` int(11) NOT NULL,
  `ven_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `detalle_venta` (`id`, `ven_id`, `product_id`, `cantidad`, `subtotal`) VALUES
(1, 1, 1, 2, '7900.00'),
(2, 2, 1, 1, '3950.00'),
(3, 3, 1, 7, '27650.00'),
(4, 3, 2, 2, '6400.00');

CREATE TABLE `productos` (
  `product_id` int(11) NOT NULL,
  `nomb_product` varchar(100) NOT NULL,
  `precio_product` decimal(10,2) NOT NULL,
  `stock_product` int(11) NOT NULL,
  `categoria` int(11) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `estado` enum('disponible','agotado','descontinuado') DEFAULT 'disponible',
  `img_product` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `productos` (`product_id`, `nomb_product`, `precio_product`, `stock_product`, `categoria`, `descripcion`, `estado`, `img_product`) VALUES
(1, 'CHANEL NÂ°5 EAU DE PARFUM', '3950.00', 15, 0, 'NÂ°5, la esencia misma de la feminidad. Un bouquet floral aldehÃ­do sublimado por un frasco emblemÃ¡tico con lÃ­neas minimalistas. Un perfume icÃ³nico y atemporal.', 'disponible', 'https://ss701.liverpool.com.mx/xl/1011907616.jpg'),
(2, '212 VIP Black', '3200.00', 4, 2, 'Un impetuoso trago de absenta, impertinente lavanda y una nube de sexy almizcle fundiÃ©ndose en un atrevido aroma de vainilla negra con notas de cuero', 'disponible', 'img/perfumes/212black.jpg'),
(3, 'Cars EDP', '600.00', 40, 4, 'Lleva la adrenalina al aroma con esta nueva fragancia', 'disponible', 'https://perfumencologne.com/wp-content/uploads/2019/06/18143-1.jpeg'),
(4, 'Perfume Mist 100 Ml - Spiderman', '99.00', 5, 5, 'Saca tu sentido arÃ¡cnido con la nueva fragancia del increÃ­ble hombre araÃ±a', 'disponible', 'https://http2.mlstatic.com/D_NQ_NP_2X_698460-MLA80944322122_122024-F.webp'),
(5, 'Forever Wisconsin EDP', '2100.00', 3, 0, 'Para el hombre sin reservas.', 'disponible', 'img/perfumes/forever.jpg'),
(6, 'Phantom EDT', '2140.00', 16, 0, 'Phantom de Rabanne, la nueva fragancia para hombre de Paco Rabanne. La esencia de la autoconfianza impulsada por las buenas energÃ­as. Una fragancia aromÃ¡tica futurista nacida entre el choque de la artesanÃ­a del lujo y la nueva tecnologÃ­a.', 'disponible', 'img/perfumes/phantom.jpg'),
(7, 'LociÃ³n Siete Machos', '100.00', 20, 0, 'Aplique lociÃ³n sobre todo el cuerpo frotando suavemente de arriba hacia abajo eliminando malas influencias. Siga con una segunda aplicaciÃ³n pero frotando de abajo hacia arriba para lograr una mejor protecciÃ³n de buena vibra.', 'disponible', 'img/perfumes/machos.jpg'),
(8, 'WhatsApp EDP', '8000.00', 5, 0, 'Presentamos John Phillips WhatsApp Eau de Parfum, una fragancia vibrante y contemporÃ¡nea inspirada en el ritmo de la vida moderna y la inmediatez de la conexiÃ³n. Este perfume es para el individuo dinÃ¡mico y sociable que siempre estÃ¡ al dÃ­a, listo par', 'disponible', 'img/perfumes/wasap.jpg.png');

CREATE TABLE `usuarios` (
  `usr_id` int(11) NOT NULL,
  `usr_email` varchar(100) NOT NULL,
  `usr_nombre` varchar(100) NOT NULL,
  `usr_contrasena` varchar(255) NOT NULL,
  `usr_telefono` varchar(15) NOT NULL,
  `usr_rol` enum('admin','usuario') NOT NULL,
  `usr_fecha_creacion` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `usuarios` (`usr_id`, `usr_email`, `usr_nombre`, `usr_contrasena`, `usr_telefono`, `usr_rol`, `usr_fecha_creacion`) VALUES
(1, 'admin@storedeparfum.com', 'ADMIN', '$2y$10$g3yXnburyWH46MxwduOfoOTtqmfpgcN595x02/hatonAadi2ClXea', '8714009087', 'admin', '2025-11-03 02:15:49'),
(2, 'miguelalvarez_m@outlook.com', 'Miguel', '$2y$10$5j.L7X4B4wmn6bBm6NCxv.LYkGU5Slc3vpzg4pqaCAaCxh9Ut53MK', '8712003094', 'usuario', '2025-11-03 02:46:46');

CREATE TABLE `ventas` (
  `ven_id` int(11) NOT NULL,
  `ven_cli_id` int(11) NOT NULL,
  `ven_fecha` timestamp NULL DEFAULT current_timestamp(),
  `ven_total` decimal(10,2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `ventas` (`ven_id`, `ven_cli_id`, `ven_fecha`, `ven_total`) VALUES
(1, 1, '2025-11-03 02:53:20', '7900.00'),
(2, 2, '2025-11-03 03:07:21', '3950.00'),
(3, 3, '2025-11-03 16:12:32', '34050.00');

ALTER TABLE `clientes`
  ADD PRIMARY KEY (`cli_id`),
  ADD KEY `cli_usr_id` (`cli_usr_id`);

ALTER TABLE `detalle_venta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ven_id` (`ven_id`),
  ADD KEY `product_id` (`product_id`);

ALTER TABLE `productos`
  ADD PRIMARY KEY (`product_id`);

ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`usr_id`),
  ADD UNIQUE KEY `usr_email` (`usr_email`);

ALTER TABLE `ventas`
  ADD PRIMARY KEY (`ven_id`),
  ADD KEY `ven_cli_id` (`ven_cli_id`);

ALTER TABLE `clientes`
  MODIFY `cli_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `detalle_venta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `productos`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

ALTER TABLE `usuarios`
  MODIFY `usr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `ventas`
  MODIFY `ven_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

COMMIT;