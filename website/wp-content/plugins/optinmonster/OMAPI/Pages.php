<?php
/**
 * Pages class.
 *
 * @since 1.9.10
 *
 * @package OMAPI
 * @author  Erik Jonasson
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Pages class.
 *
 * @since 1.9.10
 *
 * @package OMAPI
 * @author  Erik Jonasson
 */
class OMAPI_Pages {

	/**
	 * Holds the class object.
	 *
	 * @since 1.9.10
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Path to the file.
	 *
	 * @since 1.9.10
	 *
	 * @var string
	 */
	public $file = __FILE__;

	/**
	 * Holds the base class object.
	 *
	 * @since 1.9.10
	 *
	 * @var OMAPI
	 */
	public $base;

	/**
	 * The admin title tag format.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	public $title_tag = '';

	/**
	 * The registered pages.
	 *
	 * @since 2.0.0
	 *
	 * @var array
	 */
	protected $pages = array();

	/**
	 * Sets our object instance and base class instance.
	 *
	 * @since 1.9.10
	 */
	public function __construct() {
		self::$instance = $this;
		$this->base     = OMAPI::get_instance();
	}

	/**
	 * Setup any hooks.
	 *
	 * @since 2.0.0
	 */
	public function setup() {
		add_filter( 'admin_title', array( $this, 'store_admin_title' ), 999999, 2 );
		add_filter( 'admin_body_class', array( $this, 'admin_body_classes' ) );
	}

	/**
	 * Stores the admin title tag format to be used in JS.
	 *
	 * @since  2.0.0
	 *
	 * @param  string $admin_title The admin title.
	 * @param  string $title      The title.
	 *
	 * @return string             The admin title.
	 */
	public function store_admin_title( $admin_title, $title ) {
		$this->title_tag = str_replace( $title, '{replaceme}', $admin_title );

		return $admin_title;
	}

	/**
	 * Returns an array of our registered pages.
	 * If we need more pages, add them to this array
	 *
	 * @return array Array of page objects.
	 */
	public function get_registered_pages() {
		if ( empty( $this->pages ) ) {
			$this->pages['optin-monster-campaigns'] = array(
				'name'     => __( 'Campaigns', 'optin-monster-api' ),
				'app'      => true,
				'callback' => array( $this, 'render_app_loading_page' ),
			);

			$this->pages['optin-monster-templates'] = array(
				'name'     => __( 'Templates', 'optin-monster-api' ),
				'app'      => true,
				'callback' => array( $this, 'render_app_loading_page' ),
			);

			$this->pages['optin-monster-playbooks'] = array(
				'name'     => __( 'Playbooks', 'optin-monster-api' ),
				'app'      => true,
				'callback' => array( $this, 'render_app_loading_page' ),
			);

			$this->pages['optin-monster-monsterleads'] = array(
				'name'     => __( 'Subscribers', 'optin-monster-api' ),
				'app'      => true,
				'callback' => array( $this, 'render_app_loading_page' ),
			);

			$this->pages['optin-monster-integrations'] = array(
				'name'     => __( 'Integrations', 'optin-monster-api' ),
				'app'      => true,
				'callback' => array( $this, 'render_app_loading_page' ),
			);

			$this->pages['optin-monster-trustpulse'] = array(
				'name' => __( 'TrustPulse', 'optin-monster-api' ),
			);

			$this->pages['optin-monster-settings'] = array(
				'name'     => __( 'Settings', 'optin-monster-api' ),
				'app'      => true,
				'callback' => array( $this, 'render_app_loading_page' ),
			);

			$this->pages['optin-monster-personalization'] = array(
				'name'     => __( 'Personalization', 'optin-monster-api' ),
				'app'      => true,
				'callback' => array( $this, 'render_app_loading_page' ),
			);

			$this->pages['optin-monster-university'] = array(
				'name'     => __( 'University', 'optin-monster-api' ),
				'app'      => true,
				'callback' => array( $this, 'render_app_loading_page' ),
			);

			$this->pages['optin-monster-about'] = array(
				'name'     => __( 'About Us', 'optin-monster-api' ),
				'app'      => true,
				'callback' => array( $this, 'render_app_loading_page' ),
			);

			$bfcmitem = $this->should_show_bfcf_menu_item();

			// If user upgradeable, add an upgrade link to menu.
			if ( $this->base->can_show_upgrade() ) {
				$this->pages['optin-monster-upgrade'] = array(
					'name'      => 'vbp_pro' === $this->base->get_level()
						? esc_html__( 'Upgrade to Growth', 'optin-monster-api' )
						: esc_html__( 'Upgrade to Pro', 'optin-monster-api' ),
					'redirect'  => esc_url_raw( OMAPI_Urls::upgrade( 'pluginMenu' ) ),
					// Only highlight if we don't have a bfcm item to highlight.
					'highlight' => ! $bfcmitem,
					'callback'  => '__return_null',
				);
				add_filter( 'om_add_inline_script', array( $this, 'add_upgrade_url_to_js' ), 10, 2 );
			}

			if ( $bfcmitem ) {
				$this->pages['optin-monster-bfcm'] = $bfcmitem;
			}

			foreach ( $this->pages as $slug => $page ) {
				$this->pages[ $slug ]['slug'] = $slug;
			}
		}

		return $this->pages;
	}

	/**
	 * Should we show the Black Friday menu item.
	 *
	 * @since 2.11.0
	 *
	 * @return bool|array
	 */
	public function should_show_bfcf_menu_item() {
		$now          = new DateTime( 'now', new DateTimeZone( 'America/New_York' ) );
		$thanksgiving = strtotime( 'fourth Thursday of November' );
		$promo_start  = gmdate( 'Y-m-d 10:00:00', $thanksgiving - ( 3 * DAY_IN_SECONDS ) );
		$bf_end       = gmdate( 'Y-m-d 23:59:59', strtotime( 'first Tuesday of December' ) );
		$is_bf_window = OMAPI_Utils::date_within( $now, $promo_start, $bf_end );
		$year         = $now->format( 'Y' );

		if ( $is_bf_window ) {

			$url = OMAPI_Urls::marketing(
				'pricing',
				array(
					'utm_medium'   => 'pluginMenu',
					'utm_campaign' => "BF{$year}",
				)
			);

			if ( OMAPI_ApiKey::has_credentials() ) {
				$url = OMAPI_Urls::upgrade(
					'pluginMenu',
					'',
					'',
					array(
						'utm_campaign' => "BF{$year}",
						'feature'      => false,
					)
				);
			}

			$cyber_monday = $thanksgiving + ( 4 * DAY_IN_SECONDS );
			$cm_start     = gmdate( 'Y-m-d 10:00:00', $cyber_monday );
			$is_cm_window = ! OMAPI_Utils::date_before( $now, $cm_start );
			return array(
				'name'      => $is_cm_window
					? esc_html__( 'Cyber Monday!', 'optin-monster-api' )
					: esc_html__( 'Black Friday!', 'optin-monster-api' ),
				'alternate-name' => $is_cm_window
					? esc_html__( 'Cyber Monday Sale!', 'optin-monster-api' )
					: esc_html__( 'Black Friday Sale!', 'optin-monster-api' ),
				'redirect'  => esc_url_raw( $url ),
				'callback'  => '__return_null',
				'highlight' => true,
			);
		}

		$green_monday = strtotime( 'second Monday of December' );
		$is_gm_window = OMAPI_Utils::date_within(
			$now,
			gmdate( 'Y-m-d 10:00:00', $green_monday ),
			gmdate( 'Y-m-d 23:59:59', $green_monday )
		);

		if ( $is_gm_window ) {
			$url = OMAPI_Urls::marketing(
				'pricing-wp/',
				array(
					'utm_medium'   => 'pluginMenu',
					'utm_campaign' => "BF{$year}",
				)
			);

			if ( OMAPI_ApiKey::has_credentials() && ! $this->base->is_lite_user() ) {
				$url = OMAPI_Urls::upgrade(
					'pluginMenu',
					'',
					'',
					array(
						'utm_campaign' => "BF{$year}",
						'feature'      => false,
					)
				);
			}

			return array(
				'name'      => esc_html__( 'Green Monday!', 'optin-monster-api' ),
				'redirect'  => esc_url_raw( $url ),
				'callback'  => '__return_null',
				'highlight' => true,
			);
		}

		return false;
	}

	/**
	 * Add the menu upgrade url to the data sento to the global JS file.
	 *
	 * @since 2.4.0
	 *
	 * @param array  $data    Array of data for JS.
	 * @param string $handle The script handle.
	 *
	 * @return $data Array of data for JS.
	 */
	public function add_upgrade_url_to_js( $data, $handle ) {
		if ( $this->base->plugin_slug . '-global' === $handle ) {
			$data['upgradeUrl'] = esc_url_raw( OMAPI_Urls::upgrade( 'pluginMenu' ) );
		}

		return $data;
	}

	/**
	 * Returns an array of our registered JS app pages.
	 *
	 * @return array Array of page objects.
	 */
	public function get_registered_app_pages() {
		return wp_list_filter( $this->get_registered_pages(), array( 'app' => true ) );
	}

	/**
	 * Whether given page slug is one of our registered JS app pages.
	 *
	 * @param string $page_slug Page slug.
	 *
	 * @return boolean
	 */
	public function is_registered_app_page( $page_slug ) {
		$pages   = wp_list_pluck( $this->get_registered_app_pages(), 'slug' );
		$pages[] = 'optin-monster-api-settings';
		$pages[] = 'optin-monster-dashboard';

		return in_array( $page_slug, $pages, true );
	}

	/**
	 * Registers our submenu pages
	 *
	 * @param string $parent_page_name The Parent Page Name.
	 *
	 * @return array Array of hook ids.
	 */
	public function register_submenu_pages( $parent_page_name ) {
		$pages = $this->get_registered_pages();
		$hooks = array();

		foreach ( $pages as $page ) {
			if ( ! empty( $page['callback'] ) ) {
				$parent_slug = $parent_page_name;

				if ( ! empty( $page['hidden'] ) ) {
					$parent_slug .= '-hidden';
				}

				$menu_title = ! empty( $page['menu'] ) ? $page['menu'] : $page['name'];
				if ( $this->maybe_add_new_badge( $page ) ) {
					$menu_title .= ' <span class="omapi-menu-new">New!<span>';
				} elseif ( ! empty( $page['highlight'] ) ) {
					$menu_title = '<span class="om-menu-highlight">' . $menu_title . '</span>';
				}

				$hook    = add_submenu_page(
					$parent_slug, // $parent_slug
					$page['name'], // $page_title
					$menu_title,
					$this->base->access_capability( $page['slug'] ),
					$page['slug'],
					$page['callback']
				);
				$hooks[] = $hook;

				if ( ! empty( $page['redirect'] ) ) {
					add_action( 'load-' . $hook, array( $this, 'handle_redirect' ), 999 );
				}
			}
		}

		return $hooks;
	}

	/**
	 * Handle redirect for registered page.
	 *
	 * @since  2.0.0
	 *
	 * @return void
	 */
	public function handle_redirect() {
		global $plugin_page;

		$pages = $this->get_registered_pages();
		if (
			empty( $pages[ $plugin_page ]['redirect'] )
			|| is_bool( $pages[ $plugin_page ]['redirect'] )
		) {
			$this->base->menu->redirect_to_dashboard();
			return;
		}

		add_filter( 'allowed_redirect_hosts', 'OMAPI_Urls::allowed_redirect_hosts' );
		wp_safe_redirect( esc_url_raw( $pages[ $plugin_page ]['redirect'] ) );
		exit;
	}

	/**
	 * Adds om app admin body classes
	 *
	 * @since  2.0.0
	 *
	 * @param  string $classes The admin body classes.
	 *
	 * @return string         The admin body classes.
	 */
	public function admin_body_classes( $classes ) {
		global $plugin_page;

		$classes = explode( ' ', $classes );
		$classes = array_filter( $classes );
		$classes = array_map( 'trim', $classes );

		if ( $this->is_registered_app_page( $plugin_page ) ) {
			$classes[] = 'omapi-app';
			$classes[] = 'omapi-app-' . str_replace( 'optin-monster-', '', $plugin_page );
		}

		$classes = implode( ' ', $classes );

		return $classes;

	}

	/**
	 * Registers our submenu pages, but redirects to main page when navigating to them.
	 *
	 * @since  1.9.10
	 *
	 * @param string $parent_page_name The Parent Page Name.
	 * @return void
	 */
	public function register_submenu_redirects( $parent_page_name ) {
		$hooks = $this->register_submenu_pages( $parent_page_name . '-hidden' );
		foreach ( $hooks as $hook ) {
			add_action( 'load-' . $hook, array( $this->base->menu, 'redirect_to_dashboard' ) );
		}
	}

	/**
	 * Outputs the OptinMonster about-us page.
	 *
	 * @since 1.9.10
	 */
	public function render_app_loading_page() {
		$this->load_scripts();
		echo '<div id="om-app">';
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $this->base->output_view( 'archie-loading.php' );
		echo '</div>';
	}

	/**
	 * Loads the OptinMonster app scripts.
	 *
	 * @since  2.0.0
	 *
	 * @param  array $args Array of arguments to pass to the app.
	 *
	 * @return OMAPI_AssetLoader|false
	 */
	public function load_scripts( $args = array() ) {
		$path   = 'vue/dist';
		$loader = new OMAPI_AssetLoader( trailingslashit( dirname( $this->base->file ) ) . $path );
		try {

			add_filter( 'optin_monster_should_enqueue_asset', array( $this, 'should_enqueue' ), 10, 2 );
			$loader->enqueue(
				array(
					'base_url' => $this->base->url . $path,
					'version'  => $this->base->asset_version(),
				)
			);

			$pages = array(
				'optin-monster-dashboard' => __( 'Dashboard', 'optin-monster-api' ),
			);
			foreach ( $this->get_registered_pages() as $page ) {
				$pages[ $page['slug'] ] = ! empty( $page['title'] ) ? $page['title'] : $page['name'];
			}

			$creds = $this->base->get_api_credentials();

			$admin_parts = wp_parse_url( admin_url( 'admin.php' ) );
			$url_parts   = wp_parse_url( $this->base->url );

			$current_user = wp_get_current_user();

			$plugins  = new OMAPI_Plugins();
			$defaults = array(
				'key'             => ! empty( $creds['apikey'] ) ? $creds['apikey'] : '',
				'nonce'           => wp_create_nonce( 'wp_rest' ),
				'siteId'          => $this->base->get_site_id(),
				'siteIds'         => $this->base->get_site_ids(),
				'wpUrl'           => trailingslashit( site_url() ),
				'adminUrl'        => OMAPI_Urls::admin(),
				'upgradeParams'   => OMAPI_Urls::upgrade_params( null, null ),
				'restUrl'         => rest_url(),
				'adminPath'       => $admin_parts['path'],
				'apijsUrl'        => OPTINMONSTER_APIJS_URL,
				'omAppUrl'        => untrailingslashit( OPTINMONSTER_APP_URL ),
				'marketing'       => untrailingslashit( OPTINMONSTER_URL ),
				'omAppApiUrl'     => untrailingslashit( OPTINMONSTER_API_URL ),
				'omAppCdnURL'     => untrailingslashit( OPTINMONSTER_CDN_URL ),
				'newCampaignUrl'  => untrailingslashit( esc_url_raw( admin_url( 'admin.php?page=optin-monster-templates' ) ) ),
				'shareableUrl'    => untrailingslashit( OPTINMONSTER_SHAREABLE_LINK ),
				'pluginPath'      => $url_parts['path'],
				'omStaticDataKey' => 'omWpApi',
				'isItWp'          => true,
				// 'scriptPath'   => $path,
				'pages'           => $pages,
				'titleTag'        => html_entity_decode( $this->title_tag, ENT_QUOTES, 'UTF-8' ),
				'isWooActive'     => OMAPI_WooCommerce::is_active(),
				'isWooConnected'  => OMAPI_WooCommerce::is_connected(),
				'isEddActive'     => OMAPI_EasyDigitalDownloads::is_active(),
				'isEddConnected'  => OMAPI_EasyDigitalDownloads::is_connected(),
				'isWPFormsActive' => OMAPI_WPForms::is_active(),
				'blogname'        => esc_attr( get_option( 'blogname' ) ),
				'userEmail'       => esc_attr( $current_user->user_email ),
				'userFirstName'   => esc_attr( $current_user->user_firstname ),
				'userLastName'    => esc_attr( $current_user->user_lastname ),
				'betaVersion'     => $this->base->beta_version(),
				'pluginVersion'   => $this->base->version,
				'pluginsInfo'     => $plugins->get_active_plugins_header_value(),
				'partnerId'       => OMAPI_Partners::get_id(),
				'partnerUrl'      => OMAPI_Partners::has_partner_url(),
				'referredBy'      => OMAPI_Partners::referred_by(),
				'showReview'      => $this->base->review->should_show_review(),
				'timezone'        => wp_timezone_string(),
			);

			// Add the onboarding connection token if it exists.
			$connection_token = $this->base->get_option( 'connectionToken' );
			if ( ! empty( $connection_token ) ) {
				$args['connectionToken'] = $connection_token;
			}

			$onboarding_plugins = $this->base->get_option( 'onboardingPlugins', '', array() );
			if ( ! empty( $onboarding_plugins ) ) {
				$args['onboardingPlugins'] = $onboarding_plugins;
			}

			$args['isWpmlActive'] = OMAPI_Utils::is_wpml_active();
			$args['wpmlDomains']  = OMAPI_Utils::get_wpml_language_domains();

			$js_args = wp_parse_args( $args, $defaults );
			$js_args = apply_filters( 'optin_monster_campaigns_js_api_args', $js_args );

			$loader->localize( $js_args );

			return $loader;

		// phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedCatch
		} catch ( \Exception $e ) {
			// We don't want to throw an exception here, since it will break the output.
		}

		return false;
	}

	/**
	 * Determine if given asset should be enqueued.
	 *
	 * We only want app/common, since remaining assets are chunked/lazy-loaded.
	 *
	 * @since  2.0.0
	 *
	 * @param  bool   $should Whether asset should be enqueued.
	 * @param  string $handle The asset handle.
	 *
	 * @return bool           Whether asset should be enqueued.
	 */
	public function should_enqueue( $should, $handle ) {
		$allowed = array(
			'wp-om-app',
			'wp-om-common',
		);

		foreach ( $allowed as $search ) {
			if ( 0 === strpos( $handle, $search ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Determine if a page should have a "new" badge.
	 *
	 * Example:
	 *
	 * $this->pages['optin-monster-playbooks'] = [
	 *  'name'             => __( 'Playbooks', 'optin-monster-api' ),
	 *  'app'              => true,
	 *  'callback'         => [ $this, 'render_app_loading_page' ],
	 *  'new_badge_period' => [
	 *      'start' => '2023-02-02 00:00:00',
	 *      'end'   => '2023-03-03 59:59:59',
	 *  ],
	 * ];
	 *
	 * @param array $page The page data.
	 *
	 * @return boolean True if the given page should have a new badge
	 */
	public function maybe_add_new_badge( $page ) {
		if ( empty( $page['new_badge_period']['start'] ) ) {
			return false;
		}

		$now = new DateTime( 'now', new DateTimeZone( 'America/New_York' ) );

		return OMAPI_Utils::date_within(
			$now,
			$page['new_badge_period']['start'],
			$page['new_badge_period']['end']
		);
	}

}
