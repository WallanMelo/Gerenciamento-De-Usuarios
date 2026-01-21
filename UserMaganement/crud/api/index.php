<?php

use Slim\Factory\AppFactory;

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../src/bd.php';

# Inicializa o framework Slim e define o prefixo das rotas como /api
$app = AppFactory::create();
$app->setBasePath('/api');
$app->addBodyParsingMiddleware(); #Middleware p/ o PHP ler os dados q são enviados em formato JSON

# ======= Funcction toList =======
$app->get('/users', function ($req, $res) use ($pdo) {
    #Faz uma busca de tds os usuarios por ID
    $stmt = $pdo->query("SELECT id, name as nome, email, telefone FROM users ORDER BY id");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    #mostra o resultado em formato json
    $res->getBody()->write(json_encode($users));
    return $res->withHeader('Content-Type', 'application/json');
});

# ======= Function ToCreate =======
$app->post('/users', function ($req, $res) use ($pdo) {
    $data = $req->getParsedBody(); #pega os dadis q são enviados pelo forms
    $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);#validação  d0 email

    #Verifica se o emaiil é valido
    if (!$email) {
        $res->getBody()->write(json_encode(['message' => 'E-mail inválido!']));
        return $res->withStatus(400)->withHeader('Content-Type', 'application/json');
    }

    #verificar se o email informado já n existe no bd p evitar duplicação
    $check = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $check->execute(['email' => $data['email']]);
    if ($check->fetch()) {
        $res->getBody()->write(json_encode(['message' => 'Este e-mail já está cadastrado!']));
        return $res->withStatus(409)->withHeader('Content-Type', 'application/json');
    }

    #inseri os dados informados no bd e dá um return dos dados q foram criados
    $stmt = $pdo->prepare("INSERT INTO users (name, email, telefone) VALUES (:name, :email, :telefone) RETURNING id, name as nome, email, telefone");
    $stmt->execute([
        'name' => $data['nome'],
        'email' => $data['email'],
        'telefone' => $data['telefone']
    ]);
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $res->getBody()->write(json_encode(['message' => 'Usuário criado com sucesso', 'user' => $user]));
    return $res->withStatus(201)->withHeader('Content-Type', 'application/json');
});

# ======= Function ToEdit ======= 
$app->get('/users/{id}', function ($req, $res, $args) use ($pdo) {
    #busca o usuaro pelo ID informado via url
    $stmt = $pdo->prepare(
        "SELECT id, name as nome, email, telefone FROM users WHERE id = :id"
    );
    $stmt->execute(['id' => $args['id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    #se n achar, return o erro 404
    if (!$user) {
        return $res->withStatus(404)->write('Usuário não encontrado');
    }
    
    $res->getBody()->write(json_encode($user));
    return $res->withHeader('Content-Type', 'application/json');
});

# ======= Function ToUpdate =======
$app->put('/users/{id}', function ($req, $res, $args) use ($pdo) {
    $data = $req->getParsedBody();
    $id = (int)$args['id']; # irá ganrantir q o ID seja um num INTEIRO

    #verifica e valida se os campos estão preenchidos ou não
    if (empty($data['email']) || empty($data['nome'])) {
        $res->getBody()->write(json_encode(['message' => 'Campos obrigatórios ausentes!']));
        return $res->withStatus(400)->withHeader('Content-Type', 'application/json');
    }

    //verifica se o formato do email inserido é valido
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $res->getBody()->write(json_encode(['message' => 'Formato de e-mail inválido!']));
        return $res->withStatus(400)->withHeader('Content-Type', 'application/json');
    }

    # fazemos uma busca se existe alguém no BD  com esse e-mail
    $sqlCheck = "SELECT id FROM users WHERE email = :email AND id != :id LIMIT 1";
    $stmtCheck = $pdo->prepare($sqlCheck);
    $stmtCheck->execute([
        'email' => $data['email'],
        'id'    => $id
    ]);
    
    if ($stmtCheck->fetch()) {
        #se  o retior for true ele bloqueia o update
        $res->getBody()->write(json_encode(['message' => 'Este e-mail já pertence a outro usuário cadastrado!']));
        return $res->withStatus(409)->withHeader('Content-Type', 'application/json');
    }

    #se o update for permitido aí prossegue
    $stmt = $pdo->prepare("UPDATE users SET name = :name, email = :email, telefone = :telefone WHERE id = :id");
    $status = $stmt->execute([
        'name'     => $data['nome'],
        'email'    => $data['email'],
        'telefone' => $data['telefone'],
        'id'       => $id
    ]);

    $res->getBody()->write(json_encode(['message' => 'Usuário atualizado com sucesso!']));
    return $res->withHeader('Content-Type', 'application/json');
});

# ======= Function ToDelete =======
$app->delete('/users/{id}', function ($req, $res, $args) use ($pdo) {
    # deleta o registro do BD e retorna o ID p verifica se a exclusão foi feita
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id RETURNING id");
    $stmt->execute(['id' => $args['id']]);
    $deleted = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$deleted) {
        return $res->withStatus(404)->write('Usuário não encontrado');
    }
    
    return $res->withStatus(200)->write(json_encode([
        'message' => 'Usuário excluído com sucesso'
    ]));
});


#roda a aplicação
$app->run();