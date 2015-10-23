<?php
namespace blueprints\api;

interface MS_modelApiInterface
{
	/**
	 * @return mixed: display a listing of the resource.
	 */
	public function retrieveAllResources();

	/**
	 * @return mixed: status of the insert call
	 */
	public function createSingleResource();

	/**
	 * @param  int $id : the primary key for this resource
	 *
	 * @return bool: show view for an existing resource
	 */
	public function retrieveSingle($id);

	/**
	 * @param $id : the primary key for this resource
	 *
	 * @return bool: status of the update call
	 */
	public function updateSingleResource($id);

	/**
	 * @param $id : the primary key for this resource
	 *
	 * @return bool: status of the delete call
	 */
	public function deleteSingleResource($id);
}