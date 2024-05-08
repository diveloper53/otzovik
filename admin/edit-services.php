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

if(isset($_GET["action"]) && isset($_GET["service"])) {
    if($_GET["action"] == "delete") {
        
        echo '<script>
            confirm("Please confirm deleting ' . $_GET["service"] . '")
            ? window.location.href = "./edit-services.php?action=confirm-delete&service=' . $_GET["service"] . '"
            : window.location.href = "./edit-services.php";
        </script>
        <noscript>
            <h1>Please confirm deleting ' . $_GET["service"] . ':</h1>
            <a href="./edit-services.php?action=confirm-delete&service=' . $_GET["service"] . '">Confirm</a>
            <a href="./edit-services.php">Cancel</a>
        </noscript>';

    }
    if($_GET["action"] == "confirm-delete") {

        $name = mysqli_real_escape_string($database, trim($_GET['service']));

        mysqli_query($database, "DELETE FROM `services` WHERE `name`=\"$name\"");
        mysqli_query($database, "DROP TABLE " . $name . "_comments");

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
        <a class="service-button create-service-button" href="./create-service.php">+ Создать сервис +</a>

        <?php
        
        // Services pre-load

        $services_data = mysqli_query($database, "SELECT * FROM `services`");

        while($service = $services_data->fetch_assoc()) {

            ?>

            <div class="service">
                <div class="service-info">
                <img src="../<?php echo $service["name"]; ?>/logo.png" class="service-logo">
                <span class="rating-stars">
                    <?php
                    
                    for($i = 0; $i < 5; $i++, $service["rating"]--) {
                        if($service["rating"] >= 1)
                            echo $site_config->rating_star_filled;
                        else if($service["rating"] >= 0.5)
                            echo $site_config->rating_star_filled_small;
                        else
                            echo $site_config->rating_star_empty;
                    }
                    
                    ?>
                </span>
                </div>
                <div class="service-actions">
                    <a class="service-button" href="./edit-service.php?service=<?php echo $service["name"]; ?>">✏ Редактировать ✏</a>
                    <a class="service-button" href="./comments-edit.php?service=<?php echo $service["name"]; ?>">✉ Отзывы ✉</a>
                    <a class="service-button" href="./edit-services.php?action=delete&service=<?php echo $service["name"]; ?>">🗑 Удалить 🗑</a>
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