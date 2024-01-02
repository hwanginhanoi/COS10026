<?php
    include("head.inc");
    include("header.inc");
    include("footer.inc");
    include("galleryItem.inc");
    require_once("settings.php");
?>
<!DOCTYPE html>
<html lang="en">
    <?php
        head_code();
    ?>
    <body>
        <?php
            header_code(3);
        ?>
        <main>
            <div id="container">
                <section>
                    <div id="hoang-details" class="member-details">
                        <div class="wrapper">
                            <div class="details-container">
                                <div class="info-container">
                                    <div class="member-info">
                                        <dl class="single-info">
                                            <dt><strong>Name: </strong></dt>
                                            <dd>Luu Tuan Hoang</dd>
                                        </dl>
                                        <dl class="single-info">
                                            <dt><strong>Student ID: </strong></dt>
                                            <dd>104180391</dd>
                                        </dl>
                                        <dl class="single-info">
                                            <dt><strong>Course: </strong></dt>
                                            <dd>Bachelor of Computer Science</dd>
                                        </dl>
                                        <dl class="single-info">
                                            <dt><strong>Email: </strong></dt>
                                            <dd><a href="104180391@swin.edu.au" class="email-link">104180391@swin.edu.au</a></dd>
                                        </dl>
                                    </div>
                                </div>
                                <figure class="figure">
                                    <div class="img-container">
                                        <img src="images/about/hoang.jpg" alt="Hoang"/>
                                    </div>
                                    <figcaption><h2>HOANG</h2></figcaption>
                                </figure>
                            </div>
                            <div class="table-container">
                                <table>
                                    <tr>
                                        <th></th>
                                        <th>Morning</th>
                                        <th>Afternoon</th>
                                    </tr>
                                    <tr class="table-row">
                                        <td><strong>Monday</strong></td>
                                        <td>Free</td>
                                        <td>Free</td>
                                    </tr>
                                    <tr class="table-row">
                                        <td><strong>Tuesday</strong></td>
                                        <td>Free</td>
                                        <td>Free</td>
                                    </tr>
                                    <tr class="table-row">
                                        <td><strong>Wednesday</strong></td>
                                        <td>COS10006 - Network and Switching <br/>8a.m - 12a.m</td>
                                        <td>Vovinam <br/>1p.m-4p.m</td>
                                    </tr>
                                    <tr class="table-row">
                                        <td><strong>Thursday</strong></td>
                                        <td>COS10026 - Computing Technology Inquiry Project <br/>8a.m - 12a.m </td>
                                        <td>COS10022 - Data Science Principle <br/>1p.m - 5p.m</td>
                                    </tr>
                                    <tr class="table-row">
                                        <td><strong>Friday</strong></td>
                                        <td>Free</td>
                                        <td>Free</td>
                                    </tr>
                                    <tr class="table-row">
                                        <td><strong>Saturday</strong></td>
                                        <td>Free</td>
                                        <td>Free</td>
                                    </tr>
                                    <tr class="table-row">
                                        <td class="sunday"><strong>Sunday</strong></td>
                                        <td class="sunday-td">Free</td>
                                        <td class="sunday-td">Free</td>
                                    </tr>
                                </table>
                            </div>
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