<?php

/**
 * @file
 * Ten plik jest częścią biblioteki ePF_API.
 */

/**
 * Obiekt ep_Ustawa.
 *
 * Aliasy:
 *   ustawy
 *   prawo_typy
 *   isap_pliki
 *
 * Przykładowe zastosowanie:
 * <code>
 * 	 $searcher = new ep_Search();
 *	 $searcher->setDataset('ustawy')->load();
 *
 *   $objects = $searcher->getObjects();
 *   $pagination = $searcher->getPagination();
 * </code>
 *
 * @example objects/ep_Ustawa
 *
 * @see ep_Ustawa::$_aliases
 *
 * @category   System
 * @package    ePF_API
 * @subpackage Objects
 * @version    0.x.x-dev
 * @since      version 0.1.0
 */
class ep_Ustawa extends ep_Object{

	/**
	 * @see ep_Object::getDataStruct()
	 */
	public function getDataStruct() {
		$result = parent::getDataStruct();
		$result = array_merge($result, array (
			'dokument_id' => ep_Object::TYPE_STRING,
			'isip_plik_typ_id' => ep_Object::TYPE_STRING,
			'dzial_id' => ep_Object::TYPE_STRING,
			'autor_id' => ep_Object::TYPE_STRING,
			'data_publikacji' => ep_Object::TYPE_STRING,
			'data_wejscia_w_zycie' => ep_Object::TYPE_STRING,
			'data_wydania' => ep_Object::TYPE_STRING,
			'isap_data_uchylenia' => ep_Object::TYPE_STRING,
			'isap_data_wygasniecia' => ep_Object::TYPE_STRING,
			'isap_id' => ep_Object::TYPE_STRING,
			'isap_uwagi_str' => ep_Object::TYPE_STRING,
			'status_id' => ep_Object::TYPE_STRING,
			'sygnatura' => ep_Object::TYPE_STRING,
			'typ_id' => ep_Object::TYPE_STRING,
			'typ_nazwa' => ep_Object::TYPE_STRING,
			'tytul' => ep_Object::TYPE_STRING,
			'tytul_skrocony' => ep_Object::TYPE_STRING,
			'zrodlo' => ep_Object::TYPE_STRING,
		));
		return $result;
	}

	/**
	 * @var array
	 */
	public $_aliases = array('ustawy', 'prawo');	

	/**
	 * @return string
	 */
	public function __toString(){
		return $this->get_nazwa();
	}

	/**
	 * @return string
	 */	
	public function getDate(){
		return $this->data['data_wydania'];
	}
}
