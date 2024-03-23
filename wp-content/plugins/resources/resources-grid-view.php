<?php
/*
Plugin Name: Resources Grid View
Plugin URI: https://suhail.in/resources-grid-view
Description: Display posts.
Version: 1.0.0
Author: Mohammad Suhail
Author URI: https://suhail.in
License: GPL2
*/


function resources_grid_view_activate() {
    
    // Register Custom Post Types
    $blog_args = array(
        'labels' => array(
            'name' => 'Blogs',
            'singular_name' => 'Blog',
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
    );
    register_post_type('blog', $blog_args);

    $ebook_args = array(
        'labels' => array(
            'name' => 'Ebooks',
            'singular_name' => 'Ebook',
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
    );
    register_post_type('ebook', $ebook_args);

    $case_study_args = array(
        'labels' => array(
            'name' => 'Case Studies',
            'singular_name' => 'Case Study',
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
    );
    register_post_type('case_study', $case_study_args);

    $category_args = array(
        'labels' => array(
            'name' => 'Categories',
            'singular_name' => 'Category',
        ),
        'hierarchical' => true,
        'show_admin_column' => true,
    );
    register_taxonomy('resource_category', array('blog', 'ebook', 'case_study'), $category_args);
}
  

add_action('init', 'resources_grid_view_activate');
add_action('admin_menu', 'resources_grid_view_admin_menu');

function resources_grid_view_admin_menu() {
    $parent_slug = 'resources';
    $resources_page = add_menu_page('WP Resources', 'WP Resources', 'manage_options', $parent_slug, 'resources_grid_view_resources_page', 'dashicons-admin-post', 26);
    add_submenu_page($parent_slug, 'All Resources', 'All Resources', 'manage_options', 'resources_grid_view_all_resources', 'resources_grid_view_all_resources_page');
    add_submenu_page($parent_slug, 'Add New', 'Add New', 'manage_options', 'resources_grid_view_add_new', 'resources_grid_view_add_new_page');
    add_submenu_page($parent_slug, 'Categories', 'Categories', 'manage_options', 'resources_grid_view_categories', 'resources_grid_view_categories_page');

    // Load admin scripts and styles
    add_action('admin_print_styles-' . $resources_page, 'resources_grid_view_admin_styles');
    add_action('admin_print_scripts-' . $resources_page, 'resources_grid_view_admin_scripts');
}

function resources_grid_view_all_resources_page() {
    // Retrieve all resources
    $resources = get_posts(array(
        'post_type' => array('blog', 'ebook', 'case_study'),
        'posts_per_page' => -1,
    ));

    // Render the table
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">All Resources</h1>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Categories</th>
                    <th>Shortcode</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resources as $resource) : ?>
                    <tr>
                        <td><?php echo $resource->post_title; ?></td>
                        <td><?php echo implode(', ', wp_get_post_terms($resource->ID, 'resource_category', array('fields' => 'names'))); ?></td>
                        <td>[resources_grid_view post_id=<?php echo $resource->ID; ?>]</td>
                        <td><?php echo $resource->post_date; ?></td>
                        <td>
                            <a href="<?php echo get_edit_post_link($resource->ID); ?>">Edit</a>
                            <a href="<?php echo get_delete_post_link($resource->ID); ?>">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}
function resources_grid_view_add_new_page() {
    // Add new resource form
    ?>
    <div class="wrap">
        <h1>Add New Resource</h1>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="resources_grid_view_add_new_resource">
            <?php wp_nonce_field('resources_grid_view_add_new_resource', 'resources_grid_view_nonce'); ?>

            <table class="form-table">
                <tr>
                    <th scope="row">Post Type</th>
                    <td>
                        <label><input type="checkbox" name="post_types[]" value="blog"> Add Blogs</label><br>
                        <label><input type="checkbox" name="post_types[]" value="ebook"> Add Ebooks</label><br>
                        <label><input type="checkbox" name="post_types[]" value="case_study"> Add Case Studies</label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Categories</th>
                    <td>
                        <?php
                        $categories = get_terms(array(
                            'taxonomy' => 'resource_category',
                            'hide_empty' => false,
                        ));
                        foreach ($categories as $category) {
                            echo '<label><input type="checkbox" name="categories[]" value="' . $category->term_id . '"> ' . $category->name . '</label><br>';
                        }
                        ?>
                    </td>
                </tr>
            </table>

            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Add Resource"></p>
        </form>
    </div>
    <?php
}
function resources_grid_view_categories_page() {
    // Retrieve all categories
    $categories = get_terms(array(
        'taxonomy' => 'resource_category',
        'hide_empty' => false,
    ));

    // Render the categories table
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Categories</h1>
        <a href="#" class="page-title-action">Add New Category</a>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category) : ?>
                    <tr>
                        <td><?php echo $category->name; ?></td>
                        <td><?php echo $category->slug; ?></td>
                        <td><?php echo $category->count; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Add New Category Form -->
        <h2>Add New Category</h2>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="resources_grid_view_add_new_category">
            <?php wp_nonce_field('resources_grid_view_add_new_category', 'resources_grid_view_nonce'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="category_name">Name</label></th>
                    <td><input type="text" name="category_name" id="category_name" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="category_slug">Slug</label></th>
                    <td><input type="text" name="category_slug" id="category_slug" class="regular-text"></td>
                </tr>
            </table>
            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Add Category"></p>
        </form>
    </div>
    <?php
}

add_action('admin_menu', 'resources_grid_view_settings_page');

function resources_grid_view_settings_page() {
    add_options_page(
        'Resources Grid View Settings',
        'Resources Settings',
        'manage_options',
        'resources-grid-view-settings',
        'resources_grid_view_settings_page_callback'
    );
}

function resources_grid_view_settings_page_callback() {
    ?>
    <div class="wrap">
        <h1>Resources Grid View Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('resources_grid_view_settings_group');
            do_settings_sections('resources-grid-view-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

add_action('admin_init', 'resources_grid_view_settings_init');

function resources_grid_view_settings_init() {
    register_setting(
        'resources_grid_view_settings_group',
        'resources_grid_view_settings',
        'resources_grid_view_settings_validate'
    );

    add_settings_section(
        'resources_grid_view_settings_section',
        'General Settings',
        'resources_grid_view_settings_section_callback',
        'resources-grid-view-settings'
    );

    add_settings_field(
        'resources_grid_view_layout',
        'Layout',
        'resources_grid_view_layout_callback',
        'resources-grid-view-settings',
        'resources_grid_view_settings_section'
    );

    add_settings_field(
        'resources_grid_view_post_management',
        'Post Management',
        'resources_grid_view_post_management_callback',
        'resources-grid-view-settings',
        'resources_grid_view_settings_section'
    );
}

function resources_grid_view_settings_section_callback() {
    echo '<p>Configure the general settings for the Resources Grid View plugin.</p>';
}

function resources_grid_view_layout_callback() {
    $options = get_option('resources_grid_view_settings');
    $layout = isset($options['layout']) ? $options['layout'] : 'grid';
    ?>
    <select name="resources_grid_view_settings[layout]">
        <option value="grid" <?php selected($layout, 'grid'); ?>>Grid</option>
        <option value="list" <?php selected($layout, 'list'); ?>>List</option>
    </select>
    <?php
}

function resources_grid_view_post_management_callback() {
    $options = get_option('resources_grid_view_settings');
    $post_management = isset($options['post_management']) ? $options['post_management'] : 'all';
    ?>
    <select name="resources_grid_view_settings[post_management]">
        <option value="all" <?php selected($post_management, 'all'); ?>>Show All Posts</option>
        <option value="selected" <?php selected($post_management, 'selected'); ?>>Show Selected Posts</option>
    </select>
    <?php
}

function resources_grid_view_settings_validate($input) {
    $valid = array();

    $valid['layout'] = (isset($input['layout']) && in_array($input['layout'], array('grid', 'list'))) ? $input['layout'] : 'grid';
    $valid['post_management'] = (isset($input['post_management']) && in_array($input['post_management'], array('all', 'selected'))) ? $input['post_management'] : 'all';

    return $valid;
}
function resources_grid_view_admin_styles() {
    wp_enqueue_style('resources-grid-view-admin', plugins_url('assets/css/admin.css', __FILE__));
}

function resources_grid_view_admin_scripts() {
    wp_enqueue_script('resources-grid-view-admin', plugins_url('assets/js/admin.js', __FILE__), array('jquery'), '1.0.0', true);
}
function resources_grid_view_enqueue_styles() {
    wp_enqueue_style('bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css', array(), '4.5.2');
    wp_enqueue_style('resources-grid-view', plugins_url('assets/css/style.css', __FILE__), array('bootstrap'), '1.0.0');
}
add_action('wp_enqueue_scripts', 'resources_grid_view_enqueue_styles');

function resources_grid_view_enqueue_scripts() {
    wp_enqueue_script('bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js', array('jquery'), '4.5.2', true);
    wp_enqueue_script('resources-grid-view', plugins_url('assets/js/script.js', __FILE__), array('bootstrap'), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'resources_grid_view_enqueue_scripts');




add_action('admin_menu', 'resources_grid_view_settings_page');

function resources_grid_view_add_new_resource_callback() {
    // Check nonce
 if (!isset($_POST['resources_grid_view_nonce']) || !wp_verify_nonce($_POST['resources_grid_view_nonce'], 'resources_grid_view_add_new_resource')) {
        wp_die('Invalid nonce');
    }

    // Get form data
    $post_types = isset($_POST['post_types']) ? $_POST['post_types'] : array();
    $categories = isset($_POST['categories']) ? $_POST['categories'] : array();

    // Loop through post types and create posts
    foreach ($post_types as $post_type) {
        $post_id = wp_insert_post(array(
            'post_title' => 'New ' . ucfirst($post_type),
            'post_type' => $post_type,
            'post_status' => 'publish',
        ));

        // Assign categories
        wp_set_object_terms($post_id, $categories, 'resource_category');
    }

}


    // Check nonce
 function resources_grid_view_add_new_category_callback() {
    // Check nonce
    if (!isset($_POST['resources_grid_view_nonce']) || !wp_verify_nonce($_POST['resources_grid_view_nonce'], 'resources_grid_view_add_new_category')) {
        wp_die('Invalid nonce');
    }

    // Get form data
    $category_name = isset($_POST['category_name']) ? sanitize_text_field($_POST['category_name']) : '';
    $category_slug = isset($_POST['category_slug']) ? sanitize_title($_POST['category_slug']) : '';

    // Insert new category
    $term = wp_insert_term($category_name, 'resource_category', array(
        'slug' => $category_slug,
    ));

    if (is_wp_error($term)) {
        wp_die($term->get_error_message());
    }

    // Redirect to the Categories page
    wp_redirect(admin_url('admin.php?page=resources_grid_view_categories'));
    exit;
}
   
function resources_grid_view_shortcode($atts) {

    function resources_grid_view_shortcode($atts) {
    // Debug code
    echo ('resources_grid_view_shortcode called');
    $output = 'Test output';

    // Your existing shortcode code here
    // ...

    return $output;
}
    $atts = shortcode_atts(array(
        'post_types' => 'blog,ebook,case_study',
        'categories' => '',
        'layout' => 'grid',
    ), $atts, 'resources_grid_view');

    $post_types = array_map('trim', explode(',', $atts['post_types']));
    $categories = array_map('trim', explode(',', $atts['categories']));

    $args = array(
        'post_type' => $post_types,
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'resource_category',
                'field' => 'term_id',
                'terms' => $categories,
            ),
        ),
    );

    $resources = new WP_Query($args);

    ob_start();
    ?>
    <div class="resources-grid-view">
        <?php if ($atts['layout'] === 'grid') : ?>
            <div class="row">
                <?php while ($resources->have_posts()) : $resources->the_post(); ?>
                    <div class="col-md-4 col-sm-6 mb-4">
                        <div class="card h-100">
                            <?php if (has_post_thumbnail()) : ?>
                                <img src="<?php the_post_thumbnail_url('medium'); ?>" class="card-img-top" alt="<?php the_title(); ?>">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php the_title(); ?></h5>
                                <p class="card-text"><?php the_excerpt(); ?></p>
                                <a href="<?php the_permalink(); ?>" class="btn btn-primary">Read More</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else : ?>
            <ul class="list-group">
                <?php while ($resources->have_posts()) : $resources->the_post(); ?>
                    <li class="list-group-item">
                        <div class="media">
                            <?php if (has_post_thumbnail()) : ?>
                                <img src="<?php the_post_thumbnail_url('thumbnail'); ?>" class="mr-3" alt="<?php the_title(); ?>">
                            <?php endif; ?>
                            <div class="media-body">
                                <h5 class="mt-0"><?php the_title(); ?></h5>
                                <p><?php the_excerpt(); ?></p>
                                <a href="<?php the_permalink(); ?>" class="btn btn-primary">Read More</a>
                            </div>
                        </div>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php endif; ?>
    </div>
    <?php
    wp_reset_postdata();

    return ob_get_clean();
}
add_shortcode('resources_grid_view', 'resources_grid_view_shortcode');