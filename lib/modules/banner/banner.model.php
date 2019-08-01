<?

class BannerModel extends BaseModel
{
	
	public function list_page($criteria, $page, $max_regs)
	{
		$data = parent::list_page($criteria, $page, $max_regs);
		$this->set_reg_value($data, 'type');
		$this->set_reg_value($data, 'text');
		
		return $data;
		
	}

}

