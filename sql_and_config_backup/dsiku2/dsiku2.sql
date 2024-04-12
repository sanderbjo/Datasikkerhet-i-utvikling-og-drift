-- MySQL Script generated by MySQL Workbench
-- Tue Apr  9 18:11:11 2024
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema dsiku2
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `dsiku2` ;

-- -----------------------------------------------------
-- Schema dsiku2
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `dsiku2` DEFAULT CHARACTER SET utf8 ;
USE `dsiku2` ;

-- -----------------------------------------------------
-- Table `dsiku2`.`role`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `dsiku2`.`role` (
  `id` INT NOT NULL,
  `description` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `role_UNIQUE` (`description` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `dsiku2`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `dsiku2`.`user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role_id` INT NOT NULL,
  `image` VARCHAR(45) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) VISIBLE,
  INDEX `fk_user_role_idx` (`role_id` ASC) VISIBLE,
  UNIQUE INDEX `image_UNIQUE` (`image` ASC) VISIBLE,
  CONSTRAINT `fk_user_role`
    FOREIGN KEY (`role_id`)
    REFERENCES `dsiku2`.`role` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `dsiku2`.`subject`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `dsiku2`.`subject` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `code` VARCHAR(20) NOT NULL,
  `pin` VARCHAR(4) NOT NULL,
  `user_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `code_UNIQUE` (`code` ASC) VISIBLE,
  INDEX `fk_subject_user1_idx` (`user_id` ASC) VISIBLE,
  CONSTRAINT `fk_subject_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `dsiku2`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `dsiku2`.`message`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `dsiku2`.`message` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `content` VARCHAR(4000) NOT NULL,
  `user_id` INT NOT NULL,
  `subject_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_message_user1_idx` (`user_id` ASC) VISIBLE,
  INDEX `fk_message_subject1_idx` (`subject_id` ASC) VISIBLE,
  CONSTRAINT `fk_message_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `dsiku2`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_message_subject1`
    FOREIGN KEY (`subject_id`)
    REFERENCES `dsiku2`.`subject` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `dsiku2`.`report`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `dsiku2`.`report` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `content` VARCHAR(200) NULL,
  `message_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_report_message1_idx` (`message_id` ASC) VISIBLE,
  CONSTRAINT `fk_report_message1`
    FOREIGN KEY (`message_id`)
    REFERENCES `dsiku2`.`message` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `dsiku2`.`answer`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `dsiku2`.`answer` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `content` VARCHAR(4000) NOT NULL,
  `message_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_answer_message1_idx` (`message_id` ASC) VISIBLE,
  INDEX `fk_answer_user1_idx` (`user_id` ASC) VISIBLE,
  CONSTRAINT `fk_answer_message1`
    FOREIGN KEY (`message_id`)
    REFERENCES `dsiku2`.`message` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_answer_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `dsiku2`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `dsiku2`.`anon_comment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `dsiku2`.`anon_comment` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `content` VARCHAR(2000) NOT NULL,
  `message_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_anon_message_message1_idx` (`message_id` ASC) VISIBLE,
  CONSTRAINT `fk_anon_message_message1`
    FOREIGN KEY (`message_id`)
    REFERENCES `dsiku2`.`message` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;