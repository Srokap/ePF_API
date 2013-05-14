<?php

/**
 * @file
 * Ten plik jest częścią biblioteki ePF_API.
 */

/**
 * Obiekt ep_Posel.
 *
 * Aliasy:
 *   poslowie
 *   ludzie_poslowie
 *
 * Przykładowe zastosowanie:
 * <code>
 * 	 $searcher = new ep_Search();
 *	 $searcher->setDataset('poslowie')->load();
 *
 *   $objects = $searcher->getObjects();
 *   $pagination = $searcher->getPagination();
 * </code>
 *
 *  * Dostępne dodatkowe warstwy danych:
 * stat
 * info
 *
 * Przykład:
 * <code>
 * 	 $data = $object->load_layer('stat');
 * </code>
 *
 * @example objects/ep_Posel
 *
 * @see ep_Posel::$_aliases
 *
 * @category   System
 * @package    ePF_API
 * @subpackage Objects
 * @version    0.x.x-dev
 * @since      version 0.1.0
 */
class ep_Posel extends ep_Object{

	/**
	 * @see ep_Object::getDataStruct()
	 */
	public function getDataStruct() {
		$result = parent::getDataStruct();
		$result = array_merge($result, array (
			'nazwa' => ep_Object::TYPE_STRING,
			'nazwisko' => ep_Object::TYPE_STRING,
			'zawod' => ep_Object::TYPE_STRING,
			'plec' => ep_Object::TYPE_STRING,
			'data_urodzenia' => ep_Object::TYPE_STRING,
			'miejsce_urodzenia' => ep_Object::TYPE_STRING,
			'mowca_id' => ep_Object::TYPE_STRING,
			'biuro_html' => ep_Object::TYPE_STRING,
			'dopelniacz' => ep_Object::TYPE_STRING,
			'frekwencja' => ep_Object::TYPE_STRING,
			'imie_drugie' => ep_Object::TYPE_STRING,
			'imie_pierwsze' => ep_Object::TYPE_STRING,
			'klub_id' => ep_Object::TYPE_STRING,
			'liczba_glosow' => ep_Object::TYPE_STRING,
			'liczba_glosowan' => ep_Object::TYPE_STRING,
			'liczba_glosowan_opuszczonych' => ep_Object::TYPE_STRING,
			'liczba_glosowan_zbuntowanych' => ep_Object::TYPE_STRING,
			'liczba_projektow_uchwal' => ep_Object::TYPE_STRING,
			'liczba_projektow_ustaw' => ep_Object::TYPE_STRING,
			'liczba_slow' => ep_Object::TYPE_STRING,
			'liczba_wnioskow' => ep_Object::TYPE_STRING,
			'liczba_wypowiedzi' => ep_Object::TYPE_STRING,
			'miejsce_zamieszkania' => ep_Object::TYPE_STRING,
			'nazwa_odwrocona' => ep_Object::TYPE_STRING,
			'numer_na_liscie' => ep_Object::TYPE_STRING,
			'okreg_wyborczy_numer' => ep_Object::TYPE_STRING,
			'procent_glosow' => ep_Object::TYPE_STRING,
			'sejm_okreg_id' => ep_Object::TYPE_STRING,
			'zbuntowanie' => ep_Object::TYPE_STRING,
		));
		return $result;
	}

	/**
	 * @var array
	 */
	public $_aliases = array('poslowie','ludzie_poslowie');
	
	/**
	 * @return ep_Search
	 */
	public function search(){
		
		$search = new ep_Search();
		$search->addRawFilter('( (dataset:sejm_wystapienia AND _data_czlowiek_id:"' . $this->data['ludzie.id'] . '") OR (dataset:sejm_interpelacje AND _multidata_posel_id:"' . $this->data['id'] . '") )');
		return $search;
		
	}
}
