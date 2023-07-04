<?php
// Connecting to the Database
$servername = "sql101.infinityfree.com";
$username = "if0_34540731";
$password = "TuICUDkWpR";
$database= "if0_34540731_notes"; 
// Create a connection
$conn = mysqli_connect($servername, $username, $password, $database);
// Die if connection was not successful
if (!$conn){
    die("Sorry we failed to connect: ". mysqli_connect_error());
}

// Set the timezone to Indian Standard Time (IST)
date_default_timezone_set('Asia/Kolkata');
// Get the current time in IST
$current_time = date('Y-m-d H:i:s');

// Post method success boolean
$insert = false;
$update = false;
$delete = false;

// Post method runs
if ($_SERVER["REQUEST_METHOD"] == "POST"){
// update post method
if(isset($_POST['snoEdits'])){
  $sno = $_POST['snoEdits'];
  $title = $_POST['titleEdits'];
  $description = $_POST['descEdits'];

  $sql = "UPDATE `todo_list` SET `title` = '$title' , `description` = '$description' WHERE `todo_list`.`sno` = $sno";
  $result = mysqli_query($conn, $sql);
  if($result){
    $update = true;
}
else{
  echo "We could not update the record successfully";
}
}
else{
$title = $_POST["title"];
$description = $_POST["desc"];

// Inserting the data
$sql = "INSERT INTO `todo_list` (`sno`, `title`, `description`, `date`) VALUES (NULL, '$title', '$description', '$current_time')";
$result = mysqli_query($conn, $sql);
if ($result){
  $insert = true;
}
else{
  echo "Sorry! we could'nt saved your data";
}
}
}
if(isset($_GET['delete'])){
  $sno = $_GET['delete'];
  $delete = true;
  $sql = "DELETE FROM `todo_list` WHERE `todo_list`.`sno` = $sno";
  $result = mysqli_query($conn, $sql);
}
?>

<!doctype html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Notebook-App</title>
     <link rel="icon" type="image/x-icon" href="https://cdn.icon-icons.com/icons2/2760/PNG/512/note_list_notes_icon_176382.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Jquery table -->
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
<style>
 body{
    background: linear-gradient(90deg, #833ab4, #3c096c);
  }

  .Add_button{
      background:blue;
      border:none;
      border-radius:30px;
      padding:10px 10px 10px 16px;
      align-items:centre;
  }
  .Add_button:hover{
      background:#0000CC;
      border:1px solid black ;
  }
  .txtadd{
      color:white;
      position:relative;
      bottom:2px;
      right:1.4px;
  }
  .Noteheading{
      color:white;
  }
  #ttle{
      
      background-image: linear-gradient(to right, #ff00cc, #00ffcc);
  -webkit-background-clip: text; /* For Safari/Chrome */
  -webkit-text-fill-color: transparent; /* For Safari/Chrome */
  background-clip: text;
  color: transparent;
  }
  #description{
      /* color:#FFA07A; */
      background-image: linear-gradient(to right, #ff00cc, #00ffcc);
  -webkit-background-clip: text; /* For Safari/Chrome */
  -webkit-text-fill-color: transparent; /* For Safari/Chrome */
  background-clip: text;
  color: transparent;
  }
    #myTable{
        padding:30px 0 0 0;
    }
    #myTable_length,
  #myTable_info {
    padding-top: 30px;
  }
  .dataTables_wrapper .dataTables_paginate .paginate_button.previous,
  .dataTables_wrapper .dataTables_paginate .paginate_button.next {
    padding-top: 30px;
  }
    #myTable th,
    #myTable td,#myTable tr {
      border: 1px solid black;
     
    }
    #title{
        background:linear-gradient(#FFB6C1,white);
    }
    #desc{
        background:linear-gradient(#FFB6C1,white);
    }
   #title:focus {
   background-color:linear-gradient(#F4C2C2,white); 
   }
   #desc:focus {
   background-color:linear-gradient(#F4C2C2,white); 
  }
   #myTable_wrapper {
      overflow-x: auto;
    }
    #myTable {
      width: 100% !important;
    }
    #editModal{
      background: linear-gradient(#FF00FF,#ADD8E6);
    }
   
</style>
  </head>

  <body>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Edit this Note</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="index.php" method="POST">
          <div class="modal-body">
            <input type="hidden" name="snoEdits" id="snoEdits">
            <div class="form-group">
              <label for="title"><b>Note Title</b></label>
              <input type="text" class="form-control" id="titleEdits" name="titleEdits" aria-describedby="emailHelp">
            </div>

            <div class="form-group">
              <label for="desc"><br><b>Note Description</b></label>
              <textarea class="form-control" id="descEdits" name="descEdits" rows="3"></textarea>
            </div> 
          </div>
          <div class="modal-footer d-block mr-auto">
            <button type="button" id="rm"class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Close</button>
            <button type="submit" id="rm"class="btn btn-primary rounded-pill">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Nav bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#"><img src="https://www.entropywins.wtf/blog/wp-content/uploads/2018/10/php-1.png" height="28px" alt="PHP"></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">About-us</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Contact</a>
        </li>
      </ul>
      <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>

<?php
if ($insert){
echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
<strong>Success!</strong> Your data has been saved
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}
if ($update){
  echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Success!</strong> Your data has been updated
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
  }
if ($delete){
  echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Success!</strong> Your data has been deleted
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
  }
?>

<!-- PHP form -->
<div class="container my-4">
  <h2 class="Noteheading">Add Your iNote</h2>
  <form action="index.php" method="POST">
  <div class="form-group">
    <label for="title" class="form-label" id="ttle"><b>Title</b></label>
    <input type="text" class="form-control" id="title" name="title" aria-describedby="title" placeholder="Enter here title!">
  </div>
  <div class="form-group">
    <label for="desc" class="form-label" id="description"><br><b>Description</b></label>
    <textarea class="form-control" id="desc" name="desc" rows="3" placeholder="Enter here description!"></textarea>
  </div><br>
  <button type="submit" class="Add_button"><span class="txtadd">Add note</span></button>
</form>
</div>

<?php
$sql = "SELECT * FROM `todo_list`";
$result = mysqli_query($conn, $sql);
$num_rows = mysqli_num_rows($result);
?>
<br><div class="container my-4" style="background:linear-gradient(180deg, #ADD8E6, #FFFFFF);"><hr>
  <table class="table" id="myTable">
  <thead>
    <tr>
      <th scope="col">S.No</th>
      <th scope="col">Title</th>
      <th scope="col">Description</th>
      <th scope="col">Date</th>
      <th scope="col">Actions&emsp;&emsp;</th>
    </tr>
  </thead>
  <tbody>
    <?php
  // Display the rows returned by the sql query

if($num_rows> 0){
    // We can fetch in a better way using the while loop
    $sno = 1;
    while($row = mysqli_fetch_assoc($result)){
      echo '<tr>
      <th scope="row">'.$sno.'</th>
      <td>'.$row["title"].'</td>
      <td>'.$row["description"].'</td>
      <td>'.$row["date"].'</td>
      <td><button class="edit btn btn-sm btn-primary rounded-pill" id='.$row["sno"].'>Edit</button> <button class="delete btn btn-sm btn-primary rounded-pill" id='.$row["sno"].'>Delete</button></td>
      </tr>';
      $sno = $sno + 1;
}
}
else{
    echo "No data found!";
}
?>
</tbody>
  </table>
    <hr></div>

  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS and dataTables.js -->
  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
    integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
    integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
    crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
    integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
    crossorigin="anonymous"></script>
  <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
  <script>
    $(document).ready(function () {
      $('#myTable').DataTable();
    });
  </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

    <!-- Addtional js -->
<script>
  // edit js
  edits = document.getElementsByClassName('edit');
  Array.from(edits).forEach((element) => {
  element.addEventListener("click", (e) => {
  console.log("edit ");
  tr = e.target.parentNode.parentNode;
  console.log(e);
  title = tr.getElementsByTagName("td")[0].innerText;
  description = tr.getElementsByTagName("td")[1].innerText;
  console.log(title, description);
  titleEdits.value = title;
  descEdits.value = description;
  snoEdits.value = e.target.id;
  console.log(e.target.id);
  $('#editModal').modal('toggle');
  })
})

// delete js
  deletes = document.getElementsByClassName('delete');
  Array.from(deletes).forEach((element) => {
  element.addEventListener("click", (e) => {
  console.log("delete ");
  sno = e.target.id;
  console.log(sno);
  if (confirm("Are you sure you want to delete this note!")) {
          console.log("yes");
          window.location = `index.php?delete=${sno}`;
          // TODO: Create a form and use post request to submit a form
        }
        else {
          console.log("no");
        }
  })
})
</script>

  </body>
</html>