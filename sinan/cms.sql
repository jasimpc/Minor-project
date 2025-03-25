-- Create the database
CREATE DATABASE login;

-- Use the created database
USE login;

-- Create the logins table
CREATE TABLE logins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(255) NOT NULL,
    lastName VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Create the posts table
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    image VARCHAR(255),
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create the comments table
CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    comment TEXT NOT NULL,
    create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id),
    FOREIGN KEY (user_id) REFERENCES logins(id)
);

-- Create the votes table
CREATE TABLE votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    vote INT NOT NULL,
    FOREIGN KEY (post_id) REFERENCES posts(id),
    FOREIGN KEY (user_id) REFERENCES logins(id)
);

-- Create the comment_votes table
CREATE TABLE comment_votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    comment_id INT NOT NULL,
    user_id INT NOT NULL,
    vote INT NOT NULL,
    FOREIGN KEY (comment_id) REFERENCES comments(id),
    FOREIGN KEY (user_id) REFERENCES logins(id)
);

-- Create the categories table
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(250) NOT NULL,
    description LONGTEXT,
    created_by INT NOT NULL,
    image_path VARCHAR(250),
    created_at TIMESTAMP(6) DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES logins(id)
);
Explanation:
Database Creation:

The script starts by creating the login database and then switches to using it.
Tables Creation:

logins: Stores user information.
posts: Stores blog posts.
comments: Stores comments on posts.
votes: Stores votes (likes/dislikes) on posts.
comment_votes: Stores votes (likes/dislikes) on comments.
categories: Stores categories for organizing posts.
Foreign Keys:

Foreign keys are used to establish relationships between tables, ensuring referential integrity.
