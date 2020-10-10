CREATE DATABASE web_project;
USE web_project;

CREATE TABLE IF NOT EXISTS `t_usuarios` (
  `id_usuario` int(11) AUTO_INCREMENT,
  `correo` varchar(60) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `nivel` tinyint(4) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

INSERT INTO t_usuarios(correo, pass, nivel, `status`) VALUES('daniel.clementea97@gmail.com', 'Qwe123$$', 1, 1);
INSERT INTO t_usuarios(correo, pass, nivel, `status`) VALUES('minedan1057@gmail.com', 'Qwe123$$', 1, 2);
INSERT INTO t_usuarios(correo, pass, nivel, `status`) VALUES('daguirre97@hotmail.com', 'Qwe123$$', 1, 3);

