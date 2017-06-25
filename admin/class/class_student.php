<?php
	class Student{
		public $student_id;
		public $name;
		public $last_name;
		public $yr_lvl = 1;
		public $standing;
		public $curriculum_id;
		public $password;
		function __construct($student_id,$name,$last_name,$yr_lvl,$standing,$curriculum_id,$password){
			$this->student_id = $student_id;
			$this->name = $name;
			$this->last_name = $last_name;
			$this->yr_lvl = $yr_lvl;
			$this->standing = $standing;
			$this->curriculum_id = $curriculum_id;
			$this->password = $password;
		}
	}
	
	class CrudStudents{
		function del_stud($stud,$sesh_cid,$sesh_cors){
			require 'db.php';
			$conn->query("DELETE FROM student WHERE student_id='$stud'");
			$conn->query("DELETE FROM grade WHERE student_id='$stud'");
			return "<meta http-equiv='refresh' content='0;url=configure-student.php?cors=".$sesh_cors."&corsId=".$sesh_cid."'>";	
		}
		function update_stud(Student $student,$old_stud_id){
			require 'db.php';
			$zz = 0;
			
			$pissOflola = $conn->query("SELECT * FROM student WHERE student_id = '$old_stud_id'");
			while($piss = $pissOflola->fetch_object()){
				$zz = $piss->curriculum_id;
			}
			$conn->query("UPDATE student SET student_id = '$student->student_id', 
				name = '$student->name', 
				last_name='$student->last_name', 
				year_level='$student->yr_lvl',
				curriculum_id ='$student->curriculum_id',
				standing='$student->standing' WHERE student.student_id = '$old_stud_id'");
			if($conn->affected_rows > 0){
				return "<p><span style='color:#66CD00' class='glyphicon glyphicon-ok'></span> Record succesfully Updated</p>";
				if($student->curriculum_id == $zz){
					$conn->query("UPDATE grade SET student_id = '$student->student_id'
					 WHERE grade.student_id = '$old_stud_id'");
				}
				else{
					$conn->query("DELETE FROM grade WHERE student_id='$old_stud_id'");
					$getCurId = $conn->query("SELECT curriculum_id FROM student 
						WHERE student_id = '$student->student_id' LIMIT 1");
					while($myRow = $getCurId->fetch_object()){
						$getSub = $conn->query("SELECT * FROM bsit_subject WHERE curriculum_id = '$myRow->curriculum_id'");
							while($myRow2 = $getSub->fetch_object()) {
								$conn->query("INSERT INTO grade(subject_code,student_id,grade,remarks) VALUES('$myRow2->subject_code','$student->student_id','N/A','N/A');");
							}
					}
				}
			}
			else if($conn->affected_rows == 0){
				return "<p><span style='color:#66CD00' class='glyphicon glyphicon-ok'></span>no changes made</p>";
			}
			else{
				return 'duplicate id number';
			}

		}
		function insert_student(Student $stud){
			require 'db.php';
			$check_duplicate = $conn->query("SELECT student_id FROM student WHERE student_id = '$stud->student_id'");
			if($check_duplicate->num_rows > 0){
				return "<p><span style='color:#ff0033' class='glyphicon glyphicon-remove'></span> Id number already exist</p>";
			}			
			else{
				$conn->query("INSERT INTO student(student_id,name,last_name,year_level,standing,curriculum_id,password) 
				VALUES('$stud->student_id',
					'$stud->name','$stud->last_name',
					$stud->yr_lvl,
					'$stud->standing',
					$stud->curriculum_id, 
					'$stud->password')"
				);

				$inSub = $conn->query("SELECT * FROM bsit_subject WHERE curriculum_id='$stud->curriculum_id'");

				while($thisRow = $inSub->fetch_object()){
					$conn->query("INSERT INTO grade(subject_code,student_id,grade,remarks)
						VALUES
					('$thisRow->subject_code','$stud->student_id','N/A','N/A');");
				}
				
				return "<p><span style='color:#66CD00' class='glyphicon glyphicon-ok'></span> Student Succesfully added,<br/> subjects will automatically<br/> be genrated base on the curriculum</p>";;
			}
			
		}
		function view_student($course){
			require 'db.php';
			$get_curriculum = $conn->query("SELECT student.standing,
											course.course_id,
											curriculum.curriculum_id,
											student.student_id,
											student.name,
											student.last_name,
											student.year_level,
											course.course,
											curriculum.curriculum_yr FROM student,course,curriculum 
											WHERE (course.course = '$course' && 
											student.curriculum_id = curriculum.curriculum_id && 
											course.course_id = curriculum.course_id)");
			if($get_curriculum->num_rows == 0){
				echo '<h2 style=\'text-align:center;\'>No Student List on Database</h2>';
			}
			else{

				echo "<input maxlength='50' placeholder='Student-Number' id='anSrch' name='anSrch' type='text'><button id='srchBtn' class='srchBtn'><span id='btnSrch' class='glyphicon glyphicon-search'></span></button><br/>";
				echo "<div id='tblStud'>";
				echo "<table class='table table-striped'>";
				echo "<thead id='student_thead' class='thead-inverse'>";
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
					while($row = $get_curriculum->fetch_object()){
						echo "<tr>";
						echo "<td>$row->student_id</td> ";
						echo "<td>$row->name</td> ";
						echo "<td>$row->last_name</td>";
						echo "<td>$row->year_level</td>";
						echo "<td>$row->standing</td>";
						echo "<td>$row->course</td>";
						echo "<td>$row->curriculum_yr</td>";
						echo "<td><button class='stud_view_btn' ><a href='advising.php?studId=$row->student_id&curr=$row->curriculum_id&corsId=$row->course_id&yrLvl=$row->year_level'>Adv form</a></button></td>";
						echo "<td><button class='stud_view_btn'><a href='?studId=$row->student_id&cors=$row->course_id&currId=$row->curriculum_id'>evaluate</a></button></td>";
						echo "</tr>";
					}
				echo "</table>";
				echo "<div/>";
				echo "<script src='assets/script/student.js'></script>";
			}
			

		}
		function display_stud($course){
			require 'db.php';
			$stud = $conn->query("SELECT course.course_id,curriculum.curriculum_id,student.student_id,student.name,student.last_name,student.year_level,student.password,course.course,curriculum.curriculum_yr FROM student,course,curriculum WHERE (course.course = '$course' && student.curriculum_id = curriculum.curriculum_id && course.course_id = curriculum.course_id)");
			return $stud;
		}
	}

	// $stud = new Student('321','patrick','Chua',1,getStanding(2),2,random_pass());
	// $add_student =  new CrudStudents();
	// echo $add_student->insert_student($stud);
	// echo "<pre>";
	// print var_dump($stud);
	// echo "</pre>";
?>