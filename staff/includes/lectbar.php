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
                                            <li><a href="courseapprove"><i class="fa fa-registered"></i> <span>Approved Registration</span></a></li>                                           
                                        </ul>
                                    </li>
                                    <li class="has-children">
                                        <a href="#"><i class="fa fa-file-text"></i> <span>Result Page</span> <i class="fa fa-angle-right arrow"></i></a>
                                        <ul class="child-nav">
                                            <li><a href="resultupload"><i class="fa fa-registered"></i> <span>Upload Result</span></a></li>
                                            <li><a href="csvresupload"><i class="fa fa-upload"></i> <span>Excel Result Uploading</span></a></li>
                                            <li><a href="excelresultuploadinglt"><i class="fa fa-refresh"></i> <span>Late Result Uploading1</span></a></li>

                                            <li><a href="resultprinting"><i class="fa fa-print"></i> <span>Print Result</span></a></li>
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
                                        <a href="abaplatform"><i class="fa fa-check-circle"></i> <span>Aba Page</span></a>
                                        
                                    </li>
                                    
									<?php } ?>
									
                                     <li>
                                        <a href="viewallocatecourse"><i class="fa fa-eye"></i> <span>View Allocated Course</span> </a>
                                     
                                    </li>
                                    <li>
                                        <a href="viewregisterstudent"><i class="fa fa-eye"></i> <span>View Registered student</span> </a>
                                     
                                    </li><li class="has-children">
                                        <a href="#"><i class="fa fa-file-text"></i> <span>LMS</span> <i class="fa-regular fa-graduation-cap"></i></a>
                                        <ul class="child-nav">
                                            <li><a href="studymaterial"><i class="fa fa-book"></i> <span>Study Materia</span></a></li>
                                            <li><a href="#outstandingcourse"><i class="fa fa-eye"></i> <span>Online Class</span></a></li>
                                        </ul>
                                    </li>
                                    <!--	//<?php 
                                    //$paych=mysqli_fetch_assoc(mysqli_query($con,"SELECT position FROM staffprive WHERE staffid='ST/SS/24/22'"));

                                  //if ($paych!="")
                                  { ?>
                                    <li>
                                        <a href="tsls"><i class="fa fa-check-circle"></i> <span>TSLS Application</span></a>
                                        
                                    </li>
                                    
									<?php } ?>-->
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