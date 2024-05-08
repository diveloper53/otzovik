<?php

require_once 'login.php';

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST["name"]) && isset($_FILES["logo"]) && isset($_POST["url"])) {
        $mysql_config = json_decode(file_get_contents("../config/mysql.json"));

        $database = mysqli_connect(
            $mysql_config->mysql_hostname,
            $mysql_config->mysql_username,
            $mysql_config->mysql_password,
            $mysql_config->mysql_database,
            $mysql_config->mysql_port
        );

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

        mysqli_query($database, "INSERT INTO `services` (`name`, `url`, `email`, `phone-1`, `phone-2`, `working-hours`, `dealer`, `city`, `address`, `metro`, `maps`, `new-auto`, `supported-auto`, `carservice-exists`, `auto-parts`, `add-equipment`, `rating`, `comments_count`) VALUES (\"$name\", \"$url\", \"$email\", \"$phone1\", \"$phone2\", \"$workinghours\", \"$dealer\", \"$city\", \"$address\", \"$metro\", \"$maps\", $newauto, $supportedauto, $carserviceexists, $autoparts, $addequipment, 0, 0)");
        mysqli_query($database, "CREATE TABLE `" . $name . "_comments` (`username` text NOT NULL, `email` text NOT NULL, `text` text NOT NULL, `rating` int NOT NULL, `date` int NOT NULL) DEFAULT CHARSET=utf8 COLLATE utf8_general_ci");

        $index = "<?php

        require_once '../lib/pages/service.php';
        
        if(\$_SERVER['REQUEST_METHOD'] == \"POST\") {
            post_comment(\"$name\");
        } else {
            show_service_page(\"$name\");
        }";

        mkdir("../$name");
        mkdir("../$name/images");
        move_uploaded_file($_FILES["logo"]["tmp_name"], __DIR__ . "/../$name/logo.png");
        file_put_contents("../$name/index.php", $index);

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
    } else {
        echo '<script>alert("Not all values set!")</script>';
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
        <form id="site-edit" class="form" enctype="multipart/form-data" action="./create-service.php" method="POST">
            <span class="form-title">Создать сервис</span>
            <div class="form-inputs">
                <label class="form-label">Название<span class="form-label-needed">*</span>:</label>
                <input type="text" name="name" class="form-input" required>
                <label class="form-label">Сайт<span class="form-label-needed">*</span>:</label>
                <input type="text" name="url" class="form-input" required>
                <label class="form-label">Логотип<span class="form-label-needed">*</span>:</label>
                <input type="file" name="logo" class="form-input-transparent" required>
                <hr>
                <label class="form-label">Почта:</label>
                <input type="email" name="email" class="form-input">
                <label class="form-label">Телефон 1:</label>
                <input type="tel" name="phone-1" class="form-input">
                <label class="form-label">Телефон 2:</label>
                <input type="tel" name="phone-2" class="form-input">
                <label class="form-label">График работы:</label>
                <textarea type="text" name="working-hours" class="form-input form-textarea" require></textarea>
                <label class="form-label">Автодилер:</label>
                <select name="dealer" class="form-input">
                    <option value="official">Официальный дилер</option>
                    <option value="unofficial">Неофициальный дилер</option>
                </select>
                <label class="form-label">Изображения (макс. 10):</label>
                <div>
                    <div>
                        <label class="form-label">Изображение 1:</label>
                        <input type="file" name="img-1" class="form-input-transparent">
                    </div>
                    <div>
                        <label class="form-label">Изображение 2:</label>
                        <input type="file" name="img-2" class="form-input-transparent">
                    </div>
                    <div>
                        <label class="form-label">Изображение 3:</label>
                        <input type="file" name="img-3" class="form-input-transparent">
                    </div>
                    <div>
                        <label class="form-label">Изображение 4:</label>
                        <input type="file" name="img-4" class="form-input-transparent">
                    </div>
                    <div>
                        <label class="form-label">Изображение 5:</label>
                        <input type="file" name="img-5" class="form-input-transparent">
                    </div>
                    <div>
                        <label class="form-label">Изображение 6:</label>
                        <input type="file" name="img-6" class="form-input-transparent">
                    </div>
                    <div>
                        <label class="form-label">Изображение 7:</label>
                        <input type="file" name="img-7" class="form-input-transparent">
                    </div>
                    <div>
                        <label class="form-label">Изображение 8:</label>
                        <input type="file" name="img-8" class="form-input-transparent">
                    </div>
                    <div>
                        <label class="form-label">Изображение 9:</label>
                        <input type="file" name="img-9" class="form-input-transparent">
                    </div>
                    <div>
                        <label class="form-label">Изображение 10:</label>
                        <input type="file" name="img-10" class="form-input-transparent">
                    </div>
                </div>
                <label class="form-label">Город:</label>
                <input type="text" name="city" class="form-input">
                <label class="form-label">Адрес:</label>
                <input type="text" name="address" class="form-input">
                <label class="form-label">Метро:</label>
                <input type="text" name="metro" class="form-input">
                <label class="form-label">Яндекс Карты:</label>
                <input type="text" name="maps" class="form-input">
                <span class="form-label">* Чтобы добавить яндекс-карты на страницу, перейдите на
                <a href="https://maps.yandex.ru/" target="_blank">maps.yandex.ru</a>, найдите на нужное место,
                нажмите на три точки -> Поделиться -> Встраивание карт -> Виджет с картой 
                (<a href="https://yandex.ru/q/question/kak_vstavit_iandeks_kartu_na_sait_c3a43120/" target="_blank">Подробная инструкция</a>).
                После чего вставьте сюда скопированный текст.</span>
                <label class="form-label">Новые/Поддержанные авто:</label>
                <div>
                    <input type="checkbox" name="new-auto" class="form-input">Новые авто
                    <input type="checkbox" name="supported-auto" class="form-input">Поддержанные авто
                </div>
                <label class="form-label">Наличие автосервиса:</label>
                <div>
                    <input type="radio" name="carservice-exists" class="form-input" value="yes">Есть
                    <input type="radio" name="carservice-exists" class="form-input" value="no">Нету
                </div>
                <label class="form-label">Наличие автозапчастей/доп. оборудования:</label>
                <div>
                    <input type="checkbox" name="auto-parts" class="form-input">Автозапчасти
                    <input type="checkbox" name="add-equipment" class="form-input">Доп. оборудование
                </div>
                <input type="submit" class="form-input form-submit" value="Создать">
            </div>
        </form>
    </div>
    <hr>
    <footer>
        <?php echo $site_config->footer_text; ?>
    </footer>
</body>
</html>   