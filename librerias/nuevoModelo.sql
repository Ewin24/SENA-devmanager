CREATE TABLE empresas (
  id VARCHAR(36) NOT NULL, /* UUID v4 */
  nit VARCHAR(10) NOT NULL,
  nombre VARCHAR(100) NOT NULL,
  direccion VARCHAR(255) NOT NULL,
  correo VARCHAR(320) NOT NULL,
  telefono VARCHAR(10) NOT NULL,
  nombre_representante VARCHAR(100) NOT NULL,
  correo_representante VARCHAR(320) NOT NULL,
  CONSTRAINT pk_empresas PRIMARY KEY (id)
);

CREATE TABLE usuarios (
  id VARCHAR(36) NOT NULL, /* UUID v4 */
  identificacion VARCHAR(15) NOT NULL,
  tipo_identificacion CHAR NOT NULL CHECK (tipo_identificacion IN ('T', 'C', 'R', 'P')), /* Tabla abajo */
  -- nombre_usuario VARCHAR(25) NOT NULL,
  nombres VARCHAR(100) NOT NULL,
  apellidos VARCHAR(100) NOT NULL,
  correo VARCHAR(320) NOT NULL,
  clave_hash VARCHAR(60) NOT NULL, /* PASSWORD_BCRYPT */
  direccion VARCHAR(255) NOT NULL,
  nombre_foto VARCHAR(256) NOT NULL,
  telefono VARCHAR(10) NOT NULL,
  tipo_usuario CHAR NOT NULL DEFAULT 'T' CHECK (tipo_usuario IN ('A', 'D' ,'T')), /* Tabla abajo */
  id_empresa VARCHAR(36) NOT NULL, /* UUID v4 */
  CONSTRAINT pk_usuarios PRIMARY KEY (id),
  CONSTRAINT fk_usuarios_empresas FOREIGN KEY (id_empresa) REFERENCES empresas (id)
);

/*
 * Tipos de identificacion
 * T - Tarjeta de identidad
 * C - Cedula de ciudadania
 * R - Registro civil
 * P - Pasaporte
 */

/*
 * Tipos de usuario
 * A - Administrador
 * D - Director
 * T - Trabajador
 */

CREATE TABLE habilidades (
  id VARCHAR(36) NOT NULL, /* UUID v4 */
  nombre VARCHAR(100) NOT NULL,
  descripcion VARCHAR(255) NOT NULL,
  CONSTRAINT pk_habilidades PRIMARY KEY (id)
);

CREATE TABLE usuarios_habilidades (
  id VARCHAR(36) NOT NULL, /* UUID v4 */
  experiencia VARCHAR(255) NOT NULL,
  id_usuario VARCHAR(36) NOT NULL, /* UUID v4 */
  id_habilidad VARCHAR(36) NOT NULL, /* UUID v4 */
  CONSTRAINT pk_usuarios_habilidades PRIMARY KEY (id),
  CONSTRAINT fk_usuarios_habilidades_usuarios FOREIGN KEY (id_usuario) REFERENCES usuarios (id),
  CONSTRAINT fk_usuarios_habilidades_habilidades FOREIGN KEY (id_habilidad) REFERENCES habilidades (id)
);

CREATE TABLE estudios (
  id VARCHAR(36) NOT NULL, /* UUID v4 */
  nombre VARCHAR(255) NOT NULL,
  CONSTRAINT pk_estudios PRIMARY KEY (id)
);

CREATE TABLE usuarios_estudios (
  id VARCHAR(36) NOT NULL, /* UUID v4 */
  nombre_certificado VARCHAR(255) NOT NULL,
  nombre_archivo VARCHAR(256) NOT NULL,
  fecha_certificado DATE NOT NULL,
  id_usuario VARCHAR(36) NOT NULL, /* UUID v4 */
  id_estudio VARCHAR(36) NOT NULL, /* UUID v4 */
  CONSTRAINT pk_usuarios_estudios PRIMARY KEY (id),
  CONSTRAINT fk_usuarios_estudios_usuarios FOREIGN KEY (id_usuario) REFERENCES estudios (id),
  CONSTRAINT fk_usuarios_estudios_estudios FOREIGN KEY (id_estudio) REFERENCES estudios (id)
);


/*
 * Estados de proyecto
 * T - Terminado
 * P - Por iniciar
 * E - En ejecucion
 */

CREATE TABLE proyectos (
  id VARCHAR(36) NOT NULL, /* UUID v4 */
  nombre VARCHAR(100) NOT NULL,
  descripcion VARCHAR(1000) NOT NULL,
  estado CHAR NOT NULL DEFAULT 'P' CHECK (estado IN ('T', 'P', 'E')), /* Tabla abajo */
  fecha_inicio DATE NOT NULL,
  fecha_fin DATE NOT NULL,
  id_usuario VARCHAR(36) NOT NULL, /* UUID v4 */
  CONSTRAINT pk_proyectos PRIMARY KEY (id),
  CONSTRAINT fk_proyectos_usuarios FOREIGN KEY (id_usuario) REFERENCES usuarios (id)
);

CREATE TABLE rh_proyectos (
  id VARCHAR(36) NOT NULL, /* UUID v4 */
  fecha_solicitud DATE NOT NULL,
  estado CHAR NOT NULL DEFAULT 'E' CHECK (estado IN ('A', 'R', 'E')), /* Tabla abajo */
  id_proyecto VARCHAR(36)  NOT NULL, /* UUID v4 */
  id_usuario VARCHAR(36) NOT NULL, /* UUID v4 */
  CONSTRAINT pK_rh_proyectos PRIMARY KEY (id),
  CONSTRAINT fk_rh_proyectos_proyectos FOREIGN KEY (id_proyecto) REFERENCES proyectos (id),
  CONSTRAINT fk_rh_proyectos_usuarios FOREIGN KEY (id_usuario) REFERENCES usuarios (id)
);

/*
 * Estados de relacion usuarios proyectos
 * A - Aceptado
 * R - Rechazado
 * E - En espera
 */

CREATE TABLE proyectos_habilidades (
  id VARCHAR(36) NOT NULL, /* UUID v4 */
  id_proyecto VARCHAR(36) NOT NULL, /* UUID v4 */
  id_habilidad VARCHAR(36) NOT NULL, /* UUID v4 */
  CONSTRAINT pK_proyectos_habilidades PRIMARY KEY (id),
  CONSTRAINT fK_proyectos_habilidades_proyectos FOREIGN KEY (id_proyecto) REFERENCES proyectos (id),
  CONSTRAINT fK_proyectos_habilidades_habilidades FOREIGN KEY (id_habilidad) REFERENCES habilidades (id)
);


CREATE TABLE ddl_parametrizado (
	id int auto_increment,
	tabla varchar(50) NOT NULL,
	campo varchar(50) NOT NULL,
	valor varchar(50) null,
	texto varchar(50) null,
  	CONSTRAINT pK_ddl_parametrizado PRIMARY KEY (id)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci
COMMENT='Tabla que permite obtener opciones para controles ddl';


-- Poblar base
-- Tablas paramétrizadas
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(1, 'usuarios', 'tipo_identificacion', 'T', 'Tarjeta Identidad');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(2, 'usuarios', 'tipo_identificacion', 'C', 'Cédula');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(3, 'usuarios', 'tipo_identificacion', 'R', 'Registro Civil');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(4, 'usuarios', 'tipo_identificacion', 'P', 'Pasaporte');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(5, 'usuarios', 'usuarios', '-', NULL);
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(6, 'usuarios', 'tipo_usuario', 'A', 'Admin');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(7, 'usuarios', 'tipo_usuario', 'D', 'Director');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(8, 'usuarios', 'tipo_usuario', 'T', 'Trabajador');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(9, 'tipo_usuario', 'tipo_usuario', '-', NULL);
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(10, 'tblProyectos', 'estado', 'T', 'Terminado');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(11, 'tblProyectos', 'estado', 'P', 'Espera');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(12, 'tblProyectos', 'estado', 'E', 'Ejecución');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(13, 'tblProyectos', 'estado', '-', NULL);
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(14, 'rh_proyectos', 'estado', 'A', 'Admitido');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(15, 'rh_proyectos', 'estado', 'R', 'Rechazado');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(16, 'rh_proyectos', 'estado', 'E', 'Espera');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(17, 'rh_proyectos', 'estado', '-', NULL);
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(18, 'tblProyectos', 'correo_director', '8fa903bc-0789-43b2-901b-70d6c60334ba', 'fgarcia@gmail.com');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(19, 'tblProyectos', 'correo_director', '499a9d4a-fbf1-4ea7-850b-01bf301a98af', 'wtrigos@gmail.com');



-- empresa
-- UUID V4 - https://www.delftstack.com/howto/php/php-uuid/
INSERT INTO empresas
(id, nit, nombre, direccion, correo, telefono, nombre_representante, correo_representante)
VALUES
('20a9d4e8-63a8-48f0-910f-c7339d8fd7ec', '333', 'aguas de bogota', 'Bogotá, Cundinamarca ', 'aguas.bogota@gmail.com', '33333333', 'Juan Alvarez', 'juan123@gmail.com'),
('b7f6046a-b834-48f0-856e-8a360b495406', '334', 'actses', 'Bucaramanga, Cundinamarca ', 'actses@gmail.com', '33333333', 'Juan Alvarez', 'aljuan@gmail.com');

INSERT INTO usuarios
(id, identificacion, tipo_identificacion, nombres, apellidos, correo, clave_hash, direccion, nombre_foto, telefono, tipo_usuario, id_empresa)
VALUES
('499a9d4a-fbf1-4ea7-850b-01bf301a98af', '1098657073', 'C', 'William', 'Trigos', 'wtrigos@gmail.com', 'c4ca4238a0b923820dcc509a6f75849b', 'Provenza', 'fwilliam.jpg', '334422', 'D', '20a9d4e8-63a8-48f0-910f-c7339d8fd7ec'),
('8fa903bc-0789-43b2-901b-70d6c60334ba', '1095', 'C', 'Felipe', 'Garcia', 'fgarcia@gmail.com', 'MD5(felipe)', 'Concordia', 'ffelipe.jpg', '444222', 'D', '20a9d4e8-63a8-48f0-910f-c7339d8fd7ec'),
('eb036f8a-75bd-4811-a477-1444e2521f3b', '10951', 'R', 'Edwin', 'Trigos', 'etrigos@gmail.com', 'c4ca4238a0b923820dcc509a6f75849b', 'provenza', 'fedwin.jpg', '313316', 'T', '20a9d4e8-63a8-48f0-910f-c7339d8fd7ec'),
('25c00e25-9042-4f04-b059-c34820b800f8', '10985', 'P', 'Pepito', 'Peréz', 'pper@aol.com', 'MD5(pepito)', 'maracay', 'fpepito.jpg', '039', 'T', 'b7f6046a-b834-48f0-856e-8a360b495406');

INSERT INTO proyectos
(id, nombre, descripcion, estado, fecha_inicio, fecha_fin, id_usuario)
VALUES
('abfd9937-a08b-47b0-8b64-3338455d99f4','Proyecto para escuela', 'Software biblioteca de escuela', 'T', '2022-03-11 00:00:00', '2022-04-12 00:00:00', '499a9d4a-fbf1-4ea7-850b-01bf301a98af'),
('43a9245a-275a-4b23-8ac0-a63fefa13013','Software para conjunto residencial', 'Control acceso', 'P','2022-03-11 00:00:00', '2022-12-31 00:00:00', '8fa903bc-0789-43b2-901b-70d6c60334ba'),
('bbfc7b5b-f77f-4a58-a3ce-5163bac61a4c','Aerolinea app movil ', 'Aplicación móvil para aerolínea ', 'E', '2020-03-11 00:00:00', '2022-12-31 00:00:00', '8fa903bc-0789-43b2-901b-70d6c60334ba'),
('f660bbbf-dd1a-4eab-9866-dba8092c94c5','Nequi plata infinita', 'Aplicacion movil', 'T', '2012-03-11 00:00:00', '2014-04-12 00:00:00', '499a9d4a-fbf1-4ea7-850b-01bf301a98af');

INSERT INTO habilidades (id,nombre,descripcion) 
VALUES
('0463add9-313e-49bf-a07e-800612c36263','Javascript','Manejo Javascript'),
('65374dc5-692f-483d-9809-3371a7222a79','PHP','Manejo de PHP');

INSERT INTO usuarios_habilidades (id,experiencia,id_usuario,id_habilidad)
VALUES
('2fbaea0f-7171-4f8f-8615-27c4bc90f9f1','Autodidacta','eb036f8a-75bd-4811-a477-1444e2521f3b','65374dc5-692f-483d-9809-3371a7222a79'),
('e48a2ef4-bf5f-4d9b-8352-7f8067ea6809','Certificada','8fa903bc-0789-43b2-901b-70d6c60334ba','0463add9-313e-49bf-a07e-800612c36263');

INSERT INTO rh_proyectos (id,fecha_solicitud,estado,id_proyecto,id_usuario) 
VALUES
('39070ae4-a9f5-477b-aeb4-a28744b95776','2022-03-14','E','f660bbbf-dd1a-4eab-9866-dba8092c94c5','eb036f8a-75bd-4811-a477-1444e2521f3b'),
('4144ebe5-51d0-41f6-9c1d-1ce3917fb53c','2022-03-14','A','43a9245a-275a-4b23-8ac0-a63fefa13013','eb036f8a-75bd-4811-a477-1444e2521f3b'),
('95eb15b1-9912-44f9-963c-0635318dd7fa','2022-03-14','E','f660bbbf-dd1a-4eab-9866-dba8092c94c5','8fa903bc-0789-43b2-901b-70d6c60334ba');

INSERT INTO proyectos_habilidades (id,id_proyecto,id_habilidad) 
VALUES
('12c1ec3e-6d4b-4379-9322-195269bc5bd4','f660bbbf-dd1a-4eab-9866-dba8092c94c5','0463add9-313e-49bf-a07e-800612c36263'),
('6f384e65-a7b6-4814-b5bd-dfeda652d748','43a9245a-275a-4b23-8ac0-a63fefa13013','65374dc5-692f-483d-9809-3371a7222a79');
