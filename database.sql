CREATE DATABASE IF NOT EXISTS plataforma;
USE plataforma;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  senha VARCHAR(255) NOT NULL,
  tipo ENUM('aluno','professor') NOT NULL
);

CREATE TABLE IF NOT EXISTS cursos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(255) NOT NULL,
  descricao TEXT,
  status ENUM('ativo','inativo') DEFAULT 'ativo',
  professor_id INT,
  FOREIGN KEY (professor_id) REFERENCES users(id)
);