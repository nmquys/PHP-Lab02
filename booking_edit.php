<?php
require __DIR__ . '/lib/bootstrap.php';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$ref = is_string($_GET['ref'] ?? null) ? trim($_GET['ref']) : '';
$bookingIndex = $ref === '' ? null : find_booking_index_by_ref($_SESSION['bookings'], $ref);
if ($bookingIndex === null) {
    http_response_code(404);
    $booking = null;
    $workshop = null;
} else {
    $booking = $_SESSION['bookings'][$bookingIndex];
    $workshopId = is_string($booking['workshop_id'] ?? null) ? $booking['workshop_id'] : '';
    $workshop = $workshops[$workshopId] ?? null;
    if (!$workshop) {
        http_response_code(404);
        $booking = null;
    }
}
if ($method !== 'GET' && $method !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}
if ($method === 'POST') {
    if (!csrf_valid($_POST)) {
        http_response_code(403);
        exit('Invalid CSRF token');
    }
    if (!$booking) {
        http_response_code(404);
    } else {
        $input = $_POST;
        $input['workshop_id'] = $booking['workshop_id'];
        $result = validate_booking($input, $workshops);
        $errors = $result['errors'];
        $old = [
            'full_name'=>is_scalar($_POST['full_name'] ?? null) ? (string)$_POST['full_name'] : '',
            'email'=>is_scalar($_POST['email'] ?? null) ? (string)$_POST['email'] : '',
            'quantity'=>is_scalar($_POST['quantity'] ?? null) ? (string)$_POST['quantity'] : '',
        ];
        if (!$errors) {
            $data = $result['data'];
            $_SESSION['bookings'][$bookingIndex]['full_name'] = $data['full_name'];
            $_SESSION['bookings'][$bookingIndex]['email'] = $data['email'];
            $_SESSION['bookings'][$bookingIndex]['quantity'] = $data['quantity'];
            $_SESSION['bookings'][$bookingIndex]['amount'] = $workshop['fee'] * $data['quantity'];
            $_SESSION['flash'] = 'Booking updated successfully.';
            redirect_to('dashboard.php');
        }
    }
} else {
    $errors = [];
    $old = $booking ? [
        'full_name'=>$booking['full_name'],
        'email'=>$booking['email'],
        'quantity'=>$booking['quantity'],
    ] : [];
}
?>
<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Edit booking</title><link rel="stylesheet" href="assets/style.css"></head>
<body class="<?= e($theme) ?>"><main class="wrap">
<header><h1>Edit Booking</h1><nav><a href="index.php">Catalog</a><a href="dashboard.php">My bookings</a></nav></header>
<?php if (!$booking): ?>
<p class="notice error-panel">Booking not found.</p><a class="btn secondary" href="dashboard.php">Back to My Bookings</a>
<?php else: ?>
<p class="muted">Reference: <?= e($booking['ref']) ?> · Workshop: <?= e($workshop['title']) ?> · Unit fee: <?= number_format($workshop['fee']) ?> VND</p>
<form method="post" action="booking_edit.php?ref=<?= urlencode($booking['ref']) ?>" class="panel form-stack" novalidate>
<input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
<label>Full name <input name="full_name" value="<?= e($old['full_name'] ?? '') ?>" autocomplete="name"></label><small class="error"><?= e($errors['full_name'] ?? '') ?></small>
<label>Email <input name="email" value="<?= e($old['email'] ?? '') ?>" type="email" autocomplete="email"></label><small class="error"><?= e($errors['email'] ?? '') ?></small>
<label>Tickets (1–4) <input name="quantity" type="number" min="1" max="4" value="<?= e($old['quantity'] ?? '') ?>"></label><small class="error"><?= e($errors['quantity'] ?? '') ?></small>
<div class="actions"><button>Save Changes</button><a class="btn secondary" href="booking_detail.php?ref=<?= urlencode($booking['ref']) ?>">Cancel</a></div>
</form>
<?php endif; ?>
</main></body></html>