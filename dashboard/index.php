<?php
session_start();
if(!isset($_SESSION['id_user'])){
echo"<script>document.location.href='../index.php'</script>";
}   
else {
include "../koneksi.php";
$q=mysqli_query($connect, "SELECT * FROM user where id_user='".$_SESSION['id_user']."' and active='1'");
while($d=mysqli_fetch_array($q))
    {
      $id_user=$d['id_user'];
      $nama=$d['nama_lengkap'];
      $username=$d['username'];
      $last_login=$d['last_login'];
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard Administrator | RIPPER Application</title>
    <!-- BOOTSTRAP STYLES-->
    <link href="../assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME ICONS STYLES-->
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <!--CUSTOM STYLES-->
    <link href="../assets/css/style.css" rel="stylesheet" />
      <!-- HTML5 Shiv and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a  class="navbar-brand" href="index.php?menu=home">Administrator</a>
            </div>
            <div class="notifications-wrapper">
            <ul class="nav">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user"></i> <?php echo $nama; ?> &nbsp;<i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user-plus"></i> My Profile</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="logout.php"><i class="fa fa-sign-out"></i> Logout</a>
                        </li>
                    </ul>
                </li>
                <li><h5> &nbsp; <b>Aplikasi Dengan Menggunakan Algoritma RIPPER</b></h5></li>
            </ul>
            </div>
        </nav>
        <nav  class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li>
                        <div class="user-img-div">
                            <img src="../assets/img/user.png" class="img-circle" />
                        </div>
                    </li>
                    <li><a href="index.php?menu=home"><i class="fa fa-home"></i>Beranda</a></li>
                    <li>
                        <a href="#"><i class="fa fa-sitemap "></i>Proses Analisa <span class="fa arrow"></span></a>
                         <ul class="nav nav-second-level">
                            <li><a href="index.php?menu=proses&thn=2015"><i class="fa fa-cogs"></i>Tahun 2015</a></li>
                            <li><a href="index.php?menu=proses&thn=2016"><i class="fa fa-cogs"></i>Tahun 2016</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-bars"></i>Hasil Rule <span class="fa arrow"></span></a>
                         <ul class="nav nav-second-level">
                            <li><a href="index.php?menu=hasil&thn=2015"><i class="fa fa-cogs"></i>Tahun 2015</a></li>
                            <li><a href="index.php?menu=hasil&thn=2016"><i class="fa fa-cogs"></i>Tahun 2016</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-refresh"></i>Optimisasi Data<span class="fa arrow"></span></a>
                         <ul class="nav nav-second-level">
                            <li><a href="index.php?menu=optimisasi&thn=2015"><i class="fa fa-cogs"></i>Tahun 2015</a></li>
                            <li><a href="index.php?menu=optimisasi&thn=2016"><i class="fa fa-cogs"></i>Tahun 2016</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-book"></i>Evaluasi Data <span class="fa arrow"></span></a>
                         <ul class="nav nav-second-level">
                            <li><a href="index.php?menu=evaluasi&thn=2015"><i class="fa fa-cogs"></i>Tahun 2015</a></li>
                            <li><a href="index.php?menu=evaluasi&thn=2016"><i class="fa fa-cogs"></i>Tahun 2016</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
                <?php
                if (isset($_GET['menu'])){
                    $menu=$_GET['menu'];
                    if($menu=="home"){include "home.php";}
                    if($menu=="growasli"){include "growasli.php";}
                    if($menu=="evaluasi"){include "evaluasi.php";} 
                    if($menu=="alur"){include "alur.php";}  
                    if($menu=="proses_alur_optimisasi"){include "proses_alur_optimisasi.php";}  
                    if($menu=="hasil"){include "hasil.php";}  
                    if($menu=="optimisasi"){include "optimisasi.php";} 
                    if($menu=="proses"){include "proses.php";} 
                    if($menu=="revisi"){include "revisi.php";} 
                    if($menu=="replacement"){include "replacement.php";} 
                    if($menu=="ubah_pass"){include "ubah_pass.php";}}
                else{include"home.php";}
                ?>
        </div>
    <footer>© 2018 - Algoritma Ripper Application. All Rights Reserved | Developer By : Putri</footer>
    <script src="../assets/js/jquery-1.11.1.js"></script>
    <script src="../assets/js/bootstrap.js"></script>
    <script src="../assets/js/jquery.metisMenu.js"></script>
    <script src="../assets/js/custom.js"></script>
</body>
</html>
<?php } ?>