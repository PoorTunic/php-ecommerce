DROP DATABASE web_project;
CREATE DATABASE web_project CHARACTER SET utf8 COLLATE utf8_general_ci;
USE web_project;

CREATE TABLE IF NOT EXISTS `t_usuario` (
  `id_usuario` int(11) AUTO_INCREMENT,
  `correo` varchar(60) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `nivel` tinyint(4) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

INSERT INTO t_usuario(correo, pass, nivel, `status`) VALUES('daniel.clementea97@gmail.com', 'Qwe123$$', 1, 1);
INSERT INTO t_usuario(correo, pass, nivel, `status`) VALUES('minedan1057@gmail.com', 'Qwe123$$', 2, 1);
INSERT INTO t_usuario(correo, pass, nivel, `status`) VALUES('daguirre97@hotmail.com', 'Qwe123$$', 3, 1);

CREATE TABLE IF NOT EXISTS `t_categoria` (
  `id_categoria` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` varchar(30) NOT NULL,
  PRIMARY KEY (`id_categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

INSERT INTO t_categoria(categoria) VALUES("CELULARES");
INSERT INTO t_categoria(categoria) VALUES("ORDENADORES");
INSERT INTO t_categoria(categoria) VALUES("MONITORES");
INSERT INTO t_categoria(categoria) VALUES("ACCESORIOS");
INSERT INTO t_categoria(categoria) VALUES("IMPRESORAS");

CREATE TABLE IF NOT EXISTS `t_producto` (
  `id_producto` int(11) NOT NULL AUTO_INCREMENT,
  `producto` varchar(150) NOT NULL,
  `precom` double NOT NULL,
  `preven` double NOT NULL,
  `descripcion` text NOT NULL,
  `imagen` varchar(30) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  PRIMARY KEY (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

INSERT INTO t_producto(producto, precom, preven, descripcion, imagen, id_categoria) VALUES("GOOGLE PIXEL", 2000.00, 2439.99 , "Este producto reacondicionado certificado ha sido probado y certificado para trabajo y aspecto como nuevo, con el mínimo a sin signos de desgaste, por un vendedor especializado aprobado por Amazon. El producto está respaldado por una garantía de 90 días mínimo y puede que llegue en una caja genérica café o blanco. Accesorios pueden ser genérico y no directamente desde el fabricante. Número ilimitado de Google de almacenamiento", "gpixel", 1);
INSERT INTO t_producto(producto, precom, preven, descripcion, imagen, id_categoria) VALUES("GOOGLE PIXEL 2", 3500.00 , 3999.99 , "Equipo liberado para cualquier compañía, listo para usarse. El producto se envía desde la bodega de mercado libre, el 90% de las compras llegan al dia siguiente, ademas el envio es gratis. El celular puede presentar leves rasguños (mas de lo habitual), nada grave, nunca a sido destapado para reparar, el equipo funciona en su totalidad. Equipo 100% Funcional.", "gpixel2", 1);
INSERT INTO t_producto(producto, precom, preven, descripcion, imagen, id_categoria) VALUES("GOOGLE PIXEL 3", 7000.00 , 8999.99, "Inteligente y predictivo\nEl sistema operativo avanzado Android 9.0 Pie aprende tus hábitos para adaptarse a tu rutina y lograr la máxima eficiencia de tu equipo. Tu dispositivo tendrá la autonomía necesaria para reducir el consumo energético ajustando el brillo automáticamente y gestionando la batería de manera inteligente para que puedas priorizar el uso de tus aplicaciones habituales.", "gpixel3", 1);
INSERT INTO t_producto(producto, precom, preven, descripcion, imagen, id_categoria) VALUES("GOOGLE PIXEL 4", 20000.00 , 22999.99 , "Style:Pixel 4 | Size:128GB | Color:Clearly White Pixel 4 is the phone Made the Google way. So it’s built around the helpful Google software you know and love, that’s always getting better. 1. See G.Co/Pixel/astrophotography to Learn more about astrophotography on Pixel 4.2. Us only. English only. 3. Coming soon to Japan. Motion sense functional in the us, Canada, Singapore, Australia, Taiwan, and most European countries. Not all phone features are controlled by motion sense.4. Us & Canada only. English only.5. Approximate battery life based on a mix of talk, data, standby, mobile Hot spot and use of other features, with motion sense off and always on display off. Use of motion sense, an Active display or data usage will decrease battery life. Pixel 4 and 4 XL testing conducted in Mountain View, California in August 2019 on pre- production hardware and software. Actual results may vary.6. Up to 10 W with Pixel 4 and Pixel 4 XL charging with Pixel Stand (SOLD Separately). actual results may vary.", "gpixel4", 1);
INSERT INTO t_producto(producto, precom, preven, descripcion, imagen, id_categoria) VALUES("XIAOMI REDMI 9", 3500.00, 3999.99, "Bandas LTE 1/3/7/8/20/28/38/40 > (asegúrate de comprobar la compatibilidad con tu transportista antes de comprar) - Soporta tarjeta nano-SIM dual + expansión micro-SD, cualquiera de las tarjetas se puede configurar como la tarjeta principal.", "xredmi9", 1);
INSERT INTO t_producto(producto, precom, preven, descripcion, imagen, id_categoria) VALUES("COMPUINOVA PC CPU Gamer AMD", 7000.00, 7999.99, "COMPUINOVA te presenta el CPU Gamer con procesador AMD APU A8 9600, Tarjeta Madre ASUS Prime A320M-K, 8GB RAM DDR4, D.D. 1TB, Gabinete Aero Cool CYLON con linea frontal RGB, tapa lateral de acrílico y lector de memorias, Fuente de poder 600W con ventilador 12 c.m. Kit Gaming NACEB NA-633. Teclado con 7 colores de luz de LED, 19 teclas anti-ghosting, 12 teclas multimedia. Mouse con 7 colores de luz de LED, resolución DPI 1000.", "pc1", 2);
INSERT INTO t_producto(producto, precom, preven, descripcion, imagen, id_categoria) VALUES("COMPUINOVA PC CPU Gamer AMD", 6500.00, 7499.99, "COMPUINOVA te presenta el CPU Gamer con procesador AMD APU A8 9600, Tarjeta Madre ASUS Prime A320M-K, 8GB RAM DDR4, D.D. 1TB, Gabinete Aero Cool CYLON con linea frontal RGB y tapa lateral de acrílico, Fuente de poder 600W con ventilador 12 c.m.", "pc2", 2);
INSERT INTO t_producto(producto, precom, preven, descripcion, imagen, id_categoria) VALUES("COMPUINOVA Computadora CPU Gamer | AMD Ryzen 5 | Radeon RX | 8GB RAM | D.D. 1TB | WiFi | Bluetooth.", 13000.00, 14999.99, "COMPUINOVA te trae el síguete CPU GAMER. | PROCESADOR: AMD Ryzen 5 2600 3.4GHz. y 3.9GHz. en turbo, con 6 núcleos y 12 hilos, 16MB en cache. | TARJETA MADRE: ASUS Pro A320M-R Wi-Fi (Micro-ATX). | GRÁFICOS: Sapphire Pulse Radeon RX 570 8GB (Dedicada) con 2 HDMI y 2 DisplayPort. | SLOT DE EXPANSIÓN: 1 módulo PCI-Ex16 3.0, 1 módulos PCI-Ex1 3.0, 2 módulos DIMM DDR4 (Soporta hasta 32GB RAM). | CONECTORES INTERNOS: 4 SATA3 (6Gb/s.), 2 USB 2.0, 1 USB 3.1 Gen 1, 1 USB 3.1 Gen 2, 1 COM | CONECTORES PANEL TRASERO: 2 PS2 (Mouse / Teclado), 1 D-Sub (VGA), 1 DisplayPort, 1 HDMI, 4 USB 3.1, 1 LAN (R-J45), 1 Wi-Fi +Bluetooth, Line in - Line out - Mic in HD Audio 7.1 canales. | FUENTE DE PODER: Acteck Z600 600Watts. con ventilador 12 c.m. | GABINETE: Aero Cool Aero Engine con panel izquierdo de acrílico y ventilador frontal de 12 c.m. con 13 modos de LED´s RGB., 2 USB 2.0 y 1 USB 3.1 superior, conectores de audio HD y micrófono.", "pc3", 2);
INSERT INTO t_producto(producto, precom, preven, descripcion, imagen, id_categoria) VALUES("LENOVO COMPUTADORA THINKCENTRE M53", 4000.00, 5499.99, "Procesador Intel Celeron J1800 (hasta 2.58 GHz), Memoria de 2 GB DDR3, D.D. de 500 GB, Video Intel HD Graphics, No Incluye Sistema Operativo, No Incluye Monitor.", "pc4", 2);
INSERT INTO t_producto(producto, precom, preven, descripcion, imagen, id_categoria) VALUES("Lenovo Laptop Gaming Legion Y720-15IKB", 35000.00, 40999.99, "NB Lenovo LEGION Y720-15IKB Ci7 16GB _1TB_128 _Windows 10 Home_15pulgadas. Black.", "pc5", 2);
INSERT INTO t_producto(producto, precom, preven, descripcion, imagen, id_categoria) VALUES("Sceptre E205W-16003R", 1500.00, 2399.99, "Monitor LED sin Marco ultradelgado HDMI VGA, con bocinas incorporadas, Negro metálico 2018, Ultra Delgado, Metallic Black, 7.62", "monitor1", 3);
INSERT INTO t_producto(producto, precom, preven, descripcion, imagen, id_categoria) VALUES("Dell E Series E2216H", 2000.00, 2499.99, "21.5 Inches Full HD IPS Matt Negro - Monitor (1920 x 1080 Pixeles, LED, Full HD, IPS, Mate, 1920 x 1080 (HD 1080))", "monitor2", 3);
INSERT INTO t_producto(producto, precom, preven, descripcion, imagen, id_categoria) VALUES("LED BenQ GW2283", 2500.00, 2999.99, "Eye-Care, Panel IPS, Tecnologías Brightness Intelligence y Flicker-free, Bisel Ultra Delgado, Sistema de Organización de Cables, HDMI x 2", "monitor3", 3);
INSERT INTO t_producto(producto, precom, preven, descripcion, imagen, id_categoria) VALUES("HP MONHPQ050", 1600.00, 2199.99, "Monitor Led 22Yh - 21.5 Pulgadas, 250 CD/M², 1920 X 1080 Pixeles, 5 Ms", "monitor4", 3);
INSERT INTO t_producto(producto, precom, preven, descripcion, imagen, id_categoria) VALUES("Televisor de 19 Inches Proscan - PLEDV1945A-B", 2000.00, 2999.99, "720p 60Hz Pantalla LED TV-DVD incorporado (Renewed)", "monitor5", 3);
INSERT INTO t_producto(producto, precom, preven, descripcion, imagen, id_categoria) VALUES("Philips Teclado mecánico para juegos", 500.00, 869.99, "RGB LED retroiluminado, teclado con cable con interruptores azules para jugar, PC - Llave completa N - Anti-Ghosting", "accesorio1", 4);
INSERT INTO t_producto(producto, precom, preven, descripcion, imagen, id_categoria) VALUES("Razer Death Adder Essential RZ01-02540100-R3U1", 500.00, 709.99, "Mouse para Videojuegos", "accesorio2", 4);
INSERT INTO t_producto(producto, precom, preven, descripcion, imagen, id_categoria) VALUES("Charlemain Ratón Vertical Inalámbrico Ergonómico Recargable USB", 300.00, 499.99, "2.4 G ratón óptico para PC/portátil/Mac 800/1200/1600 dpi 6 Partes de Control-Negro", "accesorio3", 4);
INSERT INTO t_producto(producto, precom, preven, descripcion, imagen, id_categoria) VALUES("ASUS CHROMEBIT CS10 Stick", 1500.00, 2199.99, "Computadora de escritrio PC con RockChip 3288-C, 2 GB RAM, 16 GB eMMC, Google ChromeOS", "accesorio4", 4);
INSERT INTO t_producto(producto, precom, preven, descripcion, imagen, id_categoria) VALUES("FurnitureR Escritorio para juegos Mesa de juegos para oficina en casa de 120 cm", 4500.00, 5999.99, "Mesa para computadora de juegos para PC de 44.5 pulgadas con portavasos Gancho para auriculares y estante para manija de juego Negro y rojo", "accesorio5", 4);
INSERT INTO t_producto(producto, precom, preven, descripcion, imagen, id_categoria) VALUES("Epson Multifuncional Ecotank L3150", 3000.00, 4899.99, "MULTIFUNCIONAL EPSON L3150 PPM 33 NEGRO 15 COLOR TINTA CONTINUA ECOTANK USB WIFI", "impresora1", 5);
INSERT INTO t_producto(producto, precom, preven, descripcion, imagen, id_categoria) VALUES("Xerox Impresora Láser 3020", 1000.00, 1799.99, "Puede tener un negocio pequeño, pero grandes necesidades. La impresora Phaser 3020 ultracompacta, fue desarrollada para usuarios individuales. La configuración es rápida, el uso diario es simple, y el trabajo de impresión rápido. Tecnología de vanguardia. Wi-Fi directo.", "impresora2", 5);
INSERT INTO t_producto(producto, precom, preven, descripcion, imagen, id_categoria) VALUES("HP IMPHPI3070 Impresora Láser Laserjet Pro M15W", 1555.00, 2299.99, "HP impresora láser laserjet pro m15w, 600 x 600 dpi, 18 ppm, 8000 páginas por mes.", "impresora3", 5);
INSERT INTO t_producto(producto, precom, preven, descripcion, imagen, id_categoria) VALUES("Canon Impresora de Inyección de Tinta Continua G1100", 2500.00, 2999.99, "Canon PIXMA G1100 Impresora de Inyección de Tinta a Color", "impresora4", 5);
INSERT INTO t_producto(producto, precom, preven, descripcion, imagen, id_categoria) VALUES("HP Impresora multifuncional, Ink Tank 315 (Z4B04A)", 2500.00, 3399.99, "HP impresora multifuncional ink tank 315, inyección de tinta, 1000 páginas por mes, 8 ppm, 4800 x 1200 dpi.", "impresora5", 5);
