CREATE DATABASE IF NOT EXISTS notebook CHARACTER SET utf8 COLLATE utf8_general_ci;
 
USE notebook;
 
CREATE TABLE IF NOT EXISTS contacts (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    surname  VARCHAR(100) NOT NULL,
    name     VARCHAR(100) NOT NULL,
    lastname VARCHAR(100),
    gender   VARCHAR(10),
    date     DATE,
    phone    VARCHAR(30),
    location VARCHAR(255),
    email    VARCHAR(100),
    comment  TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 