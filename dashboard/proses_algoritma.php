<?php
$log=0.43429448190325182765;
$tahun=$_GET['tahun'];
include "../koneksi.php";
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
mysqli_query($connect, "UPDATE datareal SET status='0' tahun='$tahun'");
mysqli_query($connect, "UPDATE pasal SET status='0' tahun='$tahun'");
mysqli_query($connect, "DELETE FROM rule WHERE tahun='$tahun'");
for ($ulang=1;$ulang<2;$ulang++) {
	$cekjumlahminimal=mysqli_query($connect, "SELECT MIN(jumlah) as minimal,pasal FROM pasal WHERE status='0'");
	$datajumlahminimal=mysqli_fetch_array($cekjumlahminimal); 
	$cekpasal=mysqli_query($connect, "SELECT * FROM pasal WHERE jumlah='$datajumlahminimal[minimal]'"); 
	$datapasal=mysqli_fetch_array($cekpasal); 
	$pasall=$datapasal['pasal'];
	$data=array();
	$a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND status='0' AND pasal='$pasall'  ORDER BY RAND()");
	while($b=mysqli_fetch_array($a)) {
		$data[]=$b['jk'];
		$data[]=$b['pekerjaan'];
		$data[]=$b['kelompok'];
	}
	$implode=implode(",",$data);
	$explode=explode(",",$implode);
	$uniq=array_unique($explode);
	$imp=implode(",", $uniq);
	$exp=explode(",", $imp);
	$uniq2=array_unique($exp);
	$size2=sizeof($uniq2);
	$ps=array();

	//--------------PERHITUNGAN DATA --------------
	echo "<hr>"; 
	$a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND status='0' AND pasal='$pasall'");
	echo "JUMLAH DATA POSITIF : ";
	echo $positif=mysqli_num_rows($a);
	$a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND status='0' AND pasal!='$pasall'");
	echo "<br>JUMLAH DATA NEGATIF : ";
	echo $negatif=mysqli_num_rows($a);
	echo "<br>NILAI GAIN : ";
	echo $t0=log($positif/($negatif+$positif));
	//---------------------------------------------
	for ($i=0;$i<$size2;$i++) { $ps[]=$uniq2[$i]; }
	$impp=implode(",",$ps);
	$size=sizeof($ps);
	$expp=explode(",",$impp);
	echo "<br>".$impp;
	echo " => $pasall <hr>";
}
?>