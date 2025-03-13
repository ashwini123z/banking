<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Attendance</title>
</head>
<body>
<form method="POST">
	<?php 
		include "dbconnection.php";
		$query = mysqli_query($conn,"SELECT distinct subjectname FROM subjects");
		
	 ?>
	 <select name="semester"  onchange="test(this)" id="select_id">
	 	 	<option><?php echo $_COOKIE['myJavascriptVar'];  ?></option>
	 	<option>1</option>
	 	<option>2</option>
	 	<option>3</option>
	 	<option>4</option>
	 	<option>5</option>
	 	<option>6</option>
	 </select>
	<script type="text/javascript">
		function test(a) {
    var x = (a.value || a.options[a.selectedIndex].value);  
     document.cookie = "myJavascriptVar = " + x ;
     location.reload();
    // alert(x);
}

	</script>
	<?php
     $myPhpVar= $_COOKIE['myJavascriptVar'];
     $querys = mysqli_query($conn,"SELECT distinct subjectname FROM subjects where semester='$myPhpVar'");
?>
	 <select name="subject">
		<?php
	 	while($r = mysqli_fetch_assoc($querys)){
	 	?>
	 	<option><?php echo $r['subjectname'] ?></option>
	 	 <?php
	}
	 ?>
	</select>

	<?php
     $myPhpVar= $_COOKIE['myJavascriptVar'];
     $querys = mysqli_query($conn,"SELECT distinct division FROM subjects where semester='$myPhpVar'");
?>
	 <select name="division">
	 	<option>select div</option>
		<?php
	 	while($r = mysqli_fetch_assoc($querys)){
	 	?>
	 	<option><?php echo $r['division'] ?></option>
	 	 <?php
	}
	 ?>
	</select>
	<br>
	<input type="submit" name="submit" value="View">
</form>
<table border="1px">
<?php
if(isset($_POST['submit']))
{
	$subject = $_POST['subject'];
	$semester = $_POST['semester'];
	$division = $_POST['division'];
	$headers = array('Sl No', 'UUCMS NO', 'STUDENT NAME');
?>
<thead>
<tr>
<th>Sl</th>
<th>UUCMS NO</th>
<th>Student name</th>
<?php
$d = mysqli_query($conn, "SELECT distinct times, date FROM attendances Where subjectname='$subject' and division='$division' and semester='$semester' order by date ASC ");
while($ds = mysqli_fetch_assoc($d)){
	$date=date_create($ds['date']);
	$dates = $ds['date'];
?>

<th><?php echo date_format($date,"d/m/Y");?></th>
<?php
}
?>
</tr>
</thead>
<tbody>

	<?php
	$s = mysqli_query($conn, "SELECT * FROM students Where division='$division' and sem='$semester' and (subject1='$subject' or subject2='$subject' or subject1='None') order by uucms_no ASC ");
	$cnt = 1;
while($sd = mysqli_fetch_assoc($s)){
	?>
	<tr>
	<td><?php echo $cnt ?></td>
	<td><?php echo $sd['uucms_no'] ?></td>
	<td><?php echo $sd['student_name'] ?></td>

	<?php
		$uucmsno = $sd['uucms_no'];	
		$rpd = mysqli_query($conn, "SELECT DISTINCT 
  times,
  date,
  CASE 
    WHEN (times, date) IN (
      SELECT DISTINCT times, date
      FROM attendances
      WHERE subjectname = '$subject'
        AND division = '$division'
        AND semester = '$semester'
        AND uucms_no = '$uucmsno'
    ) THEN 1
    ELSE 0
  END AS match_status
FROM attendances
WHERE subjectname = '$subject'
  AND division = '$division'
  AND semester = '$semester'
ORDER BY date ASC;
");
		$x = 0;
		while($rsd = mysqli_fetch_array($rpd)){
		$x = $x + $rsd['match_status'];
	?>
	<td style="text-align: center"><?php echo $x; ?></td>

<?php
	
}
?>
	</tr>
	<?php
	$cnt +=1;
}
}

?>
</tbody>
</table>
<button id="export-button">Export to Excel</button>

