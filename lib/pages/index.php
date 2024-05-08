<?php

require_once './lib/scrlib.php';

function show_index_page() {

    // Loading configs...

    $site_config = json_decode(file_get_contents("./config/site.json"));
    $mysql_config = json_decode(file_get_contents("./config/mysql.json"));

    $database = mysqli_connect(
        $mysql_config->mysql_hostname,
        $mysql_config->mysql_username,
        $mysql_config->mysql_password,
        $mysql_config->mysql_database,
        $mysql_config->mysql_port
    );

    // Services pre-load

    $services_data = mysqli_query($database, "SELECT * FROM `services`");
    $services_html = file_get_contents("./templates/html/index/service.html");
    $services = "";

    while($service = $services_data->fetch_assoc()) {
        $view_service_rating_text_button = self_regex($site_config->view_service_rating_button, '.', $site_config, $service, array(), '', $services, '');

        $services .= self_regex($services_html, '.', $site_config, $service, array(), '', $services, $view_service_rating_text_button);
    }

    // Main page load

    $index = self_regex(file_get_contents("./templates/html/index/index.html"), '.', $site_config, $service, array(), '', $services, $view_service_rating_text_button);

    // Printing page

    echo $index;

}