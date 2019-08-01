<?

class AdvertisingModel extends BaseModel
{
	
	public function list_page($criteria, $page, $max_regs)
	{
		$data = parent::list_page($criteria, $page, $max_regs);
		$this->set_reg_value($data, 'position');
		
		return $data;
		
	}

}

