CREATE TABLE test_drink_stock (
  drink_id INT(11) NOT NULL PRIMARY KEY,
  stock INT(11) NOT NULL,
  create_datetime timestamp default current_timestamp,
  update_datetime timestamp
);
