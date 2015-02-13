#dobee-php-simple-framework

php simple framework: dobee

**简单**、**高效**、**敏捷**、**灵活**

#安装

本`donee php simple framework`依赖composer自动载入，在安装本框架前需要确保正确安装composer依赖管理。

`git clone https://coding.net/janhuang/dobee-php-simple-framework.git`

进入框架项目路径

`composer -vvv install`

**注意：因为国内访问composer超级缓慢的问题，建议使用代理或者国内镜像进行安装**

##1.配置
目前支持`yml`, `ini`, `php`三种配置文件类型。
###1.1 YML(YAML)
I
**注: yml(yaml)文件中，锁进使用空格锁进，一个tab等于4个空格键**

####1.1.1普通K/V字符串写法:  
````
name: janhuang
````

####1.1.2数组写法: 

```
name: 
	- janhuang
	- other
author:
	name:
		-janhuang
		-other
```

####1.1.3变量写法: 
**暂不支持
**

####1.1.4引用写法: 
**暂不支持
**

##2.项目包

在`dobee`框架中，任何的项目或者组件代码，都会被视为一个bundle(包)，那既然是一个bundle，那么这个bundle就是独立的，与其他互不相干。

###2.1如何向框架中新建一个bundle(未来会创造命令行自动生成)

随意目录创建一个bundle目录: `xxxBundle`

bundle目录结构**暂时**为

```
- Controller   	====> 控制器
- Repository    ====> 数据库模型库
- Resources		====> 资源目录
	- config	====> bundle个性配置文件目录
	- views		====> bundle 视图目录
- {BundleName}Bundle.php	=====>bundle引导文件。必须继承Dobee\FrameworkKernel\Framework\Bundles\Bundle
```

###2.2向框架注册刚新建的bundle

打开`app/AppKernel.php`文件，寻找`registerBundles`方法，在方法中实例化bundle引导文件

```	
public function registerBundles()
	return array(
		new {BundleName}Bundle(),
		// ... new register bundle
	);
}```

到目前为止就完成新建bundle并注册到框架中了。可以开始编写自己的控制器(C), 模型(M), 视图(V) 了

##3.控制器
###3.1新建控制器

在`Controller`目录下添加`{ControllerName}Controller.php`。其中`ControllerName`为自己自定义。

例如:

```
class DemoController 
{
	// coding....}
```	

这样，一个简单的控制器就建立了。

建立控制器之后，就轮到控制器路由。

###3.1路由
控制器路由，通过控制访问URL来访问或者执行指定的控制器方法，每个需要被访问的控制器方法都必须要配置一个路由规则。这里并不是像ThinkPHP一样的访问。

###3.2设置控制器方法路由

继续控制器上例。这里定义控制器路由的方式和以往的有些不一样，因为这里配置的路由是根据方法注释来到设置，那么注释时怎么能够做到设置路由的呢？这里就是用到一个`PHP5.4`版本之后的
一个新对象**反射**。详情: [php反射
](http://php.net/manual/en/class.reflection.php)

首先找到我们的控制器`DemoController`.

**注：这里控制器方法都以Action左右结束标志**

**注：路由定义都应该已`@Route(.*?)`作为路由配置标示**

**注：路由名字不应该重复，在定义路由的时候需要仔细验证，后续会推出命令行路由debug工具**

```
class DemoController
{
	/** 
	 * 路由设置
	 * @Route("/demo", name="demo_index")
	 */
	public function demoAction()
	{
		return 'hello world';
	}}
```

以上就建立了一个最简单的路由。 访问地址:`http://path/to/public/index.php/demo`

###3.2.3配置动态路由

刚才演示了一个最简单的路由设置。除了静态的，还有动态的路由配置，包括动态变量，变量类型，默认值，传输格式。

```
class DemoController
{
	/** 
	 * 路由设置
	 * @Route("/demo", name="demo_index")
	 */
	public function demoAction()
	{
		return 'hello world';
	}
	
	/** 
	 * 动态路由
	 * 路由解析
	 * defaults 是路由参数变量的默认值。
	 * requirements 是路由参数类型约束
	 * method 是路由请求方法约束
	 * he
	 * @Route("/test/{name}", name="demo_test")
	 * @Route(defaults={"name": "janhuang"}, requirements={"name": "\w+"}, method="GET")
 	 */
	public function testAction($name)
	{
		return 'hello ' . $name;	}}
```
以上建立了一个动态路由。访问地址:

`http://path/to/public/index.php/test`  => `hello janhuang`
`http://path/to/public/index.php/test/world` => `hello world`

这里的`/test/{world}`的world就会作为路由参数`name`传进去控制器方法里面。`name => 'world'`

那么到这里，路由的基本设置就完成了，后面还有一系列的调整和优化，欢迎提出更多更好的建议。

##4.模板
	
###4.1模板路径
框架中模板目录分别是`app/Resources`和`src/*/Resources/views`两个目录，默认目录是`app/resources`Resources`。

目前模板格式为`*.html.twig`

**注：{{ name }} 这是输出，类似于<?php echo $name ?>   {% 这里为表达式 %}**

###4.2模板渲染

####4.2.1 定义模板

在资源目录下`src/*bundle/Resources/views/`新建`demo.html.twig`。内容为: `hello {{ name }}`，其中name为变量，需要由控制器赋值过去
	

```
class DemoController
{	
	/** 
	 * 动态路由
	 * 路由解析
	 * defaults 是路由参数变量的默认值。
	 * requirements 是路由参数类型约束
	 * method 是路由请求方法约束
	 * he
	 * @Route("/test/{name}", name="demo_test")
	 * @Route(defaults={"name": "janhuang"}, requirements={"name": "\w+"}, method="GET")
 	 */
	public function testAction($name)
	{
		return $this->render("DemoBundle:Demo:index.html.twig", array('name' => $name));	}}
```

如无意外将现实: hello janhuang。

###4.3模板继承

和类继承一样，模板也是可以继承

继承使用`{% extend "模板名，和渲染(render)时是一样的" %}`，这就是可以继承了父类的所有数据，并且可以重写。
	
###4.4模板重写

例子: 

```
layout.html.twig:
hello world
{% block main %}
	重写{% end block %}
```

```
index.html.twig：
{% extend 'layout.html.twig' %}
{% block main %}
	我这里是重写信息
{% endblock %}
```

模板就介绍到这里，具体可以参考: [Twig 模板引擎
](http://twig.sensiolabs.org/documentation)
##5.数据模型库

好累，先不说了。



author: Jan Huang

gmail: bboyjanhuang@gmail.com


