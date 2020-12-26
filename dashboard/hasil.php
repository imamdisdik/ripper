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
            <center><h1 class="page-head-line">Hasil Grow Prune Data Analisa</h1></center>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-success">
                    <h3 align="center">Data Analisa | <?php echo "Tahun : "; echo $thn; echo " (Sebanyak "; echo $row; echo " Data)"; ?></h3></div>
                    <?php
                    	$no=1;
						$a=mysqli_query($connect, "SELECT * FROM rule WHERE status='1' AND tahun='$thn'");
						echo "<table class='table table-hover'>
						    <thead>
						    <tr>
						        <th><center>No</center></th>
						        <th colspan='2'>Nama Rule</th>
						        <th>Label</th>
						        <th>Nilai Gain</th>
						        <th><center>Pada Proses Iterasi Ke - </center></th>
						    </tr></thead><tbody>";
						    while ($b=mysqli_fetch_array($a)) {
						        echo "<tr>";
                                    echo "<td><center>"; echo $no; echo "</center></td>";
						            echo "<td>"; echo $b['nama_rule']; echo "</td>";
						            echo "<td>==> "; echo $b['pasal']; echo "</td>";
						            echo "<td>"; echo $b['label']; echo "</td>";
						            echo "<td>"; echo $b['gain']; echo "</td>";
						            echo "<td><center>"; echo $b['iterasi']; echo "</center></td>";
						        echo "</tr>";
						    $no++; }
						   echo "</tbody></table>";
					?>
                    <!--<center><h2>Tidak Ditemukan Adanya Rule<br>Pada Proses Grow Prune Data Ini</h2></center>-->
            </div>
        </div>
    </div>
</div>