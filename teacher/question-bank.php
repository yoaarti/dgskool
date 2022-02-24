<?php
include('../connect.php');
include('../admin/admin-header.php');
?>

<?php

if (isset($_GET['QueId']) && isset($_GET['action']) && $_GET['action'] == "delete") {
  $queId = $_GET['QueId'];
  // echo $Id;
  $query = "delete from question_tbl where Id='$queId'";
  // echo $query;
  $res = mysqli_query($con, $query);
  if ($res) {
  } else {

    $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!" . mysqli_error($con) . "</div>";
  }
}
?>

<head>

  <script>
  function subcodeDropdown(str) {
    if (str == "") {
      document.getElementById("sub_code").innerHTML = "";
      return;
    } else {
      if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
      } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      }
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("sub_code").innerHTML = this.responseText;

        }
      };
      xmlhttp.open("GET", "ajaxSubcode.php?subject=" + str, true);
      xmlhttp.send();
    }
  }

  function sectionDropdown(str) {
    if (str == "") {
      document.getElementById("txtHint").innerHTML = "";
      return;
    } else {
      if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
      } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      }
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("txtHint").innerHTML = this.responseText;
        }
      };
      xmlhttp.open("GET", "../institute-admin/ajaxclass.php?name=" + str, true);
      xmlhttp.send();
    }
  }

  function subjectDropdown(str) {
    if (str == "") {
      document.getElementById("subject").innerHTML = "";
      return;
    } else {
      if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
      } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      }
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("subject").innerHTML = this.responseText;
        }
      };
      xmlhttp.open("GET", "ajaxSubject.php?className=" + str, true);
      xmlhttp.send();
    }
  }
  </script>
</head>

<body>
  <div class="d-flex">
    <?php include("teacher-sidebar.php"); ?>
    <div class="content mt-5 ">
      <!-- <div class="d- justify-content-center"> -->
      <div class="row p-2">
        <form method="get">
          <div class="row ps-5">
            <div class="col-sm-3 col-xs-3">
              <div class="form-group d-flex justify-content-center">
                <select required class="form-select w-100" aria-label="Default select example"
                  onchange="sectionDropdown(this.value);subjectDropdown(this.value)" name="class" required>
                  <?php
                  $qry = "SELECT DISTINCT Name FROM class_tbl ORDER BY Name ASC";
                  $result = $con->query($qry);
                  $num = $result->num_rows;
                  if ($num > 0) {
                    echo '<option value="">---- -Select Class -----</option>';
                    while ($rows = $result->fetch_assoc()) {
                      echo '<option  value="' . $rows['Name'] . '" >' . $rows['Name'] . '</option>';
                    }
                    echo '</select>';
                  }
                  ?>
              </div>
            </div>
            <div class="col-md-3 col-xs-3">
              <div class="form-group d-flex justify-content-center">
                <select required name="section" id='txtHint' class="form-select  w-100" required>
                  <option value="">--Select Section--</option>
                </select>
              </div>
            </div>
            <div class="col-md-3 col-xs-3">
              <div class="form-group">
                <select required class="form-select w-100 " name="subject" id='subject'
                  onchange='subcodeDropdown(this.value)' required>
                  <option value="">----- Select Subject -----</option>
                </select>
              </div>
            </div>
            <div class="col-md-3 col-xs-3">
              <div class="form-group">
                <input type="submit" value="Show Question" name="submit" class="btn bg-navy-blue text-white"></input>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="row m-3 w-100" id="Quetable">
        <?php
        if (isset($_GET['submit'])) {
          $className = $_GET['class'];
          // echo $classId;
          $SubjectId = $_GET['subject'];
          // echo $SubjectId;
          $section = $_GET['section'];

          //----------------subject name----------
          $subName = "select * from subject_tbl where Id='$SubjectId'";
          $sname = mysqli_query($con, $subName);
          $ressub = mysqli_fetch_array($sname);
          // echo $ressub['Sub_name'];

          //------------class name----------

          $classId = "select * from class_tbl where  Name='$className' and Section='$section'";
          $cname = mysqli_query($con, $classId);
          $resCId = mysqli_fetch_array($cname);
          // echo $SubjectId;
          // echo $resCId[0];

          $q = "select * from question_tbl where Subject_id='$SubjectId'  and Class_id='$resCId[0]'";
          $res = mysqli_query($con, $q) or die("Query Failed");
          // print_r($res);
          $nor = mysqli_num_rows($res);
          // echo $nor;
          if ($nor > 0) {
        ?>
        <div class="table-responsive-md table-md w-100 p-5" id="tblQue">
          <br>
          <h2 class="navy-blue">Question Bank</h2>
          <hr><br>
          <table class="table table-hover">
            <thead>
              <tr class="navy-blue">
                <th scope="th-md" style="width: 1%;"></th>
                <th scope="th-md" style="width: 3%;">ID</th>
                <th scope="th-md" style="width: 25%">Question</th>
                <th scope="th-md" style="width: 1%;">Class_Name</th>
                <th scope="th-md" style="width: 2%;">Section</th>
                <th scope="th-md" style="width: 8%;">Subject</th>
                <th scope="th-md" style="width: 7%;">Option A</th>
                <th scope="th-md" style="width: 7%;">Option B</th>
                <th scope="th-md" style="width: 7%;">Option C</th>
                <th scope="th-md" style="width: 7%;">Option D</th>
                <th scope="th-md" style="width: 7%;">Option E</th>
                <th scope="th-md" style="width: 9%;">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
                  while ($r = mysqli_fetch_array($res)) {
                    echo "<tr>";
                    echo "<th><input class='form-check-input' type='checkbox' name='selectedQue[]' value=''></th>";
                    echo "<th scope='row'>$r[0]</th>";
                    echo "<td>$r[1]</td>";
                    echo "<td>$className</td>";
                    echo "<td>$r[3]</td>";
                    echo "<td>$ressub[3]</td>";
                    $q1 = "select * from answer_tbl where Question_Id=$r[0] ";
                    $res1 = mysqli_query($con, $q1);
                    $nor1 = mysqli_num_rows($res1);
                    $options = array();
                    if ($nor1 > 0) {
                      while ($r1 = mysqli_fetch_array($res1)) {
                        array_push($options, $r1[1]);
                        if ($r1['isCorrect'] == '1') {
                          // echo "yes";
                          echo "<td style='color:red'>$r1[1]</td>";
                          // $ans = $r1[1];
                        } else {
                          echo "<td>$r1[1]</td>";
                        }
                      }

                      for ($i = 0; $i < 5 - $nor1; $i++) {
                        echo "<td> - </td>";
                      }
                      // echo "<td>$ans</td>";
                    }
                    $encodedOpt = json_encode($options);
                    // echo json_encode($options);
                    echo "<td>
                    <a href='?action=delete&QueId=" . $r[0] . "&section=" . $r[3] . "&class=" . $className . "&subject=" . $SubjectId . "&submit=Show Question' >
                    <i class='fa fa-trash mr-2'></i></a>
                    <a  href='?QueId=" . $r[0] . "&section=" . $r[3] . "&class=" . $className . "&subject=" . $SubjectId . "&submit=Show Question' data-dismiss='modal' data-id='$r[0]' question='$r[1]' data-options='$encodedOpt'
              data-toggle='modal' data-target='#editQuestion' >
              <i class='fa fa-edit text-primary'></i></a>
              </td>";
                  }
                  echo "</tr>";
                  echo "
            </tbody>";
                  echo "
          </table>"; ?>
              <div class="row mt-5">
                <div class="form-group d-flex justify-content-center">
                  <button type="button" class="btn bg-navy-blue text-white m-5" name="createExam">Create Exam</button>
                </div>
              </div>
              <?php } else {
                echo "<center><h1>No Data Found</h1></center>";
              }
                ?>
        </div>
        <?php
        }
          ?>

      </div>

      <div class="modal fade bd-example-modal-lg" id="editQuestion" style="height: 100%;">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title navy-blue" id="exampleModalLabel">Edit Question</h5>
              <hr>
            </div>
            <div class="modal-body">
              <div class="modal-body p-3">
                <form id="editQueForm" method="post">
                  <input type="hidden" id="qid" name="qid" />

                  <div class="form-floating m-2">
                    <textarea class="form-control" placeholder="Question " id="floatingTextarea2" style="height: 100px"
                      name="equestion"></textarea>
                    <label for="floatingTextarea2">Question</label>
                  </div>
                  <div id="optRow">
                    <input type="hidden" id="dynamicOptions" value="0" name="dynamicOptions" />
                  </div>
                  <div class="d-flex justify-content-end m-2">
                    <button type="button" class="btn bg-navy-blue text-white m-2" id="addOpt">
                      <i class="fas fa-plus"></i> Add More Option
                    </button>

                  </div>

                  <div class="modal-footer">
                    <input type="submit" class="btn bg-navy-blue text-white" value="Save Changes"
                      name="editQue"></input>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                  </div>
              </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include("../guest/footer.php"); ?>
</body>

<script>
$('#editQuestion').on('show.bs.modal', function(e) {
  // console.log("hello");
  // get information to update quickly to modal view as loading begins
  var opener = e.relatedTarget; //this holds the element who called the modal

  //we get details from attributes
  var equestion = $(opener).attr('question');
  var id = $(opener).data('id');
  var options = $(opener).data('options');
  var len = options.length;
  // console.log(len);

  $('#editQueForm').find('[name="equestion"]').val(equestion);
  $('#editQueForm').find('[id="qid"]').val(id);

  //------------rendering option element----
  var html1 = "",
    i;

  for (var i = 0; i < len; i++) {

    html1 += "<div class='form-floating m-2'>";
    html1 += "<input class='form-check-input' type='checkbox'>";
    html1 +=
      "<input type=text' class='form-control' id='floatingInputInvalid' placeholder='Option' name='opt" + i +
      "' value='" + options[i] +
      "'>";
    html1 += "<label for = 'floatingInputInvalid'> Option </label>";
    html1 += "</div >";

  }
  document.getElementById("optRow").innerHTML = html1;
  if (len == 5) {
    $('#addOpt').prop('disabled', true);
  } else {
    $('#addOpt').prop('disabled', false);
  }

});

$("#addOpt").click(function() {
  var count = $("#optRow").children().length ?? 0;
  console.log('count', count);
  // console.log(document.getElementById("dynamicOptions").setAttribute('value', count + 1));

  var html = '';

  html += " <div class='input-group mb-3'  id='optRowChild" + count + "'>";
  html += "<div class='form-floating flex-grow-1' >";
  html +=
    " <input class='form-check-input' type='checkbox'  id='flexCheckDefault' >";
  html += "<input type='text' class='form-control'  name='opt" + (count + 1) + "' placeholder='Option' >";
  html +=
    '<label for="opt" > Option </label>';
  html += ' </div>';
  html +=
    "<button type='button' id='clr' name='clr' class='input-group-text btn btn-outline-secondary' onClick='funRm(" +
    (count + 1) + ")'><i class='fas fa-times text-dark'></i></button >";
  html += '</div>';

  console.log(count);
  $('#optRow').append(html);
  if (count == 4) {
    $('#addOpt').prop('disabled', true);
  }

});

function funRm(index) {
  console.log("#optRowChild" + index);
  $("#optRowChild" + index).remove();
  $('#addOpt').prop('disabled', false);
}
</script>
<!-- update Query -->
<?php
if (isset($_POST['editQue'])) {

  $qid = $_POST['qid'];
  $cid = "select * from question_tbl where Id='$qid'";
  $res1 = mysqli_query($con, $cid);
  $nor1 = mysqli_fetch_array($res1);

  $que = $_POST['equestion'];
  // echo $id;
  $q = "select * from answer_tbl where Question_Id='$qid'";
  $res = mysqli_query($con, $q);
  $nor = mysqli_num_rows($res);
  // $nor2 = ;
  // echo $nor[0];
  // echo "abc";
  $aid = array();
  while ($r = mysqli_fetch_array($res)) {
    // $aid=array()
    // echo "<br>$r[0]";
    array_push($aid, $r[0]);
  }
  // print_r($aid);
  // $a = json_encode($aid);

  for ($x = 0; $x <= $nor; $x++) {
    $opt = "opt" . $x;
    // print_r($aid);
    // echo "abc";

    if (!empty($_POST[$opt])) {
      $updateAns = "update answer_tbl set Answer='$_POST[$opt]' where Question_Id='$qid' and Id='$aid[$x]'";
      // echo $updateAns;
      $result = mysqli_query($con, $updateAns);
      if ($result) {
        // echo "<script>location.reload();</script>";
      } else {

        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!" . mysqli_error($con) . "</div>";
      }
    }
  }
  for ($x = $nor + 1; $x < 5; $x++) {
    $opt = "opt" . $x;
    // echo "<br> 2: $_POST[$opt]";
    if (!empty($_POST[$opt])) {
      // echo "1: $_POST[$opt]";
      $insertAns = "insert into answer_tbl values(null,'$_POST[$opt]','$qid',0)";
      $result = mysqli_query($con, $insertAns);
      if ($result) {
        echo "<script>location.reload();</script>";
      } else {

        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!" . mysqli_error($con) . "</div>";
      }
    }
  }
}
?>

</html>