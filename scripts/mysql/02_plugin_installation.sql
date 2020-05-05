-- Is a one time thing, no need to install and load it every time
-- information https://dev.mysql.com/doc/refman/8.0/en/validate-password-installation.html
-- SELECT PLUGIN_NAME, PLUGIN_STATUS FROM INFORMATION_SCHEMA.PLUGINS WHERE PLUGIN_NAME LIKE 'validate%';
INSTALL PLUGIN validate_password SONAME 'validate_password.so';