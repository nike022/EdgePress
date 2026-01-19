<?php get_header(); ?>

<div class="container mx-auto px-4 py-12">
    <div class="max-w-2xl mx-auto text-center">
        <div class="mb-8">
            <span class="text-8xl">😕</span>
        </div>

        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-gray-100 mb-4">
            404
        </h1>

        <h2 class="text-2xl md:text-3xl font-medium text-gray-700 dark:text-gray-300 mb-6">
            页面未找到
        </h2>

        <p class="text-lg text-gray-600 dark:text-gray-400 mb-8">
            抱歉，您访问的页面不存在或已被删除。
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-block px-6 py-3 bg-primary text-white rounded-lg hover:bg-blue-600 transition-colors">
                返回首页
            </a>
            <a href="javascript:history.back()" class="inline-block px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                返回上一页
            </a>
        </div>

        <!-- 搜索框 -->
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm">
            <h3 class="text-lg font-medium text-gray-800 dark:text-gray-100 mb-4">
                试试搜索您需要的内容
            </h3>
            <?php get_search_form(); ?>
        </div>

        <!-- 最新文章 -->
        <div class="mt-12">
            <h3 class="text-xl font-medium text-gray-800 dark:text-gray-100 mb-6">
                或者浏览我们的最新文章
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php
                $recent_posts = new WP_Query( array(
                    'posts_per_page' => 4,
                    'post_status'    => 'publish',
                ) );

                if ( $recent_posts->have_posts() ) :
                    while ( $recent_posts->have_posts() ) : $recent_posts->the_post();
                        ?>
                        <article class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow text-left">
                            <h4 class="text-base font-medium mb-2">
                                <a href="<?php the_permalink(); ?>" class="text-gray-800 dark:text-gray-200 hover:text-primary">
                                    <?php the_title(); ?>
                                </a>
                            </h4>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                <?php echo get_the_date(); ?>
                            </div>
                        </article>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
