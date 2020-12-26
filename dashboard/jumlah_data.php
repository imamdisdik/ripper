<?php
include "../koneksi.php";
$a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='2015' ORDER BY pasal");
echo "<b>JUMLAH DATA TAHUN 2015 : "; echo $row=mysqli_num_rows($a); echo "</b></br>";
$no=1;
$pasal=array();
while ($b=mysqli_fetch_array($a)) {	$pasal[]=$b['pasal']; $no++; }
$implode=implode(",",$pasal);
$explode=explode(",",$implode);
$uniq=array_unique($explode);
$size=sizeof($uniq);
$imp=implode(",", $uniq);
$exp=explode(",", $imp);
$uniq2=array_unique($exp);
$size2=sizeof($uniq2);
$size2;
$ps=array();
for ($i=0;$i<$size2;$i++) { $ps[]=$uniq2[$i]; }
$impp=implode(",",$ps);
$expp=explode(",",$impp);
echo "<br>";
for ($i=0;$i<$size2;$i++) {
	$s=mysqli_query($connect, "SELECT count(pasal) as num FROM datareal WHERE pasal='$expp[$i]' AND tahun='2015'");
	while ($n=mysqli_fetch_array($s)) {
	echo "<b> Pasal : "; echo $ps[$i]; echo "</b> ";
	echo $angka=$n['num']; echo " Data <br>";
	$a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='2015' AND jk='Lk' AND pasal='$expp[$i]'  AND tahun='2015'  ORDER BY pasal");
	echo "Lk "; echo $row=mysqli_num_rows($a);
	$a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='2015' AND jk='Pr' AND pasal='$expp[$i]'  AND tahun='2015' ORDER BY pasal");
	echo " | Pr "; echo $row=mysqli_num_rows($a);
	echo "<br>-------------------------------";
	$a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='2015' AND kelompok='A' AND pasal='$expp[$i]' ORDER BY pasal");
	echo "<br> A "; echo $row=mysqli_num_rows($a);
	$a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='2015' AND kelompok='B' AND pasal='$expp[$i]'ORDER BY pasal");
	echo "<br> B "; echo $row=mysqli_num_rows($a);
	$a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='2015' AND kelompok='C' AND pasal='$expp[$i]' ORDER BY pasal");
	echo "<br> C "; echo $row=mysqli_num_rows($a);
	$a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='2015' AND kelompok='D' AND pasal='$expp[$i]' ORDER BY pasal");
	echo "<br> D "; echo $row=mysqli_num_rows($a);
	echo "<br>-------------------------------";
	$a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='2015' AND pekerjaan='Swasta' AND pasal='$expp[$i]' ORDER BY pasal");
	echo "<br> Swasta "; echo $row=mysqli_num_rows($a);
	$a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='2015' AND pekerjaan='Pelajar' AND pasal='$expp[$i]'  ORDER BY pasal");
	echo "<br> Pelajar "; echo $row=mysqli_num_rows($a);
	$a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='2015' AND pekerjaan='Mahasiswa' AND pasal='$expp[$i]' ORDER BY pasal"); 
	echo "<br> Mahasiswa "; echo $rowmi=mysqli_num_rows($a);
	$a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='2015' AND pekerjaan='IRT' AND pasal='$expp[$i]' ORDER BY pasal");
	echo "<br> IRT "; echo $row=mysqli_num_rows($a);
	$a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='2015' AND pekerjaan='DLL' AND pasal='$expp[$i]' ORDER BY pasal");
	echo "<br> DLL "; echo $row=mysqli_num_rows($a);
	$a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='2015' AND pekerjaan='PNS' AND pasal='$expp[$i]' ORDER BY pasal");
	echo "<br> PNS "; echo $row=mysqli_num_rows($a);
	echo "<br><hr>";
	}
}


$a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='2016' ORDER BY pasal");
echo "<b>JUMLAH DATA TAHUN 2016 : "; echo $row=mysqli_num_rows($a); echo "</b></br>";
$no=1;
$pasal=array();
while ($b=mysqli_fetch_array($a)) {
	//echo $no;
	$pasal[]=$b['pasal'];
	//echo "<br>";
	$no++;
}
$implode=implode(",",$pasal);
$explode=explode(",",$implode);
$uniq=array_unique($explode);
$size=sizeof($uniq);
$imp=implode(",", $uniq);
$exp=explode(",", $imp);
$uniq2=array_unique($exp);
$size2=sizeof($uniq2);
$size2;
$ps=array();
for ($i=0;$i<$size2;$i++) {
//echo "<br>";
$ps[]=$uniq2[$i];
}
$impp=implode(",",$ps);
//mysqli_query($connect, "UPDATE datareal SET jk='lk' WHERE jk='. Lk' AND tahun='2016'");

$expp=explode(",",$impp);
$impp;
echo "<br>";
for ($i=0;$i<$size2;$i++) {
	$s=mysqli_query($connect, "SELECT count(pasal) as num FROM datareal WHERE pasal='$expp[$i]' AND tahun='2016'");
	while ($n=mysqli_fetch_array($s)) {
	echo "<b> Pasal : "; echo $ps[$i]; echo "</b> ";
	echo $angka=$n['num']; echo " Data <br>";
	$a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='2016' AND jk='Lk' AND pasal='$expp[$i]' ORDER BY pasal");
	echo "Lk "; echo $row=mysqli_num_rows($a);
	$a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='2016' AND jk='Pr' AND pasal='$expp[$i]' ORDER BY pasal");
	echo " | Pr "; echo $row=mysqli_num_rows($a);
	echo "<br>-------------------------------";
	$a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='2016' AND kelompok='A' AND pasal='$expp[$i]' ORDER BY pasal");
	echo "<br> A "; echo $row=mysqli_num_rows($a);
	$a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='2016' AND kelompok='B' AND pasal='$expp[$i]'ORDER BY pasal");
	echo "<br> B "; echo $row=mysqli_num_rows($a);
	$a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='2016' AND kelompok='C' AND pasal='$expp[$i]' ORDER BY pasal");
	echo "<br> C "; echo $row=mysqli_num_rows($a);
	$a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='2016' AND kelompok='D' AND pasal='$expp[$i]' ORDER BY pasal");
	echo "<br> D "; echo $row=mysqli_num_rows($a);
	echo "<br>-------------------------------";
	$a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='2016' AND pekerjaan='Swasta' AND pasal='$expp[$i]' ORDER BY pasal");
	echo "<br> Swasta "; echo $row=mysqli_num_rows($a);
	$a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='2016' AND pekerjaan='Pelajar' AND pasal='$expp[$i]'  ORDER BY pasal");
	echo "<br> Pelajar "; echo $row=mysqli_num_rows($a);
	$a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='2016' AND pekerjaan='Mahasiswa' AND pasal='$expp[$i]' ORDER BY pasal"); 
	echo "<br> Mahasiswa "; echo $rowmi=mysqli_num_rows($a);
	$a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='2016' AND pekerjaan='IRT' AND pasal='$expp[$i]' ORDER BY pasal");
	echo "<br> IRT "; echo $row=mysqli_num_rows($a);
	$a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='2016' AND pekerjaan='DLL' AND pasal='$expp[$i]' ORDER BY pasal"); 
	echo "<br> DLL "; echo $row=mysqli_num_rows($a);
	$a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='2016' AND pekerjaan='PNS' AND pasal='$expp[$i]' ORDER BY pasal");
	echo "<br> PNS "; echo $row=mysqli_num_rows($a);
	echo "<br><hr>";
	}
}
?>