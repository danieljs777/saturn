<?

class NewsModel extends BaseModel
{
	public function list_page($criteria, $page, $max_regs)
	{
		$data = parent::list_page($criteria, $page, $max_regs);
		$this->set_reg_value($data, 'category_id');
		$this->set_value($data, 'city_id', 'cities', 'city_id', 'city_name');		
		$this->set_value($data, 'columnist_id', 'columnists', 'c_id', 'c_name');		
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