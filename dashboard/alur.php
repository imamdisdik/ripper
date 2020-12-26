<div id="page-wrapper" class="page-wrapper-cls">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-warning">
                    <h3 align="center">GROW PRUNE DATA</h3>
                </div>
<?php
$log=0.43429448190325182765;
$tahun=$_GET['tahun'];
include "../koneksi.php";
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
mysqli_query($connect, "UPDATE datareal SET status='0' WHERE tahun='$tahun'");
mysqli_query($connect, "UPDATE pasal SET status='0' WHERE tahun='$tahun'");
mysqli_query($connect, "DELETE FROM rule WHERE tahun='$tahun'");
if ($tahun=='2015') {
    $batas=15;
}
else {
    $batas=15;
}
for ($ulang=1;$ulang<$batas;$ulang++) {
    $cekjumlahminimal=mysqli_query($connect, "SELECT MIN(jumlah) as minimal,pasal FROM pasal WHERE status='0' AND tahun='$tahun'");
    $datajumlahminimal=mysqli_fetch_array($cekjumlahminimal); 
    $cekpasal=mysqli_query($connect, "SELECT * FROM pasal WHERE jumlah='$datajumlahminimal[minimal]' AND tahun='$tahun' AND status='0'"); 
    $datapasal=mysqli_fetch_array($cekpasal); 
    $pasall=$datapasal['pasal'];
    $data=array();
    $a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND status='0' AND pasal='$pasall' ORDER BY RAND()");
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
    $a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND status='0' AND pasal='$pasall'");
    $positif=mysqli_num_rows($a);
    $a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND status='0' AND pasal!='$pasall'");
    $negatif=mysqli_num_rows($a); 
    $t0=(log($positif/($negatif+$positif)))*$log;
    //---------------------------------------------
    for ($i=0;$i<$size2;$i++) { $ps[]=$uniq2[$i]; }
    $impp=implode(",",$ps);
    $size=sizeof($ps);
    $expp=explode(",",$impp);for ($i=0;$i<$size;$i++) {
    if($expp[$i]=="Mahasiswa" || $expp[$i]=="Pelajar" || $expp[$i]=="IRT" || $expp[$i]=="DLL" || $expp[$i]=="Swasta" || $expp[$i]=="PNS") {
        $data=mysqli_query($connect,"SELECT * FROM datareal WHERE tahun='$tahun' AND pekerjaan='$expp[$i]' AND status='0' AND label='grow' AND pasal='$pasall'");
            $p1=mysqli_num_rows($data);
        $data=mysqli_query($connect,"SELECT * FROM datareal WHERE tahun='$tahun' AND pekerjaan='$expp[$i]' AND status='0' AND label='grow' AND pasal!='$pasall'");
            $n1=mysqli_num_rows($data);
            $R0=(log($p1/($p1+$n1)))*$log;
            $gain=($positif*($R0-$t0));
    }
    elseif($expp[$i]=="Lk" || $expp[$i]=="Pr") {
        $data=mysqli_query($connect,"SELECT * FROM datareal WHERE tahun='$tahun' AND jk='$expp[$i]' AND status='0' AND label='grow' AND pasal='$pasall'");
            $p1=mysqli_num_rows($data);
        $data=mysqli_query($connect,"SELECT * FROM datareal WHERE tahun='$tahun' AND jk='$expp[$i]' AND status='0' AND label='grow' AND pasal!='$pasall'");
            $n1=mysqli_num_rows($data);
            $R0=(log($p1/($p1+$n1)))*$log;
            $gain=($positif*($R0-$t0));
    }
    else { 
        $data=mysqli_query($connect,"SELECT * FROM datareal WHERE tahun='$tahun' AND kelompok='$expp[$i]' AND status='0' AND label='grow' AND pasal='$pasall'");
            $p1=mysqli_num_rows($data);
        $data=mysqli_query($connect,"SELECT * FROM datareal WHERE tahun='$tahun' AND kelompok='$expp[$i]' AND status='0' AND label='grow' AND pasal!='$pasall'");
            $n1=mysqli_num_rows($data);
            $R0=(log($p1/($p1+$n1)))*$log;
            $gain=($positif*($R0-$t0));
    }
   mysqli_query($connect, "INSERT INTO rule (nama_rule,gain,label,tahun,iterasi,pasal,variabel_item,data_positif,data_negatif,data_pasal_positif,data_pasal_negatif,r0) values ('$expp[$i]','$gain','grow','$tahun','$ulang','$pasall','$impp','$positif','$negatif','$p1','$n1','$pasall')");
}

//TAHAP KONJUNGSI 1
$maksimall=array();
$cekmaksimal=mysqli_query($connect, "SELECT * FROM rule WHERE iterasi='$ulang' AND tahun='$tahun'");
while ($datamaksimal=mysqli_fetch_array($cekmaksimal)) { $maksimall[]=$datamaksimal['gain']; }
$maksimal=MAX($maksimall); 
$cekpasalmaksimal=mysqli_query($connect, "SELECT * FROM rule WHERE gain='$maksimal'");
$datapasalmaksimal=mysqli_fetch_array($cekpasalmaksimal);
$rulemaksimal=$datapasalmaksimal['nama_rule'];
    //Pengecekan Terhadap Rute
    if ($rulemaksimal=="IRT" || $rulemaksimal=="PNS" || $rulemaksimal=="Mahasiswa" || $rulemaksimal=="Pelajar" || $rulemaksimal=="Swasta" || $rulemaksimal=="DLL") {
        $setting="pekerjaan";
    }
    if ($rulemaksimal=="Lk" || $rulemaksimal=="Pr") {
        $setting="jk";
    }
    if ($rulemaksimal=="A" || $rulemaksimal=="B" || $rulemaksimal=="C" || $rulemaksimal=="D") {
        $setting="kelompok";
    }
    //End

    //Perhitungan
    $datapositifkonjungsi1=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND status='0' AND $setting='$rulemaksimal' AND pasal='$pasall'"); $positif2=mysqli_num_rows($datapositifkonjungsi1);
    $datanegatifkonjungsi1=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND $setting='$rulemaksimal' AND pasal!='$pasall'"); $negatif2=mysqli_num_rows($datanegatifkonjungsi1);
    $t02=(log($positif2/($negatif2+$positif2)))*$log;
    //End
    $dataset=array();
    $datapositifkonjungsidata=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND status='0' AND pasal='$pasall'"); 
    while ($datapositif=mysqli_fetch_array($datapositifkonjungsidata)) {
        if ($rulemaksimal=="IRT" || $rulemaksimal=="PNS" || $rulemaksimal=="Mahasiswa" || $rulemaksimal=="Pelajar" || $rulemaksimal=="Swasta" || $rulemaksimal=="DLL") {
            $dataset[]=$datapositif['kelompok'];
            $dataset[]=$datapositif['jk'];
        }
        if ($rulemaksimal=="Lk" || $rulemaksimal=="Pr") {
            $dataset[]=$datapositif['kelompok'];
            $dataset[]=$datapositif['pekerjaan'];
        }
        if ($rulemaksimal=="A" || $rulemaksimal=="B" || $rulemaksimal=="C" || $rulemaksimal=="D") {
            $dataset[]=$datapositif['pekerjaan'];
            $dataset[]=$datapositif['jk'];
        }
    }
    $implode=implode(",",$dataset);
    $explode=explode(",",$implode);
    $datasetuniq=array_unique($explode);
    $implodedatauniq=implode(",",$datasetuniq);
    $explodedatauniq=explode(",",$implodedatauniq);
    $sizeuniq=sizeof($explodedatauniq);
    for ($konjungsi1=0;$konjungsi1<$sizeuniq;$konjungsi1++) {
        $kon[$konjungsi1]=$rulemaksimal."^".$explodedatauniq[$konjungsi1];
        if ($rulemaksimal=="IRT" || $rulemaksimal=="PNS" || $rulemaksimal=="Mahasiswa" || $rulemaksimal=="Pelajar" || $rulemaksimal=="Swasta" || $rulemaksimal=="DLL") {
                if($explodedatauniq[$konjungsi1]=="A" ||$explodedatauniq[$konjungsi1]=="B" ||$explodedatauniq[$konjungsi1]=="C" ||$explodedatauniq[$konjungsi1]=="D") {
                $datapositifkonjungsi1=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND status='0' AND pekerjaan='$rulemaksimal' AND kelompok='$explodedatauniq[$konjungsi1]' AND pasal='$pasall'"); $p2=mysqli_num_rows($datapositifkonjungsi1);
                $datanegatifkonjungsi1=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND pekerjaan='$rulemaksimal' AND kelompok='$explodedatauniq[$konjungsi1]' AND pasal!='$pasall'"); $n2=mysqli_num_rows($datanegatifkonjungsi1);
                    $R02=(log($p2/($p2+$n2)))*$log;
                    $gain2=($positif2*($R02-$t02));
                }
                if($explodedatauniq[$konjungsi1]=="Lk" ||$explodedatauniq[$konjungsi1]=="Pr") {
                $datapositifkonjungsi1=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND status='0' AND pekerjaan='$rulemaksimal' AND jk='$explodedatauniq[$konjungsi1]' AND pasal='$pasall'"); $p2=mysqli_num_rows($datapositifkonjungsi1);
                $datanegatifkonjungsi1=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND pekerjaan='$rulemaksimal' AND jk='$explodedatauniq[$konjungsi1]' AND pasal!='$pasall'"); $n2=mysqli_num_rows($datanegatifkonjungsi1);
                    $R02=(log($p2/($p2+$n2)))*$log;
                    $gain2=($positif2*($R02-$t02));
                }
        }
        if ($rulemaksimal=="Lk" || $rulemaksimal=="Pr") {
                if($explodedatauniq[$konjungsi1]=="A" ||$explodedatauniq[$konjungsi1]=="B" ||$explodedatauniq[$konjungsi1]=="C" ||$explodedatauniq[$konjungsi1]=="D") {
                $datapositifkonjungsi1=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND status='0' AND jk='$rulemaksimal' AND kelompok='$explodedatauniq[$konjungsi1]' AND pasal='$pasall'"); $p2=mysqli_num_rows($datapositifkonjungsi1);
                $datanegatifkonjungsi1=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND jk='$rulemaksimal' AND kelompok='$explodedatauniq[$konjungsi1]' AND pasal!='$pasall'"); $n2=mysqli_num_rows($datanegatifkonjungsi1);
                    $R02=(log($p2/($p2+$n2)))*$log;
                    $gain2=($positif2*($R02-$t02));
                }
                if($explodedatauniq[$konjungsi1]=="Swasta" || $explodedatauniq[$konjungsi1]=="Pelajar" || $explodedatauniq[$konjungsi1]=="Mahasiswa" || $explodedatauniq[$konjungsi1]=="IRT" || $explodedatauniq[$konjungsi1]=="PNS" || $explodedatauniq[$konjungsi1]=="DLL") {
                $datapositifkonjungsi1=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND status='0' AND jk='$rulemaksimal' AND pekerjaan='$explodedatauniq[$konjungsi1]' AND pasal='$pasall'"); $p2=mysqli_num_rows($datapositifkonjungsi1);
                $datanegatifkonjungsi1=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND jk='$rulemaksimal' AND pekerjaan='$explodedatauniq[$konjungsi1]' AND pasal!='$pasall'"); $n2=mysqli_num_rows($datanegatifkonjungsi1);
                    $R02=(log($p2/($p2+$n2)))*$log;
                    $gain2=($positif2*($R02-$t02));
                }                
        }
        if ($rulemaksimal=="A" || $rulemaksimal=="B" || $rulemaksimal=="C" || $rulemaksimal=="D") {
                if($explodedatauniq[$konjungsi1]=="Lk" || $explodedatauniq[$konjungsi1]=="Pr") {
                $datapositifkonjungsi1=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND status='0' AND kelompok='$rulemaksimal' AND jk='$explodedatauniq[$konjungsi1]' AND pasal='$pasall'"); $p2=mysqli_num_rows($datapositifkonjungsi1);
                $datanegatifkonjungsi1=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND kelompok='$rulemaksimal' AND jk='$explodedatauniq[$konjungsi1]' AND pasal!='$pasall'"); $n2=mysqli_num_rows($datanegatifkonjungsi1);
                    $R02=(log($p2/($p2+$n2)))*$log;
                    $gain2=($positif2*($R02-$t02));
                }
                if($explodedatauniq[$konjungsi1]=="Swasta" || $explodedatauniq[$konjungsi1]=="Pelajar" || $explodedatauniq[$konjungsi1]=="Mahasiswa" || $explodedatauniq[$konjungsi1]=="IRT" || $explodedatauniq[$konjungsi1]=="PNS" || $explodedatauniq[$konjungsi1]=="DLL") {
                $datapositifkonjungsi1=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND status='0' AND kelompok='$rulemaksimal' AND pekerjaan='$explodedatauniq[$konjungsi1]' AND pasal='$pasall'"); $p2=mysqli_num_rows($datapositifkonjungsi1);
                $datanegatifkonjungsi1=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND kelompok='$rulemaksimal' AND pekerjaan='$explodedatauniq[$konjungsi1]' AND pasal!='$pasall'"); $n2=mysqli_num_rows($datanegatifkonjungsi1);
                    $R02=(log($p2/($p2+$n2)))*$log;
                    $gain2=($positif2*($R02-$t02));
                }  
        }
    mysqli_query($connect, "INSERT INTO rule (nama_rule,gain,label,tahun,iterasi,pasal,variabel_item,data_positif,data_negatif,data_pasal_positif,data_pasal_negatif,r0) values ('$kon[$konjungsi1]','$gain2','growkonjungsi1','$tahun','$ulang','$pasall','$implodedatauniq','$positif2','$negatif2','$p2','$n2','$rulemaksimal')");
    }

//TAHAP KONJUNGSI 2

$maksimall=array();
$cekmaksimal=mysqli_query($connect, "SELECT * FROM rule WHERE iterasi='$ulang' AND tahun='$tahun' AND label='growkonjungsi1' AND gain!='NAN'");
while ($datamaksimal=mysqli_fetch_array($cekmaksimal)) { $maksimall[]=$datamaksimal['gain']; }
$maksimal=MAX($maksimall); 
$cekpasalmaksimal=mysqli_query($connect, "SELECT * FROM rule WHERE gain='$maksimal' AND label='growkonjungsi1'");
$datapasalmaksimal=mysqli_fetch_array($cekpasalmaksimal);
$rulemaksimal=$datapasalmaksimal['nama_rule'];
$explodek2=explode("^",$rulemaksimal);
$sizeexplodek2=sizeof($explodek2);
    $setting=array();
    for ($k2=0;$k2<$sizeexplodek2;$k2++) {
        if ($explodek2[$k2]=="IRT" || $explodek2[$k2]=="PNS" || $explodek2[$k2]=="Mahasiswa" || $explodek2[$k2]=="Pelajar" || $explodek2[$k2]=="Swasta" || $explodek2[$k2]=="DLL") {
            $setting[]="pekerjaan";
        }
        if ($explodek2[$k2]=="Lk" || $explodek2[$k2]=="Pr") {
            $setting[]="jk";
        }
        if ($explodek2[$k2]=="A" || $explodek2[$k2]=="B" || $explodek2[$k2]=="C" || $explodek2[$k2]=="D") {
            $setting[]="kelompok";
        }
    }
    //Perhitungan
    $datapositifkonjungsi2=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND status='0' AND $setting[0]='$explodek2[0]' AND $setting[1]='$explodek2[1]' AND pasal='$pasall'"); $positif2k=mysqli_num_rows($datapositifkonjungsi2);
    $datanegatifkonjungsi2=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND status='0' AND $setting[0]='$explodek2[0]' AND $setting[1]='$explodek2[1]' AND pasal!='$pasall'"); $negatif2k=mysqli_num_rows($datanegatifkonjungsi2);
    $t02k=(log($positif2k/($negatif2k+$positif2k)))*$log;
    //End
    $datakon=array();
    $datapositifkonjungsi2data=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND status='0' AND pasal='$pasall'"); 
    while ($datapositifkon=mysqli_fetch_array($datapositifkonjungsi2data)) {
        if ($setting[0]=='pekerjaan' && $setting[1]=='kelompok') {
            $datakon[]=$datapositifkon['jk'];
        }
        elseif ($setting[0]=='kelompok' && $setting[1]=='pekerjaan') {
            $datakon[]=$datapositifkon['jk'];
        }
        elseif ($setting[0]=='jk' && $setting[1]=='kelompok') {
            $datakon[]=$datapositifkon['pekerjaan'];
        }
        elseif ($setting[0]=='kelompok' && $setting[1]=='jk') {
            $datakon[]=$datapositifkon['pekerjaan'];
        }
        elseif ($setting[0]=='jk' && $setting[1]=='pekerjaan') {
            $datakon[]=$datapositifkon['kelompok'];
        }
        elseif ($setting[0]=='pekerjaan' && $setting[1]=='jk') {
            $datakon[]=$datapositifkon['kelompok'];
        }
    }
    $implode=implode(",",$datakon);
    $explode=explode(",",$implode);
    $datasetuniq=array_unique($explode);
    $implodedatauniq=implode(",",$datasetuniq);
    $explodedatauniq=explode(",",$implodedatauniq);
    $sizeuniq=sizeof($explodedatauniq);


    for ($konjungsi2=0;$konjungsi2<$sizeuniq;$konjungsi2++) {
        $konj[$konjungsi2]=$rulemaksimal."^".$explodedatauniq[$konjungsi2];
        if ($setting[0]=='pekerjaan' && $setting[1]=='kelompok') {
                $datapositifkonjungsi2=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND status='0' AND $setting[0]='$explodek2[0]' AND $setting[1]='$explodek2[1]' AND jk='$explodedatauniq[$konjungsi2]' AND pasal='$pasall'"); $p2k=mysqli_num_rows($datapositifkonjungsi2);
                $datanegatifkonjungsi2=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND status='0' AND label='grow' AND  $setting[0]='$explodek2[0]' AND $setting[1]='$explodek2[1]' AND jk='$explodedatauniq[$konjungsi2]' AND pasal!='$pasall'"); $n2k=mysqli_num_rows($datanegatifkonjungsi2);
                    $R02k=(log($p2k/($p2k+$n2k)))*$log;
                    $gain2k=($positif2k*($R02k-$t02k));
        }
        elseif ($setting[0]=='kelompok' && $setting[1]=='pekerjaan') {
                $datapositifkonjungsi2=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND status='0' AND label='grow' AND status='0' AND $setting[0]='$explodek2[0]' AND $setting[1]='$explodek2[1]' AND jk='$explodedatauniq[$konjungsi2]' AND pasal='$pasall'"); $p2k=mysqli_num_rows($datapositifkonjungsi2);
                $datanegatifkonjungsi2=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND status='0' AND label='grow' AND  $setting[0]='$explodek2[0]' AND $setting[1]='$explodek2[1]' AND jk='$explodedatauniq[$konjungsi2]' AND pasal!='$pasall'"); $n2k=mysqli_num_rows($datanegatifkonjungsi2);
                    $R02k=(log($p2k/($p2k+$n2k)))*$log;
                    $gain2k=($positif2k*($R02k-$t02k));
        }
        elseif ($setting[0]=='jk' && $setting[1]=='kelompok') {
                $datapositifkonjungsi2=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND status='0' AND label='grow' AND status='0' AND $setting[0]='$explodek2[0]' AND $setting[1]='$explodek2[1]' AND pekerjaan='$explodedatauniq[$konjungsi2]' AND pasal='$pasall'"); $p2k=mysqli_num_rows($datapositifkonjungsi2);
                $datanegatifkonjungsi2=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND status='0' AND label='grow' AND  $setting[0]='$explodek2[0]' AND $setting[1]='$explodek2[1]' AND pekerjaan='$explodedatauniq[$konjungsi2]' AND pasal!='$pasall'"); $n2k=mysqli_num_rows($datanegatifkonjungsi2);
                    $R02k=(log($p2k/($p2k+$n2k)))*$log;
                    $gain2k=($positif2k*($R02k-$t02k));
        }
        elseif ($setting[0]=='kelompok' && $setting[1]=='jk') {
                $datapositifkonjungsi2=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND status='0' AND label='grow' AND status='0' AND $setting[0]='$explodek2[0]' AND $setting[1]='$explodek2[1]' AND pekerjaan='$explodedatauniq[$konjungsi2]' AND pasal='$pasall'"); $p2k=mysqli_num_rows($datapositifkonjungsi2);
                $datanegatifkonjungsi2=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND  $setting[0]='$explodek2[0]' AND $setting[1]='$explodek2[1]' AND pekerjaan='$explodedatauniq[$konjungsi2]' AND pasal!='$pasall'"); $n2k=mysqli_num_rows($datanegatifkonjungsi2);
                    $R02k=(log($p2k/($p2k+$n2k)))*$log;
                    $gain2k=($positif2k*($R02k-$t02k));
        }
        elseif ($setting[0]=='jk' && $setting[1]=='pekerjaan') {
                $datapositifkonjungsi2=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND status='0' AND label='grow' AND $setting[0]='$explodek2[0]' AND $setting[1]='$explodek2[1]' AND kelompok='$explodedatauniq[$konjungsi2]' AND pasal='$pasall'"); $p2k=mysqli_num_rows($datapositifkonjungsi2);
                $datanegatifkonjungsi2=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND status='0' AND  $setting[0]='$explodek2[0]' AND $setting[1]='$explodek2[1]' AND kelompok='$explodedatauniq[$konjungsi2]' AND pasal!='$pasall'"); $n2k=mysqli_num_rows($datanegatifkonjungsi2);
                    $R02k=(log($p2k/($p2k+$n2k)))*$log;
                    $gain2k=($positif2k*($R02k-$t02k));
        }
        elseif ($setting[0]=='pekerjaan' && $setting[1]=='jk') {
                $datapositifkonjungsi2=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND status='0' AND label='grow' AND $setting[0]='$explodek2[0]' AND $setting[1]='$explodek2[1]' AND kelompok='$explodedatauniq[$konjungsi2]' AND pasal='$pasall'"); $p2k=mysqli_num_rows($datapositifkonjungsi2);
                $datanegatifkonjungsi2=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND status='0' AND  $setting[0]='$explodek2[0]' AND $setting[1]='$explodek2[1]' AND kelompok='$explodedatauniq[$konjungsi2]' AND pasal!='$pasall'"); $n2k=mysqli_num_rows($datanegatifkonjungsi2);
                    $R02k=(log($p2k/($p2k+$n2k)))*$log;
                    $gain2k=($positif2k*($R02k-$t02k));
        }
        mysqli_query($connect, "INSERT INTO rule (nama_rule,gain,label,tahun,iterasi,pasal,variabel_item,data_positif,data_negatif,data_pasal_positif,data_pasal_negatif,r0) values ('$konj[$konjungsi2]','$gain2k','growkonjungsi2','$tahun','$ulang','$pasall','$implodedatauniq','$positif2k','$negatif2k','$p2k','$n2k','$rulemaksimal')");
    }

    $cekrule=mysqli_query($connect, "SELECT * FROM rule WHERE iterasi='$ulang' AND tahun='$tahun' AND label='growkonjungsi2'");
    while ($datarule=mysqli_fetch_array($cekrule)) {
        $p=$datarule['data_pasal_positif'];
        $n=$datarule['data_pasal_negatif'];
        $id=$datarule['id_rule'];
        if ($p>=1 && $n==0) {
            //$hasil=mysqli_query($connect, "UPDATE rule SET status=1 WHERE id_rule='$id'");
        
//CEK PRUNE

$maksimall=array();   
$cekprune=mysqli_query($connect, "SELECT * FROM rule WHERE iterasi='$ulang' AND tahun='$tahun' AND label='growkonjungsi2' AND gain!='NAN' AND data_pasal_negatif='0' AND data_pasal_positif>0 LIMIT 1");
while ($datamaksimal=mysqli_fetch_array($cekprune)) { $maksimall[]=$datamaksimal['gain']; }
$prune=MAX($maksimall); 
$cekpasalprune=mysqli_query($connect, "SELECT * FROM rule WHERE gain='$prune' AND label='growkonjungsi2' AND iterasi='$ulang' AND tahun='$tahun' AND data_pasal_negatif='0' AND data_pasal_positif>0  LIMIT 1");
$datapasalprune=mysqli_fetch_array($cekpasalprune);
$ruleprune=$datapasalprune['nama_rule'];
$explodeprune=explode("^",$ruleprune);
$sizeexplodeprune=sizeof($explodeprune);
    $setting=array();
    for ($pr=0;$pr<$sizeexplodeprune;$pr++) {
        if ($explodeprune[$pr]=="IRT" || $explodeprune[$pr]=="PNS" || $explodeprune[$pr]=="Mahasiswa" || $explodeprune[$pr]=="Pelajar" || $explodeprune[$pr]=="Swasta" || $explodeprune[$pr]=="DLL") {
            $setting[]="pekerjaan";
        }
        if ($explodeprune[$pr]=="Lk" || $explodeprune[$pr]=="Pr") {
            $setting[]="jk";
        }
        if ($explodeprune[$pr]=="A" || $explodeprune[$pr]=="B" || $explodeprune[$pr]=="C" || $explodeprune[$pr]=="D") {
            $setting[]="kelompok";
        }
    }
    $datapositifprune=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='prune' AND status='0' AND $setting[0]='$explodeprune[0]' AND  $setting[1]='$explodeprune[1]' AND  $setting[2]='$explodeprune[2]' AND pasal='$pasall'"); $positifprune=mysqli_num_rows($datapositifprune);
    $datanegatifprune=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='prune' AND status='0' AND $setting[0]='$explodeprune[0]' AND  $setting[1]='$explodeprune[1]' AND  $setting[2]='$explodeprune[2]' AND pasal!='$pasall'"); $negatifprune=mysqli_num_rows($datanegatifprune);
    $jumlahnilaiprune=($positifprune-$negatifprune)/($positifprune+$negatifprune);
   /* if ($positifprune>$negatifprune) {
        $positifprune=0;
        $jumlahnilaiprune="-INF";
    }*/
        mysqli_query($connect, "INSERT INTO rule (nama_rule,gain,label,tahun,iterasi,pasal,variabel_item,data_positif,data_negatif,data_pasal_positif,data_pasal_negatif,r0) values ('$ruleprune','$jumlahnilaiprune','prune','$tahun','$ulang','$pasall','$ruleprune','$positifprune','$negatifprune','$positifprune','$negatifprune','$ruleprune')");
    

    $sizeexplodeprune=$sizeexplodeprune-1;
    $ex=array();
    $setting=array();
    for ($pr=0;$pr<$sizeexplodeprune;$pr++) {
        if ($explodeprune[$pr]=="IRT" || $explodeprune[$pr]=="PNS" || $explodeprune[$pr]=="Mahasiswa" || $explodeprune[$pr]=="Pelajar" || $explodeprune[$pr]=="Swasta" || $explodeprune[$pr]=="DLL") {
            $setting[]="pekerjaan";
            $ex[]=$explodeprune[$pr];
        }
        if ($explodeprune[$pr]=="Lk" || $explodeprune[$pr]=="Pr") {
            $setting[]="jk";
            $ex[]=$explodeprune[$pr];
        }
        if ($explodeprune[$pr]=="A" || $explodeprune[$pr]=="B" || $explodeprune[$pr]=="C" || $explodeprune[$pr]=="D") {
            $setting[]="kelompok";
            $ex[]=$explodeprune[$pr];
        }
    }
    $im=implode("^",$ex);
    $datapositifprune=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='prune' AND status='0' AND $setting[0]='$explodeprune[0]' AND  $setting[1]='$explodeprune[1]' AND pasal='$pasall'"); $positifprune=mysqli_num_rows($datapositifprune);
    $datanegatifprune=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='prune' AND status='0' AND $setting[0]='$explodeprune[0]' AND  $setting[1]='$explodeprune[1]' AND pasal!='$pasall'"); $negatifprune=mysqli_num_rows($datanegatifprune);
    $jumlahnilaiprune=($positifprune-$negatifprune)/($positifprune+$negatifprune);
    /*if ($positifprune>$negatifprune) {
        $positifprune=0;
        $jumlahnilaiprune="-INF";
    }*/
        mysqli_query($connect, "INSERT INTO rule (nama_rule,gain,label,tahun,iterasi,pasal,variabel_item,data_positif,data_negatif,data_pasal_positif,data_pasal_negatif,r0) values ('$im','$jumlahnilaiprune','prune','$tahun','$ulang','$pasall','$ruleprune','$positifprune','$negatifprune','$positifprune','$negatifprune','$ruleprune')");


    $ex=array();
    $setting=array();
    for ($pr=0;$pr<1;$pr++) {
        if ($explodeprune[$pr]=="IRT" || $explodeprune[$pr]=="PNS" || $explodeprune[$pr]=="Mahasiswa" || $explodeprune[$pr]=="Pelajar" || $explodeprune[$pr]=="Swasta" || $explodeprune[$pr]=="DLL") {
            $setting[]="pekerjaan";
            $ex[]=$explodeprune[$pr];
        }
        if ($explodeprune[$pr]=="Lk" || $explodeprune[$pr]=="Pr") {
            $setting[]="jk";
            $ex[]=$explodeprune[$pr];
        }
        if ($explodeprune[$pr]=="A" || $explodeprune[$pr]=="B" || $explodeprune[$pr]=="C" || $explodeprune[$pr]=="D") {
            $setting[]="kelompok";
            $ex[]=$explodeprune[$pr];
        }
    }
    $im=implode(",",$ex);
    $datapositifprune=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='prune' AND status='0' AND $setting[0]='$explodeprune[0]' AND pasal='$pasall'"); $positifprune=mysqli_num_rows($datapositifprune);
    $datanegatifprune=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='prune' AND status='0' AND $setting[0]='$explodeprune[0]' AND pasal!='$pasall'"); $negatifprune=mysqli_num_rows($datanegatifprune);

    $jumlahnilaiprune=($positifprune-$negatifprune)/($positifprune+$negatifprune);
    /*if ($positifprune>$negatifprune) {
        $positifprune=0;
        $jumlahnilaiprune="-INF";
    }*/
        mysqli_query($connect, "INSERT INTO rule (nama_rule,gain,label,tahun,iterasi,pasal,variabel_item,data_positif,data_negatif,data_pasal_positif,data_pasal_negatif,r0) values ('$im','$jumlahnilaiprune','prune','$tahun','$ulang','$pasall','$ruleprune','$positifprune','$negatifprune','$positifprune','$negatifprune','$ruleprune')");
}
    }


    //CEK
    $gainprune=array();
    $cekrule=mysqli_query($connect, "SELECT * FROM rule WHERE iterasi='$ulang' AND tahun='$tahun' AND label='prune' AND gain!='NAN' AND gain>0");
    while ($datarule=mysqli_fetch_array($cekrule)) {
        $p=$datarule['data_pasal_positif'];
        $n=$datarule['data_pasal_negatif'];
        $id=$datarule['id_rule'];
        $gainprune[]=$datarule['gain'];
        $maxprune=MAX($gainprune);
        }
        mysqli_query($connect, "UPDATE rule SET status='1' WHERE id_rule='$id' AND gain='$maxprune' AND iterasi='$ulang' AND tahun='$tahun' AND label='prune'");
        $cekprunerule=mysqli_query($connect,"SELECT * FROM rule WHERE status='1' AND tahun='$tahun' AND label='prune' AND iterasi='$ulang'");
        $hitung=mysqli_num_rows($cekprunerule);
        if ($hitung==0) {
        $cekrule=mysqli_query($connect, "SELECT * FROM rule WHERE iterasi='$ulang' AND tahun='$tahun' AND label='growkonjungsi2' AND gain!='NAN'");
        while ($datarule=mysqli_fetch_array($cekrule)) {
            $p=$datarule['data_pasal_positif'];
            $n=$datarule['data_pasal_negatif'];
            $id=$datarule['id_rule'];
            if($p>0 && $n==0) {
        mysqli_query($connect, "UPDATE rule SET status='1' WHERE id_rule='$id' AND iterasi='$ulang' AND tahun='$tahun' AND label='growkonjungsi2'");
            }
        }
    }

        mysqli_query($connect, "UPDATE datareal SET status='1' WHERE pasal='$pasall' AND tahun='$tahun'");
        mysqli_query($connect, "UPDATE pasal SET status='1' WHERE pasal='$pasall' AND tahun='$tahun'");
   
}
$iterasi=mysqli_query($connect, "SELECT * FROM rule WHERE tahun='$tahun'");
while ($dataiterasi=mysqli_fetch_array($iterasi)) { $itr=$dataiterasi['iterasi']; }
for ($i=1;$i<$itr+1;$i++) {
echo "<div class='table-responsive col-md-6' style='height:450px;'><center><h3><b> ITERASI KE - "; echo $i; echo "</b></h3></center><hr>";
echo "<h4><b>PROSES GROW</b></h4><hr>";
$a=mysqli_query($connect, "SELECT * FROM rule WHERE iterasi='$i' AND tahun='$tahun' AND label='grow'");
$data=mysqli_fetch_array($a);
echo "<center> R0 : "; echo $data['r0']; 
echo " == Jumlah Data Positif : "; echo $data['data_positif']; echo " data";
echo " == Jumlah Data Negatif : "; echo $data['data_negatif']; echo " data";
echo "<br> Variabel Item : ";  echo $data['variabel_item']; 
echo "</center><hr>";
echo "<table class='table table-hover'>
    <thead>
    <tr>
        <th>Nama Rule</th>
        <th>Jml Data (+)</th>
        <th>Jml Data (-)</th>
        <th>Gain</th>
    </tr></thead><tbody>";
$aa=mysqli_query($connect, "SELECT * FROM rule WHERE iterasi='$i' AND tahun='$tahun' AND label='grow'");
    while ($b=mysqli_fetch_array($aa)) {
        echo "<tr>";
            echo "<td>"; echo $b['nama_rule']; echo "</td>";
            echo "<td>"; echo $b['data_pasal_positif']; echo "</td>";
            echo "<td>"; echo $b['data_pasal_negatif']; echo "</td>";
            echo "<td>"; echo $b['gain']; echo "</td>";
        echo "</tr>";
    }
   echo "</tbody></table>";
//END GROW
//GROW KONJUNGSI 1  
$cek=mysqli_query($connect, "SELECT * FROM rule WHERE iterasi='$i' AND tahun='$tahun' AND label='growkonjungsi1'");
$datacek=mysqli_fetch_array($cek);
echo "<hr><center> R0 : "; echo $datacek['r0']; 
echo " == Jumlah Data Positif : "; echo $datacek['data_positif']; echo " data";
echo " == Jumlah Data Negatif : "; echo $datacek['data_negatif']; echo " data";
echo "<br> Variabel Item : ";  echo $datacek['variabel_item']; 
echo "</center><hr>";
echo "<table class='table table-hover'>
    <thead>
    <tr>
        <th>Nama Rule</th>
        <th>Jml Data (+)</th>
        <th>Jml Data (-)</th>
        <th>Gain</th>
    </tr></thead><tbody>";
$aa=mysqli_query($connect, "SELECT * FROM rule WHERE iterasi='$i' AND tahun='$tahun' AND label='growkonjungsi1'");
while ($dataa=mysqli_fetch_array($aa)) {
    $r0=$dataa['r0'];
    $n1=$dataa['data_negatif'];
    $p1=$dataa['data_positif']; 
    $v=$dataa['variabel_item'];
    $nama_rule=$dataa['nama_rule'];
        echo "<tr>";
            echo "<td>"; echo $dataa['nama_rule']; echo "</td>";
            echo "<td>"; echo $dataa['data_pasal_positif']; echo "</td>";
            echo "<td>"; echo $dataa['data_pasal_negatif']; echo "</td>";
            echo "<td>"; echo $dataa['gain']; echo "</td>";
        echo "</tr>";
}
   echo "</tbody></table>";
//END GROW
//GROW KONJUNGSI 2
$cek=mysqli_query($connect, "SELECT * FROM rule WHERE iterasi='$i' AND tahun='$tahun' AND label='growkonjungsi2'");
$datacek=mysqli_fetch_array($cek);
echo "<hr><center> R0 : "; echo $datacek['r0']; 
echo " == Jumlah Data Positif : "; echo $datacek['data_positif']; echo " data";
echo " == Jumlah Data Negatif : "; echo $datacek['data_negatif']; echo " data";
echo "<br> Variabel Item : ";  echo $datacek['variabel_item']; 
echo "</center><hr>";
echo "<table class='table table-hover'>
    <thead>
    <tr>
        <th>Nama Rule</th>
        <th>Jml Data (+)</th>
        <th>Jml Data (-)</th>
        <th>Gain</th>
    </tr></thead><tbody>";
$aa=mysqli_query($connect, "SELECT * FROM rule WHERE iterasi='$i' AND tahun='$tahun' AND label='growkonjungsi2'");
while ($dataa=mysqli_fetch_array($aa)) {
    $r0=$dataa['r0'];
    $n1=$dataa['data_negatif'];
    $p1=$dataa['data_positif']; 
    $v=$dataa['variabel_item'];
    $nama_rule=$dataa['nama_rule'];
        echo "<tr>";
            echo "<td>"; echo $dataa['nama_rule']; echo "</td>";
            echo "<td>"; echo $dataa['data_pasal_positif']; echo "</td>";
            echo "<td>"; echo $dataa['data_pasal_negatif']; echo "</td>";
            echo "<td>"; echo $dataa['gain']; echo "</td>";
        echo "</tr>";
}
   echo "</tbody></table><hr>";
//END GROW
//PRUNE

$cek=mysqli_query($connect, "SELECT * FROM rule WHERE iterasi='$i' AND tahun='$tahun' AND label='prune'");
$hit=mysqli_num_rows($cek);
$datacek=mysqli_fetch_array($cek);
if($hit>0) {
echo "<h4><b>PROSES PRUNE ==> Rule : </b>"; echo $datacek['r0'];
echo "</h4><hr>";
echo "<table class='table table-hover'>
    <thead>
    <tr>
        <th>Nama Rule</th>
        <th>Jml Data (+)</th>
        <th>Jml Data (-)</th>
        <th>Nilai V</th>
    </tr></thead><tbody>";
$aa=mysqli_query($connect, "SELECT * FROM rule WHERE iterasi='$i' AND tahun='$tahun' AND label='prune'");
while ($dataa=mysqli_fetch_array($aa)) {
    $r0=$dataa['r0'];
    $n1=$dataa['data_negatif'];
    $p1=$dataa['data_positif']; 
    $v=$dataa['variabel_item'];
    $nama_rule=$dataa['nama_rule'];
        echo "<tr>";
            echo "<td>"; echo $dataa['nama_rule']; echo "</td>";
            echo "<td>"; echo $dataa['data_pasal_positif']; echo "</td>";
            echo "<td>"; echo $dataa['data_pasal_negatif']; echo "</td>";
            echo "<td>"; echo $dataa['gain']; echo "</td>";
        echo "</tr>";
}
   echo "</tbody></table><hr>";
}
echo "<center>";

    $cekrule=mysqli_query($connect, "SELECT * FROM rule WHERE iterasi='$i' AND tahun='$tahun' AND status='1'");
    $datarule=mysqli_fetch_array($cekrule);
        $p=$datarule['data_pasal_positif'];
        $n=$datarule['data_pasal_negatif'];
        $id=$datarule['id_rule'];
        if ($p>$n) {
        echo "<h3><b>DITEMUKAN ADANYA RULE !</b></h3><br><br>";
        }
        else {
        echo "<h3><b>Tidak Ada Rule Yang Ditemukan, Lanjut Iterasi Berikutnya !</b></h3><br><br>";
        }
    
    if ($i>$batas-2) {
    echo "<h3>Proses Terhenti!<br>Ini Adalah Iterasi Terakhir</h3><b>(Jumlah Data Negatif Selanjutnya = 0)</b><br><br>";
}


    echo "</div>";
}
?>
            </div>
        </div>
    </div>
</div>