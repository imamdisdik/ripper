<?php
$tahun=$_GET['thn'];
$cekjumlahdata=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun'");
$jumlahdata=mysqli_num_rows($cekjumlahdata);

//PROSES INSERT JUMLAH DATA PASAL
mysqli_query($connect, "DELETE FROM pasal WHERE tahun='$tahun'");
$a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' ORDER BY pasal");
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
                    while ($n=mysqli_fetch_array($s)) {$angka=$n['num']; }
            mysqli_query($connect, "INSERT INTO pasal (pasal,jumlah,tahun) values ('$expp[$i]','$angka','$tahun')");
    }
//END PROSES INSERT JUMLAH DATA PASAL

//PROSES GROW PRUNE DATA
mysqli_query($connect, "UPDATE datareal SET label=''");
$pasal=array();
$a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' ORDER BY RAND()");
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
            mysqli_query($connect, "UPDATE datareal SET label='prune' WHERE label='' AND tahun='$tahun'"); 
//END PROSES GROW PRUNE DATA 
?>
<div id="page-wrapper" class="page-wrapper-cls">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
            <h1 class="page-head-line">Proses Analisa Ripper</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-success">
                    <h3 align="center">Data Analisa | <?php echo "Tahun : "; echo $tahun; echo " (Sebanyak "; echo $jumlahdata; echo " Data)"; ?> ----------------------------- <a class="btn btn-primary" href="index.php?menu=alur&tahun=<?php echo $tahun; ?>"> LAKUKAN PROSES ANALISA </a></h3>
                </div>
                <div class="table-responsive col-md-6" style="height:450px;">
                    <table class="table table-hover"><caption><h3><b><center>GROW DATA</center></b></h3><hr></caption>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Jenis Kelamin</th>                                                        
                                <th>Usia</th>
                                <th>Pekerjaan</th>
                                <th>Pasal</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $no=1;
                            $cekjumlah=mysqli_query($connect, "SELECT jumlah,pasal FROM pasal WHERE tahun='$tahun' ORDER BY jumlah ASC");
                            while ($jumlah=mysqli_fetch_array($cekjumlah)) { $pasal=$jumlah['pasal'];
                            $a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='grow' AND pasal='$pasal'");
                                while ($b=mysqli_fetch_array($a)) { ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td><?php echo $b['jk']; ?></td>
                                <td><?php echo $b['kelompok']; ?></td>
                                <td><?php echo $b['pekerjaan']; ?></td>
                                <td><?php echo $b['pasal']; ?></td>
                            </tr>
                        <?php $no++; } } ?>
                        </tbody>
                    </table>
                </div>    
                <div class="table-responsive col-md-6" style="height:450px;">
                    <table class="table table-hover"><caption><h3><b><center>PRUNE DATA</center></b></h3><hr></caption>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Jenis Kelamin</th>                                                        
                                <th>Usia</th>
                                <th>Pekerjaan</th>
                                <th>Pasal</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $no=1;
                            $cekjumlah=mysqli_query($connect, "SELECT jumlah,pasal FROM pasal WHERE tahun='$tahun' ORDER BY jumlah ASC");
                            while ($jumlah=mysqli_fetch_array($cekjumlah)) { $pasal=$jumlah['pasal'];
                            $a=mysqli_query($connect, "SELECT * FROM datareal WHERE tahun='$tahun' AND label='prune' AND pasal='$pasal'");
                                while ($b=mysqli_fetch_array($a)) { ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td><?php echo $b['jk']; ?></td>
                                <td><?php echo $b['kelompok']; ?></td>
                                <td><?php echo $b['pekerjaan']; ?></td>
                                <td><?php echo $b['pasal']; ?></td>
                            </tr>
                        <?php $no++; } } ?>
                        </tbody>
                    </table>
                </div>    
            </div>
        </div>
    </div>
</div>