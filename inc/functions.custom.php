<?php
/*
 * Author : trBookmark
 * Settings for Custom post type.
 */

/*
***********************************
カスタム投稿タイプ個別設定
***********************************
*/

/*
 * カスタム投稿タイプのカスタムフィールドを設定
 */
global $custom_fields;
$custom_fields = [
  'portfolio' => [
    'portfolio-caption' => 'キャプション',
    'portfolio-url' => 'サイトurl',
  ],
];

/*
 * カスタム投稿タイプを設定
 * 呼び出しは 'functions.php' の最後で実行
 */
function register_custom_posttypes() {
  register_post_type(
    'portfolio', // カスタム投稿タイプ名=テンプレートファイル名
    [
      'labels' => [
        'name' => 'portfolio記事', //ダッシュボードに表示される名前
        'add_new_item' => 'portfolio記事追加', // 新規追加画面に表示される名前
        'edit_item' => 'portfolio記事編集', // 編集画面に表示される名前
      ],
      'public' => true, // ダッシュボードに表示する
      'hierarchical' => false, // 階層型にする
      'has_archive' => true, // アーカイブ（一覧表示機能）
      'show_in_rest' => false, //ブロックエディター
      'taxonomies' => ['category', 'post_tag'],
      'supports' => [ // カスタム投稿ページに表示される項目
        'title', // タイトル
        'editor', // 本文
        'excerpt', // 抜粋
        'custom-fields', // カスタムフィールド
        'thumbnail', // アイキャッチ画像
        'revisions',
        'page-attributes',
      ],
      'menu_position' => 5, // ダッシュボードで投稿の下に表示
      'menu_icon' => 'dashicons-edit', // メニューで使用するアイコン
      'exclude_from_search' => false, // 検索対象から除外するかどうか
      'rewrite' => [ // パーマリンクの編集
        'slug' => 'portfolio',
        'with_front' => false, // 上の階層URLを使うか
      ],
      'register_meta_box_cb' => function () {
        /* add_meta_box(
          string $id,
          string $title,
          callable $callback,
          string|array|WP_Screen $screen = null,
          string $context,
          string $priority,
          array $callback_args = null
        )*/
        add_meta_box('custom-portfolio-publication', 'ポートフォリオ', 'create_portfolio', 'portfolio', 'normal');
      },
    ]
  );
}

/*
 * カスタムフィールドのソート設定
 */
add_action( 'pre_get_posts',
  function ( $query ) {
    if ( !$query->is_main_query() || ( !$orderby = $query->get( 'orderby' ) ) ) return;
    switch( $orderby ) {
      case 'portfolio-caption':
        // キャプション
        $query->set( 'meta_key', 'portfolio-caption' );
        $query->set( 'orderby', 'meta_value' );
        break;
      case 'portfolio-url':
        // サイトurl
        $query->set( 'meta_key', 'portfolio-url' );
        $query->set( 'orderby', 'meta_value' );
        break;
    }
  }
, 1 );


/*
 * 固定カスタムフィールドボックス
 * カスタム投稿タイプごとに必要なカスタムフィールドを設定
 */
function create_portfolio() {
  $now = getdate();
  echo '<div class="post-edit">';
  // キャプション
  echo '<div class="post-edit__inner">';
  create_input_text('portfolio', 'portfolio-caption', true, "作品の1行説明", 80);
  echo '</div>';
  // サイトurl
  echo '<div class="post-edit__inner">';
  create_input_text('portfolio', 'portfolio-url', true, "https//xxxx.com", 5000);
  echo '</div>';

  echo '</div>';

}

/**
 * エディターの不要パーツを隠す
 */

function remove_editor_supports_custom() {
  // ピンバックとトラックバック
  remove_post_type_support( 'portfolio', 'trackbacks' );
  // カスタムフィールド
  remove_post_type_support( 'portfolio', 'custom-fields' );
}

/*
***********************************
カスタム投稿タイプ汎用設定
***********************************
*/

/*
 * カスタム投稿タイプアーカイブページのテンプレート分岐
 */
add_filter('template_include',
  function ($template) {
    global $custom_fields;
    foreach ( $custom_fields as $custom_post_tpye => $value){
      if ( is_archive( $custom_post_tpye )  ) {
          $new_template = locate_template( array( 'templates/'.$custom_post_tpye.'/archive.php' ) );
        if ( '' != $new_template ) {
          return $new_template ;
        }
      }
      if ( is_page( $custom_post_tpye )  ) {
          $new_template = locate_template( array( 'templates/'.$custom_post_tpye.'/single.php' ) );
        if ( '' != $new_template ) {
          return $new_template ;
        }
      }
    }
    return $template;
  }
);

// カスタム投稿タイプのページタイトルを設定
add_filter( 'pre_get_document_title',
  function ( $title ) {
    global $custom_fields;
    foreach ($custom_fields as $custom_post_type => $value){
      if ( is_post_type_archive( $custom_post_type ) ) {
        // カスタム投稿タイプアーカイブ
        return $custom_post_type . ' | ' . get_bloginfo('name');
      } elseif (is_singular( $custom_post_type )){
        // カスタム投稿タイプ個別ページ
        return get_the_title() . ' | ' . $custom_post_type . ' | ' . get_bloginfo('name');
      }
    }
    return $title;
  }
);

/*
 * カスタムフィールドをクイック編集に追加
 */
add_action('quick_edit_custom_box',
  function ($column_name, $post_type) {
    global $custom_fields;
    if (!isset($custom_fields[$post_type])){
      return;
    }

    $column_key = array_search($column_name, $custom_fields[$post_type]);
    if(empty($column_key)){
      return;
    }
    // nonce用フォームパラメータを追加
    static $print_nonce = true;
    if ( $print_nonce ) {
      $print_nonce = false;
      wp_nonce_field('action-' . $column_key, 'nonce-' . $column_key);
    }
    echo '<fieldset class="inline-edit-col-right inline-custom-meta">';
    echo '<div class="inline-edit-col column-'. esc_attr( $column_name ).'">';
    echo '<label class="inline-edit-group">';
    $post_id = get_the_ID();
    $custom_text = get_post_meta( $post_id, $column_key, true );
    echo '<span class="title">'.$column_name.'</span>';
    echo '<input name="'.$column_key.'" value="'. esc_attr( $custom_text ).'" />';
    echo '</label>';
    echo '</div>';
    echo '</fieldset>';
  }
, 10, 2);

/*
 * カスタムフィールドの値を保存
 */
add_action('save_post',
  function ($post_id) {
    if ( !current_user_can( 'edit_post', $post_id ) ) return;
    // カスタムフィールド名のリスト
    global $custom_fields;
    $post_type = get_post_type($post_id);
    // カスタムフィールドの値を保存
    if (empty($post_type) || !isset($custom_fields[$post_type])) return;
    foreach ($custom_fields[$post_type] as $d => $j) {
      if (!isset($_POST['nonce-' . $d]) || !$_POST['nonce-' . $d]) continue;
      if (!check_admin_referer('action-' . $d, 'nonce-' . $d)) continue;
      if (isset($_POST[$d]) && $_POST[$d]) {
        update_post_meta($post_id, $d, $_POST[$d]);
      } else {
        delete_post_meta($post_id, $d, get_post_meta($post_id, $d, true));
      }
    }
  }
);

/*
***********************************
入力フィールドカスタマイズ
***********************************
*/
/*
 * create_input_boolean
 * 真偽値入力フィールド.
 *
 * @param string $post_type
 * @param string $keyname
 */
function create_input_boolean($post_type, $keyname): void
{
  global $post;
  global $custom_fields;
  $jname = $custom_fields[$post_type][$keyname];
  // 保存されているカスタムフィールドの値を取得
  $get_value = get_post_meta($post->ID, $keyname, true);
  // nonceの追加
  wp_nonce_field('action-' . $keyname, 'nonce-' . $keyname);
  // HTMLの出力
  $checked = $get_value == '1' ? ' checked' : '';
  echo '<label><strong><input type="checkbox" name="' . $keyname . '" value="1"' . $checked . '>' . $jname . '</strong>（真偽値）</label>　';
}

/*
 * create_input_date
 * 日付入力フィールド.
 *
 * @param string $post_type
 * @param string $keyname
 * @param bool   $required  必須項目かどうか
 */
function create_input_date($post_type, $keyname, $required): void
{
  global $post;
  global $custom_fields;
  $jname = $custom_fields[$post_type][$keyname];
  // 保存されているカスタムフィールドの値を取得
  if (!empty($post)) {
    $get_value = get_post_meta($post->ID, $keyname, true);
  }
  // nonceの追加
  wp_nonce_field('action-' . $keyname, 'nonce-' . $keyname);
  // HTMLの出力
  $dateTime = new DateTime();

  if (empty($get_value) && $required) {
    $get_value = $dateTime->format('Y-m-d');
  }
  echo '<strong>';

  if ($required) {
    echo '<span class="post-edit__required">必須</span>';
  }
  echo $jname . '</strong>（日付）：<input type="date" name="' . $keyname . '" " value="' . $get_value . '"';

  if ($required) {
    echo ' required';
  }
  echo '>　';
}

/*
 * create_input_text
 * テキスト入力フィールド（一行）.
 *
 * @param string $post_type
 * @param string $keyname
 * @param bool   $required    必須項目かどうか
 * @param string $placeholder
 * @param int    $maxlength
 */
function create_input_text($post_type, $keyname, $required, $placeholder = '', $maxlength = 0): void
{
  global $post;
  global $custom_fields;
  $jname = $custom_fields[$post_type][$keyname];
  // 保存されているカスタムフィールドの値を取得
  if ($post->post_status == 'new' || $post->post_status == 'auto-draft') {
    $get_value = ''; // $placeholder;
  }else{
    $get_value = get_post_meta($post->ID, $keyname, true);
  }
  // nonceの追加
  wp_nonce_field('action-' . $keyname, 'nonce-' . $keyname);
  // HTMLの出力
  echo '<strong>';
  if ($required) {
    echo '<span class="post-edit__required">必須</span>';
  }
  echo $jname . '</strong>（一行）';
  if ($maxlength > 0) {
    echo '（' . $maxlength . '文字まで）';
  }
  echo '：例）'.$placeholder.'<input type="text" name="' . $keyname . '" value="' . $get_value . '"';
  if ($required) {
    echo ' required';
  }
  if ($placeholder) {
    echo ' placeholder="' . $placeholder . '"';
  }
  if ($maxlength > 0) {
    echo ' maxlength="' . $maxlength . '"';
  }
  echo '>　';
}

/*
 * create_input_number.
 * 数値入力フィールド（一行）.
 *
 * @param string $post_type
 * @param string $keyname
 * @param bool   $required
 * @param mixed  $class
 * @param string $placeholder
 * @param int    $max
 * @param int    $min
 */
function create_input_number($post_type, $keyname, $required, $class, $placeholder = 0, $max = 0, $min = 0): void
{
  global $post;
  global $custom_fields;
  $jname = $custom_fields[$post_type][$keyname];
  // 保存されているカスタムフィールドの値を取得
  if (!empty($post)) {
    $get_value = get_post_meta($post->ID, $keyname, true);
  }
  // nonceの追加
  wp_nonce_field('action-' . $keyname, 'nonce-' . $keyname);
  // HTMLの出力
  echo '<strong>';

  if ($required) {
    echo '<span class="post-edit__required">必須</span>';
  }
  echo $jname . '</strong>（数値）：例）'.$placeholder.'<input type="number" name="' . $keyname . '" value="' . $get_value . '"';

  if ($required) {
    echo ' required';
  }

  if ($class) { // numberにはsize属性がないためcssで対処
    echo ' class="' . $class . '"';
  }

  if ($placeholder) {
    echo ' placeholder="' . $placeholder . '"';
  }

  if ($max !== 0) {
    echo ' max="' . $max . '"';
  }

  if ($min !== 0) {
    echo 'min="' . $min . '"';
  }
  echo '>　';
}

/*
 * create_input_textarea
 * テキスト入力フィールド（複数行）.
 *
 * @param string $post_type
 * @param string $keyname
 * @param bool   $required    必須項目かどうか
 * @param string $placeholder
 * @param int    $maxlength
 * @param mixed  $rows
 * @param mixed  $cols
 */
function create_input_textarea($post_type, $keyname, $required, $placeholder = '', $maxlength = 0, $rows = 0, $cols = 0): void
{
  global $post;
  global $custom_fields;
  $jname = $custom_fields[$post_type][$keyname];
  // 保存されているカスタムフィールドの値を取得
  if ($post->post_status == 'new' || $post->post_status == 'auto-draft') {
    $get_value = ''; // $placeholder;
  }else{
    $get_value = get_post_meta($post->ID, $keyname, true);
  }
  // nonceの追加
  wp_nonce_field('action-' . $keyname, 'nonce-' . $keyname);
  // HTMLの出力
  echo '<strong>';

  if ($required) {
    echo '<span class="post-edit__required">必須</span>';
  }
  echo $jname . '</strong>（複数行）';
  if ($maxlength > 0) {
    echo '（' . $maxlength . '文字まで）';
  }
  echo '：'.$placeholder.'<br>';
  echo '<textarea name="' . $keyname . '"';

  if ($required) {
    echo ' required';
  }

  if ($placeholder) {
    echo ' placeholder="' . $placeholder . '"';
  }

  if ($maxlength > 0) {
    echo ' maxlength="' . $maxlength . '"';
  }

  if ($rows) {
    echo 'rows="' . $rows . '"';
  }

  if ($cols) {
    echo 'cols="' . $cols . '"';
  }
  echo '>' . $get_value . '</textarea>　';
}

/*
 * create_input_select
 * 一択（select）入力フィールド.
 *
 * @param string $post_type
 * @param string $keyname
 * @param array  $data      selectの値
 * @param bool   $required  必須項目かどうか
 */
function create_input_select($post_type, $keyname, $data, $required): void
{
  global $post;
  global $custom_fields;
  $jname = $custom_fields[$post_type][$keyname];
  // 保存されているカスタムフィールドの値を取得
  $get_value = get_post_meta($post->ID, $keyname, true);
  // nonceの追加
  wp_nonce_field('action-' . $keyname, 'nonce-' . $keyname);
  // HTMLの出力
  echo '<strong>';

  if ($required) {
    echo '<span class="post-edit__required">必須</span>';
  }
  echo $jname . '</strong>（一択）：<select name="' . $keyname . '"';

  if ($required) {
    echo ' required';
  }
  echo '">';
  // echo '<option value=""></option>';
  foreach ($data as $d) {
    $selected = $d == $get_value ? ' selected' : '';
    echo '<option value="' . $d . '"' . $selected . '>' . $d . '</option>';
  }
  echo '</select>　';
}

function create_input_radio($post_type, $keyname, $data, $required): void
{
  global $post;
  global $custom_fields;
  $jname = $custom_fields[$post_type][$keyname];
  // 保存されているカスタムフィールドの値を取得
  $get_vals = get_post_meta($post->ID, $keyname, true);
  $get_value = $get_vals ? $get_vals : [];
  // nonceの追加
  wp_nonce_field('action-' . $keyname, 'nonce-' . $keyname);
  // HTMLの出力
  echo '<strong>';

  if ($required) {
    echo '<span class="post-edit__required">必須</span>';
  }
  echo $jname . '</strong>（一択）：';

  foreach ($data as $d) {
    $checked = in_array($d, $get_value) ? ' checked' : '';
    echo '<label><input type="radio" name="' . $keyname . '[]" value="' . $d . '"' . $checked;

    if ($required) {
      echo ' required';
      $required = false;
    }
    echo '>' . $d . '　</label>';
  }
}

/*
 * create_input_checkbox
 * 複数選択（checkbox）入力フィールド.
 *
 * @param string $post_type
 * @param string $keyname
 * @param array  $data      checkboxの値
 * @param bool   $required  必須項目かどうか
 */
function create_input_checkbox($post_type, $keyname, $data, $required): void
{
  global $post;
  global $custom_fields;
  $jname = $custom_fields[$post_type][$keyname];
  // 保存されているカスタムフィールドの値を取得
  $get_vals = get_post_meta($post->ID, $keyname, true);
  $get_value = $get_vals ? $get_vals : [];
  // nonceの追加
  wp_nonce_field('action-' . $keyname, 'nonce-' . $keyname);
  // HTMLの出力
  echo '<strong>';

  if ($required) {
    echo '<span class="post-edit__required">必須</span>';
  }
  echo $jname . '</strong>（複数選択）：';

  foreach ($data as $d) {
    $checked = in_array($d, $get_value) ? ' checked' : '';
    echo '<label><input type="checkbox" name="' . $keyname . '[]" value="' . $d . '"' . $checked;

    // if ($required) {
    //   echo ' required';
    //   $required = false;
    // }
    echo '>' . $d . '　</label>';
  }
}
