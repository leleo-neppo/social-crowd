<?php
/**
 * Core functions for Plugin
 */


/**
 * Gets options string from the DB and converts it into an array
 *
 * @since 0.1
 * @author randall@macnative.com
 */
function SocialCrowd_GetOptions()
{
	$options = array();
	$suboptions = explode("~",get_option('Social_Crowd_Options'));
	for($x=0; $x < count($suboptions); $x++){
		$temp = explode(":",$suboptions[$x]);
		$options[$temp[0]] = $temp[1];
	}
	return $options;
}

/**
 * Return Select Form Element
 *
 * @since 0.1
 * @author randall@macnative.com
 */
function SocialCrowd_Make_Select($x = "", $fields, $class="select", $id="select", $name="select") {
	echo '<select name="'.$name.'" id="'.$id.'" class="'.$class.'">';
		foreach ($fields as $shown => $value) {
			if($x == $value){
				echo '<option value="'.$value.'" selected="selected" >'.$shown.'</option>';
			}else{
				echo '<option value="'.$value.'" >'.$shown.'</option>';
			}
		}
	echo '</select>';
}


/**
 * Add XML Loading Function
 * 
 * @since 0.1
 * @author randall@macnative.com
 */
function SocialCrowd_Load_XML($url) 
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $count = curl_exec($curl);
    curl_close($curl);
    return @simplexml_load_string($count);
}

/**
 * Add JSON Loading Function
 * 
 * @since 0.1
 * @author randall@macnative.com
 */
function SocialCrowd_Load_JSON($url) 
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $count = curl_exec($curl);
    curl_close($curl);
    return $count;
 }

?>