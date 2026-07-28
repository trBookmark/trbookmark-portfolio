# trBookmark WordPress Theme
- **テーマ名**: trBookmark WordPress Theme
- **バージョン**: 1.0.5
- **ライセンス**: GPL-2.0-or-later
- **作者**: trBookmark
- **作者 URI**: https://trbook.site
- **テーマ URI**: https://trbook.site/portfolio

WordPress と PHP のバージョンの指定はありませんが、セキュリティ上 最新バージョンを使用することをお勧めします。以下は動作確認を行った環境です。

- **WordPress バージョン**: 7.0.x
- **PHP バージョン**: 8.3.3

## 概要

trBookmark WordPress Theme は、カスタム投稿タイプを利用するための最低限の機能だけを実装した、ごくシンプルな、ごく普通のクラシックテーマ（ブロックエディタ未対応）です。

将来的な拡張性を考慮し、あえて汎用的なテーマとして構築しました。

実際のページ: https://trbook.site/portfolio

## 特徴

- レスポンシブデザイン対応
- トップページは今後ブログ機能を追加予定
- 複数カスタム投稿タイプ設定対応
- 現時点のメインページは **portfolio**
- About、Contact は固定ページとして自動で追加（内容は自由に編集可能）

## ディレクトリ構成

```
trBookmark/
├── assets/
│   ├── scss/
│   │   └── style_admin.scss
│   │   └── style_form.scss
│   │   └── style.scss
├── css/
│   └── style_admin.css
│   └── style_form.css
│   └── style.css
├── inc/
│   ├── functions.admin.php
│   ├── functions.common.php
│   └── functions.custom.php
├── js/
│   └── main.js
├── templates/
│   ├── portfolio/
│   │   ├── archive.php
│   │   └── nav.php
├── 404.php
├── archive.php
├── footer.php
├── front-page.php
├── functions.php
├── header.php
├── index.php
├── README.md
├── screenshot.png
├── single.php
└── style.css
```

## 注意点
- 1.0 では、sidebar.php、page.php、カスタム投稿タイプ portfolio 用 single.php などは不要なため **未作成**
  - トップページ：各カスタム投稿タイプアーカイブへのリンクのみ
  - single, page：最低限の表示のみ
- SCSS 変数 ```$upload_path``` はアップロードパスに合わせて編集が必要
- コンタクトフォーム：プラグイン [Contact Form 7](https://ja.wordpress.org/plugins/contact-form-7/) のインストールが前提

## 変更履歴

### 1.0.0 - 2026-03-21
- 初回リリース（portfolio のみ実装）

### 1.0.1 - 2026-03-22
- font を woff に変更
- README 加筆

### 1.0.2 - 2026-03-22
- front ページを整える

### 1.0.3 - 2026-03-22
- css バージョン用にファイル更新日を取得

### 1.0.4 - 2026-03-26
- トップページ：カスタム投稿タイプへの投稿がない場合の処理を追加
- function 周りの不要な記述を削除
- タイポ修正

### 1.0.5 - 2026-04-28
- マークアップのエラーを修正
- 不要なタグを削除

## ライセンス

このテーマは [GNU General Public License v2.0 以降](https://www.gnu.org/licenses/gpl-2.0.html) のもとで公開されています。

このテーマは、完全に私の個人利用前提で公開されており、いかなる保証もありません。
