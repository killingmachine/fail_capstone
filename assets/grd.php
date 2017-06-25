<?php

	$studId = $_SESSION['currentUser'];
	$get_cur = $conn->query("SELECT * FROM student WHERE student_id = '$studId'");
	$currId = '';
	while($row = $get_cur->fetch_object()){
		$currId = $row->curriculum_id;
	}
?>
<div style='text-align:center;'>

	<table  class='table table-striped grd_table'>
	
			<tr>
				<th>grade</th>
				<th>Subject Code</th>
				<th>Descriptive Title</th>
				<th>Pre-requisite</th>
				<th>Units</th>
				<th>Lec Hrs</th>
				<th>Lab Hrs</th>
				<th>Contact Hrs</th>
			</tr>
			<tr>
				<th colspan='8' style='text-align:center;
				background-color: #323232;color:#f6f6f6;
				border-top:none;border-bottom:none; '>First Year</th>
			</tr>
			<tr>
				<th colspan='8' style='text-align:center;'>First semester</th>
			</tr>
			<?php
			$grade = $conn->query("SELECT grade.grade_id,bsit_subject.subject_code,subject.descriptive_title,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,grade.grade FROM student,bsit_subject,subject,grade WHERE student.student_id = '$studId' && student.student_id = grade.student_id && bsit_subject.subject_code = subject.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.semester_id = 1 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id='$currId'" );
			while($row = $grade->fetch_object()){
				///<span id='chk' class='glyphicon glyphicon-ok'</span>
				echo "<tr>";
				echo "<td>$row->grade</td>";
				echo "<td>$row->subject_code</td>";
				echo "<td>$row->descriptive_title</td>";
				echo "<td>$row->pre $row->copre $row->standing</td>";
				echo "<td>$row->units</td>";
				echo "<td>$row->lec_hrs</td>";
				echo "<td>$row->lab_hrs</td>";
				echo "<td>"; echo $row->lec_hrs + $row->lab_hrs; echo "</td>";
				echo "</tr>";
			}
		
		echo "<tr>";
		echo "<th colspan='8' style='text-align:center;'>Second semester</th>";
		echo "</tr>";
		$grade1 = $conn->query("SELECT grade.grade_id,bsit_subject.subject_code,subject.descriptive_title,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,grade.grade FROM student,bsit_subject,subject,grade WHERE student.student_id = '$studId' && student.student_id = grade.student_id && bsit_subject.subject_code = subject.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.semester_id = 2 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id='$currId'" );
		while($row = $grade1->fetch_object()){
			echo "<tr>";
			echo "<td>$row->grade</td>";
			echo "<td>$row->subject_code</td>";
			echo "<td>$row->descriptive_title</td>";
			echo "<td>$row->pre $row->copre $row->standing</td>";
			echo "<td>$row->units</td>";
			echo "<td>$row->lec_hrs</td>";
			echo "<td>$row->lab_hrs</td>";
			echo "<td>"; echo $row->lec_hrs + $row->lab_hrs; echo "</td>";
			echo "</tr>";
		}
		echo "<tr>";
		echo "<th colspan='8' style='text-align:center;'>Summer</th>";
		echo "</tr>";
		$grade1Sum = $conn->query("SELECT grade.grade_id,bsit_subject.subject_code,subject.descriptive_title,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,grade.grade FROM student,bsit_subject,subject,grade WHERE student.student_id = '$studId' && student.student_id = grade.student_id && bsit_subject.subject_code = subject.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id='$currId'" );
		if($grade1Sum->num_rows > 0){
			while($row = $grade1Sum->fetch_object()){
			
				echo "<tr>";
				echo "<td>$row->grade></td>";
				echo "<td>$row->subject_code</td>";
				echo "<td>$row->descriptive_title</td>";
				echo "<td>$row->pre $row->copre $row->standing</td>";
				echo "<td>$row->units</td>";
				echo "<td>$row->lec_hrs</td>";
				echo "<td>$row->lab_hrs</td>";
				echo "<td>"; echo $row->lec_hrs + $row->lab_hrs; echo "</td>";
				echo "</tr>";
			}
		}
		else{
			echo "<tr>";
			echo "<td colspan='8' style='text-align:center;'>No Summer Subjects Available</td>";
			echo "</tr>";
		}
		echo "<tr>";
		echo "<th colspan='8' style='text-align:center;background-color: #323232;color:#f6f6f6;border-top:none;border-bottom:none; '>Second Year</th>";
		echo "</tr>";
		
		echo "<tr>";
		echo "<th colspan='8' style='text-align:center;'>First Semester</th>";
		echo "</tr>";
		$grade2 = $conn->query("SELECT grade.grade_id,bsit_subject.subject_code,subject.descriptive_title,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,grade.grade FROM student,bsit_subject,subject,grade WHERE student.student_id = '$studId' && student.student_id = grade.student_id && bsit_subject.subject_code = subject.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.semester_id = 1 && bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id='$currId'" );
		while($row = $grade2->fetch_object()){
			echo "<tr>";
			echo "<td>$row->grade</td>";
			echo "<td>$row->subject_code</td>";
			echo "<td>$row->descriptive_title</td>";
			echo "<td>$row->pre $row->copre $row->standing</td>";
			echo "<td>$row->units</td>";
			echo "<td>$row->lec_hrs</td>";
			echo "<td>$row->lab_hrs</td>";
			echo "<td>"; echo $row->lec_hrs + $row->lab_hrs; echo "</td>";
			echo "</tr>";
		}
		echo "<tr>";
		echo "<th colspan='8' style='text-align:center;'>Second semester</th>";
		echo "</tr>";
		$grade22Sem = $conn->query("SELECT grade.grade_id,bsit_subject.subject_code,subject.descriptive_title,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,grade.grade FROM student,bsit_subject,subject,grade WHERE student.student_id = '$studId' && student.student_id = grade.student_id && bsit_subject.subject_code = subject.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.semester_id = 2 && bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id='$currId'" );
		while($row = $grade22Sem->fetch_object()){
			echo "<tr>";
			echo "<td>$row->grade</td>";
			echo "<td>$row->subject_code</td>";
			echo "<td>$row->descriptive_title</td>";
			echo "<td>$row->pre $row->copre $row->standing</td>";
			echo "<td>$row->units</td>";
			echo "<td>$row->lec_hrs</td>";
			echo "<td>$row->lab_hrs</td>";
			echo "<td>"; echo $row->lec_hrs + $row->lab_hrs; echo "</td>";
			echo "</tr>";
		}
		echo "<tr>";
		echo "<th colspan='8' style='text-align:center;'>Summer</th>";
		echo "</tr>";
		$grade2Sum = $conn->query("SELECT grade.grade_id,bsit_subject.subject_code,subject.descriptive_title,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,grade.grade FROM student,bsit_subject,subject,grade WHERE student.student_id = '$studId' && student.student_id = grade.student_id && bsit_subject.subject_code = subject.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id='$currId'" );
		if($grade2Sum->num_rows > 0){
			while($row = $grade2Sum->fetch_object()){	
				echo "<tr>";
				echo "<td>$row->grade</td>";
				echo "<td>$row->subject_code</td>";
				echo "<td>$row->descriptive_title</td>";
				echo "<td>$row->pre $row->copre $row->standing</td>";
				echo "<td>$row->units</td>";
				echo "<td>$row->lec_hrs</td>";
				echo "<td>$row->lab_hrs</td>";
				echo "<td>"; echo $row->lec_hrs + $row->lab_hrs; echo "</td>";
				echo "</tr>";
			}
		}
		else{
			echo "<tr>";
			echo "<td colspan='8' style='text-align:center;'>No Summer Subjects Available</td>";
			echo "</tr>";
		}
		
		echo "<tr>";
		echo "<th colspan='8' style='text-align:center;background-color: #323232;color:#f6f6f6;border-top:none;border-bottom:none; '>Third Year</th>";
		echo "</tr>";
		echo "<tr>";
		echo "<th colspan='8' style='text-align:center;'>First Semester</th>";
		echo "</tr>";
		$grade3 = $conn->query("SELECT grade.grade_id,bsit_subject.subject_code,subject.descriptive_title,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,grade.grade FROM student,bsit_subject,subject,grade WHERE student.student_id = '$studId' && student.student_id = grade.student_id && bsit_subject.subject_code = subject.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.semester_id = 1 && bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id='$currId'" );
		while($row = $grade3->fetch_object()){
			echo "<tr>";
			echo "<td>$row->grade</td>";
			echo "<td>$row->subject_code</td>";
			echo "<td>$row->descriptive_title</td>";
			echo "<td>$row->pre $row->copre $row->standing</td>";
			echo "<td>$row->units</td>";
			echo "<td>$row->lec_hrs</td>";
			echo "<td>$row->lab_hrs</td>";
			echo "<td>"; echo $row->lec_hrs + $row->lab_hrs; echo "</td>";
			echo "</tr>";
		}
		echo "<tr>";
		echo "<th colspan='8' style='text-align:center;'>Second semester</th>";
		echo "</tr>";
		$grade33Sem = $conn->query("SELECT grade.grade_id,bsit_subject.subject_code,subject.descriptive_title,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,grade.grade FROM student,bsit_subject,subject,grade WHERE student.student_id = '$studId' && student.student_id = grade.student_id && bsit_subject.subject_code = subject.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.semester_id = 2 && bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id='$currId'" );
		while($row = $grade33Sem->fetch_object()){
			echo "<tr>";
			echo "<td>$row->grade</td>";
			echo "<td>$row->subject_code</td>";
			echo "<td>$row->descriptive_title</td>";
			echo "<td>$row->pre $row->copre $row->standing</td>";
			echo "<td>$row->units</td>";
			echo "<td>$row->lec_hrs</td>";
			echo "<td>$row->lab_hrs</td>";
			echo "<td>"; echo $row->lec_hrs + $row->lab_hrs; echo "</td>";
			echo "</tr>";
		}
		echo "<tr>";
		echo "<th colspan='8' style='text-align:center;'>Summer</th>";
		echo "</tr>";
		$grade3Sum = $conn->query("SELECT grade.grade_id,bsit_subject.subject_code,subject.descriptive_title,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,grade.grade FROM student,bsit_subject,subject,grade WHERE student.student_id = '$studId' && student.student_id = grade.student_id && bsit_subject.subject_code = subject.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id='$currId'" );
		if($grade3Sum->num_rows > 0){
			while($row = $grade3Sum->fetch_object()){
			
				echo "<tr>";
				echo "<td>$row->grade</td>";
				echo "<td>$row->subject_code</td>";
				echo "<td>$row->descriptive_title</td>";
				echo "<td>$row->pre $row->copre $row->standing</td>";
				echo "<td>$row->units</td>";
				echo "<td>$row->lec_hrs</td>";
				echo "<td>$row->lab_hrs</td>";
				echo "<td>"; echo $row->lec_hrs + $row->lab_hrs; echo "</td>";
				echo "</tr>";
			}
		}
		else{
			echo "<tr>";
			echo "<td colspan='8' style='text-align:center;'>No Summer Subjects Available</td>";
			echo "</tr>";
		}

		echo "<tr>";
		echo "<th colspan='8' style='text-align:center;background-color: #323232;color:#f6f6f6;border-top:none;border-bottom:none; '>Fourth Year</th>";
		echo "</tr>";
		echo "<tr>";
		echo "<th colspan='8' style='text-align:center;'>First Semester</th>";
		echo "</tr>";
		$grade4 = $conn->query("SELECT grade.grade_id,bsit_subject.subject_code,subject.descriptive_title,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,grade.grade FROM student,bsit_subject,subject,grade WHERE student.student_id = '$studId' && student.student_id = grade.student_id && bsit_subject.subject_code = subject.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.semester_id = 1 && bsit_subject.yr_lvl = 4 && bsit_subject.curriculum_id='$currId'" );
		while($row = $grade4->fetch_object()){
			echo "<tr>";
			echo "<td>$row->grade</td>";
			echo "<td>$row->subject_code</td>";
			echo "<td>$row->descriptive_title</td>";
			echo "<td>$row->pre $row->copre $row->standing</td>";
			echo "<td>$row->units</td>";
			echo "<td>$row->lec_hrs</td>";
			echo "<td>$row->lab_hrs</td>";
			echo "<td>"; echo $row->lec_hrs + $row->lab_hrs; echo "</td>";
			echo "</tr>";
		}
		echo "<tr>";
		echo "<th colspan='8' style='text-align:center;'>Second semester</th>";
		echo "</tr>";
		$grade42Sem = $conn->query("SELECT grade.grade_id,bsit_subject.subject_code,subject.descriptive_title,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,grade.grade FROM student,bsit_subject,subject,grade WHERE student.student_id = '$studId' && student.student_id = grade.student_id && bsit_subject.subject_code = subject.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.semester_id = 2 && bsit_subject.yr_lvl = 4 && bsit_subject.curriculum_id='$currId'" );
		while($row = $grade42Sem->fetch_object()){
			echo "<tr>";
			echo "<td>$row->grade</td>";
			echo "<td>$row->subject_code</td>";
			echo "<td>$row->descriptive_title</td>";
			echo "<td>$row->pre $row->copre $row->standing</td>";
			echo "<td>$row->units</td>";
			echo "<td>$row->lec_hrs</td>";
			echo "<td>$row->lab_hrs</td>";
			echo "<td>"; echo $row->lec_hrs + $row->lab_hrs; echo "</td>";
			echo "</tr>";
		}
		?>
			
	</table>
</div>
<script type="text/javascript">
		function k(){
			var content_holder = getId("cont");
			var pre = getId("prev");
				function PrintPreview() {
        
        var popupWin = window.open('', '_blank', 'width=950,height=900,location=no');
        popupWin.document.write('<style>'+ 'button{display:none;}' +'</style>');  
        popupWin.document.write('<html><title>Print Preview</title><link rel="stylesheet" type="text/css" href="../admin/assets/style/bootstrap/css/bootstrap.min.css"/></head><body">');
        popupWin.document.write('<style>'+ 'button{display:none;}' +'</style>');
        popupWin.document.write(content_holder.innerHTML);
        // popupWin.document.write(sub.innerHTML);
        // popupWin.document.write('<button>Print</button>');
         // popupWin.document.write('</body>');
        // popupWin.document.write('</html>');
        
    }
   			pre.addEventListener('click',PrintPreview);
		}
		
   		window.addEventListener('load',k);
</script>
<button style='margin:0 auto;' id='prev' class='btn btn-info'><span class='glyphicon glyphicon-print'></span>Print Preview</button>