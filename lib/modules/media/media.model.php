<?

class MediaModel extends BaseModel
{
	
	public function list_page($criteria, $page, $max_regs)
	{
		$data = parent::list_page($criteria, $page, $max_regs);
		$this->set_reg_value($data, 'category');
		
		return $data;
		
	}
	
	public function list_all($criteria, $order)
	{
		$data = parent::list_all($criteria, $order);
		$this->set_reg_value($data, 'category', 'category_lbl');
		
		return $data;
		
	}
	

}

