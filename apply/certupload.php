<?php
session_start();
$connection = TRUE;
include '../include/dbconfig.php';
$harr = $_SESSION["staffid"];
if (strlen($_SESSION['staffid']) == "") {
    header("Location: logout");
    exit();
} else {
    include 'dataco.php';
    
    // Handle document removal - FIXED
    if (isset($_POST['removedd'])) {
        $doc_id = isset($_POST['doc_id']) ? mysqli_real_escape_string($con, $_POST['doc_id']) : '';
        if (!empty($doc_id)) {
            $delete_query = mysqli_query($con, "DELETE FROM cleardoc WHERE id='$doc_id' AND applid='{$_SESSION['staffid']}'");
            if ($delete_query) {
                $error = "Document Successfully Removed";
                $showModalEr = 'true';
            } else {
                $error = "Error removing document. Please try again.";
                $showModalEr = 'true';
            }
        } else {
            $error = "Invalid document ID.";
            $showModalEr = 'true';
        }
    }
    
    // Handle completion button
    if (isset($_POST['jchew'])) {
        $qu = mysqli_query($con, "UPDATE applicant SET cert='1' WHERE applid='" . $_SESSION['staffid'] . "'");
        if ($qu) {
            header("Location: appsummary");
            exit();
        } else {
            $error = "Error While Updating";
            $showModalEr = 'true';
        }
    }
    
    // Handle document upload
    if (isset($_POST['savees'])) {
        if (empty($_POST['proty'])) {
            $error = "Select Document Type";
            $showModalEr = 'true';
        } else {
            $prodt = $_POST["proty"];
            $id = $_POST['proty'];
            $filenamg = basename($_FILES['passport']['name']);
            $filetmp = $_FILES['passport']['tmp_name'];
            $filesize = $_FILES['passport']['size'];
            $extension = array("png", "jpg", "jpeg", "PNG", "JPG", "JPEG");
            // Get image file extension
            $file_extension = pathinfo($_FILES["passport"]["name"], PATHINFO_EXTENSION);
            // Validate file input to check if is not empty
            if (!file_exists($_FILES["passport"]["tmp_name"])) {
                $error = "Choose image file to upload.";
                $showModalEr = 'true';
            } // Validate file input to check if is with valid extension
            else if (!in_array($file_extension, $extension)) {
                $error = "Upload valid images. Only PNG and JPEG are allowed.";
                $showModalEr = 'true';
            } else if (($_FILES["passport"]["size"] > 500000)) {
                $error = "Image size exceeds 500KB.";
                $showModalEr = 'true';
            } // Validate image file size
            else {
                $nwtourimg = explode(".", $filenamg);
                $fileExt = end($nwtourimg);
                $res = trim(str_ireplace(array('\'', '"', ',', ';', '<', '>', '/', ''), '', $_SESSION['staffid']));
                $fileBase = $res . "" . $id . "." . $fileExt;
                $dir = "photo/";
                $final_dir = $dir . $fileBase;
                
                if ($id == '2') {
                    $igd = "Birth/Age Declaration";
                } elseif ($id == '3') {
                    $igd = "Olevel Result First Sitting";
                } elseif ($id == '4') {
                    $igd = "Olevel Result Second Sitting";
                } else {
                    $igd = "Document";
                }
                
                $sq = mysqli_query($con, "SELECT * FROM cleardoc WHERE applid='" . $_SESSION['staffid'] . "' AND documtype='$id'");
                if (mysqli_num_rows($sq) == 1) {
                    $error = "Document " . $igd . " Already Exists";
                    $showModalEr = 'true';
                } /*Ends here*/
                else {
                    //inserting to the database
                    if (move_uploaded_file($filetmp, $final_dir)) {
                        $qu = mysqli_query($con, "INSERT INTO cleardoc(applid, document, documtype) VALUES ('" . $_SESSION['staffid'] . "','$final_dir','$id')");
                        if ($qu) {
                            // Refresh the page to show new document
                            echo '<script>window.location.href = "ndupload";</script>';
                            exit();
                        } else {
                            $error = "Error saving document to database.";
                            $showModalEr = 'true';
                        }
                    } else {
                        $error = "Failed to upload file. Please check directory permissions.";
                        $showModalEr = 'true';
                    }
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
    <style type="text/css">
        #blah {
            width: 170.256px;
            height: 159px;
            border: 2px solid black;
        }
    </style>
</head>
<body class="top-navbar-fixed">
    <div class="main-wrapper">
        
        <!-- ========== TOP NAVBAR ========== -->
        <?php include('include/topbar.php'); ?>
        <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
        <div class="content-wrapper">
            <div class="content-container">
                
                <!-- ========== LEFT SIDEBAR ========== -->
                <?php include('include/leftbar.php'); ?>
                <!-- /.left-sidebar -->
                
                <div class="main-page">
                    <div class="container-fluid">
                        <div class="row page-title-div">
                            <div class="col-md-6">
                                <h2 class="title">Certificate Support Document</h2>
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
                                    <div class="panel-body">
                                        <?php
                                        $stanam = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM applicant WHERE applid='$staffid' AND status='submitted'"));
                                        if ($stanam > 0) {
                                        ?>
                                            <div class="table-responsive table-bordered">
                                                <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th><button class="btn btn-primary" type="button" data-toggle="modal" data-target="#form_modal"><span class="fa fa-plus"> Add Document</span></button></th>
                                                        </tr>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Document Type</th>
                                                            <th>Image</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $sql = "SELECT * FROM cleardoc WHERE applid='" . $_SESSION['staffid'] . "' ORDER BY documtype ASC";
                                                        $query = mysqli_query($con, $sql);
                                                        $results = mysqli_fetch_all($query, MYSQLI_ASSOC);
                                                        $cnt = 1;
                                                        
                                                        foreach ($results as $result) {
                                                        ?>
                                                            <tr>
                                                                <td><?php echo htmlentities($cnt); ?></td>
                                                                <?php if ($result['documtype'] == '2') { ?>
                                                                    <td><?php echo htmlentities('Birth/Age Declaration'); ?></td>
                                                                <?php } elseif ($result['documtype'] == '3') { ?>
                                                                    <td><?php echo htmlentities('Olevel Result First Sitting'); ?></td>
                                                                <?php } elseif ($result['documtype'] == '4') { ?>
                                                                    <td><?php echo htmlentities('Olevel Result Second Sitting (If Applicable)'); ?></td>
                                                                <?php } ?>
                                                                <td>
                                                                    <img data-u='image' src="<?php echo htmlentities($result['document']); ?>" class='img-fluid' style="width:100px;height:50px;">
                                                                </td>
                                                                <td>
                                                                    <button class="btn btn-danger userinf" data-id="<?php echo $result['id']; ?>" data-type="<?php echo $result['documtype']; ?>">
                                                                        <i class="fa fa-trash"></i>
                                                                    </button>
                                                                    <button class="btn btn-info userinfo" data-id="<?php echo $result['id']; ?>" data-doc="<?php echo htmlentities($result['document']); ?>">
                                                                        <span class="fa fa-eye"></span>
                                                                    </button>
                                                                 </nil>
                                                            </tr>
                                                        <?php
                                                            $cnt++;
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                                <?php if (mysqli_num_rows($query) >= 2) { ?>
                                                    <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                        <button name="jchew" class="btn btn-primary"><span class="fa fa-save"></span> Complete and Next Page</button>
                                                    </form>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                        
                                        <?php
                                        $stanam = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM applicant WHERE applid='$staffid' AND (status='Pending' OR status='Approved')"));
                                        if ($stanam > 0) {
                                        ?>
                                            <div class="table-responsive table-bordered">
                                                <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Document Type</th>
                                                            <th>Image</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $sql = "SELECT * FROM cleardoc WHERE applid='" . $_SESSION['staffid'] . "' ORDER BY documtype ASC";
                                                        $query = mysqli_query($con, $sql);
                                                        $results = mysqli_fetch_all($query, MYSQLI_ASSOC);
                                                        $cnt = 1;
                                                        
                                                        foreach ($results as $result) {
                                                        ?>
                                                            <tr>
                                                                <td><?php echo htmlentities($cnt); ?></td>
                                                                <?php if ($result['documtype'] == '2') { ?>
                                                                    <td><?php echo htmlentities('Birth/Age Declaration'); ?></td>
                                                                <?php } elseif ($result['documtype'] == '3') { ?>
                                                                    <td><?php echo htmlentities('Olevel Result First Sitting'); ?></td>
                                                                <?php } elseif ($result['documtype'] == '4') { ?>
                                                                    <td><?php echo htmlentities('Olevel Result Second Sitting (If Applicable)'); ?></td>
                                                                <?php } ?>
                                                                <td>
                                                                    <img data-u='image' src="<?php echo htmlentities($result['document']); ?>" class='img-fluid' style="width:400px;height:300px;">
                                                                 </td>
                                                             </tr>
                                                        <?php
                                                            $cnt++;
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Error Modal -->
                            <div class="modal fade" id="checkModalEr" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                                <div class="modal-dialog modal-dialog-scrollable" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" style="color: red;" id="exampleModalLabel">Message</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                            
                            <!-- View Document Modal -->
                            <div class="modal fade" id="viewModal" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Document Detail</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body text-center" id="teacher_details">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Delete Document Modal - FIXED -->
                            <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header" style="background: #dc3545; color: white;">
                                            <h4 class="modal-title"><i class="fa fa-trash"></i> Confirm Delete</h4>
                                            <button type="button" class="close" data-dismiss="modal" style="color: white;">&times;</button>
                                        </div>
                                        <form method="POST" action="">
                                            <div class="modal-body text-center">
                                                <i class="fa fa-exclamation-triangle" style="font-size: 48px; color: #dc3545; margin-bottom: 15px;"></i>
                                                <p>Are you sure you want to delete this document?</p>
                                                <p><strong id="deleteDocName"></strong></p>
                                                <p class="text-muted small">This action cannot be undone.</p>
                                                <input type="hidden" name="doc_id" id="deleteDocId">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                <button type="submit" name="removedd" class="btn btn-danger">Yes, Delete</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Add Document Modal -->
                            <div class="modal fade" id="form_modal" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form method="POST" action="" enctype="multipart/form-data">
                                            <div class="modal-header">
                                                <h3 class="modal-title">Add New Document</h3>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="col-md-2"></div>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <label>Document Type</label>
                                                            <select name="proty" class="form-control" id="default" required="required">
                                                                <option selected disabled>Please Select</option>
                                                                <option value="2">Birth/Age Declaration</option>
                                                                <option value="3">Olevel Result First Sitting</option>
                                                                <option value="4">Olevel Result Second Sitting (if Applicable)</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Document Image </label>
                                                            <input type="file" name="passport" onchange="readURL(this);" accept=".jpg,.jpeg,.png" class="form-control" required />
                                                        </div>
                                                        <div class="form-group">
                                                            <div style="margin:auto;width: 200px;" class="img-thumbnail">
                                                                <table>
                                                                    <tr>
                                                                        <td>
                                                                            <img id="blah" src="img/document.png" alt="Your Document">
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="clear:both;"></div>
                                            <div class="modal-footer">
                                                <button name="savees" class="btn btn-primary"><span class="fa fa-save"></span> Save</button>
                                                <button class="btn btn-danger" type="button" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-container -->
        </div>
        <!-- /.content-wrapper -->
    </div>
    <!-- /.main-wrapper -->
    
    <!-- ========== COMMON JS FILES ========== -->
    <script src="../clearance/js/jquery-2.2.4.min.js"></script>
    <script src="../clearance/js/bootstrap.min.js"></script>
    <script src="../clearance/js/main.js"></script>
    
    <script>
        $(document).ready(function() {
            
            // Read URL for image preview
            window.readURL = function(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#blah').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            };
            
            // View document details
            $('.userinfo').click(function() {
                var docId = $(this).data('id');
                var docSrc = $(this).data('doc');
                
                $('#teacher_details').html('<img src="' + docSrc + '" class="img-responsive" style="max-width: 100%; max-height: 500px; margin: 0 auto;">');
                $('#viewModal').modal('show');
            });
            
            // Delete document - FIXED
            $('.userinf').click(function() {
                var docId = $(this).data('id');
                var docType = $(this).data('type');
                var docName = '';
                
                if (docType == '2') {
                    docName = 'Birth/Age Declaration';
                } else if (docType == '3') {
                    docName = 'Olevel Result First Sitting';
                } else if (docType == '4') {
                    docName = 'Olevel Result Second Sitting';
                }
                
                $('#deleteDocId').val(docId);
                $('#deleteDocName').text(docName);
                $('#deleteModal').modal('show');
            });
            
        });
    </script>
    
    <?php
    if (!empty($showModalEr)) {
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $("#checkModalEr").modal("show");
                setTimeout(function(){
                    window.location.href = "ndupload";
                }, 2000);
            });
        </script>';
    }
    ?>
    
</body>
</html>
<?php } ?>