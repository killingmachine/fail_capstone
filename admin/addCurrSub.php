<?php
	session_start();
	require_once 'functions/functions.php';
	require 'class/db.php';

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title>Configure Curriculum</title>
	<link href="assets/style/bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
	<script src='assets/script/jquery.min.js'></script>
	<script src='assets/style/bootstrap/js/bootstrap.min.js'></script>
	<link rel="stylesheet" type="text/css" href="assets/style/addCurrSub.css"> 
	<script src='assets/script/frame.js' type='text/javascript'></script>
	<script src='assets/script/addCurrSub.js'></script>
</head>
<body>

	<?php
		include 'includes/header.php';
		$confirm = '';
		$currID = $_GET['currID'];
		$corsID = $_GET['corsID'];
		$_SESSION['Fid'] = $currID;
		$_SESSION['qwe'] = $corsID;
		if(isset($_POST['add'])){
			$curr = $_POST['currYr'];
			$subCode = $_POST['subCode'];
			$pre1 = $_POST['pre1'];
			$pre2 = $_POST['pre2'];
			$pre3 = $_POST['pre3'];
			$yrLvl = $_POST['yrLvl'];
			$sem = $_POST['sem'];
			$querCurSub = $conn->query("SELECT * FROM bsit_subject WHERE subject_code = '$subCode' && curriculum_id = $currID");
			
			$errMsg ="";
			if($subCode == '' || $curr == ''){
				$errMsg = "<span  style='color:#ff0033;' class='glyphicon glyphicon-remove'></span>pls select a subject code";
			}
			else if($querCurSub->num_rows > 0) {
				$errMsg = "<span style='color:#ff0033;' class='glyphicon glyphicon-remove'></span>Subject already exist on this curriculum";
			}
			else{
				$conn->query("INSERT INTO bsit_subject(subject_code,curriculum_id,pre,copre,yr_lvl,standing,semester_id)
					VALUES('$subCode','$currID','$pre1','$pre2','$yrLvl','$pre3','$sem')");
				$forAddingSub = $conn->query("SELECT student_id FROM student WHERE curriculum_id = '$currID'");
				while($myRows = $forAddingSub->fetch_object()){
					$conn->query("INSERT INTO grade(subject_code,student_id,grade,remarks)
					VALUES('$subCode','$myRows->student_id','N/A','N/A')");
				}
				
				$errMsg = "<span style='color:#66CD00;' class='glyphicon glyphicon-ok'></span>subject successfully added on the curriculum";
			}
		}
	?>
	<?php
		if(isset($_POST['updateCurrSub'])){
			$curDesId = $_SESSION['curDesId'];
			$pre1 = $_POST['pre1'];
			$pre2 = $_POST['pre2'];
			$pre3 = $_POST['pre3'];
			$yrLvl = $_POST['yrLvl'];
			$sem = $_POST['sem'];
			
			$conn->query("UPDATE bsit_subject SET pre = '$pre1', copre = '$pre2', standing='$pre3', yr_lvl='$yrLvl', semester_id = '$sem' WHERE bsit_subject.bsit_subject_id = '$curDesId'");
			if($conn->affected_rows > 0){
				$confirm = 'curriculum subject succesfully updated';
			}
			else{
				$confirm = 'no changes made';	
			}

		}
		$querCur = $conn->query("SELECT * FROM curriculum WHERE curriculum_id = $currID");
		while($rowCur=$querCur->fetch_object()){
	?>
	<div id='frmHolder'>
		<form id='zz' method="POST">
			<div style='display:none;'><span>Curriculum Id: </span><input name='bT' id='currId' type='number' disabled value='<?php echo $rowCur->curriculum_id; ?>'/></div>
			<div id='con1'>
			<div id='one'><span id='s'>Curriculum: </span><input name='currYr' id='currYr' type='text' value='<?php echo $rowCur->curriculum_yr;} ?>' readonly/></div>
			<div id='two'><span id='s1'>Subject Code: </span><input name='subCode' id='subCode'  type='text'  readonly/></div>
			</div>
			<div id='con2'>
			<div id='three'><span id='s2'>Subject Pre-requisite 1: </span><select class='pre1' name='pre1'>
					<option value=''>N/a</option>
					<?php
						$querPre1 = $conn->query("SELECT bsit_subject.subject_code,subject.descriptive_title FROM subject,bsit_subject WHERE subject.subject_code=bsit_subject.subject_code && bsit_subject.curriculum_id = '$currID'");
						while($rowOp1 = $querPre1->fetch_object()){
							echo "<option value='$rowOp1->subject_code'>$rowOp1->subject_code - $rowOp1->descriptive_title</option>";
						}
					?>
				</select></div>
				<div id='four'><span id='s3'>Subject Pre-requisite 2: </span><select class='pre1' name='pre2'>
					<option value=''>N/a</option>
					<?php
						$querPre2 = $conn->query("SELECT bsit_subject.subject_code,subject.descriptive_title FROM subject,bsit_subject WHERE subject.subject_code=bsit_subject.subject_code && bsit_subject.curriculum_id = '$currID'");
						while($rowOp2 = $querPre2->fetch_object()){
							echo "<option value='$rowOp2->subject_code'>$rowOp2->subject_code - $rowOp2->descriptive_title</option>";
						}
					?>
				</select></div>
			</div>
			<div id='con3'>
				<div id='five'><span id='x4'>Year Level: </span><select class='yLvl' name='yrLvl'>
					<option value='1' selected>1st year</option>
					<option value='2'>2nd year</option>
					<option value='3'>3rd year</option>
					<option value='4'>4th year</option>
				</select>
				<span>Semester: </span><select class='yLvl' name='sem'>
					<option value='1' selected>1st Semester</option>
					<option value='2'>2nd Semester</option>
					<option value='3'>Summer</option>
				</select>
				<span>Standing: </span><select class='yLvl' name='pre3'>
					<option value=''>N/a</option>
					<option value='sophomre standing'>Sophomore</option>
					<option value='junior standing'>Junior</option>
					<option value='senior standing'>Senior</option>
				</select></div>
			</div>
			<div id='con4'>
				<input type='submit' name='add' value='add to curriculum'/><br/>
				<span style='margin-left:120px;' id='errMsg'><?php if(isset($_POST['add'])){echo $errMsg;}  ?></span>
			</div>
		</form>
	</div>


<div id='availSub'>
<input placeholder='subject code' type='text' id='srchBox'/><button id='srchBtn'><span id='srch' class="glyphicon glyphicon-search"></span></button>
<button class='filBtn' id='it' value='it'>IT majors</button><button class='filBtn' id='cs' value='cs'>CS majors</button><button class='filBtn' id='min' value='min'>minors</button><button class='filBtn' value='all' id='all'>All</button>
<br/>
<div id='avail'>
<span id='head'>Available subjects</span>
<table id='sub_table' class = 'table'>
<?php
	$querSub = $conn->query("SELECT * FROM subject");
	echo "<thead>";
	echo "<tr>";
	echo "<th>Subject Code</th>";
	echo "<th>Descriptive Title</th>";
	echo "<th>Units</th>";
	echo "<th>Lec Hrs</th>";
	echo "<th>Lab Hrs</th>";
	echo "<th>Contact Hrs</th>";
	echo "</tr>";
	echo "</thead>";
	while($row=$querSub->fetch_object()){
		echo "<tr>";
		echo "<td><input id='selCode'  name='selCode' value='$row->subject_code' type='radio'/>$row->subject_code</td>";
		echo "<td>$row->descriptive_title</td>";
		echo "<td>$row->units</td>";
		echo "<td>$row->lec_hrs</td>";
		echo "<td>$row->lab_hrs</td>";
		echo "<td>$row->contact_hours</td>";
		echo "</tr>";

	}
?>
</table>
</div>
</div>


	

<div id="currSub">
<span id='confirm'><?php if(isset($_POST['updateCurrSub'])){ echo $confirm;} ?></span><br/>
<?php
$queCur = $conn->query("SELECT curriculum_yr FROM curriculum WHERE curriculum_id='$currID'");
	while($rowz=$queCur->fetch_object()){
		echo "$rowz->curriculum_yr Curriculum Subjects<br/>";
	}
?>	
<!-- cursub -->
<table id='1curSubT' class='shet table'>

	<thead >
		<th colspan=7 class='head'>First year</th>
		
	</thead>
	<thead>
		
		<th colspan=7 class='head'>First SEMESTER</th>
		
	</thead>
	<?php
		$cur = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$currID' && curriculum.course_id = '$corsID'");
		echo "<thead>";
		echo "<th style='width:10px;'></th>";
		echo "<th>Subject Code</th>";
		echo "<th>Descriptive title</th>";
		echo "<th>Requisite</th>";
		echo "<th>Co-Req</th>";
		echo "<th colspan=2>Standing</th>";
		echo "</thead>";
		if($cur->num_rows == 0){
			echo "<tr><td>No Subjects</td><td>No Subjects</td><td>No Subjects</td></tr>";
			echo "<script>";
			echo "var fYfSpS = [];";
			echo "var CfYfSpS = [];";
			echo "</script>";
		}
			
		else{
			echo "<tbody id='kainis'>";
			while($row = $cur->fetch_object()){
						echo "<tr>";
						echo "<td><input id='curDesc' name='edCurSub' value='$row->bsit_subject_id' type='radio'/></td><td>$row->subject_code</td> ";
						echo "<td>$row->descriptive_title</td> ";
						echo "<td>$row->pre</td>";
						echo "<td>$row->copre</td>";
						echo "<td>$row->standing</td>";
						echo "<td><button data-value='$row->bsit_subject_id' class='btnDel1' value='$row->subject_code'><span class='glyphicon glyphicon-trash'></span></button></td>";
						echo "</tr>";
						
					}
		echo "<script>";		
		echo "var kainis = getId('kainis');";
		echo "var fYfSpS = [];";
		echo "var CfYfSpS = [];";
		echo "for(var i = 0;i<kainis.rows.length;i++){";
		echo "if(kainis.rows[i].cells[3].innerHTML != ''){";
	 	echo "fYfSpS[i]=kainis.rows[i].cells[3].innerHTML;";  		
	 	echo "}";
	 	echo "if(kainis.rows[i].cells[4].innerHTML != ''){";
	 	echo "CfYfSpS[i]=kainis.rows[i].cells[4].innerHTML;";  		
	 	echo "}";
		echo "}";	
		echo "</script>";
		echo "</tbody>";
		}
		
	?>
		<thead>
		
		<th colspan=7 class='head'>Second SEMESTER</th>
		
	</thead>
	<?php
		$cur1 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$currID' && curriculum.course_id = '$corsID'");
		if($cur1->num_rows == 0){
			echo "<tr><td>No Subjects</td><td>No Subjects</td><td>No Subjects</td></tr>";
			echo "<script>";
			echo "var fYsSpS = [];";
			echo "var CfYsSpS = [];";
			echo "</script>";
		}
			
		else{
			echo "<tbody id='kainis1'>";
			while($row = $cur1->fetch_object()){
						echo "<tr>";
						echo "<td><input id='curDesc' name='edCurSub' value='$row->bsit_subject_id' type='radio'/></td><td>$row->subject_code</td> ";
						echo "<td>$row->descriptive_title</td> ";
						echo "<td>$row->pre</td>";
						echo "<td>$row->copre</td>";
						echo "<td>$row->standing</td>";
						// <a href='addCurrSub.php?currID=$currID&corsID=$corsID&del=$row->bsit_subject_id'>
						echo "<td><button data-value='$row->bsit_subject_id' value='$row->subject_code' class='btnDel1Sec'><span class='glyphicon glyphicon-trash'></span></button></td>";
						echo "</tr>";
						
					}
		echo "<script>";
		echo "var kainis1 = getId('kainis1');";
		echo "var fYsSpS = [];";
		echo "var CfYsSpS = [];";
		echo "for(var i = 0;i<kainis1.rows.length;i++){";
		echo "if(kainis.rows[i].cells[3].innerHTML != undefined){";
		echo		"fYsSpS[i] = kainis1.rows[i].cells[3].innerHTML;";	
		echo "}";
		echo "if(kainis.rows[i].cells[4].innerHTML != undefined){";
		echo		"CfYsSpS[i] = kainis1.rows[i].cells[4].innerHTML;";	
		echo "}";
		echo "}";	
		echo "</script>";		
		echo "</tbody>";
		}
		
	?>
	
	<thead>
		
		<th colspan=7 class='head'>Summer</th>
		
	</thead>
	<?php
		$cur10 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$currID' && curriculum.course_id = '$corsID'");
		if($cur10->num_rows == 0){
			echo "<tr><td>No Summer Subjects</td><td>No Summer Subjects</td><td>No Summer Subjects</td></tr>";
			echo "<script>";
			echo "var fYsumPs = [];";
			echo "var CfYsumPs = [];";
			echo "</script>";
		}	
		else{
		echo "<tbody id='kainis2'>";
			while($row = $cur10->fetch_object()){
						echo "<tr>";
						echo "<td><input id='curDesc' name='edCurSub' value='$row->bsit_subject_id' type='radio'/></td><td>$row->subject_code</td> ";
						echo "<td>$row->descriptive_title</td>";
						echo "<td>$row->pre</td>";
						echo "<td>$row->copre</td>";
						echo "<td>$row->standing</td>";
						echo "<td><button data-value='$row->bsit_subject_id' value='$row->subject_code' class='btnDel1Sum'><span class='glyphicon glyphicon-trash'></span></button></td>";
						echo "</tr>";
						
					}
		echo "<script>";
		echo "var kainis2 = getId('kainis2');";
		echo "var fYsumPs = [];";
		echo "var CfYsumPs = [];";
		echo "for(var i = 0;i<kainis2.rows.length;i++){";
		echo "if(kainis2.rows[i].cells[3].innerHTML != undefined){";
		echo "fYsumPs[i] = kainis2.rows[i].cells[3].innerHTML;";	
		echo "}";
		echo "if(kainis2.rows[i].cells[4].innerHTML != undefined){";
		echo "CfYsumPs[i] = kainis2.rows[i].cells[4].innerHTML;";	
		echo "}";
		echo "}";		
		echo "</script>";
		echo "</tbody>";
		}
			
	?>
	
	<thead>
		
		<th colspan=7 class='head'>Second year</th>
		
	</thead>
	<thead>
		
		<th colspan=7 class='head'>First SEMESTER</th>
		
	</thead>
	<?php
		$cur2 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1&& bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$currID' && curriculum.course_id = '$corsID'");
		if($cur2->num_rows == 0){
			echo "<tr><td>No Subjects</td><td>No Subjects</td><td>No Subjects</td></tr>";
			echo "<script>";
			echo "var sYfSPs = [];";
			echo "var CsYfSPs = [];";
			echo "</script>";
		}	
		else{
			echo "<tbody id='kainis3'>";
			while($row = $cur2->fetch_object()){
						echo "<tr>";
						echo "<td><input id='curDesc' name='edCurSub' value='$row->bsit_subject_id' type='radio'/></td><td>$row->subject_code</td> ";
						echo "<td>$row->descriptive_title</td>";
						echo "<td>$row->pre</td>";
						echo "<td>$row->copre</td>";
						echo "<td>$row->standing</td>";
						echo "<td><button data-value='$row->bsit_subject_id' value='$row->subject_code' class='btnDel2'><span class='glyphicon glyphicon-trash'></span></button></td>";
						echo "</tr>";

						// echo "<tr>";
						// echo "<td><input id='curDesc' name='edCurSub' value='$row->bsit_subject_id' type='radio'/></td><td>$row->subject_code</td> ";
						// echo "<td>$row->descriptive_title</td> ";
						// echo "<td>$row->pre</td>";
						// echo "<td>$row->copre</td>";
						// echo "<td>$row->standing</td>";
						// echo "<td><a href='addCurrSub.php?currID=$currID&corsID=$corsID&del=$row->bsit_subject_id'><button class='btnDel'><span class='glyphicon glyphicon-trash'></span></button></a></td>";
						// echo "</tr>";
						
					}
				echo "<script>";
				echo "var kainis3 = getId('kainis3');";
				echo "var sYfSPs = [];";
				echo "var CsYfSPs = [];";
				echo "for(var i = 0;i<kainis3.rows.length;i++){";
				echo "if(kainis3.rows[i].cells[3].innerHTML != undefined){";
				echo "sYfSPs[i] = kainis3.rows[i].cells[3].innerHTML;";	
				echo "}";
				echo "if(kainis3.rows[i].cells[4].innerHTML != undefined){";
				echo "CsYfSPs[i] = kainis3.rows[i].cells[4].innerHTML;";	
				echo "}";
				echo "}";		
				echo "</script>";
		
		echo "</tbody>";
		}
		
	?>
	<thead>
		
		<th colspan=7 class='head'>Second SEMESTER</th>
		
	</thead>
	<?php
		$cur3 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2&& bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$currID' && curriculum.course_id = '$corsID'");
		if($cur3->num_rows == 0){
			echo "<tr><td>No Subjects</td><td>No Subjects</td><td>No Subjects</td></tr>";
			echo "<script>";
			echo "var sYsSPs = [];";
			echo "var CsYsSPs = [];";
			echo "</script>";
		}	
		else{
			echo "<tbody id='kainis4'>";
			while($row = $cur3->fetch_object()){
						echo "<tr>";
						echo "<td><input id='curDesc' name='edCurSub' value='$row->bsit_subject_id' type='radio'/></td><td>$row->subject_code</td> ";
						echo "<td>$row->descriptive_title</td> ";
						echo "<td>$row->pre</td>";
						echo "<td>$row->copre</td>";
						echo "<td>$row->standing</td>";
						echo "<td><button data-value='$row->bsit_subject_id' value='$row->subject_code' class='btnDel2Sec'><span class='glyphicon glyphicon-trash'></span></button></td>";
						echo "</tr>";
						
					}
			echo "<script>";
			echo "var kainis4 = getId('kainis4');";
			echo "var sYsSPs = [];";
			echo "var CsYsSPs = [];";
			echo "for(var i = 0;i<kainis4.rows.length;i++){";
			echo "if(kainis4.rows[i].cells[3].innerHTML != undefined){";
			echo "sYsSPs[i] = kainis4.rows[i].cells[3].innerHTML;";	
			echo "}";
			echo "if(kainis4.rows[i].cells[4].innerHTML != undefined){";
			echo "CsYsSPs[i] = kainis4.rows[i].cells[4].innerHTML;";	
			echo "}";
			echo "}";		
			echo "</script>";
			echo "</tbody>";		
		}
	?>
	<thead>
		
		<th colspan=7 class='head'>Summer</th>
		
	</thead>
	<?php
		$cur30 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$currID' && curriculum.course_id = '$corsID'");
		if($cur30->num_rows == 0){
			echo "<tr><td>No Summer Subjects</td><td>No Summer Subjects</td><td>No Summer Subjects</td></tr>";
			echo "<script>";
			echo "var sYsumPs = [];";
			echo "var CsYsumPs = [];";
			echo "</script>";
		}	
		else{
			echo "<tbody id='kainis5'>";
			while($row = $cur30->fetch_object()){
						echo "<tr>";
						echo "<td><input id='curDesc' name='edCurSub' value='$row->bsit_subject_id' type='radio'/></td><td>$row->subject_code</td> ";
						echo "<td>$row->descriptive_title</td> ";
						echo "<td>$row->pre</td>";
						echo "<td>$row->copre</td>";
						echo "<td>$row->standing</td>";
						echo "<td><button data-value='$row->bsit_subject_id' value='$row->subject_code' class='btnDel2Sum'><span class='glyphicon glyphicon-trash'></span></button></a></td>";
						echo "</tr>";
						
					}
			echo "<script>";
			echo "var kainis5 = getId('kainis5');";
			echo "var sYsumPs = [];";
			echo "var CsYsumPs = [];";
			echo "for(var i = 0;i<kainis5.rows.length;i++){";
			echo "if(kainis5.rows[i].cells[3].innerHTML != undefined){";
			echo "sYsumPs[i] = kainis5.rows[i].cells[3].innerHTML;";	
			echo "}";
			echo "if(kainis5.rows[i].cells[4].innerHTML != undefined){";
			echo "CsYsumPs[i] = kainis5.rows[i].cells[4].innerHTML;";	
			echo "}";
			echo "}";		
			echo "</script>";
			echo "</tbody>";

		}	
	?>	
	<thead>
		
		<th colspan=7 class='head'>Third year</th>
		
	</thead>
	<thead>
		
		<th colspan=7 class='head'>First SEMESTER</th>
		
	</thead>
	<?php
		$cur4 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1&& bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$currID' && curriculum.course_id = '$corsID'");
		if($cur4->num_rows == 0){
			echo "<tr><td>No Subjects</td><td>No Subjects</td><td>No Subjects</td></tr>";
			echo "<script>";
			echo "var tYfSPs = [];";
			echo "var CtYfSPs = [];";
			echo "</script>";
		}	
		else{
			echo "<tbody id='kainis6'>";
			while($row = $cur4->fetch_object()){
						echo "<tr>";
						echo "<td><input id='curDesc' name='edCurSub' value='$row->bsit_subject_id' type='radio'/></td><td>$row->subject_code</td> ";
						echo "<td>$row->descriptive_title</td> ";
						echo "<td>$row->pre</td>";
						echo "<td>$row->copre</td>";
						echo "<td>$row->standing</td>";
						echo "<td><button data-value='$row->bsit_subject_id' value='$row->subject_code' class='btnDel3'><span class='glyphicon glyphicon-trash'></span></button></td>";
						echo "</tr>";
						
					}
			echo "<script>";
			echo "var kainis6 = getId('kainis6');";
			echo "var tYfSPs = [];";
			echo "var CtYfSPs = [];";
			echo "for(var i = 0;i<kainis6.rows.length;i++){";
			echo "if(kainis6.rows[i].cells[3].innerHTML != undefined){";
			echo "tYfSPs[i] = kainis6.rows[i].cells[3].innerHTML;";	
			echo "}";
			echo "if(kainis6.rows[i].cells[4].innerHTML != undefined){";
			echo "CtYfSPs[i] = kainis6.rows[i].cells[4].innerHTML;";	
			echo "}";
			echo "}";		
			echo "</script>";
			echo "</tbody>";
		}
	?>
	<thead>
		
		<th colspan=7 class='head'>Second SEMESTER</th>
		
	</thead>
	<?php
		$cur5 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2&& bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$currID' && curriculum.course_id = '$corsID'");
		if($cur5->num_rows == 0){
			echo "<tr><td>No Subjects</td><td>No Subjects</td><td>No Subjects</td></tr>";
			echo "<script>";
			echo "var tYsSPs = [];";
			echo "var CtYsSPs = [];";
			echo "</script>";
		}	
		else{
			echo "<tbody id='kainis7'>"; 
			while($row = $cur5->fetch_object()){
						echo "<tr>";
						echo "<td><input id='curDesc' name='edCurSub' value='$row->bsit_subject_id' type='radio'/></td><td>$row->subject_code</td> ";
						echo "<td>$row->descriptive_title</td> ";
						echo "<td>$row->pre</td>";
						echo "<td>$row->copre</td>";
						echo "<td>$row->standing</td>";
						echo "<td><button data-value='$row->bsit_subject_id' value='$row->subject_code' class='btnDel3Sec'><span class='glyphicon glyphicon-trash'></span></button></td>";
						echo "</tr>";	
					}
			echo "<script>";
			echo "var kainis7 = getId('kainis7');";
			echo "var tYsSPs = [];";
			echo "var CtYsSPs = [];";
			echo "for(var i = 0;i<kainis7.rows.length;i++){";
			echo "if(kainis7.rows[i].cells[3].innerHTML != undefined){";
			echo "tYsSPs[i] = kainis7.rows[i].cells[3].innerHTML;";	
			echo "}";
			echo "if(kainis7.rows[i].cells[4].innerHTML != undefined){";
			echo "CtYsSPs[i] = kainis7.rows[i].cells[4].innerHTML;";	
			echo "}";
			echo "}";		
			echo "</script>";
			echo "</tbody>";
		}
	?>
	<thead>
		
		<th colspan=7 class='head'>Summer</th>
		
	</thead>
	<?php
		$cur50 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$currID' && curriculum.course_id = '$corsID'");
		if($cur50->num_rows == 0){
			echo "<tr><td>No Summer Subjects</td><td>No Summer Subjects</td><td>No Summer Subjects</td></tr>";
			echo "<script>";
			echo "var tYsumPs = [];";
			echo "var CtYsumPs = [];";
			echo "</script>";
		}	
		else{
			echo "<tbody id='kainis8'>";
			while($row = $cur50->fetch_object()){
						echo "<tr>";
						echo "<td><input id='curDesc' name='edCurSub' value='$row->bsit_subject_id' type='radio'/></td><td>$row->subject_code</td> ";
						echo "<td>$row->descriptive_title</td> ";
						echo "<td>$row->pre</td>";
						echo "<td>$row->copre</td>";
						echo "<td>$row->standing</td>";
						echo "<td><button data-value='$row->bsit_subject_id' value='$row->subject_code' class='btnDel3Sum'><span class='glyphicon glyphicon-trash'></span></button></td>";
						echo "</tr>";			
			}
			echo "<script>";
			echo "var kainis8 = getId('kainis8');";
			echo "var tYsumPs = [];";
			echo "var CtYsumPs = [];";
			echo "for(var i = 0;i<kainis8.rows.length;i++){";
			echo "if(kainis8.rows[i].cells[3].innerHTML != undefined){";
			echo "tYsumPs[i] = kainis8.rows[i].cells[3].innerHTML;";	
			echo "}";
			echo "if(kainis8.rows[i].cells[4].innerHTML != undefined){";
			echo "CtYsumPs[i] = kainis8.rows[i].cells[4].innerHTML;";	
			echo "}";
			echo "}";		
			echo "</script>";
			echo "</tbody>";

		}	
	?>
	<thead>
		
		<th colspan=7 class='head'>Fourth year</th>
		
	</thead>
	<thead>
		
		<th colspan=7 class='head'>First SEMESTER</th>
		
	</thead>
	<?php
		$cur6 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1&& bsit_subject.yr_lvl = 4 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$currID' && curriculum.course_id = '$corsID'");
		if($cur6->num_rows == 0){
			echo "<tr><td>No Subjects</td><td>No Subjects</td><td>No Subjects</td></tr>";
			echo "<script>";
			echo "var fthYfSPs = [];";
			echo "var CfthYfSPs = [];";
			echo "</script>";
		}	
		else{
			echo "<tbody id='kainis9'>";
			while($row = $cur6->fetch_object()){
						echo "<tr>";
						echo "<td><input id='curDesc' name='edCurSub' value='$row->bsit_subject_id' type='radio'/></td><td>$row->subject_code</td> ";
						echo "<td>$row->descriptive_title</td> ";
						echo "<td>$row->pre</td>";
						echo "<td>$row->copre</td>";
						echo "<td>$row->standing</td>";
						echo "<td><button data-value='$row->bsit_subject_id' value='$row->subject_code' class='btnDel4'><span class='glyphicon glyphicon-trash'></span></button></td>";
						echo "</tr>";		
			}
			echo "<script>";
			echo "var kainis9 = getId('kainis9');";
			echo "var fthYfSPs = [];";
			echo "var CfthYfSPs = [];";
			echo "for(var i = 0;i<kainis9.rows.length;i++){";
			echo "if(kainis9.rows[i].cells[3].innerHTML != undefined){";
			echo "fthYfSPs[i] = kainis9.rows[i].cells[3].innerHTML;";	
			echo "}";
			echo "if(kainis9.rows[i].cells[4].innerHTML != undefined){";
			echo "CfthYfSPs[i] = kainis9.rows[i].cells[4].innerHTML;";	
			echo "}";
			echo "}";		
			echo "</script>";
			echo "</tbody>";
		}
	?>
	<thead>
		
		<th colspan=7 class='head'>Second SEMESTER</th>
		
	</thead>
		<?php
		$cur7 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2&& bsit_subject.yr_lvl = 4 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$currID' && curriculum.course_id = '$corsID'");
		if($cur7->num_rows == 0){
			echo "<tr><td>No Subjects</td><td>No Subjects</td><td>No Subjects</td></tr>";
			echo "<script>";
			echo "var fthYsSPs = [];";
			echo "var CfthYsSPs = [];";
			echo "</script>";
		}	
		else{
			echo "<tbody id='kainis0'>";
			while($row = $cur7->fetch_object()){
						echo "<tr>";
						echo "<td><input id='curDesc' name='edCurSub' value='$row->bsit_subject_id' type='radio'/></td><td>$row->subject_code</td> ";
						echo "<td>$row->descriptive_title</td> ";
						echo "<td>$row->pre</td>";
						echo "<td>$row->copre</td>";
						echo "<td>$row->standing</td>";
						echo "<td><button data-value='$row->bsit_subject_id' value='$row->subject_code' class='btnDel4Sec'><span class='glyphicon glyphicon-trash'></span></button></td>";
						echo "</tr>";	
					}
			echo "<script>";
			echo "var kainis0 = getId('kainis0');";
			echo "var fthYsSPs = [];";
			echo "var CfthYsSPs = [];";
			echo "for(var i = 0;i<kainis0.rows.length;i++){";
			echo "if(kainis0.rows[i].cells[3].innerHTML != undefined){";
			echo "fthYsSPs[i] = kainis0.rows[i].cells[3].innerHTML;";	
			echo "}";
			echo "if(kainis0.rows[i].cells[4].innerHTML != undefined){";
			echo "CfthYsSPs[i] = kainis0.rows[i].cells[4].innerHTML;";	
			echo "}";
			echo "}";		
			echo "</script>";
			echo "</tbody>";
		}
	?>

</table>
	<!-- $currID = $_GET['currID'];
	$corsID = $_GET['corsID']; -->
	<a  id = 'ayong' href='checklist.php?curID=<?php echo $currID;echo '&cors='.$corsID; ?>' target='_blank'><button class='botBtn'>PREVIEW</button></a>
<!-- <?php
	// if(isset($_GET['del'])){
	// 		$del = $_GET['del'];
	// 		$forDelSub = $conn->query("SELECT student_id FROM student WHERE curriculum_id = '$currID'");
	// 		$d = $conn->query("SELECT subject_code FROM bsit_subject WHERE bsit_subject_id = '$del'");
	// 		while($myDelRows = $forDelSub->fetch_object()){
	// 			while ($dG = $d->fetch_object()) {
	// 				$conn->query("DELETE FROM grade WHERE student_id='$myDelRows->student_id' && subject_code='$dG->subject_code'");
	// 			}
	// 		}
	// 		$conn->query("DELETE FROM bsit_subject WHERE bsit_subject_id='$del'");
	// 		echo "<meta http-equiv='refresh' content='0;url=addCurrSub.php?currID=".$currID."&corsID=".$corsID."'>";	
	// 		$confirm = 'subject succesfully removed';
	// 	}
		
?> -->

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