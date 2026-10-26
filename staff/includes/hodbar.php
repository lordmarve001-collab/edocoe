<div class="left-sidebar bg-black-300 box-shadow ">
                        <div class="sidebar-content">
                            <div class="sidebar-nav">
                                <ul class="side-nav color-gray">
                                    <li>
                                        <a href="staffportal"><i class="fa fa-user"></i> <span>Profile</span> </a>
                                    </li>
                                    <li class="has-children">
                                        <a href="#"><i class="fa fa-file-text"></i> <span>Course Page</span> <i class="fa fa-angle-right arrow"></i></a>
                                        <ul class="child-nav">
                                            <li><a href="courseupload"><i class="fa fa-upload"></i><span>Create Course</span></a></li>
                                            <li><a href="dep_allocation"><i class="fa fa-upload"></i><span>Department Course Allocation</span></a></li>
                                            <li><a href="courseapprove"><i class="fa fa-registered"></i> <span>Approved Registration</span></a></li>
                                            <li><a href="viewdeptmentcourse"><i class="fa fa fa-plus"></i> <span>View Department Courses</span></a></li>
                                            <li><a href="corriculumsetup"><i class="fa fa-eye"></i> <span>Setup Curriculum</span></a>
                                            </li>
                                            <li><a href="allocatedcourse"><i class="fa fa-eye"></i> <span>Allocate Course</span></a></li>
                                            
                                           
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
                                        <a href="#"><i class="fa fa-file-text"></i> <span>Result Page</span> <i class="fa fa-angle-right arrow"></i></a>
                                        <ul class="child-nav">
                                            <li><a href="resultupload"><i class="fa fa-upload"></i></i> <span>Upload Result</span></a></li>
                                            <li><a href="individual_result_upload"><i class="fa fa-registered"></i> <span>Upload Individual Result</span></a></li>
                                            <li><a href="csvresupload"><i class="fa fa-upload"></i> <span>Excel Result Uploading</span></a></li>
                                            <!--<li><a href="excelresultuploading"><i class="fa fa-upload"></i> <span>Excel Result Uploading</span></a></li>
                                            <li><a href="deptapprove"><i class="fa fa-eye"></i> <span>Approve Result</span></a></li>
                                            <li><a href="approved_results"><i class="fa fa-eye"></i> <span>Approved Results</span></a></li>
                                            <li><a href="resultprinting"><i class="fa fa-print"></i> <span>Print Result</span></a></li>
                                            <li><a href="departmentsum"><i class="fa fa-eye"></i> <span>Department Summary</span></a></li>
                                            <!--<li><a href="viewsenateapproval"><i class="fa fa-eye"></i> <span>Senate Summary</span></a></li>
                                            <li><a href="senatesumary"><i class="fa fa-eye"></i> <span>Senate Summary</span></a></li>-->
                                            
                                            
                                        </ul>
                                    </li>
									<li class="has-children">
                                        <a href="#"><i class="fa fa-file-text"></i> <span>Pass Mark Page</span> <i class="fa fa-angle-right arrow"></i></a>
                                        <ul class="child-nav">
                                            <li><a href="departpassmark"><i class="fa fa-upload"></i></i> <span>passmark</span></a></li>
                                            <li><a href="registpast"><i class="fa fa fa-plus"></i> <span>Registration passmark</span></a></li>
                                            
                                        </ul>
                                    </li>
                                    <?php
                                    $paycht=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM jupcordinator WHERE staffid='".$_SESSION['staffid']."' AND status='active' "));

                                  if ($paycht!="")
                                  { ?>
                                    <li>
                                        <a href="jupebplatform"><i class="fa fa-check-circle"></i> <span>PDE Page</span></a>
                                        
                                    </li>
                                    
									<?php }
                                    $paycht=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM abacordinator WHERE staffid='".$_SESSION['staffid']."' AND status='active' "));

                                  if ($paycht!="")
                                  { ?>
                                    <li>
                                        <a href="abaplatform"><i class="fa fa-check-circle"></i> <span>ABA Page</span></a>
                                        
                                    </li>
                                    
									<?php } ?>
                                    <li class="has-children">
                                        <a href="#"><i class="fa fa-university"></i> <span>Admission Page</span> <i class="fa fa-angle-right arrow"></i></a>
                                        <ul class="child-nav">
                                           <li><a href="stidcardlistpro"><i class="fa fa-eye"></i> <span>View Students</span></a>
                                            </li>
                                            <li><a href="stidcardlistprop"><i class="fa fa-print"></i> <span>Print Admitted Students</span></a>
                                            </li>
                                        </ul>
                                    </li>
                                   <li class="has-children">
                                        <a href="#"><i class="fa fa-eye"></i> <span>View Page</span> <i class="fa fa-angle-right arrow"></i></a>
                                        <ul class="child-nav">
                                            <li>
                                        <a href="viewallocatecourse"><i class="fa fa-eye"></i> <span>View Allocated Course</span> </a>
                                     
                                    </li>
                                    <li>
                                        <a href="departmentstaff"><i class="fa fa-eye"></i> <span>View Department Staff</span> </a>
                                     
                                    </li>
                                    <li>
                                        <a href="viewregisterstudent"><i class="fa fa-eye"></i> <span>View Registered student</span> </a>
                                     
                                    </li>
                                    <li>
                                        <a href="departstuden"><i class="fa fa-eye"></i> <span>View Department student</span> </a>
                                     
                                    </li>
                                        </ul>
                                    </li>
                                    <li class="has-children">
                                        <a href="#"><i class="fa fa-print"></i> <span>Printing Page</span> <i class="fa fa-angle-right arrow"></i></a>
                                        <ul class="child-nav">
                                            <li>
                                                <a href="registeredstudent"><i class="fa fa-print"></i></i> <span>Registered Students</span></a>
                                            </li>
                                            <li>
                                                <a href="examattendance"><i class="fa fa-print"></i> <span>Exam Attendance</span> </a>
                                            </li>
                                            <li>
                                                <a href="examattendancehod"><i class="fa fa-print"></i> <span>Other Exam Attendance</span> </a>
                                            </li>
                                            <li>
                                                <a href="studentcoursreprint"><i class="fa fa-print"></i> <span>Print Students Coursereg</span> </a>
                                            </li>
                                            <li>
                                                <a href="studentcoursreprints"><i class="fa fa-print"></i> <span>Print Student Coursereg</span> </a>
                                            </li>
                                            <li>
                                                <a href="viewallocate"><i class="fa fa-print"></i> <span>Course Allocation</span> </a>
                                            </li>
                                            <li>
                                                <a href="exampermit"><i class="fa fa-print"></i> <span>Exam Permit For All</span> </a>
                                            </li>
                                            <li>
                                                <a href="exampermits"><i class="fa fa-print"></i> <span>Individual Exam Permit</span> </a>
                                            </li>
                                           <!-- <li>
                                                <a href="printdeptstu"><i class="fa fa-print"></i> <span>Print Department Student</span> </a>
                                            </li>-->
                                        </ul>
                                    </li>
                                      <li class="has-children">
                                        <a href="#"><i class="fa fa-file-text"></i> <span>Settings</span> <i class="fa fa-angle-right arrow"></i></a>
                                        <ul class="child-nav">
                                            <li><a href="passwordupdate"><i class="fa fa-book"></i> <span>Update Password</span></a></li>
                                            <li><a href="passportupdate"><i class="fa fa-eye"></i> <span>Update Profile Image</span></a></li>
                                        </ul>
                                    </li>
                                <li class="has-children">
                                        <a href="#"><i class="fa fa-file-text"></i> <span>LMS</span> <i class="fa-regular fa-graduation-cap"></i></a>
                                        <ul class="child-nav">
                                            <li><a href="studymaterial"><i class="fa fa-book"></i> <span>Study Materia</span></a></li>
                                            <li><a href="#outstandingcourse"><i class="fa fa-eye"></i> <span>Online Class</span></a></li>
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