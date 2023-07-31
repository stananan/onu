CREATE TABLE users (
    id BIGINT(20) NOT NULL AUTO_INCREMENT,
    username CHAR(20) COLLATE utf8mb4_unicode_ci NOT NULL,
    password CHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    is_admin TINYINT(1) NOT NULL DEFAULT 0,
    creation_time BIGINT(20) NOT NULL DEFAULT 0,
    last_login_time BIGINT(20) NOT NULL DEFAULT 0,
    room_id BIGINT(20),

    PRIMARY KEY (id),
    UNIQUE (username)
);

CREATE TABLE rooms (
    id BIGINT(20) NOT NULL AUTO_INCREMENT,
    creator_id BIGINT(20) NOT NULL,
    creation_time BIGINT(20) NOT NULL DEFAULT 0,
    header CHAR(20) COLLATE utf8mb4_unicode_ci NOT NULL,
    password CHAR(255) COLLATE utf8mb4_unicode_ci,

    PRIMARY KEY (id),
    FOREIGN KEY (creator_id) REFERENCES users(id)
);

CREATE TABLE messages (
    id BIGINT(20) NOT NULL AUTO_INCREMENT,
    room_id BIGINT(20) NOT NULL,
    user_id BIGINT(20) NOT NULL,
    content VARCHAR(1024) NOT NULL,
    creation_time BIGINT(20) NOT NULL DEFAULT 0,

    PRIMARY KEY (id),

    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (room_id) REFERENCES rooms(id)
);