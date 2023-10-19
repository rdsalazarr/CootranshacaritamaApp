ALTER TABLE `empresa` ADD `emprpersoneriajuridica` VARCHAR(50) NULL COMMENT 'Personería jurídica de la empresa' AFTER `emprcorreo`;
UPDATE `empresa` SET `emprpersoneriajuridica` = 'Personería Jurídica No. 73 Enero 28/1976' WHERE `empresa`.`emprid` = 1;
