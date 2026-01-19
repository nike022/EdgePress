# Waline 评论系统配置指南

## 重要提示

在使用 EdgePress 主题之前，你需要先部署自己的 Waline 评论服务器，并在主题中配置服务器地址。

## 第一步：部署 Waline 服务器

请参考 [Waline 官方文档](https://waline.js.org/) 部署你自己的 Waline 服务器。

推荐部署平台：
- **Vercel**（推荐）：免费、稳定、全球 CDN
- **Railway**：每月 $5 免费额度
- **Deta**：完全免费
- **CloudBase**：适合国内用户

部署完成后，你会获得一个 Waline 服务器地址，例如：
- `https://your-waline-server.vercel.app`
- `https://your-waline-server.railway.app`

## 第二步：配置主题中的 Waline 服务器地址

部署完成后，需要在以下文件中将 `https://your-waline-server.vercel.app` 替换为你的实际 Waline 服务器地址：

### 1. comments.php（评论区）
找到第 83 行：
```php
serverURL: 'https://your-waline-server.vercel.app',
```
替换为你的服务器地址。

### 2. assets/js/waline-init.js（浏览量统计）
找到第 32、40、50 行的三处：
```javascript
serverURL: 'https://your-waline-server.vercel.app',
```
全部替换为你的服务器地址。

**重要提示**：配置完成后，需要重新使用 Simply Static 生成静态文件并部署，更改才会生效。

## 第三步：验证配置

配置完成后：
1. 在 WordPress 中发布一篇测试文章
2. 使用 Simply Static 生成静态文件
3. 部署到 ESA Pages
4. 访问文章页面，检查评论区和浏览量是否正常显示

## 常见问题

### Q: 为什么需要自己部署 Waline 服务器？
A: Waline 是一个开源的评论系统，需要后端服务器来存储评论数据。每个用户都需要部署自己的服务器来保护数据隐私和安全。

### Q: Waline 服务器部署需要付费吗？
A: 推荐使用 Vercel 或 Deta 等平台，它们提供免费额度，对于个人博客完全够用。

### Q: 如何迁移评论数据？
A: 如果你之前使用其他评论系统（如 LeanCloud），可以参考 [Waline 数据迁移文档](https://waline.js.org/guide/deploy/import.html)。

## 技术支持

如有问题，请访问：
- Waline 官方文档：https://waline.js.org/
- EdgePress GitHub Issues：https://github.com/nike022/EdgePress/issues
