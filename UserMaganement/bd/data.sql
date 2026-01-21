-- Dados do usuário:
-- * Nome
-- * E-mail
-- * Telefone

-- ========= TABELA DE USUÁRIOS =========
Create Table users(
    id serial primary key,          -- ID auto-incremento e Chave primária
    name VARCHAR(80) not null,       -- Nome é obrigatório
    email VARCHAR(150) unique not null, -- E-mail é único oq impede duplicação e é obrigatório
    telefone VARCHAR(20) not null    -- Telefone é obrigatório
);