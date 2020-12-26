<?php
include "../koneksi.php";
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
mysqli_query($connect, "UPDATE datareal SET status='0'");
mysqli_query($connect, "UPDATE pasal SET status='0'");
$tahun='2015';
$no=1;                                
$itr=array();
mysqli_query($connect, "DELETE FROM rule");
mysqli_query($connect, "DELETE FROM pasal");
$a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' ORDER BY pasal");
echo "<b>JUMLAH DATA TAHUN $tahun : "; echo $row=mysqli_num_rows($a); echo "</b></br>";
$pasal=array();
while ($b=mysqli_fetch_array($a)) { $pasal[]=$b['pasal']; }
    $implode=implode(",",$pasal);
    $explode=explode(",",$implode);
    $uniq=array_unique($explode);
    $size=sizeof($uniq);
    $imp=implode(",", $uniq);
    $exp=explode(",", $imp);
    $uniq2=array_unique($exp);
    $size2=sizeof($uniq2);
$ps=array();
for ($i=0;$i<$size2;$i++) { $ps[]=$uniq2[$i]; }
$impp=implode(",",$ps);
$expp=explode(",",$impp);   
    for ($i=0;$i<$size2;$i++) {
        $s=mysqli_query($connect, "SELECT count(pasal) as num FROM datareal WHERE pasal='$expp[$i]' AND tahun='$tahun'");
            while ($n=mysqli_fetch_array($s)) {
            echo "<b> Pasal : "; echo $pasalbaru=$ps[$i]; echo "</b> ";
            echo $angka=$n['num']; $batas=ceil($angka*0.9);echo " Data <br>";
        }
mysqli_query($connect, "INSERT INTO pasal (pasal,jumlah,tahun) values ('$expp[$i]','$angka','$tahun')");
    }

for ($ulang=1;$ulang<20;$ulang++) {
	echo "<h1>ITERASI : "; echo $ulang; echo "</h1>";
	$cekjumlahminimal=mysqli_query($connect, "SELECT MIN(jumlah) as minimal,pasal FROM pasal WHERE status='0'");
	$datajumlahminimal=mysqli_fetch_array($cekjumlahminimal); 
	$cekpasal=mysqli_query($connect, "SELECT * FROM pasal WHERE jumlah='$datajumlahminimal[minimal]'"); 
	$datapasal=mysqli_fetch_array($cekpasal); 

	//CEK DATA GROW PRUNE
	/*
	mysqli_query($connect, "UPDATE datareal SET label=''");
	$pasal=array();
	$a=mysqli_query($connect, "SELECT * FROM datareal WHERE status='0' AND tahun='$tahun' ORDER BY RAND()");
	while ($b=mysqli_fetch_array($a)) { $pasal[]=$b['pasal']; }
		$uniq=array_unique($pasal);
		$implode=implode(",", $uniq);
		$explode=explode(",",$implode);
		$size=sizeof($uniq);
		$count=array();
		$pasal_data=array();
			for ($i=0;$i<$size;$i++) {
				$pasal=mysqli_query($connect, "SELECT *, count(pasal) AS psl FROM datareal WHERE pasal='$explode[$i]' AND tahun='$tahun'");
				while ($pasaltes=mysqli_fetch_array($pasal)) {
					$pasal_data[]=$pasaltes['pasal'];
					$count[]=ceil($pasaltes['psl']*0.66666666);
					mysqli_query($connect, "UPDATE datareal SET label='grow' WHERE pasal='$pasal_data[$i]' AND tahun='$tahun' LIMIT $count[$i]");
				}
			}
			mysqli_query($connect, "UPDATE datareal SET label='prune' WHERE label=''"); */
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
for ($i=0;$i<$size;$i++) {
	echo $expp[$i];
	if($expp[$i]=="Mahasiswa" || $expp[$i]=="Pelajar" || $expp[$i]=="IRT" || $expp[$i]=="DLL" || $expp[$i]=="Swasta" || $expp[$i]=="PNS") {
		$data=mysqli_query($connect,"SELECT * FROM datareal WHERE tahun='$tahun' AND pekerjaan='$expp[$i]' AND label='grow' AND pasal='$pasall'");
			$p1=mysqli_num_rows($data);
		$data=mysqli_query($connect,"SELECT * FROM datareal WHERE tahun='$tahun' AND pekerjaan='$expp[$i]' AND label='grow' AND pasal!='$pasall'");
			$n1=mysqli_num_rows($data);
			$R0=log($p1/($p1+$n1));
			echo $gain=($positif*($R0-$t0))/10;
	}
	elseif($expp[$i]=="Lk" || $expp[$i]=="Pr") {
		$data=mysqli_query($connect,"SELECT * FROM datareal WHERE tahun='$tahun' AND jk='$expp[$i]' AND label='grow' AND pasal='$pasall'");
			$p1=mysqli_num_rows($data);
		$data=mysqli_query($connect,"SELECT * FROM datareal WHERE tahun='$tahun' AND jk='$expp[$i]' AND label='grow' AND pasal!='$pasall'");
			$n1=mysqli_num_rows($data);
			$R0=log($p1/($p1+$n1));
			echo $gain=($positif*($R0-$t0))/10;
	}
	else { 
		$data=mysqli_query($connect,"SELECT * FROM datareal WHERE tahun='$tahun' AND kelompok='$expp[$i]' AND label='grow' AND pasal='$pasall'");
			$p1=mysqli_num_rows($data);
		$data=mysqli_query($connect,"SELECT * FROM datareal WHERE tahun='$tahun' AND kelompok='$expp[$i]' AND label='grow' AND pasal!='$pasall'");
			$n1=mysqli_num_rows($data);
			$R0=log($p1/($p1+$n1));
			echo $gain=($positif*($R0-$t0))/10;
	}
	mysqli_query($connect, "INSERT INTO rule (nama_rule,gain,label,tahun,iterasi,pasal) values ('$expp[$i]','$gain','grow','$tahun','$ulang','$pasall')");
	echo "<br>";
}
echo "<hr>";
	$dataset=array();
	$cekmaksimal=mysqli_query($connect, "SELECT *, MAX(gain) as maksimal FROM rule WHERE iterasi='$ulang' AND gain!='NAN'");
	$datamaksimal=mysqli_fetch_array($cekmaksimal); $maksimal=$datamaksimal['maksimal'];
	$cekmaksimal=mysqli_query($connect, "SELECT * FROM rule WHERE iterasi='$ulang'");
	while ($datamaksimal=mysqli_fetch_array($cekmaksimal)) {
		$dataset[]=$datamaksimal['nama_rule']; }
	$cekpasalmaksimal=mysqli_query($connect, "SELECT * FROM rule WHERE gain='$maksimal'");
	$datapasalmaksimal=mysqli_fetch_array($cekpasalmaksimal);
	echo "Rule Dengan Gain Maksimal : "; echo $rulemaksimal=$datapasalmaksimal['nama_rule'];
	$implodedata=implode(",",$dataset);
	$explodedata=explode(",",$implodedata);
	$uniqdata=array_unique($explodedata);
	$implodelagi=implode(",",$uniqdata);
	$explodelagi=explode(",",$implodelagi);
	$sizeof=sizeof($explodelagi);
	$a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND status='0' AND jk='$rulemaksimal' OR kelompok='$rulemaksimal' OR pekerjaan='$rulemaksimal' AND pasal='$pasall'"); $positif=mysqli_num_rows($a);
	$a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND status='0' AND  jk='$rulemaksimal' OR kelompok='$rulemaksimal' OR pekerjaan='$rulemaksimal' AND pasal!='$pasall'"); $negatif=mysqli_num_rows($a);
	echo "<br>NILAI GAIN : ";
	echo $t0=log($positif/($negatif+$positif));
	echo "<hr>";
	for ($i=0; $i<$sizeof;$i++) {
	    echo $kon[$i]=$rulemaksimal."^".$explodelagi[$i];
	    if($rulemaksimal=="Mahasiswa" || $rulemaksimal=="Pelajar" || $rulemaksimal=="IRT" || $rulemaksimal=="DLL" || $rulemaksimal=="PNS" || $rulemaksimal=="Swasta") {
	        $data1=mysqli_query($connect,"SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND status='0' AND  jk='$explodelagi[$i]' OR kelompok='$explodelagi[$i]' OR pekerjaan='$rulemaksimal' AND pasal='$pasall'");
	            $p1=mysqli_num_rows($data1);
	        $data=mysqli_query($connect,"SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND status='0' AND  jk='$explodelagi[$i]' OR kelompok='$explodelagi[$i]' OR pekerjaan='$rulemaksimal' AND pasal!='$pasall'");
	            $n1=mysqli_num_rows($data);
	            $R0=log($p1/($p1+$n1));
	            echo $gain=$positif*($R0-$t0);
	            echo "<br>";
	    }
	    if($rulemaksimal=="Lk" || $rulemaksimal=="Pr") {
	        $data1=mysqli_query($connect,"SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND status='0' AND  jk='rulemaksimal' OR kelompok='$explodelagi[$i]' OR pekerjaan='$explodelagi[$i]' AND pasal='$pasall'");
	            $p1=mysqli_num_rows($data1);
	        $data=mysqli_query($connect,"SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND status='0' AND  jk='rulemaksimal' OR kelompok='$explodelagi[$i]' OR pekerjaan='$explodelagi[$i]' AND pasal!='$pasall'");
	            $n1=mysqli_num_rows($data);
	            $R0=log($p1/($p1+$n1));
	            echo $gain=$positif*($R0-$t0);
	            echo "<br>";	
	    }
	    if($rulemaksimal=="A" || $rulemaksimal=="B" || $rulemaksimal=="C" || $rulemaksimal=="D") {
	        $data1=mysqli_query($connect,"SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND status='0' AND  jk='explodelagi[$i]' OR kelompok='$rulemaksimal' OR pekerjaan='$explodelagi[$i]' AND pasal='$pasall'");
	            $p1=mysqli_num_rows($data1);
	        $data=mysqli_query($connect,"SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND status='0' AND  jk='explodelagi[$i]' OR kelompok='$rulemaksimal' OR pekerjaan='$explodelagi[$i]' AND pasal!='$pasall'");
	            $n1=mysqli_num_rows($data);
	            $R0=log($p1/($p1+$n1));
	            echo $gain=$positif*($R0-$t0);
	            echo "<br>";	
	    }

	mysqli_query($connect, "INSERT INTO rule (nama_rule,gain,label,tahun,iterasi,pasal) values ('$kon[$i]','$gain','grow konjungsi1','$tahun','$ulang','$pasall')");
	mysqli_query($connect, "DELETE FROM rule WHERE gain='0'");
	}
echo "<hr>";
$datakonjungsi=array();
$rulekonjungsi=array();
$cekmaksimal2=mysqli_query($connect, "SELECT *, MAX(gain) as maksimal2 FROM rule WHERE iterasi='$ulang' AND gain!='NAN' AND label='grow konjungsi1'");
$datamaksimal2=mysqli_fetch_array($cekmaksimal2); $maksimal2=$datamaksimal2['maksimal2']; 
	$cekmaksimal2=mysqli_query($connect, "SELECT * FROM rule WHERE iterasi='$ulang' AND label='grow konjungsi1'");
	while ($datamaksimal2=mysqli_fetch_array($cekmaksimal2)) {
		$datakonjungsi[]=$datamaksimal2['nama_rule']; }
	$cekpasalmaksimal2=mysqli_query($connect, "SELECT * FROM rule WHERE gain='$maksimal2'");
	$datapasalmaksimal2=mysqli_fetch_array($cekpasalmaksimal2);
	echo "Rule Dengan Gain Maksimal : "; echo $rulemaksimal2=$datapasalmaksimal2['nama_rule']; $rulekonjungsi[]=$datapasalmaksimal2['nama_rule'];
$implodekonjungsi=implode("^",$rulekonjungsi);
$explodekonjungsi=explode("^",$implodekonjungsi);
$konjungsi2=array();
for ($nomorexplode=0;$nomorexplode<2;$nomorexplode++) {
	$konjungsi2[]=$explodekonjungsi[$nomorexplode];
	$konjungsi=$explodekonjungsi[$nomorexplode];
	$a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND status='0' AND jk='$konjungsi' OR kelompok='$konjungsi' OR pekerjaan='$konjungsi' AND pasal='$pasall'"); $positif=mysqli_num_rows($a);
	$a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND status='0' AND  jk='$konjungsi' OR kelompok='$konjungsi' OR pekerjaan='$konjungsi' AND pasal!='$pasall'"); $negatif=mysqli_num_rows($a);
}
	echo "<br>NILAI GAIN : ";
	echo $t0=log($positif/($negatif+$positif));
	$implodedata2=implode("^",$datakonjungsi);
	$explodedata2=explode("^",$implodedata2);
	$uniqdata2=array_unique($explodedata2);
	$implodelagi2=implode("^",$uniqdata2);
	$explodelagi2=explode("^",$implodelagi2);
	$sizeof2=sizeof($explodelagi2);
	echo "<br>";
	for ($ii=0;$ii<$sizeof2;$ii++) {
	    echo $kondisi[$ii]=$rulemaksimal2."^".$explodelagi2[$ii];
	   	//echo "<br>";
	    if($explodelagi2[$ii]=="Lk" || $explodelagi2[$ii]=="Pr") {
	        $data1=mysqli_query($connect,"SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND status='0' AND  jk='$explodelagi2[$ii]' OR kelompok='$konjungsi2[$ii]' OR pekerjaan='$konjungsi2[$ii] AND pasal='$pasall'");
	            $p1=mysqli_num_rows($data1);
	        $data=mysqli_query($connect,"SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND status='0' AND  jk='$explodelagi2[$ii]' OR kelompok='$konjungsi2[$ii]' OR pekerjaan='$konjungsi2[$ii]' AND pasal!='$pasall'");
	            $n1=mysqli_num_rows($data);
	            $R0=log($p1/($p1+$n1));
	            echo $gain=$positif*($R0-$t0);
	            echo "<br>"; 
	    }
	    if($explodelagi2[$ii]=="A" || $explodelagi2[$ii]=="B" || $explodelagi2[$ii]=="C" || $explodelagi2[$ii]=="D") {
	        $data1=mysqli_query($connect,"SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND status='0' AND  jk='$konjungsi2[$ii]' OR kelompok='$explodelagi2[$ii]' OR pekerjaan='$konjungsi2[$ii]' AND pasal='$pasall'");
	            $p1=mysqli_num_rows($data1);
	        $data=mysqli_query($connect,"SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND status='0' AND  jk='$konjungsi2[$ii]' OR kelompok='$explodelagi2[$ii]' OR pekerjaan='$konjungsi2[$ii]' AND pasal!='$pasall'");
	            $n1=mysqli_num_rows($data);
	            $R0=log($p1/($p1+$n1));
	            echo $gain=$positif*($R0-$t0);
	            echo "<br>"; 
	    }
	    if($explodelagi2[$ii]=="Mahasiswa" || $explodelagi2[$ii]=="Swasta" || $explodelagi2[$ii]=="Pelajar" || $explodelagi2[$ii]=="IRT" || $explodelagi2[$ii]=="PNS") {
	        $data1=mysqli_query($connect,"SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND status='0' AND  jk='$konjungsi2[$ii]' OR kelompok='$explodelagi2[$ii]' OR pekerjaan='$konjungsi2[$ii]' AND pasal='$pasall'");
	            $p1=mysqli_num_rows($data1);
	        $data=mysqli_query($connect,"SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND status='0' AND  jk='$konjungsi2[$ii]' OR kelompok='$explodelagi2[$ii]' OR pekerjaan='$konjungsi2[$ii]' AND pasal!='$pasall'");
	            $n1=mysqli_num_rows($data);
	            $R0=log($p1/($p1+$n1));
	            echo $gain=$positif*($R0-$t0);
	            echo "<br>"; 
	    }

	$label="grow konjungsi2";
	if (($explodelagi2[$ii])==($konjungsi2[0]) || ($explodelagi2[$ii])==($konjungsi2[1]) ) {
	    	$label="hapus";
	    }
	mysqli_query($connect, "INSERT INTO rule (nama_rule,gain,label,tahun,iterasi,pasal) values ('$kondisi[$ii]','$gain','$label','$tahun','$ulang','$pasall')");
	mysqli_query($connect, "DELETE FROM rule WHERE gain='0' OR label='hapus'");
	}

	//CEK dengan data negatif
	$a=mysqli_query($connect, "SELECT * from datareal WHERE kelompok='C' AND pekerjaan='IRT' AND status='0' AND label='grow' AND tahun='$tahun' AND pasal='$pasall'");
	echo $hit1=mysqli_num_rows($a);
	$aa=mysqli_query($connect, "SELECT * from datareal WHERE kelompok='C' AND pekerjaan='IRT' AND status='0' AND label='grow' AND tahun='$tahun' AND pasal!='$pasall'");
	echo $hit2=mysqli_num_rows($aa);

	if ($hit1<=$hit2) {
		echo "<h3>Lanjut kepasal berikutnya</h3>";
		mysqli_query($connect, "UPDATE datareal SET status='1' WHERE pasal='$pasall' AND tahun='$tahun'");
		mysqli_query($connect, "UPDATE pasal SET status='1' WHERE pasal='$pasall' AND tahun='$tahun'");
	}
	else {
		echo "<h3>Terdapat Rule Disini Beb</h3>";
	}
}

/*
$dataset=array();
$a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='2015'");
echo mysqli_num_rows($a);
while ($b=mysqli_fetch_array($a)) {
	$dataset[]=$b['jk'];
	$dataset[]=$b['pekerjaan'];
	$dataset[]=$b['kelompok'];
	//echo "<br>";
}
	$implodedata=implode(",",$dataset);
	$size=sizeof($dataset);
	$explodedata=explode(",",$implodedata);
	$uniqdata=array_unique($explodedata);
	echo $implodelagi=implode(",",$uniqdata);
	$explodelagi=explode(",",$implodelagi);
	for ($i=0;$i<$size;$i++) {
	//mysqli_query($connect, "UPDATE datareal SET jk='Lk' WHERE id_data='$dataset[$i]' AND tahun='2016'");
	}
*/
?>