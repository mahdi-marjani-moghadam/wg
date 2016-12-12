<?php
class messageStack 
{
    var $messages;

    function messageStack() 
	{
		$this->messages = array();
    }

    function add($class, $message, $type = 'error') 
	{
		$this->messages[] = array('class' => $class, 'type' => $type, 'message' => $message);
    }

    function add_session($class, $message, $type = 'error') 
	{
		if (isset($_SESSION['messageToStack'])) 
		{
			$messageToStack = $_SESSION['messageToStack'];
		} 
		else 
		{
			$messageToStack = array();
		}
		
		$messageToStack[] = array('class' => $class, 'text' => $message, 'type' => $type);
		
		$_SESSION['messageToStack'] = $messageToStack;
		
		$this->add($class, $message, $type);
    }

    function reset() 
	{
		//$this->messages = array();
    }
    
    function getMessages($class) 
	{
		$messages = array();
		
		for ($i=0, $n=sizeof($this->messages); $i<$n; $i++) 
		{
			if ($this->messages[$i]['class'] == $class) 
			{

				$messages['message'] = $this->messages[$i]['message'];
				$messages['type'] = $this->messages[$i]['type'];
			}
		}
		
      	return $messages;
    }

    function output($class) 
	{
        $messages = '';
      	//$messages = '<a href="#" class="close"><img src="'.RELA_DIR.'templates/'.CURRENT_SKIN.'/admin/images/icons/cross_grey_small.png" title="حذف" alt="حذف" /></a>';
      	for ($i=0, $n=sizeof($this->messages); $i<$n; $i++) 
		{
        	if ($this->messages[$i]['class'] == $class) 
			{
          		switch ($this->messages[$i]['type'])
                {
                    case 'error':
                        $messages .= '<div class="alert alert-danger fade in rtl"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'. '<strong>' . $this->messages[$i]['message'] . '</strong>'. '</div>';
                        break;
                    case 'warning':
                        $messages .= '<div class="alert alert-warning  fade in rtl"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'. '<strong>' . $this->messages[$i]['message'] . '</strong>'. '</div>';
                        break;
                    case 'success':
                        $messages .= '<div class="alert alert-success fade in rtl"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'. '<strong>' . $this->messages[$i]['message'] . '</strong>'. '</div>';
                        break;
                    default:
                        $messages .= '<div class="alert alert-info fade in rtl"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'. '<strong>' . $this->messages[$i]['message'] . '</strong>'. '</div>';
                }

        	  	
        	}
      	}


      	return $messages;
    }

    function outputPlain($class) 
	{
		$message = false;
		
		for ($i=0, $n=sizeof($this->messages); $i<$n; $i++) 
		{
			if ($this->messages[$i]['class'] == $class) 
			{
				$message = $this->messages[$i]['message'];
				break;
			}
		}
		
		return $message;
    }

    function size($class) 
	{
		$class_size = 0;
		
		for ($i=0, $n=sizeof($this->messages); $i<$n; $i++) 
		{
			if ($this->messages[$i]['class'] == $class) 
			{
				$class_size++;
			}
		}
		
		return $class_size;
    }

    function loadFromSession() 
	{
		if (isset($_SESSION['messageToStack'])) 
		{
			$messageToStack = $_SESSION['messageToStack'];
		
			for ($i=0, $n=sizeof($messageToStack); $i<$n; $i++) 
			{
				$this->add($messageToStack[$i]['class'], $messageToStack[$i]['text'], $messageToStack[$i]['type']);
			}
		
			unset($_SESSION['messageToStack']);
		}
    }
}
?>
