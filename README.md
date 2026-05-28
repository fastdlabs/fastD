<p align="center">
    <img src="https://avatars0.githubusercontent.com/u/20292713?s=200&v=4" width="100px" height="100px"/>
</p>
<h1 align="center">FastD</h1>

<p align="center">🚀 A high performance PHP API framework based on PSR standards and Swoole extension.</p>

<p align="center">
<a href="https://travis-ci.org/fastdlabs/fastD"><img src="https://travis-ci.org/fastdlabs/fastD.svg?branch=master" /></a>
<a href="https://scrutinizer-ci.com/g/fastdlabs/fastD/?branch=master"><img src="https://scrutinizer-ci.com/g/fastdlabs/fastD/badges/quality-score.png?b=master" title="Scrutinizer Code Quality"></a>
<a href="https://scrutinizer-ci.com/g/fastdlabs/fastD/?branch=master"><img src="https://scrutinizer-ci.com/g/fastdlabs/fastD/badges/coverage.png?b=master" alt="Code Coverage"></a>
<a href="http://www.php.net/"><img src="https://img.shields.io/badge/php-%3E%3D8.2-brightgreen" /></a>
<a href="http://www.swoole.com/"><img src="https://img.shields.io/badge/swoole-%3E%3D4.5-brightgreen"/></a>
<a href="https://fastdlabs.com/"><img src="https://poser.pugx.org/fastd/fastd/license" /></a>
</p>

FastD 是一个轻量级的高性能 PHP Web 开发框架，基于 PSR 标准和 Swoole 扩展构建。适用于对性能有严格要求的 API 服务场景，提供灵活的扩展机制让开发者轻松构建自己的服务。

## ✨ 特性

- 🎯 **PSR 标准兼容**: 完全遵循 PSR-11 容器接口规范
- ⚡ **高性能**: 基于 Swoole 协程引擎，支持异步非阻塞 I/O
- 📦 **轻量级**: 核心组件精简，按需加载扩展
- 🔧 **灵活扩展**: 基于 ServiceProvider 的插件化架构
- 🎭 **多运行环境**: 支持 Swoole Server、FastCGI、Console、Process 等多种运行模式
- 📝 **完善测试**: 全面的单元测试覆盖，保证代码质量

## 📋 要求

- PHP >= 8.2
- Swoole >= 4.5 (可选，用于高性能模式)
- Composer

## 🚀 快速开始

### 安装

```bash
composer create-project fastd/fastd my-app
cd my-app
```

### 启动服务

```bash
# Swoole 模式（推荐）
php server start

# FastCGI 模式（传统 PHP-FPM）
# 配置 Nginx 指向 web 目录

# 命令行模式
php console list

# 多进程模式
php process start
```

### 创建路由

```php
// config/routes.php
return [
    ['GET', '/hello', function($request) {
        return json(['message' => 'Hello, FastD!']);
    }],
];
```

### 访问服务

```bash
curl http://localhost:9999/hello
# {"message":"Hello, FastD!"}
```

## 📚 架构设计

### 核心组件

- **Application**: 应用核心类，继承自 PSR-11 容器
- **Runtime**: 运行时抽象，支持多种运行环境
- **Container**: 依赖注入容器，支持延迟实例化和单例模式
- **Event**: 事件驱动架构，基于 PSR-14 事件调度器
- **Routing**: 高性能路由系统
- **HTTP**: PSR-7 HTTP 消息实现
- **Middleware**: PSR-15 中间件支持

### 运行模式

1. **Swoole Server**: 常驻内存，极致性能
2. **FastCGI**: 兼容传统 PHP-FPM 部署
3. **Console**: 命令行工具支持
4. **Process**: 多进程任务处理

## 📖 文档

我们希望不仅仅是提供一个开发框架，更希望能够将自己的经验整理成解决方案，开发套件分享和贡献给社区。

- [中文文档](http://docs.fastdlabs.com/#/zh-cn/3.2/readme)
- [English Document](https://docs.fastdlabs.com/#/en-us/3.2/readme)

## 🧪 测试

```bash
# 运行单元测试
php vendor/bin/phpunit

# 查看测试覆盖率
php vendor/bin/phpunit --coverage-html coverage
```

## 🤝 贡献

感谢所有为 FastD 做出贡献的开发者：

- [yyz26371945](https://github.com/yyz26371945)
- [RunnerLee](https://github.com/RunnerLee)
- [zqhong](https://github.com/zqhong)
- [xsharp](https://github.com/xsharp)

欢迎提交 Issue 和 Pull Request！

## 🙏 鸣谢

![](https://s103.ggwan.com/mainlinghitv2/images/public/logo.png)

非常欢迎感兴趣，愿意参与其中，共同打造更好 PHP 生态、Swoole 生态的开发者。

如果你乐于此，却又不知如何开始，可以试试下面这些事情：

- 在你的系统中使用，将遇到的问题 [反馈](https://github.com/JanHuang/fastD/issues)
- 有更好的建议？欢迎联系 [bboyjanhuang@gmail.com](mailto:bboyjanhuang@gmail.com) 或 [新浪微博:编码侠](http://weibo.com/ecbboyjan)
- 帮助改进文档或翻译
- 分享你的使用经验和最佳实践

## 📮 联系

如果你在使用中遇到问题，请联系：

- Email: [bboyjanhuang@gmail.com](mailto:bboyjanhuang@gmail.com)
- Weibo: [编码侠](http://weibo.com/ecbboyjan)
- GitHub: [Issues](https://github.com/JanHuang/fastD/issues)

## 📄 License

MIT License
