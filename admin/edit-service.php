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
    if(isset($_POST["name"]) && isset($_POST["url"])) {

        $name = mysqli_real_escape_string($database, trim($_POST["name"]));
        $url = mysqli_real_escape_string($database, $_POST["url"]);
        $email = mysqli_real_escape_string($database, $_POST["email"]);
        $phone1 = mysqli_real_escape_string($database, $_POST["phone-1"]);
        $phone2 = mysqli_real_escape_string($database, $_POST["phone-2"]);
        $workinghours = str_replace("\n", "<br/>", mysqli_real_escape_string($database, $_POST["working-hours"]));
        $dealer = $_POST["dealer"] == "official" ? 1 : 0;
        $city = mysqli_real_escape_string($database, $_POST["city"]);
        $address = mysqli_real_escape_string($database, $_POST["address"]);
        $metro = mysqli_real_escape_string($database, $_POST["metro"]);
        $maps = mysqli_real_escape_string($database, $_POST["maps"]);
        $newauto = $_POST["new-auto"] == "on" ? 1 : 0;
        $supportedauto = $_POST["supported-auto"] == "on" ? 1 : 0;
        $carserviceexists = $_POST["carservice-exists"] == "yes" ? 1 : 0;
        $autoparts = $_POST["auto-parts"] == "on" ? 1 : 0;
        $addequipment = $_POST["add-equipment"] == "on" ? 1 : 0;
        
        mysqli_query($database, "UPDATE `services` SET `name`=\"$name\", `url`=\"$url\", `email`=\"$email\", `phone-1`=\"$phone1\", `phone-2`=\"$phone2\", `working-hours`=\"$workinghours\", `dealer`=$dealer, `city`=\"$city\", `address`=\"$address\", `metro`=\"$metro\", `maps`=\"$maps\", `new-auto`=$newauto, `supported-auto`=$supportedauto, `carservice-exists`=$carserviceexists, `auto-parts`=$autoparts, `add-equipment`=$addequipment WHERE `name`=\"" . $service_info["name"] . "\"");
        mysqli_query($database, "ALTER TABLE `" . $service_info["name"] . "_comments` RENAME TO `" . $name . "_comments`");

        $index = "<?php

        require_once '../lib/pages/service.php';
        
        if(\$_SERVER['REQUEST_METHOD'] == \"POST\") {
            post_comment(\"$name\");
        } else {
            show_service_page(\"$name\");
        }";

        rename("../" . $service_info["name"], "../$name");
        file_put_contents("../$name/index.php", $index);

        if(isset($_FILES["logo"])) {
            unlink("../$name/logo.png");
            move_uploaded_file($_FILES["logo"]["tmp_name"], __DIR__ . "/../$name/logo.png");
        }

        if(isset($_POST["img-1"])) {
            move_uploaded_file($_FILES["img-1"]["tmp_name"], __DIR__ . "/../$name/images/img-1.png");
        }
        if(isset($_POST["img-2"])) {
            move_uploaded_file($_FILES["img-2"]["tmp_name"], __DIR__ . "/../$name/images/img-2.png");
        }
        if(isset($_POST["img-3"])) {
            move_uploaded_file($_FILES["img-3"]["tmp_name"], __DIR__ . "/../$name/images/img-3.png");
        }
        if(isset($_POST["img-4"])) {
            move_uploaded_file($_FILES["img-4"]["tmp_name"], __DIR__ . "/../$name/images/img-4.png");
        }
        if(isset($_POST["img-5"])) {
            move_uploaded_file($_FILES["img-5"]["tmp_name"], __DIR__ . "/../$name/images/img-5.png");
        }
        if(isset($_POST["img-6"])) {
            move_uploaded_file($_FILES["img-6"]["tmp_name"], __DIR__ . "/../$name/images/img-6.png");
        }
        if(isset($_POST["img-7"])) {
            move_uploaded_file($_FILES["img-7"]["tmp_name"], __DIR__ . "/../$name/images/img-7.png");
        }
        if(isset($_POST["img-8"])) {
            move_uploaded_file($_FILES["img-8"]["tmp_name"], __DIR__ . "/../$name/images/img-8.png");
        }
        if(isset($_POST["img-9"])) {
            move_uploaded_file($_FILES["img-9"]["tmp_name"], __DIR__ . "/../$name/images/img-9.png");
        }
        if(isset($_POST["img-10"])) {
            move_uploaded_file($_FILES["img-10"]["tmp_name"], __DIR__ . "/../$name/images/img-10.png");
        }

        echo '<script>alert("Saved!")</script>';
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
            <span class="form-title">Редактировать <?php echo $service_info["name"] ?></span>
            <div class="form-inputs">
                <label class="form-label">Название<span class="form-label-needed">*</span>:</label>
                <input type="text" name="name" class="form-input" value="<?php echo $service_info["name"] ?>" required>
                <label class="form-label">Сайт<span class="form-label-needed">*</span>:</label>
                <input type="text" name="url" class="form-input" value="<?php echo $service_info["url"] ?>" required>
                <label class="form-label">Логотип<span class="form-label-needed">*</span>:</label>
                <input type="file" name="logo" class="form-input-transparent" required>
                <img class="img-preview" src="../<?php echo $service_info["name"]; ?>/logo.png">
                <hr>
                <label class="form-label">Почта:</label>
                <input type="email" name="email" value="<?php echo $service_info["email"] ?>" class="form-input">
                <label class="form-label">Телефон 1:</label>
                <input type="tel" name="phone-1" value="<?php echo $service_info["phone-1"] ?>" class="form-input">
                <label class="form-label">Телефон 2:</label>
                <input type="tel" name="phone-2" value="<?php echo $service_info["phone-2"] ?>" class="form-input">
                <label class="form-label">График работы:</label>
                <textarea type="text" name="working-hours" class="form-input form-textarea" require><?php echo $service_info["working-hours"] ?></textarea>
                <label class="form-label">Автодилер:</label>
                <select name="dealer" class="form-input">
                    <option value="official" <?php if($service_info["dealer"] == 1) { echo "selected=\"selected\""; } ?>>Официальный дилер</option>
                    <option value="unofficial" <?php if($service_info["dealer"] == 0) { echo "selected=\"selected\""; } ?>>Неофициальный дилер</option>
                </select>
                <label class="form-label">Изображения (макс. 10):</label>
                <div>
                    <div>
                        <label class="form-label">Изображение 1:</label>
                        <input type="file" name="img-1" class="form-input-transparent">
                        <img class="img-preview" src="../<?php echo $service_info["name"]; ?>/images/img-1.png">
                    </div>
                    <div>
                        <label class="form-label">Изображение 2:</label>
                        <input type="file" name="img-2" class="form-input-transparent">
                        <img class="img-preview" src="../<?php echo $service_info["name"]; ?>/images/img-2.png">
                    </div>
                    <div>
                        <label class="form-label">Изображение 3:</label>
                        <input type="file" name="img-3" class="form-input-transparent">
                        <img class="img-preview" src="../<?php echo $service_info["name"]; ?>/images/img-3.png">
                    </div>
                    <div>
                        <label class="form-label">Изображение 4:</label>
                        <input type="file" name="img-4" class="form-input-transparent">
                        <img class="img-preview" src="../<?php echo $service_info["name"]; ?>/images/img-4.png">
                    </div>
                    <div>
                        <label class="form-label">Изображение 5:</label>
                        <input type="file" name="img-5" class="form-input-transparent">
                        <img class="img-preview" src="../<?php echo $service_info["name"]; ?>/images/img-5.png">
                    </div>
                    <div>
                        <label class="form-label">Изображение 6:</label>
                        <input type="file" name="img-6" class="form-input-transparent">
                        <img class="img-preview" src="../<?php echo $service_info["name"]; ?>/images/img-6.png">
                    </div>
                    <div>
                        <label class="form-label">Изображение 7:</label>
                        <input type="file" name="img-7" class="form-input-transparent">
                        <img class="img-preview" src="../<?php echo $service_info["name"]; ?>/images/img-7.png">
                    </div>
                    <div>
                        <label class="form-label">Изображение 8:</label>
                        <input type="file" name="img-8" class="form-input-transparent">
                        <img class="img-preview" src="../<?php echo $service_info["name"]; ?>/images/img-8.png">
                    </div>
                    <div>
                        <label class="form-label">Изображение 9:</label>
                        <input type="file" name="img-9" class="form-input-transparent">
                        <img class="img-preview" src="../<?php echo $service_info["name"]; ?>/images/img-9.png">
                    </div>
                    <div>
                        <label class="form-label">Изображение 10:</label>
                        <input type="file" name="img-10" class="form-input-transparent">
                        <img class="img-preview" src="../<?php echo $service_info["name"]; ?>/images/img-10.png">
                    </div>
                </div>
                <label class="form-label">Город:</label>
                <input type="text" name="city" value="<?php echo $service_info["city"] ?>" class="form-input">
                <label class="form-label">Адрес:</label>
                <input type="text" name="address" value="<?php echo $service_info["address"] ?>" class="form-input">
                <label class="form-label">Метро:</label>
                <input type="text" name="metro" value="<?php echo $service_info["metro"] ?>" class="form-input">
                <label class="form-label">Яндекс Карты:</label>
                <input type="text" name="maps" class="form-input">
                <?php echo $service_info["maps"]; ?>
                <span class="form-label">* Чтобы добавить яндекс-карты на страницу, перейдите на
                <a href="https://maps.yandex.ru/" target="_blank">maps.yandex.ru</a>, найдите на нужное место,
                нажмите на три точки -> Поделиться -> Встраивание карт -> Виджет с картой 
                (<a href="https://yandex.ru/q/question/kak_vstavit_iandeks_kartu_na_sait_c3a43120/" target="_blank">Подробная инструкция</a>).
                После чего вставьте сюда скопированный текст.</span>
                <label class="form-label">Новые/Поддержанные авто:</label>
                <div>
                    <input type="checkbox" name="new-auto" <?php if($service_info["new-auto"] == 1) { echo "checked"; } ?> class="form-input">Новые авто
                    <input type="checkbox" name="supported-auto" <?php if($service_info["supported-auto"] == 1) { echo "checked"; } ?> class="form-input">Поддержанные авто
                </div>
                <label class="form-label">Наличие автосервиса:</label>
                <div>
                    <input type="radio" name="carservice-exists" class="form-input" <?php if($service_info["carservice-exists"] == 1) { echo "checked"; } ?> value="yes">Есть
                    <input type="radio" name="carservice-exists" class="form-input" <?php if($service_info["carservice-exists"] == 0) { echo "checked"; } ?> value="no">Нету
                </div>
                <label class="form-label">Наличие автозапчастей/доп. оборудования:</label>
                <div>
                    <input type="checkbox" name="auto-parts" <?php if($service_info["auto-parts"] == 0) { echo "checked"; } ?> class="form-input">Автозапчасти
                    <input type="checkbox" name="add-equipment" <?php if($service_info["add-equipment"] == 0) { echo "checked"; } ?> class="form-input">Доп. оборудование
                </div>
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