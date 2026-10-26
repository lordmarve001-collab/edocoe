<?php
session_start();
$connection=TRUE;
include '../include/dbconfig.php';

if(isset($_GET['userid']))
{

$appli= $_GET['userid'];
	
		?>
		<div class="row">
					
				<div class="col-md-12">
					<table id="example" class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Staff ID</th>
                                                            <th>Full Name</th>
                                                            <th>Rank</th>
                                                            <th>Amount</th>
                                                            <th>Date</th>
                                                            <th>Status</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        
                                                        $sql = "SELECT  * FROM requestform WHERE staffid='".$appli."' AND status!='Pending' ORDER BY date";

                                                        $query = mysqli_query($con, $sql);
                                                        $results = mysqli_fetch_all($query, MYSQLI_ASSOC);
                                                        $cnt = 1;
                                                        
                                                        foreach ($results as $result) {   ?>
                                                            <tr>
                                                                <td><?php echo htmlentities($cnt); ?></td>
                                                                <td><?php echo htmlentities($result['staffid']) ?></td>
                                                                <td><?php echo htmlentities($result['fullname']); ?></td>
                                                                <td><?php echo htmlentities($result['rank']); ?></td>
                                                                <td>₦ <?php echo htmlentities($result['amount']); ?></td>
                                                                <td><?php echo htmlentities($result['adate']); ?></td>
                                                                <td><?php echo htmlentities($result['status']); ?></td>
                                                                
                                                            </tr>
                                                            <?php

                                                            $cnt = $cnt + 1;
                                                            
                                                        } 
                                                    ?>
                                                    </tbody>
                                                </table>	
				</div>
		</div>
		<?php
	}

?>