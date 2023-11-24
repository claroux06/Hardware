<?php

require_once "actions/cfg.php";

$query = "SELECT * FROM users";
$pre = $pdo->prepare($query);
$pre->execute();
$data = $pre->fetchAll(PDO::FETCH_ASSOC);

$t = time();
$uid = htmlspecialchars($_GET["uid"]); /* ID of the current user's badge */
$known = false; /* Start with the idea that every badge is unknown */

$test = "SELECT * FROM temps"; /* ORDER BY date_added DESC limit 1 */
$pre = $pdo->prepare($test);
$pre->execute();
$data1 = $pre->fetchAll(PDO::FETCH_ASSOC);


$test2 = "INSERT INTO temps (UID, Date) VALUES ('$uid', '$t')";
$pre = $pdo->prepare($test2);
$pre->execute();


$new = 1;
$d = -55; /* random number that is obviously different from $t */

foreach ($data1 as $data1){
    if ($data1['UID'] == $uid){
        if ($data1['ID'] >= $new){
            $d = $data1['Date'];
            $new = $data1['ID'];
        }
    }
}

if ($d != -55){
    if(($t-$d) < 86400){
        echo "0"; //nombre maximale de log journlier atteint 
        exit();
    }
}



foreach ($data as $data) {
    if ($uid == $data['UID']) {
        $known = true;
        if ($data['Access'] == 0) {
            echo "0"; //acces refuser
        } else {
            echo "1"; //acces autoriser
        }
    }
}

if (!$known) {
    echo "2"; //carte inconnue
}
?>