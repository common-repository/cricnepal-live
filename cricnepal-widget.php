<?php
/*
Plugin Name: Cricnepal Live Center
Plugin URI: http://cricnepal.com
Description: Cricnepal Live Center plugin allows you to easily display Live Scores of Nepali Cricket on your site. The plugin is relatively easy to setup.
Version: 1.2.0
Author: Nis Tiwari via Namastec
Author URI: http://www.nischaltiwari.com
*/


function widget_cricnepal_init() {
	
	
	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') ){
		return;	
	}

	// This saves options and prints the widget's config form.
	function widget_cricnepal_control() {
		$options = $newoptions = get_option('cricnepal');
		if ( $_POST['widget-submit'] ) {
			$newoptions['title'] = $_POST['widget-title'];
			$newoptions['url'] = $_POST['widget-url'];			
		}

		if ( $options != $newoptions ) {
			$options = $newoptions;
			update_option('widget_cricnepal', $options);
		}
		?>
		<div style='text-align:right'>
		     <p style='text-align:left;'><label for="widget-intro">Display a Cricnepal Live Scorecard <a href='http://www.cricnepal.com'>Cricnepal.com</a>. For details <a href='http://www.facebook.com/cricnepal' target='_blank'>click here</a>.</label></p>
			<label for='widget-title' style='line-height:35px;display:block;'>Title: <input type='text' id='widget-title' name='widget-title' value='<?php echo ($options['title']); ?>' /></label>
			
			<label for='widget-url' style='line-height:35px;display:block;'><input id='widget-url' name='widget-url' value='<?php echo $options['url']; ?>' type='hidden' /></label>
			<input type='hidden' name='widget-submit' id='widget-submit' value='1' />
		</div>
		<?php
	}

	// This prints the widget
	function widget_cricnepal($args) {	
		extract($args);
		$defaults = array('title' => 'Cricnepal.com', 'url' => 'http://www.cricnepal.com');
		$options = (array) get_option('widget_cricnepal');

		//If the user has not yet set the options or set them empty, take the defaults
		foreach ( $defaults as $key => $value ){
			if ( !isset($options[$key]) || $options[$key] == ""){
				$options[$key] = $defaults[$key];	
			}
		}
		
		$title = $options['title'];
		
		?>
		<?php echo $before_widget . $before_title . $title . $after_title; ?>
		
      <?
		 $homepage = file_get_contents('http://live.cricnepal.com/plugins.php');
echo $homepage;

?>
		<?php echo $after_widget; ?>
		<?php
	}

	// Tell Dynamic Sidebar about our new widget and its control
	register_sidebar_widget('Cricnepal Live', 'widget_cricnepal');
	register_widget_control('Cricnepal Live', 'widget_cricnepal_control');
}

//Converts all the occurances of [dciframe][/dciframe] to IFRAME HTML tags
function widget_cricnepal_on_page($text){
	$regex = '#\[dciframe]((?:[^\[]|\[(?!/?dciframe])|(?R))+)\[/dciframe]#';
	if (is_array($text)) {
		//Read the Width/Height Parameters, if given
	    $param = explode(",", $text[1]);
		$others = "";
		//generate the IFRAME tag
        $text = '<iFrame frameborder="0" src="'.$param[0].'"'.$others.'></iFrame>';
    }
	return preg_replace_callback($regex, 'widget_cricnepal_on_page', $text);
}

// Delay plugin execution to ensure Dynamic Sidebar has a chance to load first
add_action('plugins_loaded', 'widget_cricnepal_init');
add_filter('the_content', 'widget_cricnepal_on_page');
?>