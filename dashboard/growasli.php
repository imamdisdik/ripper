<div id="page-wrapper" class="page-wrapper-cls">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-warning">
                    <h3 align="center">ITERASI PROSES GROW PRUNE ASLI</h3>
                </div>
<?php
$log=0.43429448190325182765;
$tahun=$_GET['tahun'];
include "../koneksi.php";
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
mysqli_query($connect, "DELETE FROM ruleasli WHERE tahun='$tahun'");
mysqli_query($connect, "DELETE FROM ruleasli WHERE tahun='0'");
mysqli_query($connect, "DELETE FROM hasil_rule WHERE tahun='0'");
mysqli_query($connect, "DELETE FROM hasil_rule WHERE tahun='$tahun' AND label='Grow / Prune Asli'");
if ($tahun=='2015') {
    $batas=2;
}
else {
    $batas=6;
}
//PENGECEKAN PASAL
    $no=1;
    $cekpasalberlaku=mysqli_query($connect,"SELECT * FROM rule WHERE status='1' AND tahun='$tahun'");
    while($pasalberlaku=mysqli_fetch_array($cekpasalberlaku)) {
        $pasalbaru=$pasalberlaku['pasal'];
        $rule=$pasalberlaku['nama_rule'];
        $a=$pasalberlaku['data_positif'];
        $b=$pasalberlaku['data_negatif'];
        $c=$pasalberlaku['data_pasal_positif'];
        $d=$pasalberlaku['data_pasal_negatif'];
        $labelbaru="pasalke".$no;
            mysqli_query($connect,"INSERT INTO ruleasli (label,pasal,tahun,iterasi,nama_rule,status,data_positif,data_negatif,data_pasal_positif,data_pasal_negatif) values ('$labelbaru','$pasalbaru','$tahun','$no','$rule','1','$a','$b','$c','$d')");
        $no++;
    }
$iterasi=mysqli_query($connect, "SELECT * FROM ruleasli WHERE tahun='$tahun'");
while ($dataiterasi=mysqli_fetch_array($iterasi)) { $itr=$dataiterasi['iterasi']; }
for ($i=1;$i<$itr+1;$i++) {
echo "<div class='table-responsive col-md-6' style='height:300px;'><center><h4><b> ITERASI KE - "; echo $i; echo "</b></h3></center><hr>";
$cek2=mysqli_query($connect, "SELECT * FROM ruleasli WHERE iterasi='$i' AND tahun='$tahun'");
$datacek2=mysqli_fetch_array($cek2);echo "<h5><b>PROSES GROW RULEASLI ==> ";
echo $datacek2['nama_rule'];
echo "</b></h5><hr>";
//END GROW
echo "<center><h5><b>PERHITUNGAN NILAI MDL</b></h5></center><hr>";
                        echo "<table class='table table-hover'>
                            <thead>
                            <tr>
                                <th colspan='2'>Nama Rule</th>
                                <th>THEORY BITS</th>
                                <th>EXCEPTION BITS</th>
                                <th>MDL</th>
                            </tr></thead><tbody>";
                        $no=1;
                        $a=mysqli_query($connect, "SELECT * FROM ruleasli WHERE status='1' AND tahun='$tahun' AND iterasi='$i'");
                            while ($b=mysqli_fetch_array($a)) {
                                $no=1;
                            $label="Grow Asli";
                            $nama_rule=$b['nama_rule'];
                            $iterasi=$b['iterasi'];
                            $pasalbaru=$b['pasal'];
                            $hit=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun'");
                            $n=mysqli_num_rows($hit);
                            $r=$b['data_pasal_positif']+1+$b['data_pasal_negatif']+1;
                            $fp=$b['data_pasal_positif'];
                            $fn=$b['data_negatif']+1;
                            $log=0.69314718055994530942;
                            $k=3;
                            $km=2;
                            $p=$k/3; $ntot=$n-$r;
                            $explode=explode("^",$nama_rule);
                            $sizeof=sizeof($explode);
                            if ($sizeof==1) {
                                $theory=3.74;
                            }
                            if ($sizeof==2) {
                                $theory=4.73;
                            }
                            if ($sizeof==3) {
                                $theory=2;
                            }
                                echo "<tr>";
                                    echo "<td><a title='Jumlah data terpenuhi (r) : $r data, false negatif (fn) : $fn data, false positif (fp) : $fp data, (n - r) : $n - $r = $ntot data'>"; echo $b['nama_rule']; echo "</a></td>";
                                    echo "<td>==> "; echo $b['pasal']; echo "</td>";
                                    echo "<td>"; echo $theory; number_format(($k+(-$k)*$log*$p-(3-$k)*$log*(1-$p)),2); echo "</td>";
                                    echo "<td>"; echo $exception=number_format(($log*($r/$fp)+$log*($ntot/$fn)),2); echo "</td>"; 
                                    echo "<td>"; echo $mdl=number_format((0.5*$theory*$exception),2); echo "</td>"; 
                                echo "</tr>";
                                $no=1;
                        mysqli_query($connect,"INSERT INTO hasil_rule (nama_rule,theory,exception,mdl,iterasi,label,tahun,pasal) values ('$nama_rule','$theory','$exception','$mdl','$iterasi','Grow / Prune Asli','$tahun','$pasalbaru')");
                                $no++;
                        }
                           echo "</tbody></table>";

    echo "</div>";
}
?>
            </div>
        </div>
    </div>
</div>