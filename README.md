#dobee-php-simple-framework

php simple framework: **dobee(逗比)**

**简单**、**高效**、**敏捷**、**灵活**、**组件式更新**

##参考

PSR-*: [http://www.php-fig.org/](http://www.php-fig.org/)

Composer: [http://getcomposer.org/](http://getcomposer.org/)

Git: [http://git-scm.com/book/zh/v1](http://git-scm.com/book/zh/v1)

#安装

本`donee php simple framework`依赖composer自动载入，在安装本框架前需要确保正确安装composer依赖管理。

`git clone https://coding.net/janhuang/dobee-php-simple-framework.git`

进入框架项目路径

`composer -vvv install`

**注意：因为国内访问composer超级缓慢的问题，建议使用代理或者国内镜像进行安装**

#Nginx下配置

```
server {
    listen       80;
    server_name  server_name;
    index index.php;
    location / {
            try_files $uri @rewriteapp;
    }
    location @rewriteapp {
            rewrite ^(.*)$ /index.php$1 last;
    }
    location ~ \.php {
        fastcgi_pass 127.0.0.1:9000; 
        fastcgi_split_path_info ^(.+.php)(/.*)$;
        include       fastcgi_params;
        fastcgi_param PATH_INFO $fastcgi_path_info;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param HTTPS              off;
    }
}
```

##1.配置
目前支持`yml`, `ini`, `php`三种配置文件类型。
###1.1 YML(YAML)

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
- Command 		====> 命令行
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
}
```

到目前为止就完成新建bundle并注册到框架中了。可以开始编写自己的控制器(C), 模型(M), 视图(V) 了

##3.控制器
###3.1新建控制器

在`Controller`目录下添加`{ControllerName}Controller.php`。其中`ControllerName`为自己自定义。

例如:

```
class DemoController 
{
	// coding....
}
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
	}
}
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
		return 'hello ' . $name;
	}
}
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

在资源目录下`src/*bundle/Resources/views/`新建`demo.html.twig`。内容为: `hello {{ name }}`，其中name为变量，需要由控制器赋值过去，详情看`DemoBundle/Resources/views`
	

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
		return $this->render("DemoBundle:Demo:index.html.twig", array('name' => $name));
	}
}
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
	重写
{% end block %}
```

```
index.html.twig：
{% extend 'layout.html.twig' %}
{% block main %}
	我这里是重写信息
{% endblock %}
```

###4.5模板函数列表
```
path($route, array $parameters = array()) // 路由创建函数
```

模板就介绍到这里，具体可以参考: [Twig 模板引擎
](http://twig.sensiolabs.org/documentation)
##5.数据模型库
数据库模型使用`Repository`作为后缀命名。与以往的`Model`不太一样，但是使用的方法类似，只是提供一个模型，更加灵活处理各个业务逻辑处理，往后会加大力度优化调整数据模型驱动这块，希望能听到不同的声音。

**注：需要继承`Dobee\Kernel\Framework\Controller\Controller`基类才能正常使用此类方法**

###5.1初探

查看配置文件: `app/config/config_dev.yml` 或者 `app/config/config_prod.yml` 或者 `app/config/config.yml`。 配置文件修改调整在`AppKernel@registerContainerConfiguration`方法中可自定义，详细请看**第一章，配置**

####5.1.1获取一个`Repository`库实例

```
$repository = $this->getConnection()->getRepository("DemoBundle:Post");
```

`getConnection` 方法可以指定配置文件中指定的链接，例如配置了读写分离，可以轻松自由的指定获取R/W模型库，如果为空，那就默认获取`default_connenction`下面的链接。详细请看`DemoBundle` 和 `config_dev.yml` 两个文件。

获取出来的`Repository`默认就是当前模型链接，只对当前链接进行操作，不影响其他模型库。

例如要实例两个相同`Repository`，但是操作不同链接.

```
$read = $this->getConnection('read')->getRepository("DemoBundle:Post");
$write = $this->getConnection('write')->getRepository("DemoBundle:Post");
```

以上就是同个`Repository`模型库，但是操作对象不一样。

**注：`getConnection`中参数必须是配置文件中已经配置好的，不然会抛出`ConnectionException`异常**

**注：数据库字段目前仅支持以下划线作为词组分割标示，例如`category_id`，不支持`categoryId`这样风格命名，为何使用这个，因为这样比较明朗，比较容易发现和规范统一**

####5.1.2简单获取数据信息

1.通过主键查询字段，这里的主键指的是 `id`

```
$read->find(1);
// 目前已主键id优先，默认主键名为:id
```

2.通过主键查询所有记录

```
$read->findAll(array('id' => 1));
// 返回数组 参数里面查询条件数组
```

####5.1.3灵活的获取数据信息

1.通过ID查询字段，可以写成下面那样:

```
$read->findBy(array('id' => 1));
// 或
$read->findById(1);
```

2.通过分类ID查询所有，可以写成下面那样:

```
// 两者效果等同
$read->findAllBy(array('c_id' => 1));
// 或
$read->findAllByCId(1);
// 两者效果等同 值得注意的是这里的 '_' 下划线是通过字幕大些区分的。。。
// 例如 CategoryId => category_id
```

####5.1.4创建查询资源，自定义sql查询语句

**注：有两个可选参数，分别是: `%prefix%`、`%table%`，在操作查询的时候会自动注入到相关语句中，`createQuery` 支持链式查询**

例如：

```
$read
	->createQuery("select * from %prefix%%table% where id = :id")
	->setParameters(array('id' => 1)) // 或者 setParameters('id', 1) 效果一样
	->getQuery()
;
$result = $read->getResult();
```

这样就简单的创建了一个`DQL`查询语句了。

####5.1.5 DML操作

1.新增一条数据

```
$repository->insert(array('title' => 'demo insert'));
```

以上会返回最后插入的ID，否则不会

2.更新一条数据

```
$repository->update(array('id' => id), array('title' => 'demo update'));
```

成功更新会返回影响行数，否则为0

3.删除一条数据

```
$repository->delete(array('id' => id));
```

成功删除会返回影响行数，否则为0


#6.命令行工具

##6.1自动创建项目Bundle

打开终端(win下打开cmd并进入到框架更目录)

```
php app/console bundle:generate --bundle=TestBundle 
或者
php app/console bundle:generate --bundle=Bundel:TestBundle 
```

将会自动生成一个项目bundle

然后将`bundle`注册到`AppKernel`。


#二期计划

##希望大伙踊跃发言，建议。

* 优化结构，整理代码
* 强化配置设置
* 增强数据库模型操作
* 整合更多组件，让框架更快，更智能。

#内置组件开发规范

* 1.**命名空间必须以`Dobee\\`作为开头**
* 2.**必须遵循psr代码规范**
* 3.**代码必须带有注释或者说明文档，有明确、清晰的设计，需要附上开发者联系方式**
* 4.**可以以个人名义命名，但需遵循MIT开源协议**

#外置依赖组件开发规范

* 1.**必须遵循psr代码规范**

#框架扩展须知

* 1.**可以调整外部代码，带尽量不要自行修改核心代码，遇到问题尽量想开发人员反馈**
* 2.**更多功能可以自行发掘，如有更好想法或者框架本身做得不够好的，欢迎各位热情反馈，吐槽，吐槽热线: [JanHuang](bboyjanhuang@gmail.com)**
* 3.**若想Make Friend的话，请加QQ：384099566，本人长期在线**



*Author*:  **[JanHuang](http://segmentfault.com/blog/janhuang)**

*Blog*: **[JanHuang Blog](http://segmentfault.com/blog/janhuang)**

*Gmail*:  **bboyjanhuang@gmail.com**


