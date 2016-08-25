<?php
class Site_Contacts_Manager
{
	protected $loader;
	protected $plugin_slug;
	protected $version;

	public function __construct()
	{
		$this->plugin_slug = 'site-contacts-manager-slug';
		$this->version = '0.1.0';

		$this->load_dependencies();
		$this->define_admin_hooks();
	}

	private function load_dependencies()
	{
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/site-contacts-manager-admin.php';
		require_once plugin_dir_path( __FILE__ ) . 'site-contacts-manager-loader.php';
        $this->loader = new Site_Contacts_Manager_Loader();
	}

	private function define_admin_hooks()
	{
		$admin = new Site_Contacts_Manager_Admin( $this->get_version() );
		$this->loader->add_action( 'admin_enqueue_scripts', $admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $admin, 'enqueue_scripts' );
	}

	public function run()
	{
		$this->loader->run();
	}

	public function get_version()
	{
		return $this->version;
	}

}