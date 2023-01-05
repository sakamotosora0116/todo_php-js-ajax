create table todos (
    id int NOT NULL AUTO_INCREMENT,
    title varchar(255),
    content varchar(255),
    pos int,
    is_done boolean DEFAULT false,
    PRIMARY KEY (id),
    UNIQUE (pos)
)