<?php

ini_set('display_errors', 0); ini_set('display_startup_errors', 0); error_reporting(-1);

function self_regex($source, $rootdir, $site_config, $service_info, $comment, $comments, $services, $view_rating_stars_text)
{
    $service_rating_stars_html = "";

    if(isset($service_info["rating"])) {
        for($i = 0; $i < 5; $i++, $service_info["rating"]--) {
            if($service_info["rating"] >= 1)
                $service_rating_stars_html .= $site_config->rating_star_filled;
            else if($service_info["rating"] >= 0.5)
                $service_rating_stars_html .= $site_config->rating_star_filled_small;
            else
                $service_rating_stars_html .= $site_config->rating_star_empty;
        }
    }

    $comment_rating_stars_html = "";

    if(isset($comment["rating"])) {
        for($i = 0; $i < 5; $i++, $service_info["rating"]--) {
            if($comment["rating"] >= 1)
                $comment_rating_stars_html .= $site_config->rating_star_filled;
            else if($comment["rating"] >= 0.5)
                $comment_rating_stars_html .= $site_config->rating_star_filled_small;
            else
                $comment_rating_stars_html .= $site_config->rating_star_empty;
        }
    }

    return str_replace(array(
        "{{SITENAME_TEXT}}",
        "{{SITENAME_HEADER_TEXT}}",
        "{{RATING_STARS_COUNT}}",
        "{{RATING_STAR_EMPTY}}",
        "{{RATING_STAR_FILLED}}",
        "{{RATING_STAR_FILLED_SMALL}}",
        "{{SERVICES}}",
        "{{SERVICE_NAME}}",
        "{{SERVICE_URL}}",
        "{{SERVICE_EMAIL}}",
        "{{PHONE_1}}",
        "{{PHONE_2}}",
        "{{WORKING-HOURS}}",
        "{{DEALER}}",
        "{{CITY}}",
        "{{ADDRESS}}",
        "{{METRO}}",
        "{{MAPS}}",
        "{{NEW_AUTO}}",
        "{{SUPPORTED_AUTO}}",
        "{{CARSERVICE_EXISTS}}",
        "{{AUTO_PARTS}}",
        "{{ADD_EQUIPMENT}}",
        "{{SERVICE_RATING}}",
        "{{SERVICE_COMMENTS_COUNT}}",
        "{{SERVICE_VIEW_RATING_TEXT}}",
        "{{COMMENT_AVATAR_ID}}",
        "{{COMMENT_USERNAME}}",
        "{{COMMENT_TEXT}}",
        "{{COMMENT_RATING}}",
        "{{COMMENTS_LIST}}",
        "{{FOOTER_TEXT}}"
    ), array(
        $site_config->sitename,
        $site_config->sitename_header,
        $site_config->rating_stars_count,
        $site_config->rating_star_empty,
        $site_config->rating_star_filled,
        $site_config->rating_star_filled_small,
        $services,
        $service_info["name"],
        $service_info["url"],
        $service_info["email"],
        $service_info["phone-1"],
        $service_info["phone-2"],
        $service_info["working-hours"],
        '', // dealer
        $service_info["city"],
        $service_info["address"],
        $service_info["metro"],
        $service_info["maps"],
        '', // new-auto
        '', // supported-auto
        '', // carservice-exists
        '', // auto-parts
        '', // add-equipment
        $service_rating_stars_html,
        $service_info["comments_count"],
        $view_rating_stars_text,
        random_int(1, count(scandir("$rootdir/templates/user-avatars/")) - 2),
        $comment["username"],
        $comment["text"],
        $comment_rating_stars_html,
        $comments,
        $site_config->footer_text
    ), $source);
}
