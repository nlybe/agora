<?php
/**
 * Elgg Agora Classifieds plugin
 * @package agora
 */


class AgoraImage extends ElggFile {
    const SUBTYPE = "agora_img";
	
    protected function initializeAttributes() {
        parent::initializeAttributes();

        $this->attributes["subtype"] = self::SUBTYPE;
    }
}
