<?php

/*
Plugin Name: Test XMLRPC
*/

define( '_10up_XMLRPC_USERNAME', '' );
define( '_10up_XMLRPC_PASSWORD', '' );
define( '_10up_XMLRPC_URL',      '' );

if ( defined('WP_CLI') && WP_CLI ) {
	class test_xmlrpc_10up extends WP_CLI_COMMAND {
		public function test() {
			$this->_run_check();
			
			require_once trailingslashit( ABSPATH ) . 'wp-includes/class-IXR.php';
			
			$client = new IXR_Client( _10up_XMLRPC_URL );
			
			WP_CLI::line( WP_CLI::colorize( '%YSaying "Hello" to ' . _10up_XMLRPC_URL . '…%Y'  ) );
			$say_hello = $client->query( 'demo.sayHello', '', _10up_XMLRPC_USERNAME, _10up_XMLRPC_PASSWORD );
			if ( !$say_hello ) {
				WP_CLI::warning( 'Could not make request. Printing error then exiting.' );
				WP_CLI::line( print_r( $say_hello, true ) );
			} else {
				WP_CLI::success( 'Response: ' . $client->getResponse() );
			}
			exit;
		}
		
		private function _run_check() {
			if ( !defined('_10up_XMLRPC_USERNAME') || !defined('_10up_XMLRPC_PASSWORD') || !defined('_10up_XMLRPC_URL') || !_10up_XMLRPC_USERNAME || !_10up_XMLRPC_PASSWORD || !_10up_XMLRPC_URL ) {
				WP_CLI::error( 'Please be sure to define all of the necessary constants.' );
			}
		}
	}
	
	WP_CLI::add_command( 'xmlrpc', 'test_xmlrpc_10up' );
}