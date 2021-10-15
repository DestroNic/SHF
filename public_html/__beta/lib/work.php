<?php

78687678687687
  /*
ini_set('display_errors', 'on');
error_reporting(E_ALL);
require_once('../lib/constants.php');
require_once(CONFIGURE);
require_once(CONNECTION);


$pw = crypt("092609shf");
$sql = "INSERT INTO users (un, pw, name, email, is_admin) VALUES ('admin2', '$pw', 'Kathy','mike@quondesign.com','y')";
$result=@$mysqli->query($sql) or die('Query failed: ' . $mysqli->error);


$sql = "SELECT * FROM users";
$result=@$mysqli->query($sql) or die('Query failed: ' . $mysqli->error);
while($row = @$result->fetch_array())
	print_r($row);



/*

$sqlFileToExecute = 'calendar.sql';
$f = fopen($sqlFileToExecute,"r+");
 $sqlFile = fread($f,filesize($sqlFileToExecute));
 $sqlArray = explode(';',$sqlFile);
 
 foreach ($sqlArray as $stmt) {
       if (strlen($stmt)>3){
            $result = $mysqli->query($stmt);
              if (!$result){
                 $sqlErrorCode = $mysqli->errno;
                 $sqlErrorText = $mysqli->error;
                 $sqlStmt      = $stmt;
                 break;
              }
           }
      }

 if ($sqlErrorCode == 0){
      echo "<tr><td>Installation was finished succesfully!</td></tr>";
   } else {
      echo "<tr><td>An error occured during installation!</td></tr>";
      echo "<tr><td>Error code: $sqlErrorCode</td></tr>";
      echo "<tr><td>Error text: $sqlErrorText</td></tr>";
      echo "<tr><td>Statement:<br/> $sqlStmt</td></tr>";
   }



this is for creating the first user.
$pw = crypt("759River");

$sql = "UPDATE users SET pw='" . $pw . "' WHERE un='admin'";
$result=@$mysqli->query($sql) or die('Query failed: ' . $mysqli->error);
echo "done";

*/
?>