<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr=$_SESSION["staffid"];
include 'dataco.php';

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
    if (isset($_POST['submitcd'])) {
    /*Remember to Query selection box.
    and link it to the dropdown option
    */
    if (empty($_POST['programme'])) {
      $showModalEr='true';  
    }else{
        $progt=mysqli_escape_string($con,$_POST['programme']);
        $mins=mysqli_escape_string($con,$_POST['minm']);
        //Checking to Avoid Duplicate Of Data in the Table
        $chck=mysqli_query($con,"SELECT * FROM passmark WHERE programme='$progt'");
            if(mysqli_num_rows($chck)>0)
            {
                $error="Programme ".$progt." Detail Already Exits";
                $showModals='true';
            }
            else
            {
                //Inserting the Data Into the Database
                $inst=mysqli_query($con,"INSERT INTO passmark (passmark,programme,datecreate) VALUES ('$mins','$progt',NOW())");
                if (!$inst) 
                {
                   $error= "Error Occured While Submitting";
                   $showModals='true';
                   mysqli_commit($con);
               }
               else{
                $showModalsuc='true';
               }
        }
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
    <link rel="stylesheet" href="css/animate.min.css" media="screen" >
    <style type="text/css">
      #blah{
            width:170.256px;
            height:159px;
            border:2px solid black;
            }
            .td{
                cursor: not-allowed;
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
                   <?php include('includes/hodbar.php');?>  
                    <!-- /.left-sidebar -->

                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div>
                                    <h2 class="title">Department Pass Mark Setup</h2>
                                
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
					<div>
						<h5 style="font-size:17px;color:red;">Please Setup for All Levels In Your Department<br>To Enable Computation Of Student CGPA</h5>
						</div> 
                    <div class="panel-body">                                                
                        <section class="section">
                            <?php
          $payc=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));
            if ($payc['position']=="hod")
                  { 
          ?>
                            <form class="form-horizontal" method="post" enctype="multipart/form-data">     
                                <div class="form-group">
                                    <label for="default" class="col-sm-2 control-label">Programme:</label>
                                        <div class="col-sm-10">
                                            <select name="programme" class="form-control clid" required="required">
                                                <option selected="selected" disabled="disabled">Select Programme</option>
                                                <?php
                                                $res=mysqli_query($con,"SELECT * FROM program WHERE did='$deplt' ORDER BY pname");//
                                                while ($rows=mysqli_fetch_array($res))
                                                {?>
                                                <option value="<?php echo  $rows["pname"]; ?>"><?php echo  $rows["pname"]; ?></option>
                                                <?php }?>

                                        
                                            </select>
                                        </div>
                                </div>
                                <div class="form-group">
                                    <label for="default"class="col-sm-2 control-label">Pass Mark:</label>
                                        <div class="col-sm-10">
                                            <input type="number" min="35" max="50"  name="minm" class="form-control clid"  required="required">
                                        </div>
                                </div>
                                                    
                                <span style="color: red;font-weight: bolder;">
                                
                                </span>                    
                                                    
                                    <!-- Sepreaaater -->
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" name="submitcd" id="submit" class="btn btn-primary">Submit Data</button>
                                    </div>
                                </div>
                                                           
                                                         
                            </form>
                        </section>
                        <section>
                           <table id="example" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Programme</th>
                                        <th>Passmark</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                            <?php

                            $cnt=1;
                            $res=mysqli_query($con,"SELECT * FROM program WHERE did='$deplt' ORDER BY pname");//
                            while ($rows=mysqli_fetch_array($res))
                            {
                              $sql = "SELECT * FROM passmark WHERE programme='".$rows['pname']."' ORDER BY programme";
                            
                            $query = mysqli_query($con,$sql);
                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                            
                            
                            foreach($results as $result)
                            {   ?>
                            <tr>
                             <td><?php echo htmlentities($cnt);?></td>
                                <td class="td"><?php echo htmlentities($result['programme']) ?></td>
                                <td contenteditable="true" class="ca" onBlur="saveInlineEdit(this,'passmark','<?php 
                                                echo $result["programme"]; ?>','<?php 
                                                echo $result["passmark"]; ?>')" 
                                                onClick="highlightEdit(this);"><?php echo htmlentities($result['passmark']);?></td>
                               
                            </tr>
                            <?php 

                            $cnt=$cnt+1;
                             
                            }} ?> 
                            </tbody>
                            </table>
                    </section>
                    <?php
             }else{
              ?>
              <p style="font-size:17px;text-align: justify;">Dear:<b style="color:green;"> <?php echo htmlentities(ucwords($fullname)) ?></b> You Don't Have Access to this Page.
              </p>
              <?php
             }
           ?>
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
        
        <div class="modal fade" id="checkModalEr" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: red;" id="exampleModalLabel">Error Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Please Select Programme</p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Error Message -->
            <div class="modal fade" id="checkModalh" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: red;" id="exampleModalLabel">Error Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p style='color:red;'>
                                <?php 
                                    echo htmlentities($error);
                                ?>
							</p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Success Message -->
            <div class="modal fade" id="checkModalsuc" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: green;" id="exampleModalLabel">Success Message</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>
                            Details Is Successfully Submitted
                            </p>
                        </div>
                        <div class="modal-footer">
              <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        <!-- ========== COMMON JS FILES ========== -->
        <?php
            include 'includes/buton.php';

        if(!empty($showModalEr)) 
        {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalEr").modal("show");
            });
        </script>';
        } 
         
        if(!empty($showModals)) 
        {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalh").modal("show");
            });
        </script>';
        }
         if(!empty($showModalsuc)) 
        {
        // CALL MODAL HERE
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalsuc").modal("show");
            });
        </script>';
        } 
        ?>
    <script type="text/javascript">
           
         function saveInlineEdit(editableObj,column,progs)
         {

          $(editableObj).css("background","#FFF url(loaderIcon.gif) no-repeat right");
          var customer={
            editval : $(editableObj).text(),
            column:column,
            programme:progs
          }
           $.ajax({
            url: "pedit",
            type: "POST",
            //data:'column='+column+'&editval='+$(editableObj).text()+'&programme='+progs,
            data:customer,
            success: function(data){
              $(editableObj).css("background","#FDFDFD");
              //alert(data);
            }
          });
          

    }
        function highlightEdit(editableObj)
            {
          //     
            $(editableObj).css("background","#FFF");
            }
            
       </script>
        
      
    </body>
</html>
<?php } ?>
