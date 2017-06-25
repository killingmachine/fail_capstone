<?php
session_start();
require_once 'functions/functions.php';
require 'class/db.php';
require 'class/class_student.php';
if(!isset($_SESSION['admin']) && !isset($_SESSION['client'])){
		header("Location: log_in.php");
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title>Student Config</title>
	<link href="assets/style/bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
	<script src='assets/script/jquery.min.js'></script>
	<script src='assets/style/bootstrap/js/bootstrap.min.js'></script>
	<script src='assets/script/frame.js' type='text/javascript'></script>
	<link rel="stylesheet" type="text/css" href="assets/style/config_stud.css"> 
</head>
<body>
<script type="text/javascript">
	// alert(window.innerWidth);
</script>
<?php
	include 'includes/header.php';
?>
<?php
	if(isset($_POST['add'])){
			$idNum = trim(escape($_POST['idNum']));
			$name = trim(escape($_POST['fName']));
			$lName = trim(escape($_POST['lName']));
			$yrLvl = escape($_POST['yrLvl']);
			$curri = escape($_POST['curri']);
			$errMsg = '';
			if($idNum =='' || $name=='' || $lName ==''){
				$errMsg = "<p><span style='color:#ff0033' class='glyphicon glyphicon-remove'></span> Pls Fill up all the textbox</p>";
			}
			else{
				$new_stud = new Student($idNum,$name,$lName,$yrLvl,getStanding($yrLvl),$curri,random_pass());
				$add_student =  new CrudStudents();
				$errMsg = $add_student->insert_student($new_stud);
			}

	}
?>
<div id='main-Cont'>
	<div id = 'adstud'>
	<?php
	if(isset($_GET['cors']) && isset($_GET['corsId'])){
		$_SESSION['cursId'] = $_GET['corsId'];
		$_SESSION['curs'] = $_GET['cors'];
		$corsId = $_GET['corsId'];
			$querCur = $conn->query("SELECT course.course,
								curriculum.curriculum_yr,
								curriculum.curriculum_id FROM 
								curriculum,course WHERE 
								course.course_id = $corsId &&
								course.course_id=curriculum.course_id 
								ORDER BY curriculum.curriculum_yr ASC");
			if($querCur->num_rows == 0){
				if(isset($_SESSION['admin'])){
					$_SESSION['er2'] = "no curriculum for that course, Create a curriculum first to be able to add student,<a href='index.php?configure=curriculum'>click here!</a>";
				}
				else{
					$_SESSION['er2'] = "no curriculum for that course, ask the administrator to create a curriculum<a href='index.php?configure=curriculum'></a>";	
				}
				header("Location: /oop-student-eval/admin/");
			}
	?>
		<form id='frmAdd' method='POST' >
			<input type='text' name='idNum' placeholder='Student-number' maxlength="20" /><br/>
			<input type='text' maxlength="50"  name='fName' placeholder='First Name'/><br/>
			<input type='text' maxlength="50"  name='lName' placeholder='Last Name'/><br/>
			<span class='fillup'>Year Level: </span><br/>
			<select class='fillup' name='yrLvl'>
				<option value='1'>1st year</option>
				<option value='2'>2nd year</option>
				<option value='3'>3rd year</option>
				<option value='4'>4th year</option>
			</select><br/>
			<span class='fillup'>Curriculum:</span><br/>
				<select class='fillup' name='curri'>
				<?php
						// $_SESSION['cursId'] = $_GET['corsId'];
						// $_SESSION['curs'] = $_GET['cors'];
						$cors = $_GET['cors'];
						while($row = $querCur->fetch_object()){
							echo "<option value='$row->curriculum_id'>$row->curriculum_yr - $row->course</option>";
						}
				?>
		</select><br/>

		<input id='stud_add' class='fillup' type='submit' value='ADD' name='add'/>
		<?php if(isset($_POST['add'])) echo $errMsg; ?>
		</form>
	</div>
	
	<div id='tabContainer'>
		<input type='text' id='srchBox'/><button id='srchBtn'><span id='srch' class='glyphicon glyphicon-search'></span></button>
		<div id='stud-cont'>
			<?php
				if(isset($_POST['updateStud'])){
					$studId = trim(escape($_SESSION['hello']));
					$upStud = trim(escape($_POST['idNum']));
					$fName = trim(escape($_POST['fName']));
					$lName = trim(escape($_POST['lName']));
					$yrLvl = $_POST['yrLvl'];
					$currID = $_POST['cur'];
					$errMsg2 = '';
					$get_pass='';
					$pass = $conn->query("SELECT password FROM student WHERE student_id='$studId'");
					while($row = $pass->fetch_object()){
						$get_pass = $row->password;
					}
					if($upStud == '' || $fName == '' || $lName == ''){
						$errMsg2 = "<span style='color:#ff0033' class='glyphicon glyphicon-remove'></span> update failed";
					}
					else{
						$student = new Student($upStud,$fName,$lName,$yrLvl,getStanding($yrLvl),$currID,$pass);
						$update_stud = new CrudStudents();
						$errMsg2 = $update_stud->update_stud($student,$studId);

					}
				}
			?>
			<span><?php if(isset($_POST['updateStud'])){echo $errMsg2;} ?></span>
			<table id='student_table' class='table table-striped '>
				<thead class='thead-inverse'>
					<tr>
						<th>Student ID</th>
						<th>Name</th>
						<th>Last Name</th>
						<th>Year Level</th>
						<th>Password</th>
						<th colspan='2'></th>
					</tr>
				</thead>
				<?php
					$stud_table = new CrudStudents();
					$querStud = $stud_table->display_stud($cors);
					while($row = $querStud->fetch_object()){
							echo "<tr>";
							echo "<td><input id='studId' name='studId' value='$row->student_id' type='radio'/>$row->student_id</td>";
							echo "<td>$row->name</td>";
							echo "<td>$row->last_name</td>";
							echo "<td>$row->year_level</td>";
							echo "<td>$row->password</td>";
							echo "<td><a href='?cors=$row->course&corsId=$row->course_id&del_stud=$row->student_id'><span class='glyphicon glyphicon-trash'></span></a></td>";
							echo "</tr>";
					}
				?>
			</table>
		</div>
		<script src='assets/script/student-config.js'></script>
	</div>
	<?php
		if(isset($_GET['del_stud'])){
			$del = $_GET['del_stud'];
			$remover = new CrudStudents();
			echo $remover->del_stud($del,$_SESSION['cursId'],$_SESSION['curs']);

		}
	}

	?>
	</div>
</div>

</body>
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
</html>