<?php
class Message
{
	private $chat_id;
	private $message_id;
	private $message;
	private $first_name;
	private $last_name;
	private $username;
	private $date;
	public function __construct($message)
	{
		if(isset($message))
		{
			$this->chat_id = $message['chat']['id'];
			$this->first_name = $message['chat']['first_name'];
			$this->last_name = $message['chat']['last_name'];
			$this->username = $message['chat']['username'];
			$this->date = $message['date'];
			$this->message_id = $message['message_id'];
			$this->message = $message['text'];

			$messages = R::dispense( 'messages' );
    		$messages->chat_id = $this->chat_id;
    		$messages->message_id = $this->message_id;
    		$messages->message_text = $this->message;
    		$messages->first_name = $this->first_name;
    		$messages->last_name = $this->last_name;

    		$messages->username = $this->username;
    		$messages->date = $this->date;
    		
    		

    		$id = R::store( $messages );
		}
		
	}

	public function getChatId()
	{
		return $this->chat_id;
	}
	public function getFirstName()
	{
		return $this->first_name;
	}
	public function getLastName()
	{
		return $this->last_name;
	}
	public function getUsername()
	{
		return $this->username;
	}
	public function getDate()
	{
		return $this->date;
	}
	public function getMessageId()
	{
		return $this->message_id;
	}
	public function getMessage()
	{
		$command  = explode(" ", $this->message);
		return $command[0];
	}
	public function getMSG()
	{
		return $message;
	}
	public function getWholeMessage()
	{
		$command  = explode(" ", $this->message);
		unset($command[0]);


		return implode($command);
	}
	public function getMessageParam($param)
	{
		$command  = explode(" ", $this->message);
		if(array_key_exists($param,$command)) return $command[$param];
		else return false;

	}
}