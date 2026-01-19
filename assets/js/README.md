# Tailwind CSS 本地化配置说明

## 问题原因
之前的错误是将 CSS 文件当作 JS 加载，导致 "MIME type ('text/css') is not executable" 错误。

## 正确解决方案

### 1. 手动下载 tailwind-play.min.js
- 访问：https://cdn.tailwindcss.com
- 右键 → 另存为 → 保存为 `tailwind-play.min.js`
- 保存到：`/assets/js/` 目录

### 2. 或使用命令行下载
```bash
# Windows (PowerShell)
Invoke-WebRequest -Uri "https://cdn.tailwindcss.com" -OutFile "assets/js/tailwind-play.min.js"

# macOS/Linux
curl -o assets/js/tailwind-play.min.js https://cdn.tailwindcss.com
```

### 3. 文件位置确认
确保文件路径为：
```
less/
├── assets/
│   └── js/
│       ├── main.js
│       ├── waline.js
│       └── tailwind-play.min.js  ← 下载的文件放在这里
```

## 验证方法
1. 上传文件后，刷新网站
2. 如果看到管理员提示，说明文件未正确放置
3. 检查浏览器控制台，应该没有 "tailwind is not defined" 错误

## 优势
- **稳定性**：不依赖外部 CDN，避免网络问题
- **速度**：本地加载更快
- **兼容性**：完全兼容现有主题，无需修改 HTML 类名