<?php
/////PATRICK CHUA :)
session_start();
	require '../class/db.php';
	require '../functions/functions.php';
	require '../class/class_curriculum.php';
	require '../class/class_subjects.php';
	require '../class/class_user.php';
?>
<?php
	///sem
	// if(isset($_POST['semester'])){
	// 	$_SESSION['currentSem'] = $_POST['semester'];
	// 	echo "OK";

	// }

	///curriculum
	
	if(isset($_POST['currName']) && isset($_POST['cors'])){

		$curName=escape($_POST['currName']);
		$cors = escape($_POST['cors']);
		$curriculum = new Curriculum($cors,$curName);
		$insert_curriculum = new InsertCurriculum();

		
		if($curName == ''){
			echo "Pls Name your Curriculum";
		}
		else{
			echo $insert_curriculum->insert_curriculum($curriculum);
		}

	}
	///edit curriculum
	if(isset($_POST['selectedCurr']) && isset($_POST['fudep'])){
		$cur = $_POST['selectedCurr'];
		$_SESSION['fudep'] = $_POST['fudep'];
		$_SESSION['hello'] = $cur;
		$curr_name = new View_configure_curr();
		$selected_cur = $curr_name->single_query_cur($cur);
		echo "<center><form style='border-radius: 10px;padding:10px;width:450px;background-color:white;' method = 'POST'>";
		while($row=$selected_cur->fetch_object()){
			echo "<span>Curriculum Name: </span><input value='$row->curriculum_yr' id='currName' type='txt' name='currName'/><span id='warn'></span><br/>";
		}
		echo "<input style='margin-top:20px;width:120px;' type='submit' name='updateCur' value='UPDATE'>";
		echo "<input style='margin-top:20px;width:120px;' type='submit' name='cancel' value='CANCEL'><br/>";
		// echo "<a href='configure-subject.php'><span class='glyphicon glyphicon-circle-arrow-left'></span></a>";
		echo "</form></center>";
	}
	////curricukum subjects
	if(isset($_POST['subCode']) && isset($_POST['currId'])){
		$subCde = $_POST['subCode'];
		$currId = $_POST['currId'];
		$querCurSub = $conn->query("SELECT * FROM bsit_subject WHERE subject_code = '$subCde' && curriculum_id = $currId");
		if($querCurSub->num_rows > 0){
			echo "<span style='color:#ff0033;' class='glyphicon glyphicon-remove'></span>subject already exist on this curriculum";
		}
		else{
			echo "<span style='color:#66CD00;' class='glyphicon glyphicon-ok'></span>";
		}
	}
	///search cur sub
	if(isset($_POST['srchCurSub'])){
		$srchVal = escape($_POST['srchCurSub']);
		$curSubSrch = "SELECT * FROM subject WHERE subject_code LIKE '%".$srchVal."%'";
		$hanap = $conn->query($curSubSrch);
		if($hanap->num_rows == 0){
			echo 'No Subject listed on our Database';
		}
		else{
			echo "<span id='head'>Available subjects</span>";
			echo "<table id='sub_table' class = 'table'>";
			echo "<thead>";
			echo "<tr>";
			echo "<th>Subjec Code</th>";
			echo "<th>Descriptive Title</th>";
			echo "<th>Units</th>";
			echo "<th>Lec Hrs</th>";
			echo "<th>Lab Hrs</th>";
			echo "<th>Contact Hrs</th>";
			echo "</tr>";
			echo "</thead>";
			while($row=$hanap->fetch_object()){
				echo "<tr>";
				echo "<td><input id='selCode'  name='selCode' value='$row->subject_code' type='radio'/>$row->subject_code</td>";
				echo "<td>$row->descriptive_title</td>";
				echo "<td>$row->units</td>";
				echo "<td>$row->lec_hrs</td>";
				echo "<td>$row->lab_hrs</td>";
				echo "<td>$row->contact_hours</td>";
				echo "</tr>";

			}
			echo "</table>";
		}

		
	}
	///delete curriculum subjects
	if(isset($_POST['delFth'])){
		$subId = $_POST['delFth'];
		$delID = $_SESSION['Fid'];
		$qwe = $_SESSION['qwe'];
		$forDelSub = $conn->query("SELECT student_id FROM student WHERE curriculum_id = '$delID'");
		$d = $conn->query("SELECT subject_code FROM bsit_subject WHERE bsit_subject_id = '$subId'");
		while($myDelRows = $forDelSub->fetch_object()){
			while ($dG = $d->fetch_object()) {
				$conn->query("DELETE FROM grade WHERE student_id='$myDelRows->student_id' && subject_code='$dG->subject_code'");
			}
		}
		$conn->query("DELETE FROM bsit_subject WHERE bsit_subject_id='$subId'");
		echo "<meta http-equiv='refresh' content='0;url=addCurrSub.php?currID=".$delID."&corsID=".$qwe."'>";

		// $confirm = 'subject succesfully removed';
		echo "<span id='confirm'>";if(isset($_POST['updateCurrSub'])){ echo $confirm;}echo "</span><br/>";
		$queCur = $conn->query("SELECT curriculum_yr FROM curriculum WHERE curriculum_id='$delID'");
		while($rowz=$queCur->fetch_object()){
			echo "$rowz->curriculum_yr Curriculum Subjects<br/>";
		}
		echo "<table id='1curSubT' class='table'>";
		echo "<thead>";
		echo "<th colspan=7 class='head'>First year</th>";
		echo "</thead>";
		echo "<thead>";
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		echo "</thead>";
				$cur = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
		echo "<thead>";
		echo "<th style='width:10px;'></th>";
		echo "<th>Subject Code</th>";
		echo "<th>Descriptive title</th>";
		echo "<th>Requisite</th>";
		echo "<th>Co-Req</th>";
		echo "<th>Standing</th>";
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";

		echo "</thead>";
				$cur1 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Summer</th>";
		echo "</thead>";
		$cur10 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Second year</th>";
		
		echo "</thead>";
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		
		echo "</thead>";
		$cur2 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1&& bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";
		echo "</thead>";
		$cur3 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2&& bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Summer</th>";
		echo "</thead>";
		$cur30 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Third year</th>";
		
		echo "</thead>";
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		
		echo "</thead>";
		$cur4 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1&& bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";
		
		echo "</thead>";
		$cur5 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2&& bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Summer</th>";
		
		echo "</thead>";
		$cur50 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Fourth year</th>";
		
		echo "</thead>";
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		
		echo "</thead>";
		$cur6 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1&& bsit_subject.yr_lvl = 4 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";
		
		echo "</thead>";
		$cur7 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2&& bsit_subject.yr_lvl = 4 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "</table>";
		echo "<a  id = 'ayong' href='checklist.php?curID=$delID&cors=$qwe' target='_blank'><button class='botBtn'>PREVIEW</button></a>";
		echo "</div>";

	}
	/////del 1
	if(isset($_POST['delF'])){
		$subId = $_POST['delF'];
		$delID = $_SESSION['Fid'];
		$qwe = $_SESSION['qwe'];
		
		$forDelSub = $conn->query("SELECT student_id FROM student WHERE curriculum_id = '$delID'");
		$d = $conn->query("SELECT subject_code FROM bsit_subject WHERE bsit_subject_id = '$subId'");
		while($myDelRows = $forDelSub->fetch_object()){
			while ($dG = $d->fetch_object()) {
				$conn->query("DELETE FROM grade WHERE student_id='$myDelRows->student_id' && subject_code='$dG->subject_code'");
			}
		}
		$conn->query("DELETE FROM bsit_subject WHERE bsit_subject_id='$subId'");
		echo "<meta http-equiv='refresh' content='0;url=addCurrSub.php?currID=".$delID."&corsID=".$qwe."'>";
		// $confirm = 'subject succesfully removed';
		echo "<span id='confirm'>";if(isset($_POST['updateCurrSub'])){ echo $confirm;}echo "</span><br/>";
		$queCur = $conn->query("SELECT curriculum_yr FROM curriculum WHERE curriculum_id='$delID'");
		while($rowz=$queCur->fetch_object()){
			echo "$rowz->curriculum_yr Curriculum Subjects<br/>";
		}
		echo "<table id='1curSubT' class='table'>";
		echo "<thead>";
		echo "<th colspan=7 class='head'>First year</th>";
		echo "</thead>";
		echo "<thead>";
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		echo "</thead>";
				$cur = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
		echo "<thead>";
		echo "<th style='width:10px;'></th>";
		echo "<th>Subject Code</th>";
		echo "<th>Descriptive title</th>";
		echo "<th>Requisite</th>";
		echo "<th>Co-Req</th>";
		echo "<th>Standing</th>";
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";

		echo "</thead>";
				$cur1 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Summer</th>";
		echo "</thead>";
		$cur10 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Second year</th>";
		
		echo "</thead>";
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		
		echo "</thead>";
		$cur2 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1&& bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";
		echo "</thead>";
		$cur3 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2&& bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Summer</th>";
		echo "</thead>";
		$cur30 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Third year</th>";
		
		echo "</thead>";
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		
		echo "</thead>";
		$cur4 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1&& bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";
		
		echo "</thead>";
		$cur5 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2&& bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Summer</th>";
		
		echo "</thead>";
		$cur50 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Fourth year</th>";
		
		echo "</thead>";
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		
		echo "</thead>";
		$cur6 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1&& bsit_subject.yr_lvl = 4 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";
		
		echo "</thead>";
		$cur7 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2&& bsit_subject.yr_lvl = 4 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "</table>";
		echo "<a  id = 'ayong' href='checklist.php?curID=$delID&cors=$qwe' target='_blank'><button class='botBtn'>PREVIEW</button></a>";
		echo "</div>";

	}
	/////del1secSem
		if(isset($_POST['delFsec'])){
		$subId = $_POST['delFsec'];
		$delID = $_SESSION['Fid'];
		$qwe = $_SESSION['qwe'];
		$forDelSub = $conn->query("SELECT student_id FROM student WHERE curriculum_id = '$delID'");
		$d = $conn->query("SELECT subject_code FROM bsit_subject WHERE bsit_subject_id = '$subId'");
		while($myDelRows = $forDelSub->fetch_object()){
			while ($dG = $d->fetch_object()) {
				$conn->query("DELETE FROM grade WHERE student_id='$myDelRows->student_id' && subject_code='$dG->subject_code'");
			}
		}
		$conn->query("DELETE FROM bsit_subject WHERE bsit_subject_id='$subId'");
		echo "<meta http-equiv='refresh' content='0;url=addCurrSub.php?currID=".$delID."&corsID=".$qwe."'>";	
		// $confirm = 'subject succesfully removed';
		echo "<span id='confirm'>";if(isset($_POST['updateCurrSub'])){ echo $confirm;}echo "</span><br/>";
		$queCur = $conn->query("SELECT curriculum_yr FROM curriculum WHERE curriculum_id='$delID'");
		while($rowz=$queCur->fetch_object()){
			echo "$rowz->curriculum_yr Curriculum Subjects<br/>";
		}
		echo "<table id='1curSubT' class='table'>";
		echo "<thead>";
		echo "<th colspan=7 class='head'>First year</th>";
		echo "</thead>";
		echo "<thead>";
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		echo "</thead>";
				$cur = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
		echo "<thead>";
		echo "<th style='width:10px;'></th>";
		echo "<th>Subject Code</th>";
		echo "<th>Descriptive title</th>";
		echo "<th>Requisite</th>";
		echo "<th>Co-Req</th>";
		echo "<th>Standing</th>";
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";

		echo "</thead>";
				$cur1 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Summer</th>";
		echo "</thead>";
		$cur10 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Second year</th>";
		
		echo "</thead>";
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		
		echo "</thead>";
		$cur2 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1&& bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";
		echo "</thead>";
		$cur3 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2&& bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Summer</th>";
		echo "</thead>";
		$cur30 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Third year</th>";
		
		echo "</thead>";
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		
		echo "</thead>";
		$cur4 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1&& bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";
		
		echo "</thead>";
		$cur5 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2&& bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Summer</th>";
		
		echo "</thead>";
		$cur50 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Fourth year</th>";
		
		echo "</thead>";
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		
		echo "</thead>";
		$cur6 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1&& bsit_subject.yr_lvl = 4 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";
		
		echo "</thead>";
		$cur7 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2&& bsit_subject.yr_lvl = 4 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "</table>";
		echo "<a  id = 'ayong' href='checklist.php?curID=$delID&cors=$qwe' target='_blank'><button class='botBtn'>PREVIEW</button></a>";
		echo "</div>";

	}
	///del1sum
	if(isset($_POST['delFsum'])){
		$subId = $_POST['delFsum'];
		$delID = $_SESSION['Fid'];
		$qwe = $_SESSION['qwe'];
		$forDelSub = $conn->query("SELECT student_id FROM student WHERE curriculum_id = '$delID'");
		$d = $conn->query("SELECT subject_code FROM bsit_subject WHERE bsit_subject_id = '$subId'");
		while($myDelRows = $forDelSub->fetch_object()){
			while ($dG = $d->fetch_object()) {
				$conn->query("DELETE FROM grade WHERE student_id='$myDelRows->student_id' && subject_code='$dG->subject_code'");
			}
		}
		$conn->query("DELETE FROM bsit_subject WHERE bsit_subject_id='$subId'");
		echo "<meta http-equiv='refresh' content='0;url=addCurrSub.php?currID=".$delID."&corsID=".$qwe."'>";	
		//$confirm = 'subject succesfully removed';
		echo "<span id='confirm'>";if(isset($_POST['updateCurrSub'])){ echo $confirm;}echo "</span><br/>";
		$queCur = $conn->query("SELECT curriculum_yr FROM curriculum WHERE curriculum_id='$delID'");
		while($rowz=$queCur->fetch_object()){
			echo "$rowz->curriculum_yr Curriculum Subjects<br/>";
		}
		echo "<table id='1curSubT' class='table'>";
		echo "<thead>";
		echo "<th colspan=7 class='head'>First year</th>";
		echo "</thead>";
		echo "<thead>";
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		echo "</thead>";
				$cur = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
		echo "<thead>";
		echo "<th style='width:10px;'></th>";
		echo "<th>Subject Code</th>";
		echo "<th>Descriptive title</th>";
		echo "<th>Requisite</th>";
		echo "<th>Co-Req</th>";
		echo "<th>Standing</th>";
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";

		echo "</thead>";
				$cur1 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Summer</th>";
		echo "</thead>";
		$cur10 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Second year</th>";
		
		echo "</thead>";
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		
		echo "</thead>";
		$cur2 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1&& bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";
		echo "</thead>";
		$cur3 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2&& bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Summer</th>";
		echo "</thead>";
		$cur30 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Third year</th>";
		
		echo "</thead>";
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		
		echo "</thead>";
		$cur4 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1&& bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";
		
		echo "</thead>";
		$cur5 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2&& bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Summer</th>";
		
		echo "</thead>";
		$cur50 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Fourth year</th>";
		
		echo "</thead>";
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		
		echo "</thead>";
		$cur6 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1&& bsit_subject.yr_lvl = 4 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";
		
		echo "</thead>";
		$cur7 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2&& bsit_subject.yr_lvl = 4 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "</table>";
		echo "<a  id = 'ayong' href='checklist.php?curID=$delID&cors=$qwe' target='_blank'><button class='botBtn'>PREVIEW</button></a>";
		echo "</div>";

	}
	///del2f
	if(isset($_POST['delS'])){
		$subId = $_POST['delS'];
		$delID = $_SESSION['Fid'];
		$qwe = $_SESSION['qwe'];
		$forDelSub = $conn->query("SELECT student_id FROM student WHERE curriculum_id = '$delID'");
		$d = $conn->query("SELECT subject_code FROM bsit_subject WHERE bsit_subject_id = '$subId'");
		while($myDelRows = $forDelSub->fetch_object()){
			while ($dG = $d->fetch_object()) {
				$conn->query("DELETE FROM grade WHERE student_id='$myDelRows->student_id' && subject_code='$dG->subject_code'");
			}
		}
		$conn->query("DELETE FROM bsit_subject WHERE bsit_subject_id='$subId'");
		echo "<meta http-equiv='refresh' content='0;url=addCurrSub.php?currID=".$delID."&corsID=".$qwe."'>";	
		// $confirm = 'subject succesfully removed';
		echo "<span id='confirm'>";if(isset($_POST['updateCurrSub'])){ echo $confirm;}echo "</span><br/>";
		$queCur = $conn->query("SELECT curriculum_yr FROM curriculum WHERE curriculum_id='$delID'");
		while($rowz=$queCur->fetch_object()){
			echo "$rowz->curriculum_yr Curriculum Subjects<br/>";
		}
		echo "<table id='1curSubT' class='table'>";
		echo "<thead>";
		echo "<th colspan=7 class='head'>First year</th>";
		echo "</thead>";
		echo "<thead>";
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		echo "</thead>";
				$cur = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
		echo "<thead>";
		echo "<th style='width:10px;'></th>";
		echo "<th>Subject Code</th>";
		echo "<th>Descriptive title</th>";
		echo "<th>Requisite</th>";
		echo "<th>Co-Req</th>";
		echo "<th>Standing</th>";
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";

		echo "</thead>";
				$cur1 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Summer</th>";
		echo "</thead>";
		$cur10 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Second year</th>";
		
		echo "</thead>";
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		
		echo "</thead>";
		$cur2 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1&& bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";
		echo "</thead>";
		$cur3 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2&& bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Summer</th>";
		echo "</thead>";
		$cur30 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Third year</th>";
		
		echo "</thead>";
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		
		echo "</thead>";
		$cur4 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1&& bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";
		
		echo "</thead>";
		$cur5 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2&& bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Summer</th>";
		
		echo "</thead>";
		$cur50 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Fourth year</th>";
		
		echo "</thead>";
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		
		echo "</thead>";
		$cur6 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1&& bsit_subject.yr_lvl = 4 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";
		
		echo "</thead>";
		$cur7 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2&& bsit_subject.yr_lvl = 4 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "</table>";
		echo "<a  id = 'ayong' href='checklist.php?curID=$delID&cors=$qwe' target='_blank'><button class='botBtn'>PREVIEW</button></a>";
		echo "</div>";

	}
	////delSs
	if(isset($_POST['delSs'])){
		$subId = $_POST['delSs'];
		$delID = $_SESSION['Fid'];
		$qwe = $_SESSION['qwe'];
		$forDelSub = $conn->query("SELECT student_id FROM student WHERE curriculum_id = '$delID'");
		$d = $conn->query("SELECT subject_code FROM bsit_subject WHERE bsit_subject_id = '$subId'");
		while($myDelRows = $forDelSub->fetch_object()){
			while ($dG = $d->fetch_object()) {
				$conn->query("DELETE FROM grade WHERE student_id='$myDelRows->student_id' && subject_code='$dG->subject_code'");
			}
		}
		$conn->query("DELETE FROM bsit_subject WHERE bsit_subject_id='$subId'");
		echo "<meta http-equiv='refresh' content='0;url=addCurrSub.php?currID=".$delID."&corsID=".$qwe."'>";	
		//$confirm = 'subject succesfully removed';
		echo "<span id='confirm'>";if(isset($_POST['updateCurrSub'])){ echo $confirm;}echo "</span><br/>";
		$queCur = $conn->query("SELECT curriculum_yr FROM curriculum WHERE curriculum_id='$delID'");
		while($rowz=$queCur->fetch_object()){
			echo "$rowz->curriculum_yr Curriculum Subjects<br/>";
		}
		echo "<table id='1curSubT' class='table'>";
		echo "<thead>";
		echo "<th colspan=7 class='head'>First year</th>";
		echo "</thead>";
		echo "<thead>";
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		echo "</thead>";
				$cur = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
		echo "<thead>";
		echo "<th style='width:10px;'></th>";
		echo "<th>Subject Code</th>";
		echo "<th>Descriptive title</th>";
		echo "<th>Requisite</th>";
		echo "<th>Co-Req</th>";
		echo "<th>Standing</th>";
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";

		echo "</thead>";
				$cur1 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Summer</th>";
		echo "</thead>";
		$cur10 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Second year</th>";
		
		echo "</thead>";
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		
		echo "</thead>";
		$cur2 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1&& bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";
		echo "</thead>";
		$cur3 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2&& bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Summer</th>";
		echo "</thead>";
		$cur30 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Third year</th>";
		
		echo "</thead>";
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		
		echo "</thead>";
		$cur4 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1&& bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";
		
		echo "</thead>";
		$cur5 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2&& bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Summer</th>";
		
		echo "</thead>";
		$cur50 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Fourth year</th>";
		
		echo "</thead>";
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		
		echo "</thead>";
		$cur6 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1&& bsit_subject.yr_lvl = 4 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";
		
		echo "</thead>";
		$cur7 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2&& bsit_subject.yr_lvl = 4 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "</table>";
		echo "<a  id = 'ayong' href='checklist.php?curID=$delID&cors=$qwe' target='_blank'><button class='botBtn'>PREVIEW</button></a>";
		echo "</div>";

	}
	///delSsum
	if(isset($_POST['delSsum'])){
		$subId = $_POST['delSsum'];
		$delID = $_SESSION['Fid'];
		$qwe = $_SESSION['qwe'];
		$forDelSub = $conn->query("SELECT student_id FROM student WHERE curriculum_id = '$delID'");
		$d = $conn->query("SELECT subject_code FROM bsit_subject WHERE bsit_subject_id = '$subId'");
		while($myDelRows = $forDelSub->fetch_object()){
			while ($dG = $d->fetch_object()) {
				$conn->query("DELETE FROM grade WHERE student_id='$myDelRows->student_id' && subject_code='$dG->subject_code'");
			}
		}
		$conn->query("DELETE FROM bsit_subject WHERE bsit_subject_id='$subId'");	
		echo "<meta http-equiv='refresh' content='0;url=addCurrSub.php?currID=".$delID."&corsID=".$qwe."'>";
		//$confirm = 'subject succesfully removed';
		echo "<span id='confirm'>";if(isset($_POST['updateCurrSub'])){ echo $confirm;}echo "</span><br/>";
		$queCur = $conn->query("SELECT curriculum_yr FROM curriculum WHERE curriculum_id='$delID'");
		while($rowz=$queCur->fetch_object()){
			echo "$rowz->curriculum_yr Curriculum Subjects<br/>";
		}
		echo "<table id='1curSubT' class='table'>";
		echo "<thead>";
		echo "<th colspan=7 class='head'>First year</th>";
		echo "</thead>";
		echo "<thead>";
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		echo "</thead>";
				$cur = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
		echo "<thead>";
		echo "<th style='width:10px;'></th>";
		echo "<th>Subject Code</th>";
		echo "<th>Descriptive title</th>";
		echo "<th>Requisite</th>";
		echo "<th>Co-Req</th>";
		echo "<th>Standing</th>";
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";

		echo "</thead>";
				$cur1 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Summer</th>";
		echo "</thead>";
		$cur10 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Second year</th>";
		
		echo "</thead>";
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		
		echo "</thead>";
		$cur2 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1&& bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";
		echo "</thead>";
		$cur3 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2&& bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Summer</th>";
		echo "</thead>";
		$cur30 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Third year</th>";
		
		echo "</thead>";
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		
		echo "</thead>";
		$cur4 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1&& bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";
		
		echo "</thead>";
		$cur5 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2&& bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Summer</th>";
		
		echo "</thead>";
		$cur50 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Fourth year</th>";
		
		echo "</thead>";
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		
		echo "</thead>";
		$cur6 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1&& bsit_subject.yr_lvl = 4 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";
		
		echo "</thead>";
		$cur7 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2&& bsit_subject.yr_lvl = 4 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "</table>";
		echo "<a  id = 'ayong' href='checklist.php?curID=$delID&cors=$qwe' target='_blank'><button class='botBtn'>PREVIEW</button></a>";
		echo "</div>";

	}
	///delT
	if(isset($_POST['delT'])){
		$subId = $_POST['delT'];
		$delID = $_SESSION['Fid'];
		$qwe = $_SESSION['qwe'];
		$forDelSub = $conn->query("SELECT student_id FROM student WHERE curriculum_id = '$delID'");
		$d = $conn->query("SELECT subject_code FROM bsit_subject WHERE bsit_subject_id = '$subId'");
		while($myDelRows = $forDelSub->fetch_object()){
			while ($dG = $d->fetch_object()) {
				$conn->query("DELETE FROM grade WHERE student_id='$myDelRows->student_id' && subject_code='$dG->subject_code'");
			}
		}
		$conn->query("DELETE FROM bsit_subject WHERE bsit_subject_id='$subId'");	
		echo "<meta http-equiv='refresh' content='0;url=addCurrSub.php?currID=".$delID."&corsID=".$qwe."'>";
		// $confirm = 'subject succesfully removed';
		echo "<span id='confirm'>";if(isset($_POST['updateCurrSub'])){ echo $confirm;}echo "</span><br/>";
		$queCur = $conn->query("SELECT curriculum_yr FROM curriculum WHERE curriculum_id='$delID'");
		while($rowz=$queCur->fetch_object()){
			echo "$rowz->curriculum_yr Curriculum Subjects<br/>";
		}
		echo "<table id='1curSubT' class='table'>";
		echo "<thead>";
		echo "<th colspan=7 class='head'>First year</th>";
		echo "</thead>";
		echo "<thead>";
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		echo "</thead>";
				$cur = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
		echo "<thead>";
		echo "<th style='width:10px;'></th>";
		echo "<th>Subject Code</th>";
		echo "<th>Descriptive title</th>";
		echo "<th>Requisite</th>";
		echo "<th>Co-Req</th>";
		echo "<th>Standing</th>";
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";

		echo "</thead>";
				$cur1 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Summer</th>";
		echo "</thead>";
		$cur10 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Second year</th>";
		
		echo "</thead>";
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		
		echo "</thead>";
		$cur2 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1&& bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";
		echo "</thead>";
		$cur3 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2&& bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Summer</th>";
		echo "</thead>";
		$cur30 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Third year</th>";
		
		echo "</thead>";
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		
		echo "</thead>";
		$cur4 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1&& bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";
		
		echo "</thead>";
		$cur5 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2&& bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Summer</th>";
		
		echo "</thead>";
		$cur50 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Fourth year</th>";
		
		echo "</thead>";
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		
		echo "</thead>";
		$cur6 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1&& bsit_subject.yr_lvl = 4 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";
		
		echo "</thead>";
		$cur7 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2&& bsit_subject.yr_lvl = 4 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "</table>";
		echo "<a  id = 'ayong' href='checklist.php?curID=$delID&cors=$qwe' target='_blank'><button class='botBtn'>PREVIEW</button></a>";
		echo "</div>";

	}
	////delTs
	if(isset($_POST['delTs'])){
		$subId = $_POST['delTs'];
		$delID = $_SESSION['Fid'];
		$qwe = $_SESSION['qwe'];
		$forDelSub = $conn->query("SELECT student_id FROM student WHERE curriculum_id = '$delID'");
		$d = $conn->query("SELECT subject_code FROM bsit_subject WHERE bsit_subject_id = '$subId'");
		while($myDelRows = $forDelSub->fetch_object()){
			while ($dG = $d->fetch_object()) {
				$conn->query("DELETE FROM grade WHERE student_id='$myDelRows->student_id' && subject_code='$dG->subject_code'");
			}
		}
		$conn->query("DELETE FROM bsit_subject WHERE bsit_subject_id='$subId'");	
		$confirm = 'subject succesfully removed';
		echo "<span id='confirm'>";if(isset($_POST['updateCurrSub'])){ echo $confirm;}echo "</span><br/>";
		echo "<meta http-equiv='refresh' content='0;url=addCurrSub.php?currID=".$delID."&corsID=".$qwe."'>";
		$queCur = $conn->query("SELECT curriculum_yr FROM curriculum WHERE curriculum_id='$delID'");
		while($rowz=$queCur->fetch_object()){
			echo "$rowz->curriculum_yr Curriculum Subjects<br/>";
		}
		echo "<table id='1curSubT' class='table'>";
		echo "<thead>";
		echo "<th colspan=7 class='head'>First year</th>";
		echo "</thead>";
		echo "<thead>";
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		echo "</thead>";
				$cur = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
		echo "<thead>";
		echo "<th style='width:10px;'></th>";
		echo "<th>Subject Code</th>";
		echo "<th>Descriptive title</th>";
		echo "<th>Requisite</th>";
		echo "<th>Co-Req</th>";
		echo "<th>Standing</th>";
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";

		echo "</thead>";
				$cur1 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Summer</th>";
		echo "</thead>";
		$cur10 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Second year</th>";
		
		echo "</thead>";
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		
		echo "</thead>";
		$cur2 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1&& bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";
		echo "</thead>";
		$cur3 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2&& bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Summer</th>";
		echo "</thead>";
		$cur30 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Third year</th>";
		
		echo "</thead>";
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		
		echo "</thead>";
		$cur4 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1&& bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";
		
		echo "</thead>";
		$cur5 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2&& bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Summer</th>";
		
		echo "</thead>";
		$cur50 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Fourth year</th>";
		
		echo "</thead>";
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		
		echo "</thead>";
		$cur6 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1&& bsit_subject.yr_lvl = 4 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";
		
		echo "</thead>";
		$cur7 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2&& bsit_subject.yr_lvl = 4 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "</table>";
		echo "<a  id = 'ayong' href='checklist.php?curID=$delID&cors=$qwe' target='_blank'><button class='botBtn'>PREVIEW</button></a>";
		echo "</div>";

	}
	///delTsum
	if(isset($_POST['delTsum'])){
		$subId = $_POST['delTsum'];
		$delID = $_SESSION['Fid'];
		$qwe = $_SESSION['qwe'];
		$forDelSub = $conn->query("SELECT student_id FROM student WHERE curriculum_id = '$delID'");
		$d = $conn->query("SELECT subject_code FROM bsit_subject WHERE bsit_subject_id = '$subId'");
		while($myDelRows = $forDelSub->fetch_object()){
			while ($dG = $d->fetch_object()) {
				$conn->query("DELETE FROM grade WHERE student_id='$myDelRows->student_id' && subject_code='$dG->subject_code'");
			}
		}
		$conn->query("DELETE FROM bsit_subject WHERE bsit_subject_id='$subId'");	
		echo "<meta http-equiv='refresh' content='0;url=addCurrSub.php?currID=".$delID."&corsID=".$qwe."'>";
		// $confirm = 'subject succesfully removed';
		echo "<span id='confirm'>";if(isset($_POST['updateCurrSub'])){ echo $confirm;}echo "</span><br/>";
		$queCur = $conn->query("SELECT curriculum_yr FROM curriculum WHERE curriculum_id='$delID'");
		while($rowz=$queCur->fetch_object()){
			echo "$rowz->curriculum_yr Curriculum Subjects<br/>";
		}
		echo "<table id='1curSubT' class='table'>";
		echo "<thead>";
		echo "<th colspan=7 class='head'>First year</th>";
		echo "</thead>";
		echo "<thead>";
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		echo "</thead>";
				$cur = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
		echo "<thead>";
		echo "<th style='width:10px;'></th>";
		echo "<th>Subject Code</th>";
		echo "<th>Descriptive title</th>";
		echo "<th>Requisite</th>";
		echo "<th>Co-Req</th>";
		echo "<th>Standing</th>";
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";

		echo "</thead>";
				$cur1 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Summer</th>";
		echo "</thead>";
		$cur10 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Second year</th>";
		
		echo "</thead>";
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		
		echo "</thead>";
		$cur2 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1&& bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";
		echo "</thead>";
		$cur3 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2&& bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Summer</th>";
		echo "</thead>";
		$cur30 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Third year</th>";
		
		echo "</thead>";
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		
		echo "</thead>";
		$cur4 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1&& bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";
		
		echo "</thead>";
		$cur5 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2&& bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Summer</th>";
		
		echo "</thead>";
		$cur50 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Fourth year</th>";
		
		echo "</thead>";
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		
		echo "</thead>";
		$cur6 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1&& bsit_subject.yr_lvl = 4 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";
		
		echo "</thead>";
		$cur7 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2&& bsit_subject.yr_lvl = 4 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "</table>";
		echo "<a  id = 'ayong' href='checklist.php?curID=$delID&cors=$qwe' target='_blank'><button class='botBtn'>PREVIEW</button></a>";
		echo "</div>";

	}
	////delLast
	if(isset($_POST['delLast'])){
		$subId = $_POST['delLast'];
		$delID = $_SESSION['Fid'];
		$qwe = $_SESSION['qwe'];
		$forDelSub = $conn->query("SELECT student_id FROM student WHERE curriculum_id = '$delID'");
		$d = $conn->query("SELECT subject_code FROM bsit_subject WHERE bsit_subject_id = '$subId'");
		while($myDelRows = $forDelSub->fetch_object()){
			while ($dG = $d->fetch_object()) {
				$conn->query("DELETE FROM grade WHERE student_id='$myDelRows->student_id' && subject_code='$dG->subject_code'");
			}
		}
		$conn->query("DELETE FROM bsit_subject WHERE bsit_subject_id='$subId'");
		echo "<meta http-equiv='refresh' content='0;url=addCurrSub.php?currID=".$delID."&corsID=".$qwe."'>";	
		// $confirm = 'subject succesfully removed';
		echo "<span id='confirm'>";if(isset($_POST['updateCurrSub'])){ echo $confirm;}echo "</span><br/>";
		$queCur = $conn->query("SELECT curriculum_yr FROM curriculum WHERE curriculum_id='$delID'");
		while($rowz=$queCur->fetch_object()){
			echo "$rowz->curriculum_yr Curriculum Subjects<br/>";
		}
		echo "<table id='1curSubT' class='table'>";
		echo "<thead>";
		echo "<th colspan=7 class='head'>First year</th>";
		echo "</thead>";
		echo "<thead>";
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		echo "</thead>";
				$cur = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
		echo "<thead>";
		echo "<th style='width:10px;'></th>";
		echo "<th>Subject Code</th>";
		echo "<th>Descriptive title</th>";
		echo "<th>Requisite</th>";
		echo "<th>Co-Req</th>";
		echo "<th>Standing</th>";
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";

		echo "</thead>";
				$cur1 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Summer</th>";
		echo "</thead>";
		$cur10 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 1 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Second year</th>";
		
		echo "</thead>";
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		
		echo "</thead>";
		$cur2 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1&& bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";
		echo "</thead>";
		$cur3 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2&& bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		echo "<th colspan=7 class='head'>Summer</th>";
		echo "</thead>";
		$cur30 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 2 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Third year</th>";
		
		echo "</thead>";
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		
		echo "</thead>";
		$cur4 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1&& bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";
		
		echo "</thead>";
		$cur5 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2&& bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Summer</th>";
		
		echo "</thead>";
		$cur50 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 3 && bsit_subject.yr_lvl = 3 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Fourth year</th>";
		
		echo "</thead>";
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>First SEMESTER</th>";
		
		echo "</thead>";
		$cur6 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 1&& bsit_subject.yr_lvl = 4 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "<thead>";
		
		echo "<th colspan=7 class='head'>Second SEMESTER</th>";
		
		echo "</thead>";
		$cur7 = $conn->query("SELECT bsit_subject.bsit_subject_id,bsit_subject.subject_code,subject.descriptive_title,bsit_subject.pre,bsit_subject.copre,bsit_subject.standing,subject.units,subject.lec_hrs,subject.lab_hrs,subject.contact_hours FROM bsit_subject,subject,curriculum WHERE subject.subject_code = bsit_subject.subject_code && bsit_subject.semester_id = 2&& bsit_subject.yr_lvl = 4 && bsit_subject.curriculum_id = curriculum.curriculum_id  && bsit_subject.curriculum_id = '$delID' && curriculum.course_id = '$qwe'");
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
		echo "</table>";
		echo "<a  id = 'ayong' href='checklist.php?curID=$delID&cors=$qwe' target='_blank'><button class='botBtn'>PREVIEW</button></a>";
		echo "</div>";

	}
	///edit curriculum subjects
	if(isset($_POST['currSub'])){
		$fid = $_SESSION['Fid'];
		$currSub = $_POST['currSub'];
		$_SESSION['curDesId'] = $currSub;
		echo "<center>";
		echo "<form id='up_cur_sub' method='POST'>";
		$currSubQuer = $conn->query("SELECT * FROM bsit_subject WHERE bsit_subject_id='$currSub'");
		$corId = $conn->query("SELECT course_id FROM curriculum WHERE curriculum_id='$fid'");
		while($row = $corId->fetch_object()){
				$myCid = $row->course_id;
		}

		while($rowCurSub = $currSubQuer->fetch_object() ){

			$shit1 = "$rowCurSub->pre";
			$shit2 = "$rowCurSub->copre";

			echo "<span class='for_mgin'>Subject Pre-requisite 1: </span><select name='pre1'>";
			echo "<option value=''>N/a</option>";
				$querPre1 = $conn->query("SELECT bsit_subject.subject_code,subject.descriptive_title FROM subject,bsit_subject WHERE subject.subject_code=bsit_subject.subject_code && bsit_subject.curriculum_id = '$fid'");
				while($rowOp1 = $querPre1->fetch_object()){
					if($rowCurSub->subject_code != $rowOp1->subject_code){
						echo "<option value='$rowOp1->subject_code'"; 
						if($shit1==$rowOp1->subject_code){
							echo 'selected';
						}
						echo ">$rowOp1->subject_code - $rowOp1->descriptive_title</option>";
					}
					else{
						continue;
					}	 
				}
			echo "</select><br/>";
			
			echo "<span class='for_mgin'>Subject Pre-requisite 2: </span><select name='pre2'>";
			echo "<option value=''>N/a</option>";
				$querPre2 = $conn->query("SELECT bsit_subject.subject_code,subject.descriptive_title FROM subject,bsit_subject WHERE subject.subject_code=bsit_subject.subject_code && bsit_subject.curriculum_id = '$fid'");
				while($rowOp2 = $querPre2->fetch_object()){
					if($rowCurSub->subject_code != $rowOp2->subject_code){
						echo "<option value='$rowOp2->subject_code'";
					 	if($shit2==$rowOp2->subject_code){
					 		echo 'selected';
					 	}
						 echo ">$rowOp2->subject_code - $rowOp2->descriptive_title</option>";	
					}
					else{
					 	continue;
					}
				}
			echo "</select><br/>";
			echo "<span class='for_mgin'>Standing: </span><br/><select name='pre3'>";
			echo "<option value=''>N/a</option>";
			echo "<option value='sophomore standing'"; if($rowCurSub->standing == 'sophomore standing'){echo 'selected';} echo ">Sophomore</option>";
			echo "<option value='junior standing'"; if($rowCurSub->standing == 'junior standing'){echo 'selected';} echo ">Junior</option>";
			echo "<option value='senior standing'"; if($rowCurSub->standing == 'senior standing'){echo 'selected';} echo ">Senior</option>";
			echo "</select><br/>";
			echo "<span class='for_mgin'>Year Level: </span><br/><select name='yrLvl'>";
			echo "<option value='1'"; if($rowCurSub->yr_lvl == 1){echo 'selected';} echo ">1st year</option>";
			echo "<option value='2'"; if($rowCurSub->yr_lvl == 2){echo 'selected';} echo ">2nd year</option>";
			echo "<option value='3'"; if($rowCurSub->yr_lvl == 3){echo 'selected';} echo ">3rd year</option>";
			echo "<option value='4'"; if($rowCurSub->yr_lvl == 4){echo 'selected';} echo ">4th year</option>";
			echo "</select><br/>";
			echo "<span class='for_mgin'>Semester: </span><br/><select name='sem'>";
			echo "<option value='1'"; if($rowCurSub->semester_id == 1){echo 'selected';} echo ">1st Semester</option>";
			echo "<option value='2'"; if($rowCurSub->semester_id == 2){echo 'selected';} echo ">2nd Semester</option>";
			echo "<option value='3'"; if($rowCurSub->semester_id == 3){echo 'selected';} echo ">Summer</option>";
			echo "</select><br/>";
			echo "<input class='for_mgin' type='submit' name='updateCurrSub' value='update'/>";
			echo "<input  class='for_mgin' type='submit' name='cancelCurrSub' value='cancel'/><br/>";
			// echo "<a href='?currID=$fid&corsID=$myCid'><span class='glyphicon glyphicon-circle-arrow-left'></span></a>";
			echo "</form>";
			echo "</center>";
		}
		
	}



	////subjects
	if(isset($_POST['subcode']) && isset($_POST['desc']) && isset($_POST['unit']) && isset($_POST['lecHrs']) && isset($_POST['labHrs']) && isset($_POST['conHrs'])){
		$subCode = trim(escape(ucfirst($_POST['subcode'])));
		$desc = trim(escape(ucwords($_POST['desc'])));
		$unit = escape($_POST['unit']);
		$lecHrs = escape($_POST['lecHrs']);
		$labHrs = escape($_POST['labHrs']);
		$conHrs = escape($_POST['conHrs']);
		if($subCode == '' || $desc == ''){
			echo 'Fill up all textbox';
		}
		else{
			$subject = new Subject($subCode,$desc,$unit,$lecHrs,$labHrs,$conHrs);
			$add_sub = new CrudSubjects();
			echo $add_sub->add_subjects($subject);
		}
		

	}
	///search sub
	if(isset($_POST['srchSub'])){
		$subVal = escape($_POST['srchSub']);
		$subQ ="SELECT * FROM subject WHERE subject_code LIKE '%".$subVal."%'";
		$find = $conn->query($subQ);
		if($find->num_rows == 0){
			echo 'No subject listed on our Database';
		}
		else{
		echo "<table id='tblSub' class='table table-striped '>";
			echo "<thead>";
			echo "<tr>";
			echo "<th>Subject Code</th>";
			echo "<th>Descriptive Title</th>";
			echo "<th>Units</th>";
			echo "<th>Lec Hours</th>";
			echo "<th>Lab Hours</th>";
			echo "<th>Contact Hours</th>";
			echo "<th></th>";
			echo "</tr>";
			echo "</thead>";
			while($row = $find->fetch_object()){
				echo "<tr>";
				echo "<td><input id='selCode'  name='selCode' value='$row->subject_code' type='radio'/>$row->subject_code</td>";
				echo "<td>$row->descriptive_title</td>";
				echo "<td>$row->units</td>";
				echo "<td>$row->lec_hrs</td>";
				echo "<td>$row->lab_hrs</td>";
				echo "<td>$row->contact_hours</td>";
				echo "<td><a class='white_txt' href='index.php?configure=subject&del_sub=$row->subject_code'><span class='glyphicon glyphicon-trash'></span></a></td>";
				echo "</tr>";
			}
			echo "</table>";
		}
	}
	//subject edit
	if(isset($_POST['selected'])){
		$code = $_POST['selected'];
		$_SESSION['hello'] = $code;
		$get_sub = new ViewSubjects();
		$display_sel_sub = $get_sub->display_subjects($code);
		
		echo "<center>";
		echo "<form style='text-align:left;border-radius: 10px;padding:10px;width:750px;background-color:white;' id='frmEdt' method='POST'>";
		while($row=$display_sel_sub->fetch_object()){
			echo  "<div><span style='margin-left:50px;'>subject code: </span><input value='$row->subject_code' id='subCode' type='text' name='subCode' placeholder='Subject Code'/></span>";
			echo "<span style='margin-left:80px;'>desc. title:</span><input id='desc' value ='$row->descriptive_title' type='text' name='desTitle' placeholder='Descriptive Title'/></div>";

			echo "<div style='margin-top:10px;'><span style='margin-left:50px;'>Units: </span><input value = '$row->units' id=unit type='number' name='units' min='1' max='5'/>";
			echo "<span style='margin-left:245px;'>Lecture Hours: </span><input value = '$row->lec_hrs' id='lecHrs' type='number' name='lec' min='1' max='5'/></div>";
			
			echo "<div style='margin-top:10px;'><span span style='margin-left:50px;'>Lab Hours: </span><input value = '$row->lab_hrs' id='labHrs' type='number' name='lab' min='0' max='500'/>";
			echo "<span style='margin-left:200px;'>Contact Hours: </span><input id='conHrs' value='$row->contact_hours' type='number' name='cont' min='0' max='500'/></div>";

			echo "<div style='text-align:center;'>";
			echo "<input style='margin-top:30px;width: 120px;' type='submit' value='UPDATE' name='update'>";
			echo "<input style='margin-top:20px;width:120px;' type='submit' name='cancel_s' value='CANCEL'>";
			echo "<span id='errMsg'></span>";
			echo "</div>";
		}
		echo "</form>";
		// echo "<span id='subBack' onClick='window.location.reload()' class='glyphicon glyphicon-circle-arrow-left'></span>";
		echo "</center>";
	}
	////another search stud
	if(isset($_POST['srchAstud'])){
		$srStud = escape($_POST['srchAstud']);
		$x = escape($_SESSION['x']);
		//$adSrchQuer = "SELECT * FROM student WHERE curriculum_id = $x && student_id LIKE '%".$srStud."%' OR last_name LIKE '%".$srStud."%'";
		$adSrchQuer = "SELECT student.standing,course.course_id,curriculum.curriculum_id,student.student_id,student.name,student.last_name,student.year_level,course.course,curriculum.curriculum_yr FROM student,course,curriculum WHERE course.course = '$x' && student.curriculum_id = curriculum.curriculum_id && course.course_id = curriculum.course_id && student.student_id LIKE '%".$srStud."%'";   
		$hnp = $conn->query($adSrchQuer);
		if($hnp->num_rows== 0){
			echo 'No student listed on our Database';
		}
		else{
			echo "<table  class='table table-striped'>";
			echo "<thead class='thead-inverse'>";
			echo "<tr>";
			echo "<th>Student Id</th>";
			echo "<th>Name</th>";
			echo "<th>Last Name</th>";
			echo "<th>Year Level</th>";
			echo "<th>Standing</th>";
			echo "<th>Course</th>";
			echo "<th colspan='3'>Curriculum</th>";
			echo "</tr>";
			echo "</thead>";
			while($row = $hnp->fetch_object()){
				echo "<tr>";
				echo "<td>$row->student_id</td> ";
				echo "<td>$row->name</td> ";
				echo "<td>$row->last_name</td>";
				echo "<td>$row->year_level</td>";
				echo "<td>$row->standing</td>";
				echo "<td>$row->course</td>";
				echo "<td>$row->curriculum_yr</td>";
				echo "<td><button class='stud_view_btn'><a href='advising.php?studId=$row->student_id&curr=$row->curriculum_id&corsId=$row->course_id&yrLvl=$row->year_level'>Adv form</a></button></td>";
				echo "<td><button class='stud_view_btn'><a href='grade.php?studId=$row->student_id&cors=$row->course_id&currId=$row->curriculum_id'>evaluate</a></button></td>";
				echo "</tr>";
			}
			echo "</table>";
		}
	}
	////search student
	if(isset($_POST['srchStud'])){
		$ScorsId = escape($_SESSION['cursId']);
		$Scors = escape($_SESSION['curs']);
		$val = escape($_POST['srchStud']);
		$srchQuer = "SELECT  course.course_id,curriculum.curriculum_id,student.student_id,student.name,student.last_name,student.year_level,student.password,course.course,curriculum.curriculum_yr FROM student,course,curriculum WHERE course.course = '$Scors' && student.curriculum_id = curriculum.curriculum_id && course.course_id = curriculum.course_id && student.student_id LIKE '%" .$val."%'";
		$search = $conn->query($srchQuer);
		if($search->num_rows== 0){
			echo 'No student listed on our Database';
		}
		else{

			echo "<table id='student_table' class='table table-striped '>";
			echo "<thead class='thead-inverse'>";
			echo "<tr>";
			echo "<th>Student ID</th>";
			echo "<th>Name</th>";
			echo "<th>Last Name</th>";
			echo "<th>Year Level</th>";
			echo "<th colspan='2'>Password</th>";
			echo "</tr>";
			echo "</thead>";
			while($row = $search->fetch_object()){
					echo "<tr>";
					echo "<td><input id='studId' name='studId' value='$row->student_id' type='radio'/>$row->student_id</td>";
					echo "<td>$row->name</td>";
					echo "<td>$row->last_name</td>";
					echo "<td>$row->year_level</td>";
					echo "<td>$row->password</td>";
					echo "<td><a href='?cors=$row->course&corsId=$row->course_id&del_stud=$row->student_id'><span class='glyphicon glyphicon-trash'></span></a></td>";
					echo "</tr>";
			}
			echo "</table>";
		}
			
	}
	///students EDIT
	if(isset($_POST['slctdStud'])){
		$studId = escape($_POST['slctdStud']);
		$_SESSION['hello'] = filter_var($studId,FILTER_SANITIZE_SPECIAL_CHARS);
		///session for add-edit del
		$corsId = escape($_SESSION['cursId']);
		$cors = escape($_SESSION['curs']);
		///
		$selStud = escape($_POST['slctdStud']);
		echo "<form id='update_form' method='POST'>";
		$querStud = $conn->query("SELECT * from student WHERE student_id='$selStud'");
		while($rowStud = $querStud->fetch_object()){
			$shit = $rowStud->curriculum_id;	
			echo "<input maxlength='20' class='upd' value='$rowStud->student_id' type='text' name='idNum' placeholder='Student-number'/><br/>";
			echo "<input maxlength='50' class='upd' value='$rowStud->name' type='text' name='fName' placeholder='First Name'/><br/>";
			echo "<input maxlength='50' class='upd'value='$rowStud->last_name' type='text' name='lName' placeholder='Last Name'/><br/>";
			echo "<span class='fillup'>Year Level: </span><br/>";
			echo "<select class='fillup' name='yrLvl'>";
			echo "<option value='1'"; if($rowStud->year_level == 1) echo 'selected';  echo ">1st year</option>";
			echo "<option value='2'"; if($rowStud->year_level == 2) echo 'selected';  echo ">2nd year</option>";
			echo "<option value='3'"; if($rowStud->year_level == 3) echo 'selected';  echo ">3rd year</option>";
			echo "<option value='4'"; if($rowStud->year_level == 4) echo 'selected';  echo ">4th year</option>";	
			echo "</select><br/>";
			echo "<span class='fillup'>Curriculum: </span><br/><select name='cur'>";
			$querLum = $conn->query("SELECT curriculum.curriculum_id,curriculum.curriculum_yr FROM curriculum,course WHERE course.course_id = curriculum.course_id AND  course.course_id = '$corsId'");
			while($row = $querLum->fetch_object()){
				echo "<option value='$row->curriculum_id'"; if($rowStud->curriculum_id == $row->curriculum_id){echo "selected";} echo ">$row->curriculum_yr</option>";
			}
			
			
			echo "</select><br/>";
		
		}
		echo "<input class='up_btn fillup' type='submit' name='updateStud' value='UPDATE'/>";
		echo "<input class='up_btn fillup' type='submit' name='cancel' value='CANCEL'/>";
		
		echo "</form>";	
	}
	if(isset($_POST['grade']) && isset($_POST['rem']) && isset($_POST['gId'])){
		$grd = escape($_POST['grade']);
		$rem = escape($_POST['rem']);
		$gId = escape($_POST['gId']);
		if($grd == ''){
			echo "<span id='chk1'>Blank fields will automaticaly become N/A</span>";
			$conn->query("UPDATE grade SET grade = 'N/A', remarks='$rem' WHERE grade_id = '$gId'");	
		}
		else if($grd < 50){
			echo "<span id='chk1'>Below 50 will be N/a</span>";	
			$conn->query("UPDATE grade SET grade = 'N/A', remarks='$rem' WHERE grade_id = '$gId'");
		}
		else{
			echo "<span id='chk' style='color:color:#66CD00;'  class='glyphicon glyphicon-ok'></span><span>Grade succesfully added</span>";
			$conn->query("UPDATE grade SET grade = '$grd', remarks='$rem' WHERE grade_id = '$gId'");
		}	
	}
	///////elevate students
	if(isset($_POST['xyz']) && isset($_POST['studid'])){
		$lvl = $_POST['xyz'];
		$student_id = $_POST['studid'];
		if($conn->query("UPDATE student SET year_level = $lvl, standing='sophomore standing' WHERE student_id = '$student_id'")){
			echo "student successfully elevated to 2ndyr";	
		}
		else{
			echo "Query error contact administrator";
		}	
	}
	if(isset($_POST['xyz1']) && isset($_POST['studid'])){
		$lvl1 = $_POST['xyz1'];
		$student_id = $_POST['studid'];
		if($conn->query("UPDATE student SET year_level = $lvl1, standing='junior standing' WHERE student_id = '$student_id'")){
			echo 'student successfully elevated to 3rdyr';	
		}
		else{
			echo "Query error contact administrator";
		}
	}
	if(isset($_POST['xyz2']) && isset($_POST['studid'])){
		$lvl2 = $_POST['xyz2'];
		$student_id = $_POST['studid'];
		if($conn->query("UPDATE student SET year_level = $lvl2, standing='senior standing' WHERE student_id = '$student_id'")){
			echo 'student successfully elevated to 4thyr';	
		}
		else{
			echo "Query error contact administrator";
		}
	}
	/////filter
	if(isset($_POST['fill'])){
		$fill = $_POST['fill'];
		if($fill == 'all'){
			$qSub = $conn->query("SELECT * FROM subject");
			echo "<span id='head'>Available subjects</span>";
			echo "<table id='sub_table' class = 'table'>";
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
			while($row = $qSub->fetch_object()){
				echo "<tr>";
				echo "<td><input id='selCode'  name='selCode' value='$row->subject_code' type='radio'/>$row->subject_code</td>";
				echo "<td>$row->descriptive_title</td>";
				echo "<td>$row->units</td>";
				echo "<td>$row->lec_hrs</td>";
				echo "<td>$row->lab_hrs</td>";
				echo "<td>$row->contact_hours</td>";
				echo "</tr>";
			}
			echo "</table>";
		}
		else if($fill == 'min'){
			$filS = $conn->query("SELECT * FROM subject WHERE subject_code NOT LIKE 'it%' && subject_code NOT LIKE 'cs%' && subject_code NOT LIKE 'comp%'");
			echo "<span id='head'>Available subjects</span>";
			echo "<table id='sub_table' class = 'table'>";
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
			while($row = $filS->fetch_object()){
				echo "<tr>";
				echo "<td><input id='selCode'  name='selCode' value='$row->subject_code' type='radio'/>$row->subject_code</td>";
				echo "<td>$row->descriptive_title</td>";
				echo "<td>$row->units</td>";
				echo "<td>$row->lec_hrs</td>";
				echo "<td>$row->lab_hrs</td>";
				echo "<td>$row->contact_hours</td>";
				echo "</tr>";
			}
			echo "</table>";

		}
		else{
			$filter = $conn->query("SELECT * FROM subject WHERE subject_code LIKE '$fill%' || subject_code LIKE 'comp%'");
			echo "<span id='head'>Available subjects</span>";
			echo "<table id='sub_table' class = 'table'>";
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
			while($row = $filter->fetch_object()){
				echo "<tr>";
				echo "<td><input id='selCode'  name='selCode' value='$row->subject_code' type='radio'/>$row->subject_code</td>";
				echo "<td>$row->descriptive_title</td>";
				echo "<td>$row->units</td>";
				echo "<td>$row->lec_hrs</td>";
				echo "<td>$row->lab_hrs</td>";
				echo "<td>$row->contact_hours</td>";
				echo "</tr>";
			}
			echo "</table>";
		}
		
	}
	if(isset($_POST['sem'])){
		$_SESSION['currentSem'] = $_POST['sem'];
		$sesh = $_SESSION['currentSem'];
		$conn->query("UPDATE sess SET sem_sesh = $sesh" );
		echo "<span class='glyphicon glyphicon-ok'></span>";
	}
	if(isset($_POST['new']) && isset($_POST['old'])){
		//sleep(5);
		$new = escape($_POST['new']);
		$old = escape($_POST['old']);
		$myQ = $conn->query("SELECT password FROM admin where user_name = 'admin' AND password = '$old'");
		$rCount = $myQ->num_rows;
		if($rCount == 0){
			echo 'old password did not match';	
		}
		else{
			$conn->query("UPDATE admin SET password = '$new' WHERE user_name='admin'");
			echo 'password succesfully changed';
		}
	}
	if(isset($_POST['oldP']) && isset($_POST['newP']) && isset($_POST['user'])){
		$u = escape($_POST['user']);
		$oP = escape($_POST['oldP']);
		$nP = escape($_POST['newP']);
		$uQ = $conn->query("SELECT student_id,password FROM student where student_id = '$u'");
		$mrCount = $uQ->num_rows;
		if($u!=$_SESSION['currentUser']){
			echo 'error in changing password';
		}
		else{
			if($mrCount==0){
				echo 'error in changing password';	
			}
			else{
				while($row = $uQ->fetch_object()){
					if($row->student_id!=$u){
						echo 'error in changing password';
					}
					else{
						if($oP!=$row->password){
							echo 'old password did not match';
						}
						else{
							$conn->query("UPDATE student SET password = '$nP' WHERE student_id = $u");
							echo 'Password succesfully changed';	
						}
						
					}
				}
				
			}
		}
	}
	if(isset($_POST['ClientName'])){
		$cN = trim(escape($_POST['ClientName']));
		$get_user = new Users($cN,random_pass(),'client');
		$add_user = new CrudUsers();
		echo $add_user->add_user($get_user);
	}
	////edit user interface
	if(isset($_POST['sbtn'])){
		$userEman = $_POST['sbtn'];
		$_SESSION['uname'] = $userEman;
		$user = new CrudUsers();
		echo "<center>";
		echo "<form style='text-align:center;padding:10px;width:650px;background-color:white; id='edClient' method='POST'>";
		echo $user->user_for_edit($userEman);
		echo "</form>";
		echo "</center>";
	}
	if(isset($_POST['idn']) && isset($_POST['pass'])){
		$idNum = $_POST['idn'];
		$pass = $_POST['pass'];
		if($_POST['idn'] == '' || $_POST['pass'] == ''){
			echo 'Pls Fill up all the textbox';
		}
		else{
			$ue_check = $conn->query("SELECT * from student WHERE student_id = '$idNum' AND password = '$pass' LIMIT 1");
			$rowUser = $ue_check->num_rows;
				if($rowUser == 0){
					echo "Invalid password or username";
				}
				else{
					$_SESSION['currentUser'] = $idNum;
					// header("Location: ../../ui/content");
					$URL="http://localhost/test/ui/content/";
					echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';
				}
		}
	}
?>