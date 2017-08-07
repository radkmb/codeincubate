CREATE TABLE test_drink_master (
  drink_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  drink_name VARCHAR(100) NOT NULL,
  price INT(11) NOT NULL,
  img VARCHAR(100) NOT NULL,
  create_datetime timestamp default current_timestamp,
  update_datetime timestamp
);
