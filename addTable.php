<?php
session_start();
require_once 'excel/excel_reader2.php';

$data = new Spreadsheet_Excel_Reader();

$data->setOutputEncoding('CP1251');

$data->read($_SESSION['file']);

error_reporting(E_ALL ^ E_NOTICE);

$noOfCols = $data->sheets[0]['numCols'];
$noOfRows = $data->sheets[0]['numRows'];
$connect = mysql_connect("localhost", "root", "") or die ("check your server connection.");

mysql_select_db(""); //Put your Database name in between the quotes

$tableName = $_POST['tableName']; //Table name to which you want to add the data you got from the previous page

$problems = array();

for($j=1;$j<=$noOfCols;$j++)
	{
	
	if($data->sheets[0]['cells'][1][$j] == $_POST['phone'])
		{
		for($i=2;$i<=$noOfRows;$i++)
			{
			if(strlen($data->sheets[0]['cells'][$i][$j]) != 10)
				{
				array_push($problems, $i);
				}
			}
		}
	}
if(count($problems)!=0)	
	{	
	$_SESSION['problems'] = $problems;
	header("Location: problems.php");
	exit();
	}

$db = "CREATE TABLE $tableName 
(
`userid` INT NOT NULL AUTO_INCREMENT ,
PRIMARY KEY ( `userid` ) ,
UNIQUE (`userid`)
)";

$results = mysql_query($db) or die(mysql_error());

if(isset($_POST['columns'])) //ading the selected columns to the table
{
	foreach($_POST['columns'] as $value)
	{
	  $name = $data->sheets[0]['cells'][1][$value]; 
	  $db = "ALTER TABLE $tableName ADD column `$name` varchar(255)";
	  $results = mysql_query($db) or die(mysql_error());
	}
}

$coulmns = $_POST['columns'];
$rows = $_POST['rows'];
$cIndex = 0;

$col = $data->sheets[0]['cells'][1][$_POST['columns'][0]];

for ($j = 1; $j < count($_POST['columns']); $j++)
	{
	$col = $col." , ".$data->sheets[0]['cells'][1][$_POST['columns'][$j]];
	}


for($i = 0; $i < count($_POST['rows']); $i++)
	{
	$entry = "'".$data->sheets[0]['cells'][$_POST['rows'][$i]][$_POST['columns'][0]]."'";
	for ($j = 1; $j < count($_POST['columns']); $j++)
		{
			$entry = $entry.","."'".$data->sheets[0]['cells'][$_POST['rows'][$i]][$_POST['columns'][$j]]."'";
		}
				
		$db = "insert into $tableName($col) values($entry)"; // adding the rows to the table 
		$results = mysql_query($db) or die(mysql_error());
				
	}
echo "Data successfully added";	
?>