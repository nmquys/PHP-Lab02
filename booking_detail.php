<?php
require __DIR__ . '/lib/bootstrap.php';
$ref = is_string($_GET['ref'] ?? null) ? trim($_GET['ref']) : '';
$bookingIndex = $ref === '' ? null : find_booking_index_by_ref($_SESSION['bookings'], $ref);
$booking = $bookingIndex === null ? null : $_SESSION['bookings'][$bookingIndex];
$workshopId = is_array($booking) ? ($booking['workshop_id'] ?? '') : '';
$workshop = is_string($workshopId) ? ($workshops[$workshopId] ?? null) : null;
if (!$workshop) {
    $booking = null;
    http_response_code(404);
}
?>
<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Booking details</title><link rel="stylesheet" href="assets/style.css"></head>
<body class="<?= e($theme) ?>"><main class="wrap">
<header><h1>Booking Details</h1><nav><a href="index.php">Catalog</a><a href="dashboard.php">My bookings</a></nav></header>
<?php if (!$booking): ?>
<p class="notice error-panel">Booking not found.</p>
<a class="btn secondary" href="dashboard.php">Back to My Bookings</a>
<?php else: ?>
<dl class="panel details">
<dt>Reference</dt><dd><?= e($booking['ref']) ?></dd>
<dt>Full name</dt><dd><?= e($booking['full_name']) ?></dd>
<dt>Email</dt><dd><?= e($booking['email']) ?></dd>
<dt>Workshop</dt><dd><?= e($workshop['title']) ?></dd>
<dt>Tickets</dt><dd><?= e($booking['quantity']) ?></dd>
<dt>Unit fee</dt><dd><?= number_format($workshop['fee']) ?> VND</dd>
<dt>Amount</dt><dd><?= number_format($workshop['fee'] * (int)$booking['quantity']) ?> VND</dd>
</dl>
<div class="actions"><a class="btn secondary" href="dashboard.php">Back to My Bookings</a><a class="btn" href="booking_edit.php?ref=<?= urlencode($booking['ref']) ?>">Edit This Booking</a></div>
<?php endif; ?>
</main></body></html>