<div class="left-sidebar bg-black-300 box-shadow ">
                        <div class="sidebar-content">
                            <div class="sidebar-nav">
                                <ul class="side-nav color-gray">
                                    <li>
                                        <a href="admission"><i class="fa fa-print"></i> <span>Profile</span> </a>
                                    </li>
                                    <?php 
                $docut=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM studentpayment WHERE studentid='".$_SESSION['staffid']."' AND  purpose='Acceptance Fee' AND status='Success' "));
if ($docut!='') {?>
                                <li class="has-children">
                            <a href="#"><i class="fa fa-print" aria-hidden="true"></i><span>Print Page</span> <i class="fa fa-angle-right arrow"></i></a>
                                <ul class="child-nav">
                                               <!-- <li><a href="admission_letter" target="_blank"><i class="fa fa-print"></i> <span>Admission Letter</span></a></li>-->
                                                <li><a href="printdetail" target="_blank"><i class="fa fa-print"></i> <span>Acknowledgement Card</span></a></li>
                                                <!--<li><a href="printexampass" target="_blank"><i class="fa fa-print"></i> <span>Teaching Practice Form</span></a></li>-->
                                                
                                            </ul>                                     
                                        </li>    
                                    
                                     <li>
                                        <a href="payfees"><i class="fa fa-refresh"></i> <span>Pay Tuition Fee</span> </a>
                                    </li>
                                    <?php }?>
                                    <!--<li>
                                        <a href="paymat"><i class="fa fa-money"></i> <span>Pay Matric Fee</span> </a>
                                    </li>-->
                                    <?php 
                    $docut=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM studentpayment WHERE studentid='".$_SESSION['staffid']."' AND (purpose='ND Tuition Fee' OR purpose='JCHEW Tuition Fee' OR purpose='CERT. Tuition Fee')"));
                        if ($docut!='') { ?>
                                        <li>
                                        <a href="matric"><i class="fa fa-refresh"></i> <span>Get Matric No</span> </a>
                                        </li>    
                                    <?php                                            
                                        }
                                     ?> 
                                     </li>
                                     <li><a href="paymentsummary"><i class="fa fa-eye"></i> <span>Payment History</span></a></li>
                                        <a href="logout"><i class="fa fa-sign-out"></i> <span>Logout</span> </a>
                                     
                                    </li>
                            </div>
                            <!-- /.sidebar-nav -->
                        </div>
                        <!-- /.sidebar-content -->
                    </div>