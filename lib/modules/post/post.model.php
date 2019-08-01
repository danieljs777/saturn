<?

class PostModel extends BaseModel
{
	

	public function list_by_continent($ctt_id)
	{
		$sql = "SELECT DISTINCT countries.country_name, countries.country_id, cities.city_id, city_name
				FROM posts, cities, countries
				WHERE posts.city_id = cities.city_id
				AND countries.country_id = cities.country_id
				AND countries.continent_id = $ctt_id
				AND cities.preview = 0				
				AND posts.preview = 0				
				ORDER BY posts.publish_at DESC";
		
		$data = $this->database->execute($sql);
		
		return $data;
	}

	public function list_by_country($ctr_id)
	{
		$sql = "SELECT DISTINCT posts.city_id, city_name
				FROM posts, cities
				WHERE posts.city_id = cities.city_id
				AND $ctr_id = cities.country_id
				AND cities.preview = 0				
				AND posts.preview = 0
				ORDER BY posts.publish_at DESC";
		
		$data = $this->database->execute($sql);
		
		return $data;
	}

	public function list_by_city($cty_id)
	{
		
		$data = $this->list_all(array("city_id" => $cty_id));
		
		return $data;
	}

	public function list_page($criteria, $page, $max_regs, $order = null)
	{
		$data = parent::list_page($criteria, $page, $max_regs, $order);
		$this->set_reg_value($data, 'section');
		$this->set_value($data, 'city_id', 'cities', 'city_id', 'city_name');
		
		return $data;
		
	}

	public function list_all($criteria, $order)
	{
		$data = parent::list_all($criteria, $order);
		$this->set_reg_value($data, 'section');
		$this->set_value($data, 'city_id', 'cities', 'city_id', 'city_name');	
		
		return $data;
		
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

