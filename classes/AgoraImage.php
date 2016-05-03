<?php
/**
 * Elgg Agora Classifieds plugin
 * @package Agora
 */


class AgoraImage extends ElggFile {
	const SUBTYPE = "agoraimg";
	
    protected function initializeAttributes() {
        parent::initializeAttributes();

        $this->attributes["subtype"] = self::SUBTYPE;
    }
}
