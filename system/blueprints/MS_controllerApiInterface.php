<?php
namespace system\blueprints;

interface MS_controllerApiInterface
{
	/**
	 * @return mixed: display a listing of the resource.
	 */
	public function index();


	/**
	 * @return mixed: show view form for creating a new resource
	 */
	public function create();

	/**
	 * @return mixed: store a newly created resource in storage.
	 */
	public function store();

	/**
	 * @param  int $id : the primary key for this resource
	 *
	 * @return mixed: show view for an existing resource
	 */
	public function show($id);

	/**
	 * @param $id : the primary key for this resource
	 *
	 * @return mixed: show view for editing an existing resource
	 */
	public function edit($id);

	/**
	 * @param $id : the primary key for this resource
	 *
	 * @return mixed: execute index() with an execute status
	 */
	public function update($id);


	/**
	 * @param $id : the primary key for this resource
	 *
	 * @return mixed: execute index() with an execute status
	 */
	public function delete($id);
}