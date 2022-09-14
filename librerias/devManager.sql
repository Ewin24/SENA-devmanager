--usuario admin
--contraseña utilizar
create table empresa(
    nit varchar(20) primary key,
    nombre varchar(50) not null,
    direccion varchar(255) null,
    correo varchar(60) not null,
    telefono varchar(25) not null,
    correoRepresentante varchar(60) not null,
    nombreRepresentante varchar(55) not null
);

create table usuario(
    identificacion varchar(15) primary key,
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
    foreign key(nitEmpresa_FK) references empresa(nit) on delete restrict on update cascade
);

create table habilidad(
    idHabilidad int auto_increment primary key,
    nombre varchar(55) not null,
    descripcion varchar(255) not null,
    experiencia varchar(500) not null,
    nivelDominio char not null
);

create table proyecto(
    idProyecto int auto_increment primary key,
    nombre varchar(55) not null,
    descripcion varchar(500) null,
    estado char null,
    fechaInicio datetime not null,
    fechaFinalizacion datetime not null,
    directorProyecto datetime not null
);

create table perfil(
    idPerfil int auto_increment primary key,
    nombre varchar(55) not null,
    descripcion varchar(500) null
);

create table estudio(
    idEstudio int auto_increment primary key,
    nombreCertificacion varchar(55) not null
);

create table perfilUsuarios(
    idUsuario_FK varchar(15) not null,
    idPerfil_FK int not null,
    foreign key (idUsuario_FK) references usuario(identificacion) on delete restrict on update cascade,
    foreign key (idPerfil_FK) references perfil(idPerfil) on delete restrict on update cascade
);

create table usuarioHabilidad(
    id int auto_increment primary key,
    experiencia varchar(255) not null,
    nivelDominio char not null,
    idUsuario_FK varchar(15) not null,
    idHabilidad_FK int not null,
    foreign key (idUsuario_FK) references usuario(identificacion) on delete restrict on update cascade,
    foreign key (idHabilidad_FK) references habilidad(idHabilidad) on delete restrict on update cascade
);

create table postulaciones(
    id int auto_increment primary key,
    fechaSolicitud datetime not null default now(),
    estado char null,
    idUsuario_FK varchar(15) not null,
    idProyecto_FK int not null,
    foreign key (idUsuario_FK) references usuario(identificacion) on delete restrict on update cascade,
    foreign key (idProyecto_FK) references proyecto(idProyecto) on delete restrict on update cascade
);

create table certificaciones(
    id int auto_increment primary key,
    fechaCertificacion datetime not null,
    fotoCertificado varchar(255) not null,
    idUsuario_FK varchar(15) not null,
    idEstudio_FK int not null,
    foreign key (idUsuario_FK) references usuario(identificacion) on delete restrict on update cascade,
    foreign key (idEstudio_FK) references estudio(idEstudio) on delete restrict on update cascade
);

create table perfilProyecto(
    idPerfil_FK int not null,
    idProyecto_FK int not null,
    foreign key (idPerfil_FK) references perfil(idPerfil) on delete restrict on update cascade,
    foreign key (idProyecto_FK) references proyecto(idProyecto) on delete restrict on update cascade
);

create table perfilEstudio(
    idPerfil_FK int not null,
    idEstudio_FK int not null,
    foreign key (idPerfil_FK) references perfil(idPerfil) on delete restrict on update cascade,
    foreign key (idEstudio_FK) references estudio(idEstudio) on delete restrict on update cascade
);

create table perfilHabilidad(
    idHabilidad_FK int not null,
    idPerfil_FK int not null,
    foreign key (idHabilidad_FK) references habilidad(idHabilidad) on delete restrict on update cascade,
    foreign key (idPerfil_FK) references perfil(idPerfil) on delete restrict on update cascade
);