<?php
class Site_Contacts_Manager_DB
{
	protected $wpdb;
	protected $table;

	public function __construct($wpdb, $tableName)
	{
		$this->wpdb = $wpdb;
		$this->table = $tableName;
	}

	public function insert($contactArray)
	{
		$flag = $this->wpdb->insert(
				$this->table,
				$contactArray
			);
		return array('result' => $flag, 'id' => $this->wpdb->insert_id, 'data' => $contactArray);
	}

	public function delete($id)
	{
		$result = $this->wpdb->delete( $this->table, array( 'id' => $id ), array( '%d' ));
		return array('result' => $result);
	}

	public function update($id, $contactArray)
	{
		$data = $this->wpdb->update( 
			$this->table,
			$contactArray,
			array( 'id' => $id ),
			array( '%s', '%s', '%s' ),
			array( '%d' )
		);
		return array('result' => $data);
	}

	static function sort_select($obj1,$obj2)
	{
		if($obj1->id < $obj2->id) return -1;
		elseif($obj1->id > $obj2->id) return 1;
		else return 0;
	}

	public function select($code = '')
	{
		if($code == '')
		{
			$result = $this->wpdb->get_results( "SELECT * FROM $this->table" );
			uasort($result, array($this, "sort_select"));
			return $result;
		}
		else
			return $this->wpdb->get_results( "SELECT value FROM $this->table WHERE code = '$code'" );
	}
}