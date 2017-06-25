<?php
	class Subject{
		public $subject_code;
		public $desc_tittle;
		public $units;
		public $lec_hrs;
		public $lab_hrs;
		public $contact_hrs;

		function __construct($subject_code,$desc_tittle,$units,$lec_hrs,$lab_hrs,$contact_hrs){
			$this->subject_code = $subject_code;
			$this->desc_tittle = $desc_tittle;
			$this->units = $units;
			$this->lec_hrs = $lec_hrs;
			$this->lab_hrs = $lab_hrs;
			$this->contact_hrs = $contact_hrs;
		}
	}
	class ViewSubjects{
		public function display_subjects($sub = null){
			require 'db.php';
			if($sub!=null){
				$this_subject=$conn->query("SELECT * FROM subject WHERE (subject_code = '$sub')");
				return $this_subject;
			}
			else{
				$sub_all = $conn->query("SELECT * FROM subject");
				if($sub_all->num_rows){
					return $sub_all;
				}
				else{
					return "No subject from database";
				}	
			}
			
			
		}
	}
	class CrudSubjects{
		public function add_subjects(Subject $subject){
			require 'db.php';
			if($conn->query("INSERT INTO subject(subject_code,descriptive_title,units,lec_hrs,lab_hrs,contact_hours)
				VALUES('$subject->subject_code',
						'$subject->desc_tittle',
						'$subject->units',
						'$subject->lec_hrs',
						'$subject->lab_hrs',
						'$subject->contact_hrs')")){
				return "<span style='color:#66CD00' class='glyphicon glyphicon-ok'></span>Subject succesfully added<br/>
				";
			}
			else{
				return "<span style='color:#ff0033' class='glyphicon glyphicon-remove'></span>cant insert duplicate subject code";
			}
		}
		public function update_sub(Subject $subject,$sub_code){
			require 'db.php';
			$udated_sub_query = $conn->query("UPDATE subject SET subject_code = '$subject->subject_code', 
								descriptive_title = '$subject->desc_tittle', 
								units='$subject->units', 
								lec_hrs='$subject->lec_hrs', 
								lab_hrs = '$subject->lab_hrs', 
								contact_hours = '$subject->contact_hrs'
								 WHERE subject.subject_code = '$sub_code'");
			if($conn->affected_rows > 0){
				
				$conn->query("UPDATE grade SET subject_code = '$subject->subject_code' 
										WHERE grade.subject_code = '$sub_code'");
				$conn->query("UPDATE bsit_subject SET subject_code = '$subject->subject_code' 
										WHERE bsit_subject.subject_code = '$sub_code'");
				return "record succesfully updated";
			}
			else if($conn->affected_rows == 0) {
				return "no changes made";
			}
			else{
				return "subject code already exist";
			}
		}
		public function delete_subjects($sub_to_del){
			require 'db.php';
			$conn->query("DELETE FROM grade WHERE subject_code='$sub_to_del'");
			$conn->query("DELETE FROM subject WHERE subject_code='$sub_to_del'");
			$conn->query("DELETE FROM bsit_subject WHERE subject_code='$sub_to_del'");
			echo "<meta http-equiv='refresh' content='0;url=index.php?configure=subject'>";
		}
	}
?>