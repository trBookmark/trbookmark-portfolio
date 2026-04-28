<?php
/**
 * Author : trBookmark
 * Template pars for the navigation.
 */

// 固定ページabout、contactを取得
$page_id = get_page_by_path('about');
$about = $page_id ? get_post( $page_id ) : '';
$page_id = get_page_by_path('contact');
$contact = $page_id ? get_post( $page_id ) : '';

?>
  <header class="header">
    <nav class="nav" aria-label="サイト内メニュー">
      <ul class="nav-list">
        <li class="nav-listitem"><a href="#top"><img src="/images/trBookmark-Icon-150x150.png" width="32" height="32" alt="top" title="<?php echo bloginfo('name'); ?>"></a></li>
        <li class="nav-listitem"><a href="#sites-section">Sites</a></li>
        <?php if (!empty($about -> post_content)): ?><li class="nav-listitem"><a href="#about-section">About</a></li><?php endif; ?>
        <?php if (!empty($contact -> post_content)): ?><li class="nav-listitem"><a href="#contact-section">Contact</a></li><?php endif; ?>
      </ul>
    </nav>
  </header>
