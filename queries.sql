CREATE TABLE users (
    id int AUTO_INCREMENT,
    name varchar(100) NOT NULL,
    email varchar(255) NOT NULL,
    password varchar(255),
    PRIMARY KEY (id),
    UNIQUE (email)
);

CREATE TABLE categories (
    id int AUTO_INCREMENT,
    name varchar(100) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE hashtags (
    id int AUTO_INCREMENT,
    name varchar(100) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE books (
    id int AUTO_INCREMENT,
    name varchar(100) NOT NULL,
    description text NULL,
    category_id int NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE book_hashtag (
    book_id int NOT NULL,
    hashtag_id int NOT NULL,
    FOREIGN KEY (book_id) REFERENCES books(id),
    FOREIGN KEY (hashtag_id) REFERENCES hashtags(id),
    UNIQUE (book_id, hashtag_id)
);
