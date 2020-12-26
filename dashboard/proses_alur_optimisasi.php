<div id="page-wrapper" class="page-wrapper-cls">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-warning">
                    <h3 align="center">OPTIMISASI DATA</h3>
                </div>
<?php
$log=0.43429448190325182765;
$tahun=$_GET['thn'];
if ($tahun=='2015') {
    $batas=16;
}
else {
    $batas=19;
}
include "../koneksi.php";
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
$iterasi=mysqli_query($connect, "SELECT * FROM rule WHERE tahun='$tahun'");
while ($dataiterasi=mysqli_fetch_array($iterasi)) { $itr=$dataiterasi['iterasi']; }
for ($i=1;$i<$itr+1;$i++) {
echo "<div class='table-responsive col-md-6' style='height:400px;'><center><h4><b> ITERASI KE - "; echo $i; echo "</b></h4></center><hr>";
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
    while ($b=mysqli_fetch_array($a)) {
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
echo "</tbody></table><hr><h3>Replacment Data</h3>";
echo "<table class='table table-hover'>
    <thead>
    <tr>
        <th>Nama Rule</th>
        <th>Jml Data (+)</th>
        <th>Jml Data (-)</th>
        <th>MDL</th>
    </tr></thead><tbody></tbody></table>";
echo "</tbody></table><hr><h3>Revisi Data</h3>";
echo "<table class='table table-hover'>
    <thead>
    <tr>
        <th>Nama Rule</th>
        <th>Jml Data (+)</th>
        <th>Jml Data (-)</th>
        <th>MDL</th>
    </tr></thead><tbody></tbody></table><hr><center>";
    if ($i>$batas-2) {
    echo "<h3>Proses Terhenti!<br>Ini Adalah Iterasi Terakhir</h3><b>(Jumlah Data Negatif Selanjutnya = 0)</b><br><br>";
    }else {
    echo "<h3>Tidak Ada Rule Yang Ditemukan, Lanjut Iterasi Berikutnya !</h3><br><br>";
    }
    echo "</div>";
}
?>
            </div>
        </div>
    </div>
</div>