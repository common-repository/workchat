<?php
// check if the current user has the ability to manage options in WordPress
if (!current_user_can('manage_options')) {
    return;
}

wp_enqueue_style('bootstrap', plugins_url('../admin/css/bootstrap.min.css', __FILE__));
wp_enqueue_style('theme-default.css', plugins_url('../admin/css/theme-default.css', __FILE__));
wp_enqueue_style('style_overtext.css', plugins_url('../admin/css/style_overtext.css', __FILE__));
wp_enqueue_style('style_input.css', plugins_url('../admin/css/style_input.css', __FILE__));

$token = get_option('cloodo_token');

$company_name = get_option('blogname'); //get website name

$author_token = [
    'headers' => ['Authorization' => 'Bearer ' . get_option('cloodo_token')]
];

$widget_name = 'Wordpress Widget: ' . get_option('blogname');
$token_data_widget = [
    'headers' => ['Authorization' => 'Bearer ' . get_option('cloodo_token')],
    'body' => [
        'name' => $widget_name, 
        'status' => '1', 
        'dataf' => '{"channelNameAsync":"'. $widget_name .'"}']
];

$data_widget = wp_safe_remote_get('https://erp-amz.cloodo.com/v4/widgets/list', $token_data_widget);
$data_widget = current(json_decode(wp_remote_retrieve_body($data_widget), true)['data']);
if (!empty($data_widget)){
    $id = $data_widget['id']; //get id widget 
    $cloodo_company = current(get_option('cloodo_company'))->x_token;
    $token_get_data_widget = [
        'headers' => [
            'Authorization' => 'Bearer ' . get_option('cloodo_token'),
            'X-Worksuite-Company-Token' => $cloodo_company,
        ],
        'body' => [
            'id' => $id
        ]
    ];
    //get details
    $get_data_widget = json_decode(wp_safe_remote_post('https://erp-amz.cloodo.com/v4/widgets/detail', $token_get_data_widget)['body']);
    $code = $get_data_widget->data_widget->code;
    $chat_account = $data_widget = json_decode($get_data_widget->data_widget->data);
}else{
    //create widget
    $data_widget = wp_safe_remote_post('https://erp-amz.cloodo.com/v4/widgets/save', $token_data_widget);
}
    

if (isset($get_data_widget) && !array($get_data_widget)) {
    $get_data_widget = json_decode($get_data_widget)[0]->data_widget;
}

$cloodo_workchat_url_website = isset($chat_account->websiteAsync) ? $chat_account->websiteAsync : $_SERVER['HTTP_HOST'];
$cloodo_workchat_link_messenger = isset($chat_account->fbNameAsync) ? $chat_account->fbNameAsync : "";
$cloodo_workchat_telegram_username = isset($chat_account->telegramAsync) ? $chat_account->telegramAsync : "";
$cloodo_workchat_skype_username = isset($chat_account->skypeAsync) ? $chat_account->skypeAsync : "";
$cloodo_workchat_zalo_username = isset($chat_account->zaloAsync) ? $chat_account->zaloAsync : "";
$cloodo_workchat_whatsapp_username = isset($chat_account->phoneWabaAsync) ? $chat_account->phoneWabaAsync : "";
$cloodo_workchat_slack_username = isset($chat_account->slackAsync) ? $chat_account->slackAsync : "";
$cloodo_workchat_instagram_username = isset($chat_account->instagramAsync) ? $chat_account->instagramAsync : "";
$cloodo_workchat_line_username = isset($chat_account->lineAsync) ? $chat_account->lineAsync : "";

if (isset($_POST['submit_save_chat_setting'])) {
    if (!isset($data_widget)){
        $data_widget = [];
    }
    if (isset($_POST['cloodo_workchat_url_website']) && $_POST['cloodo_workchat_url_website']) { 
        $cloodo_workchat_url_website = sanitize_text_field($_POST['cloodo_workchat_url_website']);
        $data_widget->websiteAsync = sanitize_text_field($_POST['cloodo_workchat_url_website']);
    }
    if (isset($_POST['cloodo_workchat_link_messenger']) && $_POST['cloodo_workchat_link_messenger']) { 
        $cloodo_workchat_link_messenger = sanitize_text_field($_POST['cloodo_workchat_link_messenger']);
        $data_widget->fbNameAsync = sanitize_text_field($_POST['cloodo_workchat_link_messenger']);
    }
    if (isset($_POST['cloodo_workchat_telegram_username']) && $_POST['cloodo_workchat_telegram_username']) { 
        $cloodo_workchat_telegram_username = sanitize_text_field($_POST['cloodo_workchat_telegram_username']);
        $data_widget->telegramAsync = sanitize_text_field($_POST['cloodo_workchat_telegram_username']);
        
    }
    if (isset($_POST['cloodo_workchat_skype_username']) && $_POST['cloodo_workchat_skype_username']) {
        $cloodo_workchat_skype_username = sanitize_text_field($_POST['cloodo_workchat_skype_username']);
        $data_widget->skypeAsync = sanitize_text_field($_POST['cloodo_workchat_skype_username']);
        
    }
    if (isset($_POST['cloodo_workchat_zalo_username']) && $_POST['cloodo_workchat_zalo_username']) {
        $cloodo_workchat_zalo_username = sanitize_text_field($_POST['cloodo_workchat_zalo_username']);
        $data_widget->zaloAsync = sanitize_text_field($_POST['cloodo_workchat_zalo_username']);
    }
    if (isset($_POST['cloodo_workchat_whatsapp_username']) && $_POST['cloodo_workchat_whatsapp_username']) {
        $cloodo_workchat_whatsapp_username = sanitize_text_field($_POST['cloodo_workchat_whatsapp_username']);
        $data_widget->phoneWabaAsync = sanitize_text_field($_POST['cloodo_workchat_whatsapp_username']);
    }
    if (isset($_POST['cloodo_workchat_slack_username']) && $_POST['cloodo_workchat_slack_username']) {
        $cloodo_workchat_slack_username = sanitize_text_field($_POST['cloodo_workchat_slack_username']);
        $data_widget->slackAsync = sanitize_text_field($_POST['cloodo_workchat_slack_username']);
    }
    if (isset($_POST['cloodo_workchat_instagram_username']) && $_POST['cloodo_workchat_instagram_username']) {
        $cloodo_workchat_instagram_username = sanitize_text_field($_POST['cloodo_workchat_instagram_username']);
        $data_widget->instagramAsync = sanitize_text_field($_POST['cloodo_workchat_instagram_username']);
    }
    if (isset($_POST['cloodo_workchat_line_username']) && $_POST['cloodo_workchat_line_username']) {
        $cloodo_workchat_line_username = sanitize_text_field($_POST['cloodo_workchat_line_username']);
        $data_widget->lineAsync = sanitize_text_field($_POST['cloodo_workchat_line_username']);
    }

    $dataf = trim(json_encode($data_widget));
    $token_update_data_widget = [
        'headers' => ['Authorization' => 'Bearer ' . get_option('cloodo_token')],
        'body' => [
            'name' => $company_name,
            'dataf' => $dataf,
            'status' => 1,
            'id' => $id,
        ]
    ];
    $update = wp_safe_remote_post('https://erp-amz.cloodo.com/v4/widgets/save', $token_update_data_widget);
}

if ($token && $token != null) {
?>
    <!-- Welcome setting -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-1"><span class="text-muted fw-light"><?php echo esc_html("WorkChat/")?></span><?php esc_html_e( " Setting", "workchat");?></h4>
        <div class="card-content">
            <div class="card-body mb-2 pt-5 text-primary text-bold" style="text-align: left; font-size: 40px; ">
                <?php esc_html_e( "Chat widget setting for WorkChat", "workchat" )?>
            </div>
            <div class="card-body" style="font-size: 20px;">
                <?php esc_html_e("Please select and enter the utilities you want to activate to display on the client side.", "workchat")?>
            </div>
        </div>
    </div>
    <!--/ Welcome setting -->

    <!-- show font-end setting -->
    <div class=" container-xxl flex-grow-1 container-p-y">

        <div class="row">
            <div class="col-xxl">
                <div class="bg-white rounde dshadow card mb-4" style="max-width: 100%;">
                    <!-- Title -->
                    <div class="mb-4 card-header d-flex align-items-center justify-content-between">
                        <h3 class=""><?php esc_html_e("Setup chat channels","workchat" )?></h5>
                            <small class="text-muted float-end"><?php esc_html_e("Enable and adjust your chat channels to suit your needs.", "workchat" );?></small>
                    </div>
                    <!--/ Title -->

                    <div class="card-body">
                        <!-- Form setting -->
                        <form method="post">
                            <!-- Website URL -->
                            <div class="row mb-4">
                                <label class="col-sm-2 col-form-label" for="basic-icon-default-websiteurl"><?php esc_html_e("Website URL","workchat" );?></label>
                                <div class="col-sm-10">
                                    <div class="input-group input-group-merge ">
                                        <span id="basic-icon-default-websiteurl2" class="input-group-text">
                                            <img src="<?php echo esc_url(plugins_url('../admin/images/chat_setting/icon_url.svg', __FILE__)); ?>" alt="icon_websiteurl">
                                        </span>
                                        <input type="text" class="form-control" id="basic-icon-default-websiteurl" placeholder="Website Url" aria-label="website Url" aria-describedby="basic-icon-default-messenger2" value="<?php echo esc_attr($cloodo_workchat_url_website); ?>" name="cloodo_workchat_url_website" />
                                    </div>
                                    <div class="form-text"><?php esc_html_e("For example: with the website link ", "workchat");?><b class="">
                                            <?php echo esc_html("https://worksuite.cloodo.com");?></b>
                                        <?php esc_html_e(", you fill in the box as ", "workchat");?><b class=""><?php esc_html_e( "url","workchat" )?></b>.</div>
                                </div>
                            </div>
                            <!--/ Website URL -->

                            <!-- Messenger -->
                            <div class="row mb-4">
                                <label class="col-sm-2 col-form-label" for="basic-icon-default-messenger"><?php esc_html_e("Messenger","workchat" );?></label>
                                <div class="col-sm-10">
                                    <div class="input-group input-group-merge ">
                                        <span id="basic-icon-default-messenger2" class="input-group-text">
                                            <img src="<?php echo esc_url(plugins_url('../admin/images/chat_setting/icon_messenger.svg', __FILE__)); ?>" alt="icon_messenger">
                                        </span>
                                        <input type="text" class="form-control" id="basic-icon-default-messenger" placeholder="Facebook username" aria-label="facebook username" aria-describedby="basic-icon-default-messenger2" value="<?php echo esc_attr($cloodo_workchat_link_messenger); ?>" name="cloodo_workchat_link_messenger" />
                                    </div>
                                    <div class="form-text"><?php esc_html_e("For example: with the face link ", "workchat");?><b class="">
                                            <?php echo esc_html("https://www.facebook.com/username");?></b>
                                        <?php esc_html_e(", you fill in the box as ", "workchat");?><b class=""><?php esc_html_e( "username","workchat" )?></b>.</div>
                                </div>
                            </div>
                            <!--/ Messenger -->

                            <!-- Telegram -->
                            <div class="row mb-4">
                                <label class="col-sm-2 col-form-label" for="basic-icon-default-telegram"><?php esc_html_e("Telegram","workchat" );?></label>
                                <div class="col-sm-10">
                                    <div class="input-group input-group-merge">
                                        <span id="basic-icon-default-telegram2" class="input-group-text">
                                            <img src="<?php echo esc_url(plugins_url('../admin/images/chat_setting/icon_telegram.svg', __FILE__)); ?>" alt="icon_telegram">
                                        </span>
                                        <input type="text" id="basic-icon-default-telegram" class="form-control" placeholder="Telegram username" aria-label="telegram username" aria-describedby="basic-icon-default-telegram2" value="<?php echo esc_attr($cloodo_workchat_telegram_username); ?>" name="cloodo_workchat_telegram_username" />
                                    </div>
                                    <div class="form-text"><?php esc_html_e( "For example: with the telegram link ", "workchat" );?><b class="">
                                            <?php echo esc_html("https://telegram.me/username");?></b>
                                        <?php esc_html_e(", you fill in the box as ", "workchat");?><b class=""><?php esc_html_e( "username", "workchat" );?></b>.</div>
                                </div>
                            </div>
                            <!--/ Telegram -->

                            <!-- Skype -->
                            <div class="row mb-4">
                                <label class="col-sm-2 col-form-label" for="basic-icon-default-skype"><?php esc_html_e( "Skype", "workchat" );?></label>
                                <div class="col-sm-10">
                                    <div class="input-group input-group-merge">
                                        <span id="basic-icon-default-skype2" class="input-group-text">
                                            <img src="<?php echo esc_url(plugins_url('../admin/images/chat_setting/icon_skype.svg', __FILE__)); ?>" alt="icon_skype">
                                        </span>
                                        <input type="text" id="basic-icon-default-skype" class="form-control" placeholder="Skype name" aria-label="skype name" aria-describedby="basic-icon-default-skype2" value="<?php echo esc_attr($cloodo_workchat_skype_username); ?>" name="cloodo_workchat_skype_username" />
                                    </div>
                                    <div class="form-text"><?php esc_html_e( "For example: with the skype link ", "workchat" );?><b class="">
                                            <?php esc_html_e( "skype:live:example", "workchat" );?></b>
                                            <?php esc_html_e(", you fill in the box as ", "workchat");?><b class=""><?php esc_html_e( "live:example", "workchat" );?></b>.</div>
                                </div>
                            </div>
                            <!--/ Skype -->

                            <!-- Zalo -->
                            <div class="row mb-4">
                                <label class="col-sm-2 col-form-label" for="basic-icon-default-zalo"><?php esc_html_e("Zalo", "workchat");?></label>
                                <div class="col-sm-10">
                                    <div class="input-group input-group-merge">
                                        <span id="basic-icon-default-zalo2" class="input-group-text">
                                            <img src="<?php echo esc_url(plugins_url('../admin/images/chat_setting/icon_zalo.svg', __FILE__)); ?>" alt="icon_messenger">
                                        </span>
                                        <input type="text" id="basic-icon-default-zalo" class="form-control" placeholder="+84 12 345 6789" aria-label="zalo username" aria-describedby="basic-icon-default-zalo2" value="<?php echo esc_attr($cloodo_workchat_zalo_username); ?>" name="cloodo_workchat_zalo_username" />

                                    </div>
                                    <div class="form-text"><?php esc_html_e( "For example: with the zalo link ","workchat");?><b class="">
                                           <?php echo esc_html(" https://zalo.me/0123456789");?></b>
                                        <?php esc_html_e(", you fill in the box as ", "workchat");?><b class=""><?php esc_html_e("0123456789", "workchat");?></b>.</div>
                                </div>
                            </div>
                            <!--/ zalo -->

                            <!-- whatsapp -->
                            <div class="row mb-4">
                                <label class="col-sm-2 col-form-label" for="basic-icon-default-whatsapp"><?php esc_html_e( "WhatsApp", "workchat");?></label>
                                <div class="col-sm-10">
                                    <div class="input-group input-group-merge">
                                        <span id="basic-icon-default-whatsapp2" class="input-group-text">
                                            <img src="<?php echo esc_url(plugins_url('../admin/images/chat_setting/icon_whatsapp.svg', __FILE__)); ?>" alt="icon_messenger">
                                        </span>
                                        <input type="text" id="basic-icon-default-whatsapp" class="form-control" placeholder="+84 12 345 6789" aria-label="whatsapp username" aria-describedby="basic-icon-default-whatsapp2" value="<?php echo esc_attr($cloodo_workchat_whatsapp_username); ?>" name="cloodo_workchat_whatsapp_username" />
                                    </div>
                                    <div class="form-text"><?php esc_html_e("For example: with the whatsapp link ", "workchat")?><b class="">
                                            <?php echo esc_html("https://wa.me/0123456789");?></b>
                                       <?php esc_html_e( " , you fill in the box as ","workchat" );?><b class=""><?php esc_html_e("0123456789", "workchat");?></b>.</div>
                                </div>
                            </div>
                            <!--/ whatsapp -->

                            <!-- Slack -->
                            <div class="row mb-4">
                                <label class="col-sm-2 col-form-label" for="basic-icon-default-slack">Slack</label>
                                <div class="col-sm-10">
                                    <div class="input-group input-group-merge">
                                        <span id="basic-icon-default-slack2" class="input-group-text">
                                            <img src="<?php echo esc_url(plugins_url('../admin/images/chat_setting/icon_slack.svg', __FILE__)); ?>" alt="icon_slack">
                                        </span>
                                        <input type="text" id="basic-icon-default-slack" class="form-control" placeholder="https://username-workspace.slack.com" aria-label="slack name" aria-describedby="basic-icon-default-slack2" value="<?php echo esc_attr($cloodo_workchat_slack_username); ?>" name="cloodo_workchat_slack_username" />
                                    </div>
                                    <div class="form-text"><?php esc_html_e("For example: to fill in the box with the Slack ", "workchat")?>
                                    <b class=""><?php echo esc_html(" https://username-workspace.slack.com/");?></b>
                                        <?php esc_html_e(", you would enter it as ","workchat");?><b class=""><?php echo esc_html( " https://username-workspace.slack.com" );?></b>.</div>
                                </div>
                            </div>
                            <!--/ Slack -->

                            <!-- Instagram -->
                            <div class="row mb-4">
                                <label class="col-sm-2 col-form-label" for="basic-icon-default-instagram"><?php esc_html_e("Instagram", "workchat");?></label>
                                <div class="col-sm-10">
                                    <div class="input-group input-group-merge">
                                        <span id="basic-icon-default-instagram2" class="input-group-text">
                                            <img src="<?php echo esc_url(plugins_url('../admin/images/chat_setting/icon_instagram.svg', __FILE__)); ?>" alt="icon_instagram">
                                        </span>
                                        <input type="text" id="basic-icon-default-instagram" class="form-control" placeholder="Instagram Name" aria-label="instagram name" aria-describedby="basic-icon-default-instagram2" value="<?php echo esc_attr($cloodo_workchat_instagram_username); ?>" name="cloodo_workchat_instagram_username" />
                                    </div>
                                    <div class="form-text"><?php esc_html_e("For example: with the instagram link ", "workchat");?><b class="">
                                            <?php echo esc_html("https://www.instagram.com/username");?></b>
                                        <?php esc_html_e(", you fill in the box as ", "workchat");?><b class=""><?php esc_html_e( "username", "workchat");?></b>.</div>
                                </div>
                            </div>
                            <!--/ Instagram -->

                            <!-- Line -->
                            <div class="row mb-4">
                                <label class="col-sm-2 col-form-label" for="basic-icon-default-line"><?php esc_html_e( "Line", "workchat");?></label>
                                <div class="col-sm-10">
                                    <div class="input-group input-group-merge">
                                        <span id="basic-icon-default-line2" class="input-group-text">
                                            <img src="<?php echo esc_url(plugins_url('../admin/images/chat_setting/icon_line.svg', __FILE__)); ?>" alt="icon_line">
                                        </span>
                                        <input type="text" id="basic-icon-default-line" class="form-control" placeholder="Code Line" aria-label="line name" aria-describedby="basic-icon-default-line2" value="<?php echo esc_attr($cloodo_workchat_line_username); ?>" name="cloodo_workchat_line_username" />
                                    </div>

                                    <div class="form-text"><?php esc_html_e("For example: with the line link ", "workchat" );?>
                                    <b class=""><?php  echo esc_html( " http://line.me/ti/p/code" )?></b> , <?php esc_html_e("you fill in the box as ","workchat");?>
                                    <b class=""><?php esc_html_e( "code", "workchat");?></b>.</div>
                                </div>
                            </div>
                            <!--/ Line -->



                            <!-- Submit -->
                            <div class="row justify-content-end mt-4">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary" name="submit_save_chat_setting"><?php esc_html_e("Save setting", "workchat");?></button>
                                </div>
                            </div>
                            <!--/ Submit -->
                        </form>
                        <!--/ Form setting -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ show font-end setting -->
<?php }
