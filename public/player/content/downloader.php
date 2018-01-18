<?php 
   header("Content-Type: application/octet-stream");
   header("Content-Disposition: attachment; filename=". $_GET['name']);
   $path = urldecode($_GET['path']);
   if(isset($path))readfile($path);
?>