<?php

// Now you can use Dompdf functionalities in your theme or plugin files

/**
 * Astra Child Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Astra Child
 * @since 1.0.0
 */

/**
 * Define Constants
 */
define( 'CHILD_THEME_ASTRA_CHILD_VERSION', '1.0.0' );

/**
 * Enqueue styles
 */
//***************************************Code For Jscdn***************************************************************** */



function enqueue_jspdf_script() {
    // Register jsPDF script
    wp_register_script('jspdf', 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js', array(), '2.4.0', true);

    // Enqueue jsPDF script
    wp_enqueue_script('jspdf');
}
add_action('wp_enqueue_scripts', 'enqueue_jspdf_script');


// End



// Enque font awesom icons//
function add_font_awesome() {
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
}
add_action('wp_enqueue_scripts', 'add_font_awesome');

//***********************************************End*************************************************************************//

// Enqueue FileSaver.js library
// function enqueue_filesaver() {
//     wp_enqueue_script('filesaver', 'https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js', array(), '2.0.5', true);
// }
// add_action('wp_enqueue_scripts', 'enqueue_filesaver');




function child_enqueue_styles() {

	wp_enqueue_style( 'astra-child-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'), CHILD_THEME_ASTRA_CHILD_VERSION, 'all' );
    // Add Dompdf autoload from the child theme directory



}

add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 15 );





// ********************************** Get post data ajax code Start ********************************************//



add_action('wp_ajax_get_single_and_related_posts', 'get_single_and_related_posts');
add_action('wp_ajax_nopriv_get_single_and_related_posts', 'get_single_and_related_posts');

function get_single_and_related_posts() {
    $category_id = $_POST['category_id'];

    // Get single post
    $args_single = array(
        'posts_per_page' => 1,
        'cat' => $category_id,
    );

    $single_post_query = new WP_Query($args_single);
    $single_post = '';

    if ($single_post_query->have_posts()) {
        while ($single_post_query->have_posts()) {
            $single_post_query->the_post();
          


        // Check if the post has a featured image
         if (has_post_thumbnail()) {
         $single_post .= '<div class="featured-image">' . get_the_post_thumbnail(get_the_ID(), 'large') . '</div>';
                        }

            $single_post .= '<h2>' . get_the_title() . '</h2>';
            // $single_post .= '<p>' . get_the_content() . '</p>';
            $single_post .= '<p>' . get_the_excerpt() . '</p>'; // Display the excerpt instead of full content
            $single_post .= '<a href="' . get_the_permalink() . '" class="read-more">Read More</a>'; // Read more link
            

        }
    }

    wp_reset_postdata();

    // Get related posts
    $args_related = array(
        'posts_per_page' => 5, // Change this to the number of related posts you want to display
        'cat' => $category_id,
        'post__not_in' => array(get_the_ID()), // Exclude the current post from related posts
    );

    $related_posts_query = new WP_Query($args_related);
    $related_posts = '';

    // if ($related_posts_query->have_posts()) {
    //     while ($related_posts_query->have_posts()) {
    //         $related_posts_query->the_post();
    //         $related_posts .= '<div class="related-post">';
    //         $related_posts .= '<h3>' . get_the_title() . '</h3>';
    //         // $related_posts .= '<p>' . get_the_content() . '</p>';
    //         $related_posts .= '<p>' . get_the_excerpt() . '</p>'; // Display the excerpt instead of full content
    //         $related_posts .= '<a href="' . get_the_permalink() . '" class="read-more">Read More</a>';  // Read more link
            
    //         $related_posts .= '</div>';
    //     }
    // }
    if ($related_posts_query->have_posts()) {
        while ($related_posts_query->have_posts()) {
            $related_posts_query->the_post();
            $related_posts .= '<div class="related-post">';
            $related_posts .= '<h3>' . get_the_title() . '</h3>';
    
            // Get the content or excerpt and limit it to 30 characters
            $excerpt = get_the_excerpt(); // or get_the_content() for full content
            $limited_excerpt = mb_substr($excerpt, 0, 90);
    
            $related_posts .= '<p>' . $limited_excerpt . '...</p>'; // Display the limited excerpt
            $related_posts .= '<a href="' . get_the_permalink() . '" class="read-more">Read More</a>';  // Read more link
            $related_posts .= '</div>';
        }
    }



    wp_reset_postdata();

    // Return the content as JSON
    $response = array(
        'single_post' => $single_post,
        'related_posts' => $related_posts,
    );

    wp_send_json($response);
}



// Siebar Code//
function custom_theme_widgets_init() {
    register_sidebar(array(
        'name' => __('Right Sidebar', 'text-domain'),
        'id' => 'right-sidebar',
        'description' => __('Widgets in this area will be shown on the right side of your content.', 'text-domain'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}
add_action('widgets_init', 'custom_theme_widgets_init');




function display_category_posts($atts) {
    $atts = shortcode_atts(array(
        'category_id' => 0,
        'posts_per_page' => 5,
    ), $atts, 'category_posts');

    $args = array(
        'cat' => $atts['category_id'],
        'posts_per_page' => $atts['posts_per_page'],
    );

    $the_query = new WP_Query($args);

    $output = '';

    if ($the_query->have_posts()) {
        $output .= '<div class="row">';
        while ($the_query->have_posts()) {
            $the_query->the_post();
            $output .= '<div class="col-md-4">';
            $output .= '<div class="category-post">';
            
            // Display post thumbnail (featured image)
            if (has_post_thumbnail()) {
                $output .= '<div class="category-post-thumbnail">' . get_the_post_thumbnail() . '</div>';
            }
            
            $output .= '<h3><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h3>';
            $output .= '<div class="category-post-excerpt">' . get_the_excerpt() . '</div>'; // You can use get_the_content() instead of get_the_excerpt() if you want to display full content
            
            // Download button
            $output .= '<a href="' . get_permalink() . '?download=true" class="download-button">Download This Post</a>';
            $output .= '</div>';
            $output .= '</div>';
        }
        $output .= '</div>';
    } else {
        // no posts found
        $output .= 'No posts found';
    }

    /* Restore original Post Data */
    wp_reset_postdata();

    return $output;
}
add_shortcode('category_posts', 'display_category_posts');






// Register Custom Post Type
function create_guide_post_type() {
    $labels = array(
        'name'                  => _x( 'Guides', 'Post Type General Name', 'text_domain' ),
        'singular_name'         => _x( 'Guide', 'Post Type Singular Name', 'text_domain' ),
        'menu_name'             => __( 'Guides', 'text_domain' ),
        'name_admin_bar'        => __( 'Guide', 'text_domain' ),
        'archives'              => __( 'Guide Archives', 'text_domain' ),
        'attributes'            => __( 'Guide Attributes', 'text_domain' ),
        'parent_item_colon'     => __( 'Parent Guide:', 'text_domain' ),
        'all_items'             => __( 'All Guides', 'text_domain' ),
        'add_new_item'          => __( 'Add New Guide', 'text_domain' ),
        'add_new'               => __( 'Add New', 'text_domain' ),
        'new_item'              => __( 'New Guide', 'text_domain' ),
        'edit_item'             => __( 'Edit Guide', 'text_domain' ),
        'update_item'           => __( 'Update Guide', 'text_domain' ),
        'view_item'             => __( 'View Guide', 'text_domain' ),
        'view_items'            => __( 'View Guides', 'text_domain' ),
        'search_items'          => __( 'Search Guide', 'text_domain' ),
        'not_found'             => __( 'Guide Not found', 'text_domain' ),
        'not_found_in_trash'    => __( 'Guide Not found in Trash', 'text_domain' ),
        'featured_image'        => __( 'Featured Image', 'text_domain' ),
        'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
        'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
        'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
        'insert_into_item'      => __( 'Insert into Guide', 'text_domain' ),
        'uploaded_to_this_item' => __( 'Uploaded to this Guide', 'text_domain' ),
        'items_list'            => __( 'Guides list', 'text_domain' ),
        'items_list_navigation' => __( 'Guides list navigation', 'text_domain' ),
        'filter_items_list'     => __( 'Filter Guides list', 'text_domain' ),
    );
    $args = array(
        'label'                 => __( 'Guide', 'text_domain' ),
        'description'           => __( 'Post Type Description', 'text_domain' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes' ),
        'taxonomies'            => array( 'category', 'post_tag' ), // You can add any additional taxonomies here
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-book-alt', // Choose an icon for your Guide CPT
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
    );
    register_post_type( 'guide', $args );
}
add_action( 'init', 'create_guide_post_type', 0 );






//Code for CPT//
function custom_post_type_shortcode($atts) {
    $atts = shortcode_atts(array(
        'post_type' => 'guide', // Replace with your CPT slug
        'posts_per_page' => -1, // -1 for displaying all posts, you can change this number
    ), $atts);

    $custom_query = new WP_Query($atts);

    $output = '';
    if ($custom_query->have_posts()) {
        while ($custom_query->have_posts()) {
            $custom_query->the_post();
            $output .= '<h2>' . get_the_title() . '</h2>';
            $output .= get_the_content();
        }
        wp_reset_postdata();
    } else {
        $output = 'No posts found.';
    }

    return $output;
}
add_shortcode('display_custom_post_type', 'custom_post_type_shortcode');


// Hook into the do_shortcode_tag filter For News Leter Text
add_filter('do_shortcode_tag', 'change_newsletter_form_text', 10, 4);

function change_newsletter_form_text($output, $tag, $attr, $m) {
    // Check if the shortcode tag matches the one you want to modify
    if ($tag === 'newsletter_form' && isset($attr['type']) && $attr['type'] === 'minimal') {
        // Modify the output HTML text
        $output = str_replace('Email', 'Email Address', $output);
    }
    return $output;
}









// Code for increase media uplaod file size

@ini_set( 'upload_max_size' , '64M' );
@ini_set( 'post_max_size', '64M');
@ini_set( 'max_execution_time', '300' );



/* Code for download button*/

function download_button_shortcode() {
    // Replace the href with the correct download link
 

    // Generate the HTML for the download button with the additional class
    // $button_html = '<a class="download-clss popmake-4935" href="' . esc_url($download_link) . '">Download</a>';

     $button_html = '<a class="download-clss popmake-4935" href="#">Download</a>';
    


    return $button_html;
}
add_shortcode('download_button', 'download_button_shortcode');




// Modify the existing category_posts_shortcode function
function category_posts_shortcode($atts) {
    // Extract shortcode attributes
    $atts = shortcode_atts(
        array(
            'category' => 'template', // Default value for the category slug
            'template' => 'default', // Default template name
        ),
        $atts
    );

    // Retrieve posts based on the provided category slug
    $args = array(
        'category_name' => $atts['category'],
        'posts_per_page' => -1, // Retrieve all posts in the category
    );
    $query = new WP_Query($args);

    $output = '<div class="row">';
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $output .= '<div class="col-md-4">';
            // Customize the output as needed
            $output .= '<h2><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';
            // Display template name
            $output .= '<p>Template: ' . $atts['template'] . '</p>';
            
            // Add the [printfriendly] shortcode to each post content
            // $output .= apply_filters('the_content', get_the_content('[printfriendly]'));
            // $output .="<a class='download-clss popmake-4935' href='#'>Download</a>";
            // $output .="<button onclick='downloadPostAsPDF()'>Download as PDF</button>";
              $output .="<button id='downloadAllPDF'>Download Post as PDF</button>";

            
            $output .= '</div>';
        }
        wp_reset_postdata(); // Reset the query
    } else {
        $output .= 'No posts found';
    }
    $output .= '</div>';

    return $output;
}
add_shortcode('category_posts', 'category_posts_shortcode');

//*****************End***************** */

// Add a shortcode to display post title, image, and download button by ID
function display_post_by_id_with_download_shortcode($atts) {
    // Extract shortcode attributes
    $atts = shortcode_atts(array(
        'id' => 0, // Default post ID
    ), $atts, 'display_post_with_download');

    // Get post by ID
    $post_id = intval($atts['id']);
    $post = get_post($post_id);

    // If the post exists
    if ($post) {
        $post_title = $post->post_title;
        $post_image_id = get_post_thumbnail_id($post_id);
        $post_image_src = wp_get_attachment_image_src($post_image_id, 'full');
        $post_content = $post->post_content;

        // Construct HTML output
        $output = '<div class="custom-post-display">';
        $output .= '<h2>' . esc_html($post_title) . '</h2>';
        $output .= '<img src="' . esc_url($post_image_src[0]) . '" alt="' . esc_attr($post_title) . '" />';
        $output .= '<button class="download-button" data-post-content="' . esc_attr($post_content) . '" data-post-image-url="' . esc_url($post_image_src[0]) . '">Download Post</button>';
        $output .= '</div>';

        return $output;
    } else {
        return 'Post not found';
    }
}
add_shortcode('display_post_with_download', 'display_post_by_id_with_download_shortcode');

// Add a shortcode to display posts by category with download buttons
// function display_posts_by_category_with_download_buttons_shortcode($atts) {
//     // Shortcode attribute - category slug
//     $atts = shortcode_atts(array(
//         'category' => '', // Default empty
//     ), $atts, 'display_posts_by_category_with_download_buttons');

//     $category_slug = $atts['category'];

//     // Get category ID from slug
//     $category = get_term_by('slug', $category_slug, 'category');

//     if ($category) {
//         $category_id = $category->term_id;

//         $args = array(
//             'post_type' => 'post',
//             'posts_per_page' => '',
//             'category__in' => array($category_id),
//         );

//         $posts_query = new WP_Query($args);

//         if ($posts_query->have_posts()) {
//             $output = '<div id="postsContainer">'; // Start posts container

//             while ($posts_query->have_posts()) {
//                 $posts_query->the_post();
//                 $post_id = get_the_ID();
//                 $post_title = get_the_title();
//                 $post_image_id = get_post_thumbnail_id($post_id);
                
//                 $post_image_src = wp_get_attachment_image_src($post_image_id, 'full');
//                 $post_content = get_the_content();
//                 $output .= '<div class="single-post-by-category-with-download col-md-4">';
//                 $output .= '<a href="' . esc_url(get_permalink()) . '">'; // Opening anchor tag
//                 $output .= '<img src="' . esc_url($post_image_src[0]) . '" alt="' . esc_attr($post_title) . '" />';
//                 $output .= '<h2>' . esc_html($post_title) . '</h2>';
//                 $output .= '<div class="post-content">' . wpautop(wp_kses_post($post_content)) . '</div>';
//                 $output .= '</a>'; 
    
//                 // $output .= do_shortcode('[wpb-pcf-button]');
//                 // $output = '<div id="pcf-button-container">' . do_shortcode('[wpb-pcf-button]') . '</div>';
//                 // $output .= '<button id="post-btn">Download</button>';
//                 $output .= '<a href="' . get_the_permalink() . '" class="read-more">Read Template</a>';
//                 $output .= '</div>';
//             }

//             $output .= '</div>';
//             wp_reset_postdata(); // Restore global post data

//             return $output;
//         } else {
//             return 'No posts found in this category';
//         }
//     } else {
//         return 'Category not found';
//     }
// }
// add_shortcode('display_posts_by_category_with_download_buttons', 'display_posts_by_category_with_download_buttons_shortcode');

function display_posts_by_category_with_download_buttons_shortcode($atts) {
    // Shortcode attributes - category slug and post count
    $atts = shortcode_atts(array(
        'category' => '', // Default empty
        'count' => -1, // Default to show all posts (-1)
    ), $atts, 'display_posts_by_category_with_download_buttons');

    $category_slug = $atts['category'];
    $post_count = intval($atts['count']); // Get the count attribute as an integer

    // Get category ID from slug
    $category = get_term_by('slug', $category_slug, 'category');

    if ($category) {
        $category_id = $category->term_id;

        $args = array(
            'post_type' => 'post',
            'posts_per_page' => $post_count, // Set the posts per page based on the attribute
            'category__in' => array($category_id),
        );

        $posts_query = new WP_Query($args);

        if ($posts_query->have_posts()) {
            $output = '<div id="postsContainer">'; // Start posts container

            while ($posts_query->have_posts()) {
                $posts_query->the_post();
                $post_id = get_the_ID();
                $post_title = get_the_title();
                $post_image_id = get_post_thumbnail_id($post_id);
                
                $post_image_src = wp_get_attachment_image_src($post_image_id, 'full');
                $post_content = get_the_content();
                $output .= '<div class="single-post-by-category-with-download col-md-4">';
                $output .= '<a href="' . esc_url(get_permalink()) . '">'; // Opening anchor tag
                $output .= '<img src="' . esc_url($post_image_src[0]) . '" alt="' . esc_attr($post_title) . '" />';
                $output .= '<h2>' . esc_html($post_title) . '</h2>';
                $output .= '<div class="post-content">' . wpautop(wp_kses_post($post_content)) . '</div>';
                $output .= '</a>'; 
    
                // Add your download button or link here if needed
                // $output .= '<a href="your-download-link-here" class="download-button">Download</a>';
                
                $output .= '<a href="' . get_the_permalink() . '" class="read-more">Read Template</a>';
                $output .= '</div>';
            }

            $output .= '</div>';
            wp_reset_postdata(); // Restore global post data

            return $output;
        } else {
            return 'No posts found in this category';
        }
    } else {
        return 'Category not found';
    }
}
add_shortcode('display_posts_by_category_with_download_buttons', 'display_posts_by_category_with_download_buttons_shortcode');




add_action('template_redirect', 'redirect_specific_category_template');



//*******************************************Code For Redirect Cetegory template************************************************************************** */


function redirect_specific_category_template() {
    // Check if it's a single post
    if (is_single()) {
        global $post;
        // Check if the post belongs to the specific category by ID or slug
        if (has_category('template', $post)) {
            // Redirect to the custom template
            include(locate_template('category-temp.php'));
            exit();
        }
    }
}

//**********************************************End***************************************************************************** */

// Add meta box for a generic link to the post editing screen


// Add custom meta box for Excel URL
function add_excel_url_meta_box() {
    add_meta_box(
        'excel_url_meta_box',
        'Excel Download URL',
        'render_excel_url_meta_box',
        'post', // Change 'post' to the desired post type
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'add_excel_url_meta_box');

// Render the custom meta box content
function render_excel_url_meta_box($post) {
    // Retrieve the current value of the Excel URL
    $excel_url = get_post_meta($post->ID, 'excel_url', true);
    ?>
    <label for="excel_url_field">Excel File URL:</label>
    <input type="text" id="excel_url_field" name="excel_url" value="<?php echo esc_attr($excel_url); ?>" style="width: 100%;" />
    <?php
}

// Save the Excel URL when the post is saved
function save_excel_url_meta_data($post_id) {
    if (array_key_exists('excel_url', $_POST)) {
        update_post_meta(
            $post_id,
            'excel_url',
            sanitize_text_field($_POST['excel_url'])
        );
    }
}
add_action('save_post', 'save_excel_url_meta_data');




// Shortcode to display Excel download link
function excel_download_shortcode() {
    $excel_url = get_post_meta(get_the_ID(), 'excel_url', true);
    if ($excel_url) {
        return '<a href="' . esc_url($excel_url) . '">Download Excel</a>';
    }
}
add_shortcode('excel_download', 'excel_download_shortcode');












function generic_link_meta_box() {
    add_meta_box(
        'generic_link_field', // Unique ID
        'Link Field', // Box title
        'render_generic_link_field', // Callback function to render the link field
        'post', // Post type where the meta box will be displayed (e.g., post, page, custom post type)
        'normal', // Context: 'normal', 'advanced', or 'side'
        'default' // Priority: 'default', 'high', 'low'
    );
}
add_action('add_meta_boxes', 'generic_link_meta_box');

// Render the generic link field inside the meta box
function render_generic_link_field($post) {
    // Retrieve the existing value for the field
    $generic_link = get_post_meta($post->ID, 'generic_link', true);

    // Output the HTML input field for the link
    ?>
    <label for="generic_link_field">Link URL:</label>
    <input type="text" id="generic_link_field" name="generic_link_field" value="<?php echo esc_attr($generic_link); ?>" style="width: 100%;" />
    <p>Enter the full URL of the link.</p>
    <?php
}

// Save the generic link value when the post is saved
function save_generic_link_field($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (isset($_POST['generic_link_field'])) {
        $generic_link = esc_url_raw($_POST['generic_link_field']);
        update_post_meta($post_id, 'generic_link', $generic_link);
    }
}
add_action('save_post', 'save_generic_link_field');



// **************************************Sign in




//**********************End******************* */

//****************************Popup Login****************************** */

// function custom_signin_button_shortcode($atts) {
//     $output = '';
//     if (is_user_logged_in()) {
//         $output .= '<a href="/producttively/">Sign Out</a>';
//     } else {
//         // $output .= '<a href="#" class="popmake-6695">Sign In</a>';
//         $output .= '<a href="https://productively.app/login">Sign In</a>';
//     }
//     return $output;
// }
// add_shortcode('custom_signin_button', 'custom_signin_button_shortcode');



function custom_signin_button_shortcode($atts) {
    $output = '';
    if (is_user_logged_in()) {
        $logout_url = wp_logout_url(home_url()); // Get the logout URL
        $output .= '<a href="' . esc_url($logout_url) . '">Sign Out</a>';
    } else {
        // If user is not logged in, show the Sign In link
        $output .= '<a href="https://productively.app/login">Sign In</a>';
    }
    return $output;
}
add_shortcode('custom_signin_button', 'custom_signin_button_shortcode');






//**************************************Login Form**************************************************** */


////



//******************************************** */





//*********************************************************************************************************************8 */






// function display_posts_by_category_with_download_buttons_shortcode_two($atts) {
//     // Shortcode attribute - category slug
//     $atts = shortcode_atts(array(
//         'category' => '', // Default empty
//     ), $atts, 'display_posts_by_category_with_download_buttons_two');

//     $category_slug = $atts['category'];

//     // Get category ID from slug
//     $category = get_term_by('slug', $category_slug, 'category');

//     if ($category) {
//         $category_id = $category->term_id;

//         $args = array(
//             'post_type' => 'post',
//             'posts_per_page' => -1,
//             'category__in' => array($category_id),
//         );

//         $posts_query = new WP_Query($args);

//         if ($posts_query->have_posts()) {
//             $output = '<div id="postsContainer">'; // Start posts container

//             while ($posts_query->have_posts()) {
//                 $posts_query->the_post();
//                 $post_id = get_the_ID();
//                 $post_title = get_the_title();
//                 $post_image_id = get_post_thumbnail_id($post_id);
                
//                 $post_image_src = wp_get_attachment_image_src($post_image_id, 'full');
//                 $post_content = get_the_content();
//                 $output .= '<div class="single-post-by-category-with-download col-md-4">';
//                 $output .= '<a href="' . esc_url(get_permalink()) . '">'; // Opening anchor tag
//                 $output .= '<img src="' . esc_url($post_image_src[0]) . '" alt="' . esc_attr($post_title) . '" />';
//                 $output .= '<h2>' . esc_html($post_title) . '</h2>';
//                 $output .= '<div class="post-content">' . wpautop(wp_kses_post($post_content)) . '</div>';
//                 $output .= '</a>'; 
    
//                 // $output .= do_shortcode('[wpb-pcf-button]');
//                 // $output = '<div id="pcf-button-container">' . do_shortcode('[wpb-pcf-button]') . '</div>';
//                 // $output .= '<button id="post-btn">Download</button>';
//                 $output .= '<a href="' . get_the_permalink() . '" class="read-more">Read Guide</a>';
//                 $output .= '</div>';
//             }


//             $output .= '</div>';
//             wp_reset_postdata(); // Restore global post data

//             return $output;
//         } else {
//             return 'No posts found in this category';
//         }
//     } else {
//         return 'Category not found';
//     }






// }


// add_shortcode('display_posts_by_category_with_download_buttons_two', 'display_posts_by_category_with_download_buttons_shortcode_two');


function display_posts_by_category_with_download_buttons_shortcode_two($atts) {
    // Shortcode attributes - category slug and post count
    $atts = shortcode_atts(array(
        'category' => '', // Default empty
        'count' => -1, // Default to show all posts (-1)
    ), $atts, 'display_posts_by_category_with_download_buttons_two');

    $category_slug = $atts['category'];
    $post_count = intval($atts['count']); // Get the count attribute as an integer

    // Get category ID from slug
    $category = get_term_by('slug', $category_slug, 'category');

    if ($category) {
        $category_id = $category->term_id;

        $args = array(
            'post_type' => 'post',
            'posts_per_page' => $post_count, // Set the posts per page based on the attribute
            'category__in' => array($category_id),
        );

        $posts_query = new WP_Query($args);

        if ($posts_query->have_posts()) {
            $output = '<div id="postsContainer">'; // Start posts container

            while ($posts_query->have_posts()) {
                $posts_query->the_post();
                $post_id = get_the_ID();
                $post_title = get_the_title();
                $post_image_id = get_post_thumbnail_id($post_id);
                
                $post_image_src = wp_get_attachment_image_src($post_image_id, 'full');
                $post_content = get_the_content();
                $output .= '<div class="single-post-by-category-with-download col-md-4">';
                $output .= '<a href="' . esc_url(get_permalink()) . '">'; // Opening anchor tag
                $output .= '<img src="' . esc_url($post_image_src[0]) . '" alt="' . esc_attr($post_title) . '" />';
                $output .= '<h2>' . esc_html($post_title) . '</h2>';
                $output .= '<div class="post-content">' . wpautop(wp_kses_post($post_content)) . '</div>';
                $output .= '</a>'; 
    
                // Add your download button or link here if needed
                // $output .= '<a href="your-download-link-here" class="download-button">Download</a>';
                
                $output .= '<a href="' . get_the_permalink() . '" class="read-more">Read Guide</a>';
                $output .= '</div>';
            }

            $output .= '</div>';
            wp_reset_postdata(); // Restore global post data

            return $output;
        } else {
            return 'No posts found in this category';
        }
    } else {
        return 'Category not found';
    }
}
add_shortcode('display_posts_by_category_with_download_buttons_two', 'display_posts_by_category_with_download_buttons_shortcode_two');


add_action('template_redirect', 'redirect_specific_category_template_two');






function redirect_specific_category_template_two() {
    // Check if it's a single post
    if (is_single()) {
        global $post;
        // Check if the post belongs to the specific category by ID or slug
        if (has_category('guide', $post)) {
            // Redirect to the custom template
            include(locate_template('guide-category.php'));
            exit();
        }
    }
}










// *****************************Codefr disp**************************************************


// Add shortcode function to display post title by ID
function display_post_title_by_id($atts) {
    // Extract shortcode attributes
    $atts = shortcode_atts(array(
        'id' => '', // Default value for post ID
    ), $atts, 'display_post_title');

    // Get post ID from shortcode attribute
    $post_id = $atts['id'];

    // Check if post ID is provided
    if (!empty($post_id)) {
        // Get the post object by ID
        $post = get_post($post_id);

        // Check if the post exists
        if ($post) {
            // Output the post title with a permalink
            $output = '<a href="' . get_permalink($post_id) . '">' . esc_html($post->post_title) . '</a>';
            return $output;
        } else {
            return 'Post not found!';
        }
    } else {
        return 'Please provide a valid post ID!';
    }
}
add_shortcode('display_post_title', 'display_post_title_by_id');









//********************************************** Get post data ajax code end ************************************************************//
function enqueue_ajax_scripts() {
    wp_enqueue_script('my-custom-script', get_stylesheet_directory_uri() . '/js/myscript.js', array('jquery'), '1.0', true);
    wp_localize_script('my-custom-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
    // Pass the file URL to the script
    wp_localize_script('custom-script', 'custom_script_vars', array(
        'file_url' => 'https://web.ntfinfotech.com/producttively/wp-content/uploads/2023/12/5-Most-Overlooked-Benefits-of-Time-Tracking-Downloadable-2.pdf'
    ));
}

add_action('wp_enqueue_scripts', 'enqueue_ajax_scripts');
