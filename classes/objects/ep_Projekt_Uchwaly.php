<?php

/**
 * @file
 * Ten plik jest częścią biblioteki ePF_API.
 */

/**
 * Obiekt ep_Projekt_Uchwaly.
 *
 * Aliasy:
 *   legislacja_projekty_ustaw
 *   legislacja_projekty_uchwal
 *   legislacja_projekty
 *   konsultacje
 *
 * Przykładowe zastosowanie:
 * <code>
 * 	 $searcher = new ep_Search();
 *	 $searcher->setDataset('legislacja_projekty_uchwal')->load();
 *
 *   $objects = $searcher->getObjects();
 *   $pagination = $searcher->getPagination();
 * </code>
 *
 * @example objects/ep_Projekt_Uchwaly
 *
 * @see ep_Legislacja_Projekt::$_aliases
 *
 * @category   System
 * @package    ePF_API
 * @subpackage Objects
 * @version    0.x.x-dev
 * @since      version 0.1.0
 */
class ep_Projekt_Uchwaly extends ep_Object{

	/**
	 * @see ep_Object::getDataStruct()
	 */
	public function getDataStruct() {
		$result = parent::getDataStruct();
		$result = array_merge($result, array (
			'autorzy_html' => ep_Object::TYPE_STRING,
			'autor_typ_id' => ep_Object::TYPE_STRING,
			'data' => ep_Object::TYPE_STRING,
			'dokument_id' => ep_Object::TYPE_STRING,
			'opis' => ep_Object::TYPE_STRING,
			'opis_skrocony' => ep_Object::TYPE_STRING,
			'ostatnia_tresc_etap_id' => ep_Object::TYPE_STRING,
			'ostatni_etap_id' => ep_Object::TYPE_STRING,
			'rcl_projekt_id' => ep_Object::TYPE_STRING,
			'status_data' => ep_Object::TYPE_STRING,
			'status_id' => ep_Object::TYPE_STRING,
			'status_str' => ep_Object::TYPE_STRING,
			'typ_id' => ep_Object::TYPE_STRING,
			'tytul' => ep_Object::TYPE_STRING,
			'tytul_skrocony' => ep_Object::TYPE_STRING,
		));
		return $result;
	}

	/**
	 * @var array
	 */
	public $_aliases = array('legislacja_projekty_uchwal', 'legislacja_projekty');
	
	/**
	 * @var string
	 */
	public $_field_init_lookup = 'tytul';

	/**
	 * @var string
	 */
	public function getDate(){
		return $this->data['data'];
	}
}
