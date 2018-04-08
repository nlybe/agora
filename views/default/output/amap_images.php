<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

$images = elgg_extract('images', $vars, false);
if ($images) {
    foreach ($images as $img) {
        $list_img .= elgg_format_element('li', [], elgg_view('output/url', array(
            'href' => elgg_normalize_url(elgg_get_site_url() . "agora/icon/{$img->guid}/master/" . md5($img->time_created) . '.jpg'),
            'text' => elgg_view('output/img', array(
                'src' => elgg_normalize_url("agora/icon/{$img->guid}/smamed/" . md5($img->time_created) . ".jpg"),
                'class' => "elgg-photo agora-photo",
                'alt' => $img->title,
            )),
            'class' => "elgg-showcase-screenshot agora-icon elgg-lightbox",
            'rel' => 'showcase-gallery',
        )));
    }
    
    echo elgg_format_element('div', ['class' => 'agora-gallery'], 
        elgg_format_element('ul', ['class' => 'elgg-gallery agora-icons'], $list_img)
    );
}