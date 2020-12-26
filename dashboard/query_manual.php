<?php
$tahun='2016';
include "../koneksi.php";
$a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND pasal='288(1)' AND kelompok='A'");
mysqli_num_rows($a);
while ($b=mysqli_fetch_array($a)) {
	//echo $b['id_data'];
	//echo $b['jk'];
	//echo $b['kelompok'];
	//echo $b['pekerjaan'];
	//echo "<br>";
}
$datapositifkonjungsi2=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='2016' AND jk='Pr' AND kelompok='A' AND pekerjaan='Mahasiswa' AND pasal!='288'"); echo $p2=mysqli_num_rows($datapositifkonjungsi2);

$ar=array(0.037089019538066,0.16097714433619,0.060188511347531,0.9,-0.0053884771494342,0.0040702398206162,0.23583508131697);
echo MAX($ar);
/*
mysqli_query($connect, "DELETE FROM datareal WHERE pasal='281' AND tahun='2015'");
mysqli_query($connect, "DELETE FROM datareal WHERE pasal='281' AND tahun='2016'");
mysqli_query($connect, "DELETE FROM datareal WHERE pasal='288(1)' AND tahun='2016'");
mysqli_query($connect, "DELETE FROM datareal WHERE pasal='288(2)' AND tahun='2016'");
mysqli_query($connect, "DELETE FROM datareal WHERE pasal='291(1)' AND tahun='2016'");
*/
//mysqli_query($connect, "UPDATE datareal SET jk='Pr' WHERE pekerjaan='IRT'");
//mysqli_query($connect, "UPDATE datareal SET pasal='281' WHERE kelompok='C' AND pekerjaan='Mahasiswa' AND jk ='Pr' AND tahun='2016' AND pasal='288(1)' LIMIT 6");
//mysqli_query($connect, "UPDATE datareal SET pasal='281' WHERE kelompok='B' AND pekerjaan='Mahasiswa' AND jk ='Lk' AND tahun='2016' AND pasal='288(1)' LIMIT 12");
//mysqli_query($connect,"UPDATE datareal SET jk='Pr' WHERE id_data='2689'");
?>