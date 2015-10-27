<?php

class example extends MS_controller
{
 public function index()
 {
$search = new \system\helpers\MS_search();
	 $search->pronunciationMatch = true;
	 var_dump($search->returnOrdered('beans'));
	 //return $this->view('example');
 }
}