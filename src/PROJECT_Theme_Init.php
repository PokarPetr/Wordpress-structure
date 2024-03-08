<?php

namespace src;


class PROJECT_Theme {
	/**
	 * Singleton.
	 *
	 * @since 1.0.0
	 * @access private
	 * @static
	 * @var PROJECT_Theme $_instance
	 */
	private static $instance = null;

	/**
	 * Singleton Instance.
	 *
	 * @return PROJECT_Theme|null
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function instance(): ?PROJECT_Theme {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Gift_of_parenthood constructor.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Init functions.
	 */
	public function init() {		
		$this->init_assets();
		$this->init_templates();      
	}
	/**
	 * Init Assets.
	 */
	private function init_assets() {
		require_once 'PROJECT_Assets.php';

        new PROJECT_Assets();
	}
	/**
	 * Init Templates.
	 */
	private function init_templates() {
		require_once 'PROJECT_Templates.php';

        new PROJECT_Templates();
	}
    
}

PROJECT_Theme::instance();
