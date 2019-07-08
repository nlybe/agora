<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

$plugin = elgg_get_plugin_from_id('agora');

$params = get_input('params');
foreach ($params as $k => $v) {
    if (!$plugin->setSetting($k, $v)) {
        return elgg_error_response(elgg_echo('plugins:settings:save:fail'));
    }
}

return elgg_ok_response('', elgg_echo('agora:settings:save:ok'), REFERER);
