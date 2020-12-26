<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
$thn=$_GET['thn'];
$a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$thn'");
$row=mysqli_num_rows($a);
?>
<div id="page-wrapper" class="page-wrapper-cls">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
            <center><h1 class="page-head-line">Evaluasi Analisa Ripper</h1></center>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-success">
                    <h3 align="center">Data Analisa | <?php echo "Tahun : "; echo $thn; echo " (Sebanyak "; echo $row; echo " Data)"; ?></h3></div>
                    <?php
                        $no=1;
                        $a=mysqli_query($connect, "SELECT * FROM hasil_rule WHERE status='1' AND tahun='$thn'");
                        $hitung=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$thn'");
                        $hitungdata=mysqli_num_rows($hitung);
                        echo "<table class='table table-hover'>
                            <thead>
                            <tr>
                                <th><center>No</center></th>
                                <th colspan='2'>Nama Rule</th>
                                <th>Support</th>
                                <th>Confidance</th>
                                <th><center>Pada Proses </center></th>
                            </tr></thead><tbody>";
                            while ($b=mysqli_fetch_array($a)) {
                                echo "<tr>";
                                    echo "<td><center>"; echo $no; echo "</center></td>";
                                    echo "<td>"; echo $b['nama_rule']; echo "</td>";
                                    echo "<td>==> "; echo $pasall=$b['pasal']; echo "</td>";
$rulemaksimal=$b['nama_rule'];                        
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
    if ($sizeexplodek2=='3') {
    $data=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$thn' AND $setting[0]='$explodek2[0]' AND $setting[1]='$explodek2[1]' AND $setting[2]='$explodek2[2]' AND pasal='$pasall'"); $positif=mysqli_num_rows($data);
    $dataa=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$thn' AND $setting[0]='$explodek2[0]' AND $setting[1]='$explodek2[1]' AND $setting[2]='$explodek2[2]' AND pasal!='$pasall'"); $negatif=mysqli_num_rows($dataa);
    $dataaa=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$thn' AND $setting[0]='$explodek2[0]' AND $setting[1]='$explodek2[1]' AND $setting[2]='$explodek2[2]'"); $seluruh=mysqli_num_rows($dataaa);
    }
    if ($sizeexplodek2=='2') {
    $data=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$thn' AND $setting[0]='$explodek2[0]' AND $setting[1]='$explodek2[1]' AND pasal='$pasall'"); $positif=mysqli_num_rows($data);
    $dataa=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$thn' AND $setting[0]='$explodek2[0]' AND $setting[1]='$explodek2[1]' AND pasal!='$pasall'"); $negatif=mysqli_num_rows($dataa);
    $dataaa=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$thn' AND $setting[0]='$explodek2[0]' AND $setting[1]='$explodek2[1]'"); $seluruh=mysqli_num_rows($dataaa);
    }
    if ($sizeexplodek2=='1') {
    $data=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$thn' AND $setting[0]='$explodek2[0]' AND pasal='$pasall'"); $positif=mysqli_num_rows($data);
    $dataa=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$thn' AND $setting[0]='$explodek2[0]' AND pasal!='$pasall'"); $negatif=mysqli_num_rows($dataa);
    $dataaa=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$thn' AND $setting[0]='$explodek2[0]'"); $seluruh=mysqli_num_rows($dataaa);
    }


                                    echo "<td>"; echo "<a title='Jumlah Data Positif : $positif, Jumlah Data Keseluruhan : $hitungdata'>"; echo number_format(($positif*100/$hitungdata),2); echo " % </a></td>";
                                    echo "<td>"; echo "<a title='Jumlah Data Positif : $positif, Jumlah Data Negatif : $negatif'>"; 
                                    echo number_format(($positif*100/($seluruh)),2); echo " % </td>";
                                    echo "<td><center>Prune / Grow Asli</center></td>";
                                echo "</tr>";
                            $no++; }
                           echo "</tbody></table>";
                    ?>
                    <!--<center><h2>Tidak Ditemukan Adanya Rule<br>Tidak Dapat Melakukan Perhitungan Support dan Confidance</h2></center> -->
            </div>
        </div>
    </div>
</div>