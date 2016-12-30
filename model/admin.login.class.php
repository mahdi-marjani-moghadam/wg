<?

class adminLogin
{

	private static function GetHash()
	{
		return '%%1^^@@REWcmv21))--';
	}

	function encrypt($string, $key) 
	{
		$result = '';
		for($i=0; $i<strlen($string); $i++) 
		{
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)+ord($keychar));
			$result.=$char;
		}
		
		return base64_encode($result);
	}
		
	function decrypt($string, $key)
	{
		$result = '';
		$string = base64_decode($string);
		
		for($i=0; $i<strlen($string); $i++)
		{
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)-ord($keychar));
			$result.=$char;
		}
	
		return $result;
	}
	function getSession_id()
	{

		$session['decrypt'] = $this->decrypt($_SESSION["sessionID"],$this->GetHash());
		$session['encrypt'] = $_SESSION["sessionID"];

		return $session;
	}

	function loginform($message = '')
	{
		global $conn, $messageStack;

		include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/admin.login.php");
		die();
	}

	function login() 
	{
		global $admin_info, $messageStack,$company_info;

        $db = new dbConn();
        $db = $db->getConnection();

		$username = handleData($_REQUEST["username"]);
		$password = handleData($_REQUEST["password"]);

		if($username == "" || strlen($username) > 20)
			$messageStack->add_session('login', "Username is not valid", 'error');
			
		if($password == "" || strlen($password) > 20)
			$messageStack->add_session('login', "Password is not valid", 'error');

		if ($messageStack->size('login') > 0) {
			//redirectPage($_SERVER['HTTP_REFERER'],"");
		}

        $sql = "DELETE FROM sessions WHERE last_access_time < (NOW()-3000000)";

        $db->query($sql);

		$password = md5($password);


		$sql = "SELECT `admin_id` ,`comp_id` , `name`, `family` FROM `admin` where `comp_id` = '".$company_info['comp_id']."' AND `username` = '".$username."' AND password = '".$password."'";

        $admin_rs = $db->query($sql);

        $obj = $admin_rs->fetch(PDO::FETCH_OBJ);


		if(!$admin_rs)
		{
            print_r($db->errorInfo());
		}

        $count = $admin_rs->rowCount();

		if($count == 0)
		{
          	$messageStack->add_session('login', "Username or Password is not correct", 'error');
			redirectPage($_SERVER['HTTP_REFERER'],"");
		}
		elseif($count)
		{
			
			$sql = "DELETE FROM sessions WHERE admin_id='". $obj->admin_id . "'";
            $db->exec($sql);
			$sql = "DELETE FROM login_as WHERE admin_id='". $obj->admin_id . "'";
			$db->exec($sql);

			$sql = "
					  insert into sessions(admin_id,comp_id,remote_addr,last_access_time)
			  values
			  		  (" . $obj->admin_id . ",'".$obj->comp_id."', '". $_SERVER["REMOTE_ADDR"] . "', '" .getDateTime(). "')";
			$rs = $db->query($sql);

			if(!$rs)
			{
                print_r($db->errorInfo());
			}

			$_SESSION["sessionID"] = $this->encrypt($db->lastInsertId(),$this->GetHash());
			$_SESSION["adminUsername"] = $obj->name . " " . $obj->family;

			if(isset($remember_me))
			{
				setcookie("sessionID",$_SESSION["sessionID"], time()+3600000000000, "/", $_SERVER['HTTP_HOST']);
			}
			else
			{
				setcookie("sessionID", $_SESSION["sessionID"], time()+3600, "/", $_SERVER['HTTP_HOST']);
			}
			
			$admin_info = $this->checkLogin();
			$resultLog  = $this->_setAdminLog($admin_info['admin_id']);
			if(!$resultLog)
			{
				///set notification	
			}
			$messageStack->add_session('redirect', "Welcome to Admin Panel", 'success');

			redirectPage(RELA_DIR ,"");
		}
	}

	function checkLogin()
	{
        global $db;

		if(!isset($_SESSION["sessionID"]))
		{
			if(!isset($_COOKIE["sessionID"]))
			{

				return -1;
			}
			else
			{
				$sessionID = $this->decrypt($_COOKIE["sessionID"],$this->GetHash());
			}
		}
		else
		{
			$sessionID = $this->decrypt($_SESSION["sessionID"],$this->GetHash());
		}

		$row = $db->query("SELECT admin_id FROM sessions_admin WHERE session_id = '$sessionID'");

        $row = $row->fetch(PDO::FETCH_OBJ);
		if(!$row)
		{
			return -1;
		}




        $rs = $db->query("select * from admin where admin_id='".$row->admin_id."'");

		if(!$rs)
		{
			return -1;
		}


        $obj = $rs->fetch(PDO::FETCH_ASSOC);


		return $obj;
	}	
	
	
	function logout()
	{
        $db = new dbConn();
        $db = $db->getConnection();

		if(isset($_SESSION["sessionID"]))		
		{
			$sessionID = $this->decrypt($_SESSION["sessionID"],$this->GetHash());
			
			setcookie("sessionID", $sessionID, time()-10000, "/", $_SERVER['HTTP_HOST']);

			$sql = "delete from sessions where session_id='$sessionID'";
            $rs = $db->query($sql);

			$sql = "delete from login_as where session_id='$sessionID'";
			$rs = $db->query($sql);

            if(!$rs)
            {
                print_r($db->errorInfo());
            }
		}
		elseif(isset($_COOKIE["sessionID"]))		
		{
			$sessionID = $this->decrypt($_COOKIE["sessionID"],$this->GetHash());
			
			setcookie("sessionID", $sessionID, time()-10000, "/", $_SERVER['HTTP_HOST']);

			$sql = "delete from sessions where session_id='$sessionID'";
            $rs = $db->query($sql);

			$sql = "delete from login_as where session_id='$sessionID'";
			$rs = $db->query($sql);

            if(!$rs)
            {
                print_r($db->errorInfo());
            }
		}

		session_unset();
		redirectPage(RELA_DIR, "You have successfully signed out");
	}
	
	function checkAdminTask($admin_id, $task_id)
	{
		global $conn;
		
		if($admin_id == 100)
		{
			return 0;
		}
		
		$sql = "select * from admin_task a, tasks b	where a.admin_id = " . $admin_id . " and a.task_id = b.task_id and b.task_id = '" . $task_id . "'";
		$rs  = $conn->Execute($sql);
		if(!$rs)
		{
			return -1;
		}
		if($rs->RecordCount() != 1)
		{
			return -2;
		}
		return 0;
	}
	
	private function _setAdminLog()
	{
        $db = new dbConn();
        $db = $db->getConnection();
		
		$temp	= func_get_args();
		$adminID = $temp[0];	
		$IP	  = $_SERVER['REMOTE_ADDR'];
		
		$Query = $db->exec("INSERT INTO admin_log(admin_id,ip,access_time) VALUES ('$adminID','$IP',NOW())");

		if(!$Query)
		{
			return FALSE;
		}
		
		return TRUE;
		
	}
	function getCompanyBysessionID($sessionID)
	{
		$conn = dbConn::getConnection();


		$sessionID = $this->decrypt($sessionID,$this->GetHash());

		$sql = "

			SELECT
			  `tbl_company`.`comp_name`
			FROM
			  `sessions`
			  LEFT JOIN `tbl_company` ON `sessions`.`comp_id` = `tbl_company`.`comp_id`
			WHERE
			  `sessions`.`session_id` = '$sessionID'" ;

		$stmt = $conn->prepare($sql);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);

		if (!$stmt)
		{
			$result['result'] = -1;
			$result['Number'] = 1;
			$result['msg'] = $conn->errorInfo();
			return $result;
		}
		if ($stmt->rowCount())
		{
			$result= $stmt->fetch();
			$comp_name=$result['comp_name'];
		}else
		{
			$comp_name=-1;
		}

		return $comp_name;
	}

	function loginas($_input)
	{
		global $admin_info, $messageStack,$company_info;
		$db = dbConn::getConnection();

		$sessionID=$_input['s'];
		//$sessionID='2471';
		//$sessionID=$this->encrypt($sessionID,$this->GetHash());
		$sessionID = $this->decrypt($sessionID,$this->GetHash());

		$sql = "DELETE FROM login_as WHERE last_access_time < (NOW()-3000000)";

		$db_rs = $db->query($sql);


		$sql = "SELECT `admin_id` ,`comp_id`,`ascomp_id` FROM `login_as` where `ascomp_id` = '".$company_info['comp_id']."' AND `session_id` = '".$sessionID."' ";

		$admin_rs = $db->query($sql);

		if(!$admin_rs)
		{
			print_r($db->errorInfo());
		}

		$obj = $admin_rs->fetch(PDO::FETCH_OBJ);

		$count = $admin_rs->rowCount();

		if($count == 0)
		{
			$messageStack->add_session('login', "Username or Password is not correct", 'error');
			return;
		}
		elseif($count)
		{
			$sql = "DELETE FROM sessions WHERE admin_id='". $obj->admin_id . "'";
			$rs = $db->query($sql);

			$sql = "DELETE FROM login_as WHERE admin_id='". $obj->admin_id . "'
			 AND  `session_id` <> '".$sessionID."'";
			$rs = $db->query($sql);

			$getDateTime=date("Y-m-d H:i:s");
			$sql = "
					  insert into sessions(`admin_id`,`comp_id`,`remote_addr`,`last_access_time`)
			  values
					(
					'".$obj->admin_id ."',
					'".$obj->ascomp_id."',
					'". $_SERVER["REMOTE_ADDR"] . "',
					'".$getDateTime."'
					)";
			$rs = $db->query($sql);
			//print_r($rs);
			//die($sql);
			if(!$rs)
			{
				print_r($db->errorInfo());
			}

			$_SESSION["sessionID"] = $this->encrypt($db->lastInsertId(),$this->GetHash());
			//$_SESSION["adminUsername"] = $obj->name . " " . $obj->family;

			setcookie("sessionID", $_SESSION["sessionID"], time()+36000, "/", $_SERVER['HTTP_HOST']);


			$admin_info = $this->checkLogin();

			//print_r($admin_info);

			//print_r($admin_info);
			//die();
			//$resultLog  = $this->_setAdminLog($admin_info['admin_id']);

			//$messageStack->add_session('redirect', "Welcome to Admin Panel", 'success');

			redirectPage(RELA_DIR.'loginAs.php' ,"");
		}
	}

}
?>