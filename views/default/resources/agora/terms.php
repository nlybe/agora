<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

use Agora\AgoraOptions;

echo elgg_format_element('h3', [], elgg_echo('agora:settings:terms_of_use'));
echo elgg_format_element('div', ['class' => 'width:300px;'], AgoraOptions::getTerms()); 