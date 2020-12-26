<?php
$act=(isset($_GET['act']) ? strtolower($_GET['act']) : NULL);//$_GET[act];
	if(!isset($_SESSION['id_user'])){
	echo"<script>document.location.href='../'</script>";
	}
	else{unset($_SESSION['id_user']);
	echo "<script>
			alert(\"Anda Berhasil Logout\");
			document.location=\"../\"
		</script>";
	}
?>