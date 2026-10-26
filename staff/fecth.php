<?php
$connection = TRUE;
include '../include/dbconfig.php';
$facult=$_GET["facult"];
$dat=mysqli_fetch_assoc(mysqli_query($con,"SELECT session,description,deadline FROM resultsession WHERE description='Hostel Allocation'"));
$ses=$dat['session'];
if ($facult !="")
 {
 	$newfc=array();
	$res=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM hostelroom WHERE id='$facult'"));
	$hst=$res['hostelname'];
	$stp=$res['stperoom'];
	$shp=$res['numofroom'];
    $array = array();
	echo "<option selected='selected' disabled='disabled'>Please Select Room</option>";
	for($x=2;$x<=$shp;$x++)
    {
    	 	
       $array[] = $x;  
    	
    }

    foreach($array as $key => $value) {
            //$rooms=$value;
            //echo $value;
            
           
            $_POST['points']=0;
            $_POST['pointt']=0;
            $sch=0; 
            $chp="SELECT * FROM hostelroomalloc WHERE  hostelname='$facult' AND roomno='$value'";
            $stmtp = mysqli_query($con,$chp);
                $resulterp = mysqli_fetch_all($stmtp,MYSQLI_ASSOC);
           foreach($resulterp as $row)
            {        
                    $_POST['points']=($row['bedno']);
                    $_POST['pointt']=($row['roomno']);
                    $sch++;
                
            }
            if ($stp==$sch) {
              $newfc[]=$_POST['pointt'];
            }
            //echo $_POST['pointt'];
        //$newfc[]= $_POST['pointt'];
            
} 
// foreach($newfc As $pt){
//     echo $pt;
// }
$result = array_diff($array,$newfc);
        foreach($result as $keys => $values) {
            echo   '<option value="'.$values.'">'.$values.'</option>';
            }
} 
?>