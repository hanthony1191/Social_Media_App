/**
	Schema for a Social Network
**/

CREATE TABLE IF NOT EXISTS final_user
(
user_id INT(11) NOT NULL AUTO_INCREMENT,
user_email VARCHAR(100) NOT NULL,
user_password VARCHAR(50) NOT NULL,
user_first VARCHAR(55) NOT NULL,
user_last VARCHAR(55) NOT NULL,
PRIMARY KEY (user_id)
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS final_type
(
type_id TINYINT(3) NOT NULL AUTO_INCREMENT,
type_name VARCHAR(25) NOT NULL,
PRIMARY KEY (type_id)
)ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS final_relationship
(
first_id INT(11) NOT NULL,
second_id INT(11) NOT NULL,
type_id TINYINT(3) NOT NULL,
PRIMARY KEY (first_id, second_id),
CONSTRAINT fk_relationship_first FOREIGN KEY (first_id) REFERENCES final_user(user_id)
ON UPDATE CASCADE,
CONSTRAINT fk_relationship_second FOREIGN KEY (second_id) REFERENCES final_user(user_id)
ON UPDATE CASCADE,
CONSTRAINT fk_relationship_type FOREIGN KEY (type_id) REFERENCES final_type(type_id)
ON UPDATE CASCADE
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS final_group
(
group_id INT(11) NOT NULL AUTO_INCREMENT,
group_name VARCHAR(50) NOT NULL,
user_id INT(11) NOT NULL,
PRIMARY KEY (group_id),
CONSTRAINT fk_group_user FOREIGN KEY (user_id) REFERENCES final_user(user_id)
ON DELETE CASCADE
ON UPDATE CASCADE
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS final_user_group
(
group_id INT(11) NOT NULL,
user_id INT(11) NOT NULL,
PRIMARY KEY (group_id, user_id),
CONSTRAINT fk_user_group_group FOREIGN KEY (group_id) REFERENCES final_group(group_id)
ON DELETE CASCADE
ON UPDATE CASCADE,
CONSTRAINT fk_user_group_user FOREIGN KEY (user_id) REFERENCES final_user(user_id)
ON DELETE CASCADE
ON UPDATE CASCADE
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS final_event
(
event_id INT(11) NOT NULL AUTO_INCREMENT,
event_name VARCHAR(50) NOT NULL,
event_location VARCHAR(50) NOT NULL,
event_date DATETIME NOT NULL,
user_id INT(11) NOT NULL,
PRIMARY KEY (event_id),
CONSTRAINT fk_event_user FOREIGN KEY (user_id) REFERENCES final_user(user_id)
ON DELETE CASCADE
ON UPDATE CASCADE
)ENGINE=InnoDB;

ALTER TABLE final_event
ADD group_id INT(11);

ALTER TABLE final_event 
  ADD CONSTRAINT fk_event_group 
  FOREIGN KEY (group_id) 
  REFERENCES final_group(group_id) 
  ON DELETE CASCADE
  ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS final_category
(
category_id TINYINT(3) NOT NULL AUTO_INCREMENT,
category_type VARCHAR(25) NOT NULL,
PRIMARY KEY (category_id)
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS final_response
(
response_id INT(11) NOT NULL AUTO_INCREMENT,
category_id TINYINT(3) NOT NULL,
event_id INT(11) NOT NULL,
user_id INT(11) NOT NULL,
PRIMARY KEY (response_id),
CONSTRAINT fk_response_category FOREIGN KEY (category_id) REFERENCES final_category(category_id)
ON DELETE CASCADE
ON UPDATE CASCADE,
CONSTRAINT fk_response_event FOREIGN KEY (event_id) REFERENCES final_event(event_id)
ON DELETE CASCADE
ON UPDATE CASCADE,
CONSTRAINT fk_response_user FOREIGN KEY (user_id) REFERENCES final_user(user_id)
ON DELETE CASCADE
ON UPDATE CASCADE
)ENGINE=InnoDB;