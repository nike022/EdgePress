# EdgePress - WordPress 边缘静态化主题

**本项目由阿里云ESA提供加速、计算和保护**

![阿里云ESA](https://img.alicdn.com/imgextra/i3/O1CN01H1UU3i1Cti9lYtFrs_!!6000000000139-2-tps-7534-844.png)

一个专为静态站点生成优化的现代化 WordPress 主题，完美适配 Simply Static 插件，可部署到阿里云 ESA Pages、Cloudflare Pages 等边缘平台。

## 核心优势

### 🎯 完整的 WordPress 体验 + 零服务器成本

**无需放弃 WordPress 的任何功能**：
- ✅ 在本地安装 WordPress，使用熟悉的后台管理界面
- ✅ 使用可视化编辑器发布和管理文章
- ✅ 使用插件、主题、小工具等所有 WordPress 功能
- ✅ 完整的分类、标签、菜单管理

**无需服务器和数据库**：
- ✅ 本地运行 WordPress（XAMPP/MAMP/LocalWP）
- ✅ 使用免费的 Simply Static 插件生成静态文件
- ✅ 推送到 GitHub，ESA Pages 自动部署
- ✅ 零服务器成本，无需维护 PHP 和 MySQL

### 💡 突破 Simply Static 免费版限制

Simply Static 付费版才有的功能，我们已经免费实现：

**1. 静态搜索功能** ✅
- 付费版功能：需要升级才能使用搜索
- EdgePress 方案：集成 Pagefind 静态搜索，完全免费
- 优势：支持中文全文搜索，即时响应，深色模式

**2. 评论系统** ✅
- 付费版功能：需要升级才能集成第三方评论
- EdgePress 方案：集成 Waline 边缘评论系统，完全免费
- 优势：实时评论、浏览量统计、表情支持

**3. 内容加密** ✅
- EdgePress 独有：AES-256-CBC 加密，支持公众号引流和付费内容
- 优势：静态 HTML 中实现内容保护，支持多种变现方式

### 🚀 极简工作流程

```
本地 WordPress 编辑文章
    ↓
Simply Static 生成静态文件（免费版）
    ↓
推送到 GitHub（包含 esa.jsonc）
    ↓
ESA Pages 自动部署（自动执行 pagefind 构建）
    ↓
全球边缘加速访问
```

**整个流程完全免费，无需任何付费插件或服务器！**

## 在线演示

- **部署 URL**: https://wp-blog.e4dd06ac.er.aliyun-esa.net
- **WordPress 主题源码**: https://github.com/nike022/EdgePress
- **静态站点仓库**: https://github.com/nike022/wp-blog

## 仓库说明

本项目采用双仓库结构：

1. **WordPress 主题源码仓库**（本仓库）
   - 包含完整的 WordPress 主题源代码
   - 展示针对静态化的优化工作
   - 包含所有自定义功能和小工具

2. **静态站点仓库**
   - 包含 Simply Static 生成的静态 HTML 文件
   - 包含 Pagefind 生成的搜索索引
   - 实际部署到 ESA Pages 的内容

这种结构既展示了开发过程，又提供了可部署的最终产物。

## 项目介绍

EdgePress 是一个轻量级、高性能的 WordPress 主题，专为静态站点生成而设计。通过 Simply Static 插件将 WordPress 站点转换为纯静态 HTML，部署到边缘平台后可获得极致的访问速度和安全性。

### 创意卓越 🎨

- **极简设计理念**：采用 Tailwind CSS 构建，界面简洁优雅，专注内容呈现
- **深色模式支持**：自动适配系统主题，提供舒适的阅读体验
- **响应式布局**：完美适配桌面、平板、手机等各种设备
- **动态幻灯片**：首页支持自定义幻灯片展示，可配置图片、标题和链接

### 应用价值 💡

- **零服务器成本**：静态化后部署到 ESA Pages，无需维护 PHP 服务器
- **极致性能**：静态 HTML + 边缘加速，全球访问速度提升 10 倍以上
- **安全可靠**：无数据库依赖，杜绝 SQL 注入等安全隐患
- **SEO 友好**：自动生成 meta 标签，支持自定义首页 SEO 信息
- **开箱即用**：提供完整的小工具系统，无需编码即可自定义侧边栏

### 技术探索 🚀

#### 1. 静态化优化技术

- **Tailwind Play CDN 集成**：使用 JIT 编译器，按需生成 CSS，减少文件体积
- **本地资源优先**：优先加载本地 JS/CSS 资源，避免 CDN 依赖
- **路径兼容处理**：完美处理 `.html` 后缀，确保静态站点链接正确

#### 2. 边缘功能集成

- **Waline 评论系统**：集成边缘评论服务，静态站点也能实时互动
- **浏览量统计**：基于 Waline API 的边缘统计，无需后端数据库
  - 静态 HTML 中嵌入 `<span class="waline-pageview-count">` 元素
  - 通过 [waline-init.js](assets/js/waline-init.js) 调用 Waline API 实时获取和更新浏览量
  - 文章详情页自动增加浏览量，列表页仅显示浏览量
  - 完全基于边缘计算，无需 WordPress 数据库支持
- **内容加密**：支持 AES-256-CBC 加密，静态 HTML 中实现付费内容保护

#### 3. 性能优化

- **图片懒加载**：所有图片默认 `loading="lazy"`，优化首屏加载
- **CSS 内联优化**：关键 CSS 内联，减少渲染阻塞
- **JavaScript 模块化**：将 Waline 初始化代码独立为 JS 文件，避免 PHP 转义问题

#### 4. 静态站点搜索

- **Pagefind 集成**：使用 Pagefind 实现静态站点全文搜索
- **零后端依赖**：搜索索引在构建时生成，无需服务器支持
- **中文优化**：完整的中文分词和搜索支持
- **即时搜索**：输入即搜索，无需等待
- **深色模式适配**：搜索界面自动适配深色模式

#### 5. 开发者友好

- **完整的小工具系统**：最新文章、随机文章、热门标签、图片广告等
- **自定义短代码**：`[gzh2v]` 公众号加密、`[pay]` 付费内容等
- **多种支付方式**：支持微信、支付宝、发卡平台、知识星球等

## 核心功能

### 内容管理
- ✅ 文章列表与详情页
- ✅ 分类与标签归档
- ✅ Pagefind 静态搜索（支持中文全文搜索）
- ✅ 分页导航（静态化兼容）

### 主题特性
- ✅ 深色模式切换
- ✅ 响应式设计
- ✅ 自定义 Logo（支持深色/浅色双 Logo）
- ✅ 自定义导航菜单（支持二级菜单）
- ✅ 首页幻灯片（支持自定义或自动获取置顶文章）

### 评论系统
- ✅ Waline 评论集成
- ✅ 浏览量统计
- ✅ 最新评论小工具
- ✅ 表情符号支持

### 内容加密
- ✅ 公众号加密内容 `[gzh2v]`
- ✅ 付费内容保护 `[pay]`
- ✅ 多种支付方式（微信/支付宝/发卡/星球）
- ✅ AES-256-CBC 加密算法

### SEO 优化
- ✅ 自定义首页标题、描述、关键词
- ✅ 自动生成文章 meta 描述
- ✅ 标签自动转换为关键词
- ✅ 归档页面 SEO 优化

## 技术栈

- **前端框架**: Tailwind CSS 3.4
- **JavaScript**: 原生 ES6+
- **评论系统**: Waline
- **加密算法**: CryptoJS (AES-256-CBC)
- **静态化工具**: Simply Static
- **部署平台**: 阿里云 ESA Pages

## 快速开始

### 1. 安装主题

```bash
# 下载主题
git clone https://github.com/nike022/EdgePress.git

# 上传到 WordPress
# 将主题文件夹上传到 wp-content/themes/ 目录
# 在 WordPress 后台启用主题
```

### 2. 配置主题

1. 进入 WordPress 后台 → 外观 → EdgePress 主题设置
2. 配置基本信息：Logo、Favicon、SEO 信息
3. 配置 Waline 评论服务器地址（如需评论功能）
   - 需要先部署 Waline 评论服务，参考 [Waline 官方文档](https://waline.js.org/)
   - 支持部署到 Vercel、Railway、Deta、CloudBase 等平台
   - **重要**：部署完成后，需要手动修改主题代码文件中的 Waline 服务器地址，详见 [WALINE_CONFIG.md](WALINE_CONFIG.md)
4. 配置支付二维码（如需内容加密功能）

### 3. 生成静态站点

1. 安装 Simply Static 插件
2. 配置插件：设置 → Simply Static
3. 点击"生成静态文件"
4. 下载生成的 ZIP 文件

### 4. 准备部署文件

1. 解压 Simply Static 生成的 ZIP 文件到本地目录
2. 在静态站点根目录创建 `esa.jsonc` 配置文件：

```json
{
  "buildCommand": "npx pagefind --site .",
  "assets": {
    "directory": "."
  }
}
```

**配置说明**：
- `buildCommand`: ESA Pages 部署时自动执行此命令生成搜索索引
- `assets.directory`: 指定静态资源目录为根目录

⚠️ **重要提示**：`esa.jsonc` 文件中的 `buildCommand: "npx pagefind --site ."` 是必需的！如果不配置此构建命令，搜索功能将无法使用。你也可以在 ESA 控制台的构建配置中手动填写此命令。

### 5. 部署到 ESA Pages

1. 将静态文件推送到 GitHub 仓库（包含 `esa.jsonc` 文件）
2. 登录 [ESA 控制台](https://esa.console.aliyun.com/)，选择"边缘计算 > 函数和Pages"
3. 点击"创建"，选择"导入 Github 仓库"
4. 授权并选择你的 GitHub 仓库
5. ESA Pages 会自动：
   - 检测 `esa.jsonc` 配置
   - 执行 `npx pagefind --site .` 生成搜索索引
   - 部署静态文件到边缘节点
6. 部署完成后，获得 `*.er.aliyun-esa.net` 域名

**注意**：无需手动运行 `npx pagefind --site .`，ESA Pages 会在部署时自动执行。

## 项目亮点

### 1. 完美的静态化支持

传统 WordPress 主题在静态化后常常出现各种问题：
- ❌ 分页链接失效
- ❌ 评论系统无法使用
- ❌ JavaScript 代码转义错误
- ❌ 路径不兼容

**EdgePress 主题完美解决了这些问题**：
- ✅ 分页链接自动适配静态路径
- ✅ 集成边缘评论系统
- ✅ JavaScript 代码模块化，避免转义问题
- ✅ 完整的 `.html` 后缀兼容

### 2. 边缘计算最佳实践

- **评论系统**：使用 Waline 边缘评论服务，无需后端数据库
- **浏览量统计**：基于边缘 API 的实时统计
- **内容加密**：前端 AES 解密，后端预加密，完美适配静态站点

### 3. 开发者体验优化

- **模块化设计**：JavaScript 代码独立文件，易于维护
- **完整的文档**：代码注释详细，易于二次开发
- **丰富的小工具**：无需编码即可自定义侧边栏

## 文件结构

```
less/
├── assets/
│   ├── css/
│   │   ├── waline.css          # Waline 样式
│   │   └── tailwind-play.min.css
│   └── js/
│       ├── main.js              # 主题核心 JS
│       ├── waline.js            # Waline SDK
│       ├── waline-init.js       # Waline 初始化（新增）
│       ├── crypto-js.min.js     # 加密库
│       └── tailwind-play.min.js # Tailwind JIT
├── inc/
│   ├── theme-options.php        # 主题设置页面
│   └── widgets.php              # 自定义小工具
├── template-parts/
│   └── content.php              # 文章列表模板
├── comments.php                 # 评论模板
├── footer.php                   # 页脚
├── front-page.php               # 首页模板
├── header.php                   # 页头
├── functions.php                # 主题函数
├── sidebar.php                  # 侧边栏
├── single.php                   # 文章详情页
├── style.css                    # 主题样式
└── README.md                    # 本文件
```

## 使用说明

### 内容加密

#### 公众号加密内容

```
[gzh2v key="8888" keyword="获取密码"]
这里是需要加密的内容
[/gzh2v]
```

#### 付费内容

```
[pay type="wechat" price="9.9" password="8888"]
这里是付费内容
[/pay]
```

支持的支付类型：
- `wechat` - 微信支付
- `alipay` - 支付宝支付
- `card` - 自动发卡平台
- `planet` - 知识星球
- `gzh` - 公众号

### 侧边栏小工具

主题提供以下自定义小工具：
- **Less: 最新文章** - 带缩略图的最新文章列表
- **Less: 随机文章** - 随机文章列表（每次生成静态站时顺序不同）
- **Less: 热门标签** - 标签云
- **Less: 图片广告** - 带链接的图片广告
- **关于作者** - 作者信息卡片（使用自定义 HTML 小工具）

## 性能数据

- **首屏加载时间**: < 1s（ESA Pages 边缘加速）
- **Lighthouse 评分**: 95+ (Performance)
- **文件大小**:
  - HTML: ~50KB (gzip)
  - CSS: ~30KB (Tailwind JIT)
  - JS: ~150KB (含 Waline + CryptoJS)

## 浏览器兼容性

- ✅ Chrome 90+
- ✅ Firefox 88+
- ✅ Safari 14+
- ✅ Edge 90+

## 许可证

GPL-2.0 License

本主题基于 [Less Theme](https://github.com/xzmhxdxh/Less) 进行二次开发和优化。

## 致谢

- [Less Theme](https://github.com/xzmhxdxh/Less) - 原始主题，提供了优秀的基础框架
- [Tailwind CSS](https://tailwindcss.com/) - CSS 框架
- [Waline](https://waline.js.org/) - 评论系统
- [Pagefind](https://pagefind.app/) - 静态搜索引擎
- [CryptoJS](https://cryptojs.gitbook.io/) - 加密库
- [Simply Static](https://wordpress.org/plugins/simply-static/) - 静态化插件
- [阿里云 ESA](https://www.aliyun.com/product/esa) - 边缘加速平台

## 联系方式

- GitHub Issues: [提交问题](https://github.com/nike022/EdgePress/issues)
- 作者: nike022

---

**再次感谢阿里云 ESA 提供的边缘计算平台支持！**
