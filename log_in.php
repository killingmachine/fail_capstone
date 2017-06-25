<?php
	require_once 'admin/functions/functions.php';
	require 'admin/class/db.php';
	session_start();
	if(isset($_SESSION['currentUser'])){
		header("Location: index.php");	
	}	
?>
<!DOCTYPE html>
<html>
<head>
	<title>STUDENT EVALUATION</title>
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
		$err = '';
		$x = '';
		if($username == '' || $pass == ''){
			$err = "*";
			
		}
		else{
			$stud_quer = $conn->query("SELECT * FROM student WHERE student_id = '$username' 
				AND password = '$pass' ");
			if($stud_quer->num_rows == 0){
				$x='invalid password or username';
			}
			else{
				$_SESSION['currentUser'] = $username;
				header("Location: index.php");	
			}

		}
	}
?>
	<div>
		<p>STUDENT EVALUATION</p>
		<form method='post'>
			<input class='for_mgin' name='a_c_uname' type='text' maxlength="50" placeholder='ID NUMBER'/><span style='color:red;'><?php if(isset($_POST['login'])){if($username == ''){echo $err;}} ?></span><br/>
			<input class='for_mgin' name='a_c_pass' type='password' maxlength="50" placeholder='PASSWORD'/><span style='color:red;'><?php if(isset($_POST['login'])){if($pass == ''){echo $err;}} ?></span><br/>
		
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