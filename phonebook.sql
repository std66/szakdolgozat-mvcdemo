-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema phonebook
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema phonebook
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `phonebook` DEFAULT CHARACTER SET utf8 ;
USE `phonebook` ;

-- -----------------------------------------------------
-- Table `phonebook`.`person`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `phonebook`.`person` (
  `person_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'A személy azonosítója.',
  `name` VARCHAR(45) NOT NULL COMMENT 'A személy neve.',
  `address` VARCHAR(45) NOT NULL COMMENT 'A személy címe.',
  PRIMARY KEY (`person_id`))
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8
COMMENT = 'Az emberekről tárol információkat.';


-- -----------------------------------------------------
-- Table `phonebook`.`phonenumber`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `phonebook`.`phonenumber` (
  `phonenumber_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'A telefonszám azonosítója.',
  `person_id` INT(11) NOT NULL COMMENT 'A személy azonosítója',
  `phone` VARCHAR(45) NOT NULL COMMENT 'A telefonszám',
  PRIMARY KEY (`phonenumber_id`),
  INDEX `fk_phonenumber_person_idx` (`person_id` ASC),
  CONSTRAINT `fk_phonenumber_person`
    FOREIGN KEY (`person_id`)
    REFERENCES `phonebook`.`person` (`person_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Az egyes személyekhez tartozó telefonszámokat tárolja.';


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
