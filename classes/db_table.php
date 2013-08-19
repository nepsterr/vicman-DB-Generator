<?php
class db_table {
	private $table_name = '';
	private $table_fields = array();
	private $table_new_columns = 0;
	public function __construct($t_name) {
		$this->setTableName($t_name);
	}
	
	public function setTableName($tableName) {
		$this->table_name = $tableName;
	}
	public function getTableName() {
		return $this->table_name;
	}
	public function addField($row = array()) {
		$field = array();
		if(is_array($row))
			foreach($row as $key => $value)
			{
					if($key === 'COLUMN_NAME')
					$field['column_name'] = $value;
				elseif(($key === 'COLUMN_DEFAULT')&&($value != NULL ))
					$field['column_default'] = true;
				elseif($key === 'DATA_TYPE')
					$field['column_data_type'] = $value;
				elseif(($key === 'COLUMN_KEY')&&($value === 'PRI'))
					$field['column_primary'] = true;
				elseif(($key === 'EXTRA')&&($value === 'auto_increment'))
					$field['column_increment'] = true;
				elseif($key === 'CHARACTER_SET_NAME')
					$field['column_setname'] = $value;
				elseif($key === 'NUMERIC_PRECISION')
					$field['column_precision'] = $value;
				elseif($key === 'NUMERIC_SCALE')
					$field['column_scale'] = $value;
				elseif($key === 'CHARACTER_MAXIMUM_LENGTH')
					$field['column_length'] = $value;
				elseif(($key === 'COLUMN_TYPE')&&(strpos($value,"unsigned") !== false))
					$field['column_unsigned'] = true;
					
			}
		$this->table_fields[] = new db_field($field);
	}
	public function fillFromDb() {
		
		$tableStructure = db::query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name =  '{$this->getTableName()}'");
		while($row = $tableStructure->fetch_assoc()) {
			$this->addField($row);
		}
	}
	
	public function generateValues($countValues = 1) {
		for($i = 0; $i< $countValues; $i++)
		{
			foreach($this->table_fields as $tfield)
			{
				$value = DataGenerator::generate($tfield);
				$tfield->addValue($value,$i);
			}
		}
		$this->table_new_columns = $countValues;
	}
	
	public function getColumnsNames() {
		$columns = "(";
		$i = 0;
		$count = count($this->table_fields);
		foreach($this->table_fields as $tfield)
		{
			if($tfield->getColumn("default"))
			{
				if(!save_default_values)
					$columns .= $tfield->getColumnName().',';
			}
			else
				$columns .= $tfield->getColumnName().',';
		}
		$columns = rtrim($columns,",").')';
		return $columns;
	}
	
	public function generateSQLUpdate() {
		// ÄÎÄÅËÀÒÜ SQL . INSERT INTO () VALUES VALUES VALUES. 
		$sql = "INSERT INTO `{$this->getTableName()}` {$this->getColumnsNames()} VALUES ";
		for($i = 0 ; $i < $this->table_new_columns; $i++)
		{
			$values = "(";
			$j = 0;
			$count = count($this->table_fields);
			foreach($this->table_fields as $tfield)
			{
				if($tfield->getColumn("default"))
				{
					if(!save_default_values)
						$values .= "'{$tfield->getValue($i)}',";
				}
					else
						$values .= "'{$tfield->getValue($i)}',";
			}
			$values = rtrim($values,",").')';
			
			$sql .= $values;
			if($i < $this->table_new_columns-1)
				$sql .= " , ";
		}
		return $sql;
	}
	
	public function printTable() {
		$data = array();
		$data['fields'] = $this->table_fields;
		$data['tableName'] = $this->getTableName();
		$this->generateView($data,'tablesHtml');
	}
	public function printNewValues() {
		$data = array();
		
		$values = array ();
		for($i = 0 ; $i < $this->table_new_columns; $i++)
			foreach($this->table_fields as $tfield)
				$values[$i][]= $tfield->getValue($i);
		$columnNames = array();
		foreach($this->table_fields as $tfield)
			$columnNames[] = $tfield->getColumnName();
		$data['columnNames'] = $columnNames;
		$data['values'] = $values;
		$data['tableName'] = $this->getTableName();
		
		$this->generateView($data,'tablesData');
	}
	public function generateView($data = array(), $template) {
		include('views/'.$template.".php");
	}
	public function SQLUpdate() {
		if(!debug)
			db::query($this->generateSQLUpdate());
	}
}