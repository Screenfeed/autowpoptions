<?php

namespace Screenfeed\AutoWPOptions\Tests;

use org\bovigo\vfs\vfsStreamDirectory;

trait FilesystemTestCaseTrait {
	/**
	 * @var vfsStreamDirectory
	 */
	protected $fs_root;

	/**
	 * @var string
	 */
	protected $raw_file_name = 'myplugin-settings-{autowpoptions-network-id}-{autowpoptions-blog-id}.php';

	/**
	 * This method is called before the first test of this test class is run.
	 */
	public static function setUpBeforeClass() {
		require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';
	}

	protected function get_raw_file_path( $subdir = '' ) {
		$subdir = trim( $subdir, '/\\' );
		$subdir = '' === $subdir ? '/' : "/$subdir/";
		return $this->fs_root->url() . $subdir . $this->raw_file_name;
	}

	protected function get_file_path( $is_network_option, $network_id, $blog_id, $subdir = '' ) {
		$subdir = trim( $subdir, '/\\' );
		$subdir = '' === $subdir ? '/' : "/$subdir/";
		return $this->fs_root->url() . $subdir . $this->get_file_name( $is_network_option, $network_id, $blog_id );
	}

	protected function get_file_name( $is_network_option, $network_id, $blog_id ) {
		$blog_id_replacement = $is_network_option ? 'network' : $blog_id;

		$file_name = str_replace( '{autowpoptions-network-id}', "$network_id", $this->raw_file_name );
		$file_name = str_replace( '{autowpoptions-blog-id}', "{$blog_id_replacement}", $file_name );

		if ( pathinfo( $file_name, PATHINFO_EXTENSION ) !== 'php' ) {
			$file_name .= '.php';
		}

		return $file_name;
	}
}
