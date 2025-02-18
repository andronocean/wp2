<?php
// Path: wp-content/plugins/wp2-directory/src/Catalogs/Modules/Style/index.php

namespace WP2_Directory\Catalogs\Modules\Style;

$inner_blocks = sprintf(
    '<InnerBlocks useBlockProps tag="%s" class="%s"/>',
    'div',
    'wp2-listing wp2-listing--module'
);

echo $inner_blocks;