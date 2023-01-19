-- Poblar base
use devmanager2;

INSERT INTO parametros (id, parametro, valor, descripcion) VALUES('1', 'tipo_identificacion', 'T', 'Tarjeta Identidad');
INSERT INTO parametros (id, parametro, valor, descripcion) VALUES('2', 'tipo_identificacion', 'C', 'Cédula');
INSERT INTO parametros (id, parametro, valor, descripcion) VALUES('3', 'tipo_identificacion', 'R', 'Registro Civil');
INSERT INTO parametros (id, parametro, valor, descripcion) VALUES('4', 'tipo_identificacion', 'P', 'Pasaporte');
INSERT INTO parametros (id, parametro, valor, descripcion) VALUES('5', 'tipo_identificacion', '-', ' ');
INSERT INTO parametros (id, parametro, valor, descripcion) VALUES('6', 'tipo_usuario', 'A', 'Admin');
INSERT INTO parametros (id, parametro, valor, descripcion) VALUES('7', 'tipo_usuario', 'D', 'Director');
INSERT INTO parametros (id, parametro, valor, descripcion) VALUES('8', 'tipo_usuario', 'T', 'Trabajador');
INSERT INTO parametros (id, parametro, valor, descripcion) VALUES('9', 'tipo_usuario', '-', ' ');
INSERT INTO parametros (id, parametro, valor, descripcion) VALUES('10', 'estado_proyecto', 'T', 'Terminado');
INSERT INTO parametros (id, parametro, valor, descripcion) VALUES('11', 'estado_proyecto', 'P', 'Espera');
INSERT INTO parametros (id, parametro, valor, descripcion) VALUES('12', 'estado_proyecto', 'E', 'Ejecución');
INSERT INTO parametros (id, parametro, valor, descripcion) VALUES('13', 'estado_proyecto', '-', '');
INSERT INTO parametros (id, parametro, valor, descripcion) VALUES('14', 'estado_postulacion', 'A', 'Admitido');
INSERT INTO parametros (id, parametro, valor, descripcion) VALUES('15', 'estado_postulacion', 'R', 'Rechazado');
INSERT INTO parametros (id, parametro, valor, descripcion) VALUES('16', 'estado_postulacion', 'E', 'Espera');
INSERT INTO parametros (id, parametro, valor, descripcion) VALUES('17', 'estado_postulacion', '-', '');

-- ############################### PARAMETRIZACION MENU PROYECTOS
-- tblProyectos
CALL llenar_ddl_desde_parametros('tblProyectos','estado','estado_proyecto');
CALL llenar_ddl_desde_usuarios('tblProyectos','id_director');
-- tblHab_Requeridas
CALL parametrizar_desde_habilidades('tblHab_Requeridas','id_habilidad');
CALL parametrizar_desde_proyectos('tblHab_Requeridas','id_proyecto');
-- tblHab_Disponibles
-- NO REQUIERE PARAMETRIZACION está directo sobre la tabla
-- tblContratados
CALL llenar_ddl_desde_usuarios('tblContratados','id_usuario');
CALL llenar_ddl_desde_parametros('tblContratados','estado','estado_postulacion');
-- tblCandidatos
CALL llenar_ddl_desde_usuarios('tblCandidatos','id_usuario');
CALL llenar_ddl_desde_parametros('tblCandidatos','estado','estado_postulacion');


-- ############################### PARAMETRIZACION MENU PERFIL
-- tblEmpleados
CALL parametrizar_desde_tabla('tblEmpleados','tipo_identificacion','parametros','parametro', 'tipo_identificacion','valor','descripcion'); 
CALL parametrizar_desde_tabla('tblEmpleados','tipo_usuario','parametros','parametro', 'tipo_usuario','valor','descripcion'); 
CALL parametrizar_desde_tabla('tblEmpleados','id_empresa','empresas','1', '1','id','nombre'); 


-- tblEstudios
CALL llenar_ddl_desde_parametros('tblEstudios','tipo_identificacion','tipo_identificacion');

-- tblHabilidades
CALL llenar_ddl_desde_parametros('tblEstudios','tipo_identificacion','tipo_identificacion');

-- ############################### PARAMETRIZACION MENU ADMON



DELIMITER //
-- campos id_director
CREATE PROCEDURE `llenar_ddl_desde_usuarios` ( 
	IN nombre_tabla_html varchar(100),
	IN campo_tabla_html varchar(100) 
)
BEGIN
	
	INSERT INTO ddl_parametrizado 
	(tabla, campo, valor, texto)
	(
		SELECT 	nombre_tabla_html, campo_tabla_html, u.id, u.correo
		FROM 	usuarios u 
		WHERE   NOT EXISTS (
			SELECT DISTINCT valor, texto
			FROM 	ddl_parametrizado 
			WHERE	tabla = nombre_tabla_html
			AND		campo = campo_tabla_html
			AND 	u.id  = valor
			AND 	u.correo = texto 
		)
	)
	ON DUPLICATE KEY UPDATE
	-- tabla = VALUES(tabla),
	campo = VALUES(campo),
	valor = VALUES(valor),
	texto = VALUES(texto);

END //
DELIMITER ;



DELIMITER //
-- Campos: estado_proyecto, estado_postulacion, tipo_usuario, tipo_documento 
CREATE PROCEDURE `llenar_ddl_desde_parametros` ( 
	IN nombre_tabla_html varchar(100),
	IN campo_tabla_html varchar(100),
	IN parametro varchar(100)
)
BEGIN
	
	INSERT INTO ddl_parametrizado 
	(tabla, campo, valor, texto)
	(
		SELECT 	
        @nombre_tabla_html, @campo_tabla_html, p.valor, p.descripcion 
		-- @nombre_tabla_html, @campo_tabla_html, p.parametro, p.valor, p.descripcion 
		FROM 	parametros  p 
		WHERE   NOT EXISTS (
			SELECT DISTINCT campo, valor, texto
			FROM 	ddl_parametrizado 
			WHERE	tabla = @nombre_tabla_html
			AND		campo = @campo_tabla_html
			AND 	valor = p.valor
			AND 	texto = p.descripcion 
			
		)
		AND 		p.parametro = @parametro 
	)
	ON DUPLICATE KEY UPDATE
	-- tabla = VALUES(tabla),
	campo = VALUES(campo),
	valor = VALUES(valor),
	texto = VALUES(texto);

END //
DELIMITER ;


DELIMITER //
-- Campo id_habilidad
CREATE PROCEDURE `parametrizar_desde_habilidades` ( 
	IN nombre_tabla_html varchar(100),
	IN campo_tabla_html varchar(100) 
)
BEGIN
	
	INSERT INTO ddl_parametrizado 
	(tabla, campo, valor, texto)
	(
		SELECT 	nombre_tabla_html, campo_tabla_html, h.id, h.descripcion
		FROM 	habilidades h 
		WHERE   NOT EXISTS (
			SELECT DISTINCT valor, texto
			FROM 	ddl_parametrizado 
			WHERE	tabla = nombre_tabla_html
			AND		campo = campo_tabla_html
			AND 	h.id  = valor
			AND 	h.descripcion = texto 
		)
	)
	ON DUPLICATE KEY UPDATE
	-- tabla = VALUES(tabla),
	campo = VALUES(campo),
	valor = VALUES(valor),
	texto = VALUES(texto);

END //
DELIMITER ;


DELIMITER //

CREATE PROCEDURE `parametrizar_desde_proyectos` ( 
	IN nombre_tabla_html varchar(100),
	IN campo_tabla_html varchar(100) 
)
BEGIN
	
	INSERT INTO ddl_parametrizado 
	(tabla, campo, valor, texto)
	(
		SELECT 	nombre_tabla_html, campo_tabla_html, proy.id, proy.nombre
		FROM 	proyectos proy
		WHERE   NOT EXISTS (
			SELECT DISTINCT valor, texto
			FROM 	ddl_parametrizado 
			WHERE	tabla = nombre_tabla_html
			AND		campo = campo_tabla_html
			AND 	proy.id  = valor
			AND 	proy.nombre = texto 
		)
	)
	ON DUPLICATE KEY UPDATE
	-- tabla = VALUES(tabla),
	campo = VALUES(campo),
	valor = VALUES(valor),
	texto = VALUES(texto);

END //

DELIMITER ;



DELIMITER //
-- campos id_director
CREATE PROCEDURE `parametrizar_desde_tabla` ( 
	IN nombre_tabla_html varchar(100),
	IN campo_tabla_html varchar(100),
	IN tabla_origen_bd varchar(100),
	IN nombre_columna_origen varchar(100),
	IN valor_columna_origen varchar(100),
	IN campo_llave varchar(100),
	IN campo_valor varchar(100)
)
BEGIN

		

SET @query = concat("INSERT INTO ddl_parametrizado (tabla, campo, valor, texto) ( SELECT '", 
							@nombre_tabla_html, "','", @campo_tabla_html, "',", @campo_llave, ",", @campo_valor, 
							' FROM  ', @tabla_origen_bd, ' WHERE NOT EXISTS ( SELECT DISTINCT valor, texto FROM ddl_parametrizado WHERE	tabla = ', 
							"'", @nombre_tabla_html,"' AND campo = '", @campo_tabla_html, "'" , " AND valor = ", @campo_llave, " AND texto = ", @campo_valor,
							") AND ", @nombre_columna_origen ," = '", @valor_columna_origen, "') ON DUPLICATE KEY UPDATE valor = VALUES(valor), texto = VALUES(texto);" 
				);

PREPARE stmt1 FROM @query;
EXECUTE stmt1;
DEALLOCATE PREPARE stmt1;
	
END //

DELIMITER ;



SELECT @nombre_tabla_html := 'tblContratados';
SELECT @campo_tabla_html :='estado';
SELECT @tabla_origen_bd := 'parametros';
SELECT @nombre_columna_origen := 'parametro';
SELECT @valor_columna_origen := 'estado_proyecto';
SELECT @campo_llave := 'valor';
SELECT @campo_valor := 'descripcion';


-- SELECT @nombre_tabla_html := 'tblContratados';
-- SELECT @campo_tabla_html :='id_director';
-- SELECT @tabla_origen_bd := 'usuarios';
-- SELECT @nombre_columna_origen := '1';
-- SELECT @valor_columna_origen := '1';
-- SELECT @campo_llave := 'id';
-- SELECT @campo_valor := 'correo';
-- CALL parametrizar_desde_tabla('tblContratados','id_director','usuarios','1', '1','id','correo')


