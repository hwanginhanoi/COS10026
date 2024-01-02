<?php
    require_once("settings.php");
    $conn = @mysqli_connect($host, $user, $password, $sql_db);

    if(!$conn) {
        echo "<p>Oops! Something went wrong1! :(</p>";
    } else {
        $userId = htmlspecialchars(trim($_GET["userId"]));
        $productId = htmlspecialchars(trim($_GET["productId"]));
        $action = htmlspecialchars(trim($_GET["action"]));

        $errMsg = 0;

        if($userId == "" || $productId == "" || $action == "") {
            $errMsg += 1;
        }

        if($errMsg != 0) {
            echo "<p>Oops! Something went wrong2! :(</p>";
        } else {
            $query = "SELECT * FROM cart WHERE user_id = $userId AND product_id = $productId;";
            $result = mysqli_query($conn,$query);
            $row = mysqli_fetch_array($result);
            if($row["quantity"] == 1 && $action == "dec") {
                $query = "DELETE FROM cart WHERE user_id = $userId AND product_id = $productId;";
                mysqli_query($conn, $query);
            } else {
                $query = "UPDATE cart SET quantity = quantity ";
                if($action == "dec") {
                    $query .= "- ";
                } else {
                    $query .= "+ ";
                }
                $query .= "1 WHERE user_id = $userId AND product_id = $productId;";
                mysqli_query($conn, $query);
            }
            header("Location: manager.php?page=1");
        }
    }
?>