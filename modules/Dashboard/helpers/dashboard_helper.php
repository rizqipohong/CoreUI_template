<?php
/**
 * @author Henry
 */
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('lorem_ipsum'))
{
	/**
	 * @param string $table       Table name
	 * @param string $foreign_key Collumn name having the Foreign Key
	 * @param string $references  Table and column reference. Ex: users(id)
	 * @param string $on_delete   RESTRICT, NO ACTION, CASCADE, SET NULL, SET DEFAULT
	 * @param string $on_update   RESTRICT, NO ACTION, CASCADE, SET NULL, SET DEFAULT
	 *
	 * @return string SQL command
	 */
	function lorem_ipsum()
	{
	    return "lorem ipsum helper";
	}
}
