<?php
   $id = uniqid("");
?>
<html>
<head><title>Upload Example</title></head>
<body>

<script src="http://maps.google.com/maps?file=api&v=2&key=<yourkeyhere>"
            type="text/javascript"></script>

<script type="text/javascript">

function getProgress(){
  GDownloadUrl("getprogress.php?progress_key=<?php echo($id)?>", 
               function(percent, responseCode) {
                   document.getElementById("progressinner").style.width = percent+"%";
                   if (percent < 100){
                        setTimeout("getProgress()", 100);
                   }
               });

}

function startProgress(){
    document.getElementById("progressouter").style.display="block";
    setTimeout("getProgress()", 1000);
}

</script>

<iframe id="theframe" name="theframe" 
        src="upload.php?id=<?php echo($id) ?>" 
        style="border: none; height: 100px; width: 400px;" > 
</iframe>
<br/><br/>

<div id="progressouter" style=
   "width: 500px; height: 20px; border: 6px solid red; display:none;">
   <div id="progressinner" style=
       "position: relative; height: 20px; background-color: purple; width: 0%; ">
   </div>
</div>

</body>
</html>