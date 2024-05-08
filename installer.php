<?php

$site_config = json_decode(file_get_contents("./config/site.json"));
$mysql_config = json_decode(file_get_contents("./config/mysql.json"));

// Enabling errors display
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

if($_SERVER["REQUEST_METHOD"] == "POST") {

    // Checking data...

    if(!isset($_POST["admin_login"]) || !isset($_POST["admin_password"]) || !isset($_POST["admin_repeat_password"])) {
        echo '<script>window.location.href = "./installer.php";</script>';
        exit;
    }

    if(!isset($_POST["mysql_hostname"]) || !isset($_POST["mysql_username"]) || !isset($_POST["mysql_password"]) ||
    !isset($_POST["mysql_database"]) || !isset($_POST["mysql_port"])) {
        echo '<script>window.location.href = "./installer.php";</script>';
        exit;
    }
    
    if($_POST["admin_password"] != $_POST["admin_repeat_password"]) {
        echo '<script>alert("Passwords does not match!"); window.location.href = "./installer.php";</script>';
        exit;
    }

    // Trying to connect database...

    $database = mysqli_connect($_POST["mysql_hostname"], $_POST["mysql_username"], $_POST["mysql_password"], $_POST["mysql_database"], $_POST["mysql_port"]);

    if($database === false) {
        echo '<script>alert("Cannot connect database."); window.location.href = "./installer.php";</script>';
        exit;
    }

    // Saving admin data...

    file_put_contents("./config/admin.hash", password_hash($_POST["admin_login"] . $_POST["admin_password"], null));

    // Saving database data...

    $mysql_json = json_encode(array(
        "mysql_hostname" => $_POST["mysql_hostname"],
        "mysql_username" => $_POST["mysql_username"],
        "mysql_password" => $_POST["mysql_password"],
        "mysql_database" => $_POST["mysql_database"],
        "mysql_port" => $_POST["mysql_port"]
    ));

    file_put_contents("./config/mysql.json", $mysql_json);

    // Registering services table...

    mysqli_query($database, "ALTER DATABASE " . $_POST["mysql_database"] . " CHARACTER SET utf8 COLLATE utf8_general_ci");
    mysqli_query($database, "CREATE TABLE `services` (`name` text NOT NULL, `url` text NOT NULL, `email` text NOT NULL, `phone-1` text NOT NULL, `phone-2` text NOT NULL, `working-hours` text NOT NULL, `dealer` int NOT NULL, `city` text NOT NULL, `address` text NOT NULL, `metro` text NOT NULL, `maps` text NOT NULL, `new-auto` int NOT NULL, `supported-auto` int NOT NULL, `carservice-exists` int NOT NULL, `auto-parts` int NOT NULL, `add-equipment` int NOT NULL, `rating` float NOT NULL, `comments_count` int NOT NULL) DEFAULT CHARSET=utf8 COLLATE utf8_general_ci");

    // Writing index.php...

    file_put_contents("./index.php", "<?php

    require_once './lib/pages/index.php';

    show_index_page();
    
    ?>");

    // Deleting installer and exiting...

    echo '<script>alert("OTZOVIK.RU successfully installed!"); window.location.href = "./admin/index.php";</script>';
    unlink("./installer.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./templates/styles/main.css">
    <link rel="stylesheet" href="./templates/styles/admin.css">
    <title><?php echo $site_config->sitename; ?> — INSTALLER</title>
</head>
<body>
    <header>
        <a class="sitename" href="./index.php"><?php echo $site_config->sitename_header; ?> — INSTALLER</a>
    </header>
    <hr>
    <div class="forms">
        <span class="form-title">Установщик <?php echo $site_config->sitename; ?></span>
        <form id="change-password" class="form" action="./installer.php" method="POST">
            <span class="form-title">Аккаунт администратора</span>
            <div class="form-inputs">
                <label class="form-label">Логин администратора<span class="form-label-needed">*</span>:</label>
                <input type="text" name="admin_login" class="form-input" value="admin" required>
                <label class="form-label">Пароль администратора<span class="form-label-needed">*</span>:</label>
                <input type="password" name="admin_password" class="form-input" required>
                <label class="form-label">Повторите пароль администратора<span class="form-label-needed">*</span>:</label>
                <input type="password" name="admin_repeat_password" class="form-input" required>
            </div>
            <hr style="margin: 15px">
            <span class="form-title">База данных</span>
            <div class="form-inputs">
                <label class="form-label">Хост<span class="form-label-needed">*</span>:</label>
                <input type="text" name="mysql_hostname" class="form-input" value="<?php echo $mysql_config->mysql_hostname ?>" required>
                <label class="form-label">Имя пользователя<span class="form-label-needed">*</span>:</label>
                <input type="text" name="mysql_username" class="form-input" value="<?php echo $mysql_config->mysql_username ?>" required>
                <label class="form-label">Пароль<span class="form-label-needed">*</span>:</label>
                <input type="text" name="mysql_password" class="form-input" value="<?php echo $mysql_config->mysql_password ?>" required>
                <label class="form-label">Имя базы данных<span class="form-label-needed">*</span>:</label>
                <input type="text" name="mysql_database" class="form-input" value="<?php echo $mysql_config->mysql_database ?>" required>
                <label class="form-label">Порт<span class="form-label-needed">*</span>:</label>
                <input type="number" name="mysql_port" class="form-input" value="<?php echo $mysql_config->mysql_port ?>" required>
            </div>
            <input type="submit" class="form-input form-submit" value="Установить OTZOVIK.RU">
        </form>
    </div>
    <hr>
    <footer>
        <?php echo $site_config->footer_text; ?>
    </footer>
</body>
</html>