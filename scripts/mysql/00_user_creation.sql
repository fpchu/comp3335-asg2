-- Disable root access other than localhost.
DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');
FLUSH PRIVILEGES;

-- Create two database, each for their own purpose.
CREATE DATABASE IF NOT EXISTS development;
CREATE DATABASE IF NOT EXISTS production;

-- Create a dev_admin role who can do all the job in the development db.
CREATE ROLE 'dev_admin';
GRANT ALL ON development.* TO 'dev_admin';

-- Create a developer role who can only do the "Establishment job".
CREATE ROLE 'developer';
GRANT CREATE, DELETE, INSERT, UPDATE, SELECT ON development.* TO 'developer';

-- CREATE opeator role who can only do the Establishement job on prod db. 
CREATE ROLE 'operator';
GRANT CREATE, DELETE, INSERT, UPDATE, SELECT ON production.* TO 'operator';

CREATE USER IF NOT EXISTS 'dev_admin'@'%' IDENTIFIED BY 'P@ssw0rd';
CREATE USER IF NOT EXISTS 'web_developer'@'%' IDENTIFIED BY 'P@ssw0rd';
CREATE USER IF NOT EXISTS 'prod_operator'@'%' IDENTIFIED BY 'P@ssw0rd';

GRANT 'dev_admin' TO 'dev_admin'@'%';
GRANT 'developer' TO 'web_developer'@'%';
GRANT 'operator' TO 'prod_operator'@'%';

SET DEFAULT ROLE ALL TO 'dev_admin'@'%';
SET DEFAULT ROLE ALL TO 'web_developer'@'%';
SET DEFAULT ROLE ALL TO 'prod_operator'@'%';
