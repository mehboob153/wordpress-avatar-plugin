<?php
/**
 * Plugin Name: Veteranlogix Fancy Avatars Plugin
 * Plugin URI: http://veteranlogix.com
 * Description: Plugin to upload avatars for users.
 * Version: 1.0.0
 * Author: VeteranLogix
 * Author URI: http://veteranlogix.com
 * License: GPLv2
 */
define( 'VL_USER_AVATAR_FOLDER', 'vl_avatars' );
define( 'VL_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'VL_PLUGIN_URI_PATH', plugin_dir_url( __FILE__ ) );

if(!class_exists('vl_fancy_avatars')){

	class vl_fancy_avatars{
		
		public function __construct() {
			add_filter( 'get_avatar' , array(&$this,'vl_get_avatars' ), 100 , 5);
			add_action( 'init', array(&$this,'vl_theme_scripts' ));
			add_shortcode('VL_AVATAR',array(&$this,'vl_avatar_function'));
			register_activation_hook( __FILE__, array( &$this, 'vl_avatar_activation' ) );
			add_filter( 'page_template', array(&$this, 'ajax_page_template') );
			add_action('admin_menu', array(&$this, 'register_menu_page'));
			register_deactivation_hook( __FILE__, array(&$this,'deactivate_plugin'));
		}
		
		public function vl_theme_scripts(){
			wp_enqueue_script( 'vl_cjqury_script', VL_PLUGIN_URI_PATH . 'inc/js/jquery.js', array('jquery'), '1.0.0', true );
			wp_enqueue_script( 'vl_custome_script', VL_PLUGIN_URI_PATH . 'inc/js/custom.js', array('jquery'), '1.0.0', true );
			wp_enqueue_script( 'vl_uploads_script', VL_PLUGIN_URI_PATH . 'inc/js/jquery.uploadfile.js', array('jquery'), '1.0.0', true );
			wp_enqueue_script( 'vl_avatars_script', VL_PLUGIN_URI_PATH . 'inc/js/vl_avatars.js', array('jquery'), '1.0.0', true );
			wp_enqueue_style( 'vl_avatars_css', VL_PLUGIN_URI_PATH . 'inc/css/vl_avatars.css', array(), '1.0.0');
		}

		public function register_menu_page() {
			add_menu_page( 'fancyavatar', 'Fancy Avatar', 'manage_options', 'avatar-settings',array(&$this, 'description_cb'),'dashicons-universal-access', 30);
			add_submenu_page('avatar-settings','settings','Settings','manage_options','fancy-avatar-settings',array(&$this, 'fancy_avatar_cb'));
		}
		
		public function description_cb(){
			?>
			<h1>Fancy Avatar </h1>
			<hr style="float:left;height:1px;background-color:#000;width:70%;">
			<section>
				<p><br>
					All you need to do to get the ball rolling is to follow these simple steps:<br>
					1. Install and active the plugin through WordPress' admin panel<br>
					2. Add the shortcode [VL_AVATAR] to the place you want to display your profile picture<br>

					That's it! Enjoy your funky little customized fancy avatar.
				</p>
			</section>
			<?php
		}
		public function fancy_avatar_cb(){
			?>
			<div class="wrap">
				<h1>Fancy Avatar Settings Page</h1>
				<hr>
				<h4>Select picture style</h4>
				<?php
				if (isset($_POST['btn'])) {	

					global $wpdb;
					$msg = "Settings  updated successfully";
					$msg1 = "Settings added successfully";
					$msg2 = "Something went wrong!";
					$shape=$_POST['style'];
					$circle_size=$_POST['circle_size'];
					$round_size=$_POST['round_size'];
					$size=$_POST['size_in'];

					$shape_option=get_option('fancy_style');
					$value_option=get_option('fancy_style_value');
					$size_option=get_option('fancy-style-size');
					if(($shape_option===false) && ($value_option===false) && ($size_option===false)){
						if($shape=='square'){
							$result1=add_option( 'fancy_style',$shape);
							$result2=add_option( 'fancy_style_value','');
							$result3=add_option( 'fancy_style_size',$size);
							if($result1==true && $result2==true && $result3==true){
								echo "<span class='eror'>" . $msg1 . "</span>";
							}else{
								echo "<span class='eror'>" . $msg2 . "</span>";
							}
						}
						if($shape=='circle'){
							$result1=add_option( 'fancy_style',$shape);
							$result2=add_option( 'fancy_style_value',$circle_size);
							$result3=add_option( 'fancy_style_size',$size);
							if($result1==true && $result2==true && $result3==true){
								echo "<span class='eror'>" . $msg1 . "</span>";
							}else{
								echo "<span class='eror'>" . $msg2 . "</span>";
							}
						}
						if($shape=='round'){
							$result1=add_option( 'fancy_style',$shape);
							$result2=add_option( 'fancy_style_value',$round_size);
							$result3=add_option( 'fancy_style_size',$size);
							if($result1==true && $result2==true && $result3==true){
								echo "<span class='eror'>" . $msg1 . "</span>";
							}else{
								echo "<span class='eror'>" . $msg2 . "</span>";
							}
						}

					}else{
						if($shape=='square'){
							update_option( 'fancy_style_size','xyz');
							update_option( 'fancy_style_value','xyz');
							update_option( 'fancy_style','xyz');
							$result1=update_option( 'fancy_style',$shape);
							$result2=update_option( 'fancy_style_value','');
							$result3=update_option( 'fancy_style_size',$size);
							if($result1==true && $result2==true && $result3==true){
								echo "<span class='eror'>" . $msg . "</span>";
							}else{
								echo "<span class='eror'>" . $msg2 . "</span>";
							}
						}
						if($shape=='circle'){
							update_option( 'fancy_style_size','xyz');
							update_option( 'fancy_style_value','xyz');
							update_option( 'fancy_style','xyz');
							$result1=update_option( 'fancy_style',$shape);
							$result2=update_option( 'fancy_style_value',$circle_size);
							$result3=update_option( 'fancy_style_size',$size);
							if($result1==true && $result2==true && $result3==true){
								echo "<span class='eror'>" . $msg . "</span>";
							}else{
								echo "<span class='eror'>" . $msg2 . "</span>";
							}
						}
						if($shape=='round'){
							update_option( 'fancy_style_size','xyz');
							update_option( 'fancy_style_value','xyz');
							update_option( 'fancy_style','xyz');
							$result1=update_option( 'fancy_style',$shape);
							$result2=update_option( 'fancy_style_value',$round_size);
							$result3=update_option( 'fancy_style_size',$size);
							if($result1==true && $result2==true && $result3==true){
								echo "<span class='eror'>" . $msg . "</span>";
							}else{
								echo "<span class='eror'>" . $msg2 . "</span>";
							}
						}
					}


				}
				?>
				<form method="post" action="">
					<input type="radio" name="style" value="square" id="square_input" <?php $got=get_option('fancy_style'); if($got=='square') {echo"checked";}?> >Square<br>

					<input type="radio" name="style" value="circle" id="circle_input" <?php $got=get_option('fancy_style'); if($got=='circle') {echo"checked";}?>>Circle<br>
					<div id="circle_div" class="hide_this">
						Enter size of circle value, in pixels: <input type='text' id='circle_in' name='circle_size' value="<?php $got=get_option('fancy_style_value'); echo $got;?>">
					</div>
					<input type="radio" name="style" value="round" id="round_input" <?php $got=get_option('fancy_style'); if($got=='round') {echo"checked";}?>>Round
					<div id="round_div" class="hide_this">
						Enter size of round value, in pixels: <input type='text' id='round_in' name='round_size' value="<?php $got=get_option('fancy_style_value'); echo $got;?>">
					</div>
					<lable>
						<h4>Select size of picture:</h4>
					</lable>
					<input type="radio" name="size_in" value="small" id="small_in" <?php $got=get_option('fancy_style_size'); if($got=='small') {echo"checked";}?>>Small
					<input type="radio" name="size_in" value="medium" id="medium_in" <?php $got=get_option('fancy_style_size'); if($got=='medium') {echo"checked";}?>>Medium
					<input type="radio" name="size_in" value="large" id="large_in" <?php $got=get_option('fancy_style_size'); if($got=='large') {echo"checked";}?> >Large<br><br>
					<input type="submit" name="btn" value="Save"  class="sub">
				</form>
			</div>	
			<?php
		}

		public function vl_avatar_activation($network_wide){
			global $wpdb;
			$upload_dir  = wp_upload_dir(); 
			if(!file_exists($upload_dir['basedir'].'/' . VL_USER_AVATAR_FOLDER)){
				mkdir($upload_dir['basedir'].'/'.VL_USER_AVATAR_FOLDER,0777,true);
			}
			// check if it is a multisite network
			if (is_multisite()) {
				// check if the plugin has been activated on the network or on a single site
				if ($network_wide) { 
					// get ids of all sites
					$blogids = wp_get_sites();
					foreach ($blogids as $blog_id) {
						switch_to_blog($blog_id['blog_id']);
						// create page for each site
						if	(get_page_by_title('ajax upload',OBJECT,'page') === NULL ){
							$page = array();
							$page['post_title']= "ajax upload";
							$page['post_status'] = "publish";
							$page['post_slug'] = "ajax-upload";
							$page['post_type'] = "page";
							$post_id = wp_insert_post($page);
							add_option("fancy_plugin_page_id",$post_id);
						}
						restore_current_blog();
					}	
				}
				else{
					//create page on a single site, in a multi-site
					if(get_page_by_title('ajax upload',OBJECT,'page') === NULL  ){
						$page = array();
						$page['post_title']= "ajax upload";
						$page['post_status'] = "publish";
						$page['post_slug'] = "ajax-upload";
						$page['post_type'] = "page";
						$post_id = wp_insert_post($page);
						add_option("fancy_plugin_page_id",$post_id);
					}
				}
			}
			else{
				//create page on a single site 
				if(get_page_by_title('ajax upload',OBJECT,'page') === NULL ){
					$page = array();
					$page['post_title']= "ajax upload";
					$page['post_status'] = "publish";
					$page['post_slug'] = "ajax-upload";
					$page['post_type'] = "page";
					$post_id = wp_insert_post($page);
					add_option("fancy_plugin_page_id",$post_id);
				}
			}
		}
		function deactivate_plugin($network_wide){
			global $wpdb;
			// check if it is a multisite network
			if (is_multisite()) {
				// check if the plugin has been activated on the network or on a single site
				if ($network_wide){ 	
					// get ids of all sites
					$blogids = wp_get_sites();
					foreach ($blogids as $blog_id) {
						switch_to_blog($blog_id['blog_id']);
						// we get the id of post means page
						$the_post_id = get_option("fancy_plugin_page_id");
						// delete the post from table
						wp_delete_post($the_post_id,true);
						delete_option( 'fancy_plugin_page_id',$the_post_id);
						restore_current_blog();
					}
				}else{
					//create page on a single site, in a multi-site
					// we get the id of post means page
					$the_post_id = get_option("fancy_plugin_page_id");
					if(!empty($the_post_id)){
						wp_delete_post($the_post_id,true);
						delete_option( 'fancy_plugin_page_id',$the_post_id);
					}
				}
			}
			else{
				//create page on a single site
				// we get the id of post means page
				$the_post_id = get_option("fancy_plugin_page_id");
				if(!empty($the_post_id)){
					// delete the post from table
					wp_delete_post($the_post_id,true);
					delete_option( 'fancy_plugin_page_id',$the_post_id);
				}
			}
			
		}
		public function ajax_page_template( $page_template )
		{
			if ( is_page( 'ajax-upload' ) ) {
				$page_template = dirname( __FILE__ ) . '/page-ajax-upload.php';
			}
			return $page_template;
		}

		public function vl_avatar_function(){
			/* Front end Avatar Display */
			global $current_user;
			$user_id = $current_user->ID;
			?>
			<div class="vl_profile_avatar_container">
				<?php 
				$size= get_option('fancy-style-size');
				if ( is_user_logged_in() ) {
					echo 'Welcome : ' . esc_html( $current_user->display_name );
					echo "<br>";
					echo $this->vl_get_avatars( $user_id,$user_id, $size ,'avatar','avatar'); ?>
					<br class="small-grid">
					<div class="vl_upload_container profile_avatar_upload_container hidden">
						<div class="vl_upload_area">Upload</div>
					</div>
					<div id="upload-button">
						<button type="button" class="btn btn-block vl_profile_upload_btn">UPLOAD</button>
						<br class="tiny-grid">
					</div>
			</div>
				<?php
			}else{
				echo "Sorry! you are not logged in";
				echo "<br>";
				 echo get_avatar($current_user->ID);
			}
			

		}

		public function vl_get_avatars( $avatar, $id_or_email, $size, $default, $alt ) {

			$data =get_option('fancy_style');
			if($data == 'round'){
				$style_class = 'round';
			}elseif ($data=='square') {
				$style_class = 'square';
			}elseif ($data=='circle') {
				$style_class = 'circle';
			}
			?>
			<style>
			img.round{
				border-radius:<?php echo get_option('fancy_style_value');?>;
			}
			img.circle {
				border-radius:50% ;
			}
		</style>
		<?php
		$user = false;
		$upload_dir  = wp_upload_dir(); 
			 // Check if user_id came or user_email
		if ( is_numeric( $id_or_email ) ) {

			$id = (int) $id_or_email;
			$user = get_user_by( 'id' , $id );

		} elseif ( is_object( $id_or_email ) ) {

			if ( ! empty( $id_or_email->user_id ) ) {
				$id = (int) $id_or_email->user_id;
				$user = get_user_by( 'id' , $id );
			}

		} else {
			$user = get_user_by( 'email', $id_or_email ); 
		}

		if ( $user && is_object( $user ) ) {
			$ext = get_user_meta($user->data->ID, 'avatar_file_extension', true);
			$psize= get_option('fancy_style_size');
			if ($psize=='medium') {

				$calc_size = '100x100';

			} else if ($psize=='small') {

				$calc_size = '50x50';

			} else if ($psize=='large') {

				$calc_size = '250x250';

			} else {

				$calc_size = '250x250';

			}

			$path = '/' . VL_USER_AVATAR_FOLDER . '/' . $user->data->user_login . '_' . $calc_size . '.' . $ext;

			if ( file_exists($upload_dir['basedir'] . $path )) {
				$filesize  = filesize($upload_dir['basedir'] . $path );
				$avatar_src = $upload_dir['baseurl'] . $path . '?' . $filesize;
				$avatar  = "<img alt='{$alt}' src='{$avatar_src}' class='{$style_class} avatar-{$size} photo' height='{$size}' width='{$size}' />";

			}  else {
				$path = 'default_avatar/no_avatar_' . $calc_size . '.png';
				$avatar_src = VL_PLUGIN_URI_PATH . $path;
				$avatar  = "<img alt='{$alt}' src='{$avatar_src}' class='{$style_class} avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
			}
		}
		
		return $avatar;
	}

}

new vl_fancy_avatars();

}
