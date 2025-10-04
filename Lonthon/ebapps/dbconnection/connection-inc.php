<?php
$connectdb = new mysqli(EB_HOSTNAME,EB_DB_USERNAME,EB_DB_PASSWORD,EB_DATABASE);
// Check connection
if ($connectdb -> connect_errno)
{
echo $connectdb -> connect_error;
exit();
}
?>