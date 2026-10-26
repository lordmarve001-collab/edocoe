<?php
session_start();
$connection = TRUE;
include('../include/dbconfig.php');
include 'dataco.php';
if (!isset($_SESSION['staffid']))
    header("Location: ../logout");
$now = time(); // Checking the time now when home page starts.
if ($now > $_SESSION['expire']) {
    session_unset();
    session_destroy();
    header("Location:../logout");
}
  $harr = $_SESSION['staffid'];
  $sectin=$_SESSION['section'] ;
  $semet=$_SESSION['semest'];
  $level=$_SESSION['level'];
  
  $_POST["sectin"]=$_SESSION['section'] ;
  $_POST["semet"]=$_SESSION['semest'];
  $_POST["level"]=$_SESSION['level'];
 // $_POST["program"]=$_SESSION['programs'];
        ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo htmlentities($orgname) ;?></title>
        <link href="<?php echo htmlentities($logo) ;?>" rel="shortcut icon" type="image/x-icon" />
       <link rel="stylesheet" href="css/bootstrap.min.css" media="screen" >
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
    <link rel="stylesheet" href="css/main.css" media="screen" >
    <link rel="stylesheet" href="css/coms.css">
    <link rel="stylesheet" href="css/print.css" media="print">
    <!-- <link rel="stylesheet" href="css/receipt.css"> -->
    </head>
<body class="top-navbar-fixed">
        <div class="main-wrapper">

            <!-- ========== TOP NAVBAR ========== -->
   <?php include('includes/topbar.php');?> 
            <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
            <div class="content-wrapper">
                <div class="content-container">
<?php include('includes/leftbar.php');?> 
<style>
        
    .table td, .table th{
      border: 1px solid black;
    }
    @media print {
        
  .table{
    width: 100%;
  }
  .table td{
    border: 1px solid black;
    cellpadding:0px;
    cellspacing:0px;
    padding: 4px 4px;
    font-size: 15px;
  }
  .tgh{
    width: 100%;
  }
.invoice-box {
        /*max-width: 800px;*/
        max-width: 100%;
        margin: auto;
        padding: 30px;
        /*border: 1px solid #eee;*/
        /*box-shadow: 0 0 10px rgba(0, 0, 0, .15);*/
        font-size: 16px;
        line-height: 24px;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color: #555;
    }
    .invoice-box .tables {
        width: 100%;
        line-height: inherit;
        text-align: left;
    }
    
    .invoice-box .tables td {
        padding: 5px;
        vertical-align: top;
    }
    
    .invoice-box .tables tr td:nth-child(2) {
        text-align: right;
    }
    
    .invoice-box .tables tr.top .tables td {
        padding-bottom: 20px;
    }
    
    .invoice-box .tables tr.top .tables td.title {
        font-size: 45px;
        line-height: 15px;
        color: #333;
    }
    
    .invoice-box .tables tr.information .tables td {
        padding-bottom: 40px;
    }
    
    .invoice-box .tables tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }
    
    .invoice-box .tables tr.details td {
        padding-bottom: 20px;
    }
    
    .invoice-box .tables tr.item td{
        border-bottom: 1px solid #eee;
    }
    
    .invoice-box .tables tr.item.last td {
        border-bottom: none;
    }
    
    .invoice-box .tables tr.total td:nth-child(2) {
        border-top: 2px solid #eee;
        font-weight: bold;
    }
    .left-sidebar{
        display: none;
    }
    .top-navbar{
        display: none;
    }
}
    </style>
    <div class="main-page">
        <div class="container-fluid">
            <div class="row page-title-div">
                <div>
                    <h3 class="title">STUDENT RESULT VIEW SETUP</h3>

                </div>

                <!-- /.col-md-6 text-right -->
            </div>

            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->

        <section class="section">
            <div class="container-fluid">



                <div class="row">
                    <div class="col-md-12">

                        <div class="panel">


                            <div class="panel-body p-20">
                               
                                <div class="invoice-box">
                                    <div style="width: 150px;margin: auto;">
            <img src="<?php echo htmlentities($logo)?>" style="width:100px; height: 100px; max-width:150px;">

        </div>
        <div style="width: 350px;margin: auto;">
            <h5><span style="font-size: 20px;"><?php echo htmlentities($orgname) ?>, <?php echo htmlentities($schadress) ?> (UAT)</span>
                <br>
                <span style="font-size:20px;margin-left: 36px;">Sagbama, Bayelsa State.</span>
            </h5>
        </div>             
           <hr>                      
        <div class="logos">
      
      </div>  
                                    <table class="tgh" style="font-size: 14px;float: left;" >
                                        <tr>
                                            <td style="">Student Name <span class="span">: <?php echo htmlentities($fullname) ?></span></td>
                                            <td rowspan="4">
                                                <img src="<?php echo $passport ?>" alt="Photo" style="width:150px; height: 150px; max-width:150px;border: 2px solid white;float: right;">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-transform: capitalize;">Matric No<span class="span">: <?php echo htmlentities ($harr) ?></span></td>
                                            <td>
                                                
                                        </tr>
                                        <tr>
                                            <td style="text-transform: capitalize;">Session<span class="span">: <?php echo htmlentities($sectin) ?></span></td>
                                            
                                        </tr>
                                        <tr>
                                            <td style="text-transform: capitalize;">Level<span class="span">: <?php echo htmlentities($level) ?></span></td>
                                            
                                        </tr>
                                        <tr>
                                            <td style="text-transform: capitalize;">Programme<span class="span">: <?php echo htmlentities($progr) ?></span></td>
                                          
                                        </tr>
                                        <tr>
                                            <td style="text-transform: capitalize;">Semester<span class="span">: <?php echo htmlentities($semet) ?></span></td>
                                           
                                        </tr>
                                    </table>
                                                                       </div>
                                    <div align="center">
                                        <h3>STUDENT
                                            <!--<asp:Label ID="Snm" runat="server"></asp:Label>-->
                                            SEMESTER RESULT DETAILS</h3>
                                    </div>

                                    <table class="table" border=1 cellspacing=0 cellpadding=0>
                                    <tr>
                                        <th>#</th>
                                        <th>Code</th>
                                        <th>Title</th>
                                        <th>Unit</th>
                                        <th>Grade</th>
                                        <th>Point</th>
                                    </tr>
            <?php
            $rec = "SELECT  * FROM result JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='".$_SESSION['staffid']."' AND result.acadsession='$sectin' AND result.semester='$semet' AND result.level='$level' AND result.programme='$progr' AND result.status='Y'  AND result.publish='1'  ORDER BY result.coursecode";
                $res = mysqli_query($con,$rec);
                 $datax = mysqli_fetch_all($res,MYSQLI_ASSOC);
                 if($datax>0){
                    
                        // Get Grade 
                         GetGradeSystem($con);
                        //GetScores($_POST['semester'],$_POST['level'],$_POST['mysession']);                    
                         // Get the carryovers
                         Carryover($con,$_POST['level'],$_POST['sectin'],$progr,$_SESSION['staffid'],$semet);
                         //Compute Cumulative
                         GetCumulative($con,$_POST['semet'],$_POST['level'],$_POST['sectin'],$progr,$_SESSION['staffid']);
           
                                  for($k=1;$k<=$_SESSION['scoresCounter']; $k++)://{
                                 
                              ?>

            <tr>
                
                                  <td><?php echo htmlentities($k)  ?></td>
                                  <td><?php echo htmlentities($_POST['Code'][$k]) ?></td>
                                  <td><?php echo htmlentities($_POST['Title'][$k]) ?></td>
                                  <td><?php echo htmlentities($_POST['Units'][$k]) ?></td>
                                  <td><?php echo htmlentities($_POST['G'][$k]) ?></td>
                                  <td><?php echo htmlentities($_POST['P'][$k]) ?></td>
                                  
                                
                                <?php
                                //}
                            endfor;
                                ?>
                            </tr>
                                <?php
                            }
                            ?>
                            
                                <tr>
                                    <td colspan='3'>  Total  </td>
                                    
                                   
                                    <td> <?php echo htmlentities($_POST['totalUnit']); ?> </td>
                                    <td> </td>
                                    
                                    <td> <?php echo htmlentities($_POST['points']); ?> </td>
                                </tr>
                           
                                    </table>

                                    <div style="text-transform: capitalize; font-size: 13px; margin-top: 3%;">
                                        <aside>
                                            <span><strong>Previous CGPA:
                                                <?php echo htmlentities($_POST['PGP']); ?></strong></span><br>
                                            <span><strong>Semester GPA:
                                                 <?php echo htmlentities($_POST['GP']); ?>
                                            </strong></span><br>
                                            
                                            <span><strong>Cummulative GPA:
                                                <?php echo htmlentities($_POST['CGP']); ?>
                                            </strong></span><br>
                                           
                                            <span style="color: green"><strong>Classification : 
                                                    <?php
                                                        gradeclass($con,$_POST['CGP']);
                                                    echo htmlentities($_POST['myGracl']); ?>
                                            </strong></span>
                                            <br>
                                            <span style="color: red"><strong>Carry Overs (If Any): 
                                                    <?php echo htmlentities($_POST['HolderCO']); ?>
                                            </strong></span>

                                            
                                        </aside>
                                    </div>

                                </div>
                                <div style="margin:auto;width:50px;" class="button">
                
                <button type="button" class="btn btn-success"onclick="window.open('printre','_blank')" style="font-size: 20px;display: non;">Print Result Detail <i class="fa fa-print"></i></button>             
            </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.col-md-6 -->


                </div>
                <!-- /.col-md-12 -->
            </div>
    </div>
    <!-- /.panel -->
    </div>
                                    <!-- /.col-md-6 -->

    </div>
                                <!-- /.row -->

    </div>
                            <!-- /.container-fluid -->
    </section>
                        <!-- /.section -->

    </div>
                    <!-- /.main-page -->
    
                    <!-- /.main-page -->
                    </div>
                <!-- /.content-container -->
            </div>
            <!-- /.content-wrapper -->
<?php include 'includes/buton.php'; 
include 'ipcap.php';?>
        </div>
        </body>
    <?php 
    
function GetGradeSystem($con)
        {
      $gradeCounter=0;
     $sqlper = "SELECT  * FROM gradetable";
      $result = mysqli_query($con,$sqlper);
     $data = mysqli_fetch_all($result,MYSQLI_ASSOC);
     //$rowks=mysqli_num_rows($result);
     foreach($data as $row){
        $gradeCounter+=1;
        $_POST['Minscore'][$gradeCounter] =  $row["minscore"];
        $_POST['Maxscore'][$gradeCounter] =  $row["maxscore"];
        $_POST['Grade'][$gradeCounter]    =  $row["grade"];
        $_POST['point'][$gradeCounter]    =  $row["point"];
       }
         $_SESSION['gradeCounter']=$gradeCounter;

 }

function GetScores($con,$semc,$les,$sesc,$prob,$hars)
{
    $scoresCounter=0;
    $_POST['points']=0;
    $_POST['totalUnit']=0;
     
    $mysqler = "SELECT  * FROM result INNER JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$hars' AND result.acadsession='$sesc' AND result.semester='$semc' AND result.level='$les' AND result.programme='$prob' AND result.status='Y'  AND result.publish='1'  ORDER BY result.coursecode";
    $resultf = mysqli_query($con,$mysqler);
     $dataf = mysqli_fetch_all($resultf,MYSQLI_ASSOC);
     foreach($dataf as $row){
     $scoresCounter+=1;
     $_POST['Units'][$scoresCounter]=$row['unit'];
     $_POST['Code'][$scoresCounter]=$row['coursecode'];
     $_POST['Title'][$scoresCounter]=$row['title'];
     $_POST['Scores'][$scoresCounter]=(int)$row['exam'] + (int)$row['ca']; 
  }
  $_SESSION['scoresCounter']=$scoresCounter;
  //compute GP
  GetGradeSystem($con); //Get the grade
   for($k=1; $k<=$_SESSION['scoresCounter']; $k++)
                         {
                          
                            for ($j = 1; $j<= $_SESSION['gradeCounter']; $j++)
                                if ($_POST['Scores'][$k] <= $_POST['Maxscore'][$j] && $_POST['Scores'][$k] >= $_POST['Minscore'][$j])
                                 {
                                    
                                    $_POST['G'][$k] = $_POST['Grade'][$j];
                                    $_POST['P'][$k] = $_POST['point'][$j]*$_POST['Units'][$k];
                                    $_POST['points']+=(int)$_POST['point'][$j] * (int)$_POST['Units'][$k];
                                    
                                 }
                                $_POST['totalUnit']+=$_POST['Units'][$k];
                          }
                           

}
//Carry over Section


     /*//function Carryover($level,$sectin,$proj,$harr,$sem)
     function Carryover($con,$level,$sectin,$proj,$harr,$sem)
     {
        
         $MyData="";
        $HolderCO='';
       $_POST['coufc']='';
        $COS='';
        $_POST['MyData']='';
        try {
            
          $CO="SELECT *,coursecode, MAX(ca+exam) FROM result WHERE  acadsession<='$sectin' AND level<='$level' AND studentid='$harr' AND programme='$proj' AND publish='1' AND status='Y' ";
            $data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM passmark WHERE programme='$proj'"));
            if ($data!='') {
            $type = $data['passmark'];  
            }
          $stmt = mysqli_query($con,$CO);
            $resulter = mysqli_fetch_all($stmt,MYSQLI_ASSOC);
                   foreach($resulter as $rw)
              {

              if ($rw['MAX(ca+exam)'] < $type)
              {  
                //echo $rw['coursecode'];
                 $MyData .=$rw['coursecode']. ",";
                  
              }
                        }
             $_POST['MyData']=$MyData;
             
             $COS.=$_POST['MyData'];
         }
              catch(PDOException $e)
           {
          echo $e->getMessage();
         }

         if ($_POST['MyData'] == "")
             {
                 $HolderCO .= "";
             }
             else
             {
                 $HolderCO .="".$COS;
             }
             $_POST['HolderCO'] = $HolderCO;
     }*/
function CheckCOS($con,$codet,$level,$ppl,$har)
   {
      
        $MyData="";
        //echo $ppl;
         try
         {
            $ch=
              "SELECT coursecode, MAX(ca+exam) FROM result WHERE coursecode='$codet' AND level<='$level' AND studentid='$har' AND programme='$ppl' AND publish='1' AND status='Y' ORDER BY acadsession DESC";

                $data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM passmark WHERE programme='$ppl'"));
            //$ = ($car);
            $type = $data['passmark'];
                        $stmt = mysqli_query($con,$ch);
                        $resulter = mysqli_fetch_all($stmt,MYSQLI_ASSOC);
                       foreach($resulter as $rw)
                        {

                            if ($rw['MAX(ca+exam)'] < $type)
                            {
                                 $MyData .= $rw['coursecode']. ",";

                            }
                        }
                       //$_POST['MyData']=$MyData;
                       $_POST['MyData']=$MyData;
         }
              catch(PDOException $e)
             {
              echo $e->getMessage();
             }

     }
     //function Carryover($level,$sectin,$proj,$harr,$sem)
     function Carryover($con,$level,$sectin,$proj,$harr,$sem)
     {
       $kanter_CO = 0;
        $HolderCO='';
        $COS='';
        $_POST['MyData']='';
             $CO="SELECT DISTINCT coursecode,ca,exam  FROM result  WHERE  studentid='$harr' AND acadsession<='$sectin' AND level <='$level' AND programme='ND. ENVIRONMENTAL HEALTH' AND publish='1' AND status='Y'";
            $data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM passmark WHERE programme='$proj'"));
            //$ = ($car);
            $type = $data['passmark'];
           $st = mysqli_query($con,$CO);
          $resulter = mysqli_fetch_all($st,MYSQLI_ASSOC);
                foreach($resulter as $rowers)
                {
                 $CodeMe = $rowers['coursecode'];
                 $kanter_CO = $kanter_CO + 1;
                 if ($rowers['ca'] != "" && $rowers['exam'] !="")
                 {
                     $SumTotal[$kanter_CO]  =  $rowers['ca'] + $rowers['exam'];
                     $SumTotal2[$kanter_CO] =  $rowers['ca'] + $rowers['exam'];
                 }
                 if ($SumTotal[$kanter_CO] < $type)
                 {
                     
                     CheckCOS($con,$CodeMe,$level,$proj,$harr);
                     
                          $COS.=$_POST['MyData'];
                         
                 }
                 if (($SumTotal2[$kanter_CO] >= $type))
                 {
                     $CO_Code2[$kanter_CO] = $rowers['coursecode'];
                     // Data = "Passed";

                 }

             }

             if ($_POST['MyData']== "")
             {
                 $HolderCO .= " ";
             }
             else
             {
                
                 $HolderCO .= "".$COS ; 
             }
             $_POST['HolderCO'] = $HolderCO;
     }

function GetCumulative($con,$semet,$levg,$sech,$proh,$hars)
{
    $_POST['pointsc']=0;
    $_POST['totalUnitc']=0;
     $scoresCounterc=0;
   
     $harr= "SELECT  * FROM result INNER JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$hars' AND result.acadsession<'$sech' AND result.programme='$proh' AND result.status='Y'  AND result.publish='1'  ORDER BY result.coursecode";
     $resultfz = mysqli_query($con,$harr);
        $resulter = mysqli_fetch_all($resultfz,MYSQLI_ASSOC);
  foreach($resulter as $row){
     $scoresCounterc+=1;
     $_POST['Unitc'][$scoresCounterc]=$row['unit'];
     $_POST['Codec'][$scoresCounterc]=$row['coursecode'];
     $_POST['Scoresc'][$scoresCounterc]=(int)$row['exam'] + (int)$row['ca']; 
  }
  $_SESSION['scoresCounterc']=$scoresCounterc; 

  //Compute CUMULATIVE
  GetGradeSystem($con);// Graing System
                        for($k=1; $k<=$_SESSION['scoresCounterc']; $k++)
                         {
                          
                            for ($j = 1; $j<= $_SESSION['gradeCounter']; $j++)
                                if ($_POST['Scoresc'][$k] <= $_POST['Maxscore'][$j] && $_POST['Scoresc'][$k] >= $_POST['Minscore'][$j])
                                 {
                                    
                                    $_POST['Gc'][$k] = $_POST['Grade'][$j];
                                    $_POST['Pc'][$k] = $_POST['point'][$j];
                                    $_POST['pointsc']+=$_POST['point'][$j] * (int)$_POST['Unitc'][$k];
                                 }
                                $_POST['totalUnitc']+=(int)$_POST['Unitc'][$k];
                                //echo $_POST['totalUnitc'];
                          }
                          
                        if($semet=="First Semester")
                         {
                            GetScores($con,$semet,$levg,$sech,$proh,$hars);
                            $_POST['GP']=number_format(($_POST['points']/$_POST['totalUnit']),2);//Compute current semester
                            $_POST['CGP']=number_format((($_POST['pointsc'] + $_POST['points'])/($_POST['totalUnitc']+ $_POST['totalUnit'])),2);//compute cumulative
                              if($_POST['pointsc']!=0 && $_POST['totalUnitc']!=0)
                              {
                               $_POST['PGP']=number_format(($_POST['pointsc']/$_POST['totalUnitc']),2);//computer previous GP
                              } 
                              else if($_POST['pointsc']==0 && $_POST['totalUnitc']==0){
                                 $_POST['PGP']="0.00";
                              }               
                         } 
                         else if($semet=="Second Semester")
                         {
                          GetScores($con,$semet,$levg,$sech,$proh,$hars); //get first semester computation
                          GetSecondResult($con,$semet,$sech,$proh,$hars);//get second semester computation
                          /*
                            $_POST['totalUnitsd']
                            $_POST['pointsd']
                          */
                          if ($_POST['pointsd']!=0 && $_POST['totalUnitsd']!=0) {
                            $_POST['GP']=number_format(($_POST['points']/$_POST['totalUnit']),2);//Current Semester GP
                         $_POST['CGP']=number_format((($_POST['pointsc'] +$_POST['points']+$_POST['pointsd'])/($_POST['totalUnitc']+$_POST['totalUnit']+$_POST['totalUnitsd'])),2);//Cumulative Gpa
                         $_POST['PGP']=number_format((($_POST['pointsc'] + $_POST['pointsd'])/($_POST['totalUnitc']+$_POST['totalUnitsd'])),2);//Compute previous
                             
                          }elseif ($_POST['pointsd']==0 && $_POST['totalUnitsd']==0) {
                            $_POST['PGP']="0.00";
                            $_POST['GP']=number_format(($_POST['points']/$_POST['totalUnit']),2);//Current Semester GP
                         $_POST['CGP']=number_format((($_POST['pointsc'] +$_POST['points']+$_POST['pointsd'])/($_POST['totalUnitc']+$_POST['totalUnit']+$_POST['totalUnitsd'])),2);//Cumulative Gpa
                          }
                         } 
    
    }                     
            
function GetSecondResult($con,$smt,$sef,$prt,$hars)
{   
    
    $scoresCounters=0;
    $_POST['pointsd']=0;
    $_POST['totalUnitsd']=0;

    $semet="First Semester";
     
   $mysqlercs = "SELECT  * FROM result INNER JOIN course ON result.coursecode=course.coursecode WHERE result.studentid='$hars' AND result.acadsession='$sef' AND result.semester='$semet' AND result.programme='$prt' AND result.status='Y'  AND result.publish='1'  ORDER BY result.coursecode";
   $resultfy = mysqli_query($con,$mysqlercs);
        $resulters = mysqli_fetch_all($resultfy,MYSQLI_ASSOC);
  foreach($resulters as $row){
     $scoresCounters+=1;
     $_POST['Unitsd'][$scoresCounters]=$row['unit'];
     $_POST['Codesd'][$scoresCounters]=$row['coursecode'];
     $_POST['Titlesd'][$scoresCounters]=$row['title'];
     $_POST['Scoresd'][$scoresCounters]=(int)$row['exam'] + (int)$row['ca']; 
  }
  $_SESSION['scoresCounters']=$scoresCounters;      
  //Compute CUMULATIVE
  GetGradeSystem($con);// Graing System
                        for($k=1; $k<=$_SESSION['scoresCounters']; $k++)
                         {
                          
                            for ($j = 1; $j<= $_SESSION['gradeCounter']; $j++)
                                if ($_POST['Scoresd'][$k] <= $_POST['Maxscore'][$j] && $_POST['Scoresd'][$k] >= $_POST['Minscore'][$j])
                                 {
                                    
                                    $_POST['Gcd'][$k] = $_POST['Grade'][$j];
                                    $_POST['Pcd'][$k] = $_POST['point'][$j];
                                    $_POST['pointsd']+=$_POST['point'][$j] * (int)$_POST['Unitsd'][$k];
                                 }
                                $_POST['totalUnitsd']+=(int)$_POST['Unitsd'][$k];
                               
                          }
}                                                  
function gradeclass($con,$CGP){
  /* 
  *Computing the graduating class of the Student From Graduating class table
  */
  $gradeCounterx=0;
  $sqlperx = "SELECT  * FROM gradclass ORDER BY id";
  $resultx = mysqli_query($con, $sqlperx);
  $datax = mysqli_fetch_all($resultx, MYSQLI_ASSOC);
  //$rowks=mysqli_num_rows($result);
  foreach ($datax as $row) {
    $gradeCounterx += 1;
    $_POST['Minscorep'][$gradeCounterx] =  $row["minscorep"];
    $_POST['Maxscorep'][$gradeCounterx] =  $row["maxscorep"];
    $_POST['Graclas'][$gradeCounterx]    =  $row["gracla"];
  }
  $_SESSION['gradeCounterx'] = $gradeCounterx;

  for ($i = 1; $i <= $_SESSION['gradeCounterx']; $i++) {
    //GetCumulative();
    if ($CGP <= $_POST['Maxscorep'][$i] && $CGP >= $_POST['Minscorep'][$i]) {
      
      $_POST['myGracl']   =   $_POST['Graclas'][$i];
      $_POST['myGraclc']   =   $_POST['Graclas'][$i];
      $_SESSION['myGracl']   =   $_POST['Graclas'][$i];

    }
  }
  
}
    ?>