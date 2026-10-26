<div class="left-sidebar bg-black-300 box-shadow ">
                        <div class="sidebar-content">
                            <div class="sidebar-nav">
                                <ul class="side-nav color-gray">                 
                                    <li>
                                        <a href="staffportal"><i class="fa fa-user"></i> <span>Profile</span> </a>
                                    </li>

                                    <li class="has-children">
                                        <a href="#"><i class="fa fa-file-text"></i> <span>Transcript Page</span> <i class="fa fa-angle-right arrow"></i></a>
                                        <ul class="child-nav">
                                            
                                            <li><a href="fulltrancript"><i class="fa fa-print"></i> <span>Student Transcript</span></a>
                                            </li>
											<li><a href="fulltrancript"><i class="fa fa-print"></i> <span>Student Transcript</span></a>
                                            </li>
                                        </ul>
                                    </li>

                                    <li class="has-children">
                                        <a href="#"><i class="fa fa-check"></i> <span>Publish Result Page</span> <i class="fa fa-angle-right arrow"></i></a>
                                        <ul class="child-nav">
                                            <li><a href="generalresultapprove"><i class="fa fa-upload"></i><span>General</span></a></li>
                                            <li><a href="indivapproval"><i class="fa fa-eye"></i> <span>Individual</span></a>
                                            </li>
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
                                        <a href="#"><i class="fa fa-check"></i> <span>DisPublish Result Page</span> <i class="fa fa-angle-right arrow"></i></a>
                                        <ul class="child-nav">
                                            <li><a href="resultdisapp"><i class="fa fa-upload"></i><span>General</span></a></li>
                                            <li><a href="resultdisappind"><i class="fa fa-eye"></i> <span>Individual</span></a>
                                            </li>
                                           
                                        </ul>
                                    </li>
                                    <li class="has-children">
                                        <a href="#"><i class="fa fa-university"></i> <span>School Page</span> <i class="fa fa-angle-right arrow"></i></a>
                                        <ul class="child-nav">
                                            <li>
                                                <a href="gradesystem"><i class="fa fa-upload"></i>
                                                    <span>Grading System</span>
                                                </a>
                                            </li> 
                                            <li>
                                                <a href="resultsession"><i class="fa fa-plus"></i>
                                                    <span>Academic Activities</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="facultycreate"><i class="fa fa-plus"></i>
                                                    <span>Create New Faculty</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="newdeptcreate"><i class="fa fa-plus"></i>
                                                    <span>Create New Department</span>
                                                </a>
                                            </li> 
                                             <li>
                                                <a href="newprogrammecreate"><i class="fa fa-plus"></i>
                                                    <span>Create New Programme</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="newdegree"><i class="fa fa-plus"></i>
                                                    <span>Create New Degree</span>
                                                </a>
                                            </li>
                                            
                                                                            
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