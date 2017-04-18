<?php
class Location
{
	private $marker_name;
	private $marker_lat;
	private $marker_lng;
	private $marker_type;
	private $marker_state;
	private $marker_id;
	private $chat_id;
	/*
		marker_waiting_name

		coords

		type

		marker_done

	*/
	public function __construct($chat_id)
	{
		$this->chat_id = $chat_id;
	}
	public function createMarker()
	{

		$marker =  R::getAll( 'SELECT * FROM markers WHERE marker_state <> :marker_state AND chat_id = :chat_id',[':marker_state' => "marker_done", ':chat_id' => $this->chat_id]); 
		if(!$marker)
		{

			$markers = R::dispense( 'markers' );
    		$markers->chat_id = $this->chat_id;
    		$markers->marker_name = "";
    		$markers->marker_address = "";
    		$markers->marker_lat = 0.00;
    		$markers->marker_lng = 0.00;
    		$markers->marker_type = "";
    		$markers->marker_state = "marker_waiting_name";
    		

    		$id = R::store( $markers );
    	}
    	else
    	{
    		
    		return false;
    	}
	}
	public function getLast()
	{
		$marker =  R::getAll( 'SELECT * FROM markers WHERE marker_state <> :marker_state AND chat_id = :chat_id LIMIT 1',[':marker_state' => "marker_done", ':chat_id' => $this->chat_id ]); 
		$this->marker_id = $marker[0]['id'];
		$this->marker_name = $marker[0]['marker_name'];
		$this->marker_lat = $marker[0]['marker_lat'];
		$this->marker_lng = $marker[0]['marker_lng'];
		$this->marker_type = $marker[0]['marker_type'];
		$this->marker_state = $marker[0]['marker_state'];
		return $marker;
	}
	public function getStep($msg)
	{
		$this->getLast();
		$text = $msg['text'];

		switch($this->marker_state)
		{
			case "marker_waiting_name":

				if(!$text) return $step = "Send me the Marker name";
				$markers = R::load('markers', $this->marker_id);
				$markers->marker_name = $text;
				$markers->marker_state = "marker_waiting_address";
				R::store($markers);
				$step = "Now I need Address or description";
			break;
			case "marker_waiting_address":

				if(!$text) return $step = "Send me the Address or description";
				$markers = R::load('markers', $this->marker_id);
				$markers->marker_address = $text;
				$markers->marker_state = "marker_waiting_coords";
				R::store($markers);
				$step = "Now I need coords";
			break;
			case "marker_waiting_coords":

				if(!isset($msg['location']['latitude']) || !isset($msg['location']['longitude']))
					return $step = "Could not catch it! Send me your location!";
			

				$markers = R::load('markers', $this->marker_id);
				$markers->marker_lat = $msg['location']['latitude'];
				$markers->marker_lng = $msg['location']['longitude'];
				$markers->marker_state = "marker_waiting_type";
				R::store($markers);
				$step = "At last I need a type of this marker";
				
			break;
			case "marker_waiting_type":
				if(!$text) return $step="At last I need a type of this marker";

				$markers = R::load('markers', $this->marker_id);
				$markers->marker_type = $text;
				$markers->marker_state = "marker_done";
				R::store($markers);
				$step="You are done with it!";
			break;
			default: $step = "Hm...";
		}
		return $step;
	}
}