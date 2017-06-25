<?php
	class Users{
		public $user_name ;
		public $password ;
		public $type_of_user;
		function __construct($user_name,$password,$type_of_user){
			$this->user_name = $user_name;
			$this->password = $password;
			$this->type_of_user = $type_of_user;
		}
		
	}
	class CrudUsers{
		function display_client($userType){
			require 'db.php';
			$client_query = $conn->query("SELECT * FROM admin WHERE type_of_user = '$userType'");
			return $client_query;
		}
		function disp($uname,$pass,$userType){
			require 'db.php';
			$client_query = $conn->query("SELECT * FROM admin WHERE
			 user_name='$uname' AND
			 password = '$pass' AND 
			 type_of_user = '$userType'");
			return $client_query;

		}
		function add_user(Users $users){
			require 'db.php';
			$chk_dup = $conn->query("SELECT * FROM admin WHERE type_of_user = 'client' AND user_name = '$users->user_name'");
			if($chk_dup->num_rows > 0){
				return "Duplicate username";	
			}
			else{
				if($users->user_name ==''){
					return "Fill up the textbox";
				}
				else{

					$conn->query("INSERT INTO admin(user_name,password,type_of_user)
			 		VALUES('$users->user_name','$users->password','client')");
			 		return "<meta http-equiv='refresh' content='0;url=?configure=client'>";
				}
			}
		}
		function user_for_edit($old_user){
			require 'db.php';
			$form = '';
			$slctdClient = $conn->query("SELECT * FROM admin WHERE 
							user_name = '$old_user' AND 
							type_of_user='client'");
			while($row = $slctdClient->fetch_object()){
				$form = "<span>username: </span><input id='forUname' name='Cname' value='$row->user_name' type='text' placeholder='USERNAME' style='margin-right:10px;'/>";
				$form .= "<span>password: </span><input id='forPass' name='Cpass' value='$row->password' type='password' placeholder='PASSWORD'/><br/>";
				$form .= "<input type='submit' name='edC' value='UPDATE' style='width:100px; margin:10px 0px 0px -5px;'/>";
				$form .= "<input type='submit' name='cancelE' value='CANCEL' style='width:100px; margin:10px 0px 0px -5px;'/>";
			}
			return $form;

		}
		function update_user(Users $user,$old_user){
			require 'db.php';
			$conn->query("UPDATE admin SET 
						user_name = '$user->user_name', 
						password = '$user->password' WHERE 
						admin.user_name = '$old_user' AND 
						admin.type_of_user = 'client'");
			if($conn->affected_rows > 0){
				return 'Client Succesfully Updated';
			}
			else{
				return 'No changes made';
			}
		}
		function del_user($del_user){
			require 'db.php';
		$conn->query("DELETE FROM admin WHERE user_name='$del_user'");
			return "<meta http-equiv='refresh' content='0;url=index.php?configure=client'>";
		}
	}
?>
