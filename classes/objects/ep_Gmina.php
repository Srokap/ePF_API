<?php

/**
 * @file
 * Ten plik jest częścią biblioteki ePF_API.
 */

/**
 * Obiekt ep_Gmina.
 *
 * Aliasy:
 *   gminy
 *
 * Przykładowe zastosowanie:
 * <code>
 * 	 $searcher = new ep_Search();
 *	 $searcher->setDataset('gminy')->load();
 *
 *   $objects = $searcher->getObjects();
 *   $pagination = $searcher->getPagination();
 * </code>
 *
 * Dostępne dodatkowe warstwy danych:
 * spat
 * enspat
 *
 * Przykład:
 * <code>
 * 	 $data = $object->load_layer('spat');
 * </code>
 
 * @example objects/ep_Gmina
 *
 * @see ep_Gmina::$_aliases
 *
 * @category   System
 * @package    ePF_API
 * @subpackage Objects
 * @version    0.x.x-dev
 * @since      version 0.1.0
 */
class ep_Gmina extends ep_Object{

	/**
	 * @see ep_Object::getDataStruct()
	 */
	public function getDataStruct() {
		$result = parent::getDataStruct();
		$result = array_merge($result, array (
			'nazwa' => ep_Object::TYPE_STRING,
			'typ_id' => ep_Object::TYPE_INT,
			'adres' => ep_Object::TYPE_STRING,
			'bip_www' => ep_Object::TYPE_STRING,
			'dochody_roczne' => ep_Object::TYPE_STRING,
			'email' => ep_Object::TYPE_STRING,
			'fax' => ep_Object::TYPE_STRING,
			'liczba_ludnosci' => ep_Object::TYPE_STRING,
			'nazwa_urzedu' => ep_Object::TYPE_STRING,
			'nts' => ep_Object::TYPE_STRING,
			'powiat_id' => ep_Object::TYPE_STRING,
			'powierzchnia' => ep_Object::TYPE_STRING,
			'rada_nazwa' => ep_Object::TYPE_STRING,
			'szef_stanowisko_id' => ep_Object::TYPE_STRING,
			'telefon' => ep_Object::TYPE_STRING,
			'teryt' => ep_Object::TYPE_STRING,
			'wojewodztwo_id' => ep_Object::TYPE_STRING,
			'wydatki_roczne' => ep_Object::TYPE_STRING,
			'zadluzenie_roczne' => ep_Object::TYPE_STRING,
			'typ_nazwa' => ep_Object::TYPE_STRING,
		));
		return $result;
	}

	/**
	 * @var array
	 */
	public $_aliases = array('gminy');
	

	public function parse_data($data){
		parent::parse_data($data);

		switch( $this->data['typ_id'] ) {
			case '1': {
				$this->data['typ_nazwa'] = 'Gmina miejska'; break;
			}
			case '2': {
				$this->data['typ_nazwa'] = 'Gmina wiejska'; break;
			}
			case '3': {
				$this->data['typ_nazwa'] = 'Gmina miejsko-wiejska'; break;
			}
			case '4': {
				$this->data['typ_nazwa'] = 'Miasto stołeczne'; break;
			}
		}
	}

}
