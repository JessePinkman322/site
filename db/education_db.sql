-- Создание базы данных
CREATE DATABASE IF NOT EXISTS e_db;
USE e_db;

-- Таблица пользователей
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE
);

-- Таблица курсов
CREATE TABLE IF NOT EXISTS courses (
    course_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL
);

-- Таблица событий
CREATE TABLE IF NOT EXISTS events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    date DATE NOT NULL,
    description TEXT NOT NULL
);

-- Таблица контактов
CREATE TABLE IF NOT EXISTS contacts (
    contact_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    message TEXT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Таблица избранных курсов
CREATE TABLE IF NOT EXISTS favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    course_id INT,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (course_id) REFERENCES courses(course_id),
    UNIQUE (user_id, course_id)
);

-- Вставка тестовых данных
INSERT INTO courses (title, description, price) VALUES
('Веб-разработка', 'Основы HTML, CSS и JavaScript.', 5000.00),
('Python для начинающих', 'Введение в программирование на Python.', 4500.00);

INSERT INTO events (title, date, description) VALUES
('Вебинар по фронтенду', '2025-06-01', 'Узнайте о последних трендах в разработке интерфейсов.'),
('Мастер-класс по Python', '2025-07-15', 'Практическое занятие по созданию приложений на Python.');