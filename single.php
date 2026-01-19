<?php get_header(); ?>

    <div class="container mx-auto px-4 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2">
                <?php
                if ( have_posts() ) :
                    while ( have_posts() ) : the_post();
                        ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> data-pagefind-body>
                            <div class="bg-white dark:bg-gray-800 rounded-t-lg p-6 md:p-8 border-b border-gray-100 dark:border-gray-700">
                                <div class="text-xs text-gray-400 mb-4 flex items-center gap-2">
                                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-primary">首页</a> &gt;
                                    <?php
                                    $categories = get_the_category();
                                    if ( ! empty( $categories ) ) {
                                        echo '<a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '" class="hover:text-primary">' . esc_html( $categories[0]->name ) . '</a> &gt;';
                                    }
                                    ?>
                                    <span>正文</span>
                                </div>
                                <h1 class="text-2xl md:text-3xl lg:text-4xl font-medium mb-6 leading-tight text-gray-900 dark:text-gray-100"><?php the_title(); ?></h1>
                                <div class="flex flex-wrap items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                                    <?php
                                    $options = get_option( 'less_options' );
                                    if ( isset( $options['show_author'] ) && $options['show_author'] ) : ?>
                                        <span class="flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 448 512" fill="currentColor"><path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"/></svg>
                                            <?php the_author(); ?>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <span class="flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 512 512" fill="currentColor"><path d="M464 256A208 208 0 1 1 48 256a208 208 0 1 1 416 0zM0 256a256 256 0 1 0 512 0A256 256 0 1 0 0 256zM232 120V256c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2V120c0-13.3-10.7-24-24-24s-24 10.7-24 24z"/></svg>
                                        <?php echo get_the_date(); ?>
                                    </span>

                                    <span class="flex items-center gap-1 flex-row">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 576 512" fill="currentColor"><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg>
                                        <span class="waline-pageview-count" data-path="<?php echo esc_attr( less_get_waline_path() ); ?>">0</span>
                                    </span>
                                    
                                    <?php if ( ! empty( $categories ) ) : ?>
                                        <span class="flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 512 512" fill="currentColor"><path d="M64 480H448c35.3 0 64-28.7 64-64V160c0-35.3-28.7-64-64-64H288c-10.1 0-19.6-4.7-25.6-12.8L243.2 57.6C231.1 41.5 212.1 32 192 32H64C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64z"/></svg>
                                            <?php echo esc_html( $categories[0]->name ); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="bg-white dark:bg-gray-800 rounded-b-lg p-6 md:p-8 leading-relaxed text-gray-700 dark:text-gray-300 entry-content">
                                <div class="space-y-6 prose dark:prose-invert max-w-none">
                                    <?php the_content(); ?>
                                </div>

                                <?php
                                $tags = get_the_tags();
                                if ( $tags ) : ?>
                                    <div class="mt-10 pt-6 border-t border-gray-100 dark:border-gray-700 flex flex-wrap items-center gap-3 entry-tags">
                                         <span class="inline-flex items-center text-gray-400 text-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-1" width="1em" height="1em" viewBox="0 0 448 512" fill="currentColor"><path d="M0 80V229.5c0 17 6.7 33.3 18.7 45.3l176 176c25 25 65.5 25 90.5 0L418.7 317.3c25-25 25-65.5 0-90.5l-176-176c-12-12-28.3-18.7-45.3-18.7H48C21.5 32 0 53.5 0 80zm112 32a32 32 0 1 1 0 64 32 32 0 1 1 0-64z"/></svg>
                                            标签:
                                         </span>
                                         <?php foreach ( $tags as $tag ) : ?>
                                            <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="inline-block px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-sm rounded-full hover:bg-primary hover:text-white transition-colors duration-200"><?php echo esc_html( $tag->name ); ?></a>
                                         <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </article>

                        <?php
                        $related_args = array(
                            'posts_per_page' => 2,
                            'post__not_in'   => array( get_the_ID() ),
                            'orderby'        => 'rand',
                        );
                        if ( ! empty( $categories ) ) {
                            $related_args['cat'] = $categories[0]->term_id;
                        }
                        $related_query = new WP_Query( $related_args );

                        if ( $related_query->have_posts() ) : ?>
                            <div class="mt-8">
                                <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
                                    <h3 class="text-lg font-medium border-l-4 border-primary pl-3 leading-none text-gray-900 dark:text-gray-100">相关推荐</h3>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <?php while ( $related_query->have_posts() ) : $related_query->the_post(); ?>
                                        <article class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm flex gap-4">
                                            <div class="w-24 aspect-[3/2] bg-gray-200 dark:bg-gray-700 rounded overflow-hidden relative shrink-0">
                                                <?php if ( has_post_thumbnail() ) : ?>
                                                    <a href="<?php the_permalink(); ?>" class="block w-full h-full">
                                                        <?php the_post_thumbnail( 'thumbnail', array( 'class' => 'w-full h-full object-cover' ) ); ?>
                                                    </a>
                                                <?php else: 
                                                    $first_img = function_exists('less_get_first_content_image') ? less_get_first_content_image() : '';
                                                    if ( $first_img ) :
                                                ?>
                                                    <a href="<?php the_permalink(); ?>" class="block w-full h-full">
                                                        <img src="<?php echo esc_url($first_img); ?>" class="w-full h-full object-cover" alt="<?php the_title_attribute(); ?>">
                                                    </a>
                                                <?php else: ?>
                                                    <a href="<?php the_permalink(); ?>" class="block w-full h-full">
                                                        <img src="<?php echo less_get_random_cover(); ?>" class="w-full h-full object-cover" alt="Random Thumb">
                                                    </a>
                                                <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div>
                                                 <h4 class="text-base mb-1 leading-snug"><a href="<?php the_permalink(); ?>" class="hover:text-primary text-gray-800 dark:text-gray-200"><?php the_title(); ?></a></h4>
                                                 <div class="flex items-center gap-3 text-xs text-gray-400">
                                                     <span><?php echo get_the_date(); ?></span>
                                                     <span class="inline-flex items-center">
                                                         <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 576 512" fill="currentColor" class="mr-1"><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg>
                                                         <span class="waline-pageview-count" data-path="<?php echo esc_attr( less_get_waline_path() ); ?>">0</span>
                                                     </span>
                                                 </div>
                                            </div>
                                        </article>
                                    <?php endwhile; ?>
                                </div>
                            </div>
                            <?php wp_reset_postdata(); ?>
                        <?php endif; ?>

                        <?php
                        $options = get_option( 'less_options' );
                        if ( isset($options['enable_comments']) && $options['enable_comments'] == 1 ) :
                            comments_template();
                        endif;
                        ?>

                        <?php
                    endwhile;
                endif;
                ?>
            </div>

            <?php get_sidebar(); ?>
            
        </div>
    </div>

<?php get_footer(); ?>