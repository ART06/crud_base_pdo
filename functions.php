<?php
function buildInsertSQL($table, $columns) {
  $cols = array_map(fn($c) => $c['name'], $columns);
  $placeholders = array_map(fn($c) => ':' . $c['name'], $columns);
  return "INSERT INTO {$table} (" . implode(',', $cols) . ") VALUES (" . implode(',', $placeholders) . ")";
}
function buildUpdateSQL($table, $columns) {
  $parts = [];
  foreach ($columns as $c) { $parts[] = $c['name'] . '=:' . $c['name']; }
  return "UPDATE {$table} SET " . implode(',', $parts) . " WHERE id=:id";
}
function h($s){ return htmlspecialchars((string)$s ?? '', ENT_QUOTES, 'UTF-8'); }
