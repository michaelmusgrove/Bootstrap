<?php
if (is_admin()) {
	if (!(defined('MODMAN_RUN_MODE'))) {
	   require_once dirname(__FILE__) . '/embedded-modules-manager/plugin.php';
	}
    require_once dirname(__FILE__) . '/theme-options/bootstrap-grid/bootstrap.php';
}

// Include theme options/theme-options/inc/
if (!function_exists('optionsframework_init')) {

    define('OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/theme-options/inc/');
    require_once dirname(__FILE__) . '/theme-options/inc/options-framework.php';

    function wpbootstrap_get_setting($option, $id) {
        if (is_array(of_get_option($option))) {
            if (array_key_exists($id, of_get_option($option))) {
                $setting = of_get_option($option);
                return $setting [$id];
            } else {
                return false;
            }
        }
        return true;
    }

}

// Calculations for content width based on sidebar width
if (!function_exists('wpbootstrap_get_content_width')) {

    function wpbootstrap_get_content_width() {
        $content_class = 'span12'; // if sidebar is disabled
        if (wpbootstrap_get_setting('general_settings', 'display_sidebar') && (!is_page_template('page-fullwidth.php'))) {
            $content_class = 'span' . (12 - intval(of_get_option('sidebar_width')) ); // sidebar width - content width
        }
        return $content_class;
    }

}

// Set max content width
if (!isset($content_width)) {
    $content_width = 770;
    if (wpbootstrap_get_setting('general_settings', 'display_sidebar')) {
        $content_width = (12 - intval(of_get_option('sidebar_width'))) * 100 - 30;
    }
}

// Basic theme setup
if (!function_exists('wpbootstrap_setup_theme')) {

    function wpbootstrap_setup_theme() {

        // Define /lang/ directory for translations
        load_theme_textdomain('wpbootstrap', get_template_directory() . '/languages');

        // Add editor-style.css for WordPress editor
        add_editor_style('editor-style.css');

        // Adds RSS feed links
        add_theme_support('automatic-feed-links');
        add_theme_support( 'woocommerce' );

        // Add support for post formats: http://codex.wordpress.org/Post_Formats
        add_theme_support('post-formats', array('aside', 'image', 'link', 'quote', 'status', 'gallery'));

        // Add support for custom background
        add_theme_support('custom-background', array(
            'default-color' => 'fff',
        ));

        // Add support fot post thumbnails
        add_theme_support('post-thumbnails');

        // Register nav menu
        register_nav_menus(
                array(
                    'header-menu' => __('Header Menu', 'wpbootstrap')
                )
        );

    }

    add_action('after_setup_theme', 'wpbootstrap_setup_theme');
}

// Registers main widget area
if (!function_exists('wpbootstrap_register_sidebar') && wpbootstrap_get_setting('general_settings', 'display_sidebar')) {

    function wpbootstrap_register_sidebar() {
        register_sidebar(array(
            'name' => __('Sidebar', 'wpbootstrap'),
            'id' => 'sidebar',
            'description' => __('Appears on posts and pages except the optional full width page template', 'wpbootstrap'),
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget' => '</aside>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
    }

    add_action('widgets_init', 'wpbootstrap_register_sidebar');
}

// Registers header widget area
if (!function_exists('wpbootstrap_register_header_widgets') && wpbootstrap_get_setting('general_settings', 'display_header_widgets')) {

    function wpbootstrap_register_header_widgets() {
        $widget_class = 'span4';
        if (wpbootstrap_get_setting('general_settings', 'display_header_widgets')) {
            $widget_class = 'span' . intval(of_get_option('header_widget_width'));
        }
        register_sidebar(array(
            'name' => __('Header widgets area', 'wpbootstrap'),
            'id' => 'header-widgets',
            'description' => __('Appears above the header', 'wpbootstrap'),
            'before_widget' => '<div id="%1$s" class="' . $widget_class . ' widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
    }

    add_action('widgets_init', 'wpbootstrap_register_header_widgets');
}

// Registers footer widget area
if (!function_exists('wpbootstrap_register_footer_widgets') && wpbootstrap_get_setting('general_settings', 'display_footer_widgets')) {

    function wpbootstrap_register_footer_widgets() {
        $widget_class = 'span4';
        if (wpbootstrap_get_setting('general_settings', 'display_footer_widgets')) {
            $widget_class = 'span' . intval(of_get_option('footer_widget_width'));
        }
        register_sidebar(array(
            'name' => __('Footer widgets area', 'wpbootstrap'),
            'id' => 'footer-widgets',
            'description' => __('Appears above the footer', 'wpbootstrap'),
            'before_widget' => '<div id="%1$s" class="' . $widget_class . ' widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
    }

    add_action('widgets_init', 'wpbootstrap_register_footer_widgets');
}

// Support for Bootstrap Pager. More info: http://twitter.github.com/bootstrap/components.html#pagination
if (!function_exists('wpbootstrap_content_nav')) {

    function wpbootstrap_content_nav() {
        global $wp_query;
        if ($wp_query->max_num_pages > 1) :
            ?>
            <ul class="pager" role="navigation">
                <li class="nav-previous previous">
                    <?php echo str_replace('<a href', '<a rel="prev" href', get_next_posts_link(__('&larr; Olders posts', 'wpbootstrap'))) ?>
                </li>
                <li class="nav-next next">
                    <?php echo str_replace('<a href', '<a rel="next" href', get_previous_posts_link(__('Newer posts &rarr;', 'wpbootstrap'))) ?>
                </li>
            </ul>
            <?php
        endif;
    }

}

// Adds 'hero-unit' class for sticky posts. More info: http://twitter.github.com/bootstrap/components.html#typography
if (!function_exists('wpbootstrap_sticky_post')) {

    function wpbootstrap_sticky_post($classes) {
        if (is_sticky() && is_home() && !is_paged()) {
            $classes[] = 'hero-unit';
        }
        return $classes;
    }

    add_filter('post_class', 'wpbootstrap_sticky_post');
}

// Adds 'table' class for <table> tags. Bootstrap needs an additional 'table' class to style tables. More info: http://twitter.github.com/bootstrap/base-css.html#tables
if (!function_exists('wpbootstrap_add_table_class')) {

    function wpbootstrap_add_table_class($content) {
        $table_has_class = preg_match('/<table class="/', $content);
        if ($table_has_class) {
            $content = str_replace('<table class="', '<table class="table ', $content);
        } else {
            $content = str_replace('<table', '<table class="table"', $content);
        }
        return $content;
    }

    add_filter('the_content', 'wpbootstrap_add_table_class');
}


//Pagination function. Thanks to: https://gist.github.com/3774261
if (!function_exists('wpbootstrap_link_pages')) {

    function wpbootstrap_link_pages($args = '') {
        $defaults = array(
            'before' => '<div class="pagination"><ul>',
            'after' => '</ul></div>',
            'next_or_number' => 'number',
            'nextpagelink' => __('Next page', 'wpbootstrap'),
            'previouspagelink' => __('Previous page', 'wpbootstrap'),
            'pagelink' => '%',
            'echo' => 1
        );

        $r = wp_parse_args($args, $defaults);
        $r = apply_filters('wp_link_pages_args', $r);
        extract($r, EXTR_SKIP);

        global $page, $numpages, $multipage, $more, $pagenow;

        $output = '';
        if ($multipage) {
            if ('number' == $next_or_number) {
                $output .= $before;
                for ($i = 1; $i < ( $numpages + 1 ); $i = $i + 1) {
                    $j = str_replace('%', $i, $pagelink);
                    $output .= ' ';
                    if ($i != $page || ( (!$more ) && ( $page == 1 ) ))
                        $output .= '<li>' . _wp_link_page($i);
                    else
                        $output .= '<li class="active"><a href="#">';

                    $output .= $j;
                    if ($i != $page || ( (!$more ) && ( $page == 1 ) ))
                        $output .= '</a>';
                    else
                        $output .= '</a></li>';
                }
                $output .= $after;
            } else {
                if ($more) {
                    $output .= $before;
                    $i = $page - 1;
                    if ($i && $more) {
                        $output .= _wp_link_page($i);
                        $output .= $previouspagelink . '</a>';
                    }
                    $i = $page + 1;
                    if ($i <= $numpages && $more) {
                        $output .= _wp_link_page($i);
                        $output .= $nextpagelink . '</a>';
                    }
                    $output .= $after;
                }
            }
        }

        if ($echo)
            echo $output;

        return $output;
    }

}

/* Make default WordPress wp_nav_menu() to be Twitter Bootstrap compatibile.
 *  Thanks to Roots Theme: http://www.rootstheme.com/
 *  More info: http://twitter.github.com/bootstrap/components.html#navs
 */

class Wpbootstrap_Nav_Walker extends Walker_Nav_Menu {

    function check_current($classes) {
        return preg_match('/(current[-_])|active|dropdown/', $classes);
    }

    function start_lvl(&$output, $depth = 0, $args = array()) {
        $output .= "\n<ul class=\"dropdown-menu\">\n";
    }

    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $item_html = '';
        parent::start_el($item_html, $item, $depth, $args);

        if ($item->is_dropdown && ($depth === 0)) {
            $item_html = str_replace('<a', '<a class="dropdown-toggle" data-toggle="dropdown" data-target="#"', $item_html);
            $item_html = str_replace('</a>', ' <b class="caret"></b></a>', $item_html);
        } elseif (stristr($item_html, 'li class="divider')) {
            $item_html = preg_replace('/<a[^>]*>.*?<\/a>/iU', '', $item_html);
        } elseif (stristr($item_html, 'li class="nav-header')) {
            $item_html = preg_replace('/<a[^>]*>(.*)<\/a>/iU', '$1', $item_html);
        }

        $output .= $item_html;
    }

    function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output) {
        $element->is_dropdown = !empty($children_elements[$element->ID]);

        if ($element->is_dropdown) {
            if ($depth === 0) {
                $element->classes[] = 'dropdown';
            } elseif ($depth === 1) {
                $element->classes[] = 'dropdown-submenu';
            }
        }

        parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
    }

}

/*
 * Set default attributes for wp_nav_menu() function:
 * items_wrap => '<ul class="%2$s">%3$s</ul>',
 * dept => 3
 */
if (!function_exists('wpbootstrap_nav_menu_defaults')) {

    function wpbootstrap_nav_menu_defaults($args = '') {
        $nav_menu_args['container'] = false;

        if (!$args['items_wrap']) {
            $nav_menu_args['items_wrap'] = '<ul class="%2$s">%3$s</ul>';
        }

        if (current_theme_supports('bootstrap-top-navbar')) {
            $nav_menu_args['depth'] = 3;
        }

        if (!$args['walker']) {
            $nav_menu_args['walker'] = new Wpbootstrap_Nav_Walker();
        }

        return array_merge($args, $nav_menu_args);
    }

    add_filter('wp_nav_menu_args', 'wpbootstrap_nav_menu_defaults');
}


/**
 * Display top level pages if no nav menu is selected
 *
 */
if (!function_exists('wpbootstrap_nav_menu_fallback')) {

    function wpbootstrap_nav_menu_fallback($args) {
        if (!isset($args['show_home'])) {
            $args['show_home'] = true;
            $args['depth'] = '1';
            $args['walker'] = null;
        }
        return $args;
    }

    add_filter('wp_page_menu_args', 'wpbootstrap_nav_menu_fallback');
}

/**
 * Add "nav" class to wp_page_menu() <ul> element
 *
 */
if (!function_exists('wpbootstrap_add_wp_page_menu_class')) {

    function wpbootstrap_add_wp_page_menu_class($ulclass) {
        return preg_replace('/<ul>/', '<ul class="nav">', $ulclass);
    }

    add_filter('wp_page_menu', 'wpbootstrap_add_wp_page_menu_class');
}

/**
 * Twitter Bootstrap compatibile [gallery] shortcode,
 * Thanks to The Bootstrap theme: http://wordpress.org/extend/themes/the-bootstrap
 */
if (!function_exists('wpbootstrap_gallery')) {

    function wpbootstrap_gallery($content, $attr) {
        global $instance, $post;
        $instance++;

        // We're trusting author input, so let's at least make sure it looks like a valid orderby statement
        if (isset($attr['orderby'])) {
            $attr['orderby'] = sanitize_sql_orderby($attr['orderby']);
            if (!$attr['orderby'])
                unset($attr['orderby']);
        }

        extract(shortcode_atts(array(
                    'order' => 'ASC',
                    'orderby' => 'menu_order ID',
                    'id' => $post->ID,
                    'itemtag' => 'div',
                    'captiontag' => 'div',
                    'columns' => 3,
                    'size' => 'thumbnail',
                    'include' => '',
                    'exclude' => ''
                        ), $attr));


        $id = intval($id);
        if ('RAND' == $order)
            $orderby = 'none';

        if ($include) {
            $include = preg_replace('/[^0-9,]+/', '', $include);
            $_attachments = get_posts(array(
                'include' => $include,
                'post_status' => 'inherit',
                'post_type' => 'attachment',
                'post_mime_type' => 'image',
                'order' => $order,
                'orderby' => $orderby
                    ));

            $attachments = array();
            foreach ($_attachments as $key => $val) {
                $attachments[$val->ID] = $_attachments[$key];
            }
        } elseif ($exclude) {
            $exclude = preg_replace('/[^0-9,]+/', '', $exclude);
            $attachments = get_children(array(
                'post_parent' => $id,
                'exclude' => $exclude,
                'post_status' => 'inherit',
                'post_type' => 'attachment',
                'post_mime_type' => 'image',
                'order' => $order,
                'orderby' => $orderby
                    ));
        } else {
            $attachments = get_children(array(
                'post_parent' => $id,
                'post_status' => 'inherit',
                'post_type' => 'attachment',
                'post_mime_type' => 'image',
                'order' => $order,
                'orderby' => $orderby
                    ));
        }

        if (empty($attachments))
            return;

        if (is_feed()) {
            $output = "\n";
            foreach ($attachments as $att_id => $attachment)
                $output .= wp_get_attachment_link($att_id, $size, true) . "\n";
            return $output;
        }

        $itemtag = tag_escape($itemtag);
        $captiontag = tag_escape($captiontag);
        $columns = intval(min(array(8, $columns)));
        $float = (is_rtl()) ? 'right' : 'left';

        if (4 > $columns)
            $size = 'full';

        $selector = "gallery-{$instance}";
        $size_class = sanitize_html_class($size);
        $output = "<ul id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class} thumbnails'>";

        $i = 0;
        foreach ($attachments as $id => $attachment) {
            $comments = get_comments(array(
                'post_id' => $id,
                'count' => true,
                'type' => 'comment',
                'status' => 'approve'
                    ));

            $link = wp_get_attachment_link($id, $size, !( isset($attr['link']) AND 'file' == $attr['link'] ));
            $clear_class = ( 0 == $i++ % $columns ) ? ' clear' : '';
            $span = 'span' . floor(8 / $columns);

            $output .= "<li class='{$span}{$clear_class}'><{$itemtag} class='gallery-item thumbnail'>";
            $output .= "{$link}\n";

            if ($captiontag AND ( 0 < $comments OR trim($attachment->post_excerpt) )) {
                $comments = ( 0 < $comments ) ? sprintf(_n('%d comment', '%d comments', $comments, 'wpbootstrap'), $comments) : '';
                $excerpt = wptexturize($attachment->post_excerpt);
                $out = ($comments AND $excerpt) ? " $excerpt <br /> $comments " : " $excerpt$comments ";
                $output .= "<{$captiontag} class='wp-caption-text gallery-caption caption'><p>{$out}</p></{$captiontag}>\n";
            }
            $output .= "</{$itemtag}></li>\n";
        }
        $output .= "</ul>\n";

        return $output;
    }

    add_filter('post_gallery', 'wpbootstrap_gallery', 10, 2);
}

/*
 * Make WordPress comments template Bootstrap compatibile
 * Using Media object component. More info: http://twitter.github.com/bootstrap/components.html#media
 *
 */

/** COMMENTS WALKER */
class Wpbootstrap_Comments extends Walker_Comment {

    function start_lvl(&$output, $depth = 0, $args = array()) {
        $GLOBALS['comment_depth'] = $depth + 1;
        ?>
        <ul class="children unstyled media">
            <?php
        }

        function end_lvl(&$output, $depth = 0, $args = array()) {
            $GLOBALS['comment_depth'] = $depth + 1;
            ?>
        </ul><!-- .children -->
        </div><!-- .media-body -->
        <?php
    }

    /** START_EL */
    function start_el(&$output, $comment, $depth=0, $args=array(), $id = 0) {
        $depth++;
        $GLOBALS['comment_depth'] = $depth;
        $GLOBALS['comment'] = $comment;
        global $post
        ?>

        <li class="media" id="comment-<?php comment_ID(); ?>">
            <span class="pull-left <?php echo ( $comment->user_id === $post->post_author ? 'thumbnail' : ''); ?>">
                <?php
                if ($comment->user_id === $post->post_author) {
                    echo get_avatar($comment, 54);
                } else {
                    echo get_avatar($comment, 64);
                }
                ?>
            </span>
            <div class="media-body">
                <h4 class="media-heading">
                    <?php
                    printf('<cite>%1$s %2$s</cite>', get_comment_author_link(), ( $comment->user_id === $post->post_author ) ? '<span class="bypostauthor label label-info"> ' . __('Post author', 'wpbootstrap') . '</span>' : ''
                    );
                    ?>
                </h4>
                <?php
                printf('<a href="%1$s"><time datetime="%2$s">%3$s</time></a>', esc_url(get_comment_link($comment->comment_ID)), get_comment_time('c'), sprintf(__('%1$s at %2$s', 'wpbootstrap'), get_comment_date(), get_comment_time())
                );
                ?>

                <?php if ('0' == $comment->comment_approved) : ?>
                    <p class="alert comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.', 'wpbootstrap'); ?></p>
                <?php endif; ?>

                <div class="comment-content">
                    <?php comment_text(); ?>
                </div><!-- .comment-content -->

                <div class="reply">
                    <a class="btn btn-small edit-link" href="<?php echo get_edit_comment_link(); ?>"><?php _e('Edit', 'wpbootstrap') ?></a>
                    <?php comment_reply_link(array_merge($args, array('reply_text' => '<span class="btn btn-small">' . __('Reply', 'wpbootstrap') . '</span>', 'after' => '', 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
                </div><!-- .reply -->

                <?php if (empty($args['has_children'])): ?>
                </div> <!-- .media-body -->
            <?php endif; ?>

            <?php
        }

        function end_el(&$output, $comment, $depth = 0, $args = array()) {
            ?>
        </li> <!-- .media -->
        <?php
    }

}

// Changes the default comment form markup
if (!function_exists('wpbootstrap_comment_form')) {

    function wpbootstrap_comment_form($defaults) {
        $defaults['comment_field'] = '<p class="comment-form-comment"><label for="comment">' . _x('Comment', 'noun') . '</label><textarea id="comment" class="' . wpbootstrap_get_content_width() . '" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>';
        $defaults['comment_notes_after'] = '<p class="form-allowed-tags">' . sprintf(__('You may use these ','wpbootstrap').'<abbr title="HyperText Markup Language">HTML</abbr>'.__(' tags and attributes: ','wpbootstrap').'%s', '<pre>' . allowed_tags() . '</pre>') . '</p>';
        return $defaults;
    }

    add_filter('comment_form_defaults', 'wpbootstrap_comment_form');
}

// Changes the default password protection form markup
if (!function_exists('wpbootstrap_password_form')) {

    function wpbootstrap_password_form() {
        global $post;
        $label = 'pwbox-' . ( empty($post->ID) ? rand() : $post->ID );
        $form = '<form class="protected-post-form form-inline" action="' . get_option('siteurl') . '/wp-login.php?action=postpass" method="post"> <p class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>' . __('This post is password protected.', 'wpbootstrap') . '</strong> ' . __("To view it please enter your password below.", 'wpbootstrap') . '</p> <p><label for="' . $label . '">' . __("Password:", 'wpbootstrap') . ' <input type="password" placeholder="Password" name="post_password" id="' . $label . '" /></label> <button type="submit" class="btn"/>' . __('Submit', 'wpbootstrap') . '</button></p></form>';
        return $form;
    }

    add_filter('the_password_form', 'wpbootstrap_password_form');
}

// removes invalid rel="category tag" attribute from the links
if (!function_exists('wpbootstrap_remove_category_rel')) {

    function wpbootstrap_remove_category_rel($link) {
        $link = str_replace('rel="category tag"', "", $link);
        return $link;
    }

    add_filter('the_category', 'wpbootstrap_remove_category_rel');
}

// Enqueue styles and scripts
if (!function_exists('wpbootstrap_register_scripts')) {

    function wpbootstrap_register_scripts() {
        if (!is_admin()) {

            // Deregister scripts
            wp_deregister_script('jquery');
            wp_deregister_script('wpbootstrap_user_scripts_js');

            // Deregister styles
            wp_deregister_style('wpbootstrap_bootstrap_main_css');
            wp_deregister_style('wpbootstrap_bootstrap_responsive_css');

            // Register Twitter Bootstrap CSS files
            wp_register_style('wpbootstrap_bootstrap_main_css', get_template_directory_uri() . '/bootstrap/css/bootstrap.min.css', false, null);
            wp_register_style('wpbootstrap_bootstrap_responsive_css', get_template_directory_uri() . '/bootstrap/css/bootstrap-responsive.min.css', array('wpbootstrap_bootstrap_main_css'), null);

            // Register Twitter Bootstrap JS
            wp_register_script('wpbootstrap_bootstrap_js', get_template_directory_uri() . '/bootstrap/js/bootstrap.min.js', array('jquery'), null, true);

            // Load style.css from the parent theme
            if (is_child_theme()) {
                wp_deregister_style('wpbootstrap_child_css');
                wp_register_style('wpbootstrap_child_css', get_stylesheet_uri(), false, null);
                wp_enqueue_style('wpbootstrap_child_css');
            }

            // Register the jQuery library from Google CDN or local copy if Google CDN fails
            $protocol = is_ssl() ? 'https' : 'http'; // define the correct protocol
            $jquery_url = $protocol . '://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js'; // jQuery from Google CDN repository

            $response = wp_remote_head($jquery_url, array('timeout' => 5));
            if (!(!is_wp_error($response) && in_array(wp_remote_retrieve_response_code($response), array(200, 301, 302)))) {
                $jquery_url = get_template_directory_uri() . "/js/jquery-1.8.3.min.js"; // fallback for local copy if Google CDN repository is not available
            }
            wp_register_script('jquery', $jquery_url, array(), null, true);

            // Enqueue the jQuery library
            wp_enqueue_script('jquery');
            if (is_single() && comments_open() && get_option('thread_comments')) {
                wp_enqueue_script('comment-reply');
            }

            // Enqueue Twitter Bootstrap CSS files
            wp_enqueue_style('wpbootstrap_bootstrap_main_css');
            wp_enqueue_style('wpbootstrap_bootstrap_responsive_css');

            if ( is_rtl() ) {
                //Add support for rtl languages, CSS on front end
                wp_register_style('wpbootstrap_bootstrap_rtl_css', get_template_directory_uri() . '/bootstrap_rtl.css', array('wpbootstrap_bootstrap_responsive_css'), null);
                wp_enqueue_style('wpbootstrap_bootstrap_rtl_css');
            }

            // Eneuqueu Twitter Bootstrap JS
            wp_enqueue_script('wpbootstrap_bootstrap_js');

            // Eneuqueu user sctips
            wp_enqueue_script('wpbootstrap_user_scripts_js');
        }
    }

    add_action('wp_enqueue_scripts', 'wpbootstrap_register_scripts');
}

// Enqueue custom CSS for theme options
if (!function_exists('wpbootstrap_register_options_scripts')) {

    function wpbootstrap_register_options_scripts() {
        wp_register_style('wpbootstrap_options_css', get_bloginfo('template_directory') . '/theme-options/css/optionsframework-custom.css');
        wp_register_style('wpbootstrap_alerts_css', get_bloginfo('template_directory') . '/theme-options/css/bootstrap-alerts.css');
        wp_enqueue_style('wpbootstrap_options_css');
        wp_enqueue_style('wpbootstrap_alerts_css');
        wp_enqueue_style('wp-pointer');
        wp_enqueue_script('wp-pointer');
    }

    add_action('admin_enqueue_scripts', 'wpbootstrap_register_options_scripts');
}

// WooCommerce support
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

if (!(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)) {

//EMERSON: Logic on remembering saved tabs.
//Don't run on autosave

//Get user editor settings in user meta table
//Multisite compatibility

	//Get active tab settings saved by the theme
	if (isset($_GET['post'])) {
		$edited_id=$_GET['post'];
		$editor_active_tab_theme_loaded = get_post_meta($edited_id, '_wpbt_active_tab',TRUE);
		$editor_visual_editor_status = get_post_meta($edited_id, 'visual_mode_editor',TRUE);

		//Use stylesheet
		$themename = get_option( 'stylesheet' );
		$themename = preg_replace("/\W/", "_", strtolower($themename) );

		$editor_disable_visual_editor_option=get_option($themename);
		$posttype_processed=get_post_type($edited_id);

		if (isset($editor_disable_visual_editor_option["post_type_editor_no_highlighting"][$posttype_processed])) {
		$editor_disable_visual_editor_option_status=$editor_disable_visual_editor_option["post_type_editor_no_highlighting"][$posttype_processed];
		}

		//Syntax is loaded,load text editor to be activated to syntax by CodeMirror
		if ($editor_active_tab_theme_loaded=='2') {

			add_filter('wp_default_editor', create_function('', 'return "html";'));

		//TinyMCE is loaded, load tinymce
		} elseif ($editor_active_tab_theme_loaded=='1') {

            //When user disable tinyMCE, check if its status otherwise load in text editor
            if ($editor_visual_editor_status==1) {

                 //Disabled, load text editor instead
                 add_filter('wp_default_editor', create_function('', 'return "html";'));

            } elseif (isset($editor_disable_visual_editor_option_status)) {

                 if ($editor_disable_visual_editor_option_status==1) {

					 //Disabled, load text editor instead
					 add_filter('wp_default_editor', create_function('', 'return "html";'));

                 } else {

					//Load tinyMCE since its not disable
					add_filter('wp_default_editor', create_function('', 'return "tinymce";'));
                }

            } else {
			     //Load tinyMCE since its not disable
                 add_filter('wp_default_editor', create_function('', 'return "tinymce";'));

			}

		//Text editor is loaded; load text editor
		} elseif (($editor_active_tab_theme_loaded=='0')) {

			add_filter('wp_default_editor', create_function('', 'return "html";'));

		}
	}

}