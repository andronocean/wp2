# WP2

> [!CAUTION]
> This is a work in progress. The information provided is subject to change and the project is not yet ready for production use.

## Overview

WP2 is a powerful WordPress framework, an extensive block library, and a suite of custom modules. Designed for both developers and content creators, WP2 makes it easier than ever to build engaging, high-performance websites.

Explore the [Wiki](https://coda.io/@vinnysgreen/wp2-wiki) 

## Getting Started

### Requirements

- [InstaWP](https://app.instawp.io/register?ref=39TUWaLAzX) — Or any WordPress site supporting PHP X.0+ and WP 6.X
- [Blockstudio](https://www.blockstudio.dev) — A tool for managing and creating custom blocks and more.

### Installation

The repository is structure mirrors the WordPress directory structure. WP2 works through must-use plugins, standard plugins, and themes. These coexist with a core WordPress installation.

Within the `wp2-new` module, all configurations and preparations are handled automatically during the cloning process. If you are using InstaWP, the following post-creation commands prepare a newly cloned site:

```bash
wp cache flush
wp eval 'file_put_contents(WP_CONTENT_DIR . "/debug.log", "");'
wp wp2-new run
```

The site is created and commands are execute, site will be fully configured and ready for use.

## Structure

### Daemons

```bash
.
└── wp-content/
    ├── mu-plugins/
    │   ├── wp2.php
    │   └── wp2-*/
    │       └── src
```

### Modules

```bash
.
└── wp-content/
    ├── plugins/
    │   ├── wp2*/
    │   │   └── src/
    │   │       ├── Assets/
    │   │       │   ├── Scripts/
    │   │       │   │   ├── global-scripts.js
    │   │       │   │   ├── global-scripts-{inline|editor|view}.js
    │   │       │   │   └── {block-editor|admin}-scripts.js
    │   │       │   └── Styles/
    │   │       │       ├── scss/
    │   │       │       │   ├── Blocks
    │   │       │       │   ├── Elements
    │   │       │       │   ├── Templates
    │   │       │       ├── global-styles.(s)css
    │   │       │       ├── global-styles-{inline|editor|scoped}.(s)css
    │   │       │       └── {block-editor|admin}-styles.(s)css
    │   │       ├── Blocks/
    │   │       │   ├── Namespaces/
    │   │       │   │   ├── core
    │   │       │   │   └── wp2*/
    │   │       │   │       └── PascalCaseName/
    │   │       │   │           ├── block.json
    │   │       │   │           ├── *.(s)css
    │   │       │   │           ├── *-{inline|editor|scoped}.(s)css
    │   │       │   │           ├── *.js
    │   │       │   │           ├── *-{inline|editor|view}.js
    │   │       │   │           ├── index.php
    │   │       │   │           └── init.php
    │   │       │   └── Settings
    │   │       ├── Catalogs
    │   │       ├── Elements
    │   │       ├── Helpers
    │   │       ├── Syncs
    │   │       ├── Templates
    │   │       └── Types 
    │   └── wp2*.php
```

### Themes

```bash
.
└── wp-content/
    ├── themes/
    │   └── wp2/
    │       ├── theme.json
    │       ├── parts/
    │       │   └── {template_zone}-part-{template}.html
    │       └── templates/
    │           ├── 404.html
    │           ├── archive.html
    │           ├── author.html
    │           ├── front-page.html
    │           ├── index.html
    │           ├── page.html
    │           ├── search.html
    │           └── single.html
```

## Contact

Have questions or need support? Reach out:
Email: [wp2@wp2s.com](mailto:hello+wp2@wp2s.com)
