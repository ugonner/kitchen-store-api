
<?php

$targetFolder = $_SERVER['DOCUMENT_ROOT'] . '/../api/storage/app/public';
$linkFolder = $_SERVER['DOCUMENT_ROOT'] . '/api/storage';
symlink($targetFolder, $linkFolder) or die("error creating symlink");
echo 'Symlink process successfully completed';
?>