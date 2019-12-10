-- MySQL Workbench Synchronization
-- Generated: 2019-10-28 21:48
-- Model: New Model
-- Version: 1.0
-- Project: Name of the project
-- Author: INESMARIA

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

CREATE TABLE IF NOT EXISTS `weber`.`detalle_ventas` (
  `Id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ventas_id` INT(11) UNSIGNED NOT NULL,
  `producto_id` INT(11) UNSIGNED NOT NULL,
  `cantidad` INT(11) UNSIGNED NOT NULL,
  `precio_venta` DOUBLE NOT NULL,
  PRIMARY KEY (`Id`),
  INDEX `fk_detalle_ventas_ventas1_idx` (`ventas_id` ASC) ,
  INDEX `fk_detalle_ventas_producto1_idx` (`producto_id` ASC) ,
  CONSTRAINT `fk_detalle_ventas_ventas1`
    FOREIGN KEY (`ventas_id`)
    REFERENCES `weber`.`ventas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_detalle_ventas_producto1`
    FOREIGN KEY (`producto_id`)
    REFERENCES `weber`.`producto` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 155
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `weber`.`producto` (
  `id` INT(11) UNSIGNED NOT NULL,
  `nombres` VARCHAR(244) NOT NULL,
  `precio` DOUBLE NOT NULL,
  `stock` INT(11) UNSIGNED NOT NULL,
  `estado` ENUM('Activo', 'Inactivo') NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 9
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `weber`.`ventas` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `numero_serie` VARCHAR(244) NOT NULL,
  `cliente_id` INT(10) UNSIGNED NOT NULL,
  `empleado_id` INT(10) UNSIGNED NOT NULL,
  `fecha_venta` DATETIME NOT NULL,
  `monto` DOUBLE NOT NULL,
  `estado` ENUM('Activo', 'Inactivo') NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_ventas_Usuarios1_idx` (`cliente_id` ASC) ,
  INDEX `fk_ventas_Usuarios2_idx` (`empleado_id` ASC) ,
  CONSTRAINT `fk_ventas_Usuarios1`
    FOREIGN KEY (`cliente_id`)
    REFERENCES `weber`.`usuarios` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ventas_Usuarios2`
    FOREIGN KEY (`empleado_id`)
    REFERENCES `weber`.`usuarios` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 94
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `weber`.`usuarios` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombres` VARCHAR(60) NOT NULL,
  `apellidos` VARCHAR(60) NOT NULL,
  `tipo_documento` ENUM('C.C', 'C.E', 'T.I') NOT NULL,
  `documento` BIGINT(19) UNSIGNED NOT NULL,
  `telefono` BIGINT(19) UNSIGNED NOT NULL,
  `direccion` VARCHAR(70) NOT NULL,
  `user` VARCHAR(30) NULL DEFAULT NULL,
  `password` VARCHAR(30) NULL DEFAULT NULL,
  `rol` ENUM('Empleado', 'Cliente') NOT NULL,
  `estado` ENUM('Activo', 'Inactivo') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `documento_UNIQUE` (`documento` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
