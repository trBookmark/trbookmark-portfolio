<?php
/**
 * Author : trBookmark
 * Template Name: Top Page (Default Template)
 * Template for the Top Page.
 */
get_header('', ['body-class' => 'front_page']); // body に class を付与
global $custom_fields;
?>
  <main class="main">
    <section class="post_sections">
      <div  class="post_sections-inner">
        <h1><? bloginfo('name') ?></h1>
        <ul class="post_sections-list">
        <?php
          foreach($custom_fields as $custom_post_type => $fields){
          $count_posts = wp_count_posts( $custom_post_type ); // 投稿数を取得
          if(property_exists( $count_posts, 'publish' ) && !empty($count_posts->publish)): ?>
          <li><a href="<?php echo get_post_type_archive_link($custom_post_type); ?>"><?php echo $custom_post_type ?></a></li>
        <?php endif; } ?>
        </ul>
      </div>
    </section>
  </main>
<?php get_footer(); ?>
