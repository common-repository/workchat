<?php
wp_enqueue_style('bootstrap', plugins_url('../admin/css/bootstrap.min.css', __FILE__));
wp_enqueue_style('theme-default.css', plugins_url('../admin/css/theme-default.css', __FILE__));
wp_enqueue_style('style_overtext.css', plugins_url('../admin/css/style_overtext.css', __FILE__));

$author_token = array(
    'headers' => ['Authorization' => 'Bearer' . ' ' . get_option('cloodo_token')]
);


// $cloodo_company = current(get_option('cloodo_company'))->x_token;
// $token_get_data_widget = [
//     'headers' => [
//         'Authorization' => 'Bearer ' . get_option('cloodo_token'),
//         'X-Worksuite-Company-Token' => $cloodo_company,
//     ],
// ];
// $response = wp_remote_get("https://convert.cloodo.com/api/statistical-workchat", $cloodo_company);
// echo $cloodo_company;
// echo get_option('cloodo_token');
add_action('wp_footer', 'clme_icon_box');

$token = get_option('cloodo_token');

if (!$token) {
    $user_id = get_current_user_id();
    $user_data = get_userdata($user_id);
    $user_login = sanitize_text_field($user_data->user_login);
?>
    <!-- show register form -->
    <div class="container mt-5">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-center h-px-500 ">
                <form class="w-px-600 border rounded p-3 p-md-5 border border-primary alert  alert-dismissible " method="POST">
                    <div></div>
                    <img class="" src="<?php echo esc_url(plugins_url('../admin/images/icons8-messaging-50.png', __FILE__)); ?>" alt="WorkChat_Image">
                    <div class="card bg-primary text-white mb-3">
                        <h4 class="title mt-3 card-title text-white">
                            <?php esc_html_e("Hello: ", "workchat"); ?><b>
                                <?php echo esc_html($user_login); ?>
                            </b>, <?php esc_html_e("Welcome to Work Chat", "workchat") ?>
                        </h4>
                    </div>

                    <div class="mb-3" role="">
                        <p class="mb-0" style="text-align: justify; text-justify: inter-word;">
                            <?php esc_html_e("Hello! Would you like to join our Work Chat community to connect with millions of users
                            worldwide? To get started, you need to register for an account. Please click on the \"Register\"
                            button below to begin the registration process for a", "workchat"); ?>
                            <b><?php esc_html_e(" Cloodo ", "workchat"); ?></b>
                            <?php esc_html_e("Once you have completed the
                            registration, you will have access to many useful features on our application. Thank you for
                            your interest in and registration for using Work Chat!", "workchat"); ?>
                        </p>
                    </div>
                    <div class="col-12 d-flex justify-content-between">
                        <div></div>
                        <button type="submit" class="btn rounded-pill btn-primary waves-effect waves-light mt-3" name="cloodo_register">
                            <?php esc_html_e("Register", "workchat"); ?>
                        </button>
                    </div>
                    <?php cloodo_register_form_nonce(); ?>
                </form>
            </div>
        </div>
    </div>
    <!--/ show register form -->
<?php
}

if ($token && $token != null) {
    // api with total
    $api_endpoints = array(
        'agent_v2' => 'https://erp.cloodo.com/api/v2/ticket-agents?limit',
        'client_v1' => 'https://erp.cloodo.com/api/v1/client',
    );

    $data_totals = array();

    foreach ($api_endpoints as $key => $value) {
        $endpoint_version = substr($key, -3);
        $response = wp_remote_get($value, $author_token);
        $response_body = wp_remote_retrieve_body($response);
        $response_data = json_decode($response_body, true);

        if ($response_data && is_array($response_data)) {
            if ($endpoint_version === '_v1') {
                $data_totals[substr($key, 0, -3)] = isset($response_data['meta']) && isset($response_data['meta']['paging']['total']) ? $response_data['meta']['paging']['total'] : 0;
            } elseif ($endpoint_version === '_v2') {
                $data_totals[substr($key, 0, -3)] = isset($response_data['meta']) && isset($response_data['meta']['total']) ? $response_data['meta']['total'] : 0;
            }
        }
    }

    $agent_total = isset($data_totals['agent']) ? $data_totals['agent'] : 0;
    $clients_total = isset($data_subset['client']) ? $data_subset['client'] : 0;

?>

    <!-- Dashboard -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-1"><span class="text-muted fw-light"><?php echo esc_html("WorkChat/"); ?></span> <?php esc_html_e("Dashboard", "workchat"); ?></h4>
        <div class="row" style="border-radius: 0px;">
            <!-- Congratulation -->
            <div class="card-header px-4" style="background-color: white;">
                <div class="card-body mb-3 text-primary pt-5" style="text-align: left; font-size: 40px; ">
                    <?php esc_html_e("Welcome to WorkChat", "workchat"); ?></div>
                <div class="card-body mb-4" style="font-size: 20px;">
                    <?php esc_html_e("Congratulations, you have successfully connected to WorkChat as", "workchat"); ?>
                    <span style="font-weight: bold;">
                        <?php echo esc_html(wp_get_current_user()->user_login); ?>
                    </span>
                    <?php esc_html_e("by email", "workchat"); ?>
                    <span style="font-weight: bold;">
                        <?php echo esc_html(get_option('admin_email')); ?>
                    </span>
                    <?php esc_html_e(". Your client can start a chat with", "workchat"); ?>
                    <span style="font-weight: bold;"> (
                        <?php echo esc_html(get_option('admin_email')); ?>)
                    </span>
                </div>
            </div>
            <!--/ Congratulation -->


            <div class="col-4">
                <div class="p-3">
                    <div class="p-3 pr-6 bg-white shadow rounded">
                        <h5 class="title">Total Contact
                        </h5>
                        <div class="d-flex align-items-center justify-content-between py-2">
                            <span class="flex-1 fs-4 fw-medium count">0</span>
                            <div class="rounded-circle ">
                                <svg class="p-2 dashboard-icon rounded-circle " xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-4">
                <div class="p-3">
                    <div class="p-3 pr-6 bg-white shadow rounded">
                        <h5 class="title">Total files storage
                        </h5>
                        <div class="d-flex align-items-center justify-content-between py-2">
                            <span class="flex-1 fs-4 fw-medium count">0</span>
                            <div class="rounded-circle ">
                                <svg class="p-2 dashboard-icon rounded-circle " xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-4">
                <div class="p-3">
                    <div class="p-3 pr-6 bg-white shadow rounded">
                        <h5 class="title">Total mesages
                        </h5>
                        <div class="d-flex align-items-center justify-content-between py-2">
                            <span class="flex-1 fs-4 fw-medium count">0</span>
                            <div class="rounded-circle ">
                                <svg class="p-2 dashboard-icon rounded-circle " xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>
    <!--/ Dashboard -->
<?php
}
