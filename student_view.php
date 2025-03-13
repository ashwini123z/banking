<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Attendance view</title>
</head>
<body>
<div class="container">
<form method="post">
	<input type="text" name="uucms_no"><br>
	<input type="submit" name="submit" value="Search">
</form>
<?php
	include "dbconnection.php";
	if(isset($_POST['submit']))
	{
		$uucmsno = $_POST['uucms_no'];
		$q = mysqli_query($conn,"SELECT uucms_no, student_name, sem, division FROM students WHERE uucms_no = '$uucmsno'"); 
		while($m = mysqli_fetch_assoc($q)){
	
?>
<table border="1px">
	<tr>
		<th>UUCMS NO</th>
		<td><?php echo $m['uucms_no'] ?></td>
		<th>Student Name</th>
		<td colspan="2"><?php echo $m['student_name'] ?></td>
	</tr>
	<tr>
		<th>Semester</th>
		<td><?php echo $m['sem'] ?></td>
		<th>Division</th>
		<td colspan="2"><?php echo $m['division'] ?></td>
	</tr>
	<tr>
		<th>Sl No</th>
		<th>Subject</th>
		<th>Attended classes</th>
		<th>Total classes taken</th>
		<th>%</th>

	</tr>
<?php
$sem = $m['sem'];
$div = $m['division'];
$q = mysqli_query($conn,"SELECT 
    s.subjectname,
    (SELECT COUNT(a1.uucms_no) 
     FROM attendances a1 
     WHERE a1.uucms_no = '$uucmsno' AND a1.subjectname = s.subjectname) AS cnt1,
    (SELECT COUNT(DISTINCT a2.date) 
     FROM attendances a2 
     WHERE a2.subjectname = s.subjectname AND a2.division = '$div' AND a2.semester = '$sem') AS cnt2
	FROM subjects s
	WHERE s.semester = '$sem' AND s.division = '$div';");
		$no = 1; 
		while($y = mysqli_fetch_assoc($q)){
			$percent = ($y['cnt1'] / $y['cnt2'])*100;
?>
	<tr>
		<td style="text-align: center;"><?php echo $no ?></td>
		<td><?php echo $y['subjectname'] ?></td>
		<td style="text-align: center;"><?php echo $y['cnt1']  ?></td>
		<td style="text-align:center;"><?php echo $y['cnt2']  ?></td>
		<td style="text-align:center;"><?php echo number_format((float)$percent, 2, '.', '');  ?></td>
	</tr>

<?php
$no = $no + 1;
}
}
}
?>
</table>
</div>


</body>
</html>