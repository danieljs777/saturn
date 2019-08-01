<?

class CountryModel extends BaseModel
{

	public function list_page($criteria, $page, $max_regs)
	{
		$data = parent::list_page($criteria, $page, $max_regs, "country_name");
		$this->set_reg_value($data, 'continent_id');
		
		return $data;
		
	}

	

	

}