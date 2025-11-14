<?php
require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/functions.php';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $sql = buildInsertSQL($TABLE, $COLUMNS);
  $stmt = $pdo->prepare($sql);
  $bind = [];
  foreach ($COLUMNS as $c) {
    $bind[':' . $c['name']] = $_POST[$c['name']] ?? null;
  }
  try {
    $stmt->execute($bind);
    header('Location: index.php');
    exit;
  } catch (Throwable $e) {
    $errors[] = $e->getMessage();
  }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Novo Registro</title>
  
  <link rel="stylesheet" href="assets/style.css" />
  <link rel="stylesheet" href="assets/formsStyle.css" />
  <link rel="stylesheet" href="assets/createStyle.css" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>

<body>
  <div class="container">
    
    <header class="cabecalho-principal" style="background-color: var(--amarelo-acao);">
      <img src="assets/leaf-svgrepo-com.svg" alt="Ícone de Registro" class="icone-titulo" />
      <h1>Novo Registro de Coleta Seletiva</h1>
    </header>

    <?php if ($errors) : ?>
      <div class="errors">
        <strong>Erro:</strong> <?php echo h(implode(' | ', $errors)); ?>
      </div>
    <?php endif; ?>

    <form class="formulario-cadastro" method="post">
      
      <?php foreach ($COLUMNS as $c) : ?>
        <div class="campo-form">
          <label for="<?php echo h($c['name']); ?>"><?php echo h($c['label']); ?>:</label>
          <input 
            type="<?php echo h($c['type'] ?? 'text'); ?>" 
            id="<?php echo h($c['name']); ?>" 
            name="<?php echo h($c['name']); ?>" 
            step="<?php echo h($c['step'] ?? ''); ?>" 
            required>
        </div>
      <?php endforeach; ?>

      <div class="acoes-form">
        <button type="submit" class="button create">Criar</button>
        <a href="index.php" class="button back">Voltar</a>
      </div>
      
    </form>
  </div>
</body>
</html>