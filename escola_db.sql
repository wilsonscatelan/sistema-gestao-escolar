
CREATE TYPE user_role AS ENUM ('adm', 'professor', 'aluno');

CREATE TABLE professores (
    id_professor SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    telefone VARCHAR(20) NULL,
    foto_professor VARCHAR(255) NULL
);

CREATE TABLE turmas (
    id_turma SERIAL PRIMARY KEY,
    nome_turma VARCHAR(100) NOT NULL,
    ano INT NOT NULL,
    id_professor_fk INT NULL REFERENCES professores(id_professor) ON DELETE SET NULL,
    imagem_turma VARCHAR(255) NULL,
    descricao TEXT NULL,
    carga_horaria INT NULL
);

CREATE TABLE alunos (
    id_aluno SERIAL PRIMARY KEY,
    nome_aluno VARCHAR(100) NOT NULL,
    data_nascimento DATE,
    caminho_foto VARCHAR(255) NULL,
    cpf VARCHAR(14) NULL UNIQUE,
    telefone VARCHAR(20) NULL
);

CREATE TABLE matriculas (
    id_matricula SERIAL PRIMARY KEY,
    id_aluno_fk INT NOT NULL REFERENCES alunos(id_aluno) ON DELETE CASCADE,
    id_turma_fk INT NOT NULL REFERENCES turmas(id_turma) ON DELETE CASCADE,
    data_matricula DATE DEFAULT CURRENT_DATE
);

CREATE TABLE notas (
    id_nota SERIAL PRIMARY KEY,
    id_matricula_fk INT NOT NULL REFERENCES matriculas(id_matricula) ON DELETE CASCADE,
    descricao_avaliacao VARCHAR(100) NOT NULL,
    nota DECIMAL(5, 2) NOT NULL,
    data_avaliacao DATE DEFAULT CURRENT_DATE
);

CREATE TABLE usuarios (
    id_usuario SERIAL PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    tipo_usuario user_role NOT NULL,
    id_referencia INT NULL
);

ALTER TABLE matriculas
ADD COLUMN documento VARCHAR(255) NULL;

CREATE INDEX idx_matriculas_turma_aluno ON matriculas (id_turma_fk, id_aluno_fk);

INSERT INTO usuarios (email, senha, tipo_usuario) VALUES
('adm@escola.com', '$2y$10$3g.PA.o.1Sg5.dDUg1a0a.hi4a3.mN4q5QJ2n.2jtF6d5pr.3pSmu', 'adm');
