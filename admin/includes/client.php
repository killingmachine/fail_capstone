<?php

	if(isset($_SESSION['admin'])){
	
?>
<center>
<div id='subFormCont'>
	<input maxlength="50" id='userName' type='text' name='userName' placeholder='User Name'/><span id='warn'></span><br/>
	<button id='add'>ADD</button><br/>
	<span id='eMsg'></span><span id='shit'></span>
</div>
</center>
<div style='margin-top:20px;' id='subCont'>
<?php
if(isset($_POST['edC'])){
	$uName = trim(escape($_SESSION['uname']));
	$Cname = trim(escape($_POST['Cname']));
	$pass = trim(escape($_POST['Cpass']));
	$errMsg = "";
	if($Cname == '' || $pass == ''){
		$errMsg = "Fill up all the textbox";
	}
	else{
		$update_client = new Users($Cname,$pass,'client');
		$update_user = new CrudUsers();
		$errMsg = $update_user->update_user($update_client,$uName);
	}
}
if(isset($_POST['cancelE'])){
	echo "<meta http-equiv='refresh' content='0;url=index.php?configure=client'>";
}
?>
<span><?php if(isset($errMsg)){echo $errMsg;}else{echo "";} ?></span>
	<table id='tblSub' class='table table-striped '>
		<thead>
			<tr>
				<th>User Name</th>
				<th>Password</th>
				<th>Type of user</th>
				<th colspan=''></th>
			</tr>
		</thead>
		<?php
			$client = new CrudUsers;
			$client_query = $client->display_client('client');
			while($row = $client_query->fetch_object()){
				echo "<tr>";
				echo "<td><input id='cId'  name='cId' value='$row->user_name' type='radio'/>$row->user_name</td>";
				echo "<td>$row->password</td>";
				echo "<td>$row->type_of_user</td>";
				echo "<td><a class='white_txt' href='index.php?configure=client&del_user=$row->user_name'><span class='glyphicon glyphicon-trash'></span></a></td>";
				echo "</tr>";
			}
		?>
	</table>
</div>	
<?php
	if(isset($_GET['del_user'])){
		$del_user = $_GET['del_user'];
		$delete_user = new CrudUsers();
		echo $delete_user->del_user($del_user);
	}
}
else{
	header("Location: index.php");
}
?>
<script src='assets/script/client.js'></script>