<?php

/**
 * IGUniform Hook
 *
 * @package     Joomla.librariesistrator
 * @subpackage  com_uniform
 * @since       1.6
 */
class IGUniformActionHook {

	/**
	 * IT Uniform Plugin's custom post type slug.
	 *
	 * @var  string
	 */
	public $type_slug = 'ig_uniform_post_type';

	/**
	 * Define pages.
	 *
	 * @var  array
	 */
	public static $pages = array( 'ig_uniform_post_type', 'ig_uniform_sb' );


	/**
	 * Render configuration page.
	 *
	 * @return  void
	 */
	public static function settings() {
		IG_Init_Assets::load(
			array(
				//'ig-bootstrap-css',
				'ig-bootstrap2-jsn-gui-css',
				'ig-uniform-css',
			)
		);
		IG_Uniform_Settings::render();
	}

	/**
	 * Render addons management screen.
	 *
	 * @return  void
	 */
	public static function addons() {
		// Instantiate product addons class
		IG_Init_Assets::load(
			array(
				'ig-bootstrap3-css',
				'ig-bootstrap3-jsn-gui-css',
				//'ig-jquery-ui-css',
				//'ig-uniform-css',
				'ig-form-css',
				'ig-form-js',
				'ig-addons-css',
				'ig-addons-js',
			)
		);
		IG_Product_Addons::init( IG_UNIFORM_IDENTIFIED_NAME );
	}

	/**
	 * Render About page.
	 *
	 * @return  void
	 */
	public static function about() {
		//define assets load view upgrade
		$assets = array(
			'ig-bootstrap2-css',
			'ig-bootstrap2-jsn-gui-css',
			'ig-jquery-ui-css',
			'ig-uniform-css',
		);
		//load assets view upgrade
		IG_Init_Assets::load( $assets );

		IG_Uniform_about::render();
	}

	/**
	 * Overwrite submission customer view count data
	 *
	 * @param $views
	 *
	 * @return mixed
	 */
	public static function ig_uniform_submissions_custom_view_count( $views ) {
		global $wpdb;

		/*
		 * This needs refining, and maybe a better method
		 * e.g. Attachments have completely different counts
		 */
		$formID = ! empty( $_SESSION[ 'ig-uniform' ][ 'form_id' ] ) ? $_SESSION[ 'ig-uniform' ][ 'form_id' ] : '';
		$where = '';
		$total = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts WHERE (post_status = 'publish' OR post_status = 'trash' OR post_status = 'draft' OR post_status = 'pending') AND (post_content = '" . (int)$formID . "'  AND post_type = 'ig_uniform_sb' ) " . $where );
		$publish = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'ig_uniform_sb' AND post_content = " . (int)$formID . $where );
		$trash = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'trash' AND post_type = 'ig_uniform_sb' AND post_content = " . (int)$formID . $where );
		$draft = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'draft'  AND post_type = 'ig_uniform_sb' AND post_content = " . (int)$formID . $where );
		$pending = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'pending' AND post_type = 'ig_uniform_sb' AND post_content = " . (int)$formID . $where );
		/*
		 * Only tested with Posts/Pages
		 * - there are moments where Draft and Pending shouldn't return any value
		 */
		$views[ 'all' ] = preg_replace( '/\(.+\)/U', '(' . $total . ')', $views[ 'all' ] );
		if ( ! empty( $views[ 'publish' ] ) ) {
			$views[ 'publish' ] = preg_replace( '/\(.+\)/U', '(' . $publish . ')', $views[ 'publish' ] );
		}
		if ( $views[ 'trash' ] ) {
			$views[ 'trash' ] = preg_replace( '/\(.+\)/U', '(' . $trash . ')', $views[ 'trash' ] );
		}
		if ( $views[ 'draft' ] ) {
			$views[ 'draft' ] = preg_replace( '/\(.+\)/U', '(' . $draft . ')', $views[ 'draft' ] );
		}
		if ( $views[ 'pending' ] ) {
			$views[ 'pending' ] = preg_replace( '/\(.+\)/U', '(' . $pending . ')', $views[ 'pending' ] );
		}
		return $views;
	}

	//------------------------------------------------------
	//------------- PAGE/POST EDIT PAGE ---------------------

	public static function page_supports_add_form_button() {
		if ( ! defined( 'RG_CURRENT_PAGE' ) ) define( 'RG_CURRENT_PAGE', basename( $_SERVER[ 'PHP_SELF' ] ) );
		$is_post_edit_page = in_array(
			RG_CURRENT_PAGE, array(
				'post.php',
				'page.php',
				'page-new.php',
				'post-new.php',
			)
		);
		IG_Init_Assets::hook();
		wp_enqueue_script( 'jquery' );
		IG_Init_Assets::load(
			array(
				'ig-uniform-editor-plugin-css',
				'ig-uniform-editor-plugin-js',
			)
		);
		add_action( 'admin_footer', array( __CLASS__, 'add_mce_popup' ) );
		$display_add_form_button = apply_filters( 'ig_uniform_display_add_form_button', $is_post_edit_page );
		return $display_add_form_button;
	}

	//Action target that displays the popup to insert a form to a post/page
	public static function add_mce_popup() {
		?>
	<div id="select_uniform_form" style="display:none;">
		<div class="wrap ig-uniform-thickbox-add-field">
			<div class="ig-uniform-thickbox-header">
				<h3><?php _e( 'Insert A Form', IG_UNIFORM_TEXTDOMAIN ); ?></h3>
				<span> <?php _e( 'Select a form below to add it to your post or page.', IG_UNIFORM_TEXTDOMAIN ); ?> </span>
			</div>
			<div class="ig-uniform-thickbox-content">
				<select class="ig-uniform-list-form">
					<option value="">  <?php _e( 'Select a Form', IG_UNIFORM_TEXTDOMAIN ); ?>  </option>
					<?php
					$forms = get_posts(
						array(
							'post_type' => 'ig_uniform_post_type',
							'post_status' => 'any',
							'numberposts' => '99999',
						)
					);
					if ( ! empty( $forms ) ) {
						foreach ( $forms as $form ) {
							$formTitle = ! empty( $form->post_title ) ? $form->post_title : '(no title)';
							$meta = get_post_meta( (int)$form->ID );
							if ( ! empty( $meta[ 'form_id' ][ 0 ] ) ) {
								$formID = (int)$meta[ 'form_id' ][ 0 ];
							}
							else {
								$formID = (int)$form->ID;
							}
							?>
							<option value="<?php echo absint( $formID ) ?>"><?php echo esc_html( $formTitle ) ?></option>
							<?php
						}
					}
					?>
				</select>

				<div class="ig-uniform-thickbox-messages"><?php _e( 'Can\'t find your form? Make sure it is active.', IG_UNIFORM_TEXTDOMAIN ); ?></div>
			</div>
			<div class="ig-uniform-thicjbox-action">
				<input type="button" class="button-primary" id="ig_uniform_btn_add_fied" value="<?php _e( 'Insert Form', IG_UNIFORM_TEXTDOMAIN ); ?>" />
				<a class="button" href="#" onclick="tb_remove(); return false;"><?php _e( 'Cancel', IG_UNIFORM_TEXTDOMAIN ); ?></a>
			</div>
		</div>
	</div>
	<?php
	}

	//Action target that adds the 'Insert Form' button to the post/page edit screen
	public static function add_form_button() {

		$is_add_form_page = self::page_supports_add_form_button();
		if ( ! $is_add_form_page ) return;

		// do a version check for the new 3.5 UI
		$version = get_bloginfo( 'version' );

		if ( $version < 3.5 ) {
			// show button for v 3.4 and below
			$image_btn = IG_UNIFORM_URI . 'assets/images/icons-16/icon-form-16x16.png';
			echo '<a href="#TB_inline?width=350&height=500&inlineId=select_uniform_form" class="thickbox" id="add_ig_uniform" title="' . __( 'IGUniFrom Add Form', IG_UNIFORM_TEXTDOMAIN ) . '"><img src="' . $image_btn . '" alt="' . __( 'IGUniFrom Add Form', IG_UNIFORM_TEXTDOMAIN ) . '" /></a>';
		}
		else {
			// display button matching new UI
			echo '<style>.ig-uniform-media-icon{
                    background:url(' . IG_UNIFORM_URI . 'assets/images/icons-16/icon-form-16x16.png) no-repeat top left;
                    display: inline-block;
                    height: 16px;
                    margin: 0 2px 0 0;
                    vertical-align: text-top;
                    width: 16px;
                    }
                    .wp-core-ui a.ig-uniform-media-icon{
                     padding-left: 0.4em;
                    }
                 </style>
                  <a href="#TB_inline?width=350&height=500&inlineId=select_uniform_form" class="thickbox button" id="add_ig_uniform" title="' . __( 'IGUniFrom Add Form', IG_UNIFORM_TEXTDOMAIN ) . '"><span class="ig-uniform-media-icon "></span> ' . __( 'Add Form', IG_UNIFORM_TEXTDOMAIN ) . '</a>';
		}
	}

	/**
	 * Register admin menu for IT Uniform Plugin.
	 *
	 * @return  void
	 */
	public static function ig_uniform_register_menus() {
		global $pagenow;
		// Get product information
		$plugin = IG_Product_Info::get( IG_UNIFORM_IDENTIFIED_NAME );
		// Generate menu title
		$menu_title = __( 'IG UniForm', IG_UNIFORM_TEXTDOMAIN );

		if ( $plugin[ 'Available_Update' ] && ( 'edit.php' != $pagenow || ! isset( $_GET[ 'post_type' ] ) || ! in_array( $_GET[ 'post_type' ], self::$pages ) ) ) {
			IG_Init_Admin_Menu::replace(
				'IG UniForm', array(
					0 => "IG UniForm  <span class='ig-available-updates update-plugins count-{$plugin['Available_Update']}'><span class='pending-count'>{$plugin['Available_Update']}</span></span>",
					1 => 'edit_posts',
					2 => 'edit.php?post_type=ig_uniform_post_type',
					3 => '',
					4 => 'menu-top menu-icon-ig_uniform_post_type',
					5 => 'menu-posts-ig_uniform_post_type',
					6 => IG_UNIFORM_URI . '/assets/images/icons-16/icon-forms.png',
				)
			);
		}
		// Register menu item for Forms page
		IG_Init_Admin_Menu::add(
			array(
				'parent_slug' => 'edit.php?post_type=ig_uniform_post_type',
				'menu_title' => __( 'All Forms', IG_UNIFORM_TEXTDOMAIN ),
				'menu_slug' => 'edit.php?post_type=ig_uniform_post_type',
				'capability' => 'edit_posts',
				'menu_slug' => 'edit.php?post_type=ig_uniform_post_type',
			)
		);
		// Register menu item for Forms page
		IG_Init_Admin_Menu::add(
			array(
				'parent_slug' => 'edit.php?post_type=ig_uniform_post_type',
				'menu_title' => __( 'Add New', IG_UNIFORM_TEXTDOMAIN ),
				'menu_slug' => 'post-new.php?post_type=ig_uniform_post_type',
				'capability' => 'edit_posts',
				'menu_slug' => 'post-new.php?post_type=ig_uniform_post_type',
			)
		);
		// Register menu item for Submissions page
		IG_Init_Admin_Menu::add(
			array(
				'parent_slug' => 'edit.php?post_type=' . 'ig_uniform_post_type',
				'page_title' => 'IG UniForm Plugin - Submissions',
				'menu_title' => 'Submissions',
				'capability' => 'edit_posts',
				'menu_slug' => 'edit.php?post_type=ig_uniform_sb',
			)
		);
		// Register menu item for configuration page
		IG_Init_Admin_Menu::add(
			array(
				'parent_slug' => 'edit.php?post_type=' . 'ig_uniform_post_type',
				'page_title' => 'IG UniForm Plugin - Settings',
				'menu_title' => 'Settings',
				'capability' => 'edit_posts',
				'menu_slug' => 'ig-uniform-settings',
				'function' => array( 'IGUniformActionHook', 'settings' )
			)
		);

		if ( $plugin[ 'Addons' ] ) {
			// Generate menu title
			$menu_title = __( 'Add-ons', IG_UNIFORM_TEXTDOMAIN );

			if ( $plugin[ 'Available_Update' ] && ( 'edit.php' == $pagenow && isset( $_GET[ 'post_type' ] ) && in_array( $_GET[ 'post_type' ], self::$pages ) ) ) {
				$menu_title .= " <span class='ig-available-updates update-plugins count-{$plugin['Available_Update']}'><span class='pending-count'>{$plugin['Available_Update']}</span></span>";
			}
			// Register menu item for configuration page
			IG_Init_Admin_Menu::add(
				array(
					'parent_slug' => 'edit.php?post_type=' . 'ig_uniform_post_type',
					'page_title' => 'IG UniForm Plugin - Addons',
					'menu_title' => $menu_title,
					'capability' => 'edit_posts',
					'menu_slug' => 'ig-uniform-addons',
					'function' => array( 'IGUniformActionHook', 'addons' )
				)
			);
		}
	}

	/**
	 * Load necessary assets.
	 *
	 * @return  void
	 */
	public static function load_assets() {
		global $pagenow;
		if ( in_array( $pagenow, array( 'edit.php', 'post.php', 'post-new.php' ) ) ) {
			$post_type = $pagenow == 'post.php' ? ( isset( $_REQUEST[ 'post' ] ) ? get_post_type(
				$_REQUEST[ 'post' ]
			) : '' ) : $_REQUEST[ 'post_type' ];
			if ( $post_type == 'ig_uniform_post_type' || $post_type == 'ig_uniform_sb' ) {
				// Load common assets
				$assets = IGUniformHelper::load_asset_edit_form();
				add_filter( 'ig_uniform_form_edit_assets', array( 'IGUniformHelper', 'load_asset_edit_form' ) );
				// Load additional assets for add/edit post page
				if ( $pagenow == 'edit.php' AND isset( $_REQUEST[ 'page' ] ) AND $_REQUEST[ 'page' ] == 'ig-sample-configuration' ) {
					$assets = array_merge( $assets, array() );
				}
				if ( $post_type != 'ig_uniform_sb' && $pagenow != 'edit.php' ) {
					IG_Init_Assets::load( $assets );
				}
			}
			if ( $post_type == 'ig_uniform_post_type' && empty( $_GET[ 'page' ] ) ) {
				add_action( 'delete_post', array( 'IGUniformActionHook', 'delete_form' ) );
				if ( $pagenow == 'edit.php' ) {
					add_filter( 'post_row_actions', array( 'IGUniformActionHook', 'hook_action_view_forms' ), 10, 2 );
					wp_enqueue_script( 'jquery' );
					$assets = array(
						'ig-bootstrap2-css',
						'ig-bootstrap2-jsn-gui-css',
						'ig-jquery-ui-css',
						'ig-uniform-css',
						'ig-uniform-forms-js',
					);
					add_filter( 'admin_footer_text', array( 'IGUniformHelper', 'get_footer' ) );
					IG_Init_Assets::load( $assets );
				}
			}
			if ( $post_type == 'ig_uniform_sb' && $pagenow == 'edit.php' ) {
				add_filter( 'admin_footer_text', array( 'IGUniformHelper', 'get_footer' ) );
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'jquery-ui' );
				wp_enqueue_script( 'jquery-ui-dialog' );
				$assets = array(
					'ig-bootstrap2-css',
					'ig-jquery-daterangepicker-bs2-css',
					'ig-bootstrap2-jsn-gui-css',
					'ig-jquery-ui-css',
					'ig-uniform-css',
					'ig-jquery-json-js',
					'ig-jquery-daterangepicker-js',
					'ig-jquery-daterangepicker-moment-js',
					'ig-uniform-submissions-js',
				);
				IG_Init_Assets::load( $assets );
				add_filter( 'months_dropdown_results', array( __CLASS__, 'ig_uniform_remove_filter_date' ), 10, 2 );
				add_action(
					'restrict_manage_posts', array(
						'IGUniformActionHook',
						'submissions_restrict_manage_data',
					)
				);
				add_action( 'pre_get_posts', array( 'IGUniformActionHook', 'filter_posts' ) );
				add_action( 'delete_post', array( 'IGUniformActionHook', 'delete_submission' ) );
				add_filter(
					'views_edit-ig_uniform_sb', array(
						'IGUniformActionHook',
						'ig_uniform_submissions_custom_view_count',
					), 10, 2
				);
			}
		}
	}

	public static function ig_uniform_remove_filter_date( $months, $post_type ) {
		return array();
	}

	/**
	 * Register custom post type for IG UniForm Plugin.
	 *
	 * @return  void
	 */
	public static function register_post_type() {
		IG_Init_Post_Type::add(
			array(
				'slug' => 'ig_uniform_post_type',
				'options' => array(
					'labels' => array(
						'name' => __( 'Forms', IG_UNIFORM_TEXTDOMAIN ),
						'menu_name' => __( 'IG UniForm', IG_UNIFORM_TEXTDOMAIN ),
						'edit_item' => __( 'Edit Form', IG_UNIFORM_TEXTDOMAIN ),
						'add_new_item' => __( 'Add New Form', IG_UNIFORM_TEXTDOMAIN ),
					),
					'supports' => array( 'title' ),
					'public' => true,
					'has_archive' => true,
					'menu_icon' => IG_UNIFORM_URI . 'assets/images/icons-16/icon-forms.png',
				),
				'meta_boxes' => array(
					array(
						'id' => 'ig_uniform_form_settings',
						'title' => __( 'Form Settings', IG_UNIFORM_TEXTDOMAIN ),
						'callback' => array( 'IG_Uniform_Form_Settings', 'print_form_settings_html' ),
						'save_post' => array( 'IG_Uniform_Form_Settings', 'ig_uniform_save_form' )
					),
				),
				'list_columns' => array(
					'title' => __( 'Title', IG_UNIFORM_TEXTDOMAIN ),
					'total_submissions' => __( 'Submissions', IG_UNIFORM_TEXTDOMAIN ),
					'form_short_code' => __( 'Short Code', IG_UNIFORM_TEXTDOMAIN ),
					'author' => __( 'Author', IG_UNIFORM_TEXTDOMAIN ),
					'date' => __( 'Date', IG_UNIFORM_TEXTDOMAIN )
				),
				'render_column' => array( 'IG_Uniform_Post_Type', 'render_form_column' ),
				'sortable_columns' => true,
				'main_feed' => true,

			)
		);
		IG_Init_Post_Type::add(
			array(
				'slug' => 'ig_uniform_sb',
				'options' => array(
					'labels' => array(
						'name' => __( 'Submissions', IG_UNIFORM_TEXTDOMAIN ),
						'singular_name' => __( 'Submission Edit', IG_UNIFORM_TEXTDOMAIN ),
						'edit_item' => __( 'Submission Detail', IG_UNIFORM_TEXTDOMAIN ),
						'add_new_item' => __( 'Submission Detail', IG_UNIFORM_TEXTDOMAIN ),
					),
					'supports' => array( 'title' ),
					'public' => false,
					'has_archive' => false,
				),
				'meta_boxes' => array(
					array(
						'id' => 'ig_uniform_submission_detail',
						'title' => __( 'Submission Data', IG_UNIFORM_TEXTDOMAIN ),
						'callback' => array( 'IG_Uniform_Submission_Detail', 'print_submission_detail_html' ),
						'save_post' => array( 'IG_Uniform_Submission_Detail', 'ig_uniform_submission_save_form' )
					),
				),
				'list_columns' => self::get_submissions_column(),
				'render_column' => array( 'IG_Uniform_Post_Type', 'render_submissions_column' ),
				'sortable_columns' => true,
				'main_feed' => true,
			)
		);
		IG_Init_Admin_Menu::remove( 'post-new.php?post_type=ig_uniform_post_type', 'edit.php?post_type=ig_uniform_post_type' );
		IG_Init_Admin_Menu::remove( 'edit.php?post_type=ig_uniform_post_type', 'edit.php?post_type=ig_uniform_post_type' );

	}

	/**
	 * Render submissions page.
	 *
	 * @return  void
	 */
	public static function get_submissions_column() {
		$column = array();
		if ( ! empty( $_GET[ 'ig_uniform_form_id' ] ) ) {
			$_SESSION[ 'ig-uniform' ][ 'form_id' ] = $_GET[ 'ig_uniform_form_id' ];
		}
		$formID = ! empty( $_SESSION[ 'ig-uniform' ][ 'form_id' ] ) ? $_SESSION[ 'ig-uniform' ][ 'form_id' ] : '';
		if ( empty( $formID ) ) {
			$postslist = get_posts(
				array(
					'post_type' => 'ig_uniform_post_type',
					'post_status' => 'any',
					'numberposts' => '99999',
				)
			);
			if ( ! empty( $postslist[ 0 ]->ID ) ) {
				$formID = $postslist[ 0 ]->ID;
				$_SESSION[ 'ig-uniform' ][ 'form_id' ] = $formID;
			}
		}
		$column[ 'date_created' ] = __( 'Date Submitted', IG_UNIFORM_TEXTDOMAIN );
		if ( ! empty( $formID ) ) {
			$fielForm = IGUniformHelper::get_filed_by_form_id( $formID );
			if ( ! empty( $fielForm ) ) {
				foreach ( $fielForm as $field ) {
					if ( ! empty( $field->field_id ) && ! empty( $field->field_type ) && ! in_array(
						$field->field_type, array(
							'static-content',
							'google-maps',
						)
					)
					) {
						$column[ '_' . $field->field_id ] = ! empty( $field->field_title ) ? $field->field_title : '';
					}
				}
			}
		}
		$column[ 'ip' ] = __( 'IP Address', IG_UNIFORM_TEXTDOMAIN );
		$column[ 'browser' ] = __( 'Browser', IG_UNIFORM_TEXTDOMAIN );
		$column[ 'os' ] = __( 'Operating System', IG_UNIFORM_TEXTDOMAIN );
		return $column;
	}

	/**
	 * Submissions restrict manage data
	 */
	public static function submissions_restrict_manage_data() {
		$forms = get_posts(
			array(
				'post_type' => 'ig_uniform_post_type',
				'post_status' => 'any',
				'numberposts' => '99999',
			)
		);
		if ( ! empty( $forms ) ) {
			$formID = ! empty( $_SESSION[ 'ig-uniform' ][ 'form_id' ] ) ? $_SESSION[ 'ig-uniform' ][ 'form_id' ] : '';
			if ( empty( $formID ) ) {
				$postslist = get_posts(
					array(
						'post_type' => 'ig_uniform_post_type',
						'post_status' => 'any',
						'numberposts' => '99999',
					)
				);
				if ( ! empty( $postslist[ 0 ]->ID ) ) {
					$formID = $postslist[ 0 ]->ID;
					$_SESSION[ 'ig-uniform' ][ 'form_id' ] = $formID;
				}
			}
			echo '<select name="ig_uniform_form_id" id="dropdown_ig_form_id">';
			echo '<option value="-1">- Select Form -</option>';
			foreach ( $forms as $f ) {
				$fTitle = ! empty( $f->post_title ) ? $f->post_title : '(No Title)';
				$meta = get_post_meta( (int)$f->ID );
				if ( ! empty( $meta[ 'form_id' ][ 0 ] ) ) {
					$fID = (int)$meta[ 'form_id' ][ 0 ];
				}
				else {
					$fID = (int)$f->ID;
				}
				echo '<option value="' . esc_attr( $fID ) . '"';
				if ( isset( $formID ) ) selected( $fID, $formID );
				echo '>' . $fTitle . '</option>';
			}
			echo '</select>';
			$date = ! empty( $_GET[ 'filter_date' ] ) ? $_GET[ 'filter_date' ] : '';
			echo '
			<input type="text" readonly placeholder="' . __( '- Select Date -', IG_UNIFORM_TEXTDOMAIN ) . '" value="' . $date . '" name="filter_date" id="ig-submission-filter-date">
			<input type="submit" value="Clear" id="clear-submit" class="button" id="clear-submit" >
			';


		}
	}

	/**
	 *  Get query where
	 *
	 * @param string $where
	 *
	 * @return mixed|string
	 */
	public static function submissions_where( $where = '' ) {
		global $wpdb;
		//get Form id
		$formID = ! empty( $_SESSION[ 'ig-uniform' ][ 'form_id' ] ) ? $_SESSION[ 'ig-uniform' ][ 'form_id' ] : '';
		//check validation form id
		if ( ! empty( $formID ) && is_numeric( $formID ) ) {
			$where .= ' AND post_content = ' . (int)$formID;

			$dateSubmission = ! empty( $_GET[ 'filter_date' ] ) ? $_GET[ 'filter_date' ] : '';
			if ( ! empty( $dateSubmission ) ) {
				$dateSubmission = @explode( ' - ', $dateSubmission );
				$dateStart = @explode( '/', $dateSubmission[ 0 ] );
				$dateStart = @$dateStart[ 2 ] . '-' . @$dateStart[ 0 ] . '-' . @$dateStart[ 1 ];
				if ( @$dateSubmission[ 1 ] ) {
					$dateEnd = @explode( '/', $dateSubmission[ 1 ] );
					$dateEnd = @$dateEnd[ 2 ] . '-' . @$dateEnd[ 0 ] . '-' . @$dateEnd[ 1 ];
					$where = $wpdb->prepare( $where .= ' AND ( date(post_date) BETWEEN %s AND %s )', $dateStart, $dateEnd );
				}
				else {
					$where = $wpdb->prepare( $where .= ' AND date(submission_created_at) = %s ', $dateStart );
				}
			}
			if ( ! empty( $_GET[ 's' ] ) ) {
				//replace query where
				$where = preg_replace( '/AND \(\(\((.*?).post_title LIKE (.*?)\) OR \((.*?).post_content LIKE (.*?)\)\)\)/', '', $where );
				// query get list submission id
				$submissionData = $wpdb->get_results(
					$wpdb->prepare(
						"SELECT submission_id FROM {$wpdb->prefix}ig_uniform_submission_data WHERE form_id = %d AND submission_data_value LIKE '%%%s%%' ORDER BY submission_data_id ASC", (int)$formID, $_GET[ 's' ]
					)
				);
				$listID = array();
				if ( ! empty( $submissionData ) ) {
					foreach ( $submissionData as $getID ) {
						$listID[ ] = $getID->submission_id;
					}
					if ( ! empty( $listID ) ) {
						$listID = array_unique( $listID );
						$where .= ' AND id IN (' . implode( ',', $listID ) . ')';
					}
				}
				else {
					$where .= ' AND 1=2';
				}
			}
		}
		return $where;
	}


	/**
	 * Action Hook delete form data
	 *
	 * @param $post_id
	 */
	public static function delete_form( $post_id ) {
		global $wpdb;
		$wpdb->query( $wpdb->prepare( 'DELETE FROM ' . $wpdb->prefix . 'ig_uniform_submission_data WHERE form_id = %d ', (int)$post_id ) );
		$wpdb->query( $wpdb->prepare( 'DELETE FROM ' . $wpdb->prefix . 'ig_uniform_fields WHERE form_id = %d ', (int)$post_id ) );
		$wpdb->query( $wpdb->prepare( 'DELETE FROM ' . $wpdb->prefix . 'ig_uniform_form_pages WHERE form_id = %d ', (int)$post_id ) );
	}

	/**
	 * Action Hook delete submission data
	 *
	 * @param $post_id
	 */
	public static function delete_submission( $post_id ) {
		global $wpdb;
		$wpdb->query( $wpdb->prepare( 'DELETE FROM ' . $wpdb->prefix . 'ig_uniform_submission_data WHERE submission_id = %d ', (int)$post_id ) );
	}

	/**
	 * Submission filter posts
	 *
	 * @param $query
	 */
	public static function filter_posts( $query ) {
		add_filter( 'posts_where', array( 'IGUniformActionHook', 'submissions_where' ) );
	}

	/**
	 * Action View Forms
	 *
	 * @param $actions
	 * @param $page_object
	 *
	 * @return mixed
	 */
	public static function hook_action_view_forms( $actions, $post_object ) {
		$post_id = $post_object->ID;
		$meta = get_post_meta( (int)$post_id );
		if ( ! empty( $meta[ 'form_id' ][ 0 ] ) ) {
			$form_id = (int)$meta[ 'form_id' ][ 0 ];
		}
		else {
			$form_id = (int)$post_id;
		}
		$action = array();
		foreach ( $actions as $k => $v ) {
			$action[ $k ] = $v;
			if ( $k == 'edit' ) {
				$action[ 'duplicate' ] = '<a href="?ig-gadget=uniform-duplicate&action=default&form_id=' . $post_object->ID . '">' . __( 'Duplicate' ) . '</a>';
				$action[ 'submissions' ] = '<a href="edit.php?post_status=all&post_type=ig_uniform_sb&action=-1&m=0&paged=1&mode=list&action2=-1&ig_uniform_form_id=' . $form_id . '">' . __( 'Submissions' ) . '</a>';
			}
		}
		return $action;
	}

	/**
	 *  Show date filters
	 */
	public static function ig_uniform_submissions_filters() {

	}
}