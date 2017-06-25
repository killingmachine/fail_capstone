<?php
	class Curriculum{
		public $course_id;
		public $curriculum_yr;
		function __construct($course_id,$curriculum_yr){
			
			$this->course_id = $course_id;
			$this->curriculum_yr = $curriculum_yr;
		}
		
	}
	class InsertCurriculum{
		public function insert_curriculum(Curriculum $curriculum){
			require 'db.php';
			$check_duplicate = $conn->query("SELECT * FROM curriculum where
			 curriculum_yr = '$curriculum->curriculum_yr' && 
			 course_id = '$curriculum->course_id'");
			if($check_duplicate->num_rows){
				return "Same Course and Curriculum name is not allowed.";
			}
			else{
				$conn->query("INSERT INTO curriculum(course_id,curriculum_yr)	
		 		VALUES('$curriculum->course_id','$curriculum->curriculum_yr')");
		 		return "<meta http-equiv='refresh' content='0;url=index.php?configure=curriculum'>";
			}

		}
	}
	class UpdateCurriculum{
		public function update_curriculum(Curriculum $curriculum,$new_curr_yr,$cur){
			require 'db.php';
			$validation_query = $conn->query("SELECT * FROM curriculum where 
				curriculum_yr = '$new_curr_yr' &&
				 course_id = '$curriculum->course_id'");
			// return var_dump($validation_query);
			if($conn->affected_rows > 1){
				return "duplicate Curriculum";
			}
			else if($conn->affected_rows == 1){
				return "no changes";
			}
			else{
				$conn->query("UPDATE curriculum SET curriculum_yr = '$new_curr_yr' WHERE curriculum_id = '$cur'");
				return "curriculum name succesfully updated";
			}
		}
	}
	class GetCurriculum{
		public function display_cur_name($course_id){
			require 'db.php';
			$check_curriculum = $conn->query("SELECT curriculum.curriculum_id,
											curriculum.curriculum_yr,
											curriculum.course_id,
											course.course FROM curriculum,course WHERE 
											(curriculum.course_id = '$course_id' 
											&& curriculum.course_id = course.course_id)");
			return $check_curriculum;

		}
	}
	class View_configure_curr{
		public function single_query_cur($que){
			require 'db.php';
			return $conn->query("SELECT * FROM curriculum WHERE curriculum_id='$que'");
		}
		public function view_curr(){
			require 'db.php';
			$curriculums = $conn->query("SELECT curriculum.curriculum_id,
								curriculum.course_id,
								curriculum.curriculum_yr, 
								course.course_id,
								course.course FROM 
								curriculum,course WHERE 
								course.course_id = 
								curriculum.course_id");
			return $curriculums;
		}
		public function delete_curr($curr){
			require 'db.php';
			$studId=$conn->query("SELECT student_id from student WHERE curriculum_id = '$curr'");
			while($thisRow = $studId->fetch_object()){
				$conn->query("DELETE FROM grade WHERE student_id='$thisRow->student_id'");
			}
			$conn->query("DELETE FROM student WHERE curriculum_id='$del'");
			$conn->query("DELETE FROM bsit_subject WHERE curriculum_id='$curr'");
			$conn->query("DELETE FROM curriculum WHERE curriculum_id='$curr'");

		}
	}
?>