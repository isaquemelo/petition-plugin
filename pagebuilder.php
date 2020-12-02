<?php 

/** Add SiteOrigin Page Builder custom widgets */
add_filter('siteorigin_widgets_widget_folders', function($folders) {
    $folders[] = __DIR__ . '/pagebuilder-widgets/';
    return $folders;
});
