<?php

require_once 'login.php';

if($_SERVER["REQUEST_METHOD"] == "POST") {

    if(isset($_POST["mysql_hostname"]) && isset($_POST["mysql_username"]) && isset($_POST["mysql_password"]) &&
    isset($_POST["mysql_database"]) && isset($_POST["mysql_port"])) {

        $database = mysqli_connect($_POST["mysql_hostname"], $_POST["mysql_username"], $_POST["mysql_password"], $_POST["mysql_database"], $_POST["mysql_port"]);

        if($database !== false) {
            echo '<script>alert("Cannot connect database."); window.location.href = "./mysql-edit.php";</script>';
            exit;
        }

        $json = json_encode(array(
            "mysql_hostname" => $_POST["mysql_hostname"],
            "mysql_username" => $_POST["mysql_username"],
            "mysql_password" => $_POST["mysql_password"],
            "mysql_database" => $_POST["mysql_database"],
            "mysql_port" => $_POST["mysql_port"]
        ));

        file_put_contents("../config/mysql.json", $json);

        echo '<script>alert("Saved!")</script>';

    }
}

$mysql_config = json_decode(file_get_contents("../config/mysql.json"));

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
        <form id="mysql-edit" class="form" action="./mysql-edit.php" method="POST">
            <span class="form-title">Редактировать базу данных</span>
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