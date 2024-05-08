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

$service_info = mysqli_query($database, "SELECT * FROM `services` WHERE `name`=\"" . mysqli_real_escape_string($database, $_GET["service"]) . "\"")->fetch_assoc();

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST["username"]) && isset($_POST["email"]) && isset("text") && isset("rating")) {

        $username = mysqli_real_escape_string($database, trim($_POST["username"]));
        $email = mysqli_real_escape_string($database, $_POST["email"]);
        $text = mysqli_real_escape_string($database, $_POST["text"]);
        $rating = mysqli_real_escape_string($database, $_POST["rating"]);
        $date = time();
        
        mysqli_query($database, "INSERT INTO `" . $_GET["service"] . "_comments` (`username`, `email`, `text`, `rating`) VALUES (\"$username\", \"$email\", \"$text\", $rating)");
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
        <form id="site-edit" class="form" enctype="multipart/form-data" action="./edit-service.php?service=<?php echo $_GET["service"]; ?>" method="POST">
        <span class="form-title">Создать отзыв на <?php echo $service_info["name"] ?></span>
            <label class="comments-form-label">Имя пользователя<span class="comments-form-label-needed">*</span>:</label>
            <input type="text" name="username" class="comments-form-input" required>
            <label class="comments-form-label">Эл. Почта:</label>
            <input type="email" name="email" class="comments-form-input">
            <label class="comments-form-label">Оценка<span class="comments-form-label-needed">*</span>:</label>
            <div class="comments-form-rate">
                <div class="rate">
                    <input type="radio" id="star5" name="rating" value="5" required/>
                    <label for="star5" title="text">5 stars</label>
                    <input type="radio" id="star4" name="rating" value="4" required/>
                    <label for="star4" title="text">4 stars</label>
                    <input type="radio" id="star3" name="rating" value="3" required/>
                    <label for="star3" title="text">3 stars</label>
                    <input type="radio" id="star2" name="rating" value="2" required/>
                    <label for="star2" title="text">2 stars</label>
                    <input type="radio" id="star1" name="rating" value="1" required/>
                    <label for="star1" title="text">1 star</label>
                </div>
            </div>
            <label class="comments-form-label">Отзыв<span class="comments-form-label-needed">*</span>:</label>
            <textarea class="comments-form-input comments-form-textarea" name="text" required></textarea>
            <input type="submit" class="form-input form-submit" value="Опубликовать">
        </form>
    </div>
    <hr>
    <footer>
        <?php echo $site_config->footer_text; ?>
    </footer>
</body>
</html>   