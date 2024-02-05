<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */


class AgoraFile extends ElggFile {
    const SUBTYPE = "agora_file";
	
    protected function initializeAttributes() {
        parent::initializeAttributes();

        $this->attributes["subtype"] = self::SUBTYPE;
        $this->attributes['access_id'] = ACCESS_PRIVATE;
    }
}

