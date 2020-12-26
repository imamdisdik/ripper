<?php
include "koneksi.php";
$user=$_POST['username'];
$pw=$_POST['password'];
$q=mysqli_query($connect, "SELECT * FROM user WHERE username='$user' and password='$pw'");
$hit=mysqli_num_rows($q);
	if($hit>0){
		while($d=mysqli_fetch_array($q)){
			$user_act=$d['active'];
			$id=$d['id_user'];
			$last_login=$d['last_login'];
		}
			if($user_act==1){
			session_start();
			$_SESSION['last_login']=$last_login;
			$s_id=$_SESSION['id_user']=$id;
			$time=mysqli_query($connect, "UPDATE user SET last_login=CURRENT_TIMESTAMP WHERE id_user='$s_id'");
				echo "<script>window.location.href='dashboard/index.php?menu=home';</script>";
	}
}
	else{
		echo "<script>
		alert('Maaf username dan password anda salah! Silahkan coba lagi');
		window.location.href='index.php';
		</script>";
		}
?>