<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

elgg_require_js("agora/js/amap_images");

// maximum number of images
$max_images = elgg_extract('$max_images', $vars, AgoraOptions::getParams('max_images'));

$entity = get_entity(elgg_extract('guid', $vars));

if ($entity) {
    $images = $entity->getMoreImages();
    $imgcount = count($images);

    if ($imgcount) {
        $gallery .= '<ul id="agora-icons" class="elgg-gallery agora-icons">';
        foreach ($images as $img) {
            $thumb_img = elgg_view('output/img', array(
                'src' => elgg_normalize_url("agora/icon/{$img->guid}/smamed/" . md5($img->time_created) . ".jpg"),
                'class' => "elgg-photo agora-photo",
                'alt' => $img->title,
            ));

            $full_img = elgg_view('output/url', array(
                'href' => elgg_normalize_url(elgg_get_site_url() . "agora/icon/{$img->guid}/master/" . md5($img->time_created) . '.jpg'),
                'text' => $thumb_img,
                'class' => "agora-icon elgg-lightbox",
            ));
            $gallery .= '<li>';
            $gallery .= $full_img;

            $gallery .= elgg_view('output/url', array(
                'text' => elgg_view_icon('delete'),
                'href' => 'action/agora/icon/delete?guid=' . $img->guid,
                'is_action' => true,
                'is_trusted' => true,
                'data-guid' => $img->guid,
                'class' => 'agora-icon-delete'
            ));
            $gallery .= '</li>';
        }
        $gallery .= '</ul>';
    }
}

$icon_class = 'agora-icon-input';
if ($imgcount >= $max_images) {
    $icon_class .= ' hidden';
}

$render = elgg_format_element('div', ['class' => $icon_class], elgg_view('input/file', array('id' => 'product_icon', 'name' => 'product_icon[]', 'multiple' => 'multiple')));

if ($gallery) {
    $render .= elgg_format_element('div', [], $gallery);
}

echo elgg_format_element('div', [], $render);