<?php //login here

function cleaner($in) {
    $in = trim(strip_tags($in));
     if (ini_get('magic_quotes_gpc')) { 
        $in = stripslashes($in); 
     } 
    $in=$mysqli->real_escape_string($in);
    
    return $in;
}

function login($user, $pw, $a=false)
{
    $user = cleaner($user);
    $pw = cleaner($pw);
    $sql="SELECT * FROM users WHERE un='$user'";
	if($a) 
		$sql .= " and is_admin='y'";
    $result=@$mysqli->query($sql) or die('Query failed: ' . $mysqli->error);
    $row = @$result->fetch_row();
    $count=@$result->num_rows;
	
	  
    if($count==1){
		
        if($row[2] == crypt($pw,$row[2])) {
            setcookie("shf_user", $row[1], time()+3600 * 24 * 7, "/", ".settlementhousingfund.org");
            setcookie("shf_i", $row[0], time()+3600 * 24 * 7, "/", ".settlementhousingfund.org");
            $_SESSION['shf_i'] = $row[0];
			$_SESSION['f_name'] = $row[3];
			$_SESSION['whole_name'] = $row[3];
            $sql2 = "INSERT INTO session (session, uID, date) VALUES ('" . $_COOKIE['PHPSESSID'] . "', " . $row[0] . ", '" . date('Y-m-d') . "')";
            $result2=@$mysqli->query($sql2) or die('Session Store failed: ' . $mysqli->error);
            
			if($row[5] == 'y') {
				$_SESSION['jbnj_administrator'] = 'yes';
			}
			
			$rere = array('u' => $row[1], 'a' => $row[5]);
			
            return $rere;
        }
    }
    return false;
}

function logout()
{
    if(isset($_SESSION['shf_i'])) {
        $sql = "DELETE FROM session WHERE uID = " . $_SESSION['shf_i'];
        $result=@$mysqli->query($sql);
        //or die('Query failed: ' . $mysqli->error);
        $_SESSION['shf_i'] = $_SESSION['f_name'] = $_SESSION['whole_name'] = "";
		if(isset($_SESSION['jbnj_administrator']))
			$_SESSION['jbnj_administrator'] = '';
            setcookie("shf_user", ' ', time()-3600 * 24 * 7, "/", ".settlementhousingfund.org");
            setcookie("shf_i", ' ', time()-3600 * 24 * 7, "/", ".settlementhousingfund.org");
        return true;
    }
    
}

function check_login($is_admin=false) {
    if(isset($_COOKIE['shf_i'])) {
        if(isset($_SESSION['shf_i'])) {
            $sql = "SELECT * FROM session WHERE uID = " . $_SESSION['shf_i'] . " AND session = '" . $_COOKIE['PHPSESSID'] . "'";
			
            $result=@$mysqli->query($sql) or die('Check Login Query failed: ' . $mysqli->error);
            $row = @$result->fetch_row();
			if($is_admin) {
				if(!isset($_SESSION['jbnj_administrator']) || $_SESSION['jbnj_administrator'] != 'yes') {
					return false;
				}
			}
            if($row[2] != $_COOKIE['shf_i']) 
                return false;
            $count=@$result->num_rows;
            if($count==1)
                return true;
        }
    }
    return false;
}
    
	

?>