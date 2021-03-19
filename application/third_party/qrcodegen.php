<?php 

    include('phpqrcode/qrlib.php'); 
     
    // outputs image directly into browser, as PNG stream 
    QRcode::png($_GET['data']);

?>