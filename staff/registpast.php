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
    
    // Handle Delete Request
    if (isset($_POST['delete_id'])) {
        $delete_id = mysqli_real_escape_string($con, $_POST['delete_id']);
        $delete_programme = mysqli_real_escape_string($con, $_POST['delete_programme']);
        $delete_level = mysqli_real_escape_string($con, $_POST['delete_level']);
        
        $del_query = mysqli_query($con, "DELETE FROM registcheck WHERE programme='$delete_programme' AND level='$delete_level' AND id='$delete_id'");
        
        if ($del_query) {
            echo json_encode(['success' => true, 'message' => 'Record deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error deleting record: ' . mysqli_error($con)]);
        }
        exit();
    }
    
    if (isset($_POST['submitcd'])) {
        if (empty($_POST['programme'])) {
            $showModalEr='true';  
        } elseif (empty($_POST['level'])) {
            $showModal='true'; 
        } else {
            $progt=mysqli_escape_string($con,$_POST['programme']);
            $levl=mysqli_escape_string($con,$_POST['level']);
            $maxs=mysqli_escape_string($con,$_POST['maxm']);
            $mins=mysqli_escape_string($con,$_POST['minm']);
            
            //Checking to Avoid Duplicate Of Data in the Table
            $chck=mysqli_query($con,"SELECT * FROM registcheck WHERE level='$levl' AND programme='$progt'");
            if(mysqli_num_rows($chck)>0) {
                $error="Programme ".$progt." AND Level ".$levl." Detail Already Exits";
                $showModals='true';
            } else {
                $inst=mysqli_query($con,"INSERT INTO registcheck (maxm,minm,programme,level,createdate) VALUES ('$maxs','$mins','$progt','$levl',NOW())");
                if (!$inst) {
                    $error= "Error Occured While Submitting";
                    $showModals='true';
                } else {
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
    <title><?php echo htmlentities($orgname); ?></title>
    <link href="<?php echo htmlentities($logo); ?>" rel="shortcut icon" type="image/x-icon" />
    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="css/receipt.css" media="screen">
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
    <link rel="stylesheet" href="css/main.css" media="screen">
    <link rel="stylesheet" href="css/animate.min.css" media="screen">
    <style type="text/css">
        #blah {
            width:170.256px;
            height:159px;
            border:2px solid black;
        }
        .td {
            cursor: not-allowed;
        }
        .btn-delete {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            transition: all 0.3s;
        }
        .btn-delete:hover {
            background-color: #c82333;
            border-color: #bd2130;
            transform: scale(1.05);
        }
        .btn-delete i {
            margin-right: 5px;
        }
        .table tbody tr:hover {
            background-color: #f5f5f5;
        }
        .delete-loading {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid #fff;
            border-top: 2px solid #dc3545;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-left: 5px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .action-buttons {
            display: flex;
            gap: 5px;
        }
    </style>
</head>
<body class="top-navbar-fixed">
    <div class="main-wrapper">
        <!-- ========== TOP NAVBAR ========== -->
        <?php include('includes/topbar.php'); ?> 
        
        <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
        <div class="content-wrapper">
            <div class="content-container">
                <!-- ========== LEFT SIDEBAR ========== -->
                <?php
                $paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='".$_SESSION['staffid']."'"));
                if ($paych['position']=="hod") {
                    include('includes/hodbar.php');
                } elseif ($paych['position']=="superadmin") {
                    include('includes/supbar.php');
                } else {
                    include('includes/leftbar.php');
                }
                ?>  
                <!-- /.left-sidebar -->

                <div class="main-page">
                    <div class="container-fluid">
                        <div class="row page-title-div">
                            <div>
                                <h2 class="title">Course Registration Pass Mark Setup</h2>
                            </div>
                        </div>
                    </div>
                    
                    <div class="container-fluid">                           
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel"> 
                                    <div>
                                        <h5 style="font-size:17px;color:red;">Please Setup for All Levels In Your Department<br>To Enable You Setup their Curriculum</h5>
                                    </div>                                          
                                    <div class="panel-body"> 
                                        <?php
                                        if ($paych['position']=="hod" OR $paych['position']=="superadmin") {
                                        ?>                                             
                                            <section class="section">
                                                <form class="form-horizontal" method="post" enctype="multipart/form-data">     
                                                    <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Programme:</label>
                                                        <div class="col-sm-10">
                                                            <?php if ($paych['position']=="hod") { ?>
                                                                <select name="programme" class="form-control clid" required="required">
                                                                    <option selected disabled>Please select Programme</option>
                                                                    <?php
                                                                    $rest = mysqli_query($con, "SELECT * FROM program WHERE did='$deplt' ORDER BY pname");
                                                                    while ($rowt = mysqli_fetch_array($rest)) {
                                                                        ?>
                                                                        <option value="<?php echo $rowt["pname"]; ?>"><?php echo $rowt["pname"]; ?></option>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            <?php } else { ?>
                                                                <select name="programme" class="form-control clid" required="required">
                                                                    <option selected disabled>Please select Programme</option>
                                                                    <?php
                                                                    $rest = mysqli_query($con, "SELECT * FROM program ORDER BY pname");
                                                                    while ($rowt = mysqli_fetch_array($rest)) {
                                                                        ?>
                                                                        <option value="<?php echo $rowt["pname"]; ?>"><?php echo $rowt["pname"]; ?></option>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Level:</label>
                                                        <div class="col-sm-10">
                                                            <select name="level" class="form-control clid" required="required">
                                                                <option selected="selected" disabled="disabled">Select Level</option>
                                                                <?php
                                                                $res = mysqli_query($con, "SELECT * FROM level ORDER BY levels");
                                                                while ($row = mysqli_fetch_array($res)) {
                                                                ?>
                                                                    <option value="<?php echo $row["levels"]; ?>"><?php echo $row["levels"]; ?></option>
                                                                <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Maximum:</label>
                                                        <div class="col-sm-10">
                                                            <input type="number" name="maxm" class="form-control clid" required="required" placeholder="Enter Maximum Value">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Minimum:</label>
                                                        <div class="col-sm-10">
                                                            <input type="number" name="minm" class="form-control clid" required="required" placeholder="Enter Minimum Value">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="submit" name="submitcd" id="submit" class="btn btn-primary">
                                                                <i class="fa fa-save"></i> Submit Data
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </section>
                                            
                                            <section>
                                                <table id="example" class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Maximum</th>
                                                            <th>Minimum</th>
                                                            <th>Programme</th>
                                                            <th>Level</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="recordsTable">
                                                        <?php
                                                        $cnt=1;
                                                        $res=mysqli_query($con,"SELECT * FROM program ORDER BY pname");
                                                        while ($rows=mysqli_fetch_array($res)) {
                                                            $sql = "SELECT * FROM registcheck WHERE programme='".$rows['pname']."' ORDER BY programme";
                                                            $query = mysqli_query($con,$sql);
                                                            $results = mysqli_fetch_all($query,MYSQLI_ASSOC);
                                                            
                                                            foreach($results as $result) {   
                                                                ?>
                                                                <tr id="row_<?php echo $result['programme'] . '_' . $result['level']; ?>">
                                                                    <td><?php echo htmlentities($cnt); ?></td>
                                                                    <td contenteditable="true" class="ca" onBlur="saveInlineEdit(this,'maxm','<?php echo $result["programme"]; ?>','<?php echo $result["level"]; ?>')" onClick="highlightEdit(this);">
                                                                        <?php echo htmlentities($result['maxm']); ?>
                                                                    </td>
                                                                    <td contenteditable="true" class="ca" onBlur="saveInlineEdit(this,'minm','<?php echo $result["programme"]; ?>','<?php echo $result["level"]; ?>')" onClick="highlightEdit(this);">
                                                                        <?php echo htmlentities($result['minm']); ?>
                                                                    </td>
                                                                    <td class="td"><?php echo htmlentities($result['programme']); ?></td>
                                                                    <td class="td"><?php echo htmlentities($result['level']); ?></td>
                                                                    <td class="action-buttons">
                                                                        <button class="btn btn-delete btn-sm" onclick="confirmDelete('<?php echo $result['id']; ?>', '<?php echo addslashes($result['programme']); ?>', '<?php echo $result['level']; ?>')">
                                                                            <i class="fa fa-trash-o"></i> Delete
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                                <?php 
                                                                $cnt=$cnt+1;
                                                            }
                                                        } 
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </section>
                                        <?php 
                                        } else {
                                        ?>
                                            <p style="color:red;"> Sorry Dear: <b style="color:blue;"> <?php echo htmlentities($fullname); ?></b> You Don't Have Access to this Page Thank You.</p>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">
                        <i class="fa fa-exclamation-triangle"></i> Confirm Delete
                    </h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this record?</p>
                    <div class="alert alert-warning">
                        <strong>Programme:</strong> <span id="deleteProgramme"></span><br>
                        <strong>Level:</strong> <span id="deleteLevel"></span>
                    </div>
                    <p class="text-danger"><i class="fa fa-info-circle"></i> This action cannot be undone!</p>
                    <input type="hidden" id="deleteId" value="">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">
                        <i class="fa fa-times"></i> Cancel
                    </button>
                    <button class="btn btn-danger" type="button" id="confirmDeleteBtn">
                        <i class="fa fa-trash-o"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fa fa-check-circle"></i> Success
                    </h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="successMessage">Record deleted successfully!</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="button" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div class="modal fade" id="errorModal" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fa fa-exclamation-circle"></i> Error
                    </h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="errorMessage">An error occurred!</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Existing Modals -->
    <div class="modal fade" id="checkModalEr" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
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

    <div class="modal fade" id="checkModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="color: red;" id="exampleModalLabel">Error Message</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Please Select Status</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

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
                    <p><?php echo htmlentities($error); ?></p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

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
                    <p>Details Is Successfully Submitted</p>
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
    if(!empty($showModalEr)) {
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalEr").modal("show");
            });
        </script>';
    } 
    if(!empty($showModal)) {
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModal").modal("show");
            });
        </script>';
    } 
    if(!empty($showModals)) {
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalh").modal("show");
            });
        </script>';
    } 
    if(!empty($showModalsuc)) {
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalsuc").modal("show");
            });
        </script>';
    } 
    ?>

    <script type="text/javascript">
        // Global variable for delete
        var deleteId = null;
        var deleteProgramme = null;
        var deleteLevel = null;
        
        // Confirm Delete function
        function confirmDelete(id, programme, level) {
            deleteId = id;
            deleteProgramme = programme;
            deleteLevel = level;
            
            $('#deleteProgramme').text(programme);
            $('#deleteLevel').text(level);
            $('#deleteId').val(id);
            $('#deleteModal').modal('show');
        }
        
        // Handle delete confirmation
        $('#confirmDeleteBtn').on('click', function() {
            var btn = $(this);
            var originalHtml = btn.html();
            
            // Disable button and show loading
            btn.prop('disabled', true);
            btn.html('<i class="fa fa-spinner fa-spin"></i> Deleting...');
            
            $.ajax({
                url: window.location.href,
                type: 'POST',
                data: {
                    delete_id: deleteId,
                    delete_programme: deleteProgramme,
                    delete_level: deleteLevel
                },
                dataType: 'json',
                success: function(response) {
                    $('#deleteModal').modal('hide');
                    
                    if(response.success) {
                        // Remove the row from table
                        $('#row_' + deleteProgramme + '_' + deleteLevel).fadeOut(300, function() {
                            $(this).remove();
                            // Re-number the rows
                            renumberRows();
                        });
                        
                        $('#successMessage').text(response.message);
                        $('#successModal').modal('show');
                        
                        // Auto-hide success modal after 2 seconds
                        setTimeout(function() {
                            $('#successModal').modal('hide');
                        }, 2000);
                    } else {
                        $('#errorMessage').text(response.message);
                        $('#errorModal').modal('show');
                    }
                },
                error: function(xhr, status, error) {
                    $('#deleteModal').modal('hide');
                    $('#errorMessage').text('Error: ' + error);
                    $('#errorModal').modal('show');
                },
                complete: function() {
                    btn.prop('disabled', false);
                    btn.html(originalHtml);
                }
            });
        });
        
        // Function to renumber rows after deletion
        function renumberRows() {
            var counter = 1;
            $('#recordsTable tr').each(function() {
                $(this).find('td:first').text(counter);
                counter++;
            });
        }
        
        // Save inline edit function
        function saveInlineEdit(editableObj, column, progs, level) {
            $(editableObj).css("background", "#FFF url(loaderIcon.gif) no-repeat right");
            var customer = {
                editval: $(editableObj).text(),
                programme: progs,
                column: column,
                lvs: level
            };
            $.ajax({
                url: "padit",
                type: "POST",
                data: customer,
                success: function(data) {
                    $(editableObj).css("background", "#FDFDFD");
                    // Optional: Show success toast
                    if(data.trim() === 'success') {
                        // Success handling
                    }
                },
                error: function() {
                    $(editableObj).css("background", "#FFE6E6");
                    setTimeout(function() {
                        $(editableObj).css("background", "#FDFDFD");
                    }, 2000);
                }
            });
        }
        
        function highlightEdit(editableObj) {
            $(editableObj).css("background", "#FFF");
        }
        
        // Add confirmation for form submission
        $(document).ready(function() {
            $('form').on('submit', function(e) {
                var programme = $('select[name="programme"]').val();
                var level = $('select[name="level"]').val();
                var maxm = $('input[name="maxm"]').val();
                var minm = $('input[name="minm"]').val();
                
                if(programme && programme.includes('Please select')) {
                    e.preventDefault();
                    $('#checkModalEr').modal('show');
                    return false;
                }
                
                if(level && level.includes('Select Level')) {
                    e.preventDefault();
                    $('#checkModal').modal('show');
                    return false;
                }
                
                if(parseInt(maxm) <= parseInt(minm)) {
                    e.preventDefault();
                    $('#errorMessage').text('Maximum value must be greater than Minimum value');
                    $('#errorModal').modal('show');
                    return false;
                }
                
                return true;
            });
            
            // Auto-hide success modals
            $('#checkModalsuc').on('shown.bs.modal', function() {
                setTimeout(function() {
                    $('#checkModalsuc').modal('hide');
                    location.reload();
                }, 2000);
            });
        });
    </script>
</body>
</html>
<?php } ?>