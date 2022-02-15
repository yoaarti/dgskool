<?php include("../connect.php");
include("change-header.php");
$Ins_id=$_SESSION['inst_id'];
$statusMsg="";
//------------------------SAVE--------------------------------------------------
if(isset($_POST['save'])){
    
    $className=$_POST['Name'];
    $Stud_limit=$_POST['Stud_limit'];
    $query=mysqli_query($con,"select * from class_tbl where Name ='$className'");
    $ret=mysqli_fetch_array($query);

    if($ret > 0){ 

        $statusMsg = "<div class='alert alert-danger'>This Class Already Exists!</div>";
        
      }
    else{

        $query=mysqli_query($con,"insert into class_tbl(Insti_id,Name,Section,Stud_limit) values('$Ins_id','$className','A','$Stud_limit')");

    if ($query) {
        
        $statusMsg = "<div class='alert alert-success'  >Created Successfully!</div>";
    }
    else
    {
         $statusMsg = "<div class='alert alert-danger'>An error Occurred</div>";
    }
  }
}

//--------------------EDIT------------------------------------------------------------

 if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "edit")
	{
        $Id= $_GET['Id'];
        

        $query=mysqli_query($con,"select * from class_tbl where Id ='$Id'");
        $row=mysqli_fetch_array($query);

        //------------UPDATE-----------------------------

        if(isset($_POST['update'])){
    
            $className=$_POST['Name'];
            $Stud_limit=$_POST['Stud_limit'];
            $q=mysqli_query($con,"select * from class_tbl where  Name ='$className'");
            $res=mysqli_fetch_array($q);
            $query1=mysqli_query($con,"select * from class_tbl where  Id ='$res[Id]'");
            $ret=mysqli_fetch_array($query1);
            if($ret > 0)
            { 

              if($Id==$res['Id'])
              {
               
                $query=mysqli_query($con,"update class_tbl set Stud_limit='$Stud_limit' where Id='$Id'");
 
                  if ($query) {
                      
                      echo "<script type = \"text/javascript\">
                      window.location = (\"create-class.php\")
                      </script>"; 
                  }
                  else
                  {
                      $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
                  }
              }
              else
              {
                $statusMsg = "<div class='alert alert-danger'>This Class Already Exists!</div>"; 
              }
              
            }
            else
            {
              $query=mysqli_query($con,"update class_tbl set Name='$className' ,Stud_limit='$Stud_limit' where Id='$Id'");
 
            if ($query) {
                
                echo "<script type = \"text/javascript\">
                window.location = (\"create-class.php\")
                </script>"; 
            }
            else
            {
                $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
            }
            }
            
        }
    }


//--------------------------------DELETE------------------------------------------------------------------

  if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "delete")
	{
        $Id= $_GET['Id'];

        $query = mysqli_query($con,"DELETE FROM class_tbl WHERE Id='$Id'");

        if ($query == TRUE) {

                echo "<script type = \"text/javascript\">
                window.location = (\"create-class.php\")
                </script>";  
        }
        else{

            $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>"; 
         }
      
  }



?>
<head>
    <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>  -->
	<link href="https://fonts.googleapis.com/css2?family=Aclonica&family=Nova+Slim" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <!-- <link href="css/ruang-admin.min.css" rel="stylesheet"> -->
</head>
<html>
<body>
    <div class="container p-5 text-muted h6" >
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Create Class</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="./">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create Class</li>
                    
                </ol>
          </div>
          <div class="card mb-4" style='box-shadow: rgba(0, 0, 0, 0.30) 0px 3px 8px;'>
                <div class="card-header py-4 d-flex flex-row align-items-center justify-content-between">
                  <h4 class="m-0 font-weight-bold text-primary">Add Class</h4>
                  <?php echo $statusMsg; ?>
                  
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">
                        <div class="col">
                            <label class="form-control-label p-2">Class Name<span class="text-danger ml-2">*</span></label>
                            <input type="text" class="form-control" name="Name" value="<?php if(isset($row['Name'])) echo $row['Name'];?>" placeholder="Class Name" required>
                        </div>
                        <div class="col">
                            <label class="form-control-label p-2">Student Limti<span class="text-danger ml-2">*</span></label>
                            <input type="number" class="form-control" name="Stud_limit" value="<?php if(isset($row['Stud_limit'])) echo $row['Stud_limit'];?>" required placeholder="Student Limit">
                        </div>
                    </div>
                    <?php
                    if (isset($Id))
                    {
                    ?>
                    <button type="submit" name="update" class="btn btn-warning">Update</button>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php
                    } else {           
                    ?>
                    <button type="submit" name="save" class="btn btn-primary">Save</button>
                    <?php
                    }         
                    ?>
                  </form>
                </div>

          </div>
          <div class="card mb-4">
                <div class="card-header py-4 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="h4 m-0 font-weight-bold text-primary">All Classes</h6>
                 
                </div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                    <thead class="thead-light">
                      <tr>
                        <th>#</th>
                        <th>Class Name</th>
                        <th>Section</th>
                        <th>Student Limit</th>
                        <th>Edit</th>
                        <th>Delete</th>
                      </tr>
                    </thead>
                  
                    <tbody>

                  <?php
                      $query = "SELECT * FROM class_tbl";
                      $rs = $con->query($query);
                      $num = $rs->num_rows;
                      $sn=0;
                      if($num > 0)
                      { 
                        while ($rows = $rs->fetch_assoc())
                          {
                             $sn = $sn + 1;
                            echo"
                              <tr>
                                <td>".$sn."</td>
                                <td>".$rows['Name']."</td>
                                <td>".$rows['Section']."</td>
                                <td>".$rows['Stud_limit']."</td>
                                <td><a href='?action=edit&Id=".$rows['Id']."'><i class='fas fa-fw fa-edit'></i>Edit</a></td>
                                <td><a href='?action=delete&Id=".$rows['Id']."'><i class='fas fa-fw fa-trash'></i>Delete</a></td>
                              </tr>";
                          }
                      }
                      else
                      {
                           echo   
                           "<div class='alert alert-danger' role='alert'>
                            No Record Found!
                            </div>";
                      }
                      
                      ?>
                    </tbody>
                  </table>
                </div>
            </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
   <!-- Page level plugins -->
  <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script>
    $(document).ready(function () {
      $('#dataTable').DataTable(); // ID From dataTable 
      $('#dataTableHover').DataTable(); // ID From dataTable with Hover
    });
  </script>

</body>
</html>
