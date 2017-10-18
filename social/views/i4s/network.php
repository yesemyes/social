<div class="posts-container">
    <div class="panel">
        <div class="panel-body">
<?php
$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]".$_SERVER['REQUEST_URI'];
global $current_user, $wpdb;
$table_name = $wpdb->prefix . "persons";
get_currentuserinfo();
$check_token = $wpdb->get_results("SELECT * FROM $table_name", OBJECT);
  
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
     //return "cURL Error #:" . $err;
  }
  if(isset($json->error)) {
     //return $json->error;
  }
  $result_user = $json;
?>
    <div class="row">
        <div class="col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title">List Of Social Networks</h2>
                </div>
                <div class="panel-body">
                    <p>Here will be list of connected accounts <?=$current_user->user_login?></p>
                    <?php if( isset($_GET['success']) && $_GET['success'] == 'false'):?>
                      <p>exists provider</p>
                    <?php endif;?>
                    <ul class="">
                        <?php foreach($result_user->userAccounts as $item):?>
                            <?php if(isset($item->userId,$item->access_token)):?>
                            <li class="list-group-item">
                                <div class="media">
                                    <img class="pull-left hidden-xs b2s-img-network" alt="Twitter" src="http://localhost/blog-to-social/wp-content/plugins/blog2social/assets/images/portale/<?=$item->icon?>">
                                    <div class="media-body network">
                                        <h4><?=$item->provider?>
                                            <span class="b2s-network-auth-count">(Connections <span class="">1</span>/1)</span>
                                            <span class="pull-right"><a href="#">+ Profile</a></span>
                                        </h4>
                                        <ul class="">
                                            <li class="">Profile: <?=$item->first_name?> <?=$item->last_name?> <span class="">(My profile)</span>
                                                <a href="https://social-plugin.000webhostapp.com/<?=$item->provider?>/login" class="">
                                                    <span class="glyphicon  glyphicon-refresh glyphicon-grey"></span>
                                                </a>
                                                <a class="deleteAccount" data-token="<?=$token?>" data-id="<?=$item->userId?>" href="#">
                                                    <span class="glyphicon  glyphicon-trash glyphicon-grey"></span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <?php else:?>
                            <li class="list-group-item">
                                <div class="media">
                                    <img class="pull-left hidden-xs b2s-img-network" alt="Twitter" src="http://localhost/blog-to-social/wp-content/plugins/blog2social/assets/images/portale/<?=$item->icon?>">
                                    <div class="media-body network">
                                        <h4><?=$item->provider?>
                                            <span class="b2s-network-auth-count">(Connections <span class="">0</span>/1)</span>
                                            <span class="pull-right"><a href="http://social-lena.dev/<?=$item->provider?>/login/?wp=true&user_id=<?=$result_user->user->id?>&user_name=<?=$current_user->user_login?>">+ Profile</a></span>
                                        </h4>
                                    </div>
                                </div>
                            </li>
                            <?php endif;?>
                        <?php endforeach;?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<?php	else:?>
<h2>Please <a href='admin.php?page=iio4social-settings'>Login</a></h2>

<?php die; endif;?>
</div>
    </div>
</div>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/core.js"></script>
<script type="text/javascript">
jQuery(document).ready(function($){

  $('.deleteAccount').on('click', function(e) {
      /*$.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });*/


      var dataId = $(this).attr('data-id');
      var token = $(this).attr('data-token');
      $.ajax({
          url: 'https://social-plugin.000webhostapp.com/api/account/delete',
          type: 'POST',
          data: {
            "id": dataId,
            "token": token
          },
          success: function( msg ) {
          	//console.log(msg);
            if ( msg.status === 'success' ) {
            	alert('okssss');
                setInterval(function() {
                    window.location.reload();
                }, 1000);
            }
          },
          error: function( data ) {
          	//console.log(data);
              //alert('noooooo');
          },
          //beforeSend: setHeader
      });

      return false;
  });
  /*function setHeader(xhr) {

	  xhr.setRequestHeader('Authorization', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjcyLCJpc3MiOiJodHRwOi8vc29jaWFsLWxlbmEuZGV2L2FwaS9sb2dpbiIsImlhdCI6MTUwNzM5MjIwOCwiZXhwIjoxNTA3Mzk5NDA4LCJuYmYiOjE1MDczOTIyMDgsImp0aSI6InFVOVZMSnlySUI1amdnSHQifQ.fi3OOECgE4Rb94UJLt0vXyg92pKmYHEp0-yS8It3Ghg');
	}*/
});
</script>