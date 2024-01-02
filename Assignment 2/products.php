<?php
    include("head.inc");
    include("header.inc");
    include("footer.inc");
    include("galleryItem.inc");
    require_once("settings.php");
    $conn = @mysqli_connect($host, $user, $password, $sql_db);
?>
<!DOCTYPE html>
<html lang="en">
    <?php
        head_code();
    ?>
    <body>
        <?php
            header_code(1);
        ?>
        <main>
            <div id="container">
                <section>
                    <div id="introduction">
                        <div id="intro-background" class="intro-products">
                            <div id=intro-left>
                                <h2>Our products</h2>
                                <img class="divider" src="images/divider.png" alt=""/>
                                <div class="intro-desc">
                                    <p><i>BRUHHH is a shop that sell smoking products for healthier life style.</i></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form id="search-box" method='POST' action='products.php'>
                        <input type="text" name="searchkey" id="searchkey" placeholder="What are you looking for? ..."/>
                        <div id="filter-box">
                        <select id="order" class="order" name="order">
                            <option value="old-up">Latest arrival</option>
                            <option value="old-down">Earliest arrival</option>
                            <option value="price-up">Price: Low to high</option>
                            <option value="price-down">Price: High to low</option>
                            <option value="name-up">A-Z</option>
                            <option value="name-down">Z-A</option>
                        </select>
                        </div>
                        <button id="search-btn" type="submit">
                            <img src="images/search.svg" alt="">
                        </button>
                    </form>
                </section>
                <section>
                    <div id="product-list">
                        <?php
                            if(!$conn) {
                                echo "<p>Oops! Something went wrong! :(</p>";
                            } else {
                                $orderBy = "pdate DESC;";
                                $query = "SELECT * FROM products ";
                                if(isset($_POST["searchkey"])) {
                                    $searchKey = $_POST["searchkey"];
                                    $query .= "WHERE pname LIKE '%$searchKey%' ";
                                }
                                if(isset($_POST["order"])) {
                                    switch($_POST["order"]) {
                                        case 'price-up':
                                            $orderBy = "pprice ASC";
                                            break; 
                                        case 'price-down':
                                            $orderBy = "pprice DESC";
                                            break; 
                                        case 'old-up':
                                            $orderBy = "pdate DESC";
                                            break; 
                                        case 'old-down':
                                            $orderBy = "pdate ASC";
                                            break; 
                                        case 'name-up':
                                            $orderBy = "pname ASC";
                                            break; 
                                        case 'name-down':
                                            $orderBy = "pname DESC";
                                            break;
                                    }
                                }

                                $query .= "ORDER BY $orderBy;";
                                $result = mysqli_query($conn, $query);
                                for($i = 0; $i < mysqli_num_rows($result); $i++) {
                                    $id = $i + 1;
                                    if($i == 0) {
                                        echo "<input type='radio' id='page-btn-$id' name='page-btn' checked='checked'/>";
                                    } else {
                                        echo "<input type='radio' id='page-btn-$id' name='page-btn'/>";
                                    }
                                }
                            }
                            if(isset($_POST["searchkey"]) || isset($_POST["order"])) {
                                $searchMsg = "<p class='search-msg'>Search result for ";
                                if(isset($_POST["searchkey"]) && $_POST["searchkey"] != "") {
                                    $searchMsg .= '"'.$_POST["searchkey"].'" ';
                                }
                                if(isset($_POST["order"])) {
                                    $searchMsg .= "filter by ";
                                    switch($_POST["order"]) {
                                        case 'price-up':
                                            $searchMsg .= "Price: Low to high.";
                                            break; 
                                        case 'price-down':
                                            $searchMsg .= "Price: High to low.";
                                            break; 
                                        case 'old-up':
                                            $searchMsg .= "Latest arrival.";
                                            break; 
                                        case 'old-down':
                                            $searchMsg .= "Earliest arrival.";
                                            break; 
                                        case 'name-up':
                                            $searchMsg .= "A-Z.";
                                            break; 
                                        case 'name-down':
                                            $searchMsg .= "Z-A.";
                                            break;
                                    }
                                }
                                $searchMsg .= "</p>";
                                echo $searchMsg;
                            }
                        ?>
                        <div id="sec3">
                            <?php
                                if(mysqli_num_rows($result) > 0) {
                                    $rowIndex = 1;
                                    $pageIndex = 1;
                                    $rowLast = false;

                                    while($row = mysqli_fetch_assoc($result)) {

                                        // The last item in every row in the gallery has a different animation
                                        if($rowIndex % 3 == 0) {
                                            $rowLast = true;
                                        } else {
                                            $rowLast = false;
                                        }
                                        
                                        // Every item-list contains 9 items is located in a <ul> tag
                                        if($rowIndex % 9 == 1) {
                                            echo "<ul class='item-list page$pageIndex'>";
                                            $pageIndex += 1;
                                        }
                                        galleryItem($rowLast, $row["product_id"],$row["pname"],$row["pdesc"],
                                                    $row["pprice"],base64_encode($row["pimage"]),$row["pimagetype"]);
                                        if($rowIndex % 9 == 0) {
                                            echo "</ul>";
                                        }
                                        $rowIndex += 1;
                                    }

                                    // Avoid redundant page 
                                    if($rowIndex % 9 != 0) {
                                        $pageIndex -= 1;
                                    }
                                }
                            ?>
                        </div>
                        <div id="page-btn">
                            <?php
                                if(mysqli_num_rows($result) > 0) {
                                    for($i = 1; $i <= $pageIndex; $i++) {
                                        echo "
                                            <label class='option-group' for='page-btn-$i' id='label-$i'>
                                                <span class='radio-checkmark'></span>
                                            </label>
                                        ";
                                    }
                                }
                            ?>
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