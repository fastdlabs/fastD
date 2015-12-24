### v2.0.0 (2016-xx-xx)

- 调整 bundle 结构
- 优化 framework 执行流程
- 优化 event 基础方法
- 升级组件到 2.0
- 优化结构
- 优化内部调度
- 优化程序执行速度
- 支持自定义模板扩展,函数,过滤器
- 优化基础事件类 Event
- 优化配置文件
- 优化事件调度方式
- 增加调度处理类
- 集成PHPUnit, WebTestCase
- 增加容器 singleton 方法,用于单例化对象
- 增加路由请求事件转发功能 forward
- 移除 Event::dump 方法
- 集成 Swoole 服务组件
- 增加 Twig 扩展, `FastD\Framework\Extensions\TplExtension`
- 增加注释路由定义方式
- 调整 FastD\Framework\Bundle\Events\Http\Event -> FastD\Framework\Bundle\Controllers\Controller
- 调整 app/console
- 支持 SwooleHttpServer
- 增加 ORM 映射操作
- 增加 db:update, route:cache 命令


### v1.0.* (07/09/2015)

- 新增 `dump` 调试方法，可以将调试数据放到 `debug bar` 中显示调试。实例: `$this->dump(['name' => 'jan']), $this->dump(new stdClass());` 调试数据支持大部分类型
- 修复命令行 convert bug
- 优化 `url()` 模板方法和 `generateUrl()` 方法的显示方式，更加优雅地显示URL地址信息
- 修复 `generateUrl()` 方法重复产生 `requestUri` 的bug
- 优化debug status code 非法时处理方式，统一无法识别的status code均为500错误码
- 修复 `http` 组件获取 `base url` bug
- 修复 `RestEvent::responseJson` 方法输出html的问题
- 修复 `RedirectException` 不能跳转问题
- 修复命令行 `Undefined setEnv|getEnv` 方法的bug
- 修复 `Kernel\Commands\Generator` 和 `Kernel\Commands\RouteDump` 命令工具
- 修复项目模块 `Commands` 目录找不到的bug
- 新增 `Protocol\Http\Attribute::hasGet` 方法，包含四个参数. `name, default, raw, filter` 用于获取不存在的属性的时候，默认返回 `default` 参数


### v1.0.* (07/10/2015)

- 修复 `FastD\Protocol\Http\File\Uploaded\Uploaded::upload` 获取文件失败的bug
- 优化 `FastD\Protocol\Http\File\Uploaded\Uploaded::upload` 重复上传为空的问题
- 增加 `FastD\Protocol\Http\File\File::getHash` 获取文件哈希值方法


### v1.0.* (07/12/2015)

- 添加 `FastD\Protocol\Http\Attribute\ServerAttribute::getRootPath` 方法，获取 `baseUrl` 的目录
- 修复 `Kernel\Events\TemplateEvent::asset` 模板函数


### v1.0.* (07/13/2015)

- 优化 `FastD\Protocol\Http\Attribute\ServerAttribute::getBaseUrl` 方法。
- 修复 `debug bar javascript resources conflict` 的问题
- 修复 `FastD\Routing\Matcher\RouteMatcher::match` 迭代器bug
- `Protocol` 组件正式更名为 `Http` 组件，原 `Protocol` 即将废弃


### v1.0.* (07/14/2015)

- 修复并优化 `FastD\Database\Pagination\QueryPagination` 分页对象 `showPage && showList` 参数冲突。
- 添加 `FastD\Database\Repository\Repository::pagination($page, $showList, $showPage, $lastId)`  方法，用于查询分页。
- 修复 `FastD\Database\Pagination\QueryPagination::getResult->offset= 0` 的bug
- 调整 `FastD\Debug\Exceptions\JsonException::__construct(array $message, int $code)` 构造参数


### v1.0.* (07/15/2015)

- 新增 `__initialize` 方法，用于事件初始化后操作.


### v1.0.* (07/18/2015)

- 新增 `fastd/framework` 组件包，用于整合框架常用资源，方便快速创建的自己的框架和模块
- 优化 `FastD\Http\Attribute\ServerAttribute::getBaseUrl` 地址显示
- 调整 `composer.json` 加载选项
- 修复 `FastD\Container\ServiceProvider::getServiceName` `Class not found`  bug
- 整合 `fastd/framework` 到 `fastd/fastd` 中，并为原来框架和目录结构瘦身。优化目录结构和自动加载选项
- 优化路由自动加载选项
- 调整全局配置 `app/config/config` => `app/config/global.php`
- 新增模块配置选项 `[ModuleName]/Resources/config/config.php`， 可以为不同模块配置不同信息
- 修复路由映射控制器对象参数依赖注入问题
- 修复命令行扫描bug


### v1.0.* (07/23/2015)

- 优化`fastd/framework` : `FastD\Framework\Events\TemplateEvent` 扩展函数


### v1.0.* (07/30/2015)

- 优化路由调试命令行 `route:dump` 支持模块包调试
- 添加数个模块包，优化模块包组件


### v1.0.* (08/02/2015)

- 添加 `FastD\Database\Repository\Repository::get|setFields` 方法。
  
- 添加 `FastD\Database\Repository\Repository::buildTableFieldsData parseTableFieldsData` 等方法，详情见代码文档。
  
- 优化数据库获取数据自动转换数据格式
  
- 优化 `FastD\Config\Config` 组件，优化配置参数合并
  
- 添加 `config:cache` 命令行
  
  ​
