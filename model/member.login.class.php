<?
class memberLogIns
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

	function logInForm($redirect = 0, $message='')
	{
		global $conn;

		if($redirect)
		{
			echo "0 $message";
		}
		else
		{
			include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/title.inc.php");
			include (ROOT_DIR . "templates/" . CURRENT_SKIN . "/login.php");
			include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/tail.inc.php");
		}

		die();
	}

	function logIn()
	{
		global $conn , $member_info ;

		$username 	 = handleData($_REQUEST["username"]) ;
		$password 	 = handleData($_REQUEST["password"]) ;
		$remember_me = intval(handleData($_REQUEST["remember_me"])) ;

		if($username == "" || strlen($username) > 20 || checkUser($username))
			$this->logInForm($redirect, MEMBER_0021);

		if($password == "" || strlen($password) > 20)
			$this->logInForm($redirect, MEMBER_0022);

		$sql = "DELETE FROM sessions WHERE last_access_time < (NOW()-3000000) ";
		$rs  = $conn->Execute($sql);
		if(!$rs)
		{
			showErrorMsg($conn->ErrorMsg());
		}

		/*$sql = "DELETE FROM final_order  WHERE status =0 and date < NOW()-172800 ";
		$rs  = $conn->Execute($sql);
		if(!$rs)
		{
			showErrorMsg($conn->ErrorMsg());
		}
		
		$sql = "DELETE FROM basket  WHERE status =0 and date < NOW()-172800 ";
		$rs  = $conn->Execute($sql);
		if(!$rs)
		{
			showErrorMsg($conn->ErrorMsg());
		}*/

		$sql 	   = "SELECT member_id,status,type FROM members where username='" . handleSQLData($username) . "' AND password='" . md5(handleSQLData($password)) . "'";
		$member_rs = $conn->Execute($sql);
		if(!$member_rs)
		{
			showErrorMsg($conn->ErrorMsg());
		}

		if($member_rs->RecordCount() == 1 && $member_rs->fields['status'] == 1)
		{
			$sql = "DELETE FROM sessions WHERE member_id='" . $member_rs->fields['member_id'] . "'";
			$rs  = $conn->Execute($sql);
			if(!$rs)
			{
				$this->logInForm($redirect, ALL_0005);
			}

			$sql = "insert into sessions(member_id,remote_addr,login_type, last_access_time, remember_me) values (" . $member_rs->fields['member_id'] . ", '" . $_SERVER["REMOTE_ADDR"] . "', '" . $member_rs->fields['type'] . "','" .getDateTime(). "','$remember_me')";
			$rs  = $conn->Execute($sql);
			if(!$rs)
			{
				$this->logInForm($redirect, ALL_0005);
			}

			$_SESSION["sessionID"] = $this->encrypt($conn->Insert_ID(),$this->GetHash());

			if($remember_me)
			{
				setcookie("sessionID",$_SESSION["sessionID"], time()+3600000000000, "/", $_SERVER['HTTP_HOST']);
			}
			else
			{
				setcookie("sessionID", $_SESSION["sessionID"], time()+3600, "/", $_SERVER['HTTP_HOST']);
			}

			$member_info = $this->checkLogin();
			if($_REQUEST['http_referer'] == '') $_REQUEST['http_referer'] = RELA_DIR;

			loginUSer($_REQUEST['http_referer'],"welcome",$member_info['username']) ;

		}
		elseif ($member_rs->RecordCount() == 1 && $member_rs->fields['status'] == -1)
		{
			$this->logInForm($redirect, MEMBER_0032);
		}
		elseif ($member_rs->RecordCount() == 1 && $member_rs->fields['status'] == 0)
		{
			$this->logInForm($redirect, MEMBER_0036);
		}
		else
		{
			$this->logInForm($redirect, ALL_0006);
		}
	}

	function checkLogin()
	{
		global $conn;

		//print_r($_COOKIE["sessionID"]);
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


		$sql = "select member_id from	sessions where	session_id = " . $sessionID . "	and login_type >= 1";
		$rs  = $conn->Execute($sql);
		if(!$rs)
		{
			return -1;
		}

		if($rs->RecordCount() != 1)
		{
			return -1;
		}

		$sql = "select	* from	members where	member_id = " . $rs->fields[0];
		$rs  = $conn->Execute($sql);
		if(!$rs)
		{
			return -1;
		}

		if($rs->EOF)
		{
			return -1;
		}

		$member_info = $rs->FetchRow();

		return $member_info;
	}

	function forgotPassShow($message)
	{
		global $conn;

		include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/title.inc.php");
		include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/forgot.password.php");
		include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/tail.inc.php");

		die();
	}

	function sendPass()
	{
		global $conn;

		$email = $_REQUEST['email'];

		if (checkMail($email))
		{
			$this->forgotPassShow(MEMBER_0032);
		}

		$sql = "select email,username from members where email='$email'";
		$rs  = $conn->Execute($sql);
		if(!$rs)
		{
			showErrorMsg($conn->ErrorMsg());
		}

		if ($rs->RecordCount()==0)
		{
			$this->forgotPassShow(MEMBER_0032);
		}
		else
		{
			$username 	= $rs ->fields['username'] ;
			$Key 	  	= mt_rand() . mt_rand().mt_rand() ;
			$sql 	  	="insert into password_recovery (`key`,Date,username) values('$Key',NOW(),'$username')" ;
			$conn->Execute($sql);

			$subject	= "password reminder";
			$body		= RELA_DIR."login.php?action=changePass&key=".$Key;
			sendmail($email,$subject,$body,"");
			$this->forgotPassShow(MEMBER_0034);
		}
	}

	function changePass()
	{
		global $conn ;

		$key 	= handleData($_REQUEST['key']);
		$sql 	= "select * from password_recovery where `key`='$key' and `Date` >= (NOW()-86400) " ;
		$rs  	= $conn->Execute($sql);

		if(!$rs->RecordCount())
		{
			die('the key is not valid');
		}
		else
		{


			include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/title.inc.php");
			include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/password.change.form.php");
			include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/tail.inc.php");

			die();
		}
	}

	function confirmChange()
	{
		global $conn;


		$password 			= handleData($_REQUEST['password']);
		$confirm_password	= handleData($_REQUEST['confirm_password']);
		$key 				= handleData($_REQUEST['key']);

		if($key == "")
			$this->logInForm('',MEMBER_0022);

		if($password == "")
			$this->showEditPassword(MEMBER_0022 , $username);

		if(strlen($password) > 20 || strlen($password) < 6  )
			$this->showEditPassword(MEMBER_0024 , $username);

		if($password!=$confirm_password)
			$this->showEditPassword(MEMBER_0003 , $username);

		$sql 	= "select * from password_recovery where `key`='$key' and `Date` >= (NOW()-86400) " ;
		$rs  	= $conn->Execute($sql);

		if(!$rs->RecordCount())
		{
			$this->showEditPassword('this key is not valid');
		}
		else
		{
			$username=$rs->fields['username'];
			$sql	="select * from members where username='$username'";
			$check	=$conn->Execute($sql);
			if(!$check->RecordCount())
			{
				$this->showEditPassword('this name is not valid','' );
			}
			else
			{
				$sql = "update members set password='" . md5($password) . "' where username='$username'";
				$update_pass = $conn->Execute($sql);
				$this->logInForm('','successfully changed');
			}
		}

	}

	function showEditPassword($message , $username)
	{

		include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/title.inc.php");
		include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/password.change.form.php");
		include(ROOT_DIR . "templates/" . CURRENT_SKIN . "/tail.inc.php");

		die();
	}

	function logOut()
	{
		global $conn;

		if(isset($_SESSION["sessionID"]))
		{
			setcookie("sessionID", '', time()-10000, "/", $_SERVER['HTTP_HOST']);
			$sql = "delete from sessions where session_id = " . handleData( $this->decrypt($_SESSION["sessionID"],$this->GetHash()));
			$rs  = $conn->Execute($sql);
			if(!$rs)
			{
				showErrorMsg($conn->ErrorMsg());
			}
		}
		elseif(isset($_COOKIE["sessionID"]))
		{
			setcookie("sessionID",'', time()-10000, "/", $_SERVER['HTTP_HOST']);
			$sql = "delete from sessions where session_id = ". handleData( $this->decrypt($_COOKIE["sessionID"],$this->GetHash()));
			$rs  = $conn->Execute($sql);
			if(!$rs)
			{
				showErrorMsg($conn->ErrorMsg());
			}
		}

		session_unset();
		header("Location:" . RELA_DIR);
	}

	private function memberPage($message)
	{
		header("Location:" . RELA_DIR);
		echo $message;
	}
}
?>
