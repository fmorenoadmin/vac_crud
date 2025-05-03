<p align="center">
	<img src="assets/img/logo.jpg" height="320px" title="Icono">
</p>

[![Canal de GitHub](https://img.shields.io/badge/Canal-GitHub-black)](https://github.com/fmorenoadmin)
[![Sígueme en Twitter](https://img.shields.io/twitter/follow/sendgrid.svg?style=social&label=Sígueme)](https://twitter.com/FrankMartinMor1)
[![Sígueme en Facebook](https://img.shields.io/badge/Sígueme-@FrankMartinMA-blue)](https://facebook.com/fmorenoadmin)
[![Sígueme en Facebook](https://img.shields.io/badge/Sígueme-@frankmartinmoreno-ff69b4)](https://instagram.com/fmorenoadmin)
[![Escríbeme en Facebook](https://img.shields.io/badge/Escríbeme-@FrankMartinMA-blue)](https://m.me/fmorenoadmin)
[![Escríbeme en WhatsApp](https://img.shields.io/badge/Escríbeme-WhathApp-green)](https://wa.me/+51924741703)
[![Mi Web](https://img.shields.io/badge/Mi_Página-Web-blueviolet)](https://fmorenoadmin.com.pe)

## Metodología de Programación VAC-PHP:

# vac_crud
Ejemplos de CRUD completo usando la metodología VAC
En el Archivo index.php<br>Te muestro una lista completa del CRUD completo usando mi metodología

## Primero Crea la Base de datos:

<code>
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
</code>

## Luego guíate del flujo del index.php