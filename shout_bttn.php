<?php
/*
Plugin Name: Shout Button Adder Thingy
Version: 0.5
Plugin URI: http://toastedrav.com/shout/labs
Author: Mike Flynn
Author URI: http://www.toastedrav.com
Description: To add a Shout button to the end of your blog posts.
*/ 

/*http://codex.wordpress.org/Adding_Administration_Menus*/
add_action('admin_menu', 'shout_button_admin_menu');
function shout_button_admin_menu() {
	add_submenu_page('options-general.php', 'Shout Button Options', 'Shout Button', 8, 'shout_buttn', 'shout_bttn_menu');
}


function shout_bttn_menu(){
   
  if ($_REQUEST['save']) {
	update_option('shout_appendType', mysql_escape_string($_REQUEST['shout_appendType']));		
	echo '<div id="message" class="updated fade"><p>Saved changes.</p></div>';
  }  else if($_REQUEST['clear']){
	delete_option('shout_appendType');
	echo '<div id="message" class="error fade"><p>Plugin cleared.</p></div>';
  }


  // load options from db to display
  $shout_appendType = get_option('shout_appendType');
	
  // display options
  print_shout_buttn_menu_form($shout_appendType);
}

function print_shout_buttn_menu_form($shout_appendType=''){
?>

<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" id="shout_form" name="shout_form">
<div class="wrap" id="shout_options">
<fieldset>
<h2>Shout Button Activation</h2>


<div class="wrap">
<h3>Options (Just the one!)</h3>

<p>Show the Shout button after your posts? </p>

<p>
<select name="shout_appendType">
	<option value="no" <?php if($shout_appendType=='no'){ echo "SELECTED"; } ?>>No</option>
	<option value="yes" <?php if($shout_appendType=='yes'){ echo "SELECTED"; } ?>>Yes</option>	
</select>
</p>
<p>
	<i>Note: If you want to modify where the button is located or its margin or whatever, the javascript generates an iFrame with the class "shout_bttn". - Mike</i>
</p>
</div>
</fieldset>

<p class="submit"><input name="clear" id="reset" style='width:150px' value="Reset Options" type="submit" />
<input name="save" id="save" style='width:150px' value="Save Changes" type="submit" /></p>
</div>
</form>

<?php
}

function shout_button_content_hook($content = ''){

     $result = "";
     global $wp_query; 
     $post = $wp_query->post;
     $id = $post->ID;
     $postlink = get_permalink($id);
     $title = urlencode($post->post_title);

     $shoutlink = split('#',$postlink);

     $shout_appendType = get_option('shout_appendType');

     if($shout_appendType == 'yes'){
         
		$bttn_string .= "<script type=\"text/javascript\">\n";
		$bttn_string .= "\tshout_link = '".htmlspecialchars($shoutlink['0'])."';\n";
		$bttn_string .= "</script>\n";
		$bttn_string .= "<script type=\"text/javascript\" src=\"http://toastedrav.com/modules/shout/partners/button.js\"></script>";
	        
		$content .= $bttn_string;

     }
     
     return $content;
}


function shout_button_generate()
{

	$result = "";
	global $wp_query; 
	$post = $wp_query->post;
	$id = $post->ID;
	$postlink = get_permalink($id);
    $title = urlencode($post->post_title);

	$shoutlink = split('#',$postlink);

	$bttn_string .= "<script type=\"text/javascript\">\n";
	$bttn_string .= "\tshout_link = '".htmlspecialchars($shoutlink['0'])."';\n";
	$bttn_string .= "</script>\n";
	$bttn_string .= "<script type=\"text/javascript\" src=\"http://toastedrav.com/modules/shout/partners/button.js\"></script>";  

  	echo $result;
}  

   add_filter('the_content', 'shout_button_content_hook');

?>