<?php
// Path: wp-content/plugins/wp2-directory/src/Catalogs/Templates/Page/index.php

namespace WP2_Directory\Catalogs\Templates\Page;

$inner_blocks = sprintf(
    '<InnerBlocks useBlockProps tag="%s" class="%s"/>',
    'div',
    'wp2-listing wp2-listing--templates'
);

echo $inner_blocks;
