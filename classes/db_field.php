<?php
class db_field {
	public $column_name = '';
	public $column_default = false;
	public $column_data_type = '';
	public $column_primary = false;
	public $column_increment = false;
	public $column_setname = '';
	public $column_precision = '';
	public $column_scale = '';
	public $column_length = '';
	public $column_unsigned = false;
	public $column_values = array();
	public function __construct($field = array()) {
		if(is_array($field))
			foreach($field as $key => $value)
			{
				if(!isset($value))
					$this->$key = 0;
				else
					$this->$key = $value;
			}
	}
	public function getColumnName() {
		return $this->column_name;
	}
	public function getColumn($name) {
		$columnName = 'column_'.$name;
		if(isset($this->$columnName))
			return $this->$columnName;
		else
			return '';
	}
	public function addValue($value, $key) {
		$this->column_values[$key] = $value;
	}
	public function getValue($key) {
		return $this->column_values[$key];
	}
}