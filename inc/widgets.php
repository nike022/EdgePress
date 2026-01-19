<?php
/**
 * Custom Widgets for Less Theme
 */

// Register Sidebar and Widgets
function less_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( '首页侧边栏', 'less' ),
        'id'            => 'sidebar-home',
        'description'   => esc_html__( '首页显示的侧边栏小工具。', 'less' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s bg-white dark:bg-gray-800 p-5 rounded-lg shadow-sm mb-6">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title text-lg font-medium mb-4 pb-2 border-b border-gray-100 dark:border-gray-700 text-gray-900 dark:text-gray-100">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( '列表页侧边栏', 'less' ),
        'id'            => 'sidebar-archive',
        'description'   => esc_html__( '文章分类、搜索等列表页显示的侧边栏小工具。', 'less' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s bg-white dark:bg-gray-800 p-5 rounded-lg shadow-sm mb-6">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title text-lg font-medium mb-4 pb-2 border-b border-gray-100 dark:border-gray-700 text-gray-900 dark:text-gray-100">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( '文章页侧边栏', 'less' ),
        'id'            => 'sidebar-single',
        'description'   => esc_html__( '文章详情页显示的侧边栏小工具。', 'less' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s bg-white dark:bg-gray-800 p-5 rounded-lg shadow-sm mb-6">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title text-lg font-medium mb-4 pb-2 border-b border-gray-100 dark:border-gray-700 text-gray-900 dark:text-gray-100">',
        'after_title'   => '</h3>',
    ) );

    register_widget( 'Less_Recent_Posts_Widget' );
    register_widget( 'Less_Popular_Posts_Widget' );
    register_widget( 'Less_Popular_Tags_Widget' );
    register_widget( 'Less_Image_Widget' );
}
add_action( 'widgets_init', 'less_widgets_init' );

/**
 * Recent Posts Widget
 */
class Less_Recent_Posts_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'less_recent_posts',
            esc_html__( 'Less: 最新文章', 'less' ),
            array( 'description' => esc_html__( '显示带有缩略图的最新文章。', 'less' ) )
        );
    }

    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        
        $r = new WP_Query( array(
            'posts_per_page'      => $number,
            'no_found_rows'       => true,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true,
        ) );

        if ( $r->have_posts() ) :
            echo '<ul class="space-y-4">';
            while ( $r->have_posts() ) : $r->the_post();
                ?>
                <li class="flex gap-3">
                    <div class="w-24 h-16 bg-gray-200 dark:bg-gray-700 rounded shrink-0 overflow-hidden relative">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <a href="<?php the_permalink(); ?>" class="block w-full h-full">
                                <?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-full object-cover transform hover:scale-110 transition-transform duration-300', 'loading' => 'lazy' ) ); ?>
                            </a>
                        <?php else:
                            // 1. 尝试获取文章内第一张图
                            $first_img = function_exists('less_get_first_content_image') ? less_get_first_content_image() : '';
                            if ( $first_img ) :
                            ?>
                            <a href="<?php the_permalink(); ?>" class="block w-full h-full">
                                <img src="<?php echo esc_url($first_img); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-cover transform hover:scale-110 transition-transform duration-300" loading="lazy">
                            </a>
                            <?php else: ?>
                            <a href="<?php the_permalink(); ?>" class="block w-full h-full">
                                <img src="<?php echo less_get_random_cover(); ?>" class="w-full h-full object-cover transform hover:scale-110 transition-transform duration-300" alt="Random Thumb" loading="lazy">
                            </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-[15px] leading-snug mb-1 text-gray-800 dark:text-gray-200 line-clamp-2">
                            <a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors"><?php the_title(); ?></a>
                        </h4>
                        <div class="flex items-center gap-3 text-xs text-gray-400">
                            <span><?php echo get_the_date(); ?></span>
                        </div>
                    </div>
                </li>
                <?php
            endwhile;
            echo '</ul>';
            wp_reset_postdata();
        endif;

        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title  = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $number = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( '标题:', 'less' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php esc_html_e( '显示文章数量:', 'less' ); ?></label>
            <input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title']  = sanitize_text_field( $new_instance['title'] );
        $instance['number'] = (int) $new_instance['number'];
        return $instance;
    }
}

/**
 * Random Posts Widget
 */
class Less_Popular_Posts_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'less_popular_posts',
            esc_html__( 'Less: 随机文章', 'less' ),
            array( 'description' => esc_html__( '随机显示文章，每次生成静态站时顺序不同。', 'less' ) )
        );
    }

    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 5;

        // 🟢 使用随机排序（每次生成静态站时顺序不同，适合静态站）
        $r = new WP_Query( array(
            'posts_per_page'      => $number,
            'no_found_rows'       => true,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true,
            'orderby'             => 'rand',  // 随机排序
        ) );

        if ( $r->have_posts() ) :
            echo '<ul class="space-y-3">';
            while ( $r->have_posts() ) : $r->the_post();
                ?>
                <li class="border-b border-gray-100 dark:border-gray-700 pb-3 last:border-0 last:pb-0">
                    <h4 class="text-sm font-medium leading-snug mb-1 text-gray-800 dark:text-gray-200">
                        <a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors"><?php the_title(); ?></a>
                    </h4>
                    <div class="text-xs text-gray-400">
                        <span><?php echo get_the_date('Y-m-d'); ?></span>
                    </div>
                </li>
                <?php
            endwhile;
            echo '</ul>';
            wp_reset_postdata();
        endif;

        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title  = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $number = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( '标题:', 'less' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php esc_html_e( '显示文章数量:', 'less' ); ?></label>
            <input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title']  = sanitize_text_field( $new_instance['title'] );
        $instance['number'] = (int) $new_instance['number'];
        return $instance;
    }
}

/**
 * Popular Tags Widget
 */
class Less_Popular_Tags_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'less_popular_tags',
            esc_html__( 'Less: 热门标签', 'less' ),
            array( 'description' => esc_html__( '显示使用最多的标签。', 'less' ) )
        );
    }

    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 20;
        
        $tags = get_tags( array(
            'orderby' => 'count',
            'order'   => 'DESC',
            'number'  => $number,
        ) );

        if ( $tags ) {
            echo '<div class="flex flex-wrap gap-2">';
            foreach ( $tags as $tag ) {
                echo '<a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '" class="inline-block px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-sm rounded-full hover:bg-primary hover:text-white transition-colors duration-200">' . esc_html( $tag->name ) . '</a>';
            }
            echo '</div>';
        }

        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title  = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $number = isset( $instance['number'] ) ? absint( $instance['number'] ) : 20;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( '标题:', 'less' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php esc_html_e( '显示标签数量:', 'less' ); ?></label>
            <input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title']  = sanitize_text_field( $new_instance['title'] );
        $instance['number'] = (int) $new_instance['number'];
        return $instance;
    }
}

/**
 * Image Widget (Simple Wrapper)
 */
class Less_Image_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'less_image_widget',
            esc_html__( 'Less: 图片广告', 'less' ),
            array( 'description' => esc_html__( '显示带链接的图片。', 'less' ) )
        );
    }

    public function widget( $args, $instance ) {
        $image_url = ! empty( $instance['image_url'] ) ? $instance['image_url'] : '';
        $link_url  = ! empty( $instance['link_url'] ) ? $instance['link_url'] : '#';
        $open_new  = isset( $instance['open_new'] ) ? (bool) $instance['open_new'] : true;
        
        if ( empty( $image_url ) ) return;

        echo $args['before_widget'];
        
        echo '<div class="-m-5 rounded-lg overflow-hidden">'; // Negative margin to counteract the widget padding
        echo '<a href="' . esc_url( $link_url ) . '" class="block group" ' . ( $open_new ? 'target="_blank"' : '' ) . '>';
        echo '<img src="' . esc_url( $image_url ) . '" alt="Ad" class="w-full h-auto object-cover hover:opacity-95 transition-opacity">';
        echo '</a>';
        echo '</div>';

        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $image_url = isset( $instance['image_url'] ) ? esc_attr( $instance['image_url'] ) : '';
        $link_url  = isset( $instance['link_url'] ) ? esc_attr( $instance['link_url'] ) : '';
        $open_new  = isset( $instance['open_new'] ) ? (bool) $instance['open_new'] : true;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( '标题 (可选，前端不显示):', 'less' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <div style="margin-bottom:15px;">
            <label for="<?php echo $this->get_field_id( 'image_url' ); ?>"><?php esc_html_e( '图片地址:', 'less' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'image_url' ); ?>" name="<?php echo $this->get_field_name( 'image_url' ); ?>" type="text" value="<?php echo $image_url; ?>" />
            <input type="button" class="button button-secondary js-upload-image" data-target="#<?php echo $this->get_field_id( 'image_url' ); ?>" value="<?php esc_attr_e( '上传图片', 'less' ); ?>" style="margin-top: 5px;" />
            <?php if ( ! empty( $image_url ) ) : ?>
                <div class="less-image-preview" style="margin-top:10px;"><img src="<?php echo esc_url( $image_url ); ?>" style="max-height: 100px;"></div>
            <?php endif; ?>
        </div>
        <p>
            <label for="<?php echo $this->get_field_id( 'link_url' ); ?>"><?php esc_html_e( '链接地址:', 'less' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'link_url' ); ?>" name="<?php echo $this->get_field_name( 'link_url' ); ?>" type="text" value="<?php echo $link_url; ?>" />
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $open_new ); ?> id="<?php echo $this->get_field_id( 'open_new' ); ?>" name="<?php echo $this->get_field_name( 'open_new' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'open_new' ); ?>"><?php esc_html_e( '在新窗口打开链接', 'less' ); ?></label>
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title']     = sanitize_text_field( $new_instance['title'] );
        $instance['image_url'] = esc_url_raw( $new_instance['image_url'] );
        $instance['link_url']  = esc_url_raw( $new_instance['link_url'] );
        $instance['open_new']  = isset( $new_instance['open_new'] ) ? (bool) $new_instance['open_new'] : false;
        return $instance;
    }
}

/**
 * Enqueue Media Scripts for Widgets
 */
function less_widgets_scripts( $hook ) {
    if ( 'widgets.php' !== $hook && 'customize.php' !== $hook ) {
        return;
    }
    wp_enqueue_media();
    wp_enqueue_script( 'less-admin-script', get_template_directory_uri() . '/assets/js/admin.js', array( 'jquery' ), '1.0.0', true );
}
add_action( 'admin_enqueue_scripts', 'less_widgets_scripts' );