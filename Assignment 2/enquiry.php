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
            header_code(2);
        ?>
        <main>
            <div id="container" class="enquiry">
                <div id="enquiry-form">
                    <!-- Information is sent to payment.php for checking -->
                    <form action="payment.php" method="post" novalidate>
                        <h1>Enquiry Information</h1>
                        <?php
                        if(isset($_GET["insufficient"])) {
                            echo "<p class='error'>Every fields are 
                            required except additional information</p>";
                        }
                        if($_SESSION["user"] && $_SESSION["user"] != null) {
                            echo "<p class='error'>You have logged in, please order via <a href='manager.php?page=1'>your profile</a></p>";
                        }
                        ?>
                        <div id="input-fields">
                            <div>
                                <div class="group">
                                    <input type="text" class="text-input" id="1stname" name="fname" required="required" />
                                    <span class="highlight"></span>
                                    <span class="bar"></span>
                                    <label for="1stname"><strong>First Name: </strong></label>
                                </div>
                                <div class="group">
                                    <input type="text" id="lastname" name="lname" required="required" />
                                    <span class="highlight"></span>
                                    <span class="bar"></span>
                                    <label for="lastname"><strong>Last Name: </strong></label>
                                </div>
                                <div class="group">
                                    <input type="email" id="email" name="email" required="required" />
                                    <span class="highlight"></span>
                                    <span class="bar"></span>
                                    <label for="email"><strong>Email: </strong></label>
                                </div>
                                <fieldset>
                                    <legend><strong>Address</strong></legend>
                                    <div class="group">
                                        <input type="text" id="street" name="street" required="required" />
                                        <span class="highlight"></span>
                                        <span class="bar"></span>
                                        <label for="street"><strong>Street Address: </strong></label>
                                    </div>
                                    <div class="group">
                                        <input type="text" id="town" name="town" required="required" />
                                        <span class="highlight"></span>
                                        <span class="bar"></span>
                                        <label for="town"><strong>Suburb/Town: </strong></label>
                                    </div>
                                    <div class="other-group select">
                                        <label for="state"><strong>State: </strong></label>
                                        <select id="state" name="state" required="required">
                                            <option value="">Select</option>
                                            <option value="vic">VIC</option>
                                            <option value="nsw">NSW</option>
                                            <option value="qld">QLD</option>
                                            <option value="nt">NT</option>
                                            <option value="wa">WA</option>
                                            <option value="sa">SA</option>
                                            <option value="tas">TAS</option>
                                            <option value="act">ACT</option>
                                        </select>
                                    </div>
                                </fieldset>
                                <div class="group">
                                    <input type="text" id="phonenumber" name="phone" required="required" />
                                    <span class="highlight"></span>
                                    <span class="bar"></span>
                                    <label for="phonenumber"><strong>Phone Number: </strong></label>
                                </div>
                                <div class="other-group radio-group">
                                    <p id="prefcontact"><strong>Preffered Contact: </strong></p>
                                    <label class="option-group">Email
                                        <input type="radio" id="prefemail" name="prefcontact" value="email" checked="checked"/>
                                        <span class="radio-checkmark"></span>
                                    </label>
                                    <label class="option-group">Post
                                        <input type="radio" id="post" name="prefcontact" value="post"/>
                                        <span class="radio-checkmark"></span>
                                    </label>
                                    <label class="option-group">Phone
                                        <input type="radio" id="phone" name="prefcontact" value="phone"/>
                                        <span class="radio-checkmark"></span>
                                    </label>
                                </div>
                            </div>
                            <div>
                                <div class="group">
                                    <input type="text" id="postcode" name="postcode" pattern="[0-9]{4}" required="required" />
                                    <span class="highlight"></span>
                                    <span class="bar"></span>
                                    <label for="postcode"><strong>Post Code: </strong></label>
                                </div>
                                <div class="other-group select">
                                    <label for="product"><strong>Product: </strong></label>
                                    <select id="product" name="product" required="required">
                                        <option value="">Select</option>
                                        <?php
                                            require_once("settings.php");
                                            $conn = @mysqli_connect($host, $user, $password, $sql_db);

                                            if(!$conn) {
                                                echo "<p>Sth went wrong</p>";
                                            } else {
                                                $query = "SELECT * FROM products ORDER BY pdate DESC";
                                                $result = mysqli_query($conn,$query);
                                                $options = [];
                                                while($row = mysqli_fetch_array($result)) {
                                                    array_push($options, $row);
                                                }
                                                $i = 0;
                                                while($i < count($options)) {
                                                    $id = $options[$i]["product_id"];
                                                    $name = $options[$i]["pname"];
                                                    $price = $options[$i]["pprice"];
                                                    echo "<option value='$id'>Price: <span class='price'>$$price</span>, Name: $name</option>";
                                                    $i++;
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                                <?php
                                    if(!$_SESSION["user"] || $_SESSION["user"] == null) {
                                        echo "<p>Please <a href='index.php'>login</a> to order more than 1 product at a time.</p>";
                                    }
                                ?>
                                <div class="group">
                                    <input type="number" id="quantity" name="quantity" required="required" />
                                    <span class="highlight"></span>
                                    <span class="bar"></span>
                                    <label for="quantity"><strong>Quantity: </strong></label>
                                </div>
                                <div  class="other-group box-group">
                                    <label><strong>Version: </strong></label>
                                    <label for="fea1" class="option-group">Elite
                                        <input type="checkbox" id="fea1" name="fea1" value="fea1" checked="checked"/>
                                        <span class="box-checkmark"></span>
                                    </label>
                                    <label for="fea2" class="option-group">Premium
                                        <input type="checkbox" id="fea2" name="fea2" value="fea2"/>
                                        <span class="box-checkmark"></span>
                                    </label>
                                    <label for="fea3" class="option-group">Luxury
                                        <input type="checkbox" id="fea3" name="fea3" value="fea3"/>
                                        <span class="box-checkmark"></span>
                                    </label>
                                </div>
                                <div  class="other-group radio-group">
                                    <label><strong>Color: </strong></label>
                                    <label for="red" class="option-group">Red
                                        <input type="radio" id="red" name="color" value="red" checked="checked"/>
                                        <span class="radio-checkmark"></span>
                                    </label>
                                    <label for="green" class="option-group">Green
                                        <input type="radio" id="green" name="color" value="green"/>
                                        <span class="radio-checkmark"></span>
                                    </label>
                                    <label for="brown" class="option-group">Brown
                                        <input type="radio" id="brown" name="color" value="brown"/>
                                        <span class="radio-checkmark"></span>
                                    </label>
                                </div>
                                <textarea name="addition" rows="5" placeholder="Describe any additional information here..."></textarea>
                            </div>
                        </div>
                        <input class="shop-btn" type="submit" value="Place Order"
                        <?php
                            // If user logged in, user cannot submit through Enquiry
                            // and only order through Cart
                            if($_SESSION["user"] && $_SESSION["user"] != null) {
                                echo "disabled";
                            }
                        ?>
                        />
                    </form>
                </div>
            </div>
        </main>
        <footer>
            <?php
                footer_code();
            ?>
    </body>
</html>