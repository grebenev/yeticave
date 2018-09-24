CREATE DATABASE yeticave
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;
USE yeticave;


CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name CHAR(64)
);

CREATE TABLE lots (
  id INT AUTO_INCREMENT PRIMARY KEY,
  creation_date DATETIME,
  name CHAR(128),
  description TEXT,
  image CHAR(128),
  start_price INT(16),
  end_date DATE,
  lot_step CHAR(128),
  users_id INT(8),
  winners_id INT(8),
  categories_id INT(8)
);
CREATE INDEX search_lot ON lots(name);

CREATE TABLE bet (
  id INT AUTO_INCREMENT PRIMARY KEY,
  bet_date DATETIME,
  amount INT(16),
  users_id INT(8),
  lots_id INT(8)
);

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  registration_date DATETIME,
  email CHAR(64),
  name CHAR(128),
  password CHAR(64),
  avatar CHAR(128),
  contacts TEXT
);
