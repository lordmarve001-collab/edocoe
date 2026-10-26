<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr=$_SESSION["staffid"];
include 'dataco.php';
//include "includes/getSubmit.php";
if(strlen($_SESSION['staffid'])=="")
    {   
    header("Location: ../logout"); 
    }
    else{
        $now = time(); // Checking the time now when home page starts.
if ($now > $_SESSION['expire']) {
    session_unset();
    session_destroy();
    header("Location:../logout");
}

$error='';
require"phpmailer/class.phpmailer.php";
$mail=new PHPMailer();
        $error='';
        $dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT description,session,deadline FROM resultsession WHERE description='jupeb'"));
        $desc=$dat['description'];
        $ses=$dat['session'];
        $dead=$dat['deadline'];
        if (isset($_POST['approve'])) {

            
      $update=mysqli_query($con,"UPDATE juplicant SET status='Approved',studentid='".$_SESSION['appli']."' WHERE applid='".$_SESSION['appli']."' AND acadsession='$ses'");
      if ($update) {
        $tud=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM juplicant WHERE applid='".$_SESSION['appli']."' AND acadsession='$ses' "));
        $first=trim($tud['firstname']);
        $last=trim($tud['lastname']);
        $malk=mysqli_fetch_assoc(mysqli_query($con,"SELECT count(*) as unemail  FROM juplicant WHERE firstname='$first' AND lastname='$last' "));
        
        $malb=$malk['unemail'];
        if (!$malb) {
            $username=strtolower(mysqli_escape_string($con,$first."".$malb.".".$last));
            $mk="@jupeb.uat.edu.ng";
            $uemail=strtolower(mysqli_escape_string($con,$username."".$mk));
            
        }else{
            $username=strtolower(mysqli_escape_string($con,$first.".".$last));
            $mk="@jupeb.uat.edu.ng";
            $uemail=strtolower(mysqli_escape_string($con,$username."".$mk));
            
        }
    
        $qu=mysqli_query($con,"UPDATE studdata SET unemail='$uemail' WHERE studentid='".$_SESSION['appli']."'");

          $succ='Email And Sms is Successfully Send To Applicants';
          $showModalS='true';
          appmail($_SESSION['appli'],$orgname,$schadress,$logo,$con,$mail);
          sendsms($_SESSION['appli'],$con,$orgname,$schadress);
      }else{
        $error='An Error Occured While Confirming Data Please Try Again!!!';
        $showModalEr='true';
      }
    }
    if (isset($_POST["disapproved"])) {
        $mess=$_POST['messag'];
        $upda=mysqli_query($con,"UPDATE juplicant SET status= 'rejected' WHERE applid='".$_SESSION['appli']."'");
        if ($upda) {
            disapprovedmail($con,$_SESSION['appli'],$ses,$mess,$mail,$orgname);
            studsms($_SESSION['appli'],$ses,$mess,$con,$orgname);
            $succ='Candidate has been notify of your decision via Email And Sms';
            $showModalS='true';
            }else{
                $error='An Error Occured While Rejecting Data Please Try Again!!!';
               $showModalEr='true'; 
            }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlentities($orgname) ;?></title>
    <link href="<?php echo htmlentities($logo) ;?>" rel="shortcut icon" type="image/x-icon" />
    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="css/receipt.css" media="screen">
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
    <link rel="stylesheet" href="css/main.css" media="screen" >
    <style type="text/css">
      #blah{
            width:170.256px;
            height:159px;
            border:2px solid black;
            }
            .td{
                cursor: not-allowed;
            }
        strong{
            color: blue;
        }
    </style>
    </head>
    <body class="top-navbar-fixed">
        <div class="main-wrapper">

            <!-- ========== TOP NAVBAR ========== -->
  <?php include('includes/topbar.php');?> 
            <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
            <div class="content-wrapper">
                <div class="content-container">

                    <!-- ========== LEFT SIDEBAR ========== -->
                   <?php
                    $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));

                  if ($paych['position']=="superadmin") {
                    
                    include('includes/supbar.php');
                    }elseif($paych['position']=='subadmin'){
                        include('includes/subbar.php');
                    }elseif($paych['position']=='sys_analyst'){
                        include('includes/sysbar.php');
                    }
                    else{
                    
                        include('includes/leftbar.php');
                        }
                    ?>
                    
                    
                        <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div>
                                    <h3 class="title"><?php echo htmlentities($ses) ?> JUPEB APPLICANT DATA </h3>
                                </div>
                                
                                <!-- /.col-md-6 text-right -->
                            </div>
                            <!-- /.row -->
                            
                            <!-- /.row -->
                        </div>
                        

    <div class="container-fluid">                           
        <div class="row">
            <div class="col-md-12">
                <div class="panel">    
                   <?php
                    $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));
                     if ($paych['position']=="superadmin" OR $paych['position']=='subadmin' OR $paych['position']=='sys_analyst') {
                       
                    
                     ?>
                    <div class="panel-body"> 
                    <section>
                           <table id="example" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Applicant id</th>
                                        <th>Applicant Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Acadsession</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                            <?php
                            
                            $sql = "SELECT * FROM juplicant WHERE acadsession='$ses' AND status='Pending' ";
                            $query = mysqli_query($con,$sql);
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            $cnt=1;
                            
                            foreach($results as $result)
                            {   
                                stuname($con,$result['applid']);
                                $_SESSION['applid']=$result['applid'];
                            ?>
                            <tr>
                             <td><?php echo htmlentities($cnt);?></td>
                                <td class="td"><?php echo htmlentities($result['applid']) ?></td>
                                <td class="td"><?php echo htmlentities($_POST['harrd']) ?></td>
                                <td><a href="tel:<?php echo $result['phoneno']?>"><?php echo htmlentities($result['phoneno']);?></a></td>
                                <td><a href="mailto:<?php echo $result['email']?>"><?php echo htmlentities($result['email']);?></a></td>
                                <td><?php echo htmlentities($result['acadsession']);?></td>
                                <td>
                                  <button class="btn btn-primary userinfof" id="<?php echo $result['applid'] ?>">  <i class="fa fa-eye"></i></button>  
                                </td>
                            </tr>
                            <?php 
                            $cnt=$cnt+1;
                            } ?> 
                            </tbody>
                            </table>
                    </section>
               
                    <?php
                  
                    }else{
                    ?><div class="container-fluid">                           
        <div class="row">
            <div class="col-md-12">
                <div class="panel">    
                   
                    <div class="panel-body">
                        <p style="color:red;"> Sorry Dear: <b style="color:blue;"> <?php echo htmlentities($fullname) ?></b> You Don't Have Access to this Page Thank You.</p>
                    <?php
                        //}
                      }
                    ?>  
                    <!-- /.left-sidebar -->

   </div>
            </div>
        </div>
        <!-- /.col-md-12 -->
    </div>
    </div>
</div>                 
<!-- /.content-container -->
</div>

   <!-- /.content-wrapper -->
 </div>
        <!-- /.main-wrapper -->
        <div class="modal" id="viewModals">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title" style="float:left;">Jupeb Student Data <i class="fa fa-users"></i> </h4>
                <button style="float:right;" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body" id="teacher_detailss">

            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                            <table>
                                <tr>
                                    <th>
                       <form method="POST">
                        <button name="disapproved" type="submit" class="btn btn-danger sendButton" style="float: left;margin-top:15px;" >Reject Detail <i class="fa fa-close"></i> </button>
                        <textarea id="mess" placeholder="Specify Reason for Rejecting Please" name="messag" style="resize: none;scroll-behavior: none;width: 450px;float: right;"></textarea>
                        
                    </form>
                     </th>
                     <th>
                    <form method="POST">
                        <button class="btn btn-primary" type="submit" name="approve">Confirm Detail <i class="fa fa-check"></i></button>
                        
                    </form>
                        </th>
                         </tr>
                         </table>         
                        </div>
        </div>
    </div>
</div>  
        <!-- ========== COMMON JS FILES ========== -->
        <?php
            include 'includes/buton.php';

        if(!empty($showModal)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModal").modal("show");
            });
        </script>';
    } 
    if(!empty($showModalEr)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalEr").modal("show");
            });
        </script>';
    } 
    if(!empty($showModall)) {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModall").modal("show");
            });
        </script>';
    } 
        ?>
      <script type="text/javascript">
        $(document).ready(function(){
    $('.sendButton').attr('disabled',true);

        $('#mess').keyup(function(){
        if($(this).val().length !=0){
        $('.sendButton').attr('disabled', false);
        }
        else
        {
        $('.sendButton').attr('disabled', true);        
        }
    });      
      
});
          $(document).ready(function() {

    //$('.userinfof').click(function() {
  $('body').on('click','.userinfof',function() {
        var userid = $(this).attr('id');
        
        $.ajax({
            url: 'viewjupebdata',
            type: 'POST',
            data: {
                userid: userid
            },
            success: function(response) {
                $('#teacher_detailss').html(response);
                // Display Modal
                $('#viewModals').modal('show');
            }
        });
    });
});

      </script>
    </body>
</html>
<?php 
} 
function stuname($con,$maxt)
{
 
      $sql =mysqli_query($con,"SELECT *  FROM  studdata WHERE studentid='$maxt'");
      $data = mysqli_fetch_assoc($sql);
      if ($data=="") {
        $_POST["harrd"]=" No Name";
      }else{
        $first = $data['firstname'];
        $sur = $data['surname'];
        $las=$data['lastname'];
        //$maks=$data['studentid'];
        $maks=$data['programme'];
        $name =strtoupper($sur)." ".$first." ".$las;
        $_POST["harrd"]= $name;
        $_POST["maks"]= $maks;
        }
}

?>
