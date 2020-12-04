<?php 

add_action('widgets_init', 'petition_widgets_areas');
/**
 * Register our sidebars, widgetized areas and widgets.
 *
 */
function petition_widgets_areas() {
	register_sidebar(array(
		'name'          => 'Petition Sidebar',
		'id'            => 'petition_plugin_sidebar',
		'before_widget' => '<div class="petition-plugin-sidebar">',
		'after_widget'  => '</div>',
		'before_title' => '<!--',
		'after_title' => '-->',
	));
}