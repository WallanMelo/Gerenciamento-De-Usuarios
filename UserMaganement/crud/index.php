<?php
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);

# Se for p API, delega para o Slim
if (strpos($path, '/api') === 0) {
    require __DIR__ . '/api/index.php';
    exit;
}

# Mapeamento de Rotas do Frontend
$frontend_files = [
    '/' => 'Frontend/index.html',
    '/index.html' => 'Frontend/index.html',
    '/ToCreate.html' => 'Frontend/ToCreate.html',
    '/ToEdit.html' => 'Frontend/ToEdit.html',
];

if (isset($frontend_files[$path])) {
    $file = __DIR__ . '/' . $frontend_files[$path];
    if (file_exists($file)) {
        header('Content-Type: text/html');
        readfile($file);
        exit;
    }
}

# Lógica para servir os arquivos estáticos (CSS, Imagens, JS), caso necessário
$static_file = __DIR__ . '/Frontend' . $path; 

if (file_exists($static_file) && is_file($static_file)) {
    $ext = pathinfo($static_file, PATHINFO_EXTENSION);
    $mimes = [
        'css'  => 'text/css',
        'js'   => 'application/javascript',
        'png'  => 'image/png',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'svg'  => 'image/svg+xml'
    ];
    
    if (isset($mimes[$ext])) {
        header('Content-Type: ' . $mimes[$ext]);
    }
    
    readfile($static_file);
    exit;
}

# Fallback Erro - 404
header("HTTP/1.0 404 Not Found");
echo "<h1>404 - Recurso não encontrado</h1>";
echo "<p>O caminho <code>" . htmlspecialchars($path) . "</code> não existe.</p>";