<?php

require_once 'login.php';

if($_SERVER["REQUEST_METHOD"] == "POST") {

    if(isset($_POST["sitename"]) && isset($_POST["sitename_header"]) && isset($_POST["rating_stars_count"]) &&
    isset($_POST["rating_star_empty"]) && isset($_POST["rating_star_filled"]) && isset($_POST["rating_star_filled_small"]) &&
    isset($_POST["view_service_rating_button"]) && isset($_POST["footer_text"])) {

        $json = json_encode(array(
            "sitename" => $_POST["sitename"],
            "sitename_header" => $_POST["sitename_header"],
            "rating_stars_count" => $_POST["rating_stars_count"],
            "rating_star_empty" => $_POST["rating_star_empty"],
            "rating_star_filled" => $_POST["rating_star_filled"],
            "rating_star_filled_small" => $_POST["rating_star_filled_small"],
            "view_service_rating_button" =>$_POST["view_service_rating_button"],
            "footer_text" => $_POST["footer_text"]
        ));

        file_put_contents("../config/site.json", $json);

        echo '<script>alert("Saved!")</script>';

    }
}

$site_config = json_decode(file_get_contents("../config/site.json"));

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
        <form id="site-edit" class="form" action="./site-edit.php" method="POST">
            <span class="form-title">Редактировать сайт</span>
            <div class="form-inputs">
                <label class="form-label">Имя сайта<span class="form-label-needed">*</span>:</label>
                <input type="text" name="sitename" class="form-input" value="<?php echo $site_config->sitename; ?>" required>
                <label class="form-label">Хеадер сайта<span class="form-label-needed">*</span>:</label>
                <input type="text" name="sitename_header" class="form-input" value="<?php echo $site_config->sitename_header; ?>" required>
                <hr>
                <label class="form-label">Кол-во звезд<span class="form-label-needed">*</span>:</label>
                <input type="number" name="rating_stars_count" class="form-input" value="<?php echo $site_config->rating_stars_count; ?>" required>
                <label class="form-label">Звезда рейтинга пустая<span class="form-label-needed">*</span>:</label>
                <input type="text" name="rating_star_empty" class="form-input" value="<?php echo $site_config->rating_star_empty; ?>" required>
                <label class="form-label">Звезда рейтинга заполненная<span class="form-label-needed">*</span>:</label>
                <input type="text" name="rating_star_filled" class="form-input" value="<?php echo $site_config->rating_star_filled; ?>" required>
                <label class="form-label">Звезда рейтинга заполенная на половину<span class="form-label-needed">*</span>:</label>
                <input type="text" name="rating_star_filled_small" class="form-input" value="<?php echo $site_config->rating_star_filled_small; ?>" required>
                <hr>
                <label class="form-label">Кнопка "смотреть отзывы"<span class="form-label-needed">*</span>:</label>
                <input type="text" name="view_service_rating_button" class="form-input" value="<?php echo $site_config->view_service_rating_button; ?>" required>
                <hr>
                <label class="form-label">Копирайт<span class="form-label-needed">*</span>:</label>
                <input type="text" name="footer_text" class="form-input" value="<?php echo $site_config->footer_text; ?>" required>
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