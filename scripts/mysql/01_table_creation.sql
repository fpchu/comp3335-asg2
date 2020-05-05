CREATE TABLE IF NOT EXISTS development.accounts (
	id			INT(10)		AUTO_INCREMENT,
	first_name	VARCHAR(255)	NOT NULL,
	last_name	VARCHAR(255)	NOT NULL,
	username	VARCHAR(255)	NOT NULL,
	pass		VARCHAR(255)	NOT NULL,
	salt		VARBINARY(16)	NOT NULL,
	PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS development.notes (
	id			INT		AUTO_INCREMENT,
	title		TEXT	NOT NULL,
	content		LONGTEXT,
	isencrypt	TINYINT NOT NULL,
	creation_date	DATETIME NOT NULL,
	uid				INT	NOT NULL,
	initial_vector	VARBINARY(16),
	FOREIGN KEY (uid) REFERENCES accounts (id) ON DELETE CASCADE,
	PRIMARY KEY (id)
);