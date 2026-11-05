<?php
require __DIR__ . '/lib/bootstrap.php';
$method = $_SERVER['REQUEST_METHOD'] ?? 'UNKNOWN';
$getName = (string)($_GET['name'] ?? '');
$postName = (string)($_POST['name'] ?? '');
$requestName = (string)($_REQUEST['name'] ?? '');
?>
<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Request Inspector</title><link rel="stylesheet" href="assets/style.css"></head>
<body class="<?= e($theme) ?>"><main class="wrap">
<h1>Request Inspector</h1>
<p>Method: <?= e($method) ?></p>
<p>GET name: <?= e($getName) ?></p>
<p>POST name: <?= e($postName) ?></p>
<p>REQUEST name: <?= e($requestName) ?></p>
<form method="get" class="panel form-row"><label>GET name <input name="name" placeholder="GET name"></label><button>Send GET</button></form>
<form method="post" class="panel form-row"><label>POST name <input name="name" placeholder="POST name"></label><button>Send POST</button></form>
<a href="index.php">Back to catalog</a>
</main></body></html>