<?php
/*
Plugin Name: Post blog.ru
Plugin URI: http://bogutsky.ru/?page_id=22
Description: This plugin automatically publishes posts from the your blog on a service <a href='http://blog.ru' target='_blank'>Blog.ru</a>.
Author: Bogutsky Yaroslav
Version: 1.2
Author URI: http://bogutsky.ru/
*/
/*  Copyright 2011  Bogutsky Yaroslav  (email: admin@bogutsky.ru)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
function postblogru_feedback()
{
	$feedback = "
	<div>
	". __('Like this plugin?','postblogru') ." ". __('Press','postblogru') ." <button id=\"postblogru_send_thank_btn\" class=\"button\">". __('Send thank','postblogru') ."</button> ". __('or','postblogru') ." <button id=\"postblogru_show_responseform\" class='button'>". __('Send message to author','postblogru') ."</button>
	<div id=\"postblogru_responseform\" style=\"display:none;\">
	<input type=\"hidden\" id=\"postblogru_send_project\" value=\"postblogru\">
	<input type=\"hidden\" id=\"postblogru_send_url\" value=\"". get_bloginfo('siteurl') ."\">
	<input type=\"hidden\" id=\"postblogru_send_email\" value=\"". get_bloginfo('admin_email')."\">
	<textarea id=\"postblogru_send_response\"></textarea><br>
	<input id=\"postblogru_send_response_btn\" class=\"button\" type=\"button\"  value=\"". __('Send message','postblogru') ."\">
	</div>
	</div>
	";
	return $feedback;
}

	
if( is_admin() )
{
	/* Административная часть */
	add_action('admin_head','postblogru_admin_add_js');	
	function postblogru_admin_add_js()
	{
		echo "<script type='text/javascript' src='" . get_bloginfo('siteurl'). "/wp-content/plugins/" . dirname(plugin_basename( __FILE__ )) . "/js/jquery-1.5.1.js" ."'></script>";
		echo "<script type='text/javascript' src='" . get_bloginfo('siteurl'). "/wp-content/plugins/" . dirname(plugin_basename( __FILE__ )) . "/js/postblogru.js" ."'></script>";
	}
	
	function postblogru_deleteOptions($args)
	{
		$num = count($args);
		if ($num == 1) {
			delete_option($args[0]);
		}
		elseif (count($args) > 1)
		{
			foreach ($args as $option) {
				delete_option($option);
			}
		}
	}

	function postblogru_DeActivation () {
		$options = array(
			'postblogru_login',
			'postblogru_code'
		);
		postblogru_deleteOptions($options);
	}

	register_deactivation_hook(__FILE__,'postblogru_DeActivation');
	
	function postblogru_setOptions($args)
	{
		foreach ($args as $name => $value) {
			add_option($name,$value,'','no');
		}
	}
	
	function postblogru_Activation () {
		$options = array(
			'postblogru_login' => '',
			'postblogru_code' => ''
		);
		postblogru_setOptions($options);
		
	}
	register_activation_hook(__FILE__,'postblogru_Activation');

	add_action('admin_menu', 'postblogru_admin_menu');
	function postblogru_admin_menu(){
		add_options_page('Post Blog.ru', 'Post Blog.ru', 10, 'postblogru', 'postblogru_main');
		add_action( 'admin_init', 'register_postblogru_settings' );
	}
	
	function register_postblogru_settings() {
		register_setting( 'postblogru-settings-group', 'postblogru_login' );
		register_setting( 'postblogru-settings-group', 'postblogru_code' );
	}

	function postblogru_main() {
	
	?>

		<div class="wrap">
		<h2><?php _e('Plugin Post Blog.ru', 'postblogru'); ?></h2>
        <?php _e("This plugin automatically publishes records from the your blog on a service <a href='http://blog.ru' target='_blank'>Blog.ru.</a><br>For the successful publication it is necessary to specify the account data on Blog.ru<br><b> Attention, if the data is wrong or aren't entered the publication won't be made. <br> Records aren't updated. </b> <br>", 'postblogru'); ?>
        <h3><?php _e("Settings",'postblogru'); ?></h3>
		<?php settings_errors(); ?>
		<form method="post" action="options.php">
		<?php settings_fields( 'postblogru-settings-group' ); ?>
        <?php _e("The user name on the project Blog.ru (the login used for authorization):",'postblogru');?><br>
        <input type="text" name="postblogru_login" value="<?php echo get_option('postblogru_login'); ?>" size="50" maxlength="50"><br>
        <?php _e("Code word for the publication through email (it is established in account options on Blog.ru):",'postblogru'); ?><br>
        <input type="text" name="postblogru_code" value="<?php echo get_option('postblogru_code'); ?>" size="50" maxlength="50"><br>
		<input class="button" type="submit" value="<?php _e("Save",'postblogru'); ?>">
		</form>
		</div>
		<?php echo postblogru_feedback(); ?>
	<?php
	}

	add_action( 'publish_post', 'post_blog_ru' );
	
	function post_blog_ru( $id ){
		$postblogru_login = trim(get_option('postblogru_login'));
		$postblogru_code = trim(get_option('postblogru_code'));
		if(($postblogru_login)&&($postblogru_code))
		{
			$post = get_post($id);
			if($post->post_modified == $post->post_date)
				wp_mail( 'post@blog.ru', $postblogru_login . " " . $post->post_title . " " . $postblogru_code, $post->post_content);
		}

	}
	
	add_action('init', 'postblogru_textdomain');
	function postblogru_textdomain() {
		load_plugin_textdomain('postblogru', false, dirname( plugin_basename( __FILE__ ) ).'/lang/');
	}
}

?>
