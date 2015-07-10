- 新增 `dump` 调试方法，可以将调试数据放到 `debug bar` 中显示调试。实例: `$this->dump(['name' => 'jan']), $this->dump(new stdClass());` 调试数据支持大部分类型
- 修复命令行 convert bug
- 优化 `url()` 模板方法和 `generateUrl()` 方法的显示方式，更加优雅地显示URL地址信息
- 修复 `generateUrl()` 方法重复产生 `requestUri` 的bug
- 优化debug status code 非法时处理方式，统一无法识别的status code均为500错误码
- 修复 `http` 组件获取 `base url` bug
- 修复 `RestEvent::responseJson` 方法输出html的问题
- 修复 `RedirectException` 不能跳转问题


### 07/09/2015

- 修复命令行 `Undefined setEnv|getEnv` 方法的bug
- 修复 `Kernel\Commands\Generator` 和 `Kernel\Commands\RouteDump` 命令工具
- 修复项目模块 `Commands` 目录找不到的bug
- 新增 `Protocol\Http\Attribute::hasGet` 方法，包含四个参数. `name, default, raw, filter` 用于获取不存在的属性的时候，默认返回 `default` 参数


### 07/10/2015

- 修复 `FastD\Protocol\Http\File\Uploaded\Uploaded::upload` 获取文件失败的bug
- 优化 `FastD\Protocol\Http\File\Uploaded\Uploaded::upload` 重复上传为空的问题
- 增加 `FastD\Protocol\Http\File\File::getHash` 获取文件哈希值方法
