<?php
/**
 * 手动生成404.html文件
 * 使用方法: 将此文件上传到WordPress根目录，然后访问 https://your-site.com/generate-404.php
 */

// 加载WordPress
require_once('wp-load.php');

// 设置为404状态
global $wp_query;
$wp_query->set_404();
status_header(404);
nocache_headers();

// 开始输出缓冲
ob_start();

// 加载404模板
include(get_404_template());

// 获取输出内容
$html = ob_get_clean();

// 替换URL为目标地址
$current_url = home_url();
$target_url = 'https://blog.900030.xyz'; // 您的Cloudflare Pages地址

$html = str_replace($current_url, $target_url, $html);
$html = str_replace(str_replace('https://', 'http://', $current_url), $target_url, $html);

// 保存为404.html
$output_file = 'generated-404.html';
file_put_contents($output_file, $html);

// 显示结果
header('Content-Type: text/html; charset=UTF-8');
echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>404生成成功</title></head><body>';
echo '<h1>✅ 404页面生成成功！</h1>';
echo '<p>文件已保存为: <strong>' . $output_file . '</strong></p>';
echo '<p>文件大小: ' . filesize($output_file) . ' 字节</p>';
echo '<p><a href="' . $output_file . '">查看生成的404页面</a></p>';
echo '<p><a href="' . admin_url() . '">返回WordPress后台</a></p>';
echo '<hr>';
echo '<p>请下载 <strong>generated-404.html</strong> 文件，重命名为 <strong>404.html</strong>，然后放到静态站点根目录。</p>';
echo '</body></html>';
?>
