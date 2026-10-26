<div class="left-sidebar bg-black-300 box-shadow ">
                        <div class="sidebar-content">
                            <div class="sidebar-nav">
                                <ul class="side-nav color-gray">
                                  
                                    <li>
                                        <a href="staffportal"><i class="fa fa-user"></i> <span>Profile</span> </a>
                                    </li>
                                    <li class="has-children">
                                        <a href="#"><i class="fa fa-university"></i> <span>Student Page</span> <i class="fa fa-angle-right arrow"></i></a>
                                        <ul class="child-nav">
                                            <li>
                                                <a href="stidcardlist"><i class="fa fa-print"></i> <span>Print Student List</span></a>
                                            </li>
                                            <li>
                                                <a href="printdeptstu"><i class="fa fa-print"></i> <span>Department Students</span> </a>
                                            </li>
                                            <li>
                                                <a href="printfacstu"><i class="fa fa-print"></i> <span> Faculty Students</span> </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="has-children">
                                        <a href="#"><i class="fa fa-university"></i> <span>Staff Page</span> <i class="fa fa-angle-right arrow"></i></a>
                                            <ul class="child-nav">
                                                <li>
                                                    <a href="academicstaff"><i class="fa fa-refresh"></i>
                                                        <span>Academic Staff</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="academicstaffac"><i class="fa fa-refresh"></i>
                                                        <span>Faculty Staff</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="academicstaffdp"><i class="fa fa-refresh"></i>
                                                        <span>Department Staff</span>
                                                    </a>
                                                </li>
                                            </ul>
                                    </li>
                                    <li class="has-children">
                                        <a href="#"><i class="fa fa-university"></i> <span>Course Page</span> <i class="fa fa-angle-right arrow"></i>
                                        </a>
                                            <ul class="child-nav">
                                                <li><a href="registeredstudent"><i class="fa fa-print"></i></i> <span>Registered Students</span></a>
                                                </li>
                                                <li><a href="allocatedad"><i class="fa fa-eye"></i></i> <span>Course Allocation</span></a>
                                                </li>
                                                <li>
                                                    <a href="courseregreport"><i class="fa fa-print"></i> <span>Course Registration Report</span> </a>
                                                </li>
                                            </ul>
                                    </li>
                                    <li class="has-children">
                                        <a href="#"><i class="fa fa-university"></i> <span>School Result</span> <i class="fa fa-angle-right arrow"></i>
                                        </a>
                                            <ul class="child-nav">
                                                
                                                <li>
                                                    <a href="departmentsum"><i class="fa fa-eye"></i></i> <span>Department Summary</span></a>
                                                </li>
                                                <li>
                                                    <a href="senatesumary"><i class="fa fa-eye"></i></i> <span>Senate Summary</span></a>
                                                </li>
                                                <li>
                                                    <a href="allocatereverse"><i class="fa fa-eye"></i></i> <span>Result Upload Report</span></a>
                                                </li>
                                            </ul>
                                    </li>
                                    
                                     <?php
                                    $paycht=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM jupcordinator WHERE staffid='".$_SESSION['staffid']."' AND status='active' "));

                                  if ($paycht!="")
                                  { ?>
                                    <li>
                                        <a href="jupebplatform"><i class="fa fa-check-circle"></i> <span>Jupeb Page</span></a>
                                        
                                    </li>
                                    
                                    <?php } ?>
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