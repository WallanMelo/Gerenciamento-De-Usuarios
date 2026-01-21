# Sistema De Gerenciamento de Usuários - Aplicação Web

Este projeto é uma aplicação web completa (Fullstack) para o gerenciamento de usuários, permitindo listar, cadastrar, editar e excluir registros (CRUD).

## 🛠️ Tecnologias Utilizadas

* **Frontend:** HTML5, CSS3 , JavaScript e jQuery.
* **Backend:** PHP com **Slim Framework** (Seguindo a Arquitetura de API REST).
* **Banco de Dados:** PostgreSQL.
* **Infraestrutura:** Docker e Docker Compose.

## Funcionalidades

- [x] **Listagem:** Visualização de todos os usuários cadastrados com contador dinâmico.
- [x] **Cadastro:** Inclusão de novos usuários com validação de e-mail e bloqueio de e-mails duplicados.
- [x] **Edição:** Atualização de dados existentes.
- [x] **Exclusão:** Remoção de registros.

## Como Rodar o Projeto

Para executar esta aplicação, você só precisa ter o **Docker** e o **Docker Compose** instalados em sua máquina.

1.  **Clone o repositório:**
    ```bash
    git clone https://github.com/WallanMelo/Gerenciamento-De-Usuarios
    cd "Gerenciamento-De-Usuarios"
    ```

2.  **Inicie os containers:**
    ```bash
    docker-compose up -d --build
    ```

3.  **Acesse no navegador:**
    A aplicação estará disponível em: [http://localhost:8000](http://localhost:8000)

> **Nota:** O banco de dados é inicializado automaticamente com a tabela necessária através do script `data.sql` localizado em `./bd/`.

## 📁 Estrutura do Projeto

* `/crud`: Contém o servidor web e o roteador PHP, e os Arquivos estáticos (Imagens, Logo, Favicon).
* `/crud/Frontend`: Arquivos de interface (HTML/CSS).
* `/crud/api`: Lógica da API Slim Framework.
* `/bd`: Script de inicialização do PostgreSQL.

## 👤 Autor

* [Wallan De Melo Lima][https://github.com/WallanMelo]
