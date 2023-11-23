<?php

require_once "cfg.php";

$query = "SELECT * FROM users";
$pre = $pdo->prepare($query);
$pre->execute();
$data = $pre->fetchAll(PDO::FETCH_ASSOC);

$uid = htmlspecialchars($_GET["uid"]); /* ID of the current user's badge */
$known = false; /* Start with the idea that every badge is unknown */

foreach ($data as $data) {
    if ($uid == $data['UID']) {
        $known = true;
        if ($data['Access'] == 0) {
            echo "Access denied";
        } else {
            echo "Access granted";
        }
    }
}

if (!$known) {
    echo "User unidentified";
}

header("Location:../index.php");
?>