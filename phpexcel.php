<?php
session_start();
?>

<html>
<head>
</head>

<script language="javascript" type="text/javascript">
function validateform()
{
var x=document.forms["myform"]["tableName"].value;
if (x==null || x=="")
  {
  alert("Database name must be filled out");
  return false;
  }
var x = document.forms["myform"]["phone"].value
if(x == "Choose One")
  {
  alert("please select the column containing phone numbers");
  return false;
  }  
}
</script>
<body>
<?php

require_once 'excel/excel_reader2.php';

$data = new Spreadsheet_Excel_Reader();

$data->setOutputEncoding('CP1251');

//$data->read($_FILES['uploadedfile']['tmp_name']); //In case you are processing an uploaded file from some previous webpage
//echo $_POST['file'];

$data->read("test.xls"); //reading a sample file to be processed

$_SESSION['file'] = $_POST['file'];
error_reporting(E_ALL ^ E_NOTICE);

$noOfCols = $data->sheets[0]['numCols'];
$noOfRows = $data->sheets[0]['numRows'];

echo "<form action = \"addTable.php\" method = \"post\" name=\"myform\" onsubmit=\"return validateform()\">";
echo "Database Name:<input type=\"text\" size=\"10\" maxlength=\"40\" name=\"tableName\"><br/>";

echo "Select the column with phone numbers<br>"; //In case you want user to upload a file containing one column for mobile numbers

echo "<select name=\"phone\">";
echo "<option>Choose One</option>";

for ($j = 1; $j <= $noOfCols; $j++) 
	{
		$val = $data->sheets[0]['cells'][1][$j];
		echo "<option>$val</option>";
	}
echo "</select> <br/>";

echo "Please select the rows and columns you want to add:<br/>";

echo "<table>";
echo "<tr>";
echo "<td>";
echo "</td>";
for ($j = 1; $j <= $noOfCols; $j++) 
	{
		$array[$j] = $data->sheets[0]['cells'][1][$j];
		echo "<td>";
		//echo "<input type=\"checkbox\" name=\"columns[]\" value=\"$array[$j]\">$array[$j]";
		echo "<input type=\"checkbox\" name=\"columns[]\" value=\"$j\" checked=\"checked\">";
		echo "</td>";
	}
echo "</tr>";	

echo "<tr>";
echo "<td>";
echo "</td>";
for ($j = 1; $j <= $noOfCols; $j++) 
	{
		
		echo "<td>";
		echo $array[$j];
		echo "</td>";
		echo "<br>";
	}
echo "</tr>";	

for($i = 2; $i<=$noOfRows; $i++)
{
echo "<tr>";
echo "<td>";
echo "<input type=\"checkbox\" name=\"rows[]\" value=\"$i\" checked=\"checked\">";
echo"</td>";
	
		for ($j = 1; $j <= $noOfCols; $j++) 
		{
		echo "<td>";
		echo $data->sheets[0]['cells'][$i][$j];
		echo "</td>";
		}
	echo "</tr>";
	}
echo "</table>";	
echo "<input type=\"submit\" value=\"Submit Info\">" ;
echo "</form>";
?>
</body>
</html>