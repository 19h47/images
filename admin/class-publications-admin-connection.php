<?php

/**
 * @link			http://19h47.fr
 * @since			1.0.0
 *
 * @package			Publications
 * @subpackage		Publications/admin
 */


/**
 * Instagram OAuth REST API
 *
 * @see				https://github.com/cosenary/Instagram-PHP-API
 */
use MetzWeb\Instagram\Instagram;


/**
 * @package			Publications
 * @subpackage		Publications/admin
 * @author			Jérémy Levron	<jeremylevron@19h47.fr>
 */
class Publications_Admin_Connection {

	/**
	 * The ID of this plugin.
	 *
	 * @since		1.0.0
	 * @access		private
	 * @var			string			$plugin_name		The ID of this plugin.
	 */
	private $plugin_name;


	/**
	 * The version of this plugin.
	 *
	 * @since		1.0.0
	 * @access		private
	 * @var			string			$version			The current version of this plugin.
	 */
	private $version;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since		1.0.0
	 * @param		string			$plugin_name		The name of this plugin.
	 * @param		string			$version			The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}


	/**
	 * Connection
	 *
	 * @access		public
	 * @param		$config arr
	 * @return		$content
	 */
	public static function connection( $config ) {
		$post_image_id = null;

		$connection = new Instagram(
			array(
				'apiKey'		=> $config['client']['id'],
				'apiSecret'		=> $config['client']['secret'],
				'apiCallback'	=> $config['redirect_uri']
			)
		);

		// Store user access token
		$connection->setAccessToken( $config['access_token'] );

		// Store user
		$user = $connection->getUser();

		if ( isset( $user->error_message ) ) {
			add_action( 'admin_notices', function() use ($user) {
				include plugin_dir_path( __FILE__ ) . 'partials/publications-admin-notices.php';
			});
			return false;
		}

		return $connection->getUserMedia()->data;
	}
}
