<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="cache-control" content="no-store, no-cache, must-revalidate, Post-Check=0, Pre-Check=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="-1">
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,400i,500,600,700,700i,900" rel="stylesheet">
    <link rel="shortcut icon" href="<?=FILES_DIR ?>favicon.ico" type="image/x-icon">
    <link rel="icon"  href="<?=FILES_DIR ?>favicon.ico" type="image/x-icon">

    
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>

    
    <script src="https://use.fontawesome.com/a0bf7d3b26.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        
     <?php 
        if(strpos($_SERVER['REQUEST_URI'], "admin")){
            echo '<script src="'.ROOT.DS.VENDORS_DIR.'ckeditor'.DS.'ckeditor.js"></script>';
            echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>';
        }
    ?>
    

    <script>
    $(document).ready(function (){

        var url      = window.location.href; 
        var n = url.indexOf("/ver");

        if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
            $('head').append('<link rel="stylesheet" type="text/css" href="<?=SERVER_DIR?>app/webroot/css/main.css" />');
            //$('head').append('<link rel="stylesheet" type="text/css" href="<?=SERVER_DIR?>app/webroot/css/mobile.css" />');
        } 

        else if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) && n != 0) {
            $('head').append('<link rel="stylesheet" type="text/css" href="<?=SERVER_DIR?>app/webroot/css/main.css" />');
        } 

        else {
            $('head').append('<link rel="stylesheet" type="text/css" href="<?=SERVER_DIR?>app/webroot/css/main.css" />');
        }
    });
    </script>

     <?php 
        if(strpos($_SERVER['REQUEST_URI'], "admin") AND strpos($_SERVER['REQUEST_URI'], "administracao") == false){
            echo '<link rel="stylesheet" type="text/css" href="'.SERVER_DIR.'app/webroot/css/admin.css" />';
        }
    ?>
    
    
    <title><?=SITE_TITLE?></title>
</head>