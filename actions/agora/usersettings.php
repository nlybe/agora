<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

$user_guid = (int) get_input("user_guid", elgg_get_logged_in_user_guid());
$agora_paypal_account = get_input("agora_paypal_account");

if (empty($user_guid)) {
    register_error(elgg_echo("InvalidParameterException:MissingParameter"));
    forward(REFERER);
}

if (($user = get_user($user_guid)) && $user->canEdit()) {
    $error_count = 0;
    if (!empty($agora_paypal_account)) {
        if (!($user->setPrivateSetting("agora_paypal_account", $agora_paypal_account))) {
            $error_count++;
        }
    } else {
        $user->removePrivateSetting("agora_paypal_account");
    }

    if ($error_count == 0) {
        system_message(elgg_echo("agora:usersettings:update:success"));
    } else {
        register_error(elgg_echo("agora:usersettings:update:error"));
    }
} else {
    register_error(elgg_echo("InvalidClassException:NotValidElggStar", array($user_guid, "ElggUser")));
}

forward(REFERER);

