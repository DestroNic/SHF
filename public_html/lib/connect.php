<?
require_once(CONFIGURE);


$connect = new mysqli($database_server, $database_user, $database_pass);
IF (!$connect)
{
echo $errors['01'];
}
IF (!@$connect->select_db($database_name))
{
echo $errors['02'];
}

//$success = login($user, $pw);
?>