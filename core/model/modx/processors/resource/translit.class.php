<?php
/**
 * Retrieves a string and returns it transliterated to use in various applications but mainly for real-time alias
 *
 * @param string $string The string to transliterate
 * @return string
 *
 * @package modx
 * @subpackage processors.resource
 */
class modTranslitProcessor extends modProcessor {

	public function process() {
		$string = $this->getProperty('string');
		$transliteration = array(
			'input' => $string,
			'transliteration' => $this->modx->call('modResource', 'filterPathSegment', array(&$this->modx, $string)),
		);

		return $this->success('', $transliteration);
	}
}
return 'modTranslitProcessor';