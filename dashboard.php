<?php
require __DIR__ . '/lib/bootstrap.php';
$bookings = $_SESSION['bookings'];
$flash = $_SESSION['flash'] ?? '';
unset($_SESSION['flash']);
?>
<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>My bookings</title><link rel="stylesheet" href="assets/style.css"></head>
<body class="<?= e($theme) ?>"><main class="wrap">
<header><h1>My Bookings</h1><nav><a href="index.php">Catalog</a><a href="dashboard.php">My bookings</a></nav></header>
<?php if ($flash): ?><p class="notice"><?= e($flash) ?></p><?php endif; ?>
<?php if (!$bookings): ?>
<p class="panel">No bookings yet. <a href="index.php">Choose a workshop</a>.</p>
<?php else: ?>
<div class="panel table-wrap"><table><thead><tr><th>Reference</th><th>Attendee</th><th>Workshop</th><th>Qty</th><th>Amount</th><th>Actions</th></tr></thead><tbody>
<?php foreach ($bookings as $booking): ?>
<tr>
<td><?= e($booking['ref'] ?? '') ?></td>
<td><?= e($booking['full_name'] ?? '') ?><br><small><?= e($booking['email'] ?? '') ?></small></td>
<td><?= e($booking['workshop'] ?? '') ?></td>
<td><?= e($booking['quantity'] ?? '') ?></td>
<td><?= number_format((int)($booking['amount'] ?? 0)) ?> VND</td>
<td class="actions"><a href="booking_detail.php?ref=<?= urlencode((string)($booking['ref'] ?? '')) ?>">View Details</a><a href="booking_edit.php?ref=<?= urlencode((string)($booking['ref'] ?? '')) ?>">Edit</a></td>
</tr>
<?php endforeach; ?>
</tbody></table></div>
<?php endif; ?>
<form method="post" action="logout.php"><input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>"><button class="danger">Clear session &amp; start over</button></form>
</main></body></html>