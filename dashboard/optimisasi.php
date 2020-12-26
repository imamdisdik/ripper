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
            <center><h1 class="page-head-line">Hasil Rule Melalui Proses Optimisasi</h1></center>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-success">
                    <h3 align="center">Data Analisa | <?php echo "Tahun : "; echo $thn; echo " (Sebanyak "; echo $row; echo " Data)"; ?></h3>
                </div>
                <hr>
            <div class="table-responsive col-md-4">
                <div class="alert alert-warning">
                    <h5 align="center"><b>RULE YANG DITEMUKAN PADA PROSES GROW PRUNE ASLI</b><hr><a href="index.php?menu=growasli&tahun=<?php echo $thn; ?>" target="_BLANK" class="btn btn-sm btn-primary"> Lihat Proses Iterasi</a></h5>
                </div>
                    <?php
                        $no=1;
                        $a=mysqli_query($connect, "SELECT * FROM rule WHERE status='1' AND tahun='$thn'");
                        echo "<table class='table table-hover'>
                            <thead>
                            <tr>
                                <th><center>No</center></th>
                                <th colspan='2'>Nama Rule</th>
                            </tr></thead><tbody>";
                            while ($b=mysqli_fetch_array($a)) {
                                echo "<tr>";
                                    echo "<td><center>"; echo $no; echo "</center></td>";
                                    echo "<td>"; echo $b['nama_rule']; echo "</td>";
                                    echo "<td>==> "; echo $b['pasal']; echo "</td>";
                                echo "</tr>";
                            $no++; }
                           echo "</tbody></table>";
                    ?>
                <hr></div>
            <div class="table-responsive col-md-4">
                <div class="alert alert-warning">
                    <h5 align="center"><b>RULE YANG DITEMUKAN PADA PROSES REPLACMENT</b><hr><a  href="index.php?menu=replacement&tahun=<?php echo $thn; ?>" target="_BLANK" class="btn btn-sm btn-primary"> Lihat Proses Iterasi</a></h5>
                </div>
                    <?php
                        $no=1;
                        $a=mysqli_query($connect, "SELECT * FROM replacment WHERE status='1' AND tahun='$thn'");
                        echo "<table class='table table-hover'>
                            <thead>
                            <tr>
                                <th><center>No</center></th>
                                <th colspan='2'>Nama Rule</th>
                            </tr></thead><tbody>";
                            while ($b=mysqli_fetch_array($a)) {
                                echo "<tr>";
                                    echo "<td><center>"; echo $no; echo "</center></td>";
                                    echo "<td>"; echo $b['nama_rule']; echo "</td>";
                                    echo "<td>==> "; echo $b['pasal']; echo "</td>";
                                echo "</tr>";
                            $no++; }
                           echo "</tbody></table>";
                    ?>
                <hr>
            </div>
            <div class="table-responsive col-md-4">
                <div class="alert alert-warning">
                    <h5 align="center"><b>RULE YANG DITEMUKAN PADA PROSES REVISI</b><hr><a  href="index.php?menu=revisi&tahun=<?php echo $thn; ?>" target="_BLANK" class="btn btn-sm btn-primary"> Lihat Proses Iterasi</a></h5>
                </div>
                    <?php
                        $no=1;
                        $a=mysqli_query($connect, "SELECT * FROM revisi WHERE status='1' AND tahun='$thn'");
                        echo "<table class='table table-hover'>
                            <thead>
                            <tr>
                                <th><center>No</center></th>
                                <th colspan='2'>Nama Rule</th>
                            </tr></thead><tbody>";
                            while ($b=mysqli_fetch_array($a)) {
                                echo "<tr>";
                                    echo "<td><center>"; echo $no; echo "</center></td>";
                                    echo "<td>"; echo $b['nama_rule']; echo "</td>";
                                    echo "<td>==> "; echo $b['pasal']; echo "</td>";
                                echo "</tr>";
                            $no++; }
                           echo "</tbody></table>";
                    ?>
                <hr>
                </div>
            </div>
        
        <hr>
            <div class="table-responsive col-md-12">
                <div class="alert alert-success">
                    <h4 align="center"><b>PERHITUNGAN OPTIMISASI</b></h4>
                </div>
                    <?php
                        echo "<table class='table table-hover'>
                            <thead>
                            <tr>
                                <th><center>#</center></th>
                                <th colspan='2'>Nama Rule</th>
                                <th>THEORY BITS</th>
                                <th>EXCEPTION BITS</th>
                                <th>MDL</th>
                            </tr></thead><tbody>";
                        $no=1;
                        mysqli_query($connect, "UPDATE hasil_rule SET status='0' WHERE tahun='$thn'");
                        $itr=array();
                        $cekmaks=mysqli_query($connect, "SELECT * FROM hasil_rule WHERE tahun='$thn'");
                        while ($maks=mysqli_fetch_array($cekmaks)) { $itr[]=$maks['iterasi'];
                        }
                        $itrr=implode(",",$itr);
                        $maksitr=MAX($itr);
                        for ($i=1;$i<$maksitr+1;$i++) {
                        $mdlmax=array();
                        $a=mysqli_query($connect, "SELECT * FROM hasil_rule WHERE tahun='$thn' AND iterasi='$i'");
                            while ($b=mysqli_fetch_array($a)) { ?>
                                <tr>
                                    <td><?php echo $b['label']; ?></td>
                                    <td><?php echo $b['nama_rule']; ?></td>
                                    <td><?php echo $b['pasal']; ?></td>
                                    <td><?php echo $b['theory']; ?></td>
                                    <td><?php echo $b['exception']; ?></td>
                                    <td><?php $mdlmax[]=$b['mdl']; echo $b['mdl']; ?></td>                                    
                                </tr>
                        <?php 
                    } 
                        $implode=implode(",",$mdlmax);
                        $maxmdl=MAX($mdlmax);
                        mysqli_query($connect, "UPDATE hasil_rule SET status='1' WHERE tahun='$thn' AND mdl='$maxmdl' AND iterasi='$i' LIMIT 1"); 
                        ?>
                        <tr><td colspan="6"><center><b>RULE YANG DIAMBIL DARI PROSES ==> 
                            <?php
                            $select=mysqli_query($connect,"SELECT * FROM hasil_rule WHERE status='1'");
                            $data=mysqli_fetch_array($select);
                            echo $data['label'];
                            ?>
                            </b> </center></td></tr>
                    <?php 
                }
                           echo "</tbody></table>";
                    ?>
                </div>
    </div>
</div>