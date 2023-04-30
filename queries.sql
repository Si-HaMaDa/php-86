CREATE TABLE users (
    id int AUTO_INCREMENT,
    name varchar(100) NOT NULL,
    email varchar(255) NOT NULL,
    password varchar(255),
    PRIMARY KEY (id),
    UNIQUE (email)
);
