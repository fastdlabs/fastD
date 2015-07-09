- 新增 `dump` 调试方法，可以将调试数据放到 `debug bar` 中显示调试。实例: `$this->dump(['name' => 'jan']), $this->dump(new stdClass());` 调试数据支持大部分类型
  
- 修复命令行 convert bug
  
- 优化 `url()` 模板方法和 `generateUrl()` 方法的显示方式，更加优雅地显示URL地址信息
  
- 修复 `generateUrl()` 方法重复产生 `requestUri` 的bug
  
- 优化debug status code 非法时处理方式，统一无法识别的status code均为500错误码
  
- 修复 `http` 组件获取 `base url` bug
  
- 修复 `RestEvent::responseJson` 方法输出html的问题
  
- 修复 `RedirectException` 不能跳转问题
  
  ​
