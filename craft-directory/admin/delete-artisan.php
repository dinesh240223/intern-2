<?php
include 'auth.php';
include '../includes/db.php';
include '../includes/functions.php';

$id = intval($_GET['id'] ?? 0);
if ($id) {
    $conn->query("DELETE FROM artisans WHERE id=$id");
    setFlash('success', 'Artisan deleted.');
}
redirect('manage-artisans.php');
?>