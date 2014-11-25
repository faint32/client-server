<?php  

if($_SERVER['REQUEST_METHOD']=='POST') {
  move_uploaded_file($_FILES["test_file"]["tmp_name"], 
  "." . $_FILES["test_file"]["name"]);
  echo "<p>File uploaded.  Thank you!</p>";
}

?>