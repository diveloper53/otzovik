<?php

require_once 'login.php';

$mysql_config = json_decode(file_get_contents("../config/mysql.json"));

$database = mysqli_connect(
    $mysql_config->mysql_hostname,
    $mysql_config->mysql_username,
    $mysql_config->mysql_password,
    $mysql_config->mysql_database,
    $mysql_config->mysql_port
);

if(isset($_GET["action"]) && isset($_GET["service"]) && isset($_GET["comment_un"]) && isset($_GET["comment_dt"])) {
    if($_GET["action"] == "delete") {

        echo '<script>
            confirm("Please confirm deleting comment")
            ? window.location.href = "./edit-services.php?action=confirm-delete&service=' . $_GET["service"] . '&comment_un=' . $_GET["comment_un"] . '&comment_dt=' . $_GET["comment_dt"] . '"
            : window.location.href = "./edit-services.php";
        </script>
        <noscript>
            <h1>Please confirm deleting:</h1>
            <a href="./edit-services.php?action=confirm-delete&service=' . $_GET["service"] . '&comment_un=' . $_GET["comment_un"] . '&comment_dt=' . $_GET["comment_dt"] . '">Confirm</a>
            <a href="./edit-services.php">Cancel</a>
        </noscript>';

    }
    if($_GET["action"] == "confirm-delete") {

        $username = mysqli_real_escape_string($database, trim($_GET['comment_un']));
        $date = mysqli_real_escape_string($database, trim($_GET['comment_dt']));

        mysqli_query($database, "DELETE FROM `" . $_GET["service"] . "_comments` WHERE `username`=\"$username\" AND `date`=\"$date\"");

        unlink("../$name/index.php");
        unlink("../$name/logo.png");
        rmdir("../$name");

        echo '<script>alert("Successfully deleted ' . $name . '!"); window.location.href = "./edit-services.php";</script>';
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
    <link rel="stylesheet" href="../templates/styles/stars.css">
    <title><?php echo $site_config->sitename; ?> — ADMIN PANEL</title>
</head>
<body>
    <header>
        <a class="sitename" href="./index.php"><?php echo $site_config->sitename_header; ?> — ADMIN PANEL</a>
    </header>
    <hr>
    <div class="services-list">
        <span class="form-title">Редактировать отзывы на <?php echo $_GET["service"] ?></span>
        <a class="service-button create-service-button" href="./create-service.php">+ Добавить отзыв +</a>

        <?php
        
        // Comments pre-load

        $comments_data = mysqli_query($database, "SELECT * FROM `" . $_GET["service"] . "_comments`");

        while($comment = $comments_data->fetch_assoc()) {

            ?>

            <div class="service">
                <div class="service-info">
                <span class="form-title"><?php echo $comment["username"]; ?></span>
                </div>
                <div class="service-actions">
                    <a class="service-button" href="./comment-edit.php?service=<?php echo $_GET["service"]; ?>&comment_un=<?php echo $comment["username"]; ?>&comment_dt=<?php echo $comment["date"]; ?>">✏ Редактировать ✏</a>
                    <a class="service-button" href="./comments-edit.php?action=delete&service=<?php echo $_GET["service"]; ?>&comment_un=<?php echo $comment["username"]; ?>&comment_dt=<?php echo $comment["date"]; ?>">🗑 Удалить 🗑</a>
                </div>
            </div>

            <?php
        }

        ?>
    </div>
    <hr>
    <footer>
        <?php echo $site_config->footer_text; ?>
    </footer>
</body>
</html>