

CREATE TABLE users(
    id INT NOT NULL AUTO_INCREMENT,
    username varchar(255),
    password varchar(255),
    email varchar(100),
    date datetime,
    image varchar(1024),

    PRIMARY KEY (id),
    key (username),
    key (email),
    key (date)
);

CREATE TABLE posts(
    id INT NOT NULL AUTO_INCREMENT,
    user_id int,
    post text,
    image varchar(1024),
    date datetime,

    PRIMARY KEY (id),
    key (user_id),
    key (date)
);


CREATE TABLE replies (
    id INT NOT NULL AUTO_INCREMENT,
    post_id INT,
    user_id INT,
    reply VARCHAR(1024),
    date DATETIME,

    PRIMARY KEY (id),
    FOREIGN KEY (post_id) REFERENCES posts(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);