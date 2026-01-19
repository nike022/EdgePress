<article id="post-<?php the_ID(); ?>" <?php post_class( 'bg-white dark:bg-gray-800 p-5 rounded-lg shadow-sm hover:shadow-md transition-shadow flex flex-col md:flex-row gap-5' ); ?>>
    <div class="w-full md:w-60 aspect-[3/2] bg-gray-200 dark:bg-gray-700 rounded shrink-0 overflow-hidden relative group">
        <?php 
        $categories = get_the_category();
        if ( ! empty( $categories ) ) : ?>
            <a href="<?php echo esc_url( get_category_link( $categories[0]->term_id ) ); ?>" class="absolute top-2 left-2 bg-primary text-white text-xs px-2 py-1 rounded shadow-sm hover:bg-blue-600 transition-colors z-10"><?php echo esc_html( $categories[0]->name ); ?></a>
        <?php endif; ?>
        
        <a href="<?php the_permalink(); ?>" class="block w-full h-full">
            <?php 
            // 1. 优先检查是否有特色图片
            if ( has_post_thumbnail() ) :
                the_post_thumbnail( 'medium', array( 'class' => 'w-full h-full object-cover transition-transform duration-300 group-hover:scale-105', 'loading' => 'lazy' ) );

            else :
                // 2. 如果没有特色图片，尝试抓取文章第一张图片
                // (注意:确保 functions.php 中有 less_get_first_content_image 函数)
                $first_img = function_exists('less_get_first_content_image') ? less_get_first_content_image() : '';

                if ( ! empty( $first_img ) ) :
            ?>
                <img src="<?php echo esc_url( $first_img ); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" loading="lazy" />

            <?php else : ?>
                <img src="<?php echo less_get_random_cover(); ?>" alt="Cover" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" loading="lazy" />
            <?php 
                endif;
            endif; 
            ?>
        </a>
    </div>

    <div class="flex flex-col justify-between flex-1">
        <div>
            <h2 class="text-xl font-medium text-gray-800 dark:text-gray-100 hover:text-primary mb-4 leading-snug">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h2>
            <div class="hidden md:block">
                <div class="text-gray-500 dark:text-gray-400 text-base mb-4 line-clamp-2 leading-relaxed">
                    <?php echo strip_tags( get_the_excerpt() ); ?>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between text-xs text-gray-400">
            <div class="flex items-center gap-4">
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
                <span class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 576 512" fill="currentColor"><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg>
                    <span class="waline-pageview-count" data-path="<?php echo esc_attr( less_get_waline_path() ); ?>">0</span>
                </span>
            </div>
        </div>
    </div>
</article>