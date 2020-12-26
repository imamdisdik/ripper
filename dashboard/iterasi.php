<?php	
include "../koneksi.php";
mysqli_query($connect, "UPDATE datareal SET rule='1228' WHERE kelompok='A' AND tahun='2016' AND rule='0'");
$iterasi=mysqli_query($connect, "SELECT * FROM rule WHERE tahun='2016'");
while ($dataiterasi=mysqli_fetch_array($iterasi)) {
	$itr=$dataiterasi['iterasi'];
}
for ($i=1;$i<$itr+1;$i++) {
$a=mysqli_query($connect, "SELECT * FROM rule WHERE iterasi='$i' AND tahun='2016'");
echo "<table border='1'><caption> <b>ITERASI RULE KE - "; echo $i; echo "</b>
	<tr>
		<td>Nama Rule</td>
		<td>Gain</td>
		<td>Label</td>
		<td>Pasal</td>
		<td>Tahun</td>
	</tr>";
	while ($b=mysqli_fetch_array($a)) {
		echo "<tr>";
			echo "<td>"; echo $b['nama_rule']; echo "</td>";
			echo "<td>"; echo $b['gain']; echo "</td>";
			echo "<td>"; echo $b['label']; echo "</td>";
			echo "<td>"; echo $b['pasal']; echo "</td>";
			echo "<td>"; echo $b['tahun']; echo "</td>";
		echo "</tr>";
	$maksimal=mysqli_query($connect, "SELECT *, max(gain) as maksimal FROM rule WHERE iterasi='$i' AND  tahun='2016'");
	$d=mysqli_fetch_array($maksimal);
	$maksi=$d['maksimal'];
	$rule=mysqli_query($connect, "SELECT nama_rule,id_rule FROM rule WHERE iterasi='$i' AND gain='$maksi' AND  tahun='2016'");
	$e=mysqli_fetch_array($rule);
	}
	echo"<tr><td colspan='5'>RULE YANG DIDAPAT : "; echo $e['id_rule']; echo $e['nama_rule'];  echo " ===> Gain Terbaik : "; echo $maksi;
echo "</td><tr></table><br><br>";
}
$itr=array();
$iterasi=mysqli_query($connect, "SELECT iterasi FROM rule WHERE tahun='2016'");
while ($dataiterasi=mysqli_fetch_array($iterasi)) {
	$itr[]=$dataiterasi['iterasi'];
}
$sizeof=sizeof($itr);
	for ($i=1;$i<$sizeof;$i++) {
	$maksi=mysqli_query($connect, "SELECT max(gain) as maksimal FROM rule WHERE iterasi='$i' AND tahun='2016'");
	while ($m=mysqli_fetch_array($maksi)) {
		echo $maksimal=$m['maksimal'];
		$r=mysqli_query($connect, "SELECT * FROM rule WHERE gain='$maksimal' AND tahun='2016'");
		$data=mysqli_fetch_array($r);
			echo $data['nama_rule']; 
			echo $data['label']; 
			echo $data['pasal']; 
		echo "<br>";
	}
}
/*
for ($i=1;$i<$itr+1;$i++) {
$a=mysqli_query($connect, "SELECT * FROM rule WHERE iterasi='$i'");
echo "<table border='1'><caption> <b>ITERASI RULE KE - "; echo $i; echo "</b>
	<tr>
		<td>Nama Rule</td>
		<td>Gain</td>
		<td>Label</td>
		<td>Pasal</td>
		<td>Tahun</td>
	</tr>";
	while ($b=mysqli_fetch_array($a)) {
		echo "<tr>";
			echo "<td>"; echo $b['nama_rule']; echo "</td>";
			echo "<td>"; echo $b['gain']; echo "</td>";
			echo "<td>"; echo $b['label']; echo "</td>";
			echo "<td>"; echo $b['pasal']; echo "</td>";
			echo "<td>"; echo $b['tahun']; echo "</td>";
		echo "</tr>";
	$maksimal=mysqli_query($connect, "SELECT *, max(gain) as maksimal FROM rule WHERE iterasi='$i'");
	$d=mysqli_fetch_array($maksimal);
	$maksi=$d['maksimal'];
	$rule=mysqli_query($connect, "SELECT nama_rule,id_rule FROM rule WHERE iterasi='$i' AND gain='$maksi'");
	$e=mysqli_fetch_array($rule);
	}
	echo"<tr><td colspan='5'>RULE YANG DIDAPAT : "; echo $e['id_rule']; echo $e['nama_rule'];  echo " ===> Gain Terbaik : "; echo $maksi;
echo "</td><tr></table><br><br>";
}
*/
?>