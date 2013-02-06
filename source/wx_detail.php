<?php

if (isset($_COOKIE['auth'])) 
{
 	echo $_COOKIE['auth']."<br/>";
   	exit;
} 

include_once template("./wx/template/detail");
?>