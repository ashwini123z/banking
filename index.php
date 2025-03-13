<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Attendance</title>
	<?php
	include "dbconnection.php";
	?>
</head>
<body>
<form method="post">
	<input type="text" name="uucms_no" placeholder="Enter uucms no">
	<br>
	<input type="text" name="sname" placeholder="Enter Student name">
	<br>
	<select name="semester">
		<option>1</option>
		<option>2</option>
		<option>3</option>
		<option>4</option>
		<option>5</option>
		<option>6</option>
	</select>
	<br>
	<select name="division">
		<option>A</option>
		<option>B</option>
		<option>C</option>
	</select>
	<br>
	<input type="submit" name="submit" value="Submit">
</form>
<?php
	if (isset($_POST['submit'])) {
		$uucms = $_POST['uucms_no'];
		$sname = $_POST['sname'];
		$semester = $_POST['semester'];
		$div = $_POST['division'];
		$query = mysqli_query($conn,"INSERT INTO students(uucms_no,student_name,sem,division) VALUES('$uucms','$sname','$semester','$div')");
		if($query){
			echo "<script>alert('Record Submitted')</script>";
		}
		else
		{
			echo "<script>alert('Submission Failed')</script>";	
		}
	}

?>
<form method="POST">
	<input type="text" name="subject" placeholder="ENTER SUBJECT NAME">
	<select name="semester">
		<option>1</option>
		<option>2</option>
		<option>3</option>
		<option>4</option>
		<option>5</option>
		<option>6</option>
	</select>
	<select name="division">
		<option>A</option>
		<option>B</option>
		<option>C</option>
	</select>
	<br>
	<input type="submit" name="submits" value="Submit">
</form>
<?php
if(isset($_POST['submits']))
{
	$subject = $_POST['subject'];
	$sem = $_POST['semester'];
	$div = $_POST['division'];
	$query = mysqli_query($conn, "INSERT INTO subjects(subjectname,semester,division) VALUES('$subject','$sem','$div')");
	if($query)
	{
		echo "<script>alert('Record Submitted')</script>";
	}
	else
		{
			echo "<script>alert('Submission Failed')</script>";	
		}
}

?>
<a href="attendance.php">Attendance</a>
</body>
</html>