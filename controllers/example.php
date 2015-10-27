<?php

class example extends MS_controller
{
 public function index()
 {
$pipeline = new \system\pipelines\MS_pipeline();
	 var_dump($pdsfipeline->loadRoutes());
	 //return $this->view('example');
 }
}