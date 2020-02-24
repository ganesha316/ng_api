<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function insert_data($table, $data, $escape = NULL)
	{
		$this->db->insert($table, $data, $escape);
		return $this->db->insert_id();
	}

	public function select_data($table, $where=NULL, $select=NULL, $limit=NULL, $orderby=NULL, $like=NULL,$wherein=NULL)
	{
		if($where !== NULL)
		{
			$this->db->where($where);
		}

		if($wherein !== NULL)
		{
			$this->db->where_in($wherein);
		}

		if($select !== NULL)
		{
			$this->db->select($select);
		}

		if($like !== NULL)
		{
			$this->db->like($like['column'], $like['match'], $like['side']);
		}

		if($orderby !== NULL)
		{
			$this->db->order_by($orderby['column'], $orderby['type']);
		}

		if($limit !== NULL)
		{
			$this->db->limit($limit);
			if($limit==1)
			{
				return $this->db->get($table)->row_array();
			}
		}

		return $this->db->get($table)->result_array();
	}

	public function select_data_array_options($data)
	{

		if(isset($data['select']))
		{
			$this->db->select($data['select']);
		}

		$this->db->from($data['table']);

		if (isset($data['join'])) {
			foreach ($data['join'] as $join) {
				$join['type'] = (isset($join['type'])) ? $join['type'] : '';
				$this->db->join($join['table'],$join['condition'],$join['type']);
			}
		}

		if(isset($data['where']))
		{
			$this->db->where($data['where']);
		}

		if(isset($data['where_in']))
		{
			$this->db->where_in($data['where_in']['key'],$data['where_in']['value']);
		}

		if(isset($data['like']))
		{
			$data['like']['side'] = isset($data['like']['side']) ? $data['like']['side'] : 'both';
			$this->db->like($data['like']['column'], $data['like']['match'], $data['like']['side']);
		}

		if(isset($data['group_by']))
		{
			$this->db->group_by($data['group_by']);
		}

		if(isset($data['order_by']))
		{
			$this->db->order_by($data['order_by']['column'], $data['order_by']['type']);
		}

		if(isset($data['offset']))
		{
			$this->db->offset($data['offset']);
		}


		if(isset($data['limit']))
		{
			$this->db->limit($data['limit']);
			if($data['limit']==1)
			{
				return $this->db->get()->row_array();
			}
		}

		return $this->db->get()->result_array();
	}

	public function delete_data($table, $data)
	{
		$this->db->where($data)->delete($table);
		return $this->db->affected_rows();
	}

	public function insert_batch_data($table, $data, $escape = NULL)
	{
		return $this->db->insert_batch($table, $data, $escape);
	}

	public function update_data($table, $update_array, $where_array)
	{
		$this->db->update($table, $update_array, $where_array);
		return $this->db->affected_rows();
	}

    public function get_table_columns($table)
    {
    	return $this->db->list_fields($table);
    }

    public function truncate($table)
    {
    	$this->db->truncate($table);
    }

}

/* End of file MY_Model.php */
/* Location: ./application/core/MY_Model.php */