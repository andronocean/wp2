        <?php
        // Path: wp-content/plugins/wp2-directory/src/Catalogs/Pages/Account/index.php

        namespace WP2_Directory\Catalogs\Pages\Account;

        $inner_blocks = sprintf(
            '<InnerBlocks useBlockProps tag="%s" class="%s"/>',
            'div',
            'wp2-listing wp2-listing--page'
        );

        echo $inner_blocks;
