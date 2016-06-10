<?php

class DbSaveit extends CI_Model
{

	private $CI;
	private $db;
	public function __construct()
	{
		$this->CI =& get_instance();
	}

	public function add_category($data)
	{
		return $this->insert_row($data, 'categories');
	}

	public function add_category_tag($data)
	{
		return $this->insert_row($data, 'categories_tags', false);
	}

	public function add_tag($data)
	{
		return $this->insert_row($data, 'tags');
	}

	// Register a new user in database
	// Returns user ID on success
	// Returns false on error
	public function add_link($data)
	{
		return $this->insert_row($data, 'links');
	}

	public function add_link_tag($data)
	{
		return $this->insert_row($data, 'links_tags', false);
	}

	public function get_categories($type, $value)
	{
		$this->CI->db->select('id, name, description, parent_id');

		$this->CI->db->where($type, $value);

		if ($query = $this->CI->db->get('categories')) {
			$result = [];
			if ($query->num_rows() > 0)
			{
				foreach ($query->result() as $row)
				{
					$result[] = $row;
				}
			}
		}
		return $result;
	}

	public function get_links($type, $value)
	{
		
		$this->CI->db->select('id, category_id, url, name, description');

		$this->CI->db->where($type, $value);
		$this->CI->db->order_by('category_id');

		if ($query = $this->CI->db->get('links')) {
			$result = [];
			if ($query->num_rows() > 0)
			{
				foreach ($query->result() as $row)
				{
					$row->tags = $this->get_tags('link_id', $row->id);
					$result[] = $row;
				}
			}
		}
		return $result;
	}

	// Get an user with the specified filter
	public function get_tags($field, $value)
	{
		$this->CI->db->select('t.id, t.name');

		$this->CI->db->where($field, $value);

		$this->CI->db->join('tags as t', 't.id = tag_id');

		// Construye la sentencia SQL y la ejecuta
		if ($query = $this->CI->db->get('links_tags')) {
			$result = [];
			if ($query->num_rows() > 0)
			{
				foreach ($query->result() as $row)
				{
					$result[] = $row;
				}
			}
		}
		return $result;
		return $this->_get_row('links_tags', $fields, 'id', $id);
	}

	// Get an user with the specified filter
	public function get_user($type, $value)
	{
		$fields = ['id', 'email', 'password', 'name'];
		return $this->_get_row('users', $fields, $type, $value);
	}

	public function insert_row($data, $table, $dates = true)
	{
		if ($dates === true)
		{
			$now = Date('Y-m-d H:i:s');
			$data['created_at'] = $now;
			$data['updated_at'] = $now;
		}
		
		if ($this->CI->db->insert($table, $data)) {
			return $this->CI->db->insert_id();
		}
		return false;
	}

	public function insert_session($user_id)
	{
		$now = Date('Y-m-d H:i:s');
		$user_agent = $this->CI->input->server('HTTP_USER_AGENT');
		$user_ip = $this->CI->input->server('REMOTE_ADDR');
		$data = [
			'user_id' => $user_id,
			'created_at' => $now,
			'user_agent' => $user_agent,
			'ip' => $user_ip,
			'uri' => $this->CI->uri->uri_string
		];
		$table = "sessions";
		if ($this->CI->db->insert($table, $data))
			return true;
		return false;
		
	}

	/*public function update_login($user_id, $email, $name)
	 {
		$data = [
			'email' => $email,
			'name' => $name
			//'updated_at' => Date('Ymd H:i:s')
		];
		$where = ['id' => $user_id];
		if ($this->_update_row('users', $data, $where))
			if ($this->insert_session($user_id))
				return true;
		return false;

	} */


	// Register a new user in database
	// Returns user ID on success
	// Returns false on error
	public function register_user($user_data)
	{
		$now = Date('Y-m-d H:i:s');
		$user_data['created_at'] = $now;
		$user_data['updated_at'] = $now;

		$table = 'users';
		
		if ($this->CI->db->insert($table, $user_data)) {
			$this->CI->db->select('id');
			$this->CI->db->where('email', $user_data['email']);
			if ($query = $this->CI->db->get($table)) {
				if ($query->num_rows() > 0) {
					$row = $query->row();
					if ($this->insert_session($row->id)) {
						return $row->id;
					}
				}
			}
		}
		return false;
	}

	// Get a table row, applying the requested filters
	private function _get_row($table, $fields, $type, $value)
	{
		$this->CI->db->select($fields);

		$this->CI->db->where($type, $value);

		// Runs SQL Query
		if ($query = $this->CI->db->get($table)) {
			if ($query->num_rows() > 0)
				return $query->row();
			else
				return NULL;
		}
		return false;
	}

	private function _get_rows($table, $fields, $type, $value)
	{
		$this->CI->db->select($fields);

		$this->CI->db->where($type, $value);

		// Runs SQL Query
		if ($query = $this->CI->db->get($table)) {
			$result = [];
			if ($query->num_rows() > 0)
			{
				foreach ($query->result() as $row)
				{
					$result[] = $row;
				}
			}
		}
		return $result;
	}

	private function _update_row($table, $data, $where)
	{
		return $this->CI->db->update($table, $data, $where);
	}

}