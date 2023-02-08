<?php
/**
 * Plugin class. 
 *
 * @package WP Custom Category Meta
 * @author  Vladislav Musilek
 */
class WPCustomCategoryMeta {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.0.0';

	/**
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'wp-ccm';
  /**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		/* Define custom functionality.
		 * Refer To http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		 */
		add_action ( 'edit_category_form', array($this,'wp_ccm_category_form') );
    add_action ( 'edited_category', array($this,'wp_ccm_save_category') );
    
    add_filter( 'wp_title', array($this,'wp_ccm_category_title'), 10, 2 );
    add_action ('wp_head',array($this,'wp_ccm_category_meta')); 
	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 *@return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

				}

				restore_current_blog();

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    1.0.0
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();

	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    1.0.0
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	private static function single_activate() {
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 */
	private static function single_deactivate() {
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );

	}


  /**
	 * Display Category Title 
	 *
	 * @since    1.0.0
	 */
  public function wp_ccm_category_title($title, $sep){
  
  if (is_category()) {
	global $wp_query;
	
  $cat_obj = $wp_query->get_queried_object();
	$cat_id = $cat_obj->term_id;
  
  $category_meta = get_option('category_meta_key_'.$cat_id);
  
      if(isset($category_meta['category_title'])){
        $cat_title = $category_meta['category_title'].' '.$sep.' ' ;
        return $cat_title;
      }else{
        return $title;
      }
  }else{
  
  return $title;
  
  }
  }
  /**
	 * Display Category Meta 
	 *
	 * @since    1.0.0
	 */
  public function wp_ccm_category_meta(){
  if (is_category()) {
	global $wp_query;
	$cat_obj = $wp_query->get_queried_object();
	$cat_id = $cat_obj->term_id;
  $category_meta = get_option('category_meta_key_'.$cat_id);
      if(isset($category_meta['category_meta_description'])){
        $cat_desc = '<meta name="description" content="'.$category_meta['category_meta_description'].'" />' ;
      }else{
        $cat_desc = '';
      }
      echo $cat_desc; 
      
      if(isset($category_meta['category_meta_keywords'])){
        $cat_key = '<meta name="keywords" content="'.$category_meta['category_meta_keywords'].'" />' ;
      }else{
        $cat_key = '';
      }
      
      echo $cat_key;
    }
  }
  
  /**
	 * Display Category Meta Form 
	 *
	 * @since    1.0.0
	 */
  public function wp_ccm_category_form(){
  
  //Display if category is edit
  if(isset($_GET['action']) && $_GET['action']=="edit") {
  
  //Get category meta
  $category_meta = get_option('category_meta_key_'.$_GET['tag_ID']);
  
  //Form html code
  $html  = '';
  $html .= '
      <div class="icon32" id="icon-edit-pages"><br></div>
      <h2>'.__('Custom Category Meta Setting', $this->plugin_slug).'</h2>
      <table class="form-table" >
        <tbody>
          <tr class="form-field">
            <th valign="top" scope="row">
              <label for="category_title">
                '.__('Title for Category', $this->plugin_slug).' :
              </label>
            </th>
            <td>
              <input name="category_title" type="text" value="'.$category_meta['category_title'].'" size="40" />
              <p class="description">'.__('Enter category title tag. If is empty, title not will be owerwrite', $this->plugin_slug).'</p>
            </td>
          </tr>
          <tr class="form-field">
            <th valign="top" scope="row">
              <label for="category_meta_description">
                '.__('Category Meta Description', $this->plugin_slug).' :
              </label>
            </th>
            <td>
              <textarea name="category_meta_description" size="40" rows="4">'.$category_meta['category_meta_description'].'</textarea>
              <p class="description">'.__('Enter category description text. If is empty, description not will be owerwrite', $this->plugin_slug).'</p>
            </td>
          </tr>
          <tr class="form-field">
            <th valign="top" scope="row">
              <label for="category_meta_keywords">
                '.__('Category Meta Keywords', $this->plugin_slug).' :
              </label>
            </th>
            <td>
              <input name="category_meta_keywords" type="text" size="40" value="'.$category_meta['category_meta_keywords'].'" />
              <p class="description">'.__('Enter category keywords. If is empty, keywords not will be owerwrite', $this->plugin_slug).'</p>
            </td>
          </tr>
        </tbody> 
      </table>
  ';

    echo $html;
    }  
  }


  /**
	 * Save Category Meta 
	 *
	 * @since    1.0.0
	 */
  public function wp_ccm_save_category(){
  
    $tag_ID = $_POST['tag_ID'];
  
    if ( isset($_POST['category_title']) ) {    
      $category_meta['category_title'] = sanitize_text_field($_POST['category_title']);
    }
    if ( isset($_POST['category_meta_description']) ) {
      $category_meta['category_meta_description'] = sanitize_text_field($_POST['category_meta_description']);
    }
    if ( isset($_POST['category_meta_keywords']) ) {
      $category_meta['category_meta_keywords'] = sanitize_text_field($_POST['category_meta_keywords']);
    }
    //save the option array
    update_option( 'category_meta_key_'.$tag_ID, $category_meta );
  }






}

?>