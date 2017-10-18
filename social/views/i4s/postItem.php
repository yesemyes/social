<?php
/* Data */
global $current_user, $wpdb;
$table_name = $wpdb->prefix . "persons";
get_currentuserinfo();
$check_token = $wpdb->get_results("SELECT * FROM $table_name", OBJECT);
if(isset($_GET['postId'])){
	$ID = $_GET['postId'];	
	$get_info = get_post($ID);
	//$link = get_permalink($ID);
	$link = 'https://www.list.am/';
}else{
	exit('error!');
}
if( isset($check_token[0]->token) && $check_token[0]->token != "" ):
	$token = $check_token[0]->token;
	$data = json_encode([
	           'token'  => $token,
	           'user'   => $current_user->user_login,
	       ]);
	$curl = curl_init("http://social-lena.dev/api/users");
	curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_TIMEOUT, 10);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	$result = curl_exec($curl);
	$err = curl_error($curl);
	$json   = json_decode($result);
	curl_close($curl);
	if ($err) {
	 return "cURL Error #:" . $err;
	}
	if(isset($json->error)) {
	 return $json->error;
	}
	$result_user = $json;
	if( isset($_POST['share']) && isset($token) && $token != "wrong email or password." )
	{
		if(isset($_POST['postTitle']) && !empty($_POST['postTitle']) )
		{
			$postTitle = $_POST['postTitle'];
			$connected = $_POST["connected"];
			$success_share = [];
			foreach ($postTitle as $key => $value)
			{
				if( isset($connected) && $connected != null )
				{
					array_push($success_share, $connected[$key]);
					/*$imgTmpName = $_FILES['images']['tmp_name'][$key];
					$im = explode('\\', $imgTmpName);
					$imgOriginalName = $_FILES['images']['name'][$key];
					$imgType = $_FILES['images']['type'][$key];
					$imgSize = $_FILES['images']['size'][$key];
					$imgs = new stdClass();
					$imgs->originalName = $imgOriginalName;
					$imgs->mimeType = $imgType;
					$imgs->size = $imgSize;
					$imgs->pathName = $imgTmpName;
					$imgs->fileName = end($im);*/

					$token_soc = $_POST['token_soc'][$key];
					$token_soc_sec = $_POST['token_soc_sec'][$key];
					$postContent = $_POST['postContent'][$key];
					$data_share = json_encode([
				       'message' => $postTitle[$key],
				       'link' => $link,
				       'token' => $token,
				       'token_soc' => $token_soc,
				       'token_soc_sec' => $token_soc_sec,
				   	]);

					$ch_share = curl_init("http://social-lena.dev/api/".$_POST['provider'][$key]);
					curl_setopt($ch_share, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
					curl_setopt($ch_share, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch_share, CURLOPT_TIMEOUT, 10);
					curl_setopt($ch_share, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($ch_share, CURLOPT_POST, true);
					curl_setopt($ch_share, CURLOPT_POSTFIELDS, $data_share);

					$result_share = curl_exec($ch_share);
					$err_share = curl_error($ch_share);
					$json_share   = json_decode($result_share);
					curl_close($ch_share);

					if ($err_share) {
					   //return "cURL Error #:" . $err;
					}
					if(isset($json_share->error)) {
					   //return $json->error;
					}
					//var_dump($json_share); die('share oks');
				} // end if
			} // end foreach
		}
		$share = $json_share->result;
		

	}

?>
	<div class="posts-container">
	    <div class="panel">
	        <div class="panel-body">
	        	<form action="" method="POST" id="sharePost" enctype="multipart/form-data">
                	<?php if(isset($share)): ?>
                		<?php foreach($success_share as $item):?>
                			<?php if( $item != null ):?>
                				<h1>Your Post published in <?=$item?></h1>
                			<?php endif;?>
                		<?php endforeach;?>
                	<?php else:?>
	        		<input type="hidden" name="share">
		        	<div class="b2s-post-area col-md-9 del-padding-left">
		                <div class="b2s-post-list">
	                		<?php foreach ($result_user->userAccounts as $value) : ?>
								<?php if( isset($value->userId) ): ?>
								
								<div class="b2s-post-item" data-network-name="<?=$value->provider?>">
									<input type="hidden" name="token_soc[]" value="<?=$value->access_token?>">
									<input type="hidden" name="token_soc_sec[]" value="<?=$value->access_token_secret?>">
							    	<input type="hidden" name="provider[]" value="<?=$value->provider?>">
							        <div class="panel panel-group">
							            <div class="panel-body  ">
							                <div class="b2s-post-item-area">
							                    <div class="b2s-post-item-thumb hidden-xs">
							                    	<img alt="" data-network-auth-id="149004" class="img-responsive b2s-post-item-network-image" src="http://blog-to-social.shahum.net/wp-content/plugins/blog2social/assets/images/portale/<?=$value->icon?>">
							                    </div>
							                    <div class="b2s-post-item-details">
							                        <h4 class="pull-left b2s-post-item-details-network-display-name" data-network-auth-id="149004"><?=$value->first_name?> <?=$value->last_name?></h4>
							                        <div class="clearfix"></div>
							                        <p class="pull-left">Profile | <?=$value->provider?></p>
							                        <textarea class="form-control tw-textarea-input b2s-post-item-details-item-message-input valid" data-network-auth-id="" placeholder="Write something about your post..." name="postTitle[]" required="required"><?php echo $get_info->post_title;?></textarea>
							                        
							                        <!-- <div class="form-group">
											            <label>Add Multiple Images:</label>
											            <input type="file" name="images[]" multiple class="form-control">
											        </div> -->

							                        <input type="text" class="" style="width: 100%;" readonly name="postContent[]" value="<?php echo $get_info->post_content;?>">
							                        
							                    </div>
							                </div>
							            </div>
							            <input type="hidden" class="form-control" name="b2s[149004][network_id]" value="2">
							            <input type="hidden" class="form-control" name="b2s[149004][network_type]" value="0">
							            <input type="hidden" class="form-control" name="b2s[149004][network_display_name]" value="">
							        </div>
							    </div>

								<?php endif;?>
							<?php endforeach;?>
			                <div class="b2s-publish-area">
			                    <button type="button" class="btn btn-link pull-left btn-xs scroll-to-top"><span class="glyphicon glyphicon-chevron-up"></span> scroll to top </button>
			                    <button class="btn btn-success pull-right btn-lg b2s-submit-btn">Share</button>
			                </div>
			            </div>
		            </div>
		            <div class="col-md-3">
		            	<?php foreach ($result_user->userAccounts as $value) :?>
							<?php if( isset($value->userId) ):?>
								<div><?=$value->provider?> <input type="checkbox" name="connected[]" value="<?=$value->provider?>" checked="checked" class="connected"></div>
								<hr>
							<?php endif;?>
						<?php endforeach;?>
		            </div>
		        	<?php endif;?>
		        </form>
	         </div>
	    </div>
	</div>
	
<?php else:?>
	<h2>Please <a href='admin.php?page=iio4social-settings'>Login</a></h2>
<?php endif;?>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/core.js"></script>
<script type="text/javascript">
jQuery(document).ready(function($)
{
	var block = $("#sharePost").find(".b2s-post-item");
	$(document).on("click", ".noconnected", function()
	{
		var noconnected = $(this).val();
		$(this).attr("class", "connected");
		$(this).attr("name", "connected[]");
		$( block ).each(function( index ) {
		  var elem = $("div[data-network-name='"+noconnected+"']").css({"display":"block"});
		});
	});

	$(document).on("click", ".connected", function()
	{
		var connected = $(this).val();
		$(this).attr("class", "noconnected");
		$(this).attr("name", "noconnected[]");
		$( block ).each(function( index ) {
		  var elem = $("div[data-network-name='"+connected+"']").css({"display":"none"});
		});
	});

});
</script>