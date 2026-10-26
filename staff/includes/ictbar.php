<div class="left-sidebar bg-black-300 box-shadow ">
   <div class="sidebar-content">
            <div class="sidebar-nav">
                <ul class="side-nav color-gray">
                   <li>
                      <a href="staffportal"><i class="fa fa-user"></i> <span>Profile</span> </a>
                    </li>
                    
                   <li class="has-children">
                                        <a href="#"><i class="fa fa-university"></i> <span>ID Card Page</span> <i class="fa fa-angle-right arrow"></i></a>
                                            <ul class="child-nav">
                                                <li>
                                                    <a href="staffidcard">
                                                        <i class="fa fa-pencil"></i> 
                                                        <span>Staff Card</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="studentidcard">
                                                        <i class="fa fa-user"></i> 
                                                        <span>Student Card</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#staffidcardback" target='_blank'>
                                                        <i class="fa fa-pencil"></i> 
                                                        <span>Staff Card Back</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#studidcardback" target='_blank'>
                                                        <i class="fa fa-user"></i> 
                                                        <span>Student Card Back</span>
                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="userpassupdate">
                                                        <i class="fa fa-refresh"></i> 
                                                        <span>Update Passport</span>
                                                    </a>
                                                </li>
                                                <!--<li>
                                                    <a href="registsignpas">
                                                        <i class="fa fa-upload"></i> 
                                                        <span>Registrar Signature</span>
                                                    </a>
                                                </li> --> 
                                                       
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
                            <!--<li class="has-children">
                                <a href="#"><i class="fa fa-university"></i> <span>Hods/Lecturer</span> <i class="fa fa-angle-right arrow"></i></a>
                            <ul class="child-nav">
                             <li>
                                    <a href="courseup"><i class="fa fa-upload"></i> <span>Create Course </span></a>
                                </li>
                                </ul>
                                 </li>-->
                            <li class="has-children">
                                        <a href="#"><i class="fa fa-university"></i> <span>School Page</span> <i class="fa fa-angle-right arrow"></i></a>
                                        <ul class="child-nav">
                                            
                                            
                                            <li>
                                                <a href="studentview"><i class="fa fa-plus"></i>
                                                    <span>Student</span>
                                                </a>
                                            </li>
                                            
											<!--<li>
                                                <a href="addoldschoolname">
                                                    <i class="fa fa-plus"></i> 
                                                    <span>Old School Name</span>
                                                </a>
                                            </li>-->
                                            <li>
                                            <a href="addoldstudent"><i class="fa fa-plus"></i>
                                                <span>Existing Student</span>
                                            </a>
                                            </li>
                                            <li>
                                                <a href="recordupda"><i class="fa fa-refresh"></i>
                                                            <span>Record Update</span>
                                                </a>
                                            </li>
											
                                            <li><a href="passwordreset"><i class="fa fa-undo"></i><span>Password Reset</span></a></li>
                                            <!--<li>
                                <a href="gstcordinatorapp"><i class="fa fa-plus-circle"></i>
                                    <span>Gst Cordinator</span>
                                </a>
                            </li>
							<li>
                                                <a href="abcordinatorapp"><i class="fa fa-plus-circle"></i>
                                                    <span>ABA Cordinator</span>
                                                </a>
                                            </li>
                            <li>
                                <a href="jpcordinatorapp"><i class="fa fa-plus-circle"></i>
                                    <span>PDE Cordinator</span>
                                </a>
                            </li>-->
                                        </ul>
                                    </li>
                                    <li class="has-children">
                        <a href="#"><i class="fa fa-university"></i> <span>Student Page</span> <i class="fa fa-angle-right arrow"></i></a>
                            <ul class="child-nav">
                               
                                <li>
                                    <a href="resultcheck"><i class="fa fa-check"></i>
                                        <span>Check Result</span>
                                    </a>
                                </li>
                               
                               
                                            <!--<li>
                                <a href="studedith">
                                    <i class="fa fa-pencil"></i> 
                                    <span>Edit Student Record</span>
                                </a>
                            </li>-->
                            
                            <li>
                                <a href="studentcoursepr"><i class="fa fa-print"></i>
                                    <span>Print Student Courses</span>
                                </a>
                            </li>
                            <li>
                                <a href="studentcoursreprints"><i class="fa fa-print"></i>
                                    <span>Print Course Registration</span>
                                </a>
                            </li>
                            
                            
                            </ul>
                    </li>
                           <li class="has-children">
                                        <a href="#"><i class="fa fa-university"></i> <span>Admission Page</span> <i class="fa fa-angle-right arrow"></i></a>
                                        <ul class="child-nav">
                                            <li>
                                </a></li>
                                            <!--<a href="admissionapprov"><i class="fa fa-eye"></i>
                                    <span>Bulk Approved</span>
                                </a>
                            </li>
                            <li>
                                <a href="admissionapprovs"><i class="fa fa-eye"></i>
                                    <span>Single Approved</span>
                                </a>
                            </li><li>
                                 <a href="insertadmi"><i class="fa fa-plus"></i>
                                                 <span>Insert Admission</span>
                                                </a>
                                            </li>-->
							<li>
                                <a href="admissionapprovas"><i class="fa fa-eye"></i>
                                    <span>Clearance Confirmation</span>
                                </a>
                            </li>
                                            <li>
                                                <a href="stidcardlista"><i class="fa fa-print"></i>
                                                    <span>Print Admission List</span>
                                                </a>
                                            </li><li>
                                                <a href="stidcardlistc"><i class="fa fa-print"></i>
                                                    <span>Print CLeared List</span>
                                                </a>
                                            </li>
                                          </a></li>
                                            <!-- <li>
                                                <a href="admission_data_edit"><i class="fa fa-print"></i>
                                                    <span>Applicant Data Edit</span>
                                                </a>
                                            </li>--> <li><a href="stidcardlistaf"><i class="fa fa-print"></i> <span>Print List By School</span></a>
                                            <!--<li><a href="stidcardlistaf"><i class="fa fa-print"></i> <span>Print List By Faculty</span></a>
                                                <li><a href="stidcardlistast"><i class="fa fa-print"></i> <span>Print List All State</span></a>
                                            </li>
                                            <li><a href="stidcardlistastb"><i class="fa fa-print"></i> <span>Print List By State</span></a>
                                            </li>
                                            <li>
                                                <a href="admissionlist"><i class="fa fa-eye"></i>
                                                    <span>View Confirmed</span>
                                                </a>
                                            </li>-->
                                            
                                           
                                        </ul>
                                    </li>
                                    <li class="has-children">
                        <a href="#"><i class="fa fa-university"></i> <span>Staff Page</span> <i class="fa fa-angle-right arrow"></i></a>
                            <ul class="child-nav">
                            <li>
                                <a href="staffedith">
                                    <i class="fa fa-pencil"></i> 
                                    <span>Edit Record</span>
                                </a>
                            </li>
                            <li>
                                                    <a href="addnewuser">
                                                        <i class="fa fa-pencil"></i> 
                                                        <span>Staff</span>
                                                    </a>
                                            </li><li>
                                                    <a href="newusers">
                                                        <i class="fa fa-pencil"></i> 
                                                        <span>Existing Staff</span>
                                                    </a>
                                            </li>
                            <!--<li>
                                <a href="stafffacupdate">
                                    <i class="fa fa-pencil"></i> 
                                    <span>College Record</span>
                                </a>
                            </li>-->
                            
                            </ul>
                    </li>
                                    <!--<li class="has-children">
                                        <a href="#"><i class="fa fa-university"></i> <span>College Page</span> <i class="fa fa-angle-right arrow"></i></a>
                                        <ul class="child-nav">
                                            <li>
                                                <a href="facultycreate"><i class="fa fa-plus"></i>
                                                    <span>Create College</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="newdeptcreate"><i class="fa fa-plus"></i>
                                                    <span>Create Department</span>
                                                </a>
                                            </li> 
                                             <li>
                                                <a href="newprogrammecreate"><i class="fa fa-plus"></i>
                                                    <span>Create Programme</span>
                                                </a>
                                            </li>
                                            
                                            <li>
                                                <a href="newdegree"><i class="fa fa-plus"></i>
                                                    <span>Create Degree</span>
                                                </a>
                                            </li>
                                            
                                        </ul>
                                    </li>-->
                                     <li class="has-children">
                                <a href="#"><i class="fa fa-gear"></i> <span>My Setting</span> <i class="fa fa-angle-right arrow"></i></a>
                                    <ul class="child-nav">
                                    <li>
                                        <a href="passportupdate"><i class="fa fa-refresh"></i>
                                            <span>Update Passport</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="passwordupdate"><i class="fa fa-undo"></i><span>Update Password</span></a>
                                    </li>
                                    </ul>
                                </li>
                                    <li>
                                        
                                    <a href="https://web.whatsapp.com/send?phone=8164550281&text=Hello!%20"><i class="fa fa-info-circle"></i> <span>Complain</span>

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