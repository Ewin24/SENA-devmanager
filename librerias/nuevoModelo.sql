
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



--empresa
INSERT INTO `empresa` (`nit`, `nombre`, `direccion`, `correo`, `telefono`, `correoRepresentante`, `nombreRepresentante`) VALUES ('333', 'aguas de bogota', 'Bogotá, Cundinamarca ', 'aguas.bogota@gmail.com', '33333333', 'juan123@gmail.com', 'Juan Alvarez');

--user
INSERT INTO `usuario` (`identificacion`, `tipoUsuario`, `nombreUsuario`, `nombre`, `apellido`, `tipoIdentificacion`, `correo`, `clave`, `direccion`, `foto`, `telefono`, `nitEmpresa_FK`) VALUES ('1004023221', 'A', 'admin', 'admin', 'admin', 'C', 'admin@admin.com', MD5('admin'), 'admin', NULL, NULL, '333');