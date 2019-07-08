<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

echo elgg_view('agora/admin/tabs', ['paypal_options_selected' => true]);

echo elgg_view_module('info', elgg_echo('admin:agora:paypal_options'), elgg_view_form('agora/admin/paypal_options'));
