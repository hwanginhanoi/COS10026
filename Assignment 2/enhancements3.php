<?php
    include("head.inc");
    include("header.inc");
    include("footer.inc");
?>
<!DOCTYPE html>
<html lang="en">
    <?php
        head_code();
    ?>
    <body>
        <?php
            header_code(4);
        ?>
        <main>
            <div id="container">
                <section>
                <div id="enhancements-nav">
                    <a href="enhancements3.php#fea1-content">Enhancement 1</a>
                    <a href="enhancements3.php#fea2-content">Enhancement 2</a>
                </div>
                <div id="feature-content">
                    <div class="feature-desc" id="fea1-content">
                        <h2>Enhancement 1 - Admin login and admin management page</h2>
                        <img src="images/enhancement/admin.png" alt=""/>
                        <p class="description">
                            <strong>Enhancement description: </strong>
                        </p>
                        <ol>
                            <li>The index.php page allows the admin or the user to log in. If the input email is "admin" and password is "password" the user will be logged in as an admin.</li>
                            <li>The manager page's control will base on the user type, which can be either 0 (represent the user) or 1 (represent the admin).</li>
                            <li>If admin is logged in, the manager.php page will allow the admin to:
                                <ol>
                                    <li><strong>Add product: </strong>Add a new product</li>
                                    <li><strong>Manage product: </strong>Edit or delete an existing product</li>
                                    <li><strong>Order requests: </strong>Display order requests by user. The admin have options to search and filter requests such as search requests by user name or sort the requests</li>
                                    <li><strong>Done requests: </strong>All complete order requests will be displayed</li>
                                </ol>
                            </li>
                            <li>Log out</li>
                        </ol>
                        <p class="description">
                            <strong>PHP code: </strong>
                        </p>
                        <ol>
                            <li>
                                processLogin.php:
                                <pre class="code"><code class="language-php">
    require_once("settings.php");
    $conn = @mysqli_connect($host, $user, $password, $sql_db);

    if(!$conn) {
        echo "&lt;p&gt;Oops! Something went wrong! :(&lt;/p&gt;";
    } else {
        $email = htmlspecialchars(trim($_POST["email"]));
        $password = htmlspecialchars(trim($_POST["password"]));

        
        $errors = [];
        $errMsg = "";
        if($email != "admin") {
            if(strlen($email) &lt; 3) {
                array_push($errors,'err_email');
            } else {
                $check = false;
                for($i = 1; $i &lt; strlen($email) - 1; $i++) {
                    if($email[$i] == '@') {
                        $check = true;
                        break;
                    }
                }
                if(!$check) {
                    array_push($errors,'err_email');
                }
            }
        }

        if(!isset($_POST["password"]) || strlen($password) &lt; 3) {
            array_push($errors,'err_pwd');
        }

        
        if(count($errors) != 0) {
            $errString = "";
            for($i = 0; $i &lt; count($errors); $i ++) {
                $errCode = $errors[$i];
                $errString .= "$errCode=1";
                if($i &lt; count($errors) - 1) {
                    $errString .= "&";
                }
            }
            header("Location: index.php?$errString");
        } else {
            $query = "SELECT email, type, password FROM users WHERE email = '$email' AND password = '$password' AND type = ";
            if($email == "admin") {
                $query .= "1";
            } else {
                $query .= "0";
            }
            $query .= ";";
            $result = mysqli_query($conn,$query);
            $userCre = $result -&gt; fetch_assoc();
            if($userCre && $userCre["email"] == $email && ($userCre["type"] == 0 || $userCre["type"] == 1) && $userCre["password"] == $password) {
                $query = "SELECT user_id, fname, lname, phone, email, address, type FROM users WHERE email = '$email' AND password = '$password' AND type = ";
                if($email == "admin") {
                    $query .= "1";
                } else {
                    $query .= "0";
                }
                $query .= ";";
                $result = mysqli_query($conn,$query);
                $user = $result -&gt; fetch_assoc();
                session_start();
                $_SESSION["user"] = $user;
                if($_SESSION["user"] && $_SESSION["user"] != null) {
                    header("Location: home.php");
                } else {
                    echo $user;
                }
            } else {
                header("Location: index.php?err_wrong=1");
            }
        }
    }
                                </code></pre>
                            </li>
                            <li>
                                manager.php:
                                <pre class="code"><code class="language-php">
    function addProduct() {
    echo"
    &lt;div class='tab products-tab'&gt;
        &lt;h2&gt;Add a Product&lt;/h2&gt;
        &lt;form method='POST' action='addProduct.php' enctype='multipart/form-data'&gt;
            &lt;div&gt;
                &lt;label for='pname'&gt;Product name: &lt;/label&gt;
                &lt;input type='text' name='pname' id='pname' required/&gt;
            &lt;/div&gt;
            &lt;div&gt;
                &lt;label for='pdesc'&gt;Product description: &lt;/label&gt;
                &lt;textarea type='text' name='pdesc' id='pdesc' required&gt;&lt;/textarea&gt;
            &lt;/div&gt;
            &lt;div&gt;
                &lt;label for='pprice'&gt;Product price: $&lt;/label&gt;
                &lt;input type='number' name='pprice' id='pprice' required/&gt;
            &lt;/div&gt;
            &lt;div&gt;
                &lt;label for='pimage'&gt;Product image: &lt;/label&gt;
                &lt;input type='file' name='pimage' id='pimage' accept='image/png, image/gif, image/jpeg'  required/&gt;
            &lt;/div&gt;
            &lt;input type='submit' id='update-btn' class='shop-btn' value='Add'/&gt;
        &lt;/form&gt;
    &lt;/div&gt;
    ";
}
function editProduct($conn) {
    echo "
    &lt;div class='tab products-tab'&gt;
        &lt;h2&gt;Manage Products&lt;/h2&gt;
        &lt;form method='POST' action='editProduct.php' enctype='multipart/form-data'&gt;
            &lt;div&gt;
                &lt;label for='editting-product'&gt;Edit product: &lt;/label&gt;
                &lt;select name='editting-product' required&gt;
                    &lt;option value=''&gt;Select&lt;/option&gt;";
                    if(!$conn) {
                        echo "&lt;p&gt;Sth went wrong!:(&lt;/P&gt;";
                    } else {
                        $query = "SELECT * FROM products ORDER BY pdate DESC;";
                        $result = mysqli_query($conn, $query);
                        while($row = mysqli_fetch_array($result)) {
                            $id = $row["product_id"];
                            $name = $row["pname"];
                            echo "&lt;option value='$id'&gt;ID: $id, Name: $name&lt;/option&gt;";
                        }
                    }
                echo "&lt;/select&gt;
            &lt;/div&gt;
            &lt;div&gt;
                &lt;label for='pname'&gt;Product name: &lt;/label&gt;
                &lt;input type='text' name='pname' id='pname'/&gt;
            &lt;/div&gt;
            &lt;div&gt;
                &lt;label for='pdesc'&gt;Product description: &lt;/label&gt;
                &lt;textarea type='text' name='pdesc' id='pdesc'&gt;&lt;/textarea&gt;
            &lt;/div&gt;
            &lt;div&gt;
                &lt;label for='pprice'&gt;Product price: $&lt;/label&gt;
                &lt;input type='number' name='pprice' id='pprice'/&gt;
            &lt;/div&gt;
            &lt;div&gt;
                &lt;label for='pimage'&gt;Product image: &lt;/label&gt;
                &lt;input type='file' name='pimage' id='pimage' accept='image/png, image/gif, image/jpeg'/&gt;
            &lt;/div&gt;
            &lt;div id='edit-btns'&gt;
                &lt;input type='submit' id='update-btn' class='shop-btn' value='Edit'/&gt;
                &lt;input type='submit' id='delete-btn' name='delete' class='shop-btn' value='Delete'/&gt;
            &lt;/div&gt;
        &lt;/form&gt;
    &lt;/div&gt;
    ";
}
function orderRequest($conn) {
    if(!$conn) {
        echo "&lt;p&gt;Sth went wrong!:(&lt;/P&gt;";
    } else {
        echo "
        &lt;div class='tab' id='order-request'&gt;
            &lt;h2&gt;Pending Requests&lt;/h2&gt;
            &lt;div class='request-item search-item'&gt;
            &lt;form method='POST' action='manager.php?page=2' id='search-box'&gt;
                &lt;input type='text' name='searchkey' id='searchkey' placeholder='Search order with username ...'/&gt;
                &lt;div id='filter-box'&gt;
                    &lt;select id='order' class='order half' name='order'&gt;
                        &lt;option value=''&gt;Filter&lt;/option&gt;
                        &lt;option value='old-up'&gt;All - Latest orders&lt;/option&gt;
                        &lt;option value='old-down'&gt;All - Earliest orders&lt;/option&gt;
                        &lt;option value='price-up'&gt;Price: Low to high&lt;/option&gt;
                        &lt;option value='price-down'&gt;Price: High to low&lt;/option&gt;
                        &lt;option value='pending'&gt;Pending orders&lt;/option&gt;
                    &lt;/select&gt;
                    &lt;select id='product' class='order half' name='product'&gt;
                        &lt;option value=''&gt;Orders contain product&lt;/option&gt;";
                                $query = "SELECT * FROM products ORDER BY pdate DESC";
                                $result = mysqli_query($conn,$query);
                                $options = [];
                                while($row = mysqli_fetch_array($result)) {
                                    array_push($options, $row);
                                }
                                $i = 0;
                                while($i &lt; count($options)) {
                                    $id = $options[$i]["product_id"];
                                    $name = $options[$i]["pname"];
                                    echo "&lt;option value='$id'&gt;$name&lt;/option&gt;";
                                    $i++;
                                }
                    echo "&lt;/select&gt;
                &lt;/div&gt;
                &lt;button id='search-btn' type='submit'&gt;
                    &lt;img src='images/search.svg' alt=''&gt;
                &lt;/button&gt;
            &lt;/form&gt;
            &lt;/div&gt;
        ";
        $orderBy = "order_time DESC;";
        $query = "SELECT order_id, user_id, fname, lname, phone, email, street, town, state, post_code, pref_contact, order_status, order_time FROM orders ";
        
        $query .= "WHERE order_status != 'ARCHIVED' AND ";
        $where = "";
        $whereCon = [];
        if(isset($_POST["searchkey"])) {
            array_push($whereCon,'searchKey');
            $searchKey = $_POST["searchkey"];
            $where .= "(fname LIKE '%$searchKey%' OR lname LIKE '%$searchKey%') AND ";
        } 
        if(isset($_POST["order"]) && $_POST["order"] == 'pending') {
            array_push($whereCon,'pending');
            $where .= "order_status = 'PENDING' AND ";
        }
        if(isset($_POST["product"]) && $_POST["product"] != "") {
            $searchId = $_POST["product"];
            array_push($whereCon,'product');
            $incProductQuery = "SELECT order_id FROM order_products WHERE product_id = $searchId;";
            $result = mysqli_query($conn, $incProductQuery);
            $ordersIncProduct = [];
            $inString = "";
            while($row = mysqli_fetch_array($result)) {
                array_push($ordersIncProduct,$row["order_id"]);
            }
            for($i = 0; $i &lt; count($ordersIncProduct); $i++) {
                $inString .= $ordersIncProduct[$i];
                if($i != count($ordersIncProduct) - 1) {
                    $inString .= ",";
                }
            }
            
            if($inString !="") {$where .= "order_id IN ($inString) AND ";} else {$where .= "-1 AND ";}
        }
        $where .= "1 ";
        $query .= $where;
        if(isset($_POST["order"])) {
            switch($_POST["order"]) {
                case 'price-up':
                    $orderBy = "order_cost ASC";
                    break; 
                case 'price-down':
                    $orderBy = "order_cost DESC";
                    break; 
                case 'old-up':
                    $orderBy = "order_time DESC";
                    break; 
                case 'old-down':
                    $orderBy = "order_time ASC";
                    break;
            }
        }
        $query .= "ORDER BY $orderBy;";
        $result = mysqli_query($conn,$query);
        while($row = mysqli_fetch_array($result)) {
            $orderId = $row["order_id"];
            $userId = $row["user_id"];
            $fname = $row["fname"];
            $lname = $row["lname"];
            $phone = $row["phone"];
            $email = $row["email"];
            $street = $row["street"];
            $town = $row["town"];
            $state = $row["state"];
            $postCode = $row["post_code"];
            $prefContact = $row["pref_contact"];
            $status = $row["order_status"];
            $date = $row["order_time"];
            echo "
                &lt;div class='request-item'&gt;";
                    if($status == 'PENDING') {
                        echo "&lt;a class='delete-btn' href='deleteRequest.php?orderId=$orderId'&gt;
                            DELETE
                        &lt;/a&gt;";
                    }
                    echo "&lt;h3&gt;$fname $lname&lt;/h3&gt;
                    &lt;h4&gt;Order ID: $orderId&lt;/h4&gt;
                    &lt;div class='order-general-info'&gt;&lt;strong&gt;Date: &lt;/strong&gt;&lt;span&gt;$date&lt;/span&gt;&lt;/div&gt;
                    &lt;div class='order-general-info'&gt;&lt;strong&gt;Email: &lt;/strong&gt;&lt;span&gt;$email&lt;/span&gt;&lt;/div&gt;
                    &lt;div class='order-general-info'&gt;&lt;strong&gt;Phone: &lt;/strong&gt;&lt;span&gt;$phone&lt;/span&gt;&lt;/div&gt;
                    &lt;div class='order-general-info'&gt;&lt;strong&gt;Address: &lt;/strong&gt;&lt;span&gt;$street, $town, $state $postCode&lt;/span&gt;&lt;/div&gt;
                    &lt;div class='order-general-info'&gt;&lt;strong&gt;Preffered contact: &lt;/strong&gt;&lt;span&gt;$prefContact&lt;/span&gt;&lt;/div&gt;
                    &lt;div class='order-request-items'&gt;
                        &lt;p&gt;&lt;strong&gt;Items Ordered:&lt;/strong&gt;&lt;/p&gt;
                        &lt;table class='info-table'&gt;
                            &lt;tr&gt;
                                &lt;th&gt;&lt;/th&gt;
                                &lt;th&gt;Product ID&lt;/th&gt;
                                &lt;th&gt;Name&lt;/th&gt;
                                &lt;th&gt;Color&lt;/th&gt;
                                &lt;th&gt;Version&lt;/th&gt;
                                &lt;th&gt;Price&lt;/th&gt;
                                &lt;th&gt;Quantity&lt;/th&gt;
                                &lt;th&gt;Total&lt;/th&gt;
                            &lt;/tr&gt;";
                            if(!$conn) {
                                echo "&lt;p&gt;Sth went wrong!:(&lt;/P&gt;";
                            } else {
                                $query = "SELECT * FROM order_products JOIN products ON order_products.product_id = products.product_id WHERE order_id = $orderId;";
                                $itemResult = mysqli_query($conn, $query);
                                $total = 0;
                                $i = 1;
                                while($itemRow = mysqli_fetch_array($itemResult)) {
                                    $productId = $itemRow["product_id"];
                                    $name = $itemRow["pname"];
                                    $color = $itemRow["color"];
                                    $version = $itemRow["version"];
                                    $quantity = $itemRow["quantity"];
                                    $price = $itemRow["pprice"];
                                    $subtotal = 0;
                                    $subtotal += $price;
                                    if(strpos($version,'fea1') !== false) {
                                        $subtotal += 50;
                                    }
                                    if(strpos($version,'fea2') !== false) {
                                        $subtotal += 100;
                                    }
                                    if(strpos($version,'fea3') !== false) {
                                        $subtotal += 200;
                                    }
                                    $subtotal *= $quantity;
                                    echo "
                                    &lt;tr class='table-row'&gt;
                                        &lt;td&gt;&lt;strong&gt;$i&lt;/strong&gt;&lt;/td&gt;
                                        &lt;td&gt;&lt;strong&gt;$productId&lt;/strong&gt;&lt;/td&gt;
                                        &lt;td&gt;&lt;strong&gt;$name&lt;/strong&gt;&lt;/td&gt;
                                        &lt;td&gt;$color&lt;/td&gt;
                                        &lt;td&gt;$version&lt;/td&gt;
                                        &lt;td class='price'&gt;$price&lt;/td&gt;
                                        &lt;td&gt;&lt;strong&gt;$quantity&lt;/strong&gt;&lt;/td&gt;
                                        &lt;td class='price'&gt;$$subtotal&lt;/td&gt;
                                    &lt;/tr&gt;
                                    ";
                                    $i++;
                                    $total += $subtotal;
                                }
                            }
                            echo "
                            &lt;tr class='table-row'&gt;
                                &lt;td&gt;&lt;strong&gt;Total&lt;/strong&gt;&lt;/td&gt;
                                &lt;td&gt;&lt;/td&gt;
                                &lt;td&gt;&lt;/td&gt;
                                &lt;td&gt;&lt;/td&gt;
                                &lt;td&gt;&lt;/td&gt;
                                &lt;td&gt;&lt;/td&gt;
                                &lt;td&gt;&lt;/td&gt;
                                &lt;td class='price'&gt;$$total&lt;/td&gt;
                            &lt;/tr&gt;
                        &lt;/table&gt;
                    &lt;/div&gt;
                    &lt;form method='POST' action='changeStatus.php?orderId=$orderId'&gt;
                        &lt;div class='other-group radio-group'&gt;
                            &lt;p class='order-status-label'&gt;&lt;strong&gt;Order Status: &lt;/strong&gt;&lt;/p&gt;
                            &lt;label class='option-group order-status'&gt;&lt;span class='PENDING'&gt;PENDING&lt;/span&gt;
                                &lt;input type='radio' name='status' value='PENDING'";
                                 if($status == 'PENDING') echo "checked='checked'";
                                 echo "/&gt;
                                &lt;span class='radio-checkmark PENDING-check'&gt;&lt;/span&gt;
                            &lt;/label&gt;
                            &lt;label class='option-group order-status'&gt;&lt;span class='FULFILLED'&gt;FULFILLED&lt;/span&gt;
                                &lt;input type='radio' name='status' value='FULFILLED'";
                                if($status == 'FULFILLED') echo "checked='checked'";
                                echo "/&gt;
                                &lt;span class='radio-checkmark FULFILLED-check'&gt;&lt;/span&gt;
                            &lt;/label&gt;
                            &lt;label class='option-group order-status'&gt;&lt;span class='PAID'&gt;PAID&lt;/span&gt;
                                &lt;input type='radio' name='status' value='PAID'";
                                if($status == 'PAID') echo "checked='checked'";
                                echo "/&gt;
                                &lt;span class='radio-checkmark PAID-check'&gt;&lt;/span&gt;
                            &lt;/label&gt;
                            &lt;label class='option-group order-status'&gt;&lt;span class='ARCHIVED'&gt;ARCHIVED&lt;/span&gt;
                                &lt;input type='radio' name='status' value='ARCHIVED'
                                /&gt;
                                &lt;span class='radio-checkmark ARCHIVED-check'&gt;&lt;/span&gt;
                            &lt;/label&gt;
                        &lt;/div&gt;
                        &lt;input type='submit' class='shop-btn' value='Save'/&gt;
                    &lt;/form&gt;
                &lt;/div&gt;
            ";
        }
        echo "
        &lt;/div&gt;";
    }

}
function doneRequest($conn) {
    if(!$conn) {
        echo "&lt;p&gt;Sth went wrong!:(&lt;/P&gt;";
    } else {
        echo "
        &lt;div class='tab' id='order-request'&gt;
            &lt;h2&gt;Done Requests&lt;/h2&gt;
        ";
        $query = "SELECT order_id, user_id, fname, lname, phone, email, street, town, state, post_code, pref_contact, order_status, order_time FROM orders WHERE order_status = 'ARCHIVED' ORDER BY order_time DESC;";
        $result = mysqli_query($conn,$query);
        while($row = mysqli_fetch_array($result)) {
            $orderId = $row["order_id"];
            $userId = $row["user_id"];
            $fname = $row["fname"];
            $lname = $row["lname"];
            $phone = $row["phone"];
            $email = $row["email"];
            $street = $row["street"];
            $town = $row["town"];
            $state = $row["state"];
            $postCode = $row["post_code"];
            $prefContact = $row["pref_contact"];
            $status = $row["order_status"];
            $date = $row["order_time"];
            echo "
                &lt;div class='request-item'&gt;
                    &lt;h3&gt;$fname $lname&lt;/h3&gt;
                    &lt;h4&gt;Order ID: $orderId&lt;/h4&gt;
                    &lt;div class='order-general-info'&gt;&lt;strong&gt;Date: &lt;/strong&gt;&lt;span&gt;$date&lt;/span&gt;&lt;/div&gt;
                    &lt;div class='order-general-info'&gt;&lt;strong&gt;Email: &lt;/strong&gt;&lt;span&gt;$email&lt;/span&gt;&lt;/div&gt;
                    &lt;div class='order-general-info'&gt;&lt;strong&gt;Phone: &lt;/strong&gt;&lt;span&gt;$phone&lt;/span&gt;&lt;/div&gt;
                    &lt;div class='order-general-info'&gt;&lt;strong&gt;Address: &lt;/strong&gt;&lt;span&gt;$street, $town, $state $postCode&lt;/span&gt;&lt;/div&gt;
                    &lt;div class='order-general-info'&gt;&lt;strong&gt;Preffered contact: &lt;/strong&gt;&lt;span&gt;$prefContact&lt;/span&gt;&lt;/div&gt;
                    &lt;p&gt;&lt;strong&gt;Status: &lt;/strong&gt;&lt;span class='$status'&gt;$status&lt;/span&gt;&lt;/p&gt;
                    &lt;div class='order-request-items'&gt;
                        &lt;p&gt;&lt;strong&gt;Items Ordered:&lt;/strong&gt;&lt;/p&gt;
                        &lt;table class='info-table'&gt;
                            &lt;tr&gt;
                                &lt;th&gt;&lt;/th&gt;
                                &lt;th&gt;Product ID&lt;/th&gt;
                                &lt;th&gt;Name&lt;/th&gt;
                                &lt;th&gt;Color&lt;/th&gt;
                                &lt;th&gt;Version&lt;/th&gt;
                                &lt;th&gt;Price&lt;/th&gt;
                                &lt;th&gt;Quantity&lt;/th&gt;
                                &lt;th&gt;Total&lt;/th&gt;
                            &lt;/tr&gt;";
                            if(!$conn) {
                                echo "&lt;p&gt;Sth went wrong!:(&lt;/P&gt;";
                            } else {
                                $query = "SELECT * FROM order_products JOIN products ON order_products.product_id = products.product_id WHERE order_id = $orderId;";
                                $itemResult = mysqli_query($conn, $query);
                                $total = 0;
                                $i = 1;
                                while($itemRow = mysqli_fetch_array($itemResult)) {
                                    $productId = $itemRow["product_id"];
                                    $name = $itemRow["pname"];
                                    $color = $itemRow["color"];
                                    $version = $itemRow["version"];
                                    $quantity = $itemRow["quantity"];
                                    $price = $itemRow["pprice"];
                                    $subtotal = 0;
                                    $subtotal += $price;
                                    if(strpos($version,'fea1') !== false) {
                                        $subtotal += 50;
                                    }
                                    if(strpos($version,'fea2') !== false) {
                                        $subtotal += 100;
                                    }
                                    if(strpos($version,'fea3') !== false) {
                                        $subtotal += 200;
                                    }
                                    $subtotal *= $quantity;
                                    echo "
                                    &lt;tr class='table-row'&gt;
                                        &lt;td&gt;&lt;strong&gt;$i&lt;/strong&gt;&lt;/td&gt;
                                        &lt;td&gt;&lt;strong&gt;$productId&lt;/strong&gt;&lt;/td&gt;
                                        &lt;td&gt;&lt;strong&gt;$name&lt;/strong&gt;&lt;/td&gt;
                                        &lt;td&gt;$color&lt;/td&gt;
                                        &lt;td&gt;$version&lt;/td&gt;
                                        &lt;td class='price'&gt;$price&lt;/td&gt;
                                        &lt;td&gt;&lt;strong&gt;$quantity&lt;/strong&gt;&lt;/td&gt;
                                        &lt;td class='price'&gt;$$subtotal&lt;/td&gt;
                                    &lt;/tr&gt;
                                    ";
                                    $i++;
                                    $total += $subtotal;
                                }
                            }
                            echo "
                            &lt;tr class='table-row'&gt;
                                &lt;td&gt;&lt;strong&gt;Total&lt;/strong&gt;&lt;/td&gt;
                                &lt;td&gt;&lt;/td&gt;
                                &lt;td&gt;&lt;/td&gt;
                                &lt;td&gt;&lt;/td&gt;
                                &lt;td&gt;&lt;/td&gt;
                                &lt;td&gt;&lt;/td&gt;
                                &lt;td&gt;&lt;/td&gt;
                                &lt;td class='price'&gt;$$total&lt;/td&gt;
                            &lt;/tr&gt;
                        &lt;/table&gt;
                    &lt;/div&gt;
                &lt;/div&gt;
            ";
        }
        echo "
        &lt;/div&gt;";
    }

}
                                </code></pre>
                            </li>
                        </ol>
                        <p class="description">
                            <strong>MYSQL code: </strong>
                        </p>
                        
                        <pre class="code"><code class="language-mysql">
CREATE TABLE IF NOT EXISTS orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY, user_id INT, fname VARCHAR(20), lname VARCHAR(20), phone VARCHAR(15), email VARCHAR(50)
    , street VARCHAR(40), town VARCHAR(40), state VARCHAR(4), post_code VARCHAR(5), pref_contact VARCHAR(5), card_type VARCHAR(20),
    nameoncard VARCHAR(50), card_number VARCHAR(30), expiry VARCHAR(20), cvv VARCHAR(20), order_cost INT, order_status VARCHAR(20) DEFAULT 'PENDING', order_time DATETIME
);

INSERT INTO orders (user_id, fname, lname, phone, email, street, town, state, post_code, pref_contact, card_type, nameoncard, card_number, expiry, cvv, order_cost, order_time) VALUES 
                                                 ($lastId,'$fname','$lname','$phone','$email','$street','$town','$state','$postCode','$prefContact','$cardType','$nameOnCard','$cardNumber',
                                                 '$expiry','$cvv',$totalCost, CONVERT_TZ(NOW(), @@session.time_zone, '+07:00'));

SELECT order_id FROM orders ORDER BY order_id DESC LIMIT 1;

SELECT order_id, user_id, fname, lname, phone, email, street, town, state, post_code, pref_contact, order_status, order_time FROM orders WHERE order_status = 'ARCHIVED' ORDER BY order_time DESC;
  
SELECT * FROM order_products JOIN products ON order_products.product_id = products.product_id WHERE order_id = $orderId;
                        </code></pre>
                    </div>

                    <div class="feature-desc" id="fea2-content">
                        <h2>Enhancement 2 - User login and user management page</h2>
                        <img src="images/enhancement/user.png" alt=""/>
                        <p class="description">
                            <strong>Enhancement description: </strong>
                        </p>
                        <ol>
                            <li>The index.php page allows the admin or the user to log in. If the input email and password exist in the database, the user will be logged in. If not, the page will ask the user to log in again or register a new account.</li>
                            <li>Users can create an account at the register.php page. If the user use an email that already exists in the database, the page will ask him to login or register a different email.</li>
                            <li>The manager page's control will base on the user type, which can be either 0 (represent the user) or 1 (represent the admin).</li>
                            <li>If user is logged in, the manager.php page will allow the user to:
                                <ol>
                                    <li><strong>Your information: </strong>Edit user's information and upload avatar image</li>
                                    <li><strong>Your cart: </strong>Logged in user can order multiple products at a time via "Your cart" tab. User can select products from the products.php page and add those products to cart. After that, the user need to go back to "Your cart" to adjust products quantity, see total price, and then proceed to checkout with those products in the cart.</li>
                                    <li><strong>Order history: </strong>All complete orders of that user will be displayed with the order ID, order time, order status, product list, and total cost.</li>
                                </ol>
                            </li>
                            <li>Log out</li>
                        </ol>
                        <p class="description">
                            <strong>PHP code: </strong>
                        </p>
                        <ol>
                            <li>
                                processLogin.php:
                                <pre class="code"><code class="language-php">
    require_once("settings.php");
    $conn = @mysqli_connect($host, $user, $password, $sql_db);

    if(!$conn) {
        echo "&lt;p&gt;Oops! Something went wrong! :(&lt;/p&gt;";
    } else {
        $email = htmlspecialchars(trim($_POST["email"]));
        $password = htmlspecialchars(trim($_POST["password"]));

        
        $errors = [];
        $errMsg = "";
        if($email != "admin") {
            if(strlen($email) &lt; 3) {
                array_push($errors,'err_email');
            } else {
                $check = false;
                for($i = 1; $i &lt; strlen($email) - 1; $i++) {
                    if($email[$i] == '@') {
                        $check = true;
                        break;
                    }
                }
                if(!$check) {
                    array_push($errors,'err_email');
                }
            }
        }

        if(!isset($_POST["password"]) || strlen($password) &lt; 3) {
            array_push($errors,'err_pwd');
        }

        
        if(count($errors) != 0) {
            $errString = "";
            for($i = 0; $i &lt; count($errors); $i ++) {
                $errCode = $errors[$i];
                $errString .= "$errCode=1";
                if($i &lt; count($errors) - 1) {
                    $errString .= "&";
                }
            }
            header("Location: index.php?$errString");
        } else {
            $query = "SELECT email, type, password FROM users WHERE email = '$email' AND password = '$password' AND type = ";
            if($email == "admin") {
                $query .= "1";
            } else {
                $query .= "0";
            }
            $query .= ";";
            $result = mysqli_query($conn,$query);
            $userCre = $result -&gt; fetch_assoc();
            if($userCre && $userCre["email"] == $email && ($userCre["type"] == 0 || $userCre["type"] == 1) && $userCre["password"] == $password) {
                $query = "SELECT user_id, fname, lname, phone, email, address, type FROM users WHERE email = '$email' AND password = '$password' AND type = ";
                if($email == "admin") {
                    $query .= "1";
                } else {
                    $query .= "0";
                }
                $query .= ";";
                $result = mysqli_query($conn,$query);
                $user = $result -&gt; fetch_assoc();
                session_start();
                $_SESSION["user"] = $user;
                if($_SESSION["user"] && $_SESSION["user"] != null) {
                    header("Location: home.php");
                } else {
                    echo $user;
                }
            } else {
                header("Location: index.php?err_wrong=1");
            }
        }
    }
                                </code></pre>
                            </li>
                            <li>
                                manager.php:
                                <pre class="code"><code class="language-php">
    function cartItem($isHistory,$userId, $productId, $name, $price, $color, $version, $quantity, $image, $imageType) {
        echo "
        &lt;div class='cart-item'&gt;
            &lt;img src='data:image/$imageType;charset=utf8;base64,$image' alt=''/&gt;
            &lt;div class='cart-item-info'&gt;
                &lt;a class='link-to-product' href='productDesc.php?productId=$productId'&gt;&lt;h3&gt;$name&lt;/h3&gt;&lt;/a&gt;
                &lt;p class='cart-item-price'&gt;&lt;strong&gt;Price: &lt;/strong&gt;&lt;span class='price'&gt;$$price&lt;/span&gt;&lt;/p&gt;
                &lt;p class='cart-item-price'&gt;&lt;strong&gt;Version: &lt;/strong&gt;&lt;span&gt;$version&lt;/span&gt;&lt;/p&gt;
                &lt;p class='cart-item-price'&gt;&lt;strong&gt;Color: &lt;/strong&gt;&lt;span&gt;$color&lt;/span&gt;&lt;/p&gt;
                &lt;div class='cart-item-quantity'&gt;";
                    if(!$isHistory) echo "&lt;a href='incAndDec.php?userId=$userId&productId=$productId&action=dec'&gt;&lt;div&gt;-&lt;/div&gt;&lt;/a&gt;";
                    echo "&lt;strong&gt;x$quantity&lt;/strong&gt;";
                    if(!$isHistory) echo "&lt;a href='incAndDec.php?userId=$userId&productId=$productId&action=inc'&gt;&lt;div&gt;+&lt;/div&gt;&lt;/a&gt;";
                echo "&lt;/div&gt;
            &lt;/div&gt;
            &lt;img class='item-bg' src='data:image/$imageType;charset=utf8;base64,$image' alt=''/&gt;
        &lt;/div&gt;
        ";
    }

    function userInfoTab($conn) {
        if(!$conn) {
            echo "&lt;p&gt;Sth went wrong!:(&lt;/P&gt;";
        } else {
            $userId = $_SESSION["user"]["user_id"];
            $query = "SELECT fname, lname, email, address, phone FROM users WHERE user_id = $userId;";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_array($result);
            $fname = $row["fname"];
            $lname = $row["lname"];
            $email = $row["email"];
            $address = $row["address"];
            $phone = $row["phone"];
            echo "
            &lt;div class='tab' id='info-tab'&gt;
                &lt;h2&gt;Your Information&lt;/h2&gt;
                &lt;form method='POST' action='updateInfo.php?userId=$userId' enctype='multipart/form-data'&gt;
                    &lt;div&gt;
                        &lt;label for='fname'&gt;First name: &lt;/label&gt;
                        &lt;input type='text' name='fname' id='fname' value='$fname' required/&gt;
                    &lt;/div&gt;
                    &lt;div&gt;
                        &lt;label for='lname'&gt;Last name: &lt;/label&gt;
                        &lt;input type='text' name='lname' id='lname' value='$lname' required/&gt;
                    &lt;/div&gt;
                    &lt;div&gt;
                        &lt;label for='email'&gt;Email: &lt;/label&gt;
                        &lt;input type='email' name='email' id='email' value='$email' required/&gt;
                    &lt;/div&gt;
                    &lt;div&gt;
                        &lt;label for='address'&gt;Address: &lt;/label&gt;
                        &lt;input type='text' name='address' id='address' value='$address'/&gt;
                    &lt;/div&gt;
                    &lt;div&gt;
                        &lt;label for='phone'&gt;Phone number: &lt;/label&gt;
                        &lt;input type='text' name='phone' id='phone' value='$phone'/&gt;
                    &lt;/div&gt;
                    &lt;div&gt;
                        &lt;label for='avt'&gt;Avatar: &lt;/label&gt;
                        &lt;input type='file' name='avt' id='avt' accept='image/png, image/gif, image/jpeg'/&gt;
                    &lt;/div&gt;
                    &lt;input type='submit' id='update-btn' class='shop-btn' value='Update'/&gt;
                &lt;/form&gt;
            &lt;/div&gt;";
        }
    }

    function cartTab($conn, $userId) {
        if(!$conn) {
            echo "&lt;p&gt;Sth went wrong!:(&lt;/P&gt;";
        } else {
            $query = "SELECT * FROM cart JOIN products ON cart.product_id = products.product_id WHERE user_id = $userId;";
            $result = mysqli_query($conn, $query);
            // $cart = mysqli_fetch_array($result);
            // $itemCount = count($cart);
            $cart = [];
            $itemCount = 0;
            while($row = mysqli_fetch_assoc($result)) {
                array_push($cart, $row);
                $itemCount += $row["quantity"];
            }
        echo "
        
        &lt;div class='tab' id='cart-tab'&gt;
            &lt;h2&gt;Your Cart ($itemCount item";
            if($itemCount &gt; 1) echo "s";
            echo ")&lt;/h2&gt;
            &lt;div id='cart-list'&gt;
                &lt;ul&gt;";
                $total = 0;
                $i = 0;
                while($i &lt; count($cart)) {
                    echo "
                    &lt;li&gt;";
                    cartItem(false,$_SESSION["user"]["user_id"],$cart[$i]["product_id"], $cart[$i]["pname"],$cart[$i]["pprice"],$cart[$i]["color"],$cart[$i]["version"],$cart[$i]["quantity"],base64_encode($cart[$i]["pimage"]),$cart[$i]["pimagetype"]);
                    $total += $cart[$i]["pprice"];
                    if(strpos($cart[$i]["version"],'fea1') !== false) {
                        $total += 50;
                    }
                    if(strpos($cart[$i]["version"],'fea2') !== false) {
                        $total += 100;
                    }
                    if(strpos($cart[$i]["version"],'fea3') !== false) {
                        $total += 200;
                    }
                    $total *= $cart[$i]["quantity"];
                    echo "&lt;/li&gt;
                    ";
                    $i++;
                }
                echo "&lt;/ul&gt;
                &lt;hr/&gt;
                &lt;h3&gt;Total: &lt;span class='price'&gt;$$total&lt;/span&gt;&lt;/h3&gt;
            &lt;/div&gt;";
            if($itemCount &gt; 0) echo "&lt;a class='shop-btn' href='payment.php?userId=$userId'&gt;Go to Checkout&lt;/a&gt;";
        echo "&lt;/div&gt;
        ";
        }
    }

    function historyTab($conn, $userId) {
        if(!$conn) {
            echo "&lt;p&gt;Sth went wrong!:(&lt;/P&gt;";
        } else {
            $query = "SELECT * FROM orders WHERE user_id = $userId ORDER BY order_time DESC   ;";
            $result = mysqli_query($conn, $query);
            $history = [];
            while($row = mysqli_fetch_array($result)) {
                array_push($history, $row);
            }
            echo "
            
            &lt;div class='tab' id='history-tab'&gt;
            &lt;h2&gt;Order History&lt;/h2&gt;
            &lt;ul&gt;
            ";
            $i= 0;
            while($i &lt; count($history)) {
                $orderId = $history[$i]["order_id"];
                $date = $history[$i]["order_time"];
                $status = $history[$i]["order_status"];
                echo "
                &lt;li&gt;
                    &lt;div class='prev-order'&gt;    
                        &lt;div class='general-order-info'&gt;
                            &lt;h3&gt;Order ID: $orderId&lt;/h3&gt;
                            &lt;p&gt;&lt;strong&gt;Date: &lt;/strong&gt;$date&lt;/p&gt;
                            &lt;p&gt;&lt;strong&gt;Status: &lt;/strong&gt;&lt;span class='$status'&gt;$status&lt;/span&gt;&lt;/p&gt;
                        &lt;/div&gt;
                        &lt;hr/&gt;
                        &lt;div class='prev-order-list'&gt;
                            &lt;ul&gt;
                ";
                $historyQuery = "SELECT * FROM order_products JOIN products ON order_products.product_id = products.product_id WHERE order_id = $orderId;";
                $historyResult = mysqli_query($conn, $historyQuery);
                
                $historyItem = [];
                while($row = mysqli_fetch_array($historyResult)) {
                    array_push($historyItem, $row);
                }
                $total = 0;
                $j = 0;
                while($j &lt; count($historyItem)) {
                    cartItem(true,$_SESSION["user"]["user_id"],$historyItem[$j]["product_id"], $historyItem[$j]["pname"],$historyItem[$j]["pprice"],$historyItem[$j]["color"],$historyItem[$j]["version"],$historyItem[$j]["quantity"],base64_encode($historyItem[$j]["pimage"]),$historyItem[$j]["pimagetype"]);
                   
                    $total += $historyItem[$j]["pprice"];
                    if(strpos($historyItem[$j]["version"],'fea1') !== false) {
                        $total += 50;
                    }
                    if(strpos($historyItem[$j]["version"],'fea2') !== false) {
                        $total += 100;
                    }
                    if(strpos($historyItem[$j]["version"],'fea3') !== false) {
                        $total += 200;
                    }
                    $total *= $historyItem[$j]["quantity"];
                    $j++;
                }

                echo "
                            &lt;/ul&gt;
                        &lt;/div&gt;
                        &lt;hr/&gt;
                        &lt;h3&gt;Total: &lt;span class='price'&gt;$$total&lt;/span&gt;&lt;/h3&gt;
                    &lt;/div&gt;
                &lt;/li&gt;
                ";
                $i++;
            }
            echo "
            &lt;/ul&gt;
            &lt;/div&gt;
            ";
        }
    }
                                </code></pre>
                            </li>
                        </ol>
                        <p class="description">
                            <strong>MYSQL code: </strong>
                        </p>
                        
                        <pre class="code"><code class="language-mysql">
UPDATE users SET fname = '$fname', lname = '$lname', email = '$email', address = '$address',
    phone = '$phone', avatar = '$userImageContent', avatar_type = '$userImageType' WHERE user_id = $userId;

SELECT * FROM cart JOIN products ON cart.product_id = products.product_id WHERE user_id = $userId;

SELECT * FROM orders WHERE user_id = $userId ORDER BY order_time DESC;

SELECT * FROM order_products JOIN products ON order_products.product_id = products.product_id WHERE order_id = $orderId;
                        </code></pre>
                    </div>
                </div>
                </section>
            </div>
        </main>
        <?php
            footer_code();
        ?>
    </body>
</html>