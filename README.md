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
        fastcgi_split_path_info ^(.+.php)(/.*)$; #解析PHP pathinfo
        include       fastcgi_params;
        fastcgi_param PATH_INFO $fastcgi_path_info; #新增PHP pathinfo
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param HTTPS              off;
    }
}
```

##1.Application应用文件

应用主文件主要配置整个应用的配置，项目注册和配置，需要继承`AppKernel`并且重写四个方法，而且必须返回数组，分别是:

`registerPlugins` **注册全局插件，并且支持构造注入**

`registerBundles` **注册项目包**

`registerConfigVariable` **注册全局配置变量**

`registerConfiguration` **注册全局配置**

##@API

###@registerPlugins 
返回插件数组，插件形式为命名空间＋类名。例如:

```
return array(
	'Plugins\\Demo',
);
```

必须返回数组，而且插件均以类名作为别名，在控制器中可以这样获取:

$demo = $this->get('Demo'); // 别名获取

插件注册是支持构造器注入，所以在插件构造方法可以注入指定对象，前提是注入的对象，必须是存在的，也就是必须是已经定义的。例如：

```
class Demo
{
	private $a;
	public function __construct(A $a)
	{
		$this->a = $a;
	}
}
```

$demo = $this->get('Demo'); // 别名获取

###@registerBundles

注册项目到应用，例如：

```
return array(
	new Demo(),
);
```

###@registerConfiguration

注册应用配置信息，目前支持三种配置文件格式，分别是: `yml`, `php`, `ini`。个人推荐使用`yml`作为配置文件，因为最直观，最易配置

原理：最后所有配置信息都是会编译成PHP数组，所以一般按照PHP数组写法配置即可。

```
public function registerConfiguration(\Dobee\Configuration\Configuration $configuration)
{
        $configuration->load(__DIR__ . '/config/config_' . $this->getEnvironment() . '.yml');
}
```

然后全局配置信息就注入到应用里面。在控制器当中可以通过:

```
$this->getParameters('parent.child'); // 获取parent下的children
```


###@registerConfigVariable

注册配置文件变量，必须返回数组，例如：

```
return array(
	'root_path' => $this->getRootPath(),
	'name' => 'janhuang',
);
```

这里注册了两个变量，一个是root_path(不可修改)，一个是nam(自定义的)，在配置文件中可以这样写:

```
name: %name%
```

在控制器中获取：

```
$name = $this->getParameters('name');
```

这个获取出来的变量即是刚才注册的变量的值：janhuang。如果没有注册到方法当中，那么获取出来的值应该是：%name%，没有变化，这个是一个比较灵活的设置

##应用启动流程

```
$app = new Application('dev'); // dev | prod
$app->bootstrap(); // 应用引导
$response = $app->handleHttpRequest(); // 监听http请求
$response->send(); // 相应http请求
$app->terminate($response); // 结束http请求流程
```

###流程解析

初始化应用，并赋予环境类型

启动应用引导:

* 会把所有核心组件注册到应用里面
* 创建对象容器
* 然后判断是否缓存引导
* 加载应用配置信息
* 监听错误异常并且创建日志对象
* 注册所有项目包
* 读取初始化路由列表

监听http请求:

* 创建全局请求并且分析`header`,`server`,`cookies`,`query(GET)`,`request(POST)`
* 将所有Bag注册到request请求对象当中
* 将session备注到request请求当中

调度路由:

* 获取http请求
* 读取路由列表，匹配路由
* 匹配pathinfo，request method， request format。成功匹配路由并返回
* 获取路由所在事件(控制器)
* 闭包事件
* 调度事件并获取相应
* 包装响应信息
* 返回响应对象

应用接受响应:

* 接受响应
* 推送响应信息

结束应用执行

* 纪录运行时间
* 纪录请求信息
* 纪录响应状态及相关信息



##1.配置
目前支持`yml`, `ini`, `php`三种配置文件类型。（本人推荐yml配置）
###1.1 YML(YAML)

**注: yml(yaml)文件中，缩进使用空格缩进，一个tab等于4个空格键，非4个空格会报错**

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

####1.1.3变量写法:（新增） 
这个写法比较特殊，需要在配置文件和引导文件中配合设置

配置文件：

`config.yml`

```
name: %name%
```

找到`app/Application.php`文件并且打开，找到`registerConfigVariable`方法，并且编辑注册自己的自定义配置变量，例如刚才申请了个变量`name`，那么需要在方法中注册到核心当中。注意，root_path不可修改，否则有可能会出现意想不到的bug，因为核心大部分都由root_path路径作为应用默认路径

```
public function registerConfigVariable()
    {
        return array(
            'root_path' => $this->getRootPath(),
            'name' => 'janhuang' // 注册新变量到核心
        );
    }
```

####1.1.4引用写法: 
**暂不支持**

##2.项目包

在`dobee`框架中，任何的项目或者组件代码，都会被视为一个bundle(包)，那既然是一个bundle，那么这个bundle就是独立的，与其他互不相干。

###2.1如何向框架中新建一个项目

可以在`src`目录当中随意新建一个项目

目录结构为：

```
- Controllers   	====> 控制器
- Repository    ====> 数据库模型库
- Resources		====> 资源目录
	- views		====> 视图目录
- {name}.php	=====> 引导文件。必须继承Dobee\Framework\Bundles\Bundle
```

###2.2注册项目到核心当中

打开`app/Application.php`文件，寻找`registerBundles`方法，在方法中实例化项目引导文件，也就是项目根目录继承`Dobee\Framework\Bundles\Bundle`的引导文件

```	
public function registerBundles()
	return array(
		new {name},
	);
}
```

注册项目后，即可开始正式的开发工作。

##3.路由
###3.1简介

与以往`ThinkPHP`框架不同，`Dobee`框架主导每个可访问的事件方法都需要配置一个合法的路由地址，以其该有的特性命名该路由前缀。其他没有配置路由地址，不能被外界访问，只可以在内部私有调用。并且注意的是，路由名字不可以重复，路由地址不可以重复，这样会造成冲突，会影响正常的业务访问。路由的配置需要配合访问的控制器/事件，以达到访问该路由即调用该事件方法。

###3.2路由配置新建控制器

在项目`Controllers`中新建一个控制器，例如`DemoController.php`，控制器必须继承框架基类控制器`Dobee\Framework\Controller\Controller`

在控制器当中写上方法

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

这样新建了一个可访问的控制器方法路由了。访问地址为: `host/path/to/index.php/demo`。即可访问到demoAction方法，如无意外即可以看到hello world这几个大字

控制器也可以配置变量哦，变量表示用花括号表示:`{变量名}`
	
在控制器新增多一个方法：

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

新增了一个可访问控制器路由地址，并且配有变量`name`， 设置变量`name`默认值`janhuang`。设置在`defaults`注释里面。并且设置了路由变量的有效值`requirements`类型为`\w+`。

访问地址: `host/path/to/index.php/test` 默认就会将defaults的值传给`testAction`方法参数接收，那么这里可以看到的是，会变成 hello janhuang。 

如果在这个路由地址后面带上自定义的名字就会变成你所输入的那个名字。 比如：访问地址: `host/path/to/index.php/test/demo`，就会出现 hello demo

这里，其实你还会看到有个`method`定义，其实这里method定义的是你该路由地址允许访问的http模式，如果设置了`GET`那么这个只能允许`GET`方式访问。支持的方式可以自己定义，但是请勿玩太过了。设置`ANY`则可以用任何方式访问，设置多个访问方式，`method=["GET", "POST"]`, 以数组方式设置，`method`的值一律为大写

还有一个参数设置，就是访问的格式`format`，例如`format="json"`, 那么访问的地址必须带上`.json`后缀访问，否则路由无法匹配，默认是`php`。可以设置多个访问方式，用来做[RESTful]()API专用。

###3.3路由前缀配置

有两个选择，在方法的类名定义处新增路由定义注释
```
/** 
 * @Route("/prefix") 
 */
```

访问制定路由的时候就要带上此前缀和路由地址访问。

第二种就是通过`routing.yml`配置文件进行配置。详情可以查看配置文件，与上者配置类似.

###3.4路由机制

路由机制采用PHP5.4提供的 [php反射
](http://php.net/manual/en/class.reflection.php)特性，利用注释来配置每个方法。因此这里有个缺点，开发者需要清楚知道路由的意义和项目的意义，因为路由前缀需要和项目搭上边，切随意和胡乱定义、命名，这样会带来很多维护上的问题。当然，这也是目前框架不足的地方，提示能力过弱，debug能力过弱，需要优化和提升。所以框架有计划加入更多高效高性能组件。先计划加入命令行组件Console、服务组件Server等，还有赖大家提出。

##4.模板
	
###4.1模板路径
框架中模板目录分别是`app/Resources`和`src/*/Resources/views`两个目录，默认目录是`app/resources`Resources`。

目前模板格式为`*.html.twig`

**注：{{ name }} 这是输出，类似于<?php echo $name ?>   {% 这里为表达式 %}**

###4.2模板渲染

####4.2.1 定义模板

在资源目录下`src/*/Resources/views/`新建`demo.html.twig`。内容为: `hello {{ name }}`，其中name为变量，需要由控制器赋值过去，详情看`*/Resources/views`

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

具体可以参考: [Twig 模板引擎
](http://twig.sensiolabs.org/documentation)

###4.5模板函数列表

##path($route, array $parameters = array())

###$route 路由名 例：@Route(name="demo") path('demo')

###$patameters 数组，路由参数 例：@Route("/{name}/{age}", name="demo") path('demo', {"name":"janhuang", "age": 22})

##5.数据模型库
数据库模型使用`Repository`作为后缀命名。与以往的`Model`不太一样，但是使用的方法类似，只是提供一个模型，更加灵活处理各个业务逻辑处理，往后会加大力度优化调整数据模型驱动这块，希望能听到不同的声音。

**注：需要继承`Dobee\Framework\Controller\Controller`基类才能正常使用此类方法**

###5.1数据库配置

查看配置文件: `app/config/config_dev.yml` 或者 `app/config/config_prod.yml` 或者 `app/config/config.yml`。

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

**或者Repository必须为Repository的命令空间完整路径，否则会找不到**

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


*Author*:  **[JanHuang](http://segmentfault.com/blog/janhuang)**

*Blog*: **[JanHuang Blog](http://segmentfault.com/blog/janhuang)**

*Gmail*:  **bboyjanhuang@gmail.com**


