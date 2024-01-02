<?php
    require_once("settings.php");
    $conn = @mysqli_connect($host, $user, $password, $sql_db);

    if(!$conn) {
        echo "<p>Oops! Something went wrong! :(</p>";
    } else {
        $pname = htmlspecialchars(trim($_POST["pname"]));
        $pdesc = htmlspecialchars(trim($_POST["pdesc"]));
        $pprice = htmlspecialchars(trim($_POST["pprice"]));

        // process image file
        $pimageName = basename($_FILES["pimage"]["name"]);
        $pimageType = pathinfo($pimageName, PATHINFO_EXTENSION);

        $allowTypes = ['jpg','png','jpeg','gif'];

        if(in_array($pimageType, $allowTypes)) {
            $pimage = $_FILES["pimage"]["tmp_name"];
            $pimageContent = addslashes(file_get_contents($pimage));
        }

        $errMsg = 0;

        if($pname == "" || $pdesc == "" || $pprice == "") {
            $errMsg += 1;
        }

        if($errMsg != 0) {
            echo "<p>Oops! Something went wrong! :(</p>";
        } else {

            // query to create products table if not exists
            $query = "CREATE TABLE IF NOT EXISTS products (
                            product_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, pname VARCHAR(40), 
                            pdesc TEXT, pprice INT, pimage LONGBLOB, pimagetype VARCHAR(5), pdate DATETIME
                        );";

            // query to insert products information into products table
            $query .= "INSERT INTO products (pname, pdesc, pprice, pimage, pimagetype, pdate) VALUES 
                        ('$pname','$pdesc',$pprice,'$pimageContent','$pimageType',
                        CONVERT_TZ(NOW(), @@session.time_zone, '+07:00'));";
            $result = mysqli_multi_query($conn,$query);
            header("Location: manager.php");
        }
    }
?>