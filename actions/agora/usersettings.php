<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */

$user_guid = (int) get_input("user_guid", elgg_get_logged_in_user_guid());
$agora_paypal_account = get_input("agora_paypal_account");

if (empty($user_guid)) {
    return elgg_error_response(elgg_echo('InvalidParameterException:MissingParameter'));
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
        return elgg_ok_response('', elgg_echo('agora:usersettings:update:success'), REFERER);
    } else {
        return elgg_error_response(elgg_echo('agora:usersettings:update:error'));
    }
} else {
    return elgg_error_response(elgg_echo("InvalidClassException:NotValidElggStar", [$user_guid, "ElggUser"]));
}

forward(REFERER);

