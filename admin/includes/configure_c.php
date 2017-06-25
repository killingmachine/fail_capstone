<?php
	if(isset($_SESSION['admin'])){
		$curriculums = new View_configure_curr();
		$display = $curriculums->view_curr();

?>
	<center>
	<div id='curForm'>
	<center>
	<div id='one'><span>Curriculum for: </span><select id='cors' name='course' style='color:black;'><option value='1'>BSIT</option><option value='2'>BSCS</option><option value='3'>BSIT - Major in Animaion</option></select><br/></div>
	<span>Curriculum Name: </span><input placeholder='Curriculum Name' id='currName' type='txt' name='currName'/><span id='warn'></span><br/>
	
	<button id='addBtn'>ADD</button><br/>
	</center>
	<span id='errMsg'></span>
	</div>
	
	</center>
	<div id='currList'>
	<?php
		if(isset($_POST['updateCur'])){
			$curr_id = $_SESSION['hello'];
			$curso_id = $_SESSION['fudep'];
			$cur_name = trim($_POST['currName']);
			$errMsg = "";
			$selected_cur = new Curriculum($curso_id,$cur_name);
			$update_cur = new UpdateCurriculum();
			$errMsg = $update_cur->update_curriculum($selected_cur,$cur_name,$curr_id);
			echo "<center>".$errMsg."</center>";
		}
		if(isset($_POST['cancel'])){
		 	echo "<meta http-equiv='refresh' content='0;url=index.php?configure=curriculum'>";	
		}
	?>
<?php
	if($display->num_rows){
?>
	<table id='fT' class='table table-striped '>
	<thead class = 'thead-inverse'>
		<tr>
			<th>Curriculum Name</th>
			<th colspan='3'>Course</th>
		</tr>
	</thead>
<?php
		// echo var_dump($display);	
		while($rows = $display->fetch_object()){
			echo "<tr>";
			echo "<td><input id='curId' name='curId' data-value='$rows->course_id' value='$rows->curriculum_id' type='radio'>$rows->curriculum_yr</td>";
			echo "<td>$rows->course</td>";
			echo "<td><a class='white_txt' href='addCurrSub.php?currID=$rows->curriculum_id&corsID=$rows->course_id'><button class='btnCur'>Configure subjects</button></a></td>";
			echo "<td><a class='white_txt' href='?configure=curriculum&del=$rows->curriculum_id'><span class='glyphicon glyphicon-trash'></span></a></td>";
			echo "</tr>";
		}
		if(isset($_GET['del'])){
			$del = $_GET['del'];
			$curriculums->delete_curr($del);
			echo "<meta http-equiv='refresh' content='0;url=index.php?configure=curriculum'>";	
		}	
?>
	</table>
	
<?php
	}
	else{
?>
	<h1>No Curriculums in database</h1>
<?php
	}
?>
	</div>
<?php
}
else{
	header("Location: index.php");
}
?>
<script src='assets/script/config-curri.js'></script>