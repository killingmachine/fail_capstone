<button id='prev' class='btn btn-info'><span class='glyphicon glyphicon-print'></span>Print Preview</button>
<div id='msg11'></div>
<?php
	$studId = $_GET['studId']; 
	$currId = $_GET['currId'];
		$s = $conn->query("SELECT curriculum_id FROM
		 	bsit_subject WHERE 
		 	curriculum_id = '$currId'");
		if($s->num_rows > 0){
		$name = $conn->query("SELECT name, last_name,year_level FROM student WHERE student_id = '$studId' LIMIT 1");

		$cur = $conn->query("SELECT course.course,curriculum.curriculum_yr FROM course,curriculum,student WHERE student.student_id = '$studId' && student.curriculum_id = curriculum.curriculum_id && course.course_id = curriculum.course_id LIMIT 1");
		
		echo "<table class='table table-striped '>";
		echo "<thead>";
		echo "<tr>";
		echo "<th style='border:none;'>ID #: <span id='studHehe'>$studId</span></th>";
		echo "</tr>";
		echo "</thead>";
		/////
		echo "<thead>";
		echo "<tr>";
		while($name_row = $name->fetch_object())	{
			echo "<th style='border:none;' colspan=4>Name: $name_row->last_name, $name_row->name</th>";  
			echo "<th style='border:none;' colspan=4>Year: ".$name_row->year_level.yrLevel($name_row->year_level)."</th>"; 
		}
		echo "</tr>";
		echo "</thead>";
		/////
		echo "<thead>";
		echo "<tr>";
		while($row = $cur->fetch_object())	{
			echo "<th colspan='8' style='text-align:center;border:none;'>$row->course</th>";
		echo "</tr>";
		echo "</thead>";
		
		echo "<thead>";
			echo "<th colspan='8' style='text-align:center;border-top:none;'>$row->curriculum_yr(curriculum)</th>";
		}
		echo "</thead>";
		
		
		//grades
		echo "<thead>";

		echo "<tr>";
		echo "<th>grade</th>";
		echo "<th>Subject Code</th>";
		echo "<th>Descriptive Title</th>";
		echo "<th>Pre-requisite</th>";
		echo "<th>Units</th>";
		echo "<th>Lec Hrs</th>";
		echo "<th>Lab Hrs</th>";
		echo "<th>Contact Hrs</th>";
		echo "</tr>";

		echo "</thead>";
		echo "<thead>";

		echo "<tr>";
		echo "<th colspan='8' style='text-align:center;background-color: #323232;color:#f6f6f6;border-top:none;border-bottom:none; '>First Year</th>";
		echo "</tr>";

		echo "</thead>";
		echo "<thead>";
		echo "<tr>";
		echo "<th colspan='8' style='text-align:center;'>First semester</th>";
		echo "</tr>";
		echo "</thead>";
		

		$grade = $conn->query("SELECT grade.grade_id,bsit_subject.subject_code,subject.descriptive_title,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,grade.grade FROM student,bsit_subject,subject,grade WHERE student.student_id = '$studId' && student.student_id = grade.student_id && bsit_subject.subject_code = subject.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.semester_id = 1 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id='$currId'" );
		while($row = $grade->fetch_object()){
			///<span id='chk' class='glyphicon glyphicon-ok'</span>
			echo "<tr>";
			echo "<td><input class='grd' id='$row->grade_id' name='grd1' type='text' placeholder='$row->grade_id' maxlength='2' value='$row->grade'></td>";
			echo "<td>$row->subject_code</td>";
			echo "<td>$row->descriptive_title</td>";
			echo "<td>$row->pre $row->copre $row->standing</td>";
			echo "<td>$row->units</td>";
			echo "<td>$row->lec_hrs</td>";
			echo "<td>$row->lab_hrs</td>";
			echo "<td>"; echo $row->lec_hrs + $row->lab_hrs; echo "</td>";
			echo "</tr>";
		}
		echo "<thead>";
		echo "<tr>";
		echo "<th colspan='8' style='text-align:center;'>Second semester</th>";
		echo "</tr>";
		echo "</thead>";
		$grade1 = $conn->query("SELECT grade.grade_id,bsit_subject.subject_code,subject.descriptive_title,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,grade.grade FROM student,bsit_subject,subject,grade WHERE student.student_id = '$studId' && student.student_id = grade.student_id && bsit_subject.subject_code = subject.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.semester_id = 2 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id='$currId'" );
		while($row = $grade1->fetch_object()){
			echo "<tr>";
			echo "<td><input class='grd' id='$row->grade_id' name='grd1' type='text' placeholder='$row->grade_id' maxlength='2' value='$row->grade'></td>";
			echo "<td>$row->subject_code</td>";
			echo "<td>$row->descriptive_title</td>";
			echo "<td>$row->pre $row->copre $row->standing</td>";
			echo "<td>$row->units</td>";
			echo "<td>$row->lec_hrs</td>";
			echo "<td>$row->lab_hrs</td>";
			echo "<td>"; echo $row->lec_hrs + $row->lab_hrs; echo "</td>";
			echo "</tr>";
		}
		echo "<thead>";
		echo "<tr>";
		echo "<th colspan='8' style='text-align:center;'>Summer</th>";
		echo "</tr>";
		echo "</thead>";
		$grade1Sum = $conn->query("SELECT grade.grade_id,bsit_subject.subject_code,subject.descriptive_title,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,grade.grade FROM student,bsit_subject,subject,grade WHERE student.student_id = '$studId' && student.student_id = grade.student_id && bsit_subject.subject_code = subject.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id='$currId'" );
		if($grade1Sum->num_rows > 0){
			while($row = $grade1Sum->fetch_object()){
			
				echo "<tr>";
				echo "<td><input class='grd' id='$row->grade_id' name='grd1' type='text' placeholder='$row->grade_id' maxlength='2' value='$row->grade'></td>";
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
		echo "<td colspan='8' style='text-align: center;'><button value='$studId' id='bE1'>Elevate</button><br/><span id='e1'></span></td>";
		echo "</tr>";

		echo "<thead>";
		echo "<tr>";
		echo "<th colspan='8' style='text-align:center;background-color: #323232;color:#f6f6f6;border-top:none;border-bottom:none; '>Second Year</th>";
		echo "</tr>";
		echo "</thead>";
		echo "<thead>";
		echo "<tr>";
		echo "<th colspan='8' style='text-align:center;'>First Semester</th>";
		echo "</tr>";
		echo "</thead>";
		$grade2 = $conn->query("SELECT grade.grade_id,bsit_subject.subject_code,subject.descriptive_title,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,grade.grade FROM student,bsit_subject,subject,grade WHERE student.student_id = '$studId' && student.student_id = grade.student_id && bsit_subject.subject_code = subject.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.semester_id = 1 && bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id='$currId'" );
		while($row = $grade2->fetch_object()){
			echo "<tr>";
			echo "<td><input class='grd' id='$row->grade_id' name='grd1' type='text' placeholder='$row->grade_id' maxlength='2' value='$row->grade'></td>";
			echo "<td>$row->subject_code</td>";
			echo "<td>$row->descriptive_title</td>";
			echo "<td>$row->pre $row->copre $row->standing</td>";
			echo "<td>$row->units</td>";
			echo "<td>$row->lec_hrs</td>";
			echo "<td>$row->lab_hrs</td>";
			echo "<td>"; echo $row->lec_hrs + $row->lab_hrs; echo "</td>";
			echo "</tr>";
		}
		echo "<thead>";
		echo "<tr>";
		echo "<th colspan='8' style='text-align:center;'>Second semester</th>";
		echo "</tr>";
		echo "</thead>";
		$grade22Sem = $conn->query("SELECT grade.grade_id,bsit_subject.subject_code,subject.descriptive_title,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,grade.grade FROM student,bsit_subject,subject,grade WHERE student.student_id = '$studId' && student.student_id = grade.student_id && bsit_subject.subject_code = subject.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.semester_id = 2 && bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id='$currId'" );
		while($row = $grade22Sem->fetch_object()){
			echo "<tr>";
			echo "<td><input class='grd' id='$row->grade_id' name='grd1' type='text' placeholder='$row->grade_id' maxlength='2' value='$row->grade'></td>";
			echo "<td>$row->subject_code</td>";
			echo "<td>$row->descriptive_title</td>";
			echo "<td>$row->pre $row->copre $row->standing</td>";
			echo "<td>$row->units</td>";
			echo "<td>$row->lec_hrs</td>";
			echo "<td>$row->lab_hrs</td>";
			echo "<td>"; echo $row->lec_hrs + $row->lab_hrs; echo "</td>";
			echo "</tr>";
		}
		echo "<thead>";
		echo "<tr>";
		echo "<th colspan='8' style='text-align:center;'>Summer</th>";
		echo "</tr>";
		echo "</thead>";
	
		$grade2Sum = $conn->query("SELECT grade.grade_id,bsit_subject.subject_code,subject.descriptive_title,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,grade.grade FROM student,bsit_subject,subject,grade WHERE student.student_id = '$studId' && student.student_id = grade.student_id && bsit_subject.subject_code = subject.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id='$currId'" );
		if($grade2Sum->num_rows > 0){
			while($row = $grade2Sum->fetch_object()){	
				echo "<tr>";
				echo "<td><input class='grd' id='$row->grade_id' name='grd1' type='text' placeholder='$row->grade_id' maxlength='2' value='$row->grade'></td>";
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
		echo "<td colspan='8' style='text-align: center;'><button value='$studId' id='bE2'>Elevate</button><br/><span id='e2'></span></td>";
		echo "</tr>";
		echo "<thead>";
		echo "<tr>";
		echo "<th colspan='8' style='text-align:center;background-color: #323232;color:#f6f6f6;border-top:none;border-bottom:none; '>Third Year</th>";
		echo "</tr>";
		echo "</thead>";
		echo "<thead>";
		echo "<tr>";
		echo "<th colspan='8' style='text-align:center;'>First Semester</th>";
		echo "</tr>";
		echo "</thead>";
		$grade3 = $conn->query("SELECT grade.grade_id,bsit_subject.subject_code,subject.descriptive_title,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,grade.grade FROM student,bsit_subject,subject,grade WHERE student.student_id = '$studId' && student.student_id = grade.student_id && bsit_subject.subject_code = subject.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.semester_id = 1 && bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id='$currId'" );
		while($row = $grade3->fetch_object()){
			echo "<tr>";
			echo "<td><input class='grd' id='$row->grade_id' name='grd1' type='text' placeholder='$row->grade_id' maxlength='2' value='$row->grade'></td>";
			echo "<td>$row->subject_code</td>";
			echo "<td>$row->descriptive_title</td>";
			echo "<td>$row->pre $row->copre $row->standing</td>";
			echo "<td>$row->units</td>";
			echo "<td>$row->lec_hrs</td>";
			echo "<td>$row->lab_hrs</td>";
			echo "<td>"; echo $row->lec_hrs + $row->lab_hrs; echo "</td>";
			echo "</tr>";
		}
		echo "<thead>";
		echo "<tr>";
		echo "<th colspan='8' style='text-align:center;'>Second semester</th>";
		echo "</tr>";
		echo "</thead>";
		$grade33Sem = $conn->query("SELECT grade.grade_id,bsit_subject.subject_code,subject.descriptive_title,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,grade.grade FROM student,bsit_subject,subject,grade WHERE student.student_id = '$studId' && student.student_id = grade.student_id && bsit_subject.subject_code = subject.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.semester_id = 2 && bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id='$currId'" );
		while($row = $grade33Sem->fetch_object()){
			echo "<tr>";
			echo "<td><input class='grd' id='$row->grade_id' name='grd1' type='text' placeholder='$row->grade_id' maxlength='2' value='$row->grade'></td>";
			echo "<td>$row->subject_code</td>";
			echo "<td>$row->descriptive_title</td>";
			echo "<td>$row->pre $row->copre $row->standing</td>";
			echo "<td>$row->units</td>";
			echo "<td>$row->lec_hrs</td>";
			echo "<td>$row->lab_hrs</td>";
			echo "<td>"; echo $row->lec_hrs + $row->lab_hrs; echo "</td>";
			echo "</tr>";
		}

		echo "<thead>";
		echo "<tr>";
		echo "<th colspan='8' style='text-align:center;'>Summer</th>";
		echo "</tr>";
		echo "</thead>";
		
		$grade3Sum = $conn->query("SELECT grade.grade_id,bsit_subject.subject_code,subject.descriptive_title,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,grade.grade FROM student,bsit_subject,subject,grade WHERE student.student_id = '$studId' && student.student_id = grade.student_id && bsit_subject.subject_code = subject.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id='$currId'" );
		if($grade3Sum->num_rows > 0){
			while($row = $grade3Sum->fetch_object()){
			
				echo "<tr>";
				echo "<td><input class='grd' id='$row->grade_id' name='grd1' type='text' placeholder='$row->grade_id' maxlength='2' value='$row->grade'></td>";
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
		echo "<td colspan='8' style='text-align: center;'><button value='$studId' id='bE3'>Elevate</button><br/><span id='e3'></span></td>";
		echo "</tr>";

		echo "<thead>";
		echo "<tr>";
		echo "<th colspan='8' style='text-align:center;background-color: #323232;color:#f6f6f6;border-top:none;border-bottom:none; '>Fourth Year</th>";
		echo "</tr>";
		echo "</thead>";
		echo "<thead>";
		echo "<tr>";
		echo "<th colspan='8' style='text-align:center;'>First Semester</th>";
		echo "</tr>";
		echo "</thead>";
		$grade4 = $conn->query("SELECT grade.grade_id,bsit_subject.subject_code,subject.descriptive_title,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,grade.grade FROM student,bsit_subject,subject,grade WHERE student.student_id = '$studId' && student.student_id = grade.student_id && bsit_subject.subject_code = subject.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.semester_id = 1 && bsit_subject.yr_lvl = 4 && bsit_subject.curriculum_id='$currId'" );
		while($row = $grade4->fetch_object()){
			echo "<tr>";
			echo "<td><input class='grd' id='$row->grade_id' name='grd1' type='text' placeholder='$row->grade_id' maxlength='2' value='$row->grade'></td>";
			echo "<td>$row->subject_code</td>";
			echo "<td>$row->descriptive_title</td>";
			echo "<td>$row->pre $row->copre $row->standing</td>";
			echo "<td>$row->units</td>";
			echo "<td>$row->lec_hrs</td>";
			echo "<td>$row->lab_hrs</td>";
			echo "<td>"; echo $row->lec_hrs + $row->lab_hrs; echo "</td>";
			echo "</tr>";
		}
		echo "<thead>";
		echo "<tr>";
		echo "<th colspan='8' style='text-align:center;'>Second semester</th>";
		echo "</tr>";
		echo "</thead>";

		$grade42Sem = $conn->query("SELECT grade.grade_id,bsit_subject.subject_code,subject.descriptive_title,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,grade.grade FROM student,bsit_subject,subject,grade WHERE student.student_id = '$studId' && student.student_id = grade.student_id && bsit_subject.subject_code = subject.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.semester_id = 2 && bsit_subject.yr_lvl = 4 && bsit_subject.curriculum_id='$currId'" );
		while($row = $grade42Sem->fetch_object()){
			echo "<tr>";
			echo "<td><input class='grd' id='$row->grade_id' name='grd1' type='text' placeholder='$row->grade_id' maxlength='2' value='$row->grade'></td>";
			echo "<td>$row->subject_code</td>";
			echo "<td>$row->descriptive_title</td>";
			echo "<td>$row->pre $row->copre $row->standing</td>";
			echo "<td>$row->units</td>";
			echo "<td>$row->lec_hrs</td>";
			echo "<td>$row->lab_hrs</td>";
			echo "<td>"; echo $row->lec_hrs + $row->lab_hrs; echo "</td>";
			echo "</tr>";
		}
		echo "</table>";
		// echo "<script src='../script/grade.js'></script>";
	}
	else{
		header("Location: ../admin/index.php");
		$_SESSION['err3'] = 'No subject on the curricullum';
	}
?>
<script src='assets/script/grade.js'></script>