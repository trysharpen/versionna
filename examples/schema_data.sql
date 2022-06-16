# SQLITE

CREATE TABLE IF NOT EXISTS users (
    id INTEGER,
    name TEXT NOT NULL,
    username TEXT NOT NULL,
    biography TEXT,
    birthdate TEXT,
    PRIMARY KEY (id),
    UNIQUE (username)
);

CREATE INDEX IF NOT EXISTS users_index_birthdate ON users (birthdate);

INSERT INTO users (id, name, username, biography, birthdate)
VALUES (1, 'John Doe', 'jdoe', '', '1980-01-01'), (2, 'Jane Doe', 'jdoe2', '', '1980-01-01'),
        (3, 'John Smith', 'jsmith', '', '1980-01-01'), (4, 'Jane Smith', 'jsmith2', '', '1980-01-01'),
        (5, 'John Smith', 'jsmith3', '', '1980-01-01');


CREATE TABLE IF NOT EXISTS products (
    id INTEGER,
    seller_id INTEGER NOT NULL,
    name TEXT NOT NULL,
    description TEXT,
    price FLOAT,
    PRIMARY KEY (id),
    CONSTRAINT fk_products_seller_id_users_id FOREIGN KEY (seller_id) REFERENCES users (id)
);

INSERT INTO products (id, seller_id, name, description, price)
VALUES
    (1, 1, 'AMD Ryzen 5 2600', '', 299.99),
    (2, 1, 'Intel Core i7-7700K', '', 299.99),
    (3, 2, 'Raspberry Pi 3', '', 29.99),
    (4, 1, 'Raspberry Pi 4', '', 39.99);


# MYSQL
CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL UNIQUE,
    biography TEXT,
    birthdate DATETIME,
    PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS products (
    id INT UNSIGNED NOT NULL,
    seller_id INT UNSIGNED NOT NULL,
    name VARCHAR(500) NOT NULL,
    description TEXT,
    price FLOAT,
    PRIMARY KEY (id),
    CONSTRAINT fk_products_seller_id_users_id FOREIGN KEY (seller_id) REFERENCES users (id)
);

# POSTGRESQL
CREATE TABLE IF NOT EXISTS users (
    id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL,
    biography TEXT,
    birthdate TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE (username)
);

CREATE TABLE IF NOT EXISTS products (
    id INT NOT NULL,
    seller_id INT NOT NULL,
    name VARCHAR(500) NOT NULL,
    description TEXT,
    price FLOAT,
    PRIMARY KEY (id),
    CONSTRAINT fk_products_seller_id_users_id FOREIGN KEY (seller_id) REFERENCES users (id)
);
