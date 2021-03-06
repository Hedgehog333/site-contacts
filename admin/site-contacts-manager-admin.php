<?php
class Site_Contacts_Manager_Admin
{
	protected $version;

	public function __construct($version)
	{
		$this->version = $version;
	}

	public function enqueue_styles()
	{
		wp_enqueue_style(
			'site-contacts-css',
			plugins_url('css/site_contacts.css', __FILE__),
			array(),
			$this->version,
			FALSE
		);
	}

	public function enqueue_scripts()
	{
		wp_enqueue_script('jQuery');
		wp_enqueue_script(
			'site-contacts-js',
			plugins_url('js/site_contacts.js', __FILE__),
			array(),
			$this->version,
			FALSE
		);
		wp_localize_script( 'site-contacts-js', 'dictionary', array( 
			'erUniq' => __( 'The value must be unique.', 'site-contacts' ),
			'erAjax' => __( 'Error ajax.\nOpen the Developer Console to view the error messages.', 'site-contacts' ),
			'erPattern' => __( 'Letters, numbers, hyphens, and underscores', 'site-contacts' ),
			'erEmpty' => __( 'The field must contain the value', 'site-contacts' ),
			'btnEdit' => __( 'edit', 'site-contacts' ),
			'btnDelete' => __( 'delete', 'site-contacts' ),
			'btnSave' => __( 'save', 'site-contacts' ),
			'btnCancle' => __( 'cancel', 'site-contacts' )
			)
		);
	}

	public function render($wpdb) {
		require_once plugin_dir_path( __FILE__ ) . 'partials/site-contacts-form.php';
	}
}