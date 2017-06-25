<?php
session_start();
require_once 'admin/functions/functions.php';
require 'admin/class/db.php';
$queSesh = $conn->query("SELECT * FROM sess");
	if($queSesh->num_rows > 0){
		while($row = $queSesh->fetch_object()){
			$curSem = $row->sem_sesh;
		}
	}
if(!isset($_SESSION['currentUser'])){
	header("Location: log_in.php");	
}
else{
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title>CICS EVALUATION SYSTEM</title>
	<link href="assets/style/bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
	<script src='assets/script/jquery.min.js'></script>
	<script src='assets/style/bootstrap/js/bootstrap.min.js'></script>
	<link rel="stylesheet" type="text/css" href="assets/style/style.css"> 
	<script src='assets/script/frame.js' type='text/javascript'></script>
</head>
<body>
	<script type="text/javascript">
		// alert(window.innerWidth);
	</script>
	<?php include 'admin/includes/header.php' ?>
	<div id="cont">
	
<?php
	if(isset($_SESSION['currentUser'])){
		if(isset($_GET['checklist'])){
			
			include 'assets/grd.php';
		}
		if(isset($_GET['student'])){
			if($_GET['student'] != $_SESSION['currentUser']){
				header('Location: index.php');
			}
			else{
				$oPass = $_GET['student'];
				echo "<div style='width:600px; margin:0 auto 0 auto ;'>";
				echo "<span>Old Password: <input id='o' type='password' maxlength='30'/></span><span style='color:red;margin-right:10px;' id='g'></span><span>New Password: <input id='n' type='password' maxlength='30'/></span><span id='h' style='color:red;'></span><br/><button style='margin:20px 0 0 220px;width:150px;' value='$oPass' id ='btnC'>Change</button><br/><span style='display:block;text-align:center;' id='cMsg'></span>";
				echo "</div>";	
			}
		}

		if(!isset($_GET['checklist']) && !isset($_GET['student'])){

		echo "<script src='assets/script/advicing.js'></script>";
		$studId = $_SESSION['currentUser'];
		$curr = 0;
		$cors = 0;
		$yrLvl = '';
		$qcurId = $conn->query("SELECT curriculum_id,year_level FROM student WHERE student_id ='$studId' LIMIT 1");
		while ($row = $qcurId->fetch_object()) {
			$yrLvl = $row->year_level;
			$qcorcur=$conn->query("SELECT course_id,curriculum_id FROM curriculum WHERE curriculum_id = $row->curriculum_id");
			while($row1 = $qcorcur->fetch_object()) {
				$curr = $row1->curriculum_id;
			 	$cors =  $row1->course_id;	
			}
			 
		}
		$_SESSION['idStud'] = $studId;
		$_SESSION['idCurr'] = $curr;
		$fSub = array();
		$failedSub = $conn->query("SELECT bsit_subject.subject_code,subject.descriptive_title FROM student,bsit_subject,subject,grade WHERE student.student_id = '$studId' && student.student_id = grade.student_id && bsit_subject.subject_code = subject.subject_code && 
			bsit_subject.subject_code = grade.subject_code && bsit_subject.curriculum_id = '$curr' && grade > 50 && grade < 75");
		$rSub = array();
		if($cors == 1){
			if($yrLvl==1){
				
				$reqSub = $conn->query("SELECT student.year_level,grade.subject_code,grade.student_id,grade.grade FROM grade,student,bsit_subject WHERE bsit_subject.subject_code = grade.subject_code && bsit_subject.yr_lvl = '1' && student.student_id = 12 && grade.student_id = student.student_id && student.year_level='1' && LEFT(grade.subject_code , 2) = 'IT'");
				while ($rowReq = $reqSub->fetch_object()) {
					$rSub[$rowReq->subject_code] = $rowReq->grade;
					// echo $rSub[$rowReq->subject_code];
					if($rSub[$rowReq->subject_code] >= 75){
						$conn->query("UPDATE student SET  year_level='$yrLvl',standing='sophomore standing' WHERE student.student_id = '$studId'");
						$_GET['yrLvl'] = 2;
						$yrLvl = $_GET['yrLvl'];
					}
					
				}
				
			}
			else if($yrLvl==2){
				
				$reqSub = $conn->query("SELECT student.year_level,grade.subject_code,grade.student_id,grade.grade FROM grade,student,bsit_subject WHERE bsit_subject.subject_code = grade.subject_code && bsit_subject.yr_lvl = '2' && student.student_id = 12 && grade.student_id = student.student_id && student.year_level='2' && LEFT(grade.subject_code , 2) = 'IT'");
				while ($rowReq = $reqSub->fetch_object()) {
					$rSub[$rowReq->subject_code] = $rowReq->subject_code;
					if($rSub[$rowReq->subject_code] >= 75){
						$conn->query("UPDATE student SET  year_level='$yrLvl',standing='junior standing' WHERE student.student_id = '$studId'");
						$_GET['yrLvl'] = 3;
						$yrLvl = $_GET['yrLvl'];
					}
				}
			}
			else if($yrLvl==3){
				$reqSub = $conn->query("SELECT student.year_level,grade.subject_code,grade.student_id,grade.grade FROM grade,student,bsit_subject WHERE bsit_subject.subject_code = grade.subject_code && bsit_subject.yr_lvl = '3' && student.student_id = 12 && grade.student_id = student.student_id && student.year_level='3' && LEFT(grade.subject_code , 2) = 'IT'");
				while ($rowReq = $reqSub->fetch_object()) {
					$rSub[$rowReq->subject_code] = $rowReq->subject_code;
					if($rSub[$rowReq->subject_code] >= 75){
						$conn->query("UPDATE student SET  year_level='$yrLvl',standing='senior standing' WHERE student.student_id = '$studId'");
						$_GET['yrLvl'] = 4;
						$yrLvl = $_GET['yrLvl'];
					}
				}
			}
		}
		else if($cors == 2){
			if($yrLvl==1){
				
				$reqSub = $conn->query("SELECT student.year_level,grade.subject_code,grade.student_id,grade.grade FROM grade,student,bsit_subject WHERE bsit_subject.subject_code = grade.subject_code && bsit_subject.yr_lvl = '1' && student.student_id = 12 && grade.student_id = student.student_id && student.year_level='1' && LEFT(grade.subject_code , 2) = 'CS'");
				while ($rowReq = $reqSub->fetch_object()) {
					$rSub[$rowReq->subject_code] = $rowReq->subject_code;
				}
				
			}
			else if($yrLvl==2){
				
				$reqSub = $conn->query("SELECT student.year_level,grade.subject_code,grade.student_id,grade.grade FROM grade,student,bsit_subject WHERE bsit_subject.subject_code = grade.subject_code && bsit_subject.yr_lvl = '2' && student.student_id = 12 && grade.student_id = student.student_id && student.year_level='2' && LEFT(grade.subject_code , 2) = 'CS'");
				while ($rowReq = $reqSub->fetch_object()) {
					$rSub[$rowReq->subject_code] = $rowReq->subject_code;
				}
			}
			else if($yrLvl==3){
				$reqSub = $conn->query("SELECT student.year_level,grade.subject_code,grade.student_id,grade.grade FROM grade,student,bsit_subject WHERE bsit_subject.subject_code = grade.subject_code && bsit_subject.yr_lvl = '3' && student.student_id = 12 && grade.student_id = student.student_id && student.year_level='3' && LEFT(grade.subject_code , 2) = 'CS'");
				while ($rowReq = $reqSub->fetch_object()) {
					$rSub[$rowReq->subject_code] = $rowReq->subject_code;
				}
			}
		}
		else if($cors == 3){
			if($yrLvl==1){
				
				$reqSub = $conn->query("SELECT student.year_level,grade.subject_code,grade.student_id,grade.grade FROM grade,student,bsit_subject WHERE bsit_subject.subject_code = grade.subject_code && bsit_subject.yr_lvl = '1' && student.student_id = 12 && grade.student_id = student.student_id && student.year_level='1' && LEFT(grade.subject_code , 2) = 'ITA'");
				while ($rowReq = $reqSub->fetch_object()) {
					$rSub[$rowReq->subject_code] = $rowReq->subject_code;
				}
				
			}
			else if($yrLvl==2){
				
				$reqSub = $conn->query("SELECT student.year_level,grade.subject_code,grade.student_id,grade.grade FROM grade,student,bsit_subject WHERE bsit_subject.subject_code = grade.subject_code && bsit_subject.yr_lvl = '2' && student.student_id = 12 && grade.student_id = student.student_id && student.year_level='2' && LEFT(grade.subject_code , 2) = 'ITA'");
				while ($rowReq = $reqSub->fetch_object()) {
					$rSub[$rowReq->subject_code] = $rowReq->subject_code;
				}
			}
			else if($yrLvl==3){
				$reqSub = $conn->query("SELECT student.year_level,grade.subject_code,grade.student_id,grade.grade FROM grade,student,bsit_subject WHERE bsit_subject.subject_code = grade.subject_code && bsit_subject.yr_lvl = '3' && student.student_id = 12 && grade.student_id = student.student_id && student.year_level='3' && LEFT(grade.subject_code , 2) = 'ITA'");
				while ($rowReq = $reqSub->fetch_object()) {
					$rSub[$rowReq->subject_code] = $rowReq->subject_code;
				}
			}
		}
		// $conn->query("UPDATE student SET year_level=2 WHERE student.student_id = '$studId'");
		// $yrLvl = 2;

		
		
		
		$pSub = array();
		$curSub = array();
		$na =  array();
		///
		$stud = $conn->query("SELECT course.course,student.student_id,student.name,student.last_name,student.year_level FROM student,course,curriculum WHERE (student.student_id = '$studId' && curriculum.curriculum_id = '$curr' && curriculum.course_id=course.course_id)");
		echo "<div id='studDesc'>";

		echo "<table>";

		while($row = $stud->fetch_object()){
			echo "<span style='font-weight:bold;'>ID Number: </span>$row->student_id";
			echo "<tr style=''><td style='font-weight:bold;'>Name:</td><td>$row->name $row->last_name</td></tr>";
			echo "<tr style=''><td style='font-weight:bold;'>Course:</td><td style=''>$row->course</td></tr>";
			echo "<tr><td style='font-weight:bold;'>Year Level: </td><td>$row->year_level"; echo yrLevel($row->year_level); echo "</td></tr>";
			
		}
		echo "</table>";
	
		
		////pass Subjects
		$passSub = $conn->query("SELECT bsit_subject.subject_code,subject.descriptive_title FROM student,bsit_subject,subject,grade WHERE student.student_id = '$studId' && student.student_id = grade.student_id && bsit_subject.subject_code = subject.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.curriculum_id = '$curr' && grade <> 'N/A' && grade >=75 ");
			while($row = $passSub->fetch_object()){
				$pSub[$row->subject_code] = $row->subject_code;
				// $p = print_r($pSub);
				// echo "<pre>$p</pre>";
				// echo "<span >Code: $row->subject_code  &nbsp&nbsp&nbsp&nbsp</span><span> Title: $row->descriptive_title</span><br/>";
			}
		echo "</div>";
		echo "<div id='shet'>";	
		if($failedSub->num_rows == 0){
			echo "<span>NO FAILED SUBJECTS</span>";	
		}
		else{
				echo "$failedSub->num_rows Failed Subject(s) <a id=fSub href=''>See Failed subject</a>";
				while($row = $failedSub->fetch_object()){
					$fSub[$row->subject_code] = $row->subject_code;
					
			}
		}

		echo "</div>";
		
		
		
		echo "<div id='addSub'>";

		$subAdd = $conn->query("SELECT grade.grade,grade.subject_code,subject.units,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing FROM student,grade,subject,bsit_subject WHERE bsit_subject.curriculum_id = '$curr' && grade.student_id =  '$studId' && grade.student_id = student.student_id && bsit_subject.semester_id= '$curSem' &&  grade.subject_code = subject.subject_code && bsit_subject.subject_code = grade.subject_code ");

		$cuRsub = $conn->query("SELECT subject.units,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,bsit_subject.subject_code,subject.descriptive_title FROM bsit_subject,subject,curriculum,course WHERE (bsit_subject.curriculum_id = '$curr' && bsit_subject.curriculum_id = curriculum.curriculum_id && bsit_subject.yr_lvl = '$yrLvl' && bsit_subject.semester_id='$curSem' && curriculum.course_id='$cors' && curriculum.course_id = course.course_id   &&  bsit_subject.subject_code = subject.subject_code )");

		$naSub = $conn->query("SELECT bsit_subject.subject_code,subject.descriptive_title FROM student,bsit_subject,subject,grade WHERE student.student_id = '$studId' && student.student_id = grade.student_id && bsit_subject.subject_code = subject.subject_code && bsit_subject.subject_code = grade.subject_code && bsit_subject.curriculum_id = '$curr' && grade = 'N/A' ");
		while($row = $naSub->fetch_object()){
			$na[$row->subject_code] = $row->subject_code;
			// $p = print_r($na);
			// echo "<pre>$p</pre>";
		}
		while($row = $cuRsub->fetch_object()){
			$curSub[$row->subject_code] = $row->subject_code;
			// $p = print_r($curSub);
			// echo "<pre>$p</pre>";
		}
		echo "<span>SUBJECTS YOU CAN ADD</span><br/>";
		echo "<table id='addTable' class='table table-striped '>";
		echo "<thead class='thead-inverse'>";
		echo "<tr>";
		echo "<th></th>";
		echo "<th>Code</th>";
		echo "<th>Title</th>";
		echo "<th>Units</th>";
		echo "</tr>";
		echo "</thead>";
			while($row = $subAdd->fetch_object()){
				echo "<tr>";
				if(array_key_exists($row->subject_code,$curSub)){

					continue;
				}
				else{
					//echo $row->subject_code;
					if($row->grade == 'N/A'){
						if($row->pre == '' && $row->copre == '' && $row->standing == ''){
						//requisites with value
							echo "<td><input type='checkbox' value='"; echo $row->subject_code.'.'.escape($row->descriptive_title).'.'.$row->units; echo "'/></td><td>$row->subject_code</td><td>$row->descriptive_title</td><td>$row->units</td>";
						}
						// else if($row->pre != '' && $row->copre == ''&& $row->standing == ''){

						// }
						////pre only
						else if($row->pre != '' && $row->copre == ''&& $row->standing == ''){
							if(array_key_exists($row->pre, $na)){
								continue;
							}
							else{
								if(array_key_exists($row->pre, $fSub)){
									continue;
								}
								else{
									echo "<td><input type='checkbox' value='"; echo $row->subject_code.'.'.escape($row->descriptive_title).'.'.$row->units; echo "'/></td><td>$row->subject_code</td><td>$row->descriptive_title</td><td>$row->units</td>";
								}
							}
						}
						///copre only
						else if($row->copre != '' && $row->pre == ''&& $row->standing == '') {
							if(array_key_exists($row->pre, $na)){
								continue;
							}
							else{
								if(array_key_exists($row->pre, $fSub)){
									continue;
								}
								else{
									echo "<td><input type='checkbox' value='"; echo $row->subject_code.'.'.escape($row->descriptive_title).'.'.$row->units; echo "'/></td><td>$row->subject_code</td><td>$row->descriptive_title</td><td>$row->units</td>";
								}	
							}	
						}
						///pre and co pre
						else if($row->pre != '' && $row->copre != ''){
							if(array_key_exists($row->pre, $na) || array_key_exists($row->copre, $na)){
									continue;
								}
							else{
								if(!array_key_exists($row->pre, $fSub) && !array_key_exists($row->copre, $fSub)){
									echo "<td><input type='checkbox' value='"; echo $row->subject_code.'.'.escape($row->descriptive_title).'.'.$row->units; echo "'/></td><td>$row->subject_code</td><td>$row->descriptive_title</td><td>$row->units</td>";
						
								}
								else{
									continue;	
								}

							}
						}
						else{
							if($row->standing == getStanding($yrLvl)){
								echo "<td><input type='checkbox' value='"; echo $row->subject_code.'.'.escape($row->descriptive_title).'.'.$row->units; echo "'/></td><td>$row->subject_code</td><td>$row->descriptive_title</td><td>$row->units</td>";
							}
							else{
								continue;
							}
						}
						
						
					}	
					
					else{
						if($row->grade <75){
							if($row->pre == '' && $row->copre == '' && $row->standing == ''){
							//requisites with value
								echo "<td><input type='checkbox' value='"; echo $row->subject_code.'.'.escape($row->descriptive_title).'.'.$row->units; echo "'/></td><td>$row->subject_code</td><td>$row->descriptive_title</td><td>$row->units</td>";
							}
							////lahat my pre
							// else if($row->pre != '' && $row->copre == ''&& $row->standing == ''){

							// }
							////pre only
							else if($row->pre != '' && $row->copre == ''&& $row->standing == ''){
								if(array_key_exists($row->pre, $na)){
									continue;
								}
								else{
									if(array_key_exists($row->pre, $fSub)){
										continue;
									}
									else{
										echo "<td><input type='checkbox' value='"; echo $row->subject_code.'.'.escape($row->descriptive_title).'.'.$row->units; echo "'/></td><td>$row->subject_code</td><td>$row->descriptive_title</td><td>$row->units</td>";
									}
								}
							}
							///copre only
							else if($row->copre != '' && $row->pre == ''&& $row->standing == '') {
								if(array_key_exists($row->pre, $na)){
									continue;
								}
								else{
									if(array_key_exists($row->pre, $fSub)){
										continue;
									}
									else{
										echo "<td><input type='checkbox' value='"; echo $row->subject_code.'.'.escape($row->descriptive_title).'.'.$row->units; echo "'/></td><td>$row->subject_code</td><td>$row->descriptive_title</td><td>$row->units</td>";
									}	
								}	
							}
							///pre and co pre
							else if($row->pre != '' && $row->copre != ''){
								if(array_key_exists($row->pre, $na) || array_key_exists($row->copre, $na)){
									continue;
								}
								else{
									if(!array_key_exists($row->pre, $fSub) && !array_key_exists($row->copre, $fSub)){
										echo "<td><input type='checkbox' value='"; echo $row->subject_code.'.'.escape($row->descriptive_title).'.'.$row->units; echo "'/></td><td>$row->subject_code</td><td>$row->descriptive_title</td><td>$row->units</td>";
									
									}
									else{
										continue;	
									}
								}
							}
							else{
								if($row->standing == getStanding($yrLvl)){
									echo "<td><input type='checkbox' value='"; echo $row->subject_code.'.'.escape($row->descriptive_title).'.'.$row->units; echo "'/></td><td>$row->subject_code</td><td>$row->descriptive_title</td><td>$row->units</td>";
								}
								else{
									continue;
								}
							}
							

						}


						///pumasa na
						else{
							if(array_key_exists($row->subject_code, $pSub)){
								continue;
							}
							else{
								echo "<td><input type='checkbox' value='"; echo $row->subject_code.'.'.escape($row->descriptive_title).'.'.$row->units; echo "'/></td><td>$row->subject_code</td><td>$row->descriptive_title</td><td>$row->units</td>";
							}
									
						}
					}
				}
				echo "</tr>";
			}
		echo "</table>";
		echo "</div>";

		$sub = $conn->query("SELECT grade.grade,grade.subject_code,subject.units,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing FROM student,grade,subject,bsit_subject WHERE bsit_subject.curriculum_id = '$curr' && grade.student_id =  '$studId' && grade.student_id = student.student_id && bsit_subject.semester_id= '$curSem' &&  grade.subject_code = subject.subject_code && bsit_subject.subject_code = grade.subject_code ");



		////current subjects /// removed faield and finish sub 
		echo "<div id='lineDivider'>";
		echo  "<hr class='divider'/>";
		echo "</div>";
		echo "<div id='sub'>";
		echo "<span>SUBJECTS</span><br/>";
		echo "<table id='myTable' class='table table-striped '>";
		// echo "<thead class='thead-inverse'>";
		echo "<tr style='background-color:#323232;color:#f6f6f6;'>";
		echo "<th>Code</th>";
		echo "<th>Title</th>";
		echo "<th  colspan='2'>Units</th>";
		echo "</tr>";
	

		while($row = $sub->fetch_object()){
			if(array_key_exists($row->subject_code,$curSub)){
				if($row->grade == 'N/A'){

						if($row->pre == '' && $row->copre == '' && $row->standing == ''){
						//requisites with value
								echo "<tr class='rows'>";
							echo "<td>$row->subject_code</td><td>$row->descriptive_title</td><td>$row->units</td><td><button id='shit' class='btn btn-info'><span class='glyphicon glyphicon-trash'></span></button></td>";
							echo "</tr>";
						}
						else if($row->pre != '' && $row->copre == ''&& $row->standing == ''){
							if(array_key_exists($row->pre, $na)){
								continue;
							}
							else{
								if(array_key_exists($row->pre, $fSub)){
									continue;
								}
								else{
								echo "<tr class='rows'>";
							echo "<td>$row->subject_code</td><td>$row->descriptive_title</td><td>$row->units</td><td><button id='shit' class='btn btn-info'><span class='glyphicon glyphicon-trash'></span></button></td>";
							echo "</tr>";	
								}
							}
						}
						///copre only
						else if($row->copre != '' && $row->pre == ''&& $row->standing == '') {
							if(array_key_exists($row->pre, $na)){
								continue;
							}
							else{
								if(array_key_exists($row->pre, $fSub)){
									continue;
								}
								else{
								echo "<tr class='rows'>";
							echo "<td>$row->subject_code</td><td>$row->descriptive_title</td><td>$row->units</td><td><button id='shit' class='btn btn-info'><span class='glyphicon glyphicon-trash'></span></button></td>";
							echo "</tr>";
								}	
							}	
						}
						///pre and co pre
						else if($row->pre != '' && $row->copre != ''){
							if(array_key_exists($row->pre, $na) || array_key_exists($row->copre, $na)){
								continue;
							}
							else{
								if(!array_key_exists($row->pre, $fSub) && !array_key_exists($row->copre, $fSub)){
								echo "<tr class='rows'>";
							echo "<td>$row->subject_code</td><td>$row->descriptive_title</td><td>$row->units</td><td><button id='shit' class='btn btn-info'><span class='glyphicon glyphicon-trash'></span></button></td>";
							echo "</tr>";								
								}
								else{
									continue;	
								}

							}
						}
						else{

							if($row->standing == getStanding($yrLvl)){
								if($yrLvl == 4){
									if(empty($fSub)){
										echo "<tr class='rows'>";
										echo "<td>$row->subject_code</td><td>$row->descriptive_title</td><td>$row->units</td><td><button id='shit' class='btn btn-info'><span class='glyphicon glyphicon-trash'></span></button></td>";
										echo "</tr>";	
									}
										
								}
								else{
									echo "<tr class='rows'>";
									echo "<td>$row->subject_code</td><td>$row->descriptive_title</td><td>$row->units</td><td><button id='shit' class='btn btn-info'><span class='glyphicon glyphicon-trash'></span></button></td>";
									echo "</tr>";
								}
							}
							else{
								// echo "string";
								continue;
							}
						}
						
						
					}	
					else{
						if($row->grade <75){
							if($row->pre == '' && $row->copre == '' && $row->standing == ''){
							//requisites with value
									echo "<tr class='rows'>";
							echo "<td>$row->subject_code</td><td>$row->descriptive_title</td><td>$row->units</td><td><button id='shit' class='btn btn-info'><span class='glyphicon glyphicon-trash'></span></button></td>";
							echo "</tr>";
							}
							////lahat my pre
							// else if($row->pre != '' && $row->copre == ''&& $row->standing == ''){

							// }
							////pre only
							else if($row->pre != '' && $row->copre == ''&& $row->standing == ''){
								if(array_key_exists($row->pre, $na)){
									continue;

								}
								else{
									if(array_key_exists($row->pre, $fSub)){
										continue;
									}
									else{
										echo "<tr class='rows'>";
							echo "<td>$row->subject_code</td><td>$row->descriptive_title</td><td>$row->units</td><td><button id='shit' class='btn btn-info'><span class='glyphicon glyphicon-trash'></span></button></td>";
							echo "</tr>";
									}
								}
							}
							///copre only
							else if($row->copre != '' && $row->pre == ''&& $row->standing == '') {
								if(array_key_exists($row->pre, $na)){
									continue;
								}
								else{
									if(array_key_exists($row->pre, $fSub)){
										continue;
									}
									else{
										echo "<tr class='rows'>";
							echo "<td>$row->subject_code</td><td>$row->descriptive_title</td><td>$row->units</td><td><button id='shit' class='btn btn-info'><span class='glyphicon glyphicon-trash'></span></button></td>";
							echo "</tr>";
									}	
								}	
							}
							///pre and co pre
							else if($row->pre != '' && $row->copre != ''){
								if(array_key_exists($row->pre, $na) || array_key_exists($row->copre, $na)){
									continue;
								}
								else{
									if(!array_key_exists($row->pre, $fSub) && !array_key_exists($row->copre, $fSub)){
										echo "<tr class='rows'>";
							echo "<td>$row->subject_code</td><td>$row->descriptive_title</td><td>$row->units</td><td><button id='shit' class='btn btn-info'><span class='glyphicon glyphicon-trash'></span></button></td>";
							echo "</tr>";
									
									}
									else{
										continue;	
									}

								}
							}
							else{
								if($row->standing == getStanding($yrLvl)){
								echo "<tr class='rows'>";
							echo "<td>$row->subject_code</td><td>$row->descriptive_title</td><td>$row->units</td><td><button id='shit' class='btn btn-info'><span class='glyphicon glyphicon-trash'></span></button></td>";
							echo "</tr>";
								}
								else{
									continue;
								}
							}
							

						}


						///pumasa na
						else{
							if(array_key_exists($row->subject_code, $pSub)){
								continue;
							}
							else{
								echo "<tr class='rows'>";
							echo "<td>$row->subject_code</td><td>$row->descriptive_title</td><td>$row->units</td><td><button id='shit' class='btn btn-info'><span class='glyphicon glyphicon-trash'></span></button></td>";
							echo "</tr>";

							}
									
						}
					}
					
			}
			else{
				continue;
			}
			
						
		}
		echo "</table>";
		echo "<span id='totUnits'>Total Units: </span></br>";
		
		echo "</div>";
		echo "<div id='btnHolder'>";

		echo "<button id='prev' class='btn btn-info'><span class='glyphicon glyphicon-print'></span>Print Preview</button>";
		echo "</div>";
	}
?>
</div>
<script>
function initLoad(){
	var myMsg = getId('cMsg');
	var myBtn = getId('btnC');
	var o = getId('o');
	var ne = getId('n');
	var g = getId('g');
	var h = getId('h');
	function palit(){
		if(o.value == '' || ne.value == ''){
			if(o.value == '' && ne.value == ''){
				g.innerHTML = '*';
				h.innerHTML = '*';
			}
			else if(o.value == '' && ne.value != ''){
				g.innerHTML = '*';
				h.innerHTML = '';
			}
			else if(ne.value == '' && o.value != ''){
				g.innerHTML = '';
				h.innerHTML = '*';	
			}
		}
		else{	
			var ajax = ajaxObj("POST",'admin/includes/response.php');
			ajax.onreadystatechange = function(){
				if(ajaxReturn(ajax)){
					myMsg.innerHTML = ajax.responseText;
				}
			}
			myMsg.innerHTML = 'wait....';
			ajax.send("oldP="+o.value+"&newP="+ne.value+"&user="+myBtn.value);
		}
		
	}
	myBtn.addEventListener('click',palit);
}
window.addEventListener('load',initLoad)
</script>
</body>
</html>
<?php
	}
};
?>
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