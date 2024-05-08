<?php

// Loading configs...

$site_config = json_decode(file_get_contents("../config/site.json"));
$admin_hash = file_get_contents("../config/admin.hash");

// Checking session...

session_start();

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST["admin_login"]) && isset($_POST["admin_password"]) && isset($_POST["action"])) {
        if($_POST["action"] == "login") {
            if(password_verify($_POST["admin_login"] . $_POST["admin_password"], $admin_hash)) {
                $_SESSION["admin_login"] = $_POST["admin_login"];
                $_SESSION["admin_password"] = $_POST["admin_password"];
            } else {
                echo '<script>alert("Wrong login or password!");</script>';
                loadAdminLogin($site_config);
            }
        }
    }
}

if(!isset($_SESSION["admin_login"]) && !isset($_SESSION["admin_password"])) {
    loadAdminLogin($site_config);
}

if(!password_verify($_SESSION["admin_login"] . $_SESSION["admin_password"], $admin_hash)) {
    loadAdminLogin($site_config);
}

function loadAdminLogin($site_config) {

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
        <a class="sitename" href="../index.php"><?php echo $site_config->sitename_header; ?> — ADMIN PANEL</a>
    </header>
    <hr>
    <div class="forms">
        <form class="form" action="#" method="POST">
            <span class="form-title">Панель управления</span>
            <div class="form-inputs" method="POST">
                <input type="hidden" name="action" value="login">
                <label class="form-label">Логин<span class="form-label-needed">*</span>:</label>
                <input type="text" name="admin_login" class="form-input" required>
                <label class="form-label">Пароль<span class="form-label-needed">*</span>:</label>
                <input type="password" name="admin_password" class="form-input" required>
                <input type="submit" class="form-input form-submit" value="Войти">
            </div>
        </form>
    </div>
    <hr>
    <footer>
        <?php echo $site_config->footer_text; ?>
    </footer>
</body>
</html>

<?php

die();

}

?>