<?php
declare(strict_types=1);

function e(mixed $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function normalize_text(string $text): string
{
    $text = strip_tags(trim($text));
    return preg_replace('/\s+/u', ' ', $text) ?? $text;
}

function validate_booking(array $input, array $workshops): array
{
    $errors = [];
    $rawName = $input['full_name'] ?? '';
    $rawEmail = $input['email'] ?? '';
    $rawWorkshopId = $input['workshop_id'] ?? '';
    $name = normalize_text(is_scalar($rawName) ? (string)$rawName : '');
    $email = trim(is_scalar($rawEmail) ? (string)$rawEmail : '');
    $workshopId = is_string($rawWorkshopId) ? $rawWorkshopId : '';
    $qtyRaw = $input['quantity'] ?? null;
    $qty = is_scalar($qtyRaw) ? filter_var($qtyRaw, FILTER_VALIDATE_INT) : false;

    if ($name === '') {
        $errors['full_name'] = 'Full name is required.';
    } elseif (strlen($name) < 2 || strlen($name) > 120) {
        $errors['full_name'] = 'Name must have 2–120 bytes.';
    }
    if ($email === '') {
        $errors['email'] = 'Email is required.';
    } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $errors['email'] = 'Invalid email format.';
    }
    if (!isset($workshops[$workshopId])) {
        $errors['workshop_id'] = 'Please select an existing workshop.';
    }
    if ($qty === false || $qty < 1 || $qty > 4) {
        $errors['quantity'] = 'Quantity must be an integer from 1 to 4.';
    } elseif (isset($workshops[$workshopId]) && $qty > $workshops[$workshopId]['seats']) {
        $errors['quantity'] = 'Not enough seats for this booking.';
    }

    return ['errors'=>$errors, 'data'=>[
        'full_name'=>$name,
        'email'=>$email,
        'workshop_id'=>$workshopId,
        'quantity'=>$qty === false ? 0 : $qty,
    ]];
}

function find_booking_index_by_ref(array $bookings, string $ref): ?int
{
    foreach ($bookings as $index => $booking) {
        if (is_array($booking) && ($booking['ref'] ?? null) === $ref) {
            return $index;
        }
    }
    return null;
}

function redirect_to(string $path): never
{
    header('Location: ' . $path, true, 303);
    exit;
}

function csrf_valid(array $post): bool
{
    $token = $post['csrf_token'] ?? null;
    $sessionToken = $_SESSION['csrf_token'] ?? null;
    return is_string($token) && is_string($sessionToken) && hash_equals($sessionToken, $token);
}