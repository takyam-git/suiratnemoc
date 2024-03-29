<?php
/**
 * Part of the Fuel framework.
 *
 * @package    Fuel
 * @version    1.0
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2011 Fuel Development Team
 * @link       http://fuelphp.com
 */

/**
 * NOTICE:
 *
 * If you need to make modifications to the default configuration, copy
 * this file to your app/config folder, and make them in there.
 *
 * This will allow you to upgrade fuel without losing your custom config.
 */


return array(

	/**
	 * An array of paths that will be searched for assets. Each asset is a
	 * RELATIVE path from the base_url WITH a trailing slash:
	 *
	 * array('assets/')
	 */
	'paths' => array('assets/', 'bootstrap/'),

	/**
	 * URL to your Fuel root. Typically this will be your base URL,
	 * WITH a trailing slash:
	 *
	 * Config::get('base_url')
	 */
	//'url' => Config::get('base_url'),
	'url' => '/',

	/**
	 * Whether to append the assets last modified timestamp to the url.
	 * This will aid in asset caching, and is recommended.  It will create
	 * tags like this:
	 *
	 *     <link type="text/css" rel="stylesheet" src="/assets/css/styles.css?1303443763" />
	 */
	'add_mtime' => true,

	/**
	 * Asset Sub-folders
	 *
	 * Names for the img, js and css folders (inside the asset path).
	 *
	 * Examples:
	 *
	 * img/
	 * js/
	 * css/
	 *
	 * This MUST include the trailing slash ('/')
	 */
	'img_dir' => 'img/',
	'js_dir' => 'js/',
	'css_dir' => 'css/'
);


