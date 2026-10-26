<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr=$_SESSION["staffid"];
if(strlen($_SESSION['staffid'])=="")
    {    
    header("Location: ../logout"); 
    }
    else{
        $now = time(); // Checking the time now when home page starts.

include 'dataco.php';
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
        <link rel="stylesheet" href="css/receipt.css">
    
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
    .ttable th,.ttable td{
    border: 2px solid black;
    padding: 5px 5px;
    font-size: 15px;
}
    
    @media print{
            .ttable{
                width:1000px;
                margin:auto;
                border: 1px solid black;

            }
td,th{
    border: 1px solid black;
    cellpadding:0px;
    cellspacing:0px;
    padding: 5px 5px;
    font-size: 15px;
}
.td{
    display: none;
}
        }
    </style>
    
    <div class="main-page">
        <div class="container-fluid">
            <div class="row page-title-div">
                <div>
                    <h3 class="title"><?php echo "STUDENT PAYMENTS HISTORY SETUP"; ?></h3>

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
                                    <div style="width: 200px;margin: auto;">
            <img src="<?php echo htmlentities($logo)?>" style="width:150px; height: 150px; max-width:150px;">

        </div>
        <div style="width: 300px;margin: auto;">
            <h5><span style="font-size: 17px;"><?php echo htmlentities($orgname) ;?>
       </span></h5>
        </div>
        <hr>
        <div>
        <span style='position:absolute;margin-left:
          500px;margin-top:3px;width:170px;height:170px'><img width=170 height=170
          src="<?php echo $passport ?>" alt=" "></span><b>Student Name: <?php echo htmlentities($fullname) ?></b></span>
          <br><br>
          <span class=MsoNormal><b>Matric No: <?php echo htmlentities($_SESSION['staffid']); ?></b></span>
          <br><br>
          <span class=MsoNormal><b>Email: <?php echo htmlentities($email) ?></b></span>

          <br><br><span class=MsoNormal><b>College Of: <?php echo htmlentities($facu) ?> </b></span>
          <br><br><span class=MsoNormal><b>Department: <?php echo htmlentities($def) ?> </b></span>
          <br><br>
          <span class=MsoNormal><b>Programme: <?php echo htmlentities($progr) ?> </b></span>
          <br>
      </div>
                            
                                    <div align="center">
                                        <h3>YOUR
                                           FEES
                                            PAYMENT DETAILS</h3>
                                    </div>

        <table border="1" cellpadding="0" class="ttable" cellspacing="0" width="600">
            <tr class="heading">
                <th>#</th>
                <th>Session</th>
                <th>Amount Paid</th>
                <th>Level</th>
                <th>Semester</th>
                <th>Purpose</th>
                <th>Reference</th>
                <th>Date</th>
                <th>Status</th>
                <th class="td">Action</th>
            </tr>
            <?php
             $sql = "SELECT * FROM studentpayment WHERE (studentid='".$_SESSION['staffid']."' OR studentid='$admids') AND programme!='' ORDER BY level ";
                                               
                                                $query = mysqli_query($con,$sql);
                                                $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                                                $cnt=1;
                                               
                                                foreach($results as $result)
                                                {   ?>
                                                <tr>
                                                 <td><?php echo htmlentities($cnt);?></td>
                                                    <td><?php echo htmlentities($result['section']);?></td>
                                                    <td>&#8358;<?php echo htmlentities(number_format($result['amount'],2));?></td>
                                                    <td><?php echo htmlentities($result['level']);?></td>
                                                    <?php
                                                        if ($result['semester']!="") {
                                                            ?>
                                                         <td><?php echo htmlentities($result['semester']);?>
                                                             
                                                         </td>   
                                                      <?php  }else{
                                                        
                                                        ?>
                                                       <td><?php echo 'Full';?></td>
                                                        <?php
                                                      }
                                                     ?>
                                                    <td><?php echo htmlentities($result['purpose']);?></td>
                                                    <td><?php echo htmlentities($result['paymentreference']);?></td>
                                                    <td><?php echo htmlentities($result['paytime']);?></td>
                                                    <td><?php echo htmlentities($result['status']);?></td>
                                                    <td class="td"> <a href="receiptprint?ref=<?php echo htmlentities($result['paymentreference']);?>"><button class="btn btn-primary"><i class="fa fa-print"></i>Print</button></a></
                                                   
                                                <!-- Remember to continue from Here Tomorow Please -->
                                                
                                                </tr>
                                                <?php 

                                                $cnt=$cnt+1;
                                                 
                                                }
    $queryy="SELECT SUM(amount) AS sumunit FROM studentpayment WHERE  (studentid='".$_SESSION['staffid']."' OR studentid='$admids') AND programme!='' ";
$resultt= mysqli_query($con,$queryy);
            while($row = mysqli_fetch_array($resultt))
            {
              $_POST['total']=$row['sumunit'];
                
            }

        ?>
                                                       
            <tr>
                <td colspan="2" style="text-align: right;text-transform: uppercase;font-weight: bolder;"> Total: </td>
                <td colspan="2" style="font-weight: bolder;text-align: left;">&#8358; <?php echo htmlentities(number_format($_POST['total'],2))?></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="td"></td>
               
            </tr>
        </table>
             <div class="button"><button class="btn btn-info" onclick="window.open('printsum','_blank')"><i class="fa fa-print"></i> Print All Receipt</button></div>                   
        </div>
                                <!--End of invoice-->
                                <!-- /.col-md-12 -->
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
                    </div>
                <!-- /.content-container -->
            </div>
            <!-- /.content-wrapper -->
        </div>
        </body>
<?php }?>
