<?php
session_start();
$connection = TRUE;
include('../include/dbconfig.php');
include"includes/genSubmit.php";
// if(strlen($_SESSION['alogin'])=="")
//     {   
//     header("Location: index.php"); 
//     }
//     else{
        ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>UNIVERSITY OF AFRICA</title>
       <link rel="stylesheet" href="css/bootstrap.min.css" media="screen" >
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
    <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen" >
    <link href="../favicon.ico" rel="shortcut icon" type="image/x-icon" />
       <!--  <%--  <link rel="stylesheet" href="css/result.css">--%> -->
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
        @media print{
            table{
                width:400px;
                margin:auto;
            }
        }
    </style>
    <style>
       
       
    .mover{
    /*width:500px;
    margin:auto;*/

}
    </style>
    <div class="main-page">
        <div class="container-fluid">
            <div class="row page-title-div">
                <div class="col-md-6">
                    <h3 class="title">STUDENT ADD/DROP COURSE SETUP</h3>

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


                            <div class="panel-body">

                                <form  method="POST">
            <div style="margin: auto;width: auto;">
            <table>
            <tbody>
              <tr>
                <td>
                  <select name="addcourse" class="select form-control">
                    <option selected="selected" disabled="disabled">SELECT COURSE</option>
                    <?php 
                      //$res = mysqli_query($con,"SELECT * FROM coursetable WHERE  levels='$level' AND semester='$semester' ORDER BY coursecode");
                      $res = mysqli_query($con,"SELECT * FROM coursetable ORDER BY coursecode");
                      while ($row=mysqli_fetch_array($res))
                      {
                        
                      ?>
                      <option value="<?php echo  $row["coursecode"]; ?>"><?php echo  $row["coursecode"]; ?> <?php echo  $row["coursetitle"]; ?></option>
                      <?php

                      }
                       ?>
                  </select>

                </td>
                <td><input type="submit" name="adddprog" value="ADD-COURSE" class="btn btn-success btn-label-right btn-label btn-label-right"></td>
              </tr>
            </tbody>
          </table>
          <span style="color:red"> <?php //echo $add?></span>
        </div>
                                </form>
        
    <span style="font-weight: bold;text-transform: uppercase;">Semester Courses</span>
      <table align="center" width="400" class="table">
      <tr>
          <th>S/NO</th>
          <th>Course Code</th>
          <th>Course Title</th>
          <th>Unit</th>
          <th>Status</th>
          <th>Delete</th>
      </tr>
      <form action="" method="post" enctype="multipart/form-data">
        
      <?php 
      /*this part fetchs the result of the applicant*/
 
    $que= mysqli_query($con,"SELECT * FROM courseregtable WHERE matricno='UAT20-002' ORDER BY coursecode");
  $sr =1;
      while ($rows= mysqli_fetch_array($que)): ?>
      <tr>
      <td class="tm"><?php echo $sr;?></td>
    <td class="td" style="text-align:left;"><?php echo $rows["coursecode"];?></td>
    <td class="td" id="unit" name="unit"><?php echo $rows["coursetitle"];?></td>
    <td class="td"><?php echo $rows["unit"];?></td>
    
    <td class="td"><?php echo $rows["statux"];?></td>
    <!--<td class="td"><input type="button" name="delete" value="Delete"></td>-->
    <!--<td class="td"><input type="submit" name="delete" value="Delete"></td>-->
    <td class="td">
      <a style='color: red;' href="addcourses.php?id=<?php echo $rows['id']?>&del=delete" onClick="return confirm('Are You Sure You Want To Delete <?php echo $rows['coursecode']?>?')">Delete
<!-- <button class="btn btn-danger">Delete</button> -->
</a>
      <!-- <a href='javascript:;' style='color: red;' class='item-delete' id='Courseregs.php?del=<?php echo $rows["id"];?>'>Removed</a> --></td>
    </tr>
      

       
      <?php 
      $sr++;
    endwhile;
    GetUnit();
                                    ?>
                                <tr><!-- <th> -->
                                    <td colspan='3' style="text-align: right;font-weight: bolder;"> Total Credit Units </td>
                                    
                                    <td><?php echo $_POST['totalUnit']; ?></td>
                                <!-- </th> --></tr>
        </table>
        
    </form>
                                

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
    
                    <!-- /.main-page -->
                    </div>
                <!-- /.content-container -->
            </div>
            <!-- /.content-wrapper -->
<?php include 'includes/buton.php'; 
function GetUnit()
{
$connection = TRUE;
include('../include/dbconfig.php');
$queryy="SELECT SUM(unit) AS sumunit FROM courseregtable WHERE hodstux='1'";
$resultt= mysqli_query($con,$queryy);
                        while($row = mysqli_fetch_array($resultt)){
                            $_POST['totalUnit']=$row['sumunit'];
                //$numx=$row['SUM(subject)'];
            }
}
?>
<script>
// $(function(){ 
//   $('.item-delete').click(function(){
//     var id=$(this).attr('id');
//     if(confirm('DO YOU WANT TO REMOVED THE COURSE?'+id))
//     {
//       window.location.href=id;
//     }
//   });
// });
</script>
        </div>
        </body>
        </html>    