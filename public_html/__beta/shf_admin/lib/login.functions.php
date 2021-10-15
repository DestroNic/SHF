<?php //login here

function cleaner($in) {
    $in = trim(strip_tags($in));
     if (ini_get('magic_quotes_gpc')) { 
        $in = stripslashes($in); 
     } 
    $in=$mysqli->real_escape_string($in);
    
    return $in;
}

function login($user, $pw)
{
    $user = cleaner($user);
    $pw = cleaner($pw);
    $sql="SELECT * FROM users WHERE un='$user'";
    $result=@$mysqli->query($sql) or die('Query failed: ' . $mysqli->error);
    $row = @$result->fetch_row();
    $count=@$result->num_rows;
      
    if($count==1){
      
        
        if($row[2] == crypt($pw,$row[2])) {
            setcookie("shfuser", $row[1], time()+3600 * 24 * 7, "/", ".settlementhousingfund.org");
            setcookie("shf", $row[0], time()+3600 * 24 * 7, "/", ".settlementhousingfund.org");
            $_SESSION['riverid'] = $row[0];
            $sql2 = "INSERT INTO session (session, uID, date) VALUES ('" . $_COOKIE['PHPSESSID'] . "', " . $row[0] . ", '" . date('Y-m-d') . "')";
            $result2=@$mysqli->query($sql2) or die('Session Store failed: ' . $mysqli->error);
            
            return $row[1];
        }
    }
    return false;
}

function logout()
{
    if(isset($_SESSION['riverid'])) {
        $sql = "DELETE FROM session WHERE uID = " . $_SESSION['riverid'];
        $result=@$mysqli->query($sql);
        //or die('Query failed: ' . $mysqli->error);
        $_SESSION['riverid'] = "";
            setcookie("shfuser", ' ', time()-3600 * 24 * 7, "/", ".settlementhousingfund.org");
            setcookie("shf", ' ', time()-3600 * 24 * 7, "/", ".settlementhousingfund.org");
        return true;
    }
    
}

function check_login() {
    if(isset($_COOKIE['shf'])) {
        if(isset($_SESSION['riverid'])) {
            $sql = "SELECT * FROM session WHERE uID = " . $_SESSION['riverid'] . " AND session = '" . $_COOKIE['PHPSESSID'] . "'";
            $result=@$mysqli->query($sql) or die('Check Login Query failed: ' . $mysqli->error);
            $row = @$result->fetch_row();
            if($row[2] != $_COOKIE['shf']) 
                return false;
            $count=@$result->num_rows;
            if($count==1)
                return true;
        }
    }
    return false;
}
    

