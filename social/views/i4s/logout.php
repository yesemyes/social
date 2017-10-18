<?php 
session_destroy();
global $current_user, $wpdb;
get_currentuserinfo();
$check_token = $wpdb->get_results("SELECT * FROM persons", OBJECT);  
if( $check_token != null ) {
    $query = $wpdb->query("UPDATE persons SET token = '', user_id = '$current_user->ID', username = '', password = '' WHERE ID = ".$check_token[0]->ID);
}
?>
<script type="text/javascript">
	window.location.href = "admin.php?page=iio4social-settings";
</script>