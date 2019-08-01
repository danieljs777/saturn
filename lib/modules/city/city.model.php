<?

class CityModel extends BaseModel
{
	public function list_page($criteria, $page, $max_regs)
	{
		$data = parent::list_page($criteria, $page, $max_regs);
		$this->set_value($data, 'country_id', 'countries', 'country_id', 'country_name');
		
		return $data;
		
	}

	
	public function create()
	{
		$this->set_method('edit');
		parent::edit();
	}
	
	
	public function get_city_name($id)
	{
		$data = $this->get_info($id);
		return $data['city_name'];
	}
	
	public function list_complete()
	{
		$sql = "SELECT DISTINCT p_id continent_id, p_name continent_name, country_name, countries.country_id, city_id, city_name
				FROM cities, countries, sys_parameters
				WHERE countries.country_id = cities.country_id
				AND sys_parameters.p_id = countries.continent_id
				AND preview = 0
				GROUP BY p_id, p_name, country_name, countries.country_id, city_id, city_name
				ORDER BY 1, 2, country_name, country_id, city_id, city_name DESC";
		
		$data = $this->database->execute($sql);
		
		return $data;
	}
	
	public function show_complete($city_id)
	{
		$sql = "SELECT p_id continent_id, p_name continent_name, country_name, cities.*
				FROM cities, countries, sys_parameters
				WHERE countries.country_id = cities.country_id
				AND sys_parameters.p_id = countries.continent_id
				AND cities.city_id = $city_id
				AND preview = 0				
				ORDER BY 1, 2, country_name, country_id, city_id, city_name DESC ";
		
		$data = $this->database->execute($sql);
		
		return $data[0];
	}
	
	public function list_random_podcast()
	{
		$sql = "SELECT *
				FROM cities_podcasts
				ORDER BY RAND() LIMIT 0,1";
		
		$data = $this->database->execute($sql);
		
		if(System::is_filled($data))
			return $data[0];
		
	}
	
	
	public function list_videos($id)
	{
		$id_column = $this->id_column;
		$criteria[$id_column] = $id;
			
		return $this->database->select($this->table_name . "_videos", $criteria);
		
	}
	
	
	public function save_video($data)
	{
		return $this->database->insert($this->table_name . "_videos", $data);
	}
	
	public function delete_video($criteria)
	{
		return $this->database->delete_many($this->table_name . "_video", $criteria);
	}
	
	
	public function list_podcasts($id)
	{
		$id_column = $this->id_column;
		$criteria[$id_column] = $id;
			
		return $this->database->select($this->table_name . "_podcasts", $criteria);
		
	}
	
	
	public function save_podcast($data)
	{
		return $this->database->insert($this->table_name . "_podcasts", $data);
	}
	
	public function delete_podcasts($criteria)
	{
		return $this->database->delete_many($this->table_name . "_podcasts", $criteria);
	}
	

}