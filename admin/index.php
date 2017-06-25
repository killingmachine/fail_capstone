<?php
session_start();
if(!isset($_SESSION['admin']) && !isset($_SESSION['client'])){
	header("Location: log_in.php");
}
require_once 'functions/functions.php';
require 'class/db.php';	
require 'class/class_student.php';
require 'class/class_curriculum.php';
require 'class/class_subjects.php';
require 'class/class_user.php';

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
	<script src='assets/script/script.js'></script>
</head>
<script>
	// alert(window.innerWidth);
</script>
<body>
<header>
<div>
	<a href="\oop-student-eval\admin">Student Evaluation System</a><br/>
	<span id='headText'>Cagayan State University</span>
</div>
</header>

<section style='height:100%;'>
	<nav>
	<div id='nav_wrapper'>
		<ul>
			<li class='course_txt'>BSCS
				<ul class='ul_in_course'>
					<li class='in_course_txt'><a href='?students=2&course=BSCS'>Students</a></li>
					<li class='in_course_txt'><a href='?corsId=2&course=BSCS'>curriculum</a></li>
					
				</ul>
			</li>
			<li class='course_txt'>BSIT
				<ul class='ul_in_course'>
				
					<li class='in_course_txt'><a href="?students=1&course=BSIT">Students</a></li>
					<li class='in_course_txt'><a href="?corsId=1&course=BSIT">curriculum</a></li>
					
				</ul>
			</li>
			<li class='course_txt'>Animation
				<ul class='ul_in_course'>
					
					<li class='in_course_txt'><a href='?students=3&course=BSIT Major in Animation'>Students</a></li>
					<li class='in_course_txt'><a href='?corsId=3&course=BSIT Major in Animation'>curriculum</a></li>
					
				</ul>
			</li>
			<li class='course_txt'>Settings
				<ul class='ul_in_course'>
				
					<li class='in_course_txt'><a href='configure-student.php'>Configure Students</a></li>
					<?php
						if(isset($_SESSION['admin'])){
							echo "<li class='in_course_txt'><a href='?configure=client'>Configure Clients</a></li>";
							echo "<li class='in_course_txt'><a href='?configure=subject'>Configure Subjects</a></li>"; 
							echo "<li class='in_course_txt'><a href='?configure=curriculum'>Configure Curriculum</a></li>";
							 
						}
					?>
						<li class='in_course_txt'><a href='includes/log_out.php'>Log Out</a></li>		
						
					
					
			
				</ul>
			</li>
		</ul>
		</div>
</nav>
	<div style="margin-top:110px;;" id='content_holder'>
	<?php
		if(isset(($_GET['students'])) && isset($_GET['course'])){
			$curId = $_GET['students'];
			$cors = $_GET['course'];
			$display_stud = new CrudStudents();
			if($curId == 1){
				echo"<h2 class='course_header_text'>". $cors . " list of students". "</h2>";
				$display_stud->view_student($cors);
				$_SESSION['x'] = $cors;
			}
			else if($curId == 2){
				echo"<h2 class='course_header_text'>". $cors . " list of students". "</h2>";
				$display_stud->view_student($cors);	
				$_SESSION['x'] = $cors;
			}
			else{
				echo"<h2 class='course_header_text'>". $cors . " list of students". "</h2>";
				$display_stud->view_student($cors);	
				$_SESSION['x'] = $cors;	
			}
		}
		else if(isset($_GET['corsId']) && isset($_GET['course'])){
				$show_cur_name = new GetCurriculum();
				$curiId = $_GET['corsId'];
				$cors = $_GET['course'];
				$num_rows_return = $show_cur_name->display_cur_name($curiId);
				if($num_rows_return->num_rows){
					
					echo "<h2 class='course_header_text'>". $cors . " list of curriculums". "</h2>";
					
					echo "<div style='text-align:center;'>";
					while ($get_curr_data = $num_rows_return->fetch_object()){
						echo "<a class='click_here' href='checklist.php?curID=$get_curr_data->curriculum_id&cors=$get_curr_data->course_id'>$get_curr_data->curriculum_yr</a><br/>";
					}
					echo "</div>";
				}
				else{
					echo "No $cors Curriculum Available. Create a Curriculum, <a class='click_here' href='#'>Click here</a> to create one.";
				}
		}
		else if(isset($_GET['studId']) && isset($_GET['cors']) && isset($_GET['currId'])){
			include 'includes/grade.php';
		}
		else if(isset($_GET['configure'])){
			$x = $_GET['configure'];
			if($x == 'subject'){
				include 'includes/configure_s.php';
			}
			else if($x=='curriculum'){
				include 'includes/configure_c.php';
			}
			else if($x == 'client'){
				include 'includes/client.php';
			}
			else{
				header('Location: index.php');
			}
		}
		else{
			if(isset($_SESSION['er2'])){
				echo "<span style='color:red;font-size:2em;'>".$_SESSION['er2']."</span><br/>";
				unset($_SESSION['er2']);
			}
			if(isset($_SESSION['err3'])){
				echo "<p style='color:red;'>".$_SESSION['err3']."</p>";
				unset($_SESSION['err3']);
			}
			if(isset($_SESSION['err'])){
				echo "<p style='color:red;'>".$_SESSION['err']."</p>";
				unset($_SESSION['err']);
			}
			if(isset($_SESSION['admin'])){
				echo "<spam style='color:black;font-size:1em;'>set the semester: </span><select name='sem' id='sem'>";
				echo "<option value='1'";
				if(isset($_SESSION['currentSem'])){
			    	if($_SESSION['currentSem'] == 1){
						echo "selected";
					}
				}
				echo ">1st Semester</option>";
				echo "<option value='2'";
				if(isset($_SESSION['currentSem'])){
			    	if($_SESSION['currentSem'] == 2){
						echo "selected";
					}
				}
				echo ">2nd Semester</option>";
				echo "<option value='3'";
				if(isset($_SESSION['currentSem'])){
			    	if($_SESSION['currentSem'] == 3){
						echo "selected";
					}
				}
				echo ">Summer</option>";
				echo "</select>";
			// echo "<input id='sub' name='ok' type = 'submit' value='Ok'>";
				echo "<button id='x'>Ok</button>";
			// if(isset($_SESSION['currentSem'])){
				echo "<span id='msgSem'></span>";
			// }
			
				echo "<br/>";
				echo "<br/>";	
				if(isset($_SESSION['admin'])){
					echo "<p id='z'>Change Admin Password</p>";
					echo "<span >old Password: <input id='o' type='password' maxlength='50'></span><span style='margin-right:10px;color:red;' id='q'></span>";
					echo "<span>new Password: <input id='n' type='password' maxlength='50'></span><span style='color:red;' id='w'></span>";
					echo "<button id='cBtn'>Change</button>";
					echo "<span id='msg' style='color:black;'></span>";	
				}
			}	
			
		}
	?>
	</div>

</section>
</body>
</html>