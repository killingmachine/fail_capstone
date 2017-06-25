

<nav>
	<div id='hamburger'>
		<div></div>
		<div></div>
		<div></div>
	</div>
	<span id='lg'><a href='index.php'>Student Evaluation</a></span>
	<div id='dDownHolder'>
		<ul id='ul_down'>
		<?php
			if($_SERVER['SCRIPT_NAME']=="/oop-student-eval/admin/configure-student.php"){
				$querDep = $conn->query("SELECT * FROM course");
				while($row=$querDep->fetch_object()){
					echo "<li><a href ='?cors=$row->course&corsId=$row->course_id'>$row->course Students</a></li>";
				} 
			}
			else if($_SERVER['SCRIPT_NAME'] == "/oop-student-eval/admin/advising.php" || $_SERVER['SCRIPT_NAME'] == "/oop-student-eval/admin/checklist.php"){
				echo "";

			}
			else if($_SERVER['SCRIPT_NAME'] == "/oop-student-eval/index.php"){
				echo '<li><a href=\'?student=' . $_SESSION['currentUser'] . '\'>Change Password</a></li>';
				echo '<li><a href=\'?checklist='.  $_SESSION['currentUser'] .'\'>Checklist</a></li>';
				echo "<li><a href='log_out.php'>Log out</a></li>";
			}
			else{
			?>
			
				<li style='font-family: headerFont;'> <a href='index.php?configure=curriculum'>Configure Curriculum</a></li>
			

		<?php
			}
		?>
		</ul>
	</div>
</nav>