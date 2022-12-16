
create table empresa(nit varchar(20) primary key, 
nombre varchar(50) not null, 
direccion varchar(255) null, 
correo varchar(60) not null, 
telefono varchar(25) not null,
 correoRepresentante varchar(60) not null, 
 nombreRepresentante varchar(55) not null);


create table usuario
    (identificacion varchar(15) primary key, 
    tipoUsuario char not null, 
    nombreUsuario varchar(25) not null, 
    nombre varchar(50) not null, 
    apellido varchar(50) not null, 
    tipoIdentificacion char not null, 
    correo varchar(60) not null, 
    clave varchar(32) not null, 
    direccion varchar(255) null, 
    foto varchar(255) null, 
    telefono varchar(15) null, 
    nitEmpresa_FK varchar(20) not null,
     foreign key(nitEmpresa_FK) references empresa(nit) on delete restrict on update cascade);


create table habilidad(
    idHabilidad int auto_increment primary key, 
nombre varchar(55) not null, 
descripcion varchar(255) not null);


create table usuarioHabilidad
    (id int auto_increment primary key, 
    experiencia varchar(255) not null,
     nivelDominio char not null, 
     idUsuario_FK varchar(15) not null, 
     idHabilidad_FK int not null,
     foreign key (idUsuario_FK) references usuario(identificacion) on delete restrict on update cascade,
     foreign key (idHabilidad_FK) references habilidad(idHabilidad) on delete restrict on update cascade);


create table proyecto
    (idProyecto int auto_increment primary key, 
    nombre varchar(55) not null, 
    descripcion varchar(500) null, 
    estado char null, 
    fechaInicio datetime not null, 
    fechaFinalizacion datetime not null, 
    idUsuario_FK varchar(15) not null,
     foreign key (idUsuario_FK) references usuario(identificacion) on delete restrict on update cascade);


create table rh_proyecto
    (id int auto_increment primary key, 
    fechaSolicitud datetime not null default now(), 
    estado char null, idUsuario_FK varchar(15) not null, 
    idProyecto_FK int not null,
     foreign key (idUsuario_FK) references usuario(identificacion) on delete restrict on update cascade,
     foreign key (idProyecto_FK) references proyecto(idProyecto) on delete restrict on update cascade);


create table estudio(
    idEstudio int auto_increment primary key, 
    idCertificacion int not null, nombreEstudio varchar(255), 
    fechaCertificacion datetime not null, certificado varchar(255) not null, 
    idUsuario_FK varchar(15) not null, idPerfilEstudio_FK int not null,
     foreign key (idUsuario_FK) references usuario(identificacion) on delete restrict on update cascade);


create table usuarioEstudio(
    idUsuarioEstudio int auto_increment primary KEY, 
identificacion_FK varchar(15), 
idestudio_FK int);


ALTER TABLE `usuarioestudio` ADD CONSTRAINT `fk1`
FOREIGN KEY (`idEstudio_FK`) REFERENCES `estudio`(`idEstudio`) ON
DELETE RESTRICT ON
UPDATE CASCADE;


ALTER TABLE `usuarioestudio` ADD CONSTRAINT `fk2`
FOREIGN KEY (`identificacion_FK`) REFERENCES `usuario`(`identificacion`) ON
DELETE RESTRICT ON
UPDATE CASCADE;


CREATE TABLE `proyecto_habilidad` (
  `idProyectoHabilidad` int(11) NOT NULL,
  `idProyecto_FK` int(11) NOT NULL,
  `idHabilidad_FK` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `proyecto_habilidad`
  ADD PRIMARY KEY (`idProyectoHabilidad`);

ALTER TABLE `proyecto_habilidad`
  MODIFY `idProyectoHabilidad` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;


--empresa
INSERT INTO `empresa` (`nit`, `nombre`, `direccion`, `correo`, `telefono`, `correoRepresentante`, `nombreRepresentante`) VALUES ('333', 'aguas de bogota', 'Bogotá, Cundinamarca ', 'aguas.bogota@gmail.com', '33333333', 'juan123@gmail.com', 'Juan Alvarez');

--user
INSERT INTO `usuario` (`identificacion`, `tipoUsuario`, `nombreUsuario`, `nombre`, `apellido`, `tipoIdentificacion`, `correo`, `clave`, `direccion`, `foto`, `telefono`, `nitEmpresa_FK`) VALUES ('1004023221', 'A', 'admin', 'admin', 'admin', 'C', 'admin@admin.com', MD5('admin'), 'admin', NULL, NULL, '333');
INSERT INTO `usuario` (`identificacion`, `tipoUsuario`, `nombreUsuario`, `nombre`, `apellido`, `tipoIdentificacion`, `correo`, `clave`, `direccion`, `foto`, `telefono`, `nitEmpresa_FK`) VALUES
('1', 'D', '1', 'William', 'Trigos', 'C', 'edwintrigos24@gmail.com', 'c4ca4238a0b923820dcc509a6f75849b', '222323', '', '2223232', '333'),
('11', 'T', '11', 'Juan', 'Trigos', 'R', 'edwintrigos24@gmail.com', 'c4ca4238a0b923820dcc509a6f75849b', '222323', '', '2223232', '333'),
('111', 'D', '111', 'Santiago', 'Rueda', 'T', 'sant@gmail.com', 'c4ca4238a0b923820dcc509a6f75849b', 'calle 14', '', '321424213', '333'),
('123', 'T', '123', 'Edwin ', 'Robles', 'T', 'edwintrigos24@gmail.com', 'c4ca4238a0b923820dcc509a6f75849b', '222323', '', '2223232', '333'),
('2', 'T', '2', 'Edwin ', 'Trigos', 'C', 'edwintrigos24@gmail.com', 'c4ca4238a0b923820dcc509a6f75849b', '222323', '', '2223232', '333'),
('22', 'T', '22', 'Juan', 'Robles', 'P', 'edwintrigos24@gmail.com', 'c4ca4238a0b923820dcc509a6f75849b', '222323', '', '2223232', '333'),
('222', 'T', '222', 'Manuel', 'Carranza', 'T', 'manu@gmail.com', 'c4ca4238a0b923820dcc509a6f75849b', 'calle 34 ', '', '232123214', '333'),
('33', 'T', '33', 'Jose ', 'Carrillo', 'C', 'jose@gmail.co', 'c4ca4238a0b923820dcc509a6f75849b', 'calle 434983129', '', '233435', '333'),
('333', 'A', '333', 'Ruben', 'Botella', 'C', 'ru@gmail.com', 'c4ca4238a0b923820dcc509a6f75849b', 'calle 11', '', '2313123', '333');

INSERT INTO `proyecto` (`idProyecto`, `nombre`, `descripcion`, `estado`, `fechaInicio`, `fechaFinalizacion`, `idUsuario_FK`) VALUES
(2, 'Proyecto para escuela', 'Software biblioteca de escuela', 'T', '2022-03-11 00:00:00', '2022-04-12 00:00:00', '1'),
(3, 'Software para conjunto residencial', '333|', 'E', '2022-03-11 00:00:00', '2022-12-31 00:00:00', '1'),
(4, 'Aerolinea app movil ', 'Aplicación móvil para aerolínea ', 'E', '2020-03-11 00:00:00', '2022-12-31 00:00:00', '1'),
(5, 'Nequi plata infinita', 'Aplicacion movil', 'T', '2012-03-11 00:00:00', '2014-04-12 00:00:00', '111');

