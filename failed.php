<?php
	session_start();
	require 'admin/class/db.php';
	
	$studId = $_SESSION['idStud'];
	$curr=$_SESSION['idCurr'];
	$failedSub = $conn->query("SELECT bsit_subject.subject_code,subject.descriptive_title,grade.grade,semester.semester_name FROM student,bsit_subject,subject,grade,semester WHERE student.student_id = '$studId' && student.student_id = grade.student_id && bsit_subject.subject_code = subject.subject_code && 
		bsit_subject.semester_id = semester.semester_id	&& bsit_subject.subject_code = grade.subject_code && bsit_subject.curriculum_id = '$curr' && grade > 50 && grade < 75");
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title></title>
	<link href="assets/style/bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
	<script src='assets/script/jquery.min.js'></script>
	<script src='assets/style/bootstrap/js/bootstrap.min.js'></script>
</head>
<body>
	<table class='table'>
<thead>
	<tr>
		<th>Subject Code</th>
		<th>Descriptive Title</th>
		<th>Grade</th>
		<th>Semester Available</th>
	</tr>
</thead>
<?php	

	
			while($row = $failedSub->fetch_object()){
					echo "<tr>";
					echo "<td>$row->subject_code</td>";
					echo "<td>$row->descriptive_title</td>";
					echo "<td>$row->grade</td>";
					echo "<td>$row->semester_name</td>";
					echo "</tr>";
			}
			
?>
</table>
</body>
</html>