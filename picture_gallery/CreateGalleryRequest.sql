USE picture_gallery;
/*CREATE TABLE users (
	username VARCHAR(20),
	passw VARCHAR(12),
	email VARCHAR(30),
	PRIMARY KEY (username)
);

CREATE TABLE comments (
	comment_id INTEGER UNSIGNED AUTO_INCREMENT,
	posted_by VARCHAR(20),
	email VARCHAR(30),
	created_at DATETIME,
	content VARCHAR(250),
	approval BOOLEAN, 
	PRIMARY KEY (comment_id),
	FOREIGN KEY (posted_by) REFERENCES users (username)
);*/

DESCRIBE pictures;
DESCRIBE galleries;
DESCRIBE users;
DESCRIBE comments;
SELECT * FROM galleries;
SELECT * FROM pictures;
SELECT * FROM users;
SELECT * FROM comments;

/*INSERT INTO users (username, passw, email, admin_flag) 
VALUES ('Bipki', 'qwerty', 'fakemail@mail.ru', '0');*/

/*INSERT INTO comments (comment_id, posted_by, email, created_at, content, approval, image_id ) 
VALUES ( NULL, '1', 'fakemail@mail.ru', NOW(), 'FUCK THIS SHIT.', '1', '8');*/