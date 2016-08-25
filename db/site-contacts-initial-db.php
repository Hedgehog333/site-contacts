<?php
class Site_Contacts_Initial_DB
{
	protected $version;
	protected $wpdb;
	protected $table;

	public function __construct($wpdb, $tableName, $version)
	{
		$this->wpdb = $wpdb;
		$this->table = $wpdb->prefix . $tableName;
		$this->version = $version;
	}

	public function create_table()
	{
		if($this->wpdb->get_var("SHOW TABLES LIKE '$this->table'") != $this->table)
		{
			$sql = "CREATE TABLE " . $this->table . " (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				code VARCHAR(48) NOT NULL UNIQUE,
				title VARCHAR(64) NOT NULL,
				value VARCHAR(224) NOT NULL,
				UNIQUE KEY id (id)
			) ENGINE=InnoDB AUTO_INCREMENT=1;";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}
	}

	public function drop_table()
	{
		$this->wpdb->query("DROP TABLE IF EXISTS $this->table");
	}

	public function get_wpdb()
	{
		return $this->wpdb;
	}

	public function get_table_name()
	{
		return $this->table;
	}

	public function get_version()
	{
		return $this->version;
	}
}