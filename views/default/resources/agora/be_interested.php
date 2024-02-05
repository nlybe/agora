<?php
/**
 * Elgg Entities Gallery
 * @package egallery 
 */

$guid = elgg_extract('guid', $vars, '');
$entity = get_entity($guid);

if (!$entity instanceof \Agora) {
    echo elgg_format_element('h3', [], elgg_echo('agora:error:invalid:entity'));
}
else {
    $title = elgg_format_element('div', 
        ['class' => 'elgg-head'], 
        elgg_format_element('h3', [], elgg_echo('agora:be_interested:title', [$entity->title]))
    );

    $form_params = [
        'id' => 'interested-in-form',
        'class' => 'hidden mtl',
    ];
    $body_params = [
        'classified_guid' => $entity->guid,
        'recipient_guid' => $entity->owner_guid,
        'subject' => elgg_echo("agora:be_interested:ad_message_subject", [$entity->title]),
    ];
    $content .= elgg_view_form('agora/be_interested', $form_params, $body_params);
    $content = elgg_format_element('div', ['style' => 'width:600px;height:420px;padding: 10px; overflow-y: auto'], $content);
    echo elgg_format_element('div', ['class'=>'elgg-module elgg-module-info'], $title.$content); 
}
