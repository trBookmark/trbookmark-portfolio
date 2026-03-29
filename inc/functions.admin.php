<?php
/*
 * Author : trBookmark
 * Settings for the admin.
 */

global $custom_fields;

/******************************
  管理メニューのカスタマイズ
*******************************/
// 管理画面用CSS
add_action('admin_enqueue_scripts',
  function () {
      wp_enqueue_style(
          'admin-css',
          get_template_directory_uri() . '/css/style_admin.min.css',
          array(),
          false,
          'all',
      );
  }
);

/*
 * アドミンバー（上部）のカスタマイズ.
 */
add_action('admin_bar_menu',
  function () {
    // アドミンバーのグローバル変数
    global $wp_admin_bar;

    //管理者以外は非表示にする
    if (!current_user_can('administrator')) {
      $wp_admin_bar->remove_menu('wp-logo'); // WordPressロゴグループ
      $wp_admin_bar->remove_menu('view-site'); // サイトを表示
      $wp_admin_bar->remove_menu('comments'); // コメントグループ
      $wp_admin_bar->remove_menu('updates'); // 更新グループ
      // $wp_admin_bar->remove_menu('new-post'); // 新規投稿
      // $wp_admin_bar->remove_menu('new-page'); // 新規固定ページ
      $wp_admin_bar->remove_menu('new-media'); // 新規メディア
      $wp_admin_bar->remove_menu('new-user'); // 新規ユーザー
      $wp_admin_bar->remove_menu('edit-profile'); // プロフィールを編集
    }

  // メニューを追加する例
  /*
  $title = sprintf(
      '<span class="ab-label">%s</span>',
      '管理' //親メニューラベル
  );
  $wp_admin_bar->add_menu(array(
    'id'    => 'dashboard_menu',
    'meta'  => array(),
    'title' => $title
  ));

  $wp_admin_bar->add_menu(array(
    'parent' => 'dashboard_menu', // 親メニューID
    'id'     => 'dashboard_menu-top', // 子メニューID
    'meta'   => array(),
    'title'  => 'サイトトップ', // ラベル
    'href'   => home_url('/'), // ページURL
          'meta'  => [
              'target' => '_blank'
          ],
  ));
  */
  },
  999
);

/*
 * Gutenberg を無効化し、Classic Editor を使用
 */
add_filter('use_block_editor_for_post', '__return_false', 10);

/*
 * サイドメニュー（左）のカスタマイズ.
 */
add_action('admin_menu',
  function () {
    // // 例）投稿
    // remove_menu_page('edit.php');

    if (!current_user_can('administrator')) {
      //管理者以外は非表示にする
      remove_menu_page('edit-comments.php'); // コメントメニュー
      remove_menu_page('profile.php'); // プロフィール
      // remove_menu_page('edit.php?post_type=page'); // 固定ページ
      remove_menu_page('tools.php'); // ツール
    }
  }
);

/*
 * サイドメニュー（左）項目の表示順序をカスタマイズ.
 * 先に custom_menu_order フィルターに真値が渡される必要あり.
 */
add_filter( 'custom_menu_order', 'change_adminmenu_order' );
add_filter( 'menu_order', 'change_adminmenu_order' );
function change_adminmenu_order( $menu_order ){
  if( !$menu_order ) return true;
  global $custom_fields;
  $custom_order = [
    'index.php',
    'edit.php?post_type=top_page',  // トップページ
    'separator1',
  ];
  $custom_order = array_merge($custom_order, array_keys($custom_fields), [
    'separator2',
    'edit.php', // 投稿
    'edit.php?post_type=page', // 固定ページ
    'separator3',
    // 'users.php', // ユーザー
    'upload.php', // メディア
    // 'edit-comments.php', // コメント
    // 'themes.php', // 外観
    // 'plugins.php', // プラグイン
    // 'tools.php', // ツール
    // 'options-general.php', // 設定
    // 'siteguard',
  ]);
  return $custom_order;
}

/******************************
  タグ・カテゴリ管理画面カスタマイズ
*******************************/

// category, tag 一覧画面テーブル2列目にIDカラム追加
add_filter('manage_edit-category_columns', 'add_term_columns');
add_filter('manage_edit-post_tag_columns', 'add_term_columns');
function add_term_columns($columns)
{
  $columns['ID'] = 'ID';
  return $columns;
}
// 追加されたカラムへの表示内容
add_filter('manage_category_custom_column', 'add_term_custom_column', 10, 3);
add_filter('manage_post_tag_custom_column', 'add_term_custom_column', 10, 3);
function add_term_custom_column($content, $column_name, $term_id)
{
  echo esc_html($term_id);
}
// 追加されたカラムをソートを可能に
add_filter( 'manage_edit-category_sortable_columns', 'add_term_sortable_columns' );
add_filter( 'manage_edit-post_tag_sortable_columns', 'add_term_sortable_columns' );
function add_term_sortable_columns( $columns ) {
  $columns['id'] = 'ID';
  return $columns;
}

/******************************
  post一覧画面テーブルのカスタマイズ
*******************************/

// // 投稿のクイック編集を削除したい場合
// add_filter('post_row_actions',
//   function($actions){
//     unset($actions['inline hide-if-no-js']);
//     return $actions;
//   }
// );

// // 固定ページのクイック編集を削除
// add_filter('page_row_actions',
//   function($actions){
//     unset($actions['inline hide-if-no-js']);
//     return $actions;
//   }
// );

// // HTMLタグ内でショートコードを使えるようにする
// add_filter( 'wp_kses_allowed_html',
//   function ( $tags, $context ) {
//     $tags['img']['src'] = true;
//     $tags['a']['href'] = true;
//     return $tags;
//   }
// , 10, 2 );

// // コメントにカスタムフィールド追加
// add_action('comment_form_field_comment',
//   function ($defaults) {
//     $add_content = '';
//     //入力項目を追加する
//     $pid = isset($_GET['p_id']) ? $_GET['p_id'] : get_query_var('p_id');
//     $add_content .= '<input id="p_id" name="p_id" type="hidden" value="'.esc_attr($pid).'">';
//     //選択項目を追加する
//     return $add_content . $defaults;
//   }
// );

// //追加した項目をカスタムフィールドとしてコメント投稿時に保存する
// add_action('comment_post', 'save_custom_comment_field');
// add_action('edit_comment', 'save_custom_comment_field');
// function save_custom_comment_field($comment_id) {
//   if(!$comment = get_comment($comment_id)) return false;
//   //p_idの値の保存
//   $custom_key_pid = 'p_id';
//   $pid = esc_attr($_POST[$custom_key_pid]);
//   if('' == get_comment_meta($comment_id, $custom_key_pid)) {
//     add_comment_meta($comment_id, $custom_key_pid, $pid, true);
//   } else if($pid != get_comment_meta( $comment_id, $custom_key_pid)) {
//     update_comment_meta($comment_id, $custom_key_pid, $pid);
//   } else if('' == $pid) {
//     delete_comment_meta($comment_id, $custom_key_pid);
//   }
//   return false;
// }

// 表示項目（列）をカスタマイズ
// apply_filters( “manage_{$post_type}_posts_columns”, string[] $posts_columns )
// post
add_filter( 'manage_post_posts_columns',
  function( $columns ) {
    // 一旦デフォルトのカラムを削除
    unset($columns['date']);
    unset($columns['author']);
    unset($columns['comments']);

    // カラムを追加、削除したデフォルトのうち必要なものを戻す
    $columns['ID'] = 'ID';
    $columns['slug'] = __('Slug');
    // $columns['modified'] = __( 'Last updated' );
    $columns['modified'] = '更新日時'; // 長いので変更
    $columns['date'] = __('Date');
    $columns['author'] = __('Author');
    // $columns['content'] = '本文';
    $columns['excerpt'] = '概要';
    $columns['thumbnail'] = 'アイキャッチ';

    // // 表示モードによって切り替える場合
    // global $mode;
    // if ( 'excerpt' === $mode ) { // 抜粋表示 excerpt
    // } else { // リストビュー list
    // }

    return $columns;
  }
);
// page
add_filter( 'manage_page_posts_columns',
  function ( $columns ) {
    // 一旦デフォルトのカラムを削除
    unset($columns['date']);
    unset($columns['author']);
    unset($columns['comments']);

    // カラムを追加、削除したデフォルトのうち必要なものを戻す
    $columns['ID'] = 'ID';
    $columns['slug'] = __('Slug');
    $columns['modified'] = '更新日時'; // 長いので変更
    $columns['date'] = __('Date');
    $columns['author'] = __('Author');

    return $columns;
  }
);
// カスタム投稿タイプ
foreach($custom_fields as $post_type => $fields){
  $hook_name = 'manage_' . $post_type . '_posts_columns';
  add_filter( $hook_name ,
    function ( $columns ) use ($post_type) {
      $columns['ID'] = 'ID';
      $columns['slug'] = __('Slug');
      $columns['modified'] = '更新日時';
      $columns['excerpt'] = '概要';
      global $custom_fields;
        foreach ($custom_fields[$post_type] as $field_key => $value) {
          $columns[$field_key] = $value;
        }
      return $columns;
    }
  );
}

// 追加した列の内容を設定（カスタムフィールドの値も表示）
// do_action( “manage_{$post->post_type}_posts_custom_column”, string $column_name, int $post_id )
add_action( 'manage_posts_custom_column', 'add_column_content', 10, 2 );
add_action( 'manage_pages_custom_column', 'add_column_content', 10, 2 );
function add_column_content( $column_name, $post_id ) {
  global $custom_fields;
  switch( $column_name ) {
    case 'modified':
      echo esc_html( the_modified_date( 'Y-m-d H:i' ) ) ;
      return;
    case 'thumbnail':
      // アイキャッチ
      $thumb = get_the_post_thumbnail($post_id, array(50,50), 'thumbnail');
      echo ($thumb) ? $thumb : __('None');
      return;
    case 'ID':
      // ID
      echo esc_html($post_id);
      return;
    case 'slug':
      echo esc_html(get_post_field('post_name', $post_id, 'display'));
      return;
    case 'excerpt':
      //概要
      echo (has_excerpt()) ? the_excerpt() : __('None');
      return;
    case 'content':
      // 本文
      echo the_content();
      return;
    }
    $stitle = get_post_meta($post_id, $column_name, true);
    echo (isset($stitle) && $stitle) ? esc_html($stitle) : __('None');
}

// ソート可能にする（post, page）
// apply_filters( “manage_{$this->screen->id}_sortable_columns”, array $sortable_columns )
add_filter( 'manage_edit-post_sortable_columns', 'sort_admin_columns', 10, 1 );
add_filter( 'manage_edit-page_sortable_columns', 'sort_admin_columns', 10, 1 );
function sort_admin_columns ( $sortable_columns ) {
  $sortable_columns['modified'] = array( 'modified', true );
  $sortable_columns['ID'] = array( 'ID', true );
  $sortable_columns['slug'] = array( 'slug', true );
  $sortable_columns['date'] = array( 'date', true );
  return $sortable_columns;
}
// ソート可能にする（カスタム投稿タイプ）
$hook_name = 'manage_edit-' . $post_type . '_sortable_columns';
add_filter( $hook_name ,
  function ( $sortable_columns )  use ($post_type) {
    $sortable_columns['modified'] = array( 'modified', true );
    $sortable_columns['ID'] = array( 'ID', true );
    $sortable_columns['slug'] = array( 'slug', true );
    $sortable_columns['date'] = array( 'date', true );
    global $custom_fields;
    foreach ($custom_fields[$post_type] as $field_key => $value) {
      $sortable_columns[$field_key] = array( $value, true );
    }
    return $sortable_columns;
  }
, 10, 1 );

// 追加した列幅を調整するテスト
// 最上段の外部CSSに書くのが吉
// add_action( 'admin_print_styles',
//   function () {
//     echo
//     '<style>
//     CSS
//     </style>';
//   }
// );

// タームで絞り込む
add_action( 'restrict_manage_posts',
  function () {
    echo '<select name="tag">';
    echo '<option value="">タグ指定なし</option>';
    $terms = get_terms(
      ['post_tag'],
      []
    );
    foreach ($terms as $term) {
      echo '<option value="'.$term->slug.'">'.$term->name.'</option>';
    }
    echo '</select>';
  }
);


/***********************************
投稿画面カスタマイズ
************************************/

/*
 * タクソノミー表示をセレクトボックスに変更.
 *
 * @param [type] $post
 * @param [type] $box
 */
function meta_box_change_select($post, $box): void
{
  $defaults = ['taxonomy' => 'category'];

  if (!isset($box['args']) || !is_array($box['args'])) {
    $args = [];
  } else {
    $args = $box['args'];
  }
  $r = wp_parse_args($args, $defaults);
  $tax_name = esc_attr($r['taxonomy']);
  $terms = get_terms($tax_name, ['get' => 'all', 'orderby' => 'id', 'order' => 'ASC']);    //並び順は自由に
  $select_terms = get_the_terms($post->ID, $tax_name);
  $selected_id = '';

  if ($select_terms) {
    $selected_id = array_shift($select_terms)->term_id;
  }
  echo '<select id="cat_name" name="tax_input[' . $tax_name . '][]">';

  foreach ($terms as $term) {
    $selected = $selected_id == $term->term_id ? 'selected="selected"' : '';
    echo '<option value="' . $term->term_id . '" ' . $selected . '>' . $term->name . '</option>';
  }
  echo '</select>';
}

/*
 * タクソノミー表示をラジオボタンに変更.
 *
 * @param [type] $post
 * @param [type] $box
 */
function meta_box_change_radio($post, $box): void
{
  $defaults = ['taxonomy' => 'category'];

  if (!isset($box['args']) || !is_array($box['args'])) {
    $args = [];
  } else {
    $args = $box['args'];
  }
  $r = wp_parse_args($args, $defaults);
  $tax_name = esc_attr($r['taxonomy']);
  $taxonomy = get_taxonomy($r['taxonomy']);
  echo '<div id="' . $tax_name . '-all" class="tabs-panel">';
  $name = ($tax_name == 'category') ? 'post_category' : 'tax_input[' . $tax_name . ']';
  echo "<input type='hidden' name='{$name}[]' value='0' />";
  $terms = get_terms($tax_name, ['get' => 'all', 'orderby' => 'id', 'order' => 'ASC']);   //並び順は自由に
  $select_terms = get_the_terms($post->ID, $tax_name);
  $selected_id = '';

  if ($select_terms) {
    $selected_id = array_shift($select_terms)->term_id;
  }
  echo '<ul id="' . $tax_name . 'checklist" data-wp-lists="list:.' . $tax_name . '" class="categorychecklist form-no-clear">';

  foreach ($terms as $term) {
    $id = "popular-{$tax_name}-{$term->term_id}";
    $selected = $selected_id == $term->term_id ? 'checked="checked"' : '';
    echo '<li id="' . $id . '" class="popular-category">';
    echo '<label class="selectit">';
    echo '<input id="in-' . $id . '" type="radio" ' . $selected . ' value="' . (int) $term->term_id . '" name="tax_input[' . $tax_name . '][]"/>';
    echo esc_html(apply_filters('the_category', $term->name, '', ''));
    echo '</label>';
    echo '</li>';
  }
  echo '</ul>';
  echo '</div>';
}

/*
 * playceholderの変更
 * apply_filters( 'enter_title_here', string $text, WP_Post $post ).
 * タイトルフィールドのプレースホルダーテキストをフィルタリングします.
 */
// add_filter('enter_title_here',
//   function ($title){
//     if(get_post_type() == '特定の post_type'){
//       return 'わかりやすいタイトルをつける';
//     }
//     return $title;
//   }
// );

/*
 * 本文のプレースホルダー テキストをフィルター処理.
 */
// add_filter( 'write_your_story',
//   function ( $text, $post ) {
//     if(get_post_type() == '特定の post_type'){
//       return 'テスト表示';
//     }
//     return $text;
//   }
// , 10, 2 );

/*
 * 記事編集画面上のタグ挿入ボタン（クイックタグ）カスタマイズ.
 */
add_filter('quicktags_settings', function ($qtInit) {
    $qtInit['buttons'] = 'link,block,strong,del,ins,ul,ol,li,code,fullscreen';
    return $qtInit;
});
add_action('admin_print_footer_scripts', 'appthemes_add_quicktags', 100);
function appthemes_add_quicktags()
{
?>
<script type="text/javascript">
  QTags.addButton('p', 'p', '<p>', '</p>');
  QTags.addButton('u', 'u', '<u>', '</u>');
  QTags.addButton('comment', 'comment', '<!-- ', ' -->');
</script>
<?php
}

/**
 * エディターの不要パーツを隠す
 * 一般用
 */
function remove_editor_supports() {
  // // 投稿タイトル
  // remove_post_type_support( 'post', 'title' );
  // // コンテンツ
  // remove_post_type_support( 'post', 'editor' );
  // // 投稿者設定
  // remove_post_type_support( 'post', 'author' );
  // // アイキャッチ
  // remove_post_type_support( 'post', 'thumbnail' );
  // 抜粋
  // remove_post_type_support( 'post', 'excerpt' );
  // ピンバックとトラックバック
  remove_post_type_support( 'post', 'trackbacks' );
  // カスタムフィールド
  // remove_post_type_support( 'post', 'custom-fields' );
  // // コメント
  // remove_post_type_support( 'post', 'comments' );
  // // リビジョン
  // remove_post_type_support( 'post', 'revisions' );
  // // ページ属性
  // remove_post_type_support( 'page', 'page-attributes' );
  // // フォーマット
  // remove_post_type_support( 'post', 'post-formats' );
  // // カテゴリー
  // unregister_taxonomy_for_object_type( 'category', 'post' );
  // // タグ
  // unregister_taxonomy_for_object_type( 'post_tag', 'post' );
}
