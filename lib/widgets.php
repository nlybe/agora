<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 *
 * All widgets declarations
 */

/**
 * Inits the various widgets
 * @return void
 */
function agora_widgets_init() {
    
    // Add agora widget for displaying available ads
    elgg_register_widget_type('agora', elgg_echo('agora:widget'), elgg_echo('agora:widget:description'), array('profile', 'groups', 'dashboard'));
    
    // Add agora widget for displaying bought
    elgg_register_widget_type('agorabs', elgg_echo('agora:widget:bought'), elgg_echo('agora:widget:bought:description'), array('profile', 'dashboard'));
        
}