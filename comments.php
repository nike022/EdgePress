<?php
/**
 * 静态版 Waline 评论模板 (中文直击版)
 * 采用原生 JSON 注入语言包，保证中文提示 100% 生效。
 */

if ( post_password_required() ) {
    return;
}
?>

<div id="comments" class="comments-area mt-10 pt-10 border-t border-gray-100 dark:border-gray-700">

    <h3 class="comments-title text-xl font-medium mb-6 text-gray-900 dark:text-gray-100 border-l-4 border-primary pl-3 leading-none">
        评论区
    </h3>

    <div id="waline"></div>

    <script>
    // 🟢 Waline SDK 已在 functions.php 全局加载，这里只负责初始化评论区
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Waline === 'undefined') {
            console.warn('Waline SDK 未加载');
            return;
        }

        const nativeZhCNLocale = {
            nick: '昵称',
            mail: '邮箱',
            link: '网址',
            placeholder: '来发条评论吧~ (无需登录)',
            sofa: '快来抢沙发吧~',
            submit: '发表评论',
            comment: '评论',
            refresh: '刷新',
            more: '加载更多',
            uploading: '上传中...',
            login: '登录',
            logout: '退出',
            profile: '个人资料',
            admin: '管理员',
            sticky: '置顶',
            word: '字',
            anonymous: '匿名',
            optional: '可选',
            nickError: '昵称不能少于3个字符',
            mailError: '请填写正确的邮箱地址',
            linkError: '请填写正确的网址',
            wordHint: '字数应在 $0 到 $1 之间，当前 $2 字',
            approved: '已通过',
            waiting: '待审核',
            spam: '垃圾评论',
            unsticky: '取消置顶',
            cancelReply: '取消回复',
            reply: '回复',
            like: '赞',
            cancelLike: '取消赞',
            latest: '最新',
            oldest: '最早',
            hottest: '最热',
            gifSearchPlaceholder: '表情搜索',
            uploadImage: '上传图片',
            seconds: '秒前',
            minutes: '分钟前',
            hours: '小时前',
            days: '天前',
            now: '刚刚',
            level0: 'Lv0',
            level1: 'Lv1',
            level2: 'Lv2',
            level3: 'Lv3',
            level4: 'Lv4',
            level5: 'Lv5',
            level6: 'Lv6',
            level7: 'Lv7',
            level8: 'Lv8',
            level9: 'Lv9',
        };

        Waline.init({
            el: '#waline',
            serverURL: 'https://your-waline-server.vercel.app',
            dark: 'html.dark',
            path: window.location.pathname,
            locale: nativeZhCNLocale,
            meta: [],
            imageUploader: true,
            login: 'disable',
            search: false,
            emoji: ['https://cdn.jsdelivr.net/npm/@waline/emojis@1.2.0/weibo'],
            pageview: false,  // 🟢 禁用评论区的浏览量统计，改用全局统计
            comment: true,
            placeholder: '来发条评论吧~ (无需登录)',
            copyright: false,
        });
    });
    </script>

    <style>
        /* CSS 强制移除 Markdown 和登录按钮 (保持不变，已在上一轮验证有效) */
        #waline {
            --waline-theme-color: #3b82f6; 
            --waline-active-color: #2563eb;
        }
        /* ... (省略其他样式代码，保持精简) ... */
        .no-comments { display: none; }
        .wl-header img { border-radius: 50% !important; }

        /* 隐藏 Markdown 指南图标 */
        a.wl-action[title="Markdown Guide"] { 
            display: none !important; 
        }

        /* 隐藏登录相关按钮 */
        .wl-login-info,
        .wl-info > button.wl-btn:not(.primary) {
            display: none !important;
        }
        
        /* 隐藏用户信息栏 (配合 meta: []) */
        .wl-header { display: none !important; }

        /* 隐藏图片、搜索按钮 (双重保险) */
        .wl-search-trigger {
            display: none !important;
        }
    </style>

</div>