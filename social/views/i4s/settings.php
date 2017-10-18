<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

global $current_user, $wpdb;
$table_name = $wpdb->prefix . "persons";
get_currentuserinfo();
$check_token = $wpdb->get_results("SELECT * FROM $table_name", OBJECT);
$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
/* REG */
if( isset($_POST['reg_name'], $_POST['reg_email'], $_POST['reg_password'], $_POST['reg_comfirm_password']) && 
$_POST['reg_name'] != null && $_POST['reg_email'] != null && $_POST['reg_password'] === $_POST['reg_comfirm_password'])
{
    $data = json_encode([
                'name'      => $_POST['reg_name'],
                'email'     => $_POST['reg_email'],
                'password'  => $_POST['reg_password'],
                'user_url'  => $_POST['reg_url'],
           ]);
    $ch = curl_init("http://social-lena.dev/api/register");
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $result = curl_exec($ch);
    $err = curl_error($ch);
    $json   = json_decode($result);
    curl_close($ch);

    if(isset($json->error)){
       $_SESSION['success_reg'] = false;
    }
    if( isset($json->result) && $json->result == true ) {
        $_SESSION['success_reg'] = true;
        $_SESSION['success_log'] = '123';
        
        if( $wpdb->get_var( "show tables like '$table_name'" ) != $table_name )
        {
        	$charset_collate = $wpdb->get_charset_collate();
			$sql = "CREATE TABLE ".$table_name." (
				ID int NOT NULL AUTO_INCREMENT,
				token text,
				user_id int(11) NOT NULL,
				api_user_id int(11) NOT NULL,
				username varchar(255) NOT NULL,
				password varchar(255) NOT NULL,
				PRIMARY KEY (ID)
			) $charset_collate;";
			require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
			dbDelta($sql);
        }
    }
}

/* LOGIN */
if( isset($_POST['log_email'], $_POST['log_password']) && $_POST['log_email'] != null && $_POST['log_password'] != null )
{
    $username = $_POST['log_email'];
    $password = $_POST['log_password'];
    $data = json_encode([
               'email' => $username,
               'password' => $password,
           ]);
    $ch = curl_init("http://social-lena.dev/api/login");
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $result = curl_exec($ch);
    $err = curl_error($ch);
    $json   = json_decode($result);
    curl_close($ch);
    if ($err) {
       return "cURL Error #:" . $err;
    }
    if(isset($json->status) && $json->status != true) {
       $_SESSION['success_log'] = 'false';
    }
    if( isset($json->status) && $json->status == true ) {
        $_SESSION['success_log'] = 'true';
        $token = $json->token;
        $userID = $current_user->ID;
        $authID = $json->auth->id;
        unset($_SESSION['success_reg']);
        if( $check_token != null ) {
            $query = $wpdb->query("UPDATE $table_name SET token = '$json->token', user_id = '$current_user->ID', username = '$username', password = '$password' WHERE ID = ".$check_token[0]->ID);
        }else{
            $sql = "INSERT INTO $table_name (token, user_id, api_user_id, username, password) VALUES ('$token', '$userID', '$authID', '$username', '$password')";
            $wpdb->query($sql);
            /*if( $wpdb->query($sql) )
            {
            	die('ok ins');
            }else{
            	die('no insert');
            }*/
            
        }
    }
}
elseif( isset($check_token) && $check_token != null ){
    $data = json_encode([
               'email' => $check_token[0]->username,
               'password' => $check_token[0]->password,
           ]);
    $ch = curl_init("http://social-lena.dev/api/login");
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $result = curl_exec($ch);
    $err = curl_error($ch);
    $json   = json_decode($result);
    curl_close($ch);



    if( isset($json->status) && $json->status == true ) {
        $_SESSION['success_log'] = 'true';
        if( $check_token != null ) {
            $query = $wpdb->query("UPDATE $table_name SET token = '$json->token', user_id = '$current_user->ID' WHERE ID = ".$check_token[0]->ID);

        }
    }
}
?>

<div class="b2s-container">
    <div class=" b2s-inbox col-md-12 del-padding-left">
        <div class="col-md-9 del-padding-left">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="col-md-12">
                        
                        <div class="row b2s-user-settings-area">

                        <?php //if( $check_token == null  ):?>
                            <div class="registration">
                            <?php if( isset($_SESSION['success_reg']) && $_SESSION['success_reg'] == true ):?>
                                <h2>Success Reg please login</h2>
                            <?php else:?>
                                <?php if( isset($_SESSION['success_reg']) && $_SESSION['success_reg'] == false ):?>
                                <h2>Error Registration New User</h2>
                                <?php elseif( !isset($_SESSION['success_reg']) ):?>
                                <h2>Registration New User</h2>
                                <?php endif;?>
                                <form action="" method="POST">
                                    <input type="hidden" value="<?=$actual_link?>" name="reg_url">
                                    <div>
                                        <label for="reg_name">User Name</label>
                                        <input type="text" name="reg_name" id="reg_name" placeholder="Enter your name">
                                    </div>
                                    <div>
                                        <label for="reg_email">Email</label>
                                        <input type="email" name="reg_email" id="reg_email" placeholder="Enter your email">
                                    </div>
                                    <div>
                                        <label for="reg_password">Password</label>
                                        <input type="password" name="reg_password" id="reg_password" placeholder="Enter your password">
                                    </div>
                                    <div>
                                        <label for="reg_comfirm_password">Comfirm password</label>
                                        <input type="password" name="reg_comfirm_password" id="reg_comfirm_password" placeholder="Comfirm password">
                                    </div>
                                    <div>
                                        <button>Registration</button>
                                    </div>
                                </form>
                            <?php endif;?> <!-- success_reg -->
                            </div>
                        <?php //endif;?>

                        <!-- End Reg -->


                        <!-- Login -->
                        <?php //if( isset($_SESSION['success_log']) ):?>
                            <div class="login">

                            <?php if( isset($_SESSION['success_log']) && $_SESSION['success_log'] == 'true' ):?>
                                <h2>Welcome back <a href="admin.php?page=logout">Log out</a></h2>
                            <?php else: ?>
                                <?php if( isset($_SESSION['success_log']) && $_SESSION['success_log'] == 'false' ):?>
                                    <h2>Error Login</h2>
                                <?php else:?>
                                    <h2>Login</h2>
                                <?php endif;?>
                                <form action="" method="POST">
                                    <div>
                                        <label for="log_email">Email</label>
                                        <input type="email" name="log_email" id="log_email" placeholder="Enter your email">
                                    </div>
                                    <div>
                                        <label for="log_password">Password</label>
                                        <input type="password" name="log_password" id="log_password" placeholder="Enter your password">
                                    </div>
                                    <div>
                                        <button>Login</button>
                                    </div>
                                </form>
                            <?php endif;?>
                            </div>
                            
                        <?php //endif;?>
                        <!-- End Login -->
                        
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>