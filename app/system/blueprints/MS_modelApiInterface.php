<?php
namespace system\blueprints;

interface MS_modelApiInterface
{
	/**
	 * @return mixed: display a listing of the resource.
	 */
	public static function retrieveAllResources();

	/**
	 * @return mixed: status of the insert call
	 */
	public static function createSingleResource();

	/**
	 * @param  int $id : the primary key for this resource
	 *
	 * @return bool: show view for an existing resource
	 */
	public static function retrieveSingleResource($id);

	/**
	 * @param $id : the primary key for this resource
	 *
	 * @return bool: status of the update call
	 */
	public static function updateSingleResource($id);

	/**
	 * @param $id : the primary key for this resource
	 *
	 * @return bool: status of the delete call
	 */
	public static function deleteSingleResource($id);
}