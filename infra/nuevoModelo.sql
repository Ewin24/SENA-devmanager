CREATE DATABASE devmanager2;
use devmanager2;

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
  CONSTRAINT fk_usuarios_estudios_usuarios FOREIGN KEY (id_usuario) REFERENCES usuarios (id),
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
COMMENT='Tabla que permite obtener opciones para controles ddl';

CREATE TABLE parametros (
	id int auto_increment,
	parametro varchar(100) NOT NULL,
	valor varchar(40) NOT NULL,
	descripcion varchar(100) NOT NULL,
  CONSTRAINT pK_parametros PRIMARY KEY (id)
)
COMMENT='Tabla que permite establecer parametros para tipos documentos, estados, etc';

--trigg para ddl_parametrizado
DELIMITER $$
CREATE TRIGGER tr_delete_user_ddl
AFTER INSERT ON usuarios
FOR EACH ROW
BEGIN
	IF NEW.tipo_usuario = 'D'  THEN 
		INSERT INTO ddl_parametrizado (tabla, campo, valor, texto) 
		VALUES ('tblProyectos', 'id_director', NEW.id, NEW.correo);
	END IF;
END;$$

DELIMITER $$
CREATE TRIGGER tr_update_user_ddl
AFTER UPDATE ON usuarios
FOR EACH ROW
BEGIN
    IF NEW.tipo_usuario = 'D' AND OLD.tipo_usuario <> 'D' THEN
        INSERT INTO ddl_parametrizado (tabla, campo, valor, texto) 
		VALUES ('tblProyectos', 'id_director', NEW.id, NEW.correo);
    END IF;

    IF NEW.tipo_usuario <> 'D' AND OLD.tipo_usuario = 'D' THEN
        DELETE FROM ddl_parametrizado
        WHERE valor = OLD.id;
    END IF;
   
   	IF NEW.correo <> OLD.correo THEN
        UPDATE ddl_parametrizado SET texto = NEW.correo WHERE valor = NEW.id AND tabla = 'tblProyectos';
    END IF;
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER tr_delete_user_ddl
AFTER DELETE ON usuarios
FOR EACH ROW
BEGIN
    DELETE FROM ddl_parametrizado
    WHERE valor = OLD.id;
END $$
DELIMITER ;

--empresa
DELIMITER $$
CREATE TRIGGER tr_insert_empresa_ddl
AFTER INSERT ON empresas
FOR EACH ROW
BEGIN
	INSERT INTO ddl_parametrizado (tabla, campo, valor, texto) 
	VALUES ('tblEmpleados', 'id_empresa', NEW.id, NEW.nombre);
END $$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER tr_update_empresa_ddl
AFTER UPDATE ON empresas
FOR EACH ROW
BEGIN
    UPDATE ddl_parametrizado 
    SET texto = NEW.nombre
    WHERE valor = NEW.id;
END $$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER tr_delete_empresa_ddl
AFTER DELETE ON empresas
FOR EACH ROW
BEGIN
    DELETE FROM ddl_parametrizado
    WHERE valor = OLD.id;
END $$
DELIMITER ;

--habilidades
DELIMITER $$
CREATE TRIGGER tr_insert_habilidad_ddl
AFTER INSERT ON habilidades
FOR EACH ROW
BEGIN
    INSERT INTO ddl_parametrizado (tabla, campo, valor, texto) 
	VALUES ('tblHabilidades', 'id_habilidad', NEW.id, NEW.nombre);   
END $$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER tr_update_habilidad_ddl
AFTER UPDATE ON habilidades
FOR EACH ROW
BEGIN
    UPDATE ddl_parametrizado
    SET texto = NEW.nombre
    WHERE valor = NEW.id;
END $$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER tr_delete_habilidad_ddl
AFTER DELETE ON habilidades
FOR EACH ROW
BEGIN
    DELETE FROM ddl_parametrizado
    WHERE valor = OLD.id;
END $$
DELIMITER ;

--estudios
DELIMITER $$
CREATE TRIGGER tr_insert_estudio_ddl
AFTER INSERT ON estudios
FOR EACH ROW
BEGIN
    INSERT INTO ddl_parametrizado (tabla, campo, valor, texto) 
	VALUES ('tblEstudios', 'id_estudio', NEW.id, NEW.nombre);  
END $$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER tr_update_estudio_ddl
AFTER UPDATE ON estudios
FOR EACH ROW
BEGIN
    UPDATE ddl
    SET nombre = NEW.nombre
    WHERE valor = NEW.id;
END $$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER tr_delete_estudio_ddl
AFTER DELETE ON estudios
FOR EACH ROW
BEGIN
    DELETE FROM ddl_parametrizado
    WHERE valor = OLD.id;
END $$
DELIMITER ;



-- Poblar base

INSERT INTO parametros (id, parametro, valor, descripcion) VALUES(1, 'tipo_identificacion', 'T', 'Tarjeta Identidad');
INSERT INTO parametros (id, parametro, valor, descripcion) VALUES(2, 'tipo_identificacion', 'C', 'Cédula');
INSERT INTO parametros (id, parametro, valor, descripcion) VALUES(3, 'tipo_identificacion', 'R', 'Registro Civil');
INSERT INTO parametros (id, parametro, valor, descripcion) VALUES(4, 'tipo_identificacion', 'P', 'Pasaporte');
INSERT INTO parametros (id, parametro, valor, descripcion) VALUES(5, 'tipo_identificacion', '-', ' ');
INSERT INTO parametros (id, parametro, valor, descripcion) VALUES(6, 'tipo_usuario', 'A', 'Admin');
INSERT INTO parametros (id, parametro, valor, descripcion) VALUES(7, 'tipo_usuario', 'D', 'Director');
INSERT INTO parametros (id, parametro, valor, descripcion) VALUES(8, 'tipo_usuario', 'T', 'Trabajador');
INSERT INTO parametros (id, parametro, valor, descripcion) VALUES(9, 'tipo_usuario', '-', ' ');
INSERT INTO parametros (id, parametro, valor, descripcion) VALUES(10, 'estado_proyecto', 'T', 'Terminado');
INSERT INTO parametros (id, parametro, valor, descripcion) VALUES(11, 'estado_proyecto', 'P', 'Espera');
INSERT INTO parametros (id, parametro, valor, descripcion) VALUES(12, 'estado_proyecto', 'E', 'Ejecución');
INSERT INTO parametros (id, parametro, valor, descripcion) VALUES(13, 'estado_proyecto', '-', '');
INSERT INTO parametros (id, parametro, valor, descripcion) VALUES(14, 'estado_postulacion', 'A', 'Admitido');
INSERT INTO parametros (id, parametro, valor, descripcion) VALUES(15, 'estado_postulacion', 'R', 'Rechazado');
INSERT INTO parametros (id, parametro, valor, descripcion) VALUES(16, 'estado_postulacion', 'E', 'Espera');
INSERT INTO parametros (id, parametro, valor, descripcion) VALUES(17, 'estado_postulacion', '-', '');

-- Tablas paramétrizadas
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(1, 'tblEmpleados', 'tipo_identificacion', 'T', 'Tarjeta Identidad');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(2, 'tblEmpleados', 'tipo_identificacion', 'C', 'Cédula');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(3, 'tblEmpleados', 'tipo_identificacion', 'R', 'Registro Civil');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(4, 'tblEmpleados', 'tipo_identificacion', 'P', 'Pasaporte');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(5, 'tblEmpleados', 'tipo_identificacion', '-', ' ');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(6, 'tblEmpleados', 'tipo_usuario', 'A', 'Admin');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(7, 'tblEmpleados', 'tipo_usuario', 'D', 'Director');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(8, 'tblEmpleados', 'tipo_usuario', 'T', 'Trabajador');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(9, 'tblEmpleados', 'tipo_usuario', '-', ' ');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(10, 'tblProyectos', 'estado', 'T', 'Terminado');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(11, 'tblProyectos', 'estado', 'P', 'Espera');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(12, 'tblProyectos', 'estado', 'E', 'Ejecución');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(13, 'tblProyectos', 'estado', '-', '');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(14, 'rh_proyectos', 'estado', 'A', 'Admitido');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(15, 'rh_proyectos', 'estado', 'R', 'Rechazado');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(16, 'rh_proyectos', 'estado', 'E', 'Espera');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(17, 'rh_proyectos', 'estado', '-', '');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(18, 'tblProyectos', 'id_director', '8fa903bc-0789-43b2-901b-70d6c60334ba', 'fgarcia@gmail.com');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(19, 'tblProyectos', 'id_director', '499a9d4a-fbf1-4ea7-850b-01bf301a98af', 'wtrigos@gmail.com');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(20, 'tblHab_Requeridas', 'id_habilidad', '0463add9-313e-49bf-a07e-800612c36263', 'Javascript');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(21, 'tblHab_Requeridas', 'id_habilidad', '65374dc5-692f-483d-9809-3371a7222a79', 'PHP');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(22, 'tblHab_Disponibles', 'id_habilidad', '0463add9-313e-49bf-a07e-800612c36263', 'Javascript');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(23, 'tblHab_Disponibles', 'id_habilidad', '65374dc5-692f-483d-9809-3371a7222a79', 'PHP');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(24, 'tblContratados', 'id_usuario', '8fa903bc-0789-43b2-901b-70d6c60334ba', 'fgarcia@gmail.com');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(25, 'tblContratados', 'id_usuario', 'eb036f8a-75bd-4811-a477-1444e2521f3b', 'etrigos@gmail.com');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(26, 'tblContratados', 'id_usuario', '499a9d4a-fbf1-4ea7-850b-01bf301a98af', 'wtrigos@gmail.com');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(27, 'tblContratados', 'id_usuario', '25c00e25-9042-4f04-b059-c34820b800f8', 'pper@aol.com');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(28, 'tblCandidatos', 'id_usuario', '8fa903bc-0789-43b2-901b-70d6c60334ba', 'fgarcia@gmail.com');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(29, 'tblCandidatos', 'id_usuario', 'eb036f8a-75bd-4811-a477-1444e2521f3b', 'etrigos@gmail.com');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(30, 'tblCandidatos', 'id_usuario', '499a9d4a-fbf1-4ea7-850b-01bf301a98af', 'wtrigos@gmail.com');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(31, 'tblCandidatos', 'id_usuario', '25c00e25-9042-4f04-b059-c34820b800f8', 'pper@aol.com');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(32, 'tblCandidatos', 'estado', 'A', 'Aceptado');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(33, 'tblCandidatos', 'estado', 'R', 'Rechazado');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(34, 'tblCandidatos', 'estado', 'E', 'En espera');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(35, 'tblCandidatos', 'estado', '-', '');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(36, 'tblHab_Requeridas', 'id_proyecto', '43a9245a-275a-4b23-8ac0-a63fefa13013', 'Software para conjunto residencial');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(37, 'tblHab_Requeridas', 'id_proyecto', 'abfd9937-a08b-47b0-8b64-3338455d99f4', 'Proyecto para escuela');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(38, 'tblHab_Requeridas', 'id_proyecto', 'bbfc7b5b-f77f-4a58-a3ce-5163bac61a4c', 'Aerolinea app movil ');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(39, 'tblHab_Requeridas', 'id_proyecto', 'f660bbbf-dd1a-4eab-9866-dba8092c94c5', 'Nequi plata infinita');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(40, 'tblHab_Disponibles', 'id_proyecto', '43a9245a-275a-4b23-8ac0-a63fefa13013', 'Software para conjunto residencial');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(41, 'tblHab_Disponibles', 'id_proyecto', 'abfd9937-a08b-47b0-8b64-3338455d99f4', 'Proyecto para escuela');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(42, 'tblHab_Disponibles', 'id_proyecto', 'bbfc7b5b-f77f-4a58-a3ce-5163bac61a4c', 'Aerolinea app movil ');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(43, 'tblHab_Disponibles', 'id_proyecto', 'f660bbbf-dd1a-4eab-9866-dba8092c94c5', 'Nequi plata infinita');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(44, 'tblContratados', 'estado', 'A', 'Aceptado');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(45, 'tblContratados', 'estado', 'E', 'Rechazado');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(46, 'tblContratados', 'estado', 'R', 'En espera');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(47, 'tblContratados', 'estado', '-', ' ');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(48, 'tblEmpleados', 'id_empresa', '20a9d4e8-63a8-48f0-910f-c7339d8fd7ec', 'aguas de bogota');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(49, 'tblEmpleados', 'id_empresa', 'b7f6046a-b834-48f0-856e-8a360b495406', 'actses');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(50, 'tblHabilidades', 'id_habilidad', '65374dc5-692f-483d-9809-3371a7222a79', 'PHP');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(51, 'tblHabilidades', 'id_habilidad', '0463add9-313e-49bf-a07e-800612c36263', 'JavaScript');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(52, 'tblEstudios', 'id_estudio', '50c46fc7-9066-11ed-aeb0-1701c1c49394', 'Basica Primaria');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(53, 'tblEstudios', 'id_estudio', '788486b4-9066-11ed-aeb0-1701c1c49394', 'Maestria');


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
('499a9d4a-fbf1-4ea7-850b-01bf301a98af', '3', 'C', 'William', 'Trigos', 'wtrigos@gmail.com', '$2y$15$T5y8d1BDskwCwRzh7xuGIu0ysZvvdkgkoWie2L0Ll9HBxgMbfS4SK', 'Provenza', 'fwilliam.jpg', '334422', 'D', '20a9d4e8-63a8-48f0-910f-c7339d8fd7ec'),
('8fa903bc-0789-43b2-901b-70d6c60334ba', '2', 'C', 'Felipe', 'Garcia', 'fgarcia@gmail.com', '$2y$15$T5y8d1BDskwCwRzh7xuGIu0ysZvvdkgkoWie2L0Ll9HBxgMbfS4SK', 'Concordia', 'ffelipe.jpg', '444222', 'D', '20a9d4e8-63a8-48f0-910f-c7339d8fd7ec'),
('eb036f8a-75bd-4811-a477-1444e2521f3b', '1', 'R', 'Edwin', 'Trigos', 'etrigos@gmail.com', '$2y$15$T5y8d1BDskwCwRzh7xuGIu0ysZvvdkgkoWie2L0Ll9HBxgMbfS4SK', 'provenza', 'fedwin.jpg', '313316', 'T', '20a9d4e8-63a8-48f0-910f-c7339d8fd7ec'),
('25c00e25-9042-4f04-b059-c34820b800f8', '4', 'P', 'Pepito', 'Peréz', 'pper@aol.com', '$2y$15$T5y8d1BDskwCwRzh7xuGIu0ysZvvdkgkoWie2L0Ll9HBxgMbfS4SK', 'maracay', 'fpepito.jpg', '039', 'T', 'b7f6046a-b834-48f0-856e-8a360b495406');

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

INSERT INTO estudios (`id`, `nombre`) VALUES ('50c46fc7-9066-11ed-aeb0-1701c1c49394', 'Basica Primaria');
INSERT INTO estudios (`id`, `nombre`) VALUES ('788486b4-9066-11ed-aeb0-1701c1c49394', 'Maestria');

INSERT INTO usuarios_estudios (id, nombre_certificado, nombre_archivo, fecha_certificado, id_usuario, id_estudio) VALUES('a3501f9f-1c5d-4589-92a3-21f2ad42c210', 'Prueba Edw', ' ', '2022-03-24', 'eb036f8a-75bd-4811-a477-1444e2521f3b', '788486b4-9066-11ed-aeb0-1701c1c49394');
INSERT INTO usuarios_estudios (id, nombre_certificado, nombre_archivo, fecha_certificado, id_usuario, id_estudio) VALUES('2bcf4528-8b81-4e2a-be59-7fa18964d6b9', 'Prueba Willa', ' ', '2022-03-18', '499a9d4a-fbf1-4ea7-850b-01bf301a98af', '50c46fc7-9066-11ed-aeb0-1701c1c49394');
