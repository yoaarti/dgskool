<?php

// include '../connect.php';

//     $classId = $_GET['classId'];//
//     // echo "$classId";
//         $q="select * from class_tbl where Id='$classId'";
//         $res=mysqli_query($con,$q);
//         $result=mysqli_fetch_array($res);
//         $querys=mysqli_query($con,"select * from subject_tbl where Class_name='".$result[2]."'");     
                          
        
//         echo'<option value="">--Select Section--</option>';
//         while ($row = mysqli_fetch_array($querys)) 
//         {
            
//         echo'<option value="'.$row['Sub_code'].'" >'.$row['Sub_name'].'</option>';
//         }
//         // //echo '</select>';
//         // echo'<option value="'.$row['Sub_code'].'" >'.$result[2].'</option>';
?>
<?php

include '../connect.php';
session_start();
$instid=$_SESSION['inst_id'];
    $classId = $_GET['classId'];//
    
        $q="select * from class_tbl where Id='$classId' AND Insti_id='$instid'";
        $res=mysqli_query($con,$q);
        $result=mysqli_fetch_array($res);
        $querys=mysqli_query($con,"select * from subject_tbl where Class_name='".$result[2]."' AND Inst_id='$instid'");     
        
        
        echo'<option value="">--Select Section--</option>';
        
            $que=mysqli_query($con,"select * from teacher_wise_subject_tbl where Class_id='$classId' AND Inst_id='$instid'");
            $nor=mysqli_num_rows($que);
            if($nor>0)
            {
                $que="select Sub_code,Sub_name from Subject_tbl where Inst_id='$instid' AND Class_name='".$result[2]."' and NOT Sub_code IN (Select Sub_code From teacher_wise_subject_tbl where Class_id='$classId' AND Inst_id='$instid') ";
                $result=mysqli_query($con,$que);
                while ($r = mysqli_fetch_array($result)) 
                    {   
                    echo'<option value="'.$r['Sub_code'].'" >'.$r['Sub_name'].'</option>';
                    }
                    // echo'<option value="'.$r['Sub_code'].'" >'.$que.'</option>';
            }
            else
            {
                while ($row = mysqli_fetch_array($querys)) 
                    {   
                    echo'<option value="'.$row['Sub_code'].'" >'.$row['Sub_name'].'</option>';
                    }
               
            }
        
        
        
?>
