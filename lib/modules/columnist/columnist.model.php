<?

class ColumnistModel extends BaseModel
{
/*
	public function update($data)
	{
		if(isset($data['tx_password']))
		{
			$user_data = $this->user_exists($data['tx_email']);
			
			if($user_data['tx_password'] != $data['tx_password'])
				$data['tx_password'] = md5($data['tx_password']);
		}
			
		return $this->database->update($this->table_name, $data, array($this->id_column => $data[$this->id_column]));
	}
*/	

	
	public function add($data)
	{		
	
		$c_id = $this->database->insert($this->table_name, $data);

		if(isset($data['login']))
		{
			$login_data['u_name'] = $data['c_name'];
			$login_data['email']  = $data['email'];
			$login_data['login']  = $data['login'];
			$login_data['passwd'] = md5($data['passwd']);
			$login_data['luid']   = 2;
			$login_data['enable'] = 1;
	
			unset($data['login']);
			unset($data['passwd']);

			$login_data['ent_id'] = $c_id;
			
			$u_id = $this->database->insert("sys_users", $login_data);

		}

		
		
		return $c_id;
		
	}
	
	public function update($data)
	{
		
		if(isset($data['login']))
		{
		
			$login_data['u_name'] = $data['c_name'];
			$login_data['email']  = $data['email'];
			/*
			$login_data['login']  = $data['login'];
			
			if($data['passwd'] != "")
				$login_data['passwd'] = md5($data['passwd']);
			*/
			
			unset($data['login']);
			unset($data['passwd']);

			$this->database->update("sys_users", $login_data, array("ent_id" => $data[$this->id_column]));
		}
		
		return $this->database->update($this->table_name, $data, array($this->id_column => $data[$this->id_column]));
	}

	
	public function delete($id)
	{
		//$this->database->delete("sys_users", array("ent_id" => $id));
		
		return $this->database->delete($this->table_name, array($this->id_column => $id));
	}
	
	public function delete_many($id)
	{
		//$this->database->delete_many("sys_users", array("ent_id" => $id));

		return $this->database->delete_many($this->table_name, array($this->id_column => $id));
	}

	



}