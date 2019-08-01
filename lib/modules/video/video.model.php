<?

class VideoModel extends BaseModel
{
	
	public function list_page($criteria, $page, $max_regs)
	{
		$data = parent::list_page($criteria, $page, $max_regs);
		$this->set_reg_value($data, 'type');
		$this->set_value($data, 'city_id', 'cities', 'city_id', 'city_name');
		
		return $data;
		
	}

	
	public function list_complete()
	{
		$sql = "SELECT DISTINCT p_id continent_id, p_name continent_name, country_name, countries.country_id, cities.city_id, city_name
				FROM cities, countries, sys_parameters, videos
				WHERE videos.city_id = cities.city_id
				AND countries.country_id = cities.country_id
				AND sys_parameters.p_id = countries.continent_id
				GROUP BY p_id, p_name, country_name, countries.country_id, city_id, city_name
				ORDER BY 1, 2, country_name, country_id, city_id, city_name DESC";
		
		$data = $this->database->execute($sql);
		
		return $data;
	}
	



}