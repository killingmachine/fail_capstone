<?php
	session_start();
	if(!isset($_SESSION['admin']) && !isset($_SESSION['client'])){
		header("Location: log_in.php");
	}
	require 'class/db.php';
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title>Checklist</title>
	<link href="assets/style/bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
	<script src='assets/script/jquery.min.js'></script>
	<script src='assets/style/bootstrap/js/bootstrap.min.js'></script>
	<link href="assets/style/checklist.css" rel="stylesheet"/>
	

	
</head>
<body>
<?php
	include 'includes/header.php';
?>
<div id='checkCont'>

	<?php
		if(isset($_GET['curID']) && $_GET['cors']){
			$curId = $_GET['curID'];
			$cors = $_GET['cors'];
	///bs It 1styr 1stsem
	$curriculum = $conn->query("SELECT curriculum_yr FROM curriculum WHERE curriculum_id = $curId");
	$cur = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$curId' && curriculum.course_id = '$cors'");
	////2ndtsem
	$cur1 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$curId' && curriculum.course_id = '$cors'");
	////sum
	$cur1S = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$curId' && curriculum.course_id = '$cors'");
	//bs It 2nd 1stsem
	$cur2 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1 && bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$curId' && curriculum.course_id = '$cors'");
	// 2nd sem
	$cur3 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2 && bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$curId' && curriculum.course_id = '$cors'");
	///sum 2
	$cur2S = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$curId' && curriculum.course_id = '$cors'");
	//bs It 3rdyr 1stsem
	$cur4 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1 && bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$curId' && curriculum.course_id = '$cors'");
	//2nd sem
	$cur5 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2 && bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$curId' && curriculum.course_id = '$cors'");
	//bs It 4thyr 1stsem
	$cur3S = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$curId' && curriculum.course_id = '$cors'");

	$cur6 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1 && bsit_subject.yr_lvl = 4 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$curId' && curriculum.course_id = '$cors'");
	//2nd sem
	$cur7 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2 && bsit_subject.yr_lvl = 4 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$curId' && curriculum.course_id = '$cors'");


	// $total = $conn->query("SELECT SUM(lec_hrs) AS total FROM subject");
	$queCurCor = $conn->query("SELECT curriculum.curriculum_id,curriculum.course_id FROM curriculum,course WHERE curriculum.course_id=course.course_id && curriculum.curriculum_id='$curId' && curriculum.course_id = '$cors'");
	$corse = $conn->query("SELECT course FROM course WHERE course_id = $cors");	
		while($shit = $queCurCor->fetch_object()){
			if($cors == $shit->course_id){
				if($curId == $shit->curriculum_id ){
						echo "<table id='tblSub' class='table table-striped '>";
						echo "<thead>";
						echo "<tr>";
						while($curri = $curriculum -> fetch_object()){
							while ($myRow=$corse->fetch_object()) {
								echo "<th colspan=7 id = 'mainHead' class='head'>$myRow->course($curri->curriculum_yr)</th>";
							}
						}
						echo "</tr>";
						echo "</thead>";
						echo "<thead>";
						echo "<tr>";
						echo "<th colspan=7 id = 'fHead' class='head'>First Year</th>";
						echo "</tr>";
						echo "</thead>";
						echo "<thead>";
						echo "<tr>";
						echo "<th colspan=7 class='head'>First semester</th>";
						echo "</tr>";
						echo "</thead>";
						echo "<tr>";
						echo "<thead>";
						echo "<th>Subject Code</th>";
						echo "<th>Descriptive title</th>";
						echo "<th>Requisites</th>";
						echo "<th>Units</th>";
						echo "<th>Lec hrs</th>";
						echo "<th>Lab hrs</th>";
						echo "<th>contact hours</th>";
						echo "</thead>";
						echo "</tr>";
					if($cur->num_rows == 0){
						echo "<tr><td>No Subjects</td></tr>";
					}
					else{
						while($row = $cur->fetch_object()){
							echo "<tr>";
							echo "<td>$row->subject_code</td> ";
							echo "<td>$row->descriptive_title</td> ";
							echo "<td>$row->pre $row->copre$row->standing</td>";
							echo "<td>$row->units</td>";
							echo "<td>$row->lec_hrs</td>";
							echo "<td>$row->lab_hrs</td>";
							echo "<td>$row->contact_hours</td>";
							echo "</tr>";
						
						}
					}
					echo "<thead>";
					echo "<tr>";
					echo "<th colspan='7' class='head'>Second semester</th>";
					echo "</tr>";
					echo "</thead>";
					if($cur1->num_rows == 0){
						echo "<tr><td>No Subjects</td></tr>";
					}
					else{
						while($row = $cur1->fetch_object()){
							echo "<tr>";
								echo "<td>$row->subject_code</td> ";
								echo "<td>$row->descriptive_title</td> ";
								echo "<td>$row->pre $row->copre $row->standing</td>";
								echo "<td>$row->units</td>";
								echo "<td>$row->lec_hrs</td>";
								echo "<td>$row->lab_hrs</td>";
								echo "<td>$row->contact_hours</td>";
								echo "</tr>";			
						}	
					}
						
					echo "<thead>";
					echo "<tr>";
					echo "<th colspan=7 class='head'>Summer</th>";
					echo "</tr>";
					echo "</thead>";
					if($cur1S->num_rows == 0){
						echo "<tr><td>No Summer Subjects</td></tr>";
					}
					else{
							while($row = $cur1S->fetch_object()){
							echo "<tr>";
							echo "<td>$row->subject_code</td> ";
							echo "<td>$row->descriptive_title</td> ";
							echo "<td>$row->pre $row->copre $row->standing</td>";
							echo "<td>$row->units</td>";
							echo "<td>$row->lec_hrs</td>";
							echo "<td>$row->lab_hrs</td>";
							echo "<td>$row->contact_hours</td>";
							echo "</tr>";
						}
					}
					echo "<thead>";
					echo "<tr>";
					echo "<th colspan='7' class='head'>Second Year</th>";
					echo "</tr>";
					echo "</thead>";
					echo "<thead>";
					echo "<tr>";
					echo "<th colspan='7' class='head'>First Semester</th>";
					echo "</tr>";
					echo "</thead>";
					if($cur2->num_rows == 0){
						echo "<tr><td>No Subjects</td></tr>";
					}
					else{
						while($row = $cur2->fetch_object()){
							echo "<tr>";
							echo "<td>$row->subject_code</td> ";
							echo "<td>$row->descriptive_title</td> ";
							echo "<td>$row->pre $row->copre $row->standing</td>";
							echo "<td>$row->units</td>";
							echo "<td>$row->lec_hrs</td>";
							echo "<td>$row->lab_hrs</td>";
							echo "<td>$row->contact_hours</td>";
							echo "</tr>";			
						}
					}
					
					echo "<thead>";
					echo "<tr>";
					echo "<th colspan='7' class='head'>Second Semester</th>";
					echo "</tr>";
					echo "</thead>";
					if($cur3->num_rows == 0){
						echo "<tr><td>No Subjects</td></tr>";
					}
					else{
						while($row = $cur3->fetch_object()){
							echo "<tr>";
							echo "<td>$row->subject_code</td> ";
							echo "<td>$row->descriptive_title</td> ";
							echo "<td>$row->pre $row->copre $row->standing</td>";
							echo "<td>$row->units</td>";
							echo "<td>$row->lec_hrs</td>";
							echo "<td>$row->lab_hrs</td>";
							echo "<td>$row->contact_hours</td>";
							echo "</tr>";			
						}
					}
					
					///sumer
					echo "<thead>";
					echo "<tr>";
					echo "<th colspan=7 class='head'>Summer</th>";
					echo "</tr>";
					echo "</thead>";
					if($cur2S->num_rows == 0){
						echo "<tr><td>No Summer Subjects</td></tr>";
					}
					else{
							while($row = $cur2S->fetch_object()){
							echo "<tr>";
							echo "<td>$row->subject_code</td> ";
							echo "<td>$row->descriptive_title</td> ";
							echo "<td>$row->pre $row->copre $row->standing</td>";
							echo "<td>$row->units</td>";
							echo "<td>$row->lec_hrs</td>";
							echo "<td>$row->lab_hrs</td>";
							echo "<td>$row->contact_hours</td>";
							echo "</tr>";
						}
					}
					echo "<thead>";
					echo "<tr>";
					echo "<th colspan='7' class='head'>Third Year</th>";
					echo "</tr>";
					echo "</thead>";
					echo "<thead>";
					echo "<tr>";
					echo "<th colspan='7' class='head'>First Semester</th>";
					echo "</tr>";
					echo "</thead>";
					if($cur4->num_rows == 0){
						echo "<tr><td>No Subjects</td></tr>";
					}
					else{
						while($row = $cur4->fetch_object()){
							echo "<tr>";
							echo "<td>$row->subject_code</td> ";
							echo "<td>$row->descriptive_title</td> ";
							echo "<td>$row->pre $row->copre $row->standing</td>";
							echo "<td>$row->units</td>";
							echo "<td>$row->lec_hrs</td>";
							echo "<td>$row->lab_hrs</td>";
							echo "<td>$row->contact_hours</td>";
							echo "</tr>";			
						}
					}
					
					echo "<thead>";
					echo "<tr>";
					echo "<th colspan='7' class='head'>Second Semester</th>";
					echo "</tr>";
					echo "</thead>";
					if($cur5->num_rows == 0){
						echo "<tr><td>No Subjects</td></tr>";
					}
					else{
						while($row = $cur5->fetch_object()){
							echo "<tr>";
							echo "<td>$row->subject_code</td> ";
							echo "<td>$row->descriptive_title</td> ";
							echo "<td>$row->pre $row->copre $row->standing</td>";
							echo "<td>$row->units</td>";
							echo "<td>$row->lec_hrs</td>";
							echo "<td>$row->lab_hrs</td>";
							echo "<td>$row->contact_hours</td>";
							echo "</tr>";			
						}
					}
					
					echo "<thead>";
					echo "<tr>";
					echo "<th colspan=7 class='head'>Summer</th>";
					echo "</tr>";
					echo "</thead>";
					if($cur3S->num_rows == 0){
						echo "<tr><td>No Summer Subjects</td></tr>";
					}
					else{
							while($row = $cur3S->fetch_object()){
							echo "<tr>";
							echo "<td>$row->subject_code</td> ";
							echo "<td>$row->descriptive_title</td> ";
							echo "<td>$row->pre $row->copre $row->standing</td>";
							echo "<td>$row->units</td>";
							echo "<td>$row->lec_hrs</td>";
							echo "<td>$row->lab_hrs</td>";
							echo "<td>$row->contact_hours</td>";
							echo "</tr>";
						}
					}
					echo "<thead>";
					echo "<tr>";
					echo "<th colspan='7' class='head'>4th Year</th>";
					echo "</tr>";
					echo "</thead>";
					echo "<thead>";
					echo "<tr>";
					echo "<th colspan='7' class='head'>First Semester</th>";
					echo "</tr>";
					echo "</thead>";
					if($cur6->num_rows == 0){
						echo "<tr><td>No Subjects</td></tr>";
					}
					else{
						while($row = $cur6->fetch_object()){
							echo "<tr>";
							echo "<td>$row->subject_code</td> ";
							echo "<td>$row->descriptive_title</td> ";
							echo "<td>$row->pre $row->copre $row->standing</td>";
							echo "<td>$row->units</td>";
							echo "<td>$row->lec_hrs</td>";
							echo "<td>$row->lab_hrs</td>";
							echo "<td>$row->contact_hours</td>";
							echo "</tr>";			
						}
					}
					echo "<thead>";
					echo "<tr>";
					echo "<th colspan='7' class='head'>Second Semester</th>";
					echo "</tr>";
					echo "</thead>";
					if($cur7->num_rows == 0){
						echo "<tr><td>No Subjects</td></tr>";
					}
					else{
						while($row = $cur7->fetch_object()){
							echo "<tr>";
							echo "<td>$row->subject_code</td> ";
							echo "<td>$row->descriptive_title</td> ";
							echo "<td>$row->pre $row->copre $row->standing</td>";
							echo "<td>$row->units</td>";
							echo "<td>$row->lec_hrs</td>";
							echo "<td>$row->lab_hrs</td>";
							echo "<td>$row->contact_hours</td>";
							echo "</tr>";			
						}
					}
					
					echo "</table>";
				}

			}
		}		
	}

	?>
</div>
<div>
</div>
<script type="text/javascript">
	var flg = true;
    var ham = getId('hamburger');
    var d_holder = getId('ul_down');
    function show_menu(){
      if(!flg){
        d_holder.style.transition = .2+'s';
        d_holder.style.height = '0';
        flg = true;
      }
      else{
        d_holder.style.transition = .2+'s';
        d_holder.style.height = '110px';
        flg = false;
      }
        
    }
    ham.addEventListener('click',show_menu);
</script>
</body>
</html>