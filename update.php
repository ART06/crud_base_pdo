<?php
require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/functions.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
  header('Location:index.php');
  exit;
}
$cols = implode(',', array_map(fn ($c) => $c['name'], $COLUMNS));
$stmt = $pdo->prepare("SELECT id,{$cols} FROM {$TABLE} WHERE id=:id");
$stmt->execute([':id' => $id]);
$cur = $stmt->fetch();
if (!$cur) {
  header('Location:index.php');
  exit;
}
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  foreach ($COLUMNS as $c) {
    if (empty($_POST[$c['name']])) {
      $errors[] = "O campo '{$c['label']}' é obrigatório.";
    }
  }
  if (empty($errors)) {
    $sql = buildUpdateSQL($TABLE, $COLUMNS);
    $stmt = $pdo->prepare($sql);
    $bind = [':id' => $id];
    foreach ($COLUMNS as $c) {
      $bind[':' . $c['name']] = $_POST[$c['name']] ?? null;
    }
    try {
      $stmt->execute($bind);
      header('Location:index.php');
      exit;
    } catch (Throwable $e) {
      $errors[] = $e->getMessage();
    }
  }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Registro</title>
  
  <link rel="stylesheet" href="assets/style.css" />
  <link rel="stylesheet" href="assets/formsStyle.css" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  
  <script src="assets/script.js" defer></script>
</head>

<body>
  <div class="container">

    <header class="cabecalho-principal">
      <img src="assets/leaf-svgrepo-com.svg" alt="Ícone de Edição" class="icone-titulo" />
      <h1>Editar Registro de Coleta Seletiva (ID: <?php echo h($id); ?>)</h1>
    </header>
    
    <?php if ($errors) : ?>
      <div class="errors">
        <strong>Erro:</strong> <?php echo h(implode(' | ', $errors)); ?>
      </div>
    <?php endif; ?>

    <div id="js-error-message" class="errors" style="display:none; background-color: #fff0f0; border-color: #e74c3c; color: #c0392b;"></div>

    <form class="formulario-cadastro" method="post" onsubmit="return validateForm(event)">
      
      <?php foreach ($COLUMNS as $c) : ?>
        <div class="campo-form">
          <label for="<?php echo h($c['name']); ?>"><?php echo h($c['label']); ?>:</label>
          <input 
            type="<?php echo h($c['type'] ?? 'text'); ?>" 
            id="<?php echo h($c['name']); ?>" 
            name="<?php echo h($c['name']); ?>" 
            step="<?php echo h($c['step'] ?? ''); ?>" 
            value="<?php echo h($cur[$c['name']]); ?>"
            >
        </div>
      <?php endforeach; ?>

      <div class="acoes-form">
        <button type="submit" class="button save">Salvar Alterações</button>
        <a href="index.php" class="button cancel">Cancelar</a>
      </div>
      
    </form>
  </div>
</body>
</html>