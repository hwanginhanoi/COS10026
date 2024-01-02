<?php
    require_once("settings.php");
    $conn = @mysqli_connect($host, $user, $password, $sql_db);

    if(!$conn) {
        echo "<p>Oops! Something went wrong! :(</p>";
    } else {
        $orderId = $_GET["orderId"];
        $newStatus = htmlspecialchars(trim($_POST["status"]));

        $errMsg = 0;

        if($orderId == "" || $newStatus == "") {
            $errMsg += 1;
        }

        if($errMsg != 0) {
            echo "<p>Oops! Something went wrong! :(</p>";
        } else {
            $query = "UPDATE orders SET order_status = '$newStatus' WHERE order_id = $orderId;";
            mysqli_query($conn, $query);
            header("Location: manager.php?page=2");
        }
    }
?>