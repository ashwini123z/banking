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
	 	 	<option><?php echo $_COOKIE['myJavascriptVars'];  ?></option>
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
     document.cookie = "myJavascriptVars = " + x ;
     location.reload();
    // alert(x);
}

	</script>
	<?php
     $myPhpVar= $_COOKIE['myJavascriptVars'];
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
     $myPhpVar= $_COOKIE['myJavascriptVars'];
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
	<input type="date" name="date" required>
	<input type="time" name="times" required>
	<input type="submit" name="submit" value="View">
</form>
<table>
<?php
if(isset($_POST['submit']))
{
	$subject = $_POST['subject'];
	$semester = $_POST['semester'];
	$division = $_POST['division'];
	$date = $_POST['date'];
	$time = $_POST['times'];
	
?>
<tr>
<th>Sl</th>
<th>UUCMS NO</th>
<th>Student name</th>
<th>status</th>
<th>Select All<input type="checkbox"  id="selectAllCheckbox"></th>

</tr>
<form method="POST">

<?php
	
	$cnt =1;
	$qs = mysqli_query($conn, "SELECT 
    rp.uucms_no AS uucms,
    ri.uucms_no AS rollno,
    rp.student_name,
    ri.semester,
    ri.subjectname
FROM 
    students rp
LEFT OUTER JOIN 
    attendances ri 
ON 
    ri.uucms_no = rp.uucms_no 
    AND ri.date = '$date' 
    AND ri.semester = '$semester' 
    AND ri.subjectname = '$subject' 
    AND ri.division = '$division' 
    AND ri.times = '$time'
WHERE 
    rp.sem = '$semester'
    AND rp.division = '$division'
    AND (rp.subject1 = '$subject' OR rp.subject2 = '$subject' or rp.subject1='None') 
ORDER BY 
    rp.uucms_no ASC;
");

		while($rs = mysqli_fetch_assoc($qs) ){		
?>
<tr>
	<input type="hidden" name="times" value="<?php echo $time ?>">
	<input type="hidden" name="date" value="<?php echo $date ?>">
	<input type="hidden" name="sem" value="<?php echo $semester ?>">
	<input type="hidden" name="div" value="<?php echo $division ?>">
	<input type="hidden" name="subject" value="<?php echo $subject ?>">
	<td><?php echo $cnt; ?></td>
<td><?php echo $rs['uucms'] ?></td>
<td><?php echo $rs['student_name'] ?></td> 
<td> 
	<?php
	if($rs['uucms'] == $rs['rollno'] ) {
	?>
	<input type="checkbox" name="status[]" value="<?php echo $rs['uucms']?>"  class="checkboxes" checked></td>
<?php }else {
	?>

	<input type="checkbox" name="status[]" value="<?php echo $rs['uucms']?>"  class="checkboxes" ></td>
	<?php
}
?>
</tr>
</tr>
<?php
$cnt = $cnt + 1;
}
}
?>
<input type="submit" name="submits" value="Submit">
</form>
 <script>
        document.getElementById('selectAllCheckbox')
                  .addEventListener('change', function () {
            let checkboxes = 
                document.querySelectorAll('.checkboxes');
            checkboxes.forEach(function (checkbox) {
                checkbox.checked = this.checked;
            }, this);
        });

            
    </script>
</table>
  <?php
    if(isset($_POST['submits']))
    {
    	$semester = $_POST['sem'];
    	$division = $_POST['div'];
    	$subject = $_POST['subject'];
    	$date = $_POST['date'];
    	$times = $_POST['times'];
    	$status = $_POST['status'];
    	$m = mysqli_query($conn,"DELETE FROM attendances WHERE date='$date' and semester = '$semester' and subjectname = '$subject' and division = '$division' and times='$times'");
    	for($i=0;$i<count($status);$i++){
    		$r = mysqli_query($conn, "SELECT student_name FROM students WHERE uucms_no='$status[$i]'");
    		$xyz = mysqli_fetch_assoc($r);
    		$studname = $xyz['student_name'];
    		$q = mysqli_query($conn, "INSERT INTO attendances(uucms_no,studentname,semester,division,subjectname,date,times)VALUES('$status[$i]','$studname','$semester','$division','$subject','$date','$times')");
    	}
    }

    ?>
    <a href="print_attendance.php">Print Attendance</a>