<?

class ParameterModel extends BaseModel
{
	public function list_page($criteria, $page, $max_regs)
	{
		$data = parent::list_page($criteria, $page, $max_regs);
		$this->set_value($data, 't_id', 'sys_parameters_tables', 't_id', 't_name');
		
		return $data;
		
	}
	
	public function get_label($id)
	{
		$data = $this->select_first(array("p_id" => $id));
		
		if(isset($data['p_name']))
			return $data['p_name'];
		else
			return "";
	}
	
	public function update($data)
	{
		$data['seo_hash'] = System::generate_seo_link($data['p_name']);
		return $this->database->update($this->table_name, $data, array($this->id_column => $data[$this->id_column]));
	}
	
	public function add($data)
	{
		$data['seo_hash'] = System::generate_seo_link($data['p_name']);		
		return $this->database->insert($this->table_name, $data);
	}
	

}