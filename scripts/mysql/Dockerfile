FROM mysql:8.0
ENV MYSQL_ROOT_PASSWORD P@ssw0rd
COPY *.sql /docker-entrypoint-initdb.d/

ENTRYPOINT ["./entrypoint.sh", "--default-authentication-plugin=mysql_native_password", "--block-encryption-mode=aes-256-cbc"]
