<div class="left-sidebar bg-black-300 box-shadow ">
                        <div class="sidebar-content">
                            <div class="sidebar-nav">
                                <ul class="side-nav color-gray">
                                    <li>
                                        <a href="staffportal"><i class="fa fa-user"></i> <span>Profile</span> </a>
                                    </li>
                                   
                                    <li class="has-children">
                                        <a href="#">
                                            <i class="fa fa-credit-card" aria-hidden="true"></i>
                                            <span>Student Payment</span> <i class="fa fa-angle-right arrow"></i>
                                        </a>
                                        <ul class="child-nav">
                                            <li>
                                                <a href="studentcourseclearance"><i class="fa fa-upload"></i> <span>Semester Clearance</span></a>
                                            </li>
                                            <li>
                                                <a href="clearedsturec"><i class="fa fa-refresh"></i> <span>Cleared Record</span></a>
                                            </li>
                                            <li>
                                                <a href="prclearedsturec"><i class="fa fa-refresh"></i> <span>Previous Cleared Record</span></a>
                                            </li>
                                           
                                            <li>
                                                <a href="paymentverification"><i class="fa fa-check-circle"></i> <span>Payment Verification</span></a>
                                            </li>
                                            <li>
                                                <a href="paymenthist"><i class="fa fa-eye"></i> <span>Student Payment History</span></a>
                                            </li>
                                            <li>
                                                <a href="paymenthista"><i class="fa fa-eye"></i> <span>New Student Verification</span></a>
                                            </li>
                                            <li>
                                                <a href="insertfee"><i class="fa fa-eye"></i> <span>Add Payment</span></a>
                                            </li>
                                            <li>
                                                <a href="approve_fee"><i class="fa fa-eye"></i> <span>Approve Payment</span></a>
                                            </li>
                                            
                                        </ul>
                                    </li>
									<li class="has-children">
                                        <a href="#">
                                            <i class="fa fa-credit-card" aria-hidden="true"></i>
                                            <span>Fees Uploading</span> <i class="fa fa-angle-right arrow"></i>
                                        </a>
                                        <ul class="child-nav">
                                            <li>
                                                <a href="paymentuploading"><i class="fa fa-upload"></i> <span>Tuition Fees Uploading</span></a>
                                            </li>
                                            <li>
                                                <a href="paymentuploadings"><i class="fa fa-upload"></i> <span>Other Payment Uploading</span></a>
                                            </li>
                                            <li>
                                                <a href="purposecreate"><i class="fa fa-upload"></i> <span>Create Payment Purpose</span></a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="has-children">
                                        <a href="#">
                                            <i class="fa fa-credit-card" aria-hidden="true"></i>
                                            <span>Payment Report</span> <i class="fa fa-angle-right arrow"></i></a>
                                        <ul class="child-nav">
                                    <li>
                                        <a href="viewallpaymreport"><i class="fa fa-eye"></i> <span>View All Report</span></a>
                                    </li>
                                    <li>
                                        <a href="viewfacultyreport"><i class="fa fa-eye"></i> <span>Programme Report</span></a>
                                    </li><li>
                                        <a href="viewlevelreport"><i class="fa fa-eye"></i> <span>Level Report</span></a>
                                    </li>
                                    <li>
                                        <a href="viewallpaymenreport"><i class="fa fa-eye"></i> <span>Entity Range Report</span></a>
                                    </li>
                                    <li>
                                        <a href="viewallpaymdreport"><i class="fa fa-eye"></i> <span>Date Range Report</span></a>
                                    </li>
                                    
                                    
                                     
                                        </ul>
                                    </li><li class="has-children">
                                        <a href="#">
                                            <i class="fa fa-credit-card" aria-hidden="true"></i>
                                            <span>Payment Summary</span> <i class="fa fa-angle-right arrow"></i></a>
                                        <ul class="child-nav">
                                    <li>
                                        <a href="viewallpaymreports"><i class="fa fa-eye"></i> <span>Summary By Session</span></a>
                                    </li>
                                    <li>
                                        <a href="viewfacultyreports"><i class="fa fa-eye"></i> <span>Summary By Programme</span></a>
                                    </li><li>
                                        <a href="viewlevelreports"><i class="fa fa-eye"></i> <span>Summary By Level</span></a>
                                    </li>
                                    <li>
                                        <a href="viewallpaymdreports"><i class="fa fa-eye"></i> <span>Summary By Date Range</span></a>
                                    </li>
                                    
                                    
                                     
                                        </ul>
                                    </li>
                                    <li class="has-children">
                                <a href="#"><i class="fa fa-university"></i> <span>My Setting</span> <i class="fa fa-angle-right arrow"></i></a>
                                    <ul class="child-nav">
                                    <li>
                                        <li><a href="passwordupdate"><i class="fa fa-book"></i> <span>Update Password</span></a></li>
                                            <li><a href="passportupdate"><i class="fa fa-eye"></i> <span>Update Profile Image</span></a></li>
                                    </li>
                                    </ul>
                                </li>
                                <?php 
                                        $datsvt=mysqli_fetch_assoc(mysqli_query($con,"SELECT session,semester FROM resultsession WHERE description='exam processing'"));
        
                                    $acadv=$datsvt['session'];
                                    $acadsm=$datsvt['semester'];
                                    $coa=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM allocatecourse WHERE staffid='".$_SESSION['staffid']."' AND acadsession='$acadv' AND semester='$acadsm' "));

                                  if ($coa!="")
                                  {
                                    ?>
                                     <li class="has-children">
                                        <a href="#"><i class="fa fa-file-text"></i> <span>Result Page</span> <i class="fa fa-angle-right arrow"></i></a>
                                        <ul class="child-nav">
                                            <li><a href="resultupload"><i class="fa fa-upload"></i> <span>Result Uploading</span></a></li>
                                            
                                            <li><a href="resultupdatec"><i class="fa fa fa-plus"></i> <span>Edit Current Result</span></a></li>
                                            <li><a href="resultprinting"><i class="fa fa-print"></i> <span>Print Result</span></a></li>
                                            
                                            <li>
                                                <a href="examattendance"><i class="fa fa-print"></i> <span>Print Exam Attendance</span> </a>
                                            </li>
                                            <li>
                                                <a href="viewallocatecourse"><i class="fa fa-eye"></i> <span> Allocated Course</span> </a>
                                     
                                            </li>
                                            <li>
                                                <a href="viewregisterstudent"><i class="fa fa-eye"></i> <span> Registered student</span> </a>
                                             
                                            </li>
                                        </ul>
                                    </li>
                                    
                                    <?php
                                    }
                                ?>
                                <li>
                                        
                                         <a href="advance"><i class="fa fa-info-circle"></i>
                                          <span>Advance Form</span>
                                         </a>
                                    </li>
                                     <li>
                                        
                                    <a href="https://chat.whatsapp.com/GfXoh4qZpuUCNdUZf6CvUG"><i class="fa fa-info-circle"></i> <span>Complain</span>

                                    </a>
                                    </li>
                                     <li>
                                        <a href="../logout"><i class="fa fa-sign-out"></i> <span>Logout</span> </a>
                                     
                                    </li>
                                </ul>
                            </div>
                            <!-- /.sidebar-nav -->
                        </div>
                        <!-- /.sidebar-content -->
                    </div>