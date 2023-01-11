-- Poblar base
-- Tablas paramétrizadas
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(1, 'usuarios', 'tipo_identificacion', 'T', 'Tarjeta Identidad');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(2, 'usuarios', 'tipo_identificacion', 'C', 'Cédula');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(3, 'usuarios', 'tipo_identificacion', 'R', 'Registro Civil');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(4, 'usuarios', 'tipo_identificacion', 'P', 'Pasaporte');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(5, 'usuarios', 'usuarios', '-', '');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(6, 'usuarios', 'tipo_usuario', 'A', 'Admin');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(7, 'usuarios', 'tipo_usuario', 'D', 'Director');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(8, 'usuarios', 'tipo_usuario', 'T', 'Trabajador');
INSERT INTO ddl_parametrizado (id, tabla, campo, valor, texto) VALUES(9, 'tipo_usuario', 'tipo_usuario', '-', '');
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
('499a9d4a-fbf1-4ea7-850b-01bf301a98af', '1098657073', 'C', 'William', 'Trigos', 'wtrigos@gmail.com', '$2y$15$T5y8d1BDskwCwRzh7xuGIu0ysZvvdkgkoWie2L0Ll9HBxgMbfS4SK', 'Provenza', 'fwilliam.jpg', '334422', 'D', '20a9d4e8-63a8-48f0-910f-c7339d8fd7ec'),
('8fa903bc-0789-43b2-901b-70d6c60334ba', '1095', 'C', 'Felipe', 'Garcia', 'fgarcia@gmail.com', '$2y$15$T5y8d1BDskwCwRzh7xuGIu0ysZvvdkgkoWie2L0Ll9HBxgMbfS4SK', 'Concordia', 'ffelipe.jpg', '444222', 'D', '20a9d4e8-63a8-48f0-910f-c7339d8fd7ec'),
('eb036f8a-75bd-4811-a477-1444e2521f3b', '10951', 'R', 'Edwin', 'Trigos', 'etrigos@gmail.com', '$2y$15$T5y8d1BDskwCwRzh7xuGIu0ysZvvdkgkoWie2L0Ll9HBxgMbfS4SK', 'provenza', 'fedwin.jpg', '313316', 'T', '20a9d4e8-63a8-48f0-910f-c7339d8fd7ec'),
('25c00e25-9042-4f04-b059-c34820b800f8', '10985', 'P', 'Pepito', 'Peréz', 'pper@aol.com', '$2y$15$T5y8d1BDskwCwRzh7xuGIu0ysZvvdkgkoWie2L0Ll9HBxgMbfS4SK', 'maracay', 'fpepito.jpg', '039', 'T', 'b7f6046a-b834-48f0-856e-8a360b495406');

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

