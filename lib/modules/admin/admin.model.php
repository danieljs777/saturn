<?

class AdminModel extends BaseModel
{
	public function auth_user($admin_name, $admin_passwd)
	{		
		if ($admin_name == "" || $admin_passwd == "")
		{
			return "Informe usuário e senha!";
		}
		else
		{
			$admin_data = $this->admin_exists($admin_name);
			if(is_array($admin_data))
				return $this->check_passwd($admin_passwd, $admin_data);
			else
				return "Acesso negado!";
		}
	}
	
	public function list_all($filter)
	{
		$data = parent::list_all($filter);
		$this->set_reg_value($data, 'luid');
		
		return $data;
		
	}

	public function list_page($filter)
	{
		$data = parent::list_all($filter);
		$this->set_reg_value($data, 'luid');
		
		return $data;
		
	}
	

	public function update($data)
	{
		if(isset($data['passwd']))
			$data['passwd'] = md5($data['passwd']);
			
		return $this->database->update($this->table_name, $data, array($this->id_column => $data[$this->id_column]));
	}
	
	public function add($data)
	{
		$data['passwd'] = md5($data['passwd']);		
		return $this->database->insert($this->table_name, $data);
	}
		
	
	/*******************************************************************************/
	/* Private Methods *************************************************************/
	
	private function check_passwd($admin_passwd, $admin_data)
	{
		
		if (md5($admin_passwd) == $admin_data["passwd"]) 
		{			
			$this->set_session($admin_data);
			return true;
		}
		else 
			return "Acesso negado!";
	}
	
	public function admin_exists($login)
	{
		return $this->select_first(array('login' => $login));
	}
	
	public function set_session($admin_data)
	{
		$_SESSION["admin_logged"]  = 1;
		$_SESSION["admin_id"]      = $admin_data["u_id"];
		$_SESSION["admin_name"]    = $admin_data["u_name"];
		$_SESSION["admin_luid"]    = $admin_data["luid"];
		$_SESSION["columnist_id"]  = $admin_data["ent_id"];
		
		
	}

	

}