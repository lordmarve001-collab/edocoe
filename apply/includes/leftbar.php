<div class="left-sidebar bg-black-300 box-shadow ">
                        <div class="sidebar-content">
                            <div class="sidebar-nav">
                                <ul class="side-nav color-gray">
                                    <li>
                                        <a href="admission"><i class="fa fa-print"></i> <span>Profile</span> </a>
                                    </li>
                                     <li>
                                        <a href="payfees"><i class="fa fa-refresh"></i> <span>Pay Tuition Fee</span> </a>
                                    </li>
                                    <li>
                                        <a href="paymat"><i class="fa fa-money"></i> <span>Pay Matric Fee</span> </a>
                                    </li>
                                    <?php 
                                        $tryc=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM failpayment WHERE (studentid='$matr' OR studentid='$harr')"));
                                        if ($tryc!='') { ?>
                                        <li>
                                        <a href="payverification"><i class="fa fa-refresh"></i> <span>Failed Transaction</span> </a>
                                        </li>    
                                    <?php                                            
                                        }
                                     ?> 
                                     <li>                                            <li><a href="paymentsummary"><i class="fa fa-eye"></i> <span>Payment History</span></a></li>
                                        <a href="logout"><i class="fa fa-sign-out"></i> <span>Logout</span> </a>
                                     
                                    </li>
                            </div>
                            <!-- /.sidebar-nav -->
                        </div>
                        <!-- /.sidebar-content -->
                    </div>