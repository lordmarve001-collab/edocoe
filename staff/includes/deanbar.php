<div class="left-sidebar bg-black-300 box-shadow ">
                        <div class="sidebar-content">
                            <div class="sidebar-nav">
                                <ul class="side-nav color-gray">
                                  
                                    <li>
                                        <a href="staffportal"><i class="fa fa-user"></i> <span>Profile</span> </a>
                                    </li>
                                    <li class="has-children">
                                        <a href="#"><i class="fa fa-university"></i> <span>Faculty Students</span> <i class="fa fa-angle-right arrow"></i></a>
                                        <ul class="child-nav">
                                            <li><a href="allfastudent"><i class="fa fa-eye"></i><span>All Students</span></a></li>
                                            <li><a href="allfastudentses"><i class="fa fa-eye"></i><span>By Academic Session</span></a></li>
                                            <li><a href="allfastudentdep"><i class="fa fa-eye"></i> <span>By Department</span></a></li>
                                            <!--<li><a href="allfastudentpro"><i class="fa fa-eye"></i> <span>By Programme</span></a></li>
                                            <li><a href="#"><i class="fa fa-eye"></i> <span>Disapproved Course</span></a>-->
                                            </li>                                           
                                        </ul>
                                    </li>
                                    <li class="has-children">
                                        <a href="#"><i class="fa fa-university"></i> <span>Faculty</span> <i class="fa fa-angle-right arrow"></i></a>
                                        <ul class="child-nav">
                                            <li><a href="allfastaffs"><i class="fa fa-eye"></i><span>All Staff</span></a></li>
                                            <li><a href="allfastaffsdep"><i class="fa fa-eye"></i> <span>Staff By Department</span></a></li>                       
                                            <li><a href="depofficial"><i class="fa fa-eye"></i> <span>Assign Department Official</span></a></li>
                                            <li><a href="examofficerapp"><i class="fa fa fa-plus"></i> <span>Assign exam Officer</span></a></li>

                                        </ul>
                                    </li>
                                     <?php
                                    $paycht=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM examoff WHERE staffid='".$_SESSION['staffid']."' AND acadsession='2025/2026' "));

                                  if ($paycht!="")
                                  { ?>
                                    <li>
                                        <a href="jupebplatform"><i class="fa fa-check-circle"></i> <span>Department Exam Officer</span></a>
                                        
                                    </li>
                                    
                                    <?php } ?>
                                    <li class="has-children">
                                        <a href="#"><i class="fa fa-university"></i> <span>Faculty Result</span> <i class="fa fa-angle-right arrow"></i></a>
                                        <ul class="child-nav">
                                            <li><a href="senateapproval"><i class="fa fa-eye"></i> <span>Faculty Result Approval</span></a></li>
                                            <li><a href="departmentsum.php"><i class="fa fa-eye"></i> <span> Department Summary</span></a></li>
                                            <li><a href="senatesumary.php"><i class="fa fa-eye"></i> <span>Faculty Summary</span></a></li>                                            
                                            <li><a href="examofficerapp"><i class="fa fa fa-plus"></i> <span>Assign exam Officer</span></a></li>
                                            <!--<li><a href="leveladvicersetup"><i class="fa fa-eye"></i> <span>Assigned Level advicer</span></a></li>-->
                                       
                                        </ul>
                                    </li>
                                    <li>
                                     <!--<li class="has-children">
                                        <a href="#"><i class="fa fa-file-text"></i> <span>Result Page</span> <i class="fa fa-angle-right arrow"></i></a>
                                        <ul class="child-nav">
                                            <li><a href="#"><i class="fa fa-check"></i> <span>Result Approve</span></a></li>
                                            <li><a href="#"><i class="fa fa-registered"></i> <span>Course Registration</span></a></li>
                                        </ul>
                                    </li>-->
                                    <li class="has-children">
                                        <a href="#"><i class="fa fa-undo"></i> <span>Reversal Page</span> <i class="fa fa-angle-right arrow"></i></a>
                                        <ul class="child-nav">
                                            <li><a href="resultreverse"><i class="fa fa-check"></i> <span>Course Result Approve</span></a></li>
                                            <li><a href="#"><i class="fa fa-check"></i> <span>Department Result Approve</span></a></li>
                                            <li><a href="courseregreverse"><i class="fa fa-registered"></i> <span>Course Registration</span></a></li>
                                            <li><a href="courseregreversepro"><i class="fa fa-registered"></i> <span>Programme Registration</span></a></li>
                                            
                                        </ul>
                                    </li>
                                    <li class="has-children">
                                        <a href="#"><i class="fa fa-file-text"></i> <span>LMS</span> <i class="fa fa-angle-right arrow"></i></a>
                                        <ul class="child-nav">
                                            <li><a href="studymaterial"><i class="fa fa-book"></i> <span>Study Materia</span></a></li>
                                            <li><a href="#outstandingcourse"><i class="fa fa-eye"></i> <span>Online Class</span></a></li>
                                        </ul>
                                    </li>
                                     <li>
                                        <a href="viewallocatecourse.php"><i class="fa fa-eye"></i> <span>View Allocated Course</span> </a>
                                     
                                    </li>
                                    <li>
                                        <a href="viewregisterstudent.php"><i class="fa fa-eye"></i> <span>View Registered student</span> </a>
                                     
                                    </li> 
                                    <li class="has-children">
                                        <a href="#"><i class="fa fa-file-text"></i> <span>Settings</span> <i class="fa fa-angle-right arrow"></i></a>
                                        <ul class="child-nav">
                                            <li><a href="passwordupdate"><i class="fa fa-book"></i> <span>Update Password</span></a></li>
                                            <li><a href="passportupdate"><i class="fa fa-eye"></i> <span>Update Profile Image</span></a></li>
                                        </ul>
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