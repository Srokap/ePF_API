<?php

/**
 * @file
 * Ten plik jest częścią biblioteki ePF_API.
 */

/**
 * Obiekt ep_Senat_Druk.
 *
 * Aliasy:
 *   senat_druki
 *
 * Przykładowe zastosowanie:
 * <code>
 * 	 $searcher = new ep_Search();
 *	 $searcher->setDataset('senat_druki')->load();
 *
 *   $objects = $searcher->getObjects();
 *   $pagination = $searcher->getPagination();
 * </code>
 * @example objects/ep_Senat_Druk
 *
 * @see ep_Senat_Druk::$_aliases
 *
 * @category   System
 * @package    ePF_API
 * @subpackage Objects
 * @version    0.x.x-dev
 * @since      version 0.1.0
 * 
 * @method int get_id()
 * @method string get_data()
 * @method string get_dokument_id()
 * @method string get_numer()
 * @method string get_tytul()
 */
class ep_Senat_Druk extends ep_Object{

	/**
	 * @see ep_Object::getDataStruct()
	 */
	public function getDataStruct() {
		$result = parent::getDataStruct();
		$result = array_merge($result, array (
			'data' => ep_Object::TYPE_STRING,
			'dokument_id' => ep_Object::TYPE_STRING,
			'numer' => ep_Object::TYPE_STRING,
			'tytul' => ep_Object::TYPE_STRING,
		));
		return $result;
	}

	/**
	 * @var array
	 */
	public $_aliases = array('senat_druki');

	/**
	 * @return string
	 */
	public function getDate(){
		return (string) $this->data['data'];
	}	
}
