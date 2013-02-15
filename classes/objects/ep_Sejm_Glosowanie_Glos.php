<?php

/**
 * @file
 * Ten plik jest częścią biblioteki ePF_API.
 */

/**
 * Klasa obiektu ep_Sejm_Glosowanie_Glos.
 *
 * Alias: sejm_glosowania_glosy
 *
 * @category   System
 * @package    ePF_API
 * @subpackage Objects
 * @version    0.x.x-dev
 * @since      version 0.1.0
 */
class ep_Sejm_Glosowanie_Glos extends ep_Object{

	/**
	 * @see ep_Object::getDataStruct()
	 */
	public function getDataStruct() {
		$result = parent::getDataStruct();
		$result = array_merge($result, array (
			'color' => ep_Object::TYPE_STRING,
			'nazwa' => ep_Object::TYPE_STRING,
			'opis' => ep_Object::TYPE_STRING,
		));
		return $result;
	}

	public $_aliases = array('sejm_glosowania_glosy');

	/**
	 * @return string
	 */
	public function __toString(){
		return $this->get_nazwa();
	}
}