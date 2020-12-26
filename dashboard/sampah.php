<div id="page-wrapper" class="page-wrapper-cls">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-warning">
                    <h3 align="center">GROW PRUNE DATA</h3>
                </div>
<!--MASUK KE TAHAP ALGORITMA-->
<?php
$log=0.43429448190325182765;
$tahun=$_GET['tahun'];
include "../koneksi.php";
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
mysqli_query($connect, "UPDATE datareal SET status='0' WHERE tahun='$tahun'");
mysqli_query($connect, "UPDATE pasal SET status='0' WHERE tahun='$tahun'");
mysqli_query($connect, "DELETE FROM rule WHERE tahun='$tahun'");

for ($ulang=1;$ulang<3;$ulang++) {

    //PENGECEKAN PASAL TERENDAH
    $cekjumlahminimal=mysqli_query($connect, "SELECT MIN(jumlah) as minimal,pasal FROM pasal WHERE status='0' AND tahun='$tahun'");
    $datajumlahminimal=mysqli_fetch_array($cekjumlahminimal); 
    $cekpasal=mysqli_query($connect, "SELECT * FROM pasal WHERE jumlah='$datajumlahminimal[minimal]' AND tahun='$tahun' AND status='0'"); 
    $datapasal=mysqli_fetch_array($cekpasal); 
    //END

    //MENDAPATKAN PASAL TERENDAH
    $pasalproses=$datapasal['pasal'];
    //END

    //MENYIMPAN VARIABEL DI ITEM POSITIF
    $datavariabel=array();
    $cek_variabel=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='GROW' AND status='0' AND pasal='$pasalproses' ORDER BY RAND()");
        while($variabel=mysqli_fetch_array($cek_variabel)) {
            $datavariabel[]=$variabel['jk'];
            $datavariabel[]=$variabel['pekerjaan'];
            $datavariabel[]=$variabel['kelompok'];
        }
    $implodevariabel=implode(",",$datavariabel);
    $explodevariabel=explode(",",$implodevariabel);
    $variabeluniq=array_unique($explodevariabel);
    $implodevariabel2=implode(",", $variabeluniq);
    $explodevariabel2=explode(",", $implodevariabel2);
    $variabeluniq2=array_unique($explodevariabel2);
    $sizevariabel=sizeof($variabeluniq2);
    //END

    //VARIABEL YANG AKAN DIHITUNG NANTINYA
    $variabelitem=array();
    //END


//TAHAP GROW TANPA KONJUNGSI
    //PERHITUNGAN DATA PASAL POSITIF DENGAN DATA KESELURUHAN 
    $cek_p0=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='GROW' AND status='0' AND pasal='$pasalproses'"); $p0=mysqli_num_rows($cek_p0);
    $cek_n0=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='GROW' AND status='0' AND pasal!='$pasalproses'"); $n0=mysqli_num_rows($cek_n0); 
    $nilai_p0=(log($p0/($p0+$n0)))*$log;
    //END

    //PERHITUNGAN DATA PASAL POSITIF DENGAN ITEM VARIABEL
    for ($i=0;$i<$sizevariabel;$i++) { $variabelitem[]=$variabeluniq2[$i]; }
    $implodevariabelitem=implode(",",$variabelitem);
    $sizevariabelitem=sizeof($variabelitem);

    $explodevariabelitem=explode(",",$implodevariabelitem);
    for ($i=0;$i<$sizevariabelitem;$i++) {

        //KONDISI JIKA VARIABEL BERUPA PEKERJAAN
        if($explodevariabelitem[$i]=="Mahasiswa" || $explodevariabelitem[$i]=="Pelajar" || $explodevariabelitem[$i]=="IRT" || $explodevariabelitem[$i]=="DLL" || $explodevariabelitem[$i]=="Swasta" || $explodevariabelitem[$i]=="PNS") {
            $cek_p1=mysqli_query($connect,"SELECT * FROM datareal WHERE tahun='$tahun' AND pekerjaan='$explodevariabelitem[$i]' AND status='0' AND label='GROW' AND pasal='$pasalproses'"); $p1=mysqli_num_rows($cek_p1);
            $cek_n1=mysqli_query($connect,"SELECT * FROM datareal WHERE tahun='$tahun' AND pekerjaan='$explodevariabelitem[$i]' AND status='0' AND label='GROW' AND pasal!='$pasalproses'"); $n1=mysqli_num_rows($cek_n1);
                $nilai_p1=(log($p1/($p1+$n1)))*$log;
                $gain=($p0*($nilai_p1-$nilai_p0));
        }
        //END
        //KONDISI JIKA VARIABEL BERUPA JK
        elseif($explodevariabelitem[$i]=="Lk" || $explodevariabelitem[$i]=="Pr") {
            $cek_p1=mysqli_query($connect,"SELECT * FROM datareal WHERE tahun='$tahun' AND jk='$explodevariabelitem[$i]' AND status='0' AND label='GROW' AND pasal='$pasalproses'"); $p1=mysqli_num_rows($cek_p1);
            $cek_n1=mysqli_query($connect,"SELECT * FROM datareal WHERE tahun='$tahun' AND jk='$explodevariabelitem[$i]' AND status='0' AND label='GROW' AND pasal!='$pasalproses'"); $n1=mysqli_num_rows($cek_n1);
                $nilai_p1=(log($p1/($p1+$n1)))*$log;
                $gain=($p0*($nilai_p1-$nilai_p0));
        }
        //END
        //KONDISI JIKA VARIABEL BERUPA KELOMPOK
        else { 
            $cek_p1=mysqli_query($connect,"SELECT * FROM datareal WHERE tahun='$tahun' AND kelompok='$explodevariabelitem[$i]' AND status='0' AND label='GROW' AND pasal='$pasalproses'"); $p1=mysqli_num_rows($cek_p1);
            $cek_n1=mysqli_query($connect,"SELECT * FROM datareal WHERE tahun='$tahun' AND kelompok='$explodevariabelitem[$i]' AND status='0' AND label='GROW' AND pasal!='$pasalproses'"); $n1=mysqli_num_rows($cek_n1);
                $nilai_p1=(log($p1/($p1+$n1)))*$log;
                $gain=($p0*($nilai_p1-$nilai_p0));
        }
        //END

        //MEMASUKKAN PERHITUNGAN GROW TANPA KONJUNGSI KE DATABASE
        mysqli_query($connect, "INSERT INTO rule (nama_rule,gain,label,tahun,iterasi,pasal,variabel_item,data_positif,data_negatif,data_pasal_positif,data_pasal_negatif,r0) values ('$explodevariabelitem[$i]','$gain','GROW','$tahun','$ulang','$pasalproses','$implodevariabelitem','$p0','$n0','$p1','$n1','$pasalproses')");
        //END
    }
//END GROW TANPA KONJUNGSI


//TAHAP GROW DENGAN KONJUNGSI 1
    //CEK GAIN MAKSIMAL
    $gainmaksimal=array();
    $cekgainmaksimal=mysqli_query($connect, "SELECT * FROM rule WHERE iterasi='$ulang' AND tahun='$tahun' AND label='GROW'");
    while ($datagainmaksimal=mysqli_fetch_array($cekgainmaksimal)) { $gainmaksimal[]=$datagainmaksimal['gain']; }
    $maksimal=MAX($gainmaksimal);
    //END

    //CEK VARIABEL YANG TIDAK TERMASUK KEDALAM DATA VARIABEL MAKSIMAL 
    $cekrulemaksimal=mysqli_query($connect, "SELECT * FROM rule WHERE gain='$maksimal'");
    $datarulemaksimal=mysqli_fetch_array($cekrulemaksimal);
    $rulemaksimal=$datarulemaksimal['nama_rule'];
        if ($rulemaksimal=="IRT" || $rulemaksimal=="PNS" || $rulemaksimal=="Mahasiswa" || $rulemaksimal=="Pelajar" || $rulemaksimal=="Swasta" || $rulemaksimal=="DLL") {
            $setting="pekerjaan";
        }
        if ($rulemaksimal=="Lk" || $rulemaksimal=="Pr") {
            $setting="jk";
        }
        if ($rulemaksimal=="A" || $rulemaksimal=="B" || $rulemaksimal=="C" || $rulemaksimal=="D") {
            $setting="kelompok";
        }
    //END

    //PERHITUNGAN DATA PASAL POSITIF DENGAN DATA KESELURUHAN 
    $cek_konjungsi1_p0=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='GROW' AND status='0' AND $setting='$rulemaksimal' AND pasal='$pasalproses'"); $konjungsi1_p0=mysqli_num_rows($cek_konjungsi1_p0);
    $cek_konjungsi1_n0=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='GROW' AND $setting='$rulemaksimal' AND pasal!='$pasalproses'"); $konjungsi1_n0=mysqli_num_rows($cek_konjungsi1_n0);
    $nilai_konjungsi1_p0=(log($konjungsi1_p0/($konjungsi1_p0+$konjungsi1_n0)))*$log;
    //END

    //MENYIMPAN VARIABEL KONJUNGSI DI ITEM POSITIF
    $variabelitemkonjungsi1=array();
    $cekdatapositifkonjungsi1=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='GROW' AND status='0' AND pasal='$pasalproses'"); 
    while ($datapositifkonjungsi1=mysqli_fetch_array($cekdatapositifkonjungsi1)) {
        if ($rulemaksimal=="IRT" || $rulemaksimal=="PNS" || $rulemaksimal=="Mahasiswa" || $rulemaksimal=="Pelajar" || $rulemaksimal=="Swasta" || $rulemaksimal=="DLL") {
            $variabelitemkonjungsi1[]=$datapositifkonjungsi1['kelompok'];
            $variabelitemkonjungsi1[]=$datapositifkonjungsi1['jk'];
        }
        if ($rulemaksimal=="Lk" || $rulemaksimal=="Pr") {
            $variabelitemkonjungsi1[]=$datapositifkonjungsi1['kelompok'];
            $variabelitemkonjungsi1[]=$datapositifkonjungsi1['pekerjaan'];
        }
        if ($rulemaksimal=="A" || $rulemaksimal=="B" || $rulemaksimal=="C" || $rulemaksimal=="D") {
            $variabelitemkonjungsi1[]=$datapositifkonjungsi1['pekerjaan'];
            $variabelitemkonjungsi1[]=$datapositifkonjungsi1['jk'];
        }
    }
    $implode=implode(",",$variabelitemkonjungsi1);
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

//END GROW DENGAN KONJUNGSI 1
//OUTPUT R0
echo "<div class='table-responsive col-md-6' style='height:450px;'><center><h3><b> ITERASI KE - "; echo $ulang; echo "</b></h3></center><hr>";
echo "<h4><b>PROSES GROW</b></h4><hr>";
$a=mysqli_query($connect, "SELECT * FROM rule WHERE iterasi='$ulang' AND tahun='$tahun' AND label='GROW'");
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
$aa=mysqli_query($connect, "SELECT * FROM rule WHERE iterasi='$ulang' AND tahun='$tahun' AND label='GROW'");
    while ($b=mysqli_fetch_array($aa)) {
        echo "<tr>";
            echo "<td>"; echo $b['nama_rule']; echo "</td>";
            echo "<td>"; echo $b['data_pasal_positif']; echo "</td>";
            echo "<td>"; echo $b['data_pasal_negatif']; echo "</td>";
            echo "<td>"; echo $b['gain']; echo "</td>";
        echo "</tr>";
    }
   echo "</tbody></table></div>";


}
?>
            </div>
        </div>
    </div>
</div>