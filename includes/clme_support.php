<?php
wp_enqueue_style('bootstrap', plugins_url('../admin/css/bootstrap.min.css', __FILE__));
wp_enqueue_style('theme-default.css', plugins_url('../admin/css/theme-default.css', __FILE__));
wp_enqueue_style('style_overtext.css', plugins_url('../admin/css/style_overtext.css', __FILE__));

$token = get_option('cloodo_token');

// api tutorial
$support_service_cloodo = wp_remote_get(
    'https://data2.cloodo.com/api/services?filters[service_agency][$containsi]=%22Cloodo.com%22&populate=*&sort=createdAt:DESC&pagination[pageSize]=100'
);

if ($token && $token != null) {
    $support_service_cloodo_data = json_decode(wp_remote_retrieve_body($support_service_cloodo), true)['data'];
    ?>

    <!-- Welcome support -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-1"><span class="text-muted fw-light"><?php esc_html_e( "WorkChat","workchat" );?>/</span><?php esc_html_e( " Support", "workchat");?></h4>
        <div class="card-content">
            <div class="card-body mb-2 text-primary pt-5" style="text-align: left; font-size: 40px; "><?php esc_html_e("Support service for WorkChat", "workchat");?></div>
            <div class="card-body" style="font-size: 20px;"><?php esc_html_e("Please choose service support", "workchat");?></div>
        </div>
    </div>
    <!--/ Welcome support -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row" style="border-radius: 0px;">
            <div class="row mb-5">
                <?php
                foreach ($support_service_cloodo_data as $service) {
                    $image_url = $service['attributes']['image']['data']['0']['attributes']['url'];
                    $title = $service['attributes']['title'];
                    $description = $service['attributes']['description'];
                    $alias = $service['attributes']['alias']; ?>
                    <!-- Support -->
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="card h-100">
                            <img class="card-img-top mt-3" src="<?php echo esc_url($image_url); ?>" alt="card image cap" />
                            <div class="card-body">
                                <h5 class="card-title">
                                    <?php echo esc_html($title); ?>
                                </h5>
                                <div class="card-text mb-3 over_text">
                                    <?php echo wp_kses_post($description); ?>
                                </div>
                                <a href="<?php echo esc_url("https://cloodo.com/service/" . $alias); ?>" class="btn btn-outline-primary"
                                    target="_blank"><?php esc_html_e( "Get support", "workchat");?></a>
                            </div>
                        </div>
                    </div>
                    <!-- Support -->
                <?php }
} ?>
        </div>
    </div>
</div>