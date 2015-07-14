<?php
require_once 'require.php';


class Monitor_1C
{

	const file = 'files/import.csv';
	const timezone = 'Europe/Kiev';

	private $db;
	public $log;

	/**
	 * import.csv
	 */
	private $import_tth;
	private $import_new_tth;

	public function __construct(){
		// For prevent incorrect sync date
		date_default_timezone_set(self::timezone);

		$this->db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

		$this->log = new Log_1C(DIR_SYNC . 'logs/monitor.log');

		// Check is import exists
		if (file_exists(DIR_SYNC . self::file)) {
			// Get import file tth
			$this->import_new_tth = hash_file('md5', DIR_SYNC . self::file);
			// Get old import tth
			$this->import_tth = '';
			$sql = "Select * From `" . DB_PREFIX . "setting` Where `group` = '1c' And `key` = 'import_tth'";
			$import_tth = $this->db->query($sql);
			if ($import_tth->num_rows) {
				$this->import_tth = $import_tth->row['value'];
			}
		}
	}

	public function check(){
		// If import tth is different
		if ($this->import_tth != $this->import_new_tth) {
			// Check if new import file is fully uploaded
			// Process import_1C
			require 'import_exec.php';
			// Write new tth to db
			if($this->import_tth) {
				$sql = "Update `" . DB_PREFIX . "setting` Set
                        `value` = '" . $this->db->escape($this->import_new_tth) . "',
                        `serialized` = '0'
                            Where `group` = '1c'
                            And `key` = 'import_tth'";
			} else {
				$sql = "Insert Into `" . DB_PREFIX . "setting` Set
                        `value` = '" . $this->db->escape($this->import_new_tth) . "',
                        `serialized` = '0',
                        `group` = '1c',
                        `key` = 'import_tth'";
			}
			$this->db->query($sql);

			// Write logs to file
			$this->log->add("New import.csv synchronisation at: " . date('Y-m-d H-a-s'));
		}
	}
}



$monitor = new Monitor_1C();
$monitor->check();