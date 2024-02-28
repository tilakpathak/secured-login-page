CREATE TABLE users (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    code VARCHAR(50) NOT NULL
);



CREATE TABLE sessions (
  session_id INT(11) NOT NULL AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL,
  session_token VARCHAR(255) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY (session_id)
);