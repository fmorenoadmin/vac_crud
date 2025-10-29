/*--------------------------------------------*/
CREATE DATABASE IF NOT EXISTS vac_crud;
/*--------------------------------------------*/
CREATE TABLE IF NOT EXISTS vac_crud.usuarios(
	id_u INT PRIMARY KEY AUTO_INCREMENT,
	nombres VARCHAR(350) NULL DEFAULT NULL,
	apellidos VARCHAR(350) NULL DEFAULT NULL,
	usuario VARCHAR(350) NULL DEFAULT NULL,
	correo VARCHAR(350) NULL DEFAULT NULL,
	obs TEXT NULL DEFAULT NULL,
	created_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
	id_created INT NULL DEFAULT 1,
	updated_at DATETIME NULL DEFAULT NULL,
	id_updated INT NULL DEFAULT 0,
	motivo_drop TEXT NULL DEFAULT NULL,
	drop_at DATETIME NULL DEFAULT NULL,
	id_drop INT NULL DEFAULT 0,
	status INT(1) NULL DEFAULT 1
);
/*--------------------------------------------*/
CREATE TABLE IF NOT EXISTS vac_crud.tipo_usuarios(
	id_tu INT PRIMARY KEY AUTO_INCREMENT,
	nombre VARCHAR(350) NULL DEFAULT NULL,
	obs TEXT NULL DEFAULT NULL,
	created_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
	id_created INT NULL DEFAULT 1,
	updated_at DATETIME NULL DEFAULT NULL,
	id_updated INT NULL DEFAULT 0,
	motivo_drop TEXT NULL DEFAULT NULL,
	drop_at DATETIME NULL DEFAULT NULL,
	id_drop INT NULL DEFAULT 0,
	status INT(1) NULL DEFAULT 1
);
/*--------------------------------------------*/
ALTER TABLE vac_crud.usuarios
	ADD COLUMN id_tu INT NULL DEFAULT 0 AFTER id_u
;
/*--------------------------------------------*/
CREATE VIEW vac_crud.view_users_all AS
	SELECT 
		u.*,
		tu.nombre AS nombre_tipo
	FROM vac_crud.usuarios u
		INNER JOIN vac_crud.tipo_usuarios tu ON u.id_tu=tu.id_tu
	WHERE u.status<>2
;
/*--------------------------------------------*/