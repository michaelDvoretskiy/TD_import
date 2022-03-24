<?php

namespace Model;

class TD {
	private $db;

	function __construct($type) {
		$this->db = new MySQLdb(Config::getConfig($type));
	}

	function esc($str) {
		return $this->db->escapeStr($str);
	}

	function addUtrBrand($brand) {
		$this->db->runSQL("insert ignore into utr_brands(id, name)
		values( ".$brand['id'].", '".$brand['name']."');");
	}

	function addUtrBrandAlias($brandAlias) {
		$this->db->runSQL("insert ignore into utr_brands_aliases(name, alias)
		values( '".$brandAlias['name']."', '".$brandAlias['title']."');");
	}

	function getBrandsByUtrId($utrId) {
		return $this->db->getArr("select id, name from td_brands where id in(
			select td_id from td_utr_brands where utr_id = $utrId)");
	}

	function fillTdUtrBrandsEquals() {
		$this->db->runSQL("insert ignore into td_utr_brands(td_id, utr_id)
		select tb.id, ub.id 
		from td_brands tb inner join utr_brands ub on upper(tb.name) = upper(ub.name)");
	}

	function fillTdUtrBrandsByAliases() {
		$this->db->runSQL("insert ignore into td_utr_brands(td_id, utr_id)
		select tb.id, ub.id 
		from utr_brands ub inner join utr_brands_aliases uba on ub.name = uba.name 
		inner join td_brands tb on upper(uba.alias) = upper(tb.name)
		where ub.name <> tb.name");
	}
	
	function addManufData_100($manuf) {
		$sql = "insert ignore into td_manuf_data_100(manuf, pc, cv, lcv, descr_numb, short_name) 
		values(".$manuf['id'].",".$manuf['pc'].",".$manuf['cv'].",".$manuf['lcv'].", '".$manuf['descr_numb']."', '".$manuf['short_name']."')";
		$this->db->runSQL($sql);
	}

	function updateManufDescription() {
		$sql = "update td_manuf_data_100 set descr = (select max(descr) 
		from td_descr_012 
		where lang in(4,16) and number = td_manuf_data_100.descr_numb)";
		$this->db->runSQL($sql);
	}

	function getManufList() {
		$sql = "select manuf, descr from td_manuf_data_100 order by manuf";
		return $this->db->getArr($sql);
	}

	function addModel_110($model) {
		$sql = "insert ignore into td_model_data_110(model, manuf, descr_numb) 
		values(".$model['model'].", ".$model['manuf'].", '".$model['descr_numb']."')";
		$this->db->runSQL($sql);
	}

	function updateModelDescription() {
		$sql = "update td_model_data_110 set descr = (select max(descr) 
		from td_descr_012 
		where lang in(4,16) and number = td_model_data_110.descr_numb)";
		$this->db->runSQL($sql);
	}

	function getModelList() {
		$sql = "select model, manuf, descr from td_model_data_110 order by manuf, model";
		return $this->db->getArr($sql);
	}

	function addDescription_012($descr) {
		//добавить фильтр, писать только языки (4,16)
		if (in_array($descr['lang'],['004','016'])) {
			$sql = "insert ignore into td_descr_012(descr, number, country, lang) 
			values('".$this->esc($descr['descr'])."','".$this->esc($descr['number'])."','".$this->esc($descr['country'])."','".$this->esc($descr['lang'])."')";
			$this->db->runSQL($sql);
		}
	}

	function addDescription030($descr) {
		if (in_array($descr['lang'],['004','016'])) {
			$sql = "insert ignore into td_descr_030(numb, lang, descr) 
			values(".$this->esc($descr['number']).",".$this->esc($descr['lang']).",'".$this->esc($descr['descr'])."')";
			$this->db->runSQL($sql);
		}
	}

	function addCreteria050($crit) {
		$sql = "insert ignore into td_charact_050(numb, descr_numb, typ, tabnr) 
		values(".$this->esc($crit['number']).",".$this->esc($crit['descr_numb']).",'".$this->esc($crit['typ'])."',".$this->esc($crit['tabnr']).")";
		// echo $sql . "\n\r"; 
		$this->db->runSQL($sql);
	}

	function addKeyEntry052($ent) {
		$sql = "insert ignore into td_key_entries_052(tabnr, keynr, descr_numb) 
		values(".$this->esc($ent['tabnr']).",'".$this->esc($ent['keynr'])."',".$ent['descr_numb'].")";
		// echo $sql . "\n\r"; 
		$this->db->runSQL($sql);
	}

	function addCar_120_532($car) {
		if (trim($car['sort_nr'])=='') {
			$car['sort_nr'] = 0;
		}
		$sql = "insert into td_car_data_120_532(car, model, descr_numb, d_from, d_to, cap_l, cap_hp, cap_kw, tbl, sort_nr) 
		values(".$car['car'].",".$car['model'].",'".$this->esc($car['descr_numb'])."','".$this->esc($car['d_from'])."',
		'".$this->esc($car['d_to'])."','".$this->esc($car['cap_l'])."','".$this->esc($car['cap_hp'])."',
		'".$this->esc($car['cap_kw'])."','".$car['tbl']."', ".$car['sort_nr'].")
		on duplicate key update sort_nr = ".$car['sort_nr'].";";
		// echo $sql . "\n\r"; 
		$this->db->runSQL($sql);
	}

	function addArticleCharact($charact) {
		$filtered_article = preg_replace('/[^\d\pL]+/iu', '', $charact['article']);
		$sql = "insert ignore into td_article_charact_210(article, brand_id, charact_numb, charact_value, sort_no, filtered_article) 
		values('".$this->esc($charact['article'])."',".$this->esc($charact['suppl']).",".$this->esc($charact['numb']).",'".$this->esc($charact['descr'])."',".$this->esc($charact['sort_nr']).",'".$this->esc($filtered_article)."')";
		// echo $sql . "\n\r"; 
		$this->db->runSQL($sql);
	}

	function getCharatsByBrand2($brandId) {
		$sql = "select t.charact_value val, charact_numb numb, did, sort_no
		from 
		(select ud.id did, c.tabnr, charact_value, charact_numb, ac.sort_no, max(d.descr) d
		from td_article_charact_210 ac 
		inner join td_charact_050 c on ac.charact_numb = c.numb
		inner join td_descr_030 d on c.descr_numb = d.numb
		inner join (select id, filtered_acticle from utr_details where brand_id = $brandId) ud on ac.filtered_article = ud.filtered_acticle
		where ac.brand_id in(select td_id from td_utr_brands where utr_id = $brandId) and d.lang in(4,16) and c.typ <> 'K'
		group by ud.id, c.tabnr, charact_numb, charact_value, ac.sort_no) t
		union
		select k.d val, charact_numb, did, sort_no
		from 
		(select ud.id did, c.tabnr, charact_value, charact_numb, ac.sort_no, max(d.descr) d
		from td_article_charact_210 ac 
		inner join td_charact_050 c on ac.charact_numb = c.numb
		inner join td_descr_030 d on c.descr_numb = d.numb
		inner join (select id, filtered_acticle from utr_details where brand_id = $brandId) ud on ac.filtered_article = ud.filtered_acticle
		where ac.brand_id in(select td_id from td_utr_brands where utr_id = $brandId) and d.lang in(4,16) and c.typ = 'K'
		group by ud.id, c.tabnr, charact_numb, charact_value, ac.sort_no) t
		inner join (select tabnr, keynr, case when keynr='000' then 0 
		when CONVERT(keynr, UNSIGNED INTEGER)=0 then keynr
		else CONVERT(keynr, UNSIGNED INTEGER) end keynr2, max(descr) d
		from td_key_entries_052 ke inner join td_descr_030 dke on ke.descr_numb = dke.numb
		where lang in(4,16)
		group by tabnr, keynr) k on t.tabnr = k.tabnr and t.charact_value = k.keynr2";
		// echo $sql;		
		return $this->db->getArr($sql);
	}

	function getCharactAttrList() {
		$sql = "SELECT c.numb, max(descr) descr 
		FROM td_charact_050 c inner join td_descr_030 d on c.descr_numb = d.numb
		where lang in(4,16)
		group by c.numb";
		return $this->db->getArr($sql);
	}

	function updateCarDescription() {
		$sql = "update td_car_data_120_532 set descr = (select max(descr) 
		from td_descr_012 
		where lang in(4,16) and number = td_car_data_120_532.descr_numb)";
		$this->db->runSQL($sql);
	}

	function getCarList() {
		$sql = "select model, descr, 
		if(d_from!='',DATE_ADD(MAKEDATE(substr(d_from,1,4), 1), INTERVAL (substr(d_from,5,2))-1 MONTH),null) s,
		if(d_to!='',DATE_ADD(MAKEDATE(substr(d_to,1,4), 1), INTERVAL (substr(d_to,5,2))-1 MONTH), null) f,
		round(cap_l/1000,1) cap, cap_hp, cap_kw, tbl*1000000+car car  
		from td_car_data_120_532 c";
		return $this->db->getArr($sql);
	}

	function addEngine_155($engine) {
		$sql = "insert into td_engine_155(id, code) values(".$engine['id'].",'".$engine['code']."')";
		// echo $sql . "\n\r"; 
		$this->db->runSQL($sql);
	}

	function getEngineList() {
		$sql = "select id, code from td_engine_155";
		return $this->db->getArr($sql);
	}

	function addEngineCar_125_537($engineCar) {
		$sql = "insert into td_engine_car_125_537(engine_id, car, tbl) values(".$engineCar['engine_id'].",".$engineCar['car'].",".$engineCar['tbl'].")";
		// echo $sql . "\n\r"; 
		$this->db->runSQL($sql);
	}

	function getEngineCarList() {
		$sql = "select engine_id, tbl*1000000+car car from td_engine_car_125_537;";
		return $this->db->getArr($sql);
	}

	function addUtrDetail($detail) {
		// var_dump($detail);
		$filteredArticle = preg_replace('/[^\d\pL]+/iu', '', $detail['article']);
		if (!is_null($detail['external_code'])) {
			$sql = "insert into utr_details(id, article, brand_id, external_code, filtered_acticle) values(".$detail['id'].",'".$this->esc($detail['article'])."',".$detail['brand_id'].", '".$this->esc($detail['external_code'])."','".$this->esc($filteredArticle)."')
			on duplicate key update external_code = '".$this->esc($detail['external_code'])."'";
			
		} else {
			$sql = "insert ignore into utr_details(id, article, brand_id, filtered_acticle) values(".$detail['id'].",'".$this->esc($detail['article'])."',".$detail['brand_id'].",'".$this->esc($filteredArticle)."')";		
		}		
		// echo $sql . "\r\n";
		$this->db->runSQL($sql);
	}

	function addApplic_400($applic, $table_index = "") {
		$articleFilter = preg_replace('/[^\d\pL]+/iu', '', $applic['article']);
		// $sql = "insert into applic_400(article, suppl, car, tbl_type, filtered_art) values('".$this->esc($applic['article'])."',".$applic['suppl'].",".$applic['car'].",".$applic['tbl_type'].", '".$this->esc($articleFilter)."')";

		$sql = "insert ignore into td_applic_400".$table_index."(article, suppl, car, tbl_type, filtered_art, detail_id) 
		select distinct '".$this->esc($applic['article'])."', ".$applic['suppl'].",".$applic['car'].",".$applic['tbl_type'].", filtered_acticle, d.id from utr_details d inner join td_utr_brands b on d.brand_id = b.utr_id where filtered_acticle = '".$this->esc($articleFilter)."' and b.td_id = ".$applic['suppl']."";

		// echo $sql . "\n\r"; 
		$this->db->runSQL($sql);
	}

	function getApplicability($brandId) {
		$sql = "select distinct d.id, tbl_type*1000000+car car  
		from td_applic_400 a inner join 
		(select id, filtered_acticle from utr_details where brand_id = $brandId) d 
		on a.filtered_art = d.filtered_acticle
		where suppl in(select td_id from td_utr_brands where utr_id = $brandId)";
				
		$arr1 = $this->db->getArr($sql);

		$sql = "select distinct d.id, tbl_type*1000000+car car  
		from td_applic_400_2 a inner join 
		(select id, filtered_acticle from utr_details where brand_id = $brandId) d 
		on a.filtered_art = d.filtered_acticle
		where suppl in(select td_id from td_utr_brands where utr_id = $brandId)";
				
		$arr2 = $this->db->getArr($sql);

		return array_merge($arr1, $arr2);
	}
}