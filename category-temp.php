<?php
/*
Template Name: Custom Template for Redirected Category
*/
get_header();

if (have_posts()):
    while (have_posts()):
        the_post();
?>

<div class="container">
    <div class="row">
        <div id="postsContainer" class="post-temp-category">
            <div class="single-post-by-category-with-download">
                <div class="category-temp col-md-6">
                    <div class="temp-text">
                        <p>Template/<span><?php the_title(); ?></span></p>
                    </div>

                    <?php if (has_post_thumbnail()): ?>
                        <div class="single-post-thumbnail">
                            <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title_attribute(); ?>">
                        </div>
                    <?php endif; ?>

                </div>
                <div class="category-temp col-md-6">
                    <h2><?php the_title(); ?></h2>
                    <div class="post-content">
                        <?php the_content(); ?>

                        <?php
                        $excel_url = get_post_meta(get_the_ID(), 'excel_url', true);
                        if ($excel_url) {
                            echo '<a class="download-clss popmake-5994 five-most-btnn" href="' . esc_url($excel_url) . '">Download</a>';
                        }
                        ?> 
                    </div>
                </div>
            </div>
        </div>

        <div class="related-posts">
            <h3>Related Posts</h3>
            <div class="row">
                <?php
                $related_args = array(
                    'post_type' => 'post',
                    'posts_per_page' => 3,
                    'post__not_in' => array(get_the_ID()),
                    'category__in' => wp_get_post_categories(get_the_ID()),
                );

                $related_query = new WP_Query($related_args);

                if ($related_query->have_posts()):
                    while ($related_query->have_posts()):
                        $related_query->the_post();
                ?>
                <div class="col-md-4">
                    <?php if (has_post_thumbnail()): ?>
                        <div class="related-post-thumbnail">
                            <a href="<?php the_permalink(); ?>">
                                <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title_attribute(); ?>">
                            </a>
                            <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                            <p><?php the_excerpt(); ?></p>
                            <a href="<?php the_permalink(); ?>" class="read-more">Read Template</a>
                        </div>
                    <?php endif; ?>
                </div>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else:
                    echo 'No related posts found';
                endif;
                ?>
            </div>
        </div>
    </div>
</div>

<?php
    endwhile;
else:
    echo 'No posts found';
endif;

get_footer();
?>
