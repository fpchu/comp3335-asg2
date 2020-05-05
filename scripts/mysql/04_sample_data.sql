-- Create user
set @salt = RANDOM_BYTES(16);
INSERT INTO development.accounts 
(first_name, last_name, username, pass, salt)
VALUES 
('Chi Wai', 'Tai', 'cwtai', SHA2(CONCAT('P@ssw0rd', @salt), 256), @salt);

set @salt = RANDOM_BYTES(16);
INSERT INTO development.accounts 
(first_name, last_name, username, pass, salt)
VALUES 
('Chi Keung', 'Siu', 'cksiu', SHA2(CONCAT('P@ssw0rd', @salt), 256), @salt);

set @salt = RANDOM_BYTES(16);
INSERT INTO development.accounts 
(first_name, last_name, username, pass, salt)
VALUES 
('Yuen Sam', 'Lam', 'yslam', SHA2(CONCAT('P@ssw0rd', @salt), 256), @salt);

-- Create Note
SET @title = 'Today is a good day 1';
SET @content = 'The weather is very good today.';
INSERT INTO development.notes 
(title, content, isencrypt, creation_date, uid, initial_vector)
VALUES (@title, @content, 0, '2020-04-17 07:46:31', 1, NULL);

SET @title = 'Today is a good day 2';
SET @content = 'Mom and dad takes me to the theme park.';
SET @key_str = SHA2('My secret passphrase', 512);
SET @init_vector = RANDOM_BYTES(16);
SET @crypt_str = AES_ENCRYPT(@content ,@key_str, @init_vector);
INSERT INTO development.notes 
(title, content, isencrypt, creation_date, uid, initial_vector)
VALUES (@title, TO_BASE64(@crypt_str), 1, '2020-04-17 07:47:31', 1, @init_vector);

SET @title = 'Today is a good day 3';
SET @content = 'I eat ice-cream with my family.';
INSERT INTO development.notes 
(title, content, isencrypt, creation_date, uid, initial_vector)
VALUES (@title, @content, 0, '2020-04-17 07:48:31', 1,  NULL);

SET @title = 'Today is a good day 1';
SET @content = 'The weather is very good today';
SET @key_str = SHA2('My secret passphrase',512);
SET @init_vector = RANDOM_BYTES(16);
SET @crypt_str = AES_ENCRYPT(@content ,@key_str, @init_vector);
INSERT INTO development.notes 
(title, content, isencrypt, creation_date, uid, initial_vector)
VALUES (@title, TO_BASE64(@crypt_str), 1, '2020-04-17 07:49:31', 2, @init_vector);

SET @title = 'Today is a good day 2';
SET @content = 'The weather is very good today';
INSERT INTO development.notes 
(title, content, isencrypt, creation_date, uid, initial_vector)
VALUES (@title, @content, 0, '2020-04-17 07:46:31', 2, NULL);

SET @title = 'Today is a good day 3';
SET @content = 'The weather is very good today';
INSERT INTO development.notes 
(title, content, isencrypt, creation_date, uid, initial_vector)
VALUES (@title, @content, 0, '2020-04-17 07:50:31', 2, NULL);

SET @title = 'Today is a bad day 1';
SET @content = 'The weather is very good today';
SET @key_str = SHA2('My secret passphrase', 512);
SET @init_vector = RANDOM_BYTES(16);
SET @crypt_str = AES_ENCRYPT(@content ,@key_str, @init_vector);
INSERT INTO development.notes 
(title, content, isencrypt, creation_date, uid, initial_vector)
VALUES (@title, TO_BASE64(@crypt_str), 1, '2020-04-17 07:51:31', 2, @init_vector);

SET @title = 'mixed integer programming is awesome';
SET @content = 'integer programming problem is a mathematical optimization or feasibility program in which some or all of the variables are restricted to be integers';
SET @key_str = SHA2('My secret passphrase', 512);
SET @init_vector = RANDOM_BYTES(16);
SET @crypt_str = AES_ENCRYPT(@content ,@key_str, @init_vector);
INSERT INTO development.notes 
(title, content, isencrypt, creation_date, uid, initial_vector)
VALUES (@title, TO_BASE64(@crypt_str), 1, '2020-04-17 05:46:31', 3, @init_vector);

SET @title = 'linear programming is difficut';
SET @content = 'Linear programming is an optimization technique for a system of linear constraints and a linear objective function';
SET @key_str = SHA2('My secret passphrase', 512);
SET @init_vector = RANDOM_BYTES(16);
SET @crypt_str = AES_ENCRYPT(@content ,@key_str, @init_vector);
INSERT INTO development.notes 
(title, content, isencrypt, creation_date, uid, initial_vector)
VALUES (@title, TO_BASE64(@crypt_str), 1, '2020-04-17 06:46:31', 3, @init_vector);

SET @title = 'Genetic algorithm';
SET @content = 'Genetic algorithm is a natural-inspired optimization alogrithm';
INSERT INTO development.notes 
(title, content, isencrypt, creation_date, uid, initial_vector)
VALUES (@title, @content, 0, '2020-04-17 07:46:31', 3, NULL);

SET @title = 'Haskell';
SET @content = 'Haskell is a pure functional programming language';
INSERT INTO development.notes 
(title, content, isencrypt, creation_date, uid, initial_vector)
VALUES (@title, @content, 0, '2020-04-17 07:46:31', 3, NULL);