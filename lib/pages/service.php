<?php

require_once '../lib/scrlib.php';

function show_service_page($service) {

    // Loading configs...

    $site_config = json_decode(file_get_contents("../config/site.json"));
    $mysql_config = json_decode(file_get_contents("../config/mysql.json"));

    $database = mysqli_connect(
        $mysql_config->mysql_hostname,
        $mysql_config->mysql_username,
        $mysql_config->mysql_password,
        $mysql_config->mysql_database,
        $mysql_config->mysql_port
    );

    // Service info load

    $service_info = mysqli_query($database, "SELECT * FROM `services` WHERE `name`='$service'")->fetch_assoc();
    $service_comments = mysqli_query($database, "SELECT * FROM `" . $service . "_comments` ORDER BY `date` DESC LIMIT 20");

    // Comments load

    $comments_html = file_get_contents("../templates/html/service/comment.html");
    $comments = "";

    while($comment = $service_comments->fetch_assoc()) {
        $comments .= self_regex($comments_html, '../', $site_config, $service_info, $comment, $comments, '', '');
    }

    // Main page load

    $index = self_regex(file_get_contents("../templates/html/service/index.html"), '../', $site_config, $service_info, $comment, $comments, '', '');

    echo $index;
}

function post_comment($service) {

    // Loading configs...

    $site_config = json_decode(file_get_contents("../config/site.json"));
    $mysql_config = json_decode(file_get_contents("../config/mysql.json"));

    $database = mysqli_connect(
        $mysql_config->mysql_hostname,
        $mysql_config->mysql_username,
        $mysql_config->mysql_password,
        $mysql_config->mysql_database,
        $mysql_config->mysql_port
    );

    $username = strip_tags(mysqli_real_escape_string($database, $_POST["username"]));
    $email = strip_tags(mysqli_real_escape_string($database, $_POST["email"]));
    $text = str_replace("\n", "<br>", strip_tags(mysqli_real_escape_string($database, $_POST["text"])));
    $rating = intval($_POST["rating"]);
    $date = time();

    if(isset($username) && isset($text) && isset($text) && isset($rating) && isset($date)) {
        mysqli_query($database, "INSERT INTO `" . $service . "_comments` (username, email, text, rating, date) VALUES (\"$username\", \"$email\", \"$text\", $rating, $date)");

        $comments_count = mysqli_query($database, "SELECT COUNT(date) AS count FROM `" . $service . "_comments`")->fetch_assoc()["count"];
        $service_rating = mysqli_query($database, "SELECT AVG(rating) AS rating FROM `" . $service . "_comments`;")->fetch_assoc()["rating"];

        mysqli_query($database, "UPDATE `services` SET `rating`=$service_rating, `comments_count`=$comments_count WHERE `name`=\"$service\"");
    }

    header("Location: ./#comments-form");
}