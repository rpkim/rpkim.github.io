# MySQL-Front Dump 2.5
#
# Host: 192.168.0.11   Database: icorp_master
# --------------------------------------------------------
# Server version 3.23.36


#
# Table structure for table 'users'
#

CREATE TABLE users (
  iduser int(11) NOT NULL auto_increment,
  login varchar(16) default NULL,
  senha varchar(16) default NULL,
  email varchar(160) default NULL,
  nome varchar(160) default NULL,
  fone varchar(16) default NULL,
  celular varchar(16) default NULL,
  admin tinyint(1) default NULL,
  grupo varchar(30) NOT NULL default '',
  dataace varchar(22) NOT NULL default '0000-00-00 00:00:00',
  endereco varchar(120) NOT NULL default '',
  ramal varchar(6) NOT NULL default '',
  fonecasa varchar(16) NOT NULL default '',
  valorhora double(4,2) NOT NULL default '0.00',
  horasc double(4,2) NOT NULL default '0.00',
  alimentacao double(4,2) NOT NULL default '0.00',
  transporte double(4,2) NOT NULL default '0.00',
  ativo int(1) NOT NULL default '0',
  PRIMARY KEY  (iduser),
  UNIQUE KEY iduser (iduser)
) TYPE=ISAM PACK_KEYS=1;



#
# Dumping data for table 'users'
#

INSERT INTO users VALUES("2", "jorge", "oxe", "jorge@icorp.com.br", "Fernando Jorge", "439-9148", "9292-2664", "0", "Produção", "0000-00-00 00:00:00", "", "", "", "0.00", "0.00", "0.00", "0.00", "0");
INSERT INTO users VALUES("5", "itamar", "itamac", "itamar@icorp.com.br", "Itamar Medeiros", "3247-1959", "9987-7082", "0", "Produção", "31/08/2001 17:30:55", "", "211", "3469-2168", "4.50", "8.00", "5.00", "3.00", "0");
INSERT INTO users VALUES("6", "eduardo", "fUror", "eduardo@icorp.com.br", "Eduardo Fernandes", "3247-1959", "92629666", "0", "Gerente de Projeto", "21/05/2002 18:07:36", "", "213", "3427-6943", "5.00", "8.00", "5.00", "2.00", "0");
INSERT INTO users VALUES("7", "daniel", "110981", "daniel@icorp.com.br", "Daniel Almeida", "3427-1359", "9911-4386", "0", "Produção", "30/08/2001 15:20:00", "Rua Jose Mariano 355/102 - Jardim Atlantico - Olinda", "217", "", "3.50", "4.00", "0.00", "2.60", "0");
INSERT INTO users VALUES("17", "raquel", "almeida", "raquel@icorp.com.br", "Raquel Almeida", "3427-1092", "9292-6782", "0", "Gerente de Projeto", "31/08/2001 18:06:49", "emidio carvalheira 82", "217", "3251-0829", "3.00", "4.00", "5.00", "2.00", "0");
INSERT INTO users VALUES("18", "ubertino", "rosso", "ubertino@icorp.com.br", "Riccardo Rosso", "3427-1092", "", "0", "Produção", "10/07/2001 15:24:37", "", "215", "3466-3097", "3.00", "4.00", "0.00", "2.00", "0");
INSERT INTO users VALUES("20", "berardo", "ihfirma", "berardo@icorp.com.br", "José Berardo", "3427-1092", "92626071", NULL, "Produção", "05/09/2001 18:43:42", "Av. Beira Mar nº 630", "217", "33610027", "4.50", "8.00", "5.00", "2.00", "0");