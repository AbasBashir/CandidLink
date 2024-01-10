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
