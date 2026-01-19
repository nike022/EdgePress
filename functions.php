<?php
/**
 * Less Theme Functions - Static Site Optimized
 * 完美适配 Simply Static / Cloudflare Pages / GitHub Pages
 */

if ( ! function_exists( 'less_setup' ) ) :
    function less_setup() {
        add_theme_support( 'automatic-feed-links' );
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        register_nav_menus( array(
            'primary' => esc_html__( 'Primary Menu', 'less' ),
            'footer'  => esc_html__( 'Footer Menu', 'less' ),
        ) );
        add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

        // 🟢 禁用 WordPress 自带 Emoji 脚本（静态站不需要）
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
    }
endif;
add_action( 'after_setup_theme', 'less_setup' );

// 1. 暗黑模式初始化 (优先级设为 1 确保最早执行)
function less_dark_mode_init() {
    $options = get_option( 'less_options' );
    $color_mode = isset( $options['color_mode'] ) ? $options['color_mode'] : 'light';
    echo '<script>';
    echo "if ('theme' in localStorage) {";
    echo "    if (localStorage.theme === 'dark') { document.documentElement.classList.add('dark'); }";
    echo "    else { document.documentElement.classList.remove('dark'); }";
    echo "} else {";
    echo "    var colorMode = '" . esc_js( $color_mode ) . "';";
    echo "    if (colorMode === 'dark') { document.documentElement.classList.add('dark'); }";
    echo "    else if (colorMode === 'light') { document.documentElement.classList.remove('dark'); }";
    echo "    else {";
    echo "        if (window.matchMedia('(prefers-color-scheme: dark)').matches) { document.documentElement.classList.add('dark'); }";
    echo "        else { document.documentElement.classList.remove('dark'); }";
    echo "    }";
    echo "}";
    echo '</script>';
}
add_action( 'wp_head', 'less_dark_mode_init', 1 );

// 2. 加载资源 (Tailwind + CSS + Main JS)
function less_scripts() {
    // 🟢 优先级：本地文件 → 国内CDN → 官方CDN
    $local_js = get_template_directory() . '/assets/js/tailwind-play.min.js';

    if (file_exists($local_js)) {
        // 1. 优先使用本地文件（最稳定）
        wp_enqueue_script('tailwind', get_template_directory_uri() . '/assets/js/tailwind-play.min.js', array(), '3.4.17', false);
    } else {
        // 2. 尝试国内稳定CDN（暂时注释，测试本地）
        // $china_cdn = 'https://cdn.bootcdn.net/ajax/libs/tailwindcss/3.4.17/tailwind.min.js';
        // $response = @wp_remote_head($china_cdn);

        // if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
        //     wp_enqueue_script('tailwind', $china_cdn, array(), '3.4.17', false);
        // } else {
            // 3. 最后使用官方CDN（暂时注释，测试本地）
            // wp_enqueue_script('tailwind', 'https://cdn.tailwindcss.com', array(), '3.4.17', false);

        // 显示提示信息（仅管理员可见）
        if (current_user_can('administrator')) {
            add_action('wp_head', function() {
                echo '<style>.tailwind-notice { position: fixed; top: 20px; right: 20px; background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 15px; border-radius: 8px; z-index: 9999; max-width: 400px; }</style>';
                echo '<div class="tailwind-notice">';
                echo '<h4>Tailwind 配置提示</h4>';
                echo '<p>建议下载本地文件以提高稳定性：<br><a href="https://cdn.tailwindcss.com" target="_blank"下载 tailwind-play.min.js</a><br>保存到：<code>/assets/js/</code> 目录</p>';
                echo '<button onclick="this.parentElement.remove()" style="float: right; background: none; border: none; cursor: pointer; font-size: 18px;">×</button>';
                echo '</div>';
            });
        }
    }

    // Tailwind 配置（添加检查保护）
    $tailwind_config = "
        if (typeof tailwind !== 'undefined') {
            tailwind.config = {
                darkMode: 'class',
                theme: { extend: { colors: { primary: '#3b5dce', secondary: '#2d3237' } } }
            }
        }
    ";
    wp_add_inline_script('tailwind', $tailwind_config, 'after');

    wp_enqueue_style('less-style', get_stylesheet_uri(), array(), '1.0.2');
    wp_enqueue_script('less-main', get_template_directory_uri() . '/assets/js/main.js', array(), '1.0.0', true);

    // 🟢 全局加载 Waline SDK（用于评论和浏览量统计）
    wp_enqueue_style('waline-css', get_template_directory_uri() . '/assets/css/waline.css', array(), '1.0.0');
    wp_enqueue_script('waline-js', get_template_directory_uri() . '/assets/js/waline.js', array(), '1.0.0', true);
    wp_enqueue_script('waline-init', get_template_directory_uri() . '/assets/js/waline-init.js', array('waline-js'), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'less_scripts');

// 3. 引入核心文件
require get_template_directory() . '/inc/theme-options.php';
require get_template_directory() . '/inc/widgets.php';

// 4. SEO 优化
function less_seo_title( $title ) {
    if ( is_front_page() || is_home() ) {
        $options = get_option( 'less_options' );
        if ( ! empty( $options['seo_home_title'] ) ) {
            $title['title'] = $options['seo_home_title'];
        }
    }
    return $title;
}
add_filter( 'document_title_parts', 'less_seo_title' );

// 5. 摘要长度控制
function less_excerpt_length( $length ) { return 80; }
add_filter( 'excerpt_length', 'less_excerpt_length', 999 );
function less_excerpt_more( $more ) { return '...'; }
add_filter( 'excerpt_more', 'less_excerpt_more' );

// 6. 自动获取文章第一张图 (性能优化版)
function less_get_first_content_image() {
    global $post;
    $first_img = '';
    if ( ! empty( $post->post_content ) ) {
        // 🟢 修复：移除了无用的 ob_start()，改用 preg_match 提高效率
        if ( preg_match( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches ) ) {
            $first_img = $matches[1];
        }
    }
    return $first_img;
}

/**
 * 🟢 6.5 获取文章的 Waline 路径（仅路径部分，不包含域名）
 * 用于浏览量统计，确保静态站和动态站路径一致
 */
function less_get_waline_path() {
    $permalink = get_permalink();
    $parsed = parse_url( $permalink );
    return isset( $parsed['path'] ) ? $parsed['path'] : '/';
}

/**
 * 7. AES-256 加密短代码 (Hex 传输版)
 * 静态网站完美适配方案：后端加密 -> 静态 HTML -> 前端解密
 */
function gzh_lock_content_shortcode( $atts, $content = null ) {
    $atts = shortcode_atts( array(
        'key'     => '8888',
        'keyword' => '获取密码',
        'qr'      => '',
    ), $atts );

    $password = trim($atts['key']);
    $show_word = $atts['keyword'];
    $custom_qr = trim($atts['qr']);

    if ( is_null( $content ) ) { return ''; }

    $content = wpautop( do_shortcode( $content ) );
    $verify_token = "GZH_VERIFY_TOKEN_";
    $content = $verify_token . $content;

    // AES 加密
    $key = hash('sha256', $password, true);
    $iv = openssl_random_pseudo_bytes(16);
    $encrypted = openssl_encrypt($content, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

    // Hex 传输
    $final_payload = bin2hex($iv) . bin2hex($encrypted);
    $html_id = 'locker-' . uniqid();

    // 优先使用自定义二维码，否则使用后台配置
    $options = get_option('less_options');
    $qr_url = !empty($custom_qr) ? $custom_qr : (isset($options['qr_code_url']) ? $options['qr_code_url'] : 'https://88.ai888.uk/wp-content/uploads/2025/12/11.jpg');

    $output = '
    <div id="' . $html_id . '" class="gzh-locker-box bg-gray-50 dark:bg-gray-800 border border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center my-8">
        <div class="locker-mask">
            <div class="mb-4"><span class="text-4xl">🔒</span></div>
            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-2">此内容已被隐藏</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                请扫描下方二维码关注公众号，回复 <span class="text-red-500 font-bold text-lg mx-1">'. esc_html($show_word) .'</span> 获取访问密码
            </p>
            <img src="' . esc_url($qr_url) . '" alt="公众号二维码" class="w-32 h-32 mx-auto mb-4 p-1 bg-white border rounded">
            <div class="flex justify-center gap-2 max-w-xs mx-auto flex-col sm:flex-row">
                <input type="text" id="pwd-' . $html_id . '" placeholder="请输入访问密码" class="px-4 py-2 border rounded w-full dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:border-primary text-center sm:text-left">
                <button onclick="unlock_content(\'' . $html_id . '\', \'' . $final_payload . '\')" class="px-4 py-2 bg-primary text-white rounded hover:bg-blue-600 transition-colors whitespace-nowrap">解锁</button>
            </div>
            <div id="error-' . $html_id . '" class="text-red-500 text-sm mt-3 hidden font-bold">密码错误，请重试</div>
        </div>
        <div class="locker-content hidden text-left prose dark:prose-invert max-w-none"></div>
    </div>
    ';
    return $output;
}
add_shortcode( 'gzh2v', 'gzh_lock_content_shortcode' );

// 7.2 新增付费内容短代码 [pay]
function pay_content_shortcode( $atts, $content = null ) {
    $atts = shortcode_atts( array(
        'type'     => 'free',
        'price'    => '',
        'password' => '',
        'keyword'  => '密码',
        'contact'  => '',
        'link'     => '',
    ), $atts );

    if ( empty( $content ) ) return '';

    $type = $atts['type'];
    $price = $atts['price'];
    $password = $atts['password'];
    $keyword = $atts['keyword'];
    $contact = $atts['contact'];
    $link = $atts['link'];

    if ( $type === 'free' ) {
        return wpautop( do_shortcode( $content ) );
    }

    $content = wpautop( do_shortcode( $content ) );
    $verify_token = "PAY_VERIFY_TOKEN_";
    $content = $verify_token . $content;

    $key = hash('sha256', $password, true);
    $iv = openssl_random_pseudo_bytes(16);
    $encrypted = openssl_encrypt($content, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    $final_payload = bin2hex($iv) . bin2hex($encrypted);
    $html_id = 'pay-' . uniqid();

    $options = get_option('less_options');

    $icon = '💰';
    $title = '';
    $desc = '';
    $qr_or_link = '';
    $tips = '';

    if ( $type === 'wechat' ) {
        $qr_url = isset($options['wechat_pay_qr']) ? $options['wechat_pay_qr'] : '';
        if ( empty($qr_url) ) {
            return '<div class="bg-yellow-50 border border-yellow-200 rounded p-4 my-4">请先在主题设置中配置微信收款码</div>';
        }
        $icon = '💚';
        $title = '微信支付';
        $desc = '请使用微信扫码支付 <span class="text-green-600 font-bold text-lg mx-1">¥' . esc_html($price) . '</span>';
        $qr_or_link = '<img src="' . esc_url($qr_url) . '" alt="微信收款码" class="w-40 h-40 mx-auto mb-4 p-2 bg-white border rounded">';
        $tips = '<div class="mt-3 p-3 bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded text-sm">⚠️ 支付时请备注您的<span class="font-bold">邮箱/微信号</span><br>确认收款后，我们会发送密码给您' . (!empty($contact) ? '<br>联系方式：<span class="text-primary font-bold">' . esc_html($contact) . '</span>' : '') . '</div>';

    } elseif ( $type === 'alipay' ) {
        $qr_url = isset($options['alipay_qr']) ? $options['alipay_qr'] : '';
        if ( empty($qr_url) ) {
            return '<div class="bg-yellow-50 border border-yellow-200 rounded p-4 my-4">请先在主题设置中配置支付宝收款码</div>';
        }
        $icon = '💙';
        $title = '支付宝支付';
        $desc = '请使用支付宝扫码支付 <span class="text-blue-600 font-bold text-lg mx-1">¥' . esc_html($price) . '</span>';
        $qr_or_link = '<img src="' . esc_url($qr_url) . '" alt="支付宝收款码" class="w-40 h-40 mx-auto mb-4 p-2 bg-white border rounded">';
        $tips = '<div class="mt-3 p-3 bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded text-sm">⚠️ 支付时请备注您的<span class="font-bold">邮箱/QQ号</span><br>确认收款后，我们会发送密码给您' . (!empty($contact) ? '<br>联系方式：<span class="text-primary font-bold">' . esc_html($contact) . '</span>' : '') . '</div>';

    } elseif ( $type === 'card' ) {
        if ( empty($link) ) {
            return '<div class="bg-yellow-50 border border-yellow-200 rounded p-4 my-4">请设置发卡平台链接 link 参数</div>';
        }
        $icon = '🎫';
        $title = '自动发卡';
        $desc = '点击下方按钮前往发卡平台购买' . (!empty($price) ? '（<span class="text-primary font-bold text-lg mx-1">¥' . esc_html($price) . '</span>）' : '');
        $qr_or_link = '<a href="' . esc_url($link) . '" target="_blank" class="inline-block px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 font-bold rounded-lg hover:from-blue-600 hover:to-purple-700 transition-all transform hover:scale-105 shadow-lg" style="color: white !important;">🛒 前往购买</a>';
        $tips = '<div class="mt-3 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded text-sm">✅ 购买成功后，系统会自动发送密码到您的邮箱</div>';

    } elseif ( $type === 'planet' ) {
        if ( empty($link) ) {
            return '<div class="bg-yellow-50 border border-yellow-200 rounded p-4 my-4">请设置知识星球链接 link 参数</div>';
        }
        $icon = '🌟';
        $title = '加入知识星球';
        $desc = '加入知识星球获取完整内容' . (!empty($price) ? '（<span class="text-yellow-600 font-bold text-lg mx-1">¥' . esc_html($price) . '/年</span>）' : '');
        $qr_or_link = '<a href="' . esc_url($link) . '" target="_blank" class="inline-block px-6 py-3 bg-gradient-to-r from-yellow-400 to-orange-500 font-bold rounded-lg hover:from-yellow-500 hover:to-orange-600 transition-all transform hover:scale-105 shadow-lg" style="color: white !important;">⭐ 加入星球</a>';
        $tips = '<div class="mt-3 p-3 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded text-sm">💎 加入后，在星球内获取解锁密码</div>';

    } elseif ( $type === 'gzh' ) {
        $qr_url = isset($options['qr_code_url']) ? $options['qr_code_url'] : '';
        if ( empty($qr_url) ) {
            return '<div class="bg-yellow-50 border border-yellow-200 rounded p-4 my-4">请先在主题设置中配置公众号二维码</div>';
        }
        $icon = '📱';
        $title = '关注公众号';
        $desc = '扫描下方二维码关注公众号，回复 <span class="text-red-500 font-bold text-lg mx-1">' . esc_html($keyword) . '</span> 获取密码';
        $qr_or_link = '<img src="' . esc_url($qr_url) . '" alt="公众号二维码" class="w-40 h-40 mx-auto mb-4 p-2 bg-white border rounded">';
        $tips = '';
    }

    $output = '
    <div id="' . $html_id . '" class="pay-locker-box bg-gray-50 dark:bg-gray-800 border border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center my-8">
        <div class="locker-mask">
            <div class="mb-4"><span class="text-4xl">' . $icon . '</span></div>
            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-2">' . $title . '</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">' . $desc . '</p>
            ' . $qr_or_link . '
            ' . $tips . '
            <div class="flex justify-center gap-2 max-w-xs mx-auto flex-col sm:flex-row mt-4">
                <input type="text" id="pwd-' . $html_id . '" placeholder="请输入密码" class="px-4 py-2 border rounded w-full dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:border-primary text-center sm:text-left">
                <button onclick="unlock_content(\'' . $html_id . '\', \'' . $final_payload . '\')" class="px-4 py-2 bg-primary text-white rounded hover:bg-blue-600 transition-colors whitespace-nowrap">解锁</button>
            </div>
            <div id="error-' . $html_id . '" class="text-red-500 text-sm mt-3 hidden font-bold">密码错误，请重试</div>
        </div>
        <div class="locker-content hidden text-left prose dark:prose-invert max-w-none"></div>
    </div>
    ';
    return $output;
}
add_shortcode( 'pay', 'pay_content_shortcode' );

// 8. 注入本地化 JS 脚本
function gzh_lock_footer_scripts() {
    $crypto_js = get_template_directory_uri() . '/assets/js/crypto-js.min.js';
    echo '<script src="' . esc_url($crypto_js) . '"></script>';

    // 解密 JS
    echo '<script>';
    echo 'function unlock_content(id, payload_hex) {';
    echo '    const pwdInput = document.getElementById("pwd-" + id);';
    echo '    const password = pwdInput.value.trim();';
    echo '    const errorBox = document.getElementById("error-" + id);';
    echo '    const box = document.getElementById(id);';
    echo '    const contentBox = box.querySelector(".locker-content");';
    echo '    const maskBox = box.querySelector(".locker-mask");';
    echo '    errorBox.classList.add("hidden");';
    echo '    if (!password) { errorBox.innerText = "请输入密码"; errorBox.classList.remove("hidden"); return; }';
    echo '    try {';
    echo '        const ivHex = payload_hex.substr(0, 32);';
    echo '        const cipherHex = payload_hex.substr(32);';
    echo '        const iv = CryptoJS.enc.Hex.parse(ivHex);';
    echo '        const ciphertext = CryptoJS.enc.Hex.parse(cipherHex);';
    echo '        const key = CryptoJS.SHA256(password);';
    echo '        const decrypted = CryptoJS.AES.decrypt({ ciphertext: ciphertext }, key, { iv: iv, mode: CryptoJS.mode.CBC, padding: CryptoJS.pad.Pkcs7 });';
    echo '        const resultStr = decrypted.toString(CryptoJS.enc.Utf8);';
    echo '        const tokens = ["GZH_VERIFY_TOKEN_", "PAY_VERIFY_TOKEN_"];';
    echo '        let realContent = null;';
    echo '        for (let token of tokens) {';
    echo '            if (resultStr.startsWith(token)) {';
    echo '                realContent = resultStr.substring(token.length);';
    echo '                break;';
    echo '            }';
    echo '        }';
    echo '        if (realContent !== null) {';
    echo '            maskBox.classList.add("hidden");';
    echo '            contentBox.classList.remove("hidden");';
    echo '            contentBox.innerHTML = realContent;';
    echo '        } else { throw new Error("Verification failed"); }';
    echo '    } catch (e) {';
    echo '        errorBox.innerText = "密码错误，请核对公众号回复的数字";';
    echo '        errorBox.classList.remove("hidden");';
    echo '        pwdInput.classList.add("border-red-500");';
    echo '        setTimeout(() => pwdInput.classList.remove("border-red-500"), 500);';
    echo '    }';
    echo '}';
    echo '</script>';
}
add_action( 'wp_footer', 'gzh_lock_footer_scripts' );

// 9. 导航菜单 Walker
class Less_Nav_Walker extends Walker_Nav_Menu {
    function start_lvl( &$output, $depth = 0, $args = null ) {
        $output .= '<ul class="sub-menu absolute top-full left-0 bg-white dark:bg-gray-800 shadow-lg rounded-md py-2 min-w-[160px] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 transform translate-y-2 group-hover:translate-y-0 border border-gray-100 dark:border-gray-700">';
    }
    function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        if ( $args->walker->has_children ) { $classes[] = 'group relative h-full flex items-center'; }
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
        $output .= '<li class="' . esc_attr( $class_names ) . '">';
        $atts = array( 'title' => $item->attr_title, 'target' => $item->target, 'rel' => $item->xfn, 'href' => $item->url );
        $atts['class'] = ($depth === 0) ? 'flex items-center gap-1 hover:text-primary transition-colors py-4 px-2' : 'block px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-primary transition-colors';
        $attributes = '';
        foreach ( $atts as $attr => $value ) { if ( ! empty( $value ) ) { $attributes .= ' ' . $attr . '="' . esc_attr( $value ) . '"'; } }
        $item_output = $args->before . '<a'. $attributes .'>' . $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
        if ( $args->walker->has_children && $depth === 0 ) {
            $item_output .= ' <svg class="text-xs opacity-70 group-hover:opacity-100 transition-opacity ml-1" fill="currentColor" width="1em" height="1em" viewBox="0 0 512 512"><path d="M233.4 406.6c12.5 12.5 32.8 12.5 45.3 0l192-192c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L256 338.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l192 192z"/></svg>';
        }
        $item_output .= '</a>' . $args->after;
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}

// 10. 获取图标函数
function less_get_icon( $name, $classes = '', $style = 'solid' ) {
    $icon_path = get_template_directory() . '/assets/icons/' . $style . '/' . $name . '.svg';
    if ( file_exists( $icon_path ) ) {
        $svg = file_get_contents( $icon_path );
        $svg = str_replace( '<svg', '<svg class="' . esc_attr( $classes ) . '" fill="currentColor" width="1em" height="1em"', $svg );
        return $svg;
    }
    return ''; 
}

// 11. 获取本地随机封面图（自动扫描目录）
function less_get_random_cover() {
    static $images = null;

    if ($images === null) {
        $random_dir = get_template_directory() . '/assets/images/random/';
        $random_url = get_template_directory_uri() . '/assets/images/random/';

        if (is_dir($random_dir)) {
            $files = glob($random_dir . '*.{jpg,jpeg,png,webp}', GLOB_BRACE);
            $images = array();

            foreach ($files as $file) {
                $images[] = $random_url . basename($file);
            }
        }

        if (empty($images)) {
            $images = array($random_url . '1.jpg');
        }
    }

    $count = count($images);
    $num = get_the_ID() % $count;
    return $images[$num];
}


/**
 * 🟢 12. 修复 Gravatar 头像 (使用 Cravatar)
 * Cravatar 是国内稳定的 Gravatar 镜像服务
 */
function less_replace_gravatar($avatar) {
    $avatar = str_replace(array("//www.gravatar.com", "//0.gravatar.com", "//1.gravatar.com", "//2.gravatar.com", "//secure.gravatar.com", "//weavatar.com"), "//cravatar.cn", $avatar);
    return $avatar;
}
add_filter('get_avatar', 'less_replace_gravatar');

/**
 * 13. 统一控制所有页面的文章数量
 * 🔧 核心修复: 优先级设为 1，确保在 WordPress 核心判定 404 之前完成页数计算
 */
function less_unified_posts_per_page( $query ) {
    // 只在前台且主查询时生效
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }

    $options = get_option( 'less_options' );
    $num = 10; // 默认值

    // 1. 首页或博客首页
    if ( $query->is_home() || $query->is_front_page() ) {
        $num = isset($options['posts_per_page_home']) && $options['posts_per_page_home'] > 0
            ? intval($options['posts_per_page_home'])
            : 10;
        $query->set( 'ignore_sticky_posts', 1 );
    }
    // 2. 分类、标签、归档页
    elseif ( $query->is_category() || $query->is_tag() || $query->is_archive() || $query->is_search() ) {
        $num = isset($options['posts_per_page_archive']) && $options['posts_per_page_archive'] > 0
            ? intval($options['posts_per_page_archive'])
            : 10;

        // 关键：如果是分类页，明确告知查询对象
        if ( $query->is_category() ) {
            $cat = get_queried_object();
            if ( $cat && isset( $cat->term_id ) ) {
                $query->set( 'cat', $cat->term_id );
            }
        }
    }

    $query->set( 'posts_per_page', $num );

    // 3. 物理页码强制同步（防止 paged 参数丢失）
    $paged = 1;
    if ( get_query_var('paged') ) {
        $paged = get_query_var('paged');
    } elseif ( get_query_var('page') ) {
        $paged = get_query_var('page');
    } elseif ( isset( $_SERVER['REQUEST_URI'] ) && preg_match('#/page/(\d+)/?#', $_SERVER['REQUEST_URI'], $m) ) {
        $paged = intval($m[1]);
    }

    if ( $paged > 1 ) {
        $query->set( 'paged', $paged );
        $query->set( 'page', $paged );
    }
}
// 优先级设为 1，比默认的 10 更早执行
add_action( 'pre_get_posts', 'less_unified_posts_per_page', 1 );

/**
 * 14. 修复分类页的分页链接
 * 🔧 防止分类页翻页时跳回首页
 */
function less_fix_category_pagination_link( $url ) {
    if ( is_category() ) {
        $cat = get_queried_object();
        if ( $cat && isset( $cat->slug ) ) {
            $slug = $cat->slug;
            // 如果链接中没有 category 路径，强制添加
            if ( strpos( $url, '/category/' ) === false && strpos( $url, '/page/' ) !== false ) {
                $url = str_replace( home_url('/page/'), home_url("/category/$slug/page/"), $url );
            }
        }
    }
    return $url;
}
add_filter( 'get_pagenum_link', 'less_fix_category_pagination_link' );
?>