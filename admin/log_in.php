<?php
	session_start();
	if(isset($_SESSION['admin']) || isset($_SESSION['client'])){
		header("Location: \oop-student-eval\admin");	
	}
	
	require_once 'functions/functions.php';
	// require 'class/db.php';
	require 'class/class_user.php';
?>

<!DOCTYPE html>
<html>
<head>
	<title>Admin panel</title>
	<style>
		*{
			margin:0;
			padding:0;
		}	
		@font-face {
   			font-family: bebas;
		   src: url(assets/style/fonts/proximanova-bold-webfont.ttf);
		}
		p{
			font-family: bebas;
			font-size: 2em;
		}
		
		div{
			background-color: #f1f1f1;
			width: 400px;
			padding: 20px;
			text-align: center;
			margin-top: 25%;
			margin: 170px auto 0 auto;
			border-radius: 10px;
		}
		input[type='submit']{
			width: 200px;
		}
		.for_mgin{
			margin-top: 5px;
			margin-bottom: 5px;
		}
	</style>
</head>
<body>
<?php
	if(isset($_POST['login'])){
		$username = trim(escape($_POST['a_c_uname']));
		$pass = trim(escape($_POST['a_c_pass']));
		$userType = $_POST['userType'];
		$err = '';
		
		$x = '';
		if($username == '' || $pass == ''){
			$err = "*";
			
		}
		else{
			$admin_query = new CrudUsers();
			if($userType == 'admin'){
				
				$check_row_count = $admin_query->disp($username,$pass,$userType);
				if($check_row_count->num_rows == 0){
					$x='invalid password or username';
				}
				else{
					$_SESSION['admin'] = $username;
					header("Location: /oop-student-eval/admin/");
				}
			}
			else{
				$check_row_count = $admin_query->disp($username,$pass,$userType);
				if($check_row_count->num_rows == 0){
					$x='invalid password or username';
				}
				else{
					$_SESSION['client'] = $username;
					header("Location: /oop-student-eval/admin/");
				}
			}
		}
		
	}
?>
<div>
	<p>STUDENT EVALUATION</p>
	<form method='post'>
		<input class='for_mgin' name='a_c_uname' type='text' maxlength="50" placeholder='USERNAME'/><span style='color:red;'><?php if(isset($_POST['login'])){if($username == ''){echo $err;}} ?></span><br/>
		<input class='for_mgin' name='a_c_pass' type='password' maxlength="50" placeholder='PASSWORD'/><span style='color:red;'><?php if(isset($_POST['login'])){if($pass == ''){echo $err;}} ?></span><br/>
		<select class='for_mgin' name = 'userType'>
			<option value='admin'>Admin</option>
			<option value='client'>evaluator</option>
		</select><br/>
		<input class='for_mgin' type='submit' name='login' value='login' >
	</form>
	<span style='color:red;'>
	<?php
		if(isset($_POST['login'])){
			echo $x;
		}
	?>
	</span>
</div>

</body>
</html>