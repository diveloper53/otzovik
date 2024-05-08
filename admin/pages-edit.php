<?php

require_once 'login.php';

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(!isset($_GET["form"])) {
        header("Location: ./");
        exit;
    }

    if($_GET["form"] == "main") {
        if(isset($_POST["main.css"]) && isset($_POST["stars.css"])) {
            file_put_contents("../templates/styles/main.css", $_POST["main.css"]);
            file_put_contents("../templates/styles/stars.css", $_POST["stars.css"]);

            echo '<script>alert("Saved!")</script>';
        }
    }

    if($_GET["form"] == "index") {
        if(isset($_POST["index.html"]) && isset($_POST["service.html"]) && isset($_POST["index.css"])) {
            file_put_contents("../templates/html/index/index.html", $_POST["index.html"]);
            file_put_contents("../templates/html/index/service.html", $_POST["service.html"]);
            file_put_contents("../templates/styles/index.css", $_POST["index.css"]);

            echo '<script>alert("Saved!")</script>';
        }
    }

    if($_GET["form"] == "service") {
        if(isset($_POST["index.html"]) && isset($_POST["comment.html"]) && isset($_POST["service.css"])) {
            file_put_contents("../templates/html/index/index.html", $_POST["index.html"]);
            file_put_contents("../templates/html/index/comment.html", $_POST["comment.html"]);
            file_put_contents("../templates/styles/service.css", $_POST["service.css"]);

            echo '<script>alert("Saved!")</script>';
        }
    }

    if($_GET["form"] == "main") {
        if(isset($_POST["admin.css"])) {
            file_put_contents("../templates/styles/admin.css", $_POST["admin.css"]);

            echo '<script>alert("Saved!")</script>';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../templates/styles/main.css">
    <link rel="stylesheet" href="../templates/styles/admin.css">
    <title><?php echo $site_config->sitename; ?> — ADMIN PANEL</title>
</head>
<body>
    <header>
        <a class="sitename" href="./index.php"><?php echo $site_config->sitename_header; ?> — ADMIN PANEL</a>
    </header>
    <hr>
    <div class="forms">
        <form id="main-edit" class="form" action="./pages-edit.php?form=main#main-edit" method="POST">
                <span class="form-title">Основное</span>
                <div class="form-inputs">
                    <label class="form-label">MAIN.CSS<span class="form-label-needed">*</span>:</label>
                    <textarea type="text" name="main.css" class="form-input" style="height: 500px" required><?php echo str_replace(array("<", ">"), array("&lt", "&gt"), file_get_contents("../templates/styles/main.css")); ?></textarea>
                    <label class="form-label">STARS.CSS<span class="form-label-needed">*</span>:</label>
                    <textarea type="text" name="stars.css" class="form-input" style="height: 500px" required><?php echo str_replace(array("<", ">"), array("&lt", "&gt"), file_get_contents("../templates/styles/main.css")); ?></textarea>
                    <input type="submit" class="form-input form-submit" value="Сохранить">
                </div>
        </form>
        <form id="index-edit" class="form" action="./pages-edit.php?form=index#index-edit" method="POST">
            <span class="form-title">Редактировать INDEX</span>
            <div class="form-inputs">
                <label class="form-label">INDEX.HTML<span class="form-label-needed">*</span>:</label>
                <textarea type="text" name="index.html" class="form-input" style="height: 500px" required><?php echo str_replace(array("<", ">"), array("&lt", "&gt"), file_get_contents("../templates/html/index/index.html")); ?></textarea>
                <label class="form-label">SERVICE.HTML<span class="form-label-needed">*</span>:</label>
                <textarea type="text" name="service.html" class="form-input" style="height: 500px" required><?php echo str_replace(array("<", ">"), array("&lt", "&gt"), file_get_contents("../templates/html/index/service.html")); ?></textarea>
                <label class="form-label">INDEX.CSS<span class="form-label-needed">*</span>:</label>
                <textarea type="text" name="index.css" class="form-input" style="height: 500px" required><?php echo str_replace(array("<", ">"), array("&lt", "&gt"), file_get_contents("../templates/styles/index.css")); ?></textarea>
                <input type="submit" class="form-input form-submit" value="Сохранить">
            </div>
        </form>
        <form id="edit-service" class="form" action="./pages-edit.php?form=service#edit-service" method="POST">
            <span class="form-title">Редактировать SERVICE</span>
            <div class="form-inputs">
                <label class="form-label">INDEX.HTML<span class="form-label-needed">*</span>:</label>
                <textarea type="text" name="index.html" class="form-input" style="height: 500px" required><?php echo str_replace(array("<", ">"), array("&lt", "&gt"), file_get_contents("../templates/html/service/index.html")); ?></textarea>
                <label class="form-label">COMMENT.HTML<span class="form-label-needed">*</span>:</label>
                <textarea type="text" name="comment.html" class="form-input" style="height: 500px" required><?php echo str_replace(array("<", ">"), array("&lt", "&gt"), file_get_contents("../templates/html/service/comment.html")); ?></textarea>
                <label class="form-label">SERVICE.CSS<span class="form-label-needed">*</span>:</label>
                <textarea type="text" name="service.css" class="form-input" style="height: 500px" required><?php echo str_replace(array("<", ">"), array("&lt", "&gt"), file_get_contents("../templates/styles/service.css")); ?></textarea>
                <input type="submit" class="form-input form-submit" value="Сохранить">
            </div>
        </form>
        <form id="admin-edit" class="form" action="./pages-edit.php?form=admin#admin-edit" method="POST">
            <span class="form-title">Редактировать ADMIN</span>
            <div class="form-inputs">
                <label class="form-label">ADMIN.CSS<span class="form-label-needed">*</span>:</label>
                <textarea type="text" name="admin.css" class="form-input" style="height: 500px" required><?php echo str_replace(array("<", ">"), array("&lt", "&gt"), file_get_contents("../templates/styles/admin.css")); ?></textarea>
                <input type="submit" class="form-input form-submit" value="Сохранить">
            </div>
        </form>
    </div>
    <hr>
    <footer>
        <?php echo $site_config->footer_text; ?>
    </footer>
</body>
</html>