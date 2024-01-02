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
            header_code(3);
        ?>
        <main>
            <div id="container">
                <section>
                    <div id="about-menu">
                        <div id="first-row">
                            <a href="mem2.php" id="khue" class="about-menu-item">
                                <h1>Hoang Minh Khue</h1>
                            </a>
                            <a href="mem1.php" id="nhi" class="about-menu-item">
                                <h1>Tran Yen Nhi</h1>
                            </a>
                            <a href="mem3.php" id="hoang" class="about-menu-item">
                                <h1>Luu Tuan Hoang</h1>
                            </a>
                        </div>
                        <div id="second-row">
                            <a href="mem4.php" id="bach" class="about-menu-item">
                                <h1>Le Nho Bach</h1>
                            </a>
                            <a href="mem5.php" id="kien" class="about-menu-item">
                                <h1>Le Tran Bao Kien</h1>
                            </a>
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