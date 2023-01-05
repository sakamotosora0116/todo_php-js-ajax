create table todos (
    id int NOT NULL AUTO_INCREMENT,
    title varchar(255),
    pos int,
    is_done boolean,
    PRIMARY KEY (id),
    UNIQUE (pos)
)