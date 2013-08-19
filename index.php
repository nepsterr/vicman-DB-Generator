<?php

include("includes.php");

// DB Connection 
db::connect(DB_HOST,DB_USER,DB_PASS,DB_BASE);
$tables = array();
// Getting all tables from selected DB
$allTables = db::query("SHOW TABLES;");
while($row = $allTables->fetch_assoc()) {
	$tables[] = new db_table(current($row));	
}
foreach($tables as $table)
{
	// Filling structure for table
	$table->fillFromDb();
	// Generating values. Parameter - rows needed to be generated
	$table->generateValues(1);
	// Printing table structure
	$table->printTable();
	// Printing generated values
	$table->printNewValues();
	// Inserting rows in tables MySQL
	$table->SQLUpdate();
}
db::disconnect();