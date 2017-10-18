<?php
/* Data */
//require_once (I4S_PLUGIN_DIR . 'includes/B2S/Post/Filter.php');
require_once (I4S_PLUGIN_DIR . 'includes/Util.php');
global $current_user, $wpdb;
$table_name = $wpdb->prefix . "persons";
get_currentuserinfo();
$check_token = $wpdb->get_results("SELECT * FROM $table_name", OBJECT);
if( isset($check_token[0]->token) && $check_token[0]->token != "" && $check_token[0]->username != '' ):
$args = array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => -1
);
$posts = get_posts($args);
?>
<div class="posts-container">
    <div class="panel">
        <div class="panel-body">
            <h2 class="panel-title">Posts & Sharings</h2>
            <ul class="posts">
                <?php foreach ($posts as $post): ?>
                    <?php $userInfo = get_user_meta($post->post_author); ?>
                    <li>
                        <i class="fa fa-pencil-square-o"></i>
                        <div class="tbCell">
                            <strong>
                            <a href="<?php echo $post->guid ?>" class="postTitle" target="_blank"><?php echo $post->post_title ?></a>
                            </strong>
                            <span class="pull-right shareBtn">
                                <a class="btn btn-success btn-sm publishPostBtn" href="admin.php?page=blog2social-ship&amp;postId=<?=$post->ID?>">Share on Social Media</a>
                            </span>
                            <p class="info hidden-xs">#<?php echo $post->ID ?> | Author <a href="<?php echo get_author_posts_url($post->post_author) ?>"><?php echo (isset($userInfo['nickname'][0]) ? $userInfo['nickname'][0] : '-') ?></a> | published on blog: <?php echo B2S_Util::getCustomDateFormat($post->post_date, substr('en', 0, 2)) ?></p>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
<?php else:?>
    <h2>Please <a href='admin.php?page=iio4social-settings'>Login</a></h2>
<?php endif;?>