
create database devmanager2;
use devmanager2;

GRANT ALL PRIVILEGES ON *.* TO root@'%' IDENTIFIED BY rootPassword WITH GRANT OPTION;
GRANT ALL PRIVILEGES ON *.* TO user@'%' IDENTIFIED BY userPassword WITH GRANT OPTION;

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
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci
COMMENT='Tabla que permite obtener opciones para controles ddl';
