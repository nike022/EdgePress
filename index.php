<?php get_header(); ?>

    <div class="container mx-auto px-4 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Content Area -->
            <div class="lg:col-span-2">
                <!-- Archive Title -->
                <?php if ( is_archive() || is_search() ) : ?>
                    <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
                        <h1 class="text-lg font-medium border-l-4 border-primary pl-3 leading-none text-gray-800 dark:text-gray-100">
                            <?php
                            if ( is_search() ) {
                                printf( esc_html__( '搜索结果: %s', 'less' ), '<span>' . get_search_query() . '</span>' );
                            } elseif ( is_category() ) {
                                single_cat_title();
                            } elseif ( is_tag() ) {
                                single_tag_title();
                            } elseif ( is_author() ) {
                                the_post();
                                echo '作者: ' . get_the_author();
                                rewind_posts();
                            } elseif ( is_day() ) {
                                echo '按日归档: ' . get_the_date();
                            } elseif ( is_month() ) {
                                echo '按月归档: ' . get_the_date( _x( 'F Y', 'monthly archives date format', 'less' ) );
                            } elseif ( is_year() ) {
                                echo '按年归档: ' . get_the_date( _x( 'Y', 'yearly archives date format', 'less' ) );
                            } else {
                                echo '归档';
                            }
                            ?>
                        </h1>
                    </div>
                <?php else: ?>
                    <!-- Blog Home Title if needed -->
                     <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
                        <h1 class="text-lg font-medium border-l-4 border-primary pl-3 leading-none text-gray-800 dark:text-gray-100">
                            最新文章
                        </h1>
                    </div>
                <?php endif; ?>

                <div class="space-y-6" id="post-list">
                    <?php
                    if ( have_posts() ) :
                        while ( have_posts() ) : the_post();
                            get_template_part( 'template-parts/content' );
                        endwhile;
                    else :
                        echo '<p>暂无文章。</p>';
                    endif;
                    ?>
                </div>

                <?php
                // Pagination Links (静态化兼容)
                global $wp_query;
                if ( $wp_query->max_num_pages > 1 ) :
                    $pagination = paginate_links( array(
                        'total'     => $wp_query->max_num_pages,
                        'current'   => max( 1, get_query_var( 'paged' ) ),
                        'prev_text' => '&laquo; 上一页',
                        'next_text' => '下一页 &raquo;',
                        'type'      => 'array',
                        'mid_size'  => 2,
                    ) );

                    if ( $pagination ) {
                        echo '<nav class="mt-8 flex justify-center items-center gap-2 flex-wrap">';
                        foreach ( $pagination as $page ) {
                            $page = str_replace( 'page-numbers', 'page-numbers px-4 py-2 border border-gray-200 dark:border-gray-700 rounded hover:border-primary hover:text-primary transition-colors text-gray-600 dark:text-gray-300', $page );
                            $page = str_replace( 'current', 'current bg-primary text-white border-primary', $page );
                            echo $page;
                        }
                        echo '</nav>';
                    }
                endif;
                ?>
            </div>

            <!-- Sidebar -->
            <?php get_sidebar(); ?>
            
        </div>
    </div>

<?php get_footer(); ?>
