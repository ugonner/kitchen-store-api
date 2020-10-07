<?php
//for deployment
//1. upload whole project to a folder at same level with publichtml
//2. cut or move public folder into a folder in public html eg; api
//3. change index.php in the cut-public folder, line 24 and 34,
      //app bootstrap and vendor path to reflect where you uploaded your whole app

//4. do symlink between actual storage destination and the storage destination in cut_public folder
    //run in terminal of cpanel
    //ln -s targetdirectory linkdirectory
    //example; ln -s /home/cpanelusername/api/storage/app/public/images /home/cpanelusername/public_html/api/storage/images

//5. add function to reset value of public_path() function
    //
    //added by ee to set publicpath to reflect change of public folder to public_html
    //$app->bind('path.public', function() {
        //return __DIR__  ;
    //});
