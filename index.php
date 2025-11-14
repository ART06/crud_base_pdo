<?php
require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/functions.php';

$search = $_GET['q'] ?? '';
$where = '';
$params = [];

if ($search !== '') {
  $like = [];
  foreach ($COLUMNS as $c) {
    $like[] = $c['name'] . " LIKE :q";
  }
  $where = "WHERE " . implode(" OR ", $like);
  $params[':q'] = "%{$search}%";
}

$sql = "SELECT id," . implode(',', array_map(fn($c) => $c['name'], $COLUMNS)) . " FROM {$TABLE} {$where} ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Site de Coleta Seletiva</title>
  
  <link rel="stylesheet" href="assets/style.css" />
  <link rel="stylesheet" href="assets/mainStyle.css" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  <script src="script.js" defer></script>
</head>

<body>
  <div class="container">
    
    <header class="cabecalho-principal">
      <img src="assets/leaf-svgrepo-com.svg" alt="Ícone de Coleta Seletiva" class="icone-titulo" />
      <h1>Controle de Coleta Seletiva</h1>
    </header>

    <div class="navbar">
      <form class="busca" method="get">
        
        <div class="search-wrapper">
          <input 
            type="text" 
            name="q" 
            placeholder="Pesquisar por bairro..." 
            value="<?php echo h($search); ?>"
            oninput="toggleClearIcon(this)"
          />
          <svg 
            xmlns="http://www.w3.org/2000/svg" 
            viewBox="0 0 24 24" 
            fill="currentColor" 
            class="cancel-icon" 
            width="20"
            onclick="clearSearch(this)"
            style="<?php echo !empty($search) ? 'display:block;' : 'display:none;'; ?>"
          >
            <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
          </svg>
        </div>
        
        <button type="submit" class="button">Buscar</button>
      </form>
      <a href="insert.php" class="button new"> + Novo Registro</a>
    </div>
    <table class="tabela-dados">
      <thead>
        <tr>
          <?php foreach ($COLUMNS as $c) : ?>
            <th><?php echo h($c['label']); ?></th>
          <?php endforeach; ?>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!$rows) : ?>
          <tr>
            <td colspan="<?php echo count($COLUMNS) + 1; ?>">
              <i>Nenhum registro encontrado.</i>
            </td>
          </tr>
        <?php else : ?>
          <?php foreach ($rows as $r) : ?>
            <tr>
              <?php foreach ($COLUMNS as $c) : ?>
                <td><?php echo h($r[$c['name']]); ?></td>
              <?php endforeach; ?>
              <td>
                <a class="button" href="update.php?id=<?php echo h($r['id']); ?>">Editar</a>
                <a 
                class="button danger" 
                href="delete.php?id=<?php echo h($r['id']); ?>"
                onclick="confirmDelete(event, this.href, '<?php echo h($r['bairro']); ?>')">
                Excluir
              </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div><div id="delete-modal" class="modal-overlay">
    <div class="modal-content">
      <h2 id="modal-delete-title">Confirmar Exclusão</h2>
      <p id="modal-delete-text">Tem certeza?</p>
      
      <div class="modal-actions">
        <a id="modal-confirm-delete" class="button danger" href="#">Confirmar Exclusão</a>
        <button class="button cancel" onclick="closeDeleteModal()">Cancelar</button>
      </div>
    </div>
  </div>
</body>
</html>