# PHPLab02 - Workshop Booking Portal

PHP session-based workshop booking demo implementing the Instruction stages and the Requirement's View Booking Detail and Edit Booking routes.

## Requirements

- PHP 8.1 or later with sessions enabled
- XAMPP or PHP's built-in development server

## Run locally

From this directory, start the built-in server with `php -S 127.0.0.1:8000`, then open `http://127.0.0.1:8000/index.php`. In XAMPP, place the project under `htdocs` and open it through `http://localhost/PHPLab02/`.

Bookings are stored in the browser's PHP session and are not shared across browsers or persisted to a database. The portal is a learning demo, not an account or inventory system.

## Routes

- `index.php`: catalog search/filter and theme preference form
- `booking.php`: booking GET form and CSRF-protected POST
- `dashboard.php`: session bookings, detail/edit links, and clear-session action
- `booking_detail.php?ref=...`: read-only booking detail
- `booking_edit.php?ref=...`: GET prefill and validated CSRF-protected POST update
- `request_demo.php`: GET/POST/REQUEST inspector
- `preference.php`, `logout.php`: POST-only state-changing actions

## Verification

Follow the test matrix and evidence checklist in `REPORT.md`. Real browser screenshots, personal/student details, and the GitHub repository URL must be added by the student before submission.