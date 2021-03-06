<?php
    include_once 'database.php';
    session_start();
    if(!(isset($_SESSION['email'])))
    {
        header("location:login.php");
    }
    else
    {
        $name = $_SESSION['name'];
        $email = $_SESSION['email'];
        include_once 'database.php';
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kuis Online</title>
    <link  rel="stylesheet" href="css/bootstrap.min.css"/>
    <link  rel="stylesheet" href="css/bootstrap-theme.min.css"/>    
    <link rel="stylesheet" href="css/welcome.css">
    <link  rel="stylesheet" href="css/font.css">
    <script src="js/jquery.js" type="text/javascript"></script>
    <script src="js/bootstrap.min.js"  type="text/javascript"></script>
</head>
<body>
    <nav class="navbar navbar-default title1">
        <div class="container-fluid">
            <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        <a class="navbar-brand" href="#">&nbsp;<b>- Proyek Kuis -</b>&nbsp;</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-left">
                <li <?php if(@$_GET['q']==1) 
                        echo'class="active"'; 
                        ?>>
                    <a href="welcome.php?q=1">&nbsp;Dashboard<span class="sr-only">(current)</span></a>
                </li>
                <li <?php if(@$_GET['q']==2) 
                    echo'class="active"'; ?>> 
                    <a href="welcome.php?q=2">&nbsp;Riwayat</a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
            <li <?php echo''; ?> > 
            <a href="logout.php?q=welcome.php">&nbsp;Keluar</a></li>
            </ul>
        </div>
    </div>
    </nav>
    <br><br>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php if(@$_GET['q']==1) 
                {
                    $result = mysqli_query($con,"SELECT * FROM quiz ORDER BY date DESC") or die('Error');
                    echo  '<div class="panel">
                    <div class="table-responsive">
                    <table class="table table-striped title1">
                    <tr>
                    <td><center><b>No</b></center></td><td><center><b>Topic</b></center></td>
                    <td><center><b>Total soal</b></center></td><td><center><b>Marks</center></b></td>
                    <td><center><b>Opsi Pilihan</b></center></td></tr>';
                    $c=1;
                    while($row = mysqli_fetch_array($result)) {
                        $title = $row['title'];
                        $total = $row['total'];
                        $sahi = $row['sahi'];
                        $eid = $row['eid'];
                    $q12=mysqli_query($con,"SELECT score FROM history WHERE eid='$eid' AND email='$email'" )or die('Error98');
                    $rowcount=mysqli_num_rows($q12);	
                    if($rowcount == 0){
                        echo '<tr>
                        <td><center>'.$c++.'</center></td>
                        <td><center>'.$title.'</center></td><td><center>'.$total.'</center></td>

                        <td><center>'.$sahi*$total.'</center></td>
                        <td><center><b><a href="welcome.php?q=quiz&step=2&eid='.$eid.'&n=1&t='.$total.'" class="btn sub1" style="color:black;margin:0px;background:#1de9b6">&nbsp;<span class="title1"><b>Start</b></span></a></b></center></td></tr>';
                    }
                    else
                    {
                    echo '<tr style="color:blue">
                    <td><center>'.$c++.'</center></td>
                    <td><center>'.$title.'&nbsp;<span title="This quiz is already solve by you" class="glyphicon glyphicon-ok" aria-hidden="true"></span></center></td>
                    <td><center>'.$total.'</center></td>
                    <td><center>'.$sahi*$total.'</center></td>

                    <td><center><b>
                    <a href="update.php?q=quizre&step=25&eid='.$eid.'&n=1&t='.$total.'" class="pull-right btn sub1" style="color:white;margin:0px;background:black">&nbsp;<span class="title1"><b>Restart</b></span></a></b></center></td></tr>';
                    }
                    }
                    $c=0;
                    echo '</table></div></div>';
                }?>

                <?php
                    if(@$_GET['q']== 'quiz' && @$_GET['step']== 2) 
                    {
                        $eid=@$_GET['eid'];
                        $sn=@$_GET['n'];
                        $total=@$_GET['t'];
                        $q=mysqli_query($con,"SELECT * FROM questions WHERE eid='$eid' AND sn='$sn' " );
                        echo '<div class="panel" style="margin:5%">';
                        while($row=mysqli_fetch_array($q) )
                        {
                            $qns=$row['qns'];
                            $qid=$row['qid'];
                            echo '<b>Question &nbsp;'.$sn.'&nbsp;::<br /><br />'.$qns.'</b><br /><br />';
                        }
                        $q=mysqli_query($con,"SELECT * FROM options WHERE qid='$qid' " );
                        echo '<form action="update.php?q=quiz&step=2&eid='.$eid.'&n='.$sn.'&t='.$total.'&qid='.$qid.'" method="POST"  class="form-horizontal">
                        <br />';

                        while($row=mysqli_fetch_array($q) )
                        {
                            $option=$row['option'];
                            $optionid=$row['optionid'];
                            echo'<input type="radio" name="ans" value="'.$optionid.'">&nbsp;'.$option.'<br /><br />';
                        }
                        echo'<br /><button type="submit" class="btn btn-primary">&nbsp;Submit</button></form></div>';
                    }

                    if(@$_GET['q']== 'result' && @$_GET['eid']) 
                    {
                        $eid=@$_GET['eid'];
                        $q=mysqli_query($con,"SELECT * FROM history WHERE eid='$eid' AND email='$email' " )or die('Error157');
                        echo  '<div class="panel">
                        <center><h1 class="title">Hasil Akhir</h1>
                        <center><br /><table class="table table-striped title1" style="font-size:20px;font-weight:1000;">';

                        while($row=mysqli_fetch_array($q) )
                        {
                            $s=$row['score'];
                            $w=$row['wrong'];
                            $r=$row['sahi'];
                            $qa=$row['level'];
                            echo '<tr>
                            <td>Total Soal</td>
                            <td>'.$qa.'</td></tr>
                                <tr>
                                <td>Jawaban benar&nbsp;</td>
                                <td>'.$r.'</td></tr> 
                                <tr>
                                <td>Jawaban salah&nbsp;</td>
                                <td>'.$w.'</td></tr>
                                <tr>
                                <td>Score&nbsp;</td>
                                <td>'.$s.'</td></tr>';
                        }
                        $q=mysqli_query($con,"SELECT * FROM rank WHERE  email='$email' " )or die('Error157');
                        while($row=mysqli_fetch_array($q) )
                        {
                            $s=$row['score'];
                            echo '<tr><td>Score Keseluruhan&nbsp;</td><td>'.$s.'</td></tr>';
                        }
                        echo '</table></div>';
                    }
                ?>

                <?php
                    if(@$_GET['q']== 2) 
                    {
                        $q=mysqli_query($con,"SELECT * FROM history WHERE email='$email' ORDER BY date DESC " )or die('Error197');
                        echo  '<div class="panel title">
                        <table class="table table-striped title1" >
                        <tr style="color:black;">
                        <td><center><b>No</b></center></td>
                        <td><center><b>Kuis</b></center></td>
                        <td><center><b>Soal Terselesaikan</b></center></td>
                        <td><center><b>Benar</b></center></td>
                        <td><center><b>Salah<b></center></td>
                        <td><center><b>Score</b></center></td>';
                        $c=0;
                        while($row=mysqli_fetch_array($q) )
                        {
                        $eid=$row['eid'];
                        $s=$row['score'];
                        $w=$row['wrong'];
                        $r=$row['sahi'];
                        $qa=$row['level'];
                        $q23=mysqli_query($con,"SELECT title FROM quiz WHERE  eid='$eid' " )or die('Error208');

                        while($row=mysqli_fetch_array($q23) )
                        {  $title=$row['title'];  }
                        $c++;
                        echo '<tr><td><center>'.$c.'</center></td><td><center>'.$title.'</center></td><td><center>'.$qa.'</center></td><td><center>'.$r.'</center></td><td><center>'.$w.'</center></td><td><center>'.$s.'</center></td></tr>';
                        }
                        echo'</table></div>';
                    }
                ?>
</body>
</html>