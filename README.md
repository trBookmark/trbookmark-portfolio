# trBookmark Wordpress Theme
- **テーマ名**: trBookmark WordPress Theme
- **バージョン**: 1.0.0
- **ライセンス**: GPL-2.0-or-later
- **作者**: trBookmark
- **作者 URI**: https://trbook.site
- **テーマ URI**: https://trbook.site

WordPress と PHP のバージョンの指定はありませんが、セキュリティ上 最新バージョンを使用することをお勧めします。以下は動作確認を行った環境です。

- **対応 WordPress バージョン**: 6.9.4
- **対応 PHP バージョン**: 8.5.3

## 概要

trBookmark Wordpress Theme は、カスタム投稿タイプを利用するための最低限の機能だけを実装した、ごくシンプルな、ごく普通のクラシックテーマ（ブロックエディタ未対応）です。

この程度の規模で Wordpress を使用する必要はないのですが、今後ポートフォリオに掲載したい内容が増えた際の利便性と、ブログ追加等のサイト自体の拡張を考慮し、これまでに蓄積した情報を汎用性を持たせながらぎゅっとまとめつつ、ポートフォリオに不要な部分を一排除して作成しました。

実際のページ: https://trbook.site/portfolio

## 特徴

- レスポンシブデザイン対応
- 複数カスタム投稿タイプ設定対応
- 現時点でトップページは空（ブログ機能等、後日追加予定）
- 現時点のメインページは **portfolio**
- About、Contact は固定ページとして自動で追加（内容は自由に編集可能）
- コンタクトフォーム：プラグイン [Contact Form 7](https://ja.wordpress.org/plugins/contact-form-7/) のインストールが必要

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

1.0 では、 sidebar.php、page.php、カスタム投稿タイプ portfolio 用 single.php などは不要なため **未作成** です。

## 変更履歴

### 1.0.0 - 2026-03-21
- 初回リリース（portfolio のみ実装）

## ライセンス

このテーマは [GNU General Public License v2.0 以降](https://www.gnu.org/licenses/gpl-2.0.html) のもとで公開されています。

このテーマは、完全に私の個人利用前提で公開されており、いかなる保証もありません。

- **テーマファイル**: GPL-2.0-or-later
- **フォント**
  - Lato
    - Copyright (c) 2010-2014 by tyPoland Lukasz Dziedzic
    - License: SIL Open Font License, 1.1, https://openfontlicense.org/open-font-license-official-text/
    - Source: https://github.com/latofonts/lato-source/
  - M PLUS 1 Code
    - Copyright 2021 The M+ FONTS Project Authors
    - License: SIL Open Font License, 1.1, https://openfontlicense.org/open-font-license-official-text/
    - Source: https://github.com/coz-m/MPLUS_FONTS
- **画像素材**:
  - Copyright (c) 2026 by [trBookmark](https://trbook.site)
  - License: CC BY-NC-ND, 4.0, https://creativecommons.org/licenses/by-nc/4.0/
