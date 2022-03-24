<?php

namespace Model;

class Order {
	private $db;

	function __construct($type) {
		$this->db = new MySQLdb(Config::getConfig($type));
	}

	function getBrands($condition = '') {
		return $this->db->getArr("select id, name from brands $condition order by name");
	}

	function getBrandAliases() {
		return $this->db->getArr("select distinct b.name, v.title
		from details d inner join brands b on d.brand_id = b.id 
		inner join visible_brands v on d.visible_brand_id = v.id
		union
		select distinct b.name, s.title
		from details d inner join brands b on d.brand_id = b.id 
		inner join visible_brands v on d.visible_brand_id = v.id
		inner join visible_brands_synonyms s on v.id = s.visible_brand_id");
	}

	function getDetails($brandId) {
		return $this->db->getArr("select id, article, title, brand_id, external_code 
		from details where brand_id = $brandId");
	}

	function addManuf($man) {  
		$sql = "select id, name from applicability_manufacturers where source_code = 2 and catalog_code = " . $man['manuf'];
		$existingMan = $this->db->getArr($sql);
		if($existingMan) {
			if($man['descr'] != $existingMan[0]['name']) {
				echo $man['descr'] . " : " . $existingMan[0]['name'] . "\r\n";
				$sql = "update applicability_manufacturers set name = '" . $man['descr'] . "'
				where id = " . $existingMan[0]['id'] . " and source_code = 2";
				$res = $this->db->runSQL($sql); 
				if (!$res) {
					echo $this->db->err . "<br>\r\n";
					echo $sql . "<br>\r\n";
				}
			}
		} else {
			$sql = "insert into applicability_manufacturers(name, catalog_code, source_code)
			values('".$this->esc($man['descr'])."',".$man['manuf'].",2)";
			$res = $this->db->runSQL($sql); 
			if (!$res) {
				echo $this->db->err . "<br>\r\n";
				echo $sql . "<br>\r\n";
			}
		}
	}

	function esc($str) {
		return $this->db->escapeStr($str);
	}

	function addModel($model) {
		$sql = "select id, name from applicability_models 
		where source_code = 2 and catalog_code = " . $model['model'] . "
		and manufacturer_id in (select id from applicability_manufacturers where catalog_code = ".$model['manuf']." and source_code = 2)";
		$existingMod = $this->db->getArr($sql);
		if($existingMod) {
			if($model['descr'] != $existingMod[0]['name']) {
				echo $model['descr'] . " : " . $existingMod[0]['name'] . "\r\n";
				$sql = "update applicability_models set name = '" . $model['descr'] . "'
				where id = " . $existingMod[0]['id'] . " and source_code = 2 and catalog_code = " . $model['model'] . "
				and manufacturer_id in (select id from applicability_manufacturers where catalog_code = ".$model['manuf']." and source_code = 2)";
				$res = $this->db->runSQL($sql); 
				if (!$res) {
					echo $this->db->err . "<br>\r\n";
					echo $sql . "<br>\r\n";
				}
			}
		} else {
			$sql = "insert into applicability_models(manufacturer_id, name, catalog_code, source_code)
			select id, '".$this->esc($model['descr'])."', ".$model['model'].", 2
			from applicability_manufacturers where catalog_code = ".$model['manuf']." and source_code = 2";
			// echo $sql . "<br>\r\n";
			$res = $this->db->runSQL($sql); 
			if (!$res) {
				echo $this->db->err . "<br>\r\n";
				echo $sql . "<br>\r\n";
			}
		}
	}

	function addCar($car) {
		$st_date = 'null';
		if (!\is_null($car['s'])) {
			$st_date = "'".$car['s']."'";
		}
		$fin_date = 'null';
		if (!\is_null($car['f'])) {
			$fin_date = "'".$car['f']."'";
		}

		$sql = "select id, name from applicability_cars 
		where source_code = 2 and catalog_code = " . $car['car'] . "
		/*and model_id in (select id from applicability_models where catalog_code = ".$car['model']." and source_code = 2)*/";
		$existingCar = $this->db->getArr($sql);
		if($existingCar) {
			if($car['descr'] != $existingCar[0]['name']) {
				// echo $existingCar[0]['id'] . " : " . $car['descr'] . " : " . $existingCar[0]['name'] . "\r\n";
				$sql = "update applicability_cars set name = '" . $car['descr'] . "', start_date_at = " . $st_date . ", finish_date_at = " . $fin_date . ", capacity = " . $car['cap'] . ", capacity_hp_from = " . $car['cap_hp'] . ", capacity_kw_from = " . $car['cap_kw'] . ",
				model_id = (select id from applicability_models where catalog_code = ".$car['model']." and source_code = 2)
				where id = " . $existingCar[0]['id'] . " and source_code = 2 and catalog_code = " . $car['car'] . "
				/*and model_id in (select id from applicability_models where catalog_code = ".$car['model']." and source_code = 2)*/";
				$res = $this->db->runSQL($sql); 
				if (!$res) {
					echo $this->db->err . "<br>\r\n";
					echo $sql . "<br>\r\n";
				}
			}
		} else {
			$sql = "insert into applicability_cars(model_id, name, start_date_at, finish_date_at, capacity, capacity_hp_from, capacity_kw_from, catalog_code, source_code )
			select id, '".$this->esc($car['descr'])."', ".$st_date.", ".$fin_date.", ".$car['cap'].", ".$car['cap_hp'].", ".$car['cap_kw'].", ".$car['car'].", 2
			from applicability_models where catalog_code = ".$car['model']." and source_code = 2";
			$res = $this->db->runSQL($sql); 
			if (!$res) {
				echo $this->db->err . "<br>\r\n";
				echo $sql . "<br>\r\n";
			}
		}
	}

	function addEngine($engine) {
		$sql = "select id, code from applicability_engine where source_code = 2 and catalog_code = " . $engine['id'];
		$existingEng = $this->db->getArr($sql);
		if($existingEng) {
			if($engine['code'] != $existingEng[0]['code']) {
				// echo $engine['code'] . " : " . $existingEng[0]['code'] . "\r\n";
				$sql = "update applicability_engine set code = '" . $engine['code'] . "'
				where id = " . $existingEng[0]['id'] . " and source_code = 2";
				// echo $sql;
				$res = $this->db->runSQL($sql); 
				if (!$res) {
					echo $this->db->err . "<br>\r\n";
					echo $sql . "<br>\r\n";
				}
			}
		} else {
			$sql = "insert into applicability_engine(code, catalog_code, source_code )
			values('".$this->esc($engine['code'])."',".$engine['id'].",2)";
			// echo $sql . "<br>\r\n";
			$res = $this->db->runSQL($sql); 
			if (!$res) {
				echo $this->db->err . "<br>\r\n";
				echo $sql . "<br>\r\n";
			}
		}
	}

	function addEngineCar($engineCar) {
		$sql = "insert ignore into applicability_cars_engine(car_id, engine_id)
			select (select c.id car
			from applicability_cars c
			where c.catalog_code = ".$engineCar['car']." and c.source_code = 2) car_id,
			(select e.id from applicability_engine e
			where e.catalog_code = ".$engineCar['engine_id']." and e.source_code = 2) eng_id";
		// echo $sql . "<br>\r\n";
		$res = $this->db->runSQL($sql); 
		if (!$res) {
			echo $this->db->err . "<br>\r\n";
			echo $sql . "<br>\r\n";
		}
	}

	function getApplicCountByBrand($brand_id) {
		$sql = "select count(detail_id) c from detail_applicability 
		where detail_id in(select id from details where brand_id = $brand_id)";
		//  echo $sql;
		$res = ($this->db->getArr($sql))[0]['c']; 
		return $res;
	}

	function clearApplicByBrand($brand_id) {
		$sql = "delete from detail_applicability 
		where detail_id in(select id from details where brand_id = $brand_id)";
		//  echo $sql;
		$res = $this->db->runSQL($sql); 
		if (!$res) {
			echo $this->db->err . "<br>\r\n";
			echo $sql . "<br>\r\n";
		}
	}

	function addApplic($applic) {
		$sql = "insert ignore into detail_applicability(detail_id, car_id) 
		select ".$applic['id'].", id 
		from applicability_cars where source_code = 2 and catalog_code = ".$applic['car'].";";
		// echo $sql . "\r\n";
		$res = $this->db->runSQL($sql); 
		if (!$res) {
			echo $this->db->err . "<br>\r\n";
			echo $sql . "<br>\r\n";
		}
	}

	function clearCharactsByBrand($brand_id) {
		$sql = "delete from detail_info where detail_id in(select id from details where brand_id = $brand_id) and catalog_code <> 0";
		// echo $sql;
		$res = $this->db->runSQL($sql); 
		if (!$res) {
			echo $this->db->err . "<br>\r\n";
			echo $sql . "<br>\r\n";
		}
	}

	function addCharactVal($detail_id, $charact) {
		// $sql = "insert into detail_info(attribute_id, detail_id,value,catalog_code) select id, ".$detail_id.",'".$this->esc($charact['val'])."',1 from detail_attributes where catalog_code = ".$charact['numb']." ON DUPLICATE KEY UPDATE value='".$this->esc($charact['val'])."'";

		$sql = "insert ignore into detail_info(attribute_id, detail_id,value,catalog_code,source_code,sort_no) 
		select id, ".$detail_id.",'".$this->esc($charact['val'])."',1,2,".$charact['sort_no']." from detail_attributes where catalog_code = ".$charact['numb']." and source_code=2"; 
		$res = $this->db->runSQL($sql); 
		if (!$res) {
			echo $this->db->err . "<br>\r\n";
			echo $sql . "<br>\r\n";
		}
	}

	function addCharactAttr($charact) {
		$sql = "insert ignore into detail_attributes(name, title, external_code, is_filter, priority, is_specification, catalog_code, source_code) 
		values('".$this->esc($charact['descr'])."', '".$this->esc($charact['descr'])."', null, 0, 0, 0, ".$charact['numb'].",2);";
		$res = $this->db->runSQL($sql);
		if (!$res) {
			echo $this->db->err . "<br>\r\n";
			echo $sql . "<br>\r\n";
		}
	}
}