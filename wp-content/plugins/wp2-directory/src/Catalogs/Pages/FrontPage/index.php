        <?php
        // Path: wp-content/plugins/wp2-directory/src/Catalogs/Pages/FrontPage/index.php

        namespace WP2_Directory\Catalogs\Pages\FrontPage;

        $inner_blocks = sprintf(
            '<InnerBlocks useBlockProps tag="%s" class="%s"/>',
            'div',
            'wp2-listing wp2-listing--page'
        );

        echo $inner_blocks;
