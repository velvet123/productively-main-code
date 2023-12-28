<?php /* Template Name: Display Blog Posts */ 


get_header();
?>
<div class="container blog-cls-cont">
 <h1 class="latest-news-text">Latest News</h1>
<div class="category-tabs">
<?php
   $category_ids = array(11, 12, 13, 14, 15, 18); // Specify the category IDs you want to display

   foreach ($category_ids as $category_id) {
       $category = get_category($category_id);
       if ($category) {
           $active_class = ($category_id === 11) ? ' active' : ''; // Check if category ID is 11
           echo '<button class="category-tab' . $active_class . '" data-category-id="' . $category->term_id . '">' . $category->name . '</button>';
       }
   }
   ?>

</div>

<div class="category-content">
   
    <div class="single-post-left">
        <!-- One post will be displayed here -->
    </div>
    <div class="related-posts-right">
        <!-- Related posts will be displayed here -->
    </div>

 
</div>

<div class="blog-article-read-more-btn">
    <a href="https://web.ntfinfotech.com/producttively/blog-article/">
    <button type="button">Read Blog Article</button>
    </a>
   </div>


<!-- Code for Post Slider -->

<div class="favorite-post-main-section">
    <div class="favorite-inner-section">
        <h1>Fan-Favorite Posts</h1>
 
      <?php  echo do_shortcode('[psac_post_carousel category="16" slide_show="3" dots="false" arrows="false" autoplay="true" autoplay_interval="1000" speed="800"]'); ?>

        
   </div>
</div>
<!-- Code for Post Slider End-->




<!-- Code For Recent Posts -->
<div class="recent-post-main-section" id="recent-post-cls-main">
<h1>Recent Posts</h1>


<?php echo do_shortcode('[pt_view id="d28e87fmjb"]');?>
</div>
</div>



<div class="powerful-time-traking-cls">
<div class="powerful-text-cls">
    <h1 class="powerful-text-track">Start Leveraging Powerful<br>
Time Tracking Data To Boost<br>Productivity And Improve The<br> 
Way You Do Business</h1>
<p class="powerful-text-track-inner">All that and more through a time tracking app that will empower you to understand your business better and identify problem areas.</p>
<a href="https://web.ntfinfotech.com/producttively/schedule/"><button class="powerful-btn-track">Book A Free Demo</button></a>
</div>

</div>




<?php the_content(); ?>




<!-- Code End For Recent Posts -->

<?php
get_footer();
?>








