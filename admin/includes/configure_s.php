<?php
	if(isset($_SESSION['admin'])){
	
?>
	<center>
	<div id='subFormCont'>
	<div style='text-align:left;'>
		<div style='margin-left:40px;margin-right:90px;float:left;'>
			<span>subject code </span><input maxlength="50" id='subCode' type='text' name='subCode' placeholder='Subject Code'/><span id='warn'></span>
		</div>
		<div style='float:left;'>
			<span>desc. title </span><input maxlength="100" id='desc' type='text' name='desTitle' placeholder='Descriptive Title'/><span id='warn1'></span>
		</div>
	</div>
	<div style='margin-left:40px;clear:left;text-align:left;margin-top:40px;'>
		<div>
			<span>Units: </span><input id='unit' type='number' name='units' min='1' max='5' value='1'/>
			<span style='margin-left:250px;'>Lecture Hours: </span><input id='lecHrs' type='number' name='lec' min='1' max='5' value='1'/>
		</div>
		<div style='margin-top:10px;'>
			<span>Lab Hours: </span><input id='labHrs' type='number' name='Lab' min='0' max='500' value='0'/>
			<span style='margin-left:205px;'>Contact Hours: </span><input id='conHrs' type='number' name='cont' min='0' max='500' readonly />
		</div>
	</div>
	<button id='add'>ADD</button><br/>
	<span id='errMsg'></span><span id='shit'></span>
	</div>
	</center>
	<center style='margin-top:5px;margin-bottom:5px;'>
		<input maxlength="50" type='text' placeholder='SUBJECT CODE' id='srchBox'/><button id='srchBtn' class='srchBtn'><span id='srch' class='glyphicon glyphicon-search'></span></button>
	</center>
	
	<div id='subCont'>
	<?php
		if(isset($_POST['update'])){
			$code = $_SESSION['hello'];
			$upCode = escape(ucfirst($_POST['subCode']));
			$desTitle = escape(ucwords($_POST['desTitle']));
			$unit = escape($_POST['units']);
			$lecHrs = escape($_POST['lec']);
			$labHrs = escape($_POST['lab']);
			$conHrs = escape($_POST['cont']);
			$errMsg = "";
			if($upCode == '' || $desTitle == '' || $lecHrs == ''){
				$errMsg = "pls fill up all the textboxes";

			}
			else{
				$subject = new Subject($upCode,$desTitle,$unit,$lecHrs,$labHrs,$conHrs);
				$sub_updater = new CrudSubjects();
				$errMsg = $sub_updater->update_sub($subject,$code);
			}
		}
		if(isset($_POST['cancel_s'])){
		 	echo "<meta http-equiv='refresh' content='0;url=?configure=subject'>";	
		}
	?>
	<span><?php if(isset($errMsg)){echo $errMsg;}else{echo "";} ?></span>
	<table id='tblSub' class='table table-striped '>
		<thead>
			<tr>
				<th>Subject Code</th>
				<th>Descriptive Title</th>
				<th>Units</th>
				<th>Lec Hours</th>
				<th>Lab Hours</th>
				<th colspan=''>Contact Hours</th>
				<th colspan=''></th>
			</tr>
		</thead>
		<?php
			$view_sub = new ViewSubjects();
			$display_sub = $view_sub->display_subjects(); 
			while($row=$display_sub->fetch_object()){
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
			
		?>
	</table>
	</div>

<?php
	if(isset($_GET['del_sub'])){
		$del_sub = $_GET['del_sub'];
		$delete = new CrudSubjects();
		echo $delete->delete_subjects($del_sub);
					
	}
	}
	else{
		header("Location: index.php");
	}
?>
<script src='assets/script/subject-config.js'></script>