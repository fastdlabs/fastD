#Dobee-php-simple-framework for PHP5.4+

**简单**、**高效**、**敏捷**、**灵活**、**组件式更新**

#发起人 
* JanHuang / bboyjanhuang@gmail.com

#维护者
* JanHuang / bboyjanhuang@gmail.com

###特色

	1) 简单
	2) 灵活
	3) RESTful路由
	4) composer安装/管理
	5) 灵活配置
	6) 遵循PSR命名规范
	7) 面向对象

###参考

PSR-*: [http://www.php-fig.org/](http://www.php-fig.org/)

Composer: [http://getcomposer.org/](http://getcomposer.org/)

Git: [http://git-scm.com/book/zh/v1](http://git-scm.com/book/zh/v1)

=========

#＃环境配置

###安装Composer

composer下载地址[https://getcomposer.org](https://getcomposer.org)

###安装Dobee

在安装本框架前需要确保正确安装composer依赖管理。

`git clone https://coding.net/janhuang/dobee-php-simple-framework.git`

进入框架项目路径

`composer -vvv install`

**注意：因为国内访问composer超级缓慢的问题，建议使用代理或者国内镜像进行安装**

###环境要求

* PHP >= 5.4.*

###隐藏index.php（优雅链接）

###Apache

框架通过public/.htaccess文件来实现优雅链接，隐藏index.php。在访问中不需要带index.php，但是要确定的是，需要开启mod_rewrite模块

###Nginx

```
server {
	#setting......
    location / {
            try_files $uri @rewriteapp;
    }
    location @rewriteapp {
            rewrite ^(.*)$ /index.php$1 last;
    }
    location ~ \.php {
    	#setting......
        fastcgi_split_path_info ^(.+.php)(/.*)$; #解析PHP pathinfo
        fastcgi_param PATH_INFO $fastcgi_path_info; #新增PHP pathinfo
    }
}
```

=====

#＃应用启动流程

#####初始化应用，并赋予环境类型

#####启动应用引导:

	1) 会把所有核心组件注册到应用里面
	2) 创建对象容器
	3) 然后判断是否缓存引导
	4) 加载应用配置信息
	5) 监听错误异常并且创建日志对象
	6) 注册所有项目包
	7) 读取初始化路由列表

#####监听http请求:

	1) 创建全局请求并且分析`header`,`server`,`cookies`,`query(GET)`,`request(POST)`
	2) 将所有Bag注册到request请求对象当中
	3) 将session备注到request请求当中

#####调度路由:

	1) 获取http请求
	2) 读取路由列表，匹配路由
	3) 匹配pathinfo，request method， request format。成功匹配路由并返回
	4) 获取路由所在事件(控制器)
	5) 闭包事件
	6) 调度事件并获取相应
	7) 包装响应信息
	8) 返回响应对象

#####应用接受响应:

	1) 接受响应
	2) 推送响应信息

#####结束应用执行

	1) 纪录运行时间
	1) 纪录请求信息
	3) 纪录响应状态及相关信息


=====

#＃基本功能

	1 项目包
	2 路由
	3 控制器
	4 请求
	5 响应
	6 视图
	

##1.项目包

每个项目都是一个项目包，依赖于`app/Application.php`下面的`registerBundles`方法进行注册。

每个项目包基本包含`Controllers`, `Repository`两个目录，如果有模板视图文件，则应该添加多个`Resources/views`目录，默认的模板视图目录在`app/resources`目录下。

完整目录结构如下:

```
Commands 		// 命令行(开发中)
Controllers		// 控制器
Repository		// 模型库
Event			// 事件监听(开发中)
Handlers		// 事件处理(开发中)
Resources/views	// 项目模板视图
Resources/config// 项目配置文件(开发中)
```

##3.路由

与以往ThinkPHP框架不同，Dobee框架主导每个可访问的事件方法都需要配置一个合法的路由地址，并且只有符合这个路由地址才可以正确调度制定事件。

路由机制采用PHP5.4提供的 [php反射
](http://php.net/manual/en/class.reflection.php) 特性，利用注释来配置每个方法。因此这里有个缺点，开发者需要清楚知道路由的意义和项目的意义。

因此抛出几个意义相关的提示：

	1.命名一定要有意义，切勿胡乱命名
	2.需要按照模块类型分割路由命名


####基本GET路由

因为框架采用的是[php反射
](http://php.net/manual/en/class.reflection.php)注释路由，所以路由配置暂时将会通过注释来定义.

```
/** 
 * @Route("/get", name="demo_index")
 */
```

####其他基本路由

**POST**

```
/** 
 * @Route("/post", name="demo_index", method="POST")
 */
```

**PUT**

```
/** 
 * @Route("/put", name="demo_index", method="PUT")
 */
```

**DELETE**

```
/** 
 * @Route("/delete", name="demo_index", method="DELETE")
 */
```

**ANY(此类型路由支持任何形式访问)**

```
/** 
 * @Route("/any", name="demo_index", method="ANY")
 */
```

####多种请求路由

```
/** 
 * @Route("/any", name="demo_index", method=["GET", "POST", "DELETE"])
 */
```

####路由参数

**路由参数通过标识符{参数名}表示**

```
/** 
 * @Route("/any/arguments/{arg1}/{arg2}", name="demo_index", method="ANY")
 */
```

####路由参数默认值

```
/** 
 * @Route("/any/arguments/{arg1}/{arg2}", name="demo_index", method="ANY", defaults={"arg1": "ab", "arg2": "cbd"})
 */
```

或者可以分开写

```
/** 
 * @Route("/any/arguments/{arg1}/{arg2}", name="demo_index", method="ANY")
 * @Route(defaults={"arg1": "abc", "arg2": "cbd"})
 */
```

两者效果等同

####路由参数类型限制

```
/** 
 * @Route("/any/arguments/{arg1}/{arg2}", name="demo_index", method="ANY")
 * @Route(requirements={"arg1": "\d+", "arg2": "\w+"})
 */
```

默认允许任何字符，除特殊字符和非法字符除外

####路由访问格式限制

```
/** 
 * @Route("/format", name="demo_index", method="ANY")
 * @Route(format="html")
 */
```

默认的访问格式限制为php

####多个路由访问格式

```
/** 
 * @Route("/format", name="demo_index", method="ANY")
 * @Route(format=["php", "json", "xml"])
 */
```

访问示例: `host/path/to/format.json`

##3.控制器

####基础控制器

这是一个基础控制器例子：

```
namespace 项目名路径\Controllers;
 
use Dobee\Framework\Controller\Controller;

class WelcomeController extends Controller
{
	public function welcomeAction()
	{
		return 'hello world';
	}	
}
```

####给控制器配置可访问路由


```
namespace 项目名路径\Controllers;
 
use Dobee\Framework\Controller\Controller;

class WelcomeController extends Controller
{
	/** 
 	 * @Route("/", name="demo_index", method="ANY")
 	 */
	public function welcomeAction()
	{
		return 'hello world';
	}	
}
```

访问地址:`host/path/to/`。得到 `hello world`


####给控制器带上路由参数

```
namespace 项目名路径\Controllers;
 
use Dobee\Framework\Controller\Controller;

class WelcomeController extends Controller
{
	/** 
 	 * @Route("/{name}", name="demo_index", method="ANY", defaults={"name": "JanHuang"})
 	 */
	public function welcomeAction($name)
	{
		return 'hello ' . $name;
	}	
}
```

访问地址:

	`host/path/to/`。得到 `hello JanHuang`

	`host/path/to/Fedora`。得到 `hello Fedora`

	`host/path/to/黄总`。得到 `hello 黄总`

参数限制可以参考路由一章


####RESTful资源控制器


```
namespace 项目名路径\Controllers;
 
use Dobee\Framework\Controller\Controller;

/** 
 * @Route("/index")
 */
class WelcomeController extends Controller
{
	/** 
 	 * @Route("/get", name="demo_index", method="ANY", format="json")
 	 */
	public function welcomeAction()
	{
		return 'hello world';
	}	
}
```

访问地址:`host/path/to/index/get.json`。得到 `hello world`

其他访问方式需要自己定义，或者有更好的想法可以提出。

**注意： 如果这里class也定义了路由，那么访问路由的地址的pathinfo就应该带上前缀**


####依赖注入控制器

Dobee 对象容器时用于解析所有的已定义对象。因此，你可以在控制器所需要的构造器或者方法中，对依赖作任何的类型限制。

####构造器注入

```
namespace 项目名路径\Controllers;
 
use Dobee\Framework\Controller\Controller;

/** 
 * @Route("/index")
 */
class WelcomeController extends Controller
{
	private $router;
	public function __construct(\Dobee\Routing\Router $router)
	{
		$this->router = $router;
	}
	
	/** 
 	 * @Route("/get", name="demo_index", method="ANY", format="json")
 	 */
	public function welcomeAction()
	{
		return 'hello injection. I \'m injection Router' . get_class($this->router);
	}	
}
```

####方法注入


```
namespace 项目名路径\Controllers;
 
use Dobee\Framework\Controller\Controller;

/** 
 * @Route("/index")
 */
class WelcomeController extends Controller
{	
	/** 
 	 * @Route("/get", name="demo_index", method="ANY", format="json")
 	 */
	public function welcomeAction(\Dobee\Routing\Router $router)
	{
		return 'hello injection. I \'m injection Router' . get_class($this->router);
	}	
}
```

##4.请求

####通过依赖注入获取实例

```
namespace 项目名路径\Controllers;
 
use Dobee\Framework\Controller\Controller;

class WelcomeController extends Controller
{	
	/** 
 	 * @Route("/", name="demo_index", method="ANY")
 	 */
	public function welcomeAction(\Dobee\Http\Request $request)
	{
		
	}	
}
```

####获取URL GET参数

`host/path/to/?name=JanHuang`

```
namespace 项目名路径\Controllers;
 
use Dobee\Framework\Controller\Controller;

class WelcomeController extends Controller
{	
	/** 
 	 * @Route("/", name="demo_index", method="ANY")
 	 */
	public function welcomeAction(\Dobee\Http\Request $request)
	{
		$name = $request->query->get('name');
		//TODO ......
	}	
}
```

####获取表单提交POST, PUT, DELETE数据

`form-data:name=JanHuang&age=22`

```
namespace 项目名路径\Controllers;
 
use Dobee\Framework\Controller\Controller;

class WelcomeController extends Controller
{	
	/** 
 	 * @Route("/", name="demo_index", method="ANY")
 	 */
	public function welcomeAction(\Dobee\Http\Request $request)
	{
		$name = $request->request->get('name');
		$age = $request->request->get('age');
		//TODO ......
	}	
}
```

####获取上传文件

`form-data: file=xcvzcadfbtznn==`

```
namespace 项目名路径\Controllers;
 
use Dobee\Framework\Controller\Controller;

class WelcomeController extends Controller
{	
	/** 
 	 * @Route("/", name="demo_index", method="ANY")
 	 */
	public function welcomeAction(\Dobee\Http\Request $request)
	{
		$files = $request->files;
		$file = $request->files->get('file');
		//TODO ......
	}	
}
```

####Cookies


```
namespace 项目名路径\Controllers;
 
use Dobee\Framework\Controller\Controller;

class WelcomeController extends Controller
{	
	/** 
 	 * @Route("/", name="demo_index", method="ANY")
 	 */
	public function welcomeAction(\Dobee\Http\Request $request)
	{
		$cookies = $request->cookies;
		$key = $request->cookies->get('name')->getName();
		$value = $request->cookies->get('name')->getValue();
		// Set cookie
		$request->cookies->set('name', 'JanHuang');
		// Remove cookie
		$request->cookies->remove('name')
		//TODO ......
	}	
}
```

Request对象里面所包含的都是面向对象封装的类，所以只有通过相关的方法才能获取相应的值

####Session

session是比较特殊的一个东西。框架初始化的时候是不会默认给你开启session的，当只有你去获取session的时候才会给你实例化session对象参数包


```
namespace 项目名路径\Controllers;
 
use Dobee\Framework\Controller\Controller;

class WelcomeController extends Controller
{	
	/** 
 	 * @Route("/", name="demo_index", method="ANY")
 	 */
	public function welcomeAction(\Dobee\Http\Request $request)
	{
		$session = $request->session; // $request->getSession();
		$session->get('name');
		// Set session
		$session->set('name', 'JanHuang');
		// Remove session
		$session->remove('name');
		//TODO ......
	}	
}
```

####其他特性

#####判断请求类型

`\Dobee\Http\Request::isMethod($method)`

#####获取请求方法

`\Dobee\Http\Request::getMethod`

#####获取请求格式

`\Dobee\Http\Request::getFormat`

#####是否异步(Ajax)请求

`\Dobee\Http\Request::isXmlHttpRequest`

#####获取pathinfo

`\Dobee\Http\Request::getPathInfo`

#####获取ip地址

`\Dobee\Http\Request::getClientIp`

#####获取域名

`\Dobee\Http\Request::getHost`

#####获取完整域名地址

`\Dobee\Http\Request::getHttpAndHost`

#####获取base Url

`\Dobee\Http\Request::getBaseUrl`


##5.响应

通过注入或者实例化在或者继承`\Dobee\Framework\Controller\Controller`获取`\Dobee\Http\Response`响应对象，这里比较灵活。如果以上都没有，默认系统会将返回字符串封装成一个基础的响应对象返回

####响应字符串

#####通过注入


```
namespace 项目名路径\Controllers;

class WelcomeController
{	
	/** 
 	 * @Route("/", name="demo_index", method="ANY")
 	 */
	public function welcomeAction(\Dobee\Http\Response $response)
	{
		$response->setContent('hello world');
		
		return $response;
	}	
}
```

#####通过继承


```
namespace 项目名路径\Controllers;
 
use Dobee\Framework\Controller\Controller;

class WelcomeController extends Controller
{	
	/** 
 	 * @Route("/", name="demo_index", method="ANY")
 	 */
	public function welcomeAction()
	{
		return 'hello world';
	}	
}
```

#####通过实例化

```
namespace 项目名路径\Controllers;
 
use Dobee\Framework\Controller\Controller;

class WelcomeController extends Controller
{	
	/** 
 	 * @Route("/", name="demo_index", method="ANY")
 	 */
	public function welcomeAction()
	{
		return new \Dobee\Http\Response('hello world');
	}	
}
```

注意`\Dobee\Http\Response`响应只支持字符串。

####响应json

和以上响应一样，不同的是，响应后输出的结果是json对象，主要用于与客户端交互(安卓、苹果、web前端等)。

`return new \Dobee\Http\JsonResponse(array('name' => 'JanHuang'));`

输出结果


```
{
	"name": "JanHuang"
}

```

####设置响应头

需要在实例化的时候带上第三个参数，类型为数组，信息为响应头信息

```
array(
	"Cache-Control:max-age=180"
)
```

##6.视图

框架中使用的模板引擎为[Twig](http://twig.sensiolabs.org/documentation)引擎

####基本用法

视图文件保存在`app/resources`下，和项目的`Resources/views`下。

保存在`app/resources`下，在控制器中可以直接使用`render`方法，不过前提是需要继承框架基础控制器`Dobee\Framework\Controller\Controller`。

路径表示用相对路径

例如在`app/resources`目录下的应该这样写

`$this->render(视图路径:视图名)`

在项目`Resources/views`下应该这样写

`$this->render(项目路径:视图目录:视图名)`

####为视图分配变量

`$this->render(项目路径:视图目录:视图名, array('name' => 'JanHuang'))`

视图文件输出变量

`{{ name }}`

模板引擎用法具体参考[Twig](http://twig.sensiolabs.org/documentation)

框架中添加了两个字定义模板函数，分别如下:

**创建路由地址**

	path($route, [array $parameters = array()])
	$route 为路由名 路由的name字段
	$parameters 可选，默认是路由参数默认值
	@examples: {{ path('demo_index') }}
	
**创建灵活的资源地址**

	asset($name, [$host = null], [$path = null])
	$name 资源名
	$host 可选 资源域名地址 可以通过`app/config/config_(dev|prod).yml`配置文件配置 默认是当前域名
	$path 可选 资源目录地址 可以通过`app/config/config_(dev|prod).yml`配置文件配置 默认是当前baseUrl
	@examples: {{ asset('style.css') }} => http://localhost/styls.css 默认
	@exmpales: {{ asset('style.css') }} => http://www.mmclick.com/resrouces/style.css 参考下方配置
	
配置声明

```
assets:
	host: http://www.mmclick.com
	path: /resources/
```

=====

#＃系统扩展
	
	1) 自定义配置
	2) 自定义插件
	3) 依赖注入
	4) 访问日志
	
##1.自定义配置

系统配置一律使用`%变量名%`作为变量配置定界符

####向系统注册自己的配置变量

找到`app/Application.php:registerConfigVariable()`, 方法必须返回数组

```
public function registerConfigVariable()
{
    return array(
        'root_path' => $this->getRootPath(), // 此变量为系统变量，切勿改动
        'Ymd' => date('Ymd'), // 新注册配置变量
        'name' => 'JanHuang',
    );
}
```

以上注册了一个新变量`name`, 在配置文件中可以用定界符`%%`作为这个变量的声明`%name%`

`app/config/config_(dev|prod).yml`

```
......
name: %name%
......
```

在控制器中可以通过注入的方式或者继承的方式去获取系统配置对象

```
namespace 项目名路径\Controllers;
 
use Dobee\Framework\Controller\Controller;

class WelcomeController extends Controller
{	
	/** 
 	 * @Route("/", name="demo_index", method="ANY")
 	 */
	public function welcomeAction() // welcomeAction(\Dobee\Configuration\Configuration $config)
	{
		return $this->getParameters('name');
	}	
}
```

####配置变量数组链接获取

还是拿上面的例子举例



`app/config/config_(dev|prod).yml`

```
......
authors: 
	name: %name%
.....
```
举例：

```
namespace 项目名路径\Controllers;
 
use Dobee\Framework\Controller\Controller;

class WelcomeController extends Controller
{	
	/** 
 	 * @Route("/", name="demo_index", method="ANY")
 	 */
	public function welcomeAction() // welcomeAction(\Dobee\Configuration\Configuration $config)
	{
		return $this->getParameters('authors.name');
	}	
}
```

多维数组层级关系也是按照这种用`.`号链接的形式去获取。


##自定义系统插件

自定义系统插件需要遵循[psr-*](http://www.php-fig.org/)命名规范，然后通过[composer](http://getcomposer.org/)依赖引入加载。并且插件别名均已自身的`short name`作为命名。


####新建自定义插件

```
<?php
namespace 插件目录命名空间;
class DemoPlugin
{
	public function demoPrint()
	{
		return 'hello custom plugin';
	}
}
```

也就是说这里的别名就是`DemoPlugin`

在控制器中可以通过`get($plugin)`方法或者依赖注入的方式获取。

通过`get($plugin)`方法需要传递别名，而通过依赖注入的方式需要完整命名

```
namespace 项目名路径\Controllers;
 
use Dobee\Framework\Controller\Controller;

class WelcomeController extends Controller
{	
	/** 
 	 * @Route("/", name="demo_index", method="ANY")
 	 */
	public function welcomeAction()
	{
		return $this->get('DemoPlugin')->demoPrint();
	}	
}
```

####在插件中实现构造器依赖注入


```
<?php
namespace 插件目录命名空间;
class DemoPlugin
{
	private $request;
	public function __construct(\Dobee\Http\Request $request)
	{
		$this->request = $request;
	}
	public function demoPrint()
	{
		return 'hello custom plugin. Now I access pathinfo is: ' . $this->request->getPathInfo();
	}
}
```

从控制器中实现插件依赖注入

```
namespace 项目名路径\Controllers;
 
use Dobee\Framework\Controller\Controller;

class WelcomeController extends Controller
{	
	/** 
 	 * @Route("/", name="demo_index", method="ANY")
 	 */
	public function welcomeAction(插件目录命名空间\DemoPlugin $demo)
	{
		return $demo->demoPrint();
	}	
}
```

##依赖注入

插件，控制器，甚至核心都是可以实现依赖注入，甚至看上去是那么的简单，便捷。那么依赖注入是怎么实现的呢？别急，现在我来跟大家一一讲解。

其实依赖注入，简单来说就是控制翻转(IoC)。该特性和功能的实现有赖于PHP5.4以后引入的一个[php反射
](http://php.net/manual/en/class.reflection.php)特性。而系统中恰恰使用了这一特性来实现依赖注入功能。其他更神奇，跟有趣的功能还需要大家耐心去发现和反馈。

目前核心已经实现了简单的依赖注入，覆盖率是100%，而且还支持自定义构造方法依赖注入和字定义全局变量喲

##访问日志

日志，是一个系统非常重要的组成组件，主要用来记录用户行为，访问信息，响应信息，错误信息，有效，快速的定位系统的不足，流程逻辑问题。

目前日志驱动使用知名的[Monolog](https://github.com/Seldaek/monolog)来作为核心日志组成组件。其中格式配置在`app/config:logger`一栏， 详细可打开该文件查看。

====

#＃数据库

	1) 配置
	2) 获取读取/写入连接
	3) 获取读取/写入模型库
	4) 执行查找
	5) 数据库事务处理(暂未通过repository支持，需要手动sql语句支持)
	6) 查找日志纪录
	
目前数据库驱动采用`medoo`进行框架核心驱动，后期可能会移除该驱动进行新的整合，不过基础功能不会有太大影响
	
####数据库配置

数据库配置主要在`app/config/config_(dev|prod).yml`文件中的`database`字段中，数据库配置必须按照以下方式配置，养成一个良好的规范，更加灵活的扩展往后的项目和调整数据库连接。

例子：

```
database:
    default_connection: read #数据库默认链接 名字为下方定义的数据库连接信息 不可忽略
    write: 
        database_type: mysql #数据库驱动类型 目前暂且以mysql优先, 此项忽略默认为mysql
        database_host: localhost #数据库连接ip
        database_port: 3306 #端口
        database_user: root #用户
        database_pwd: 123456 #密码
        database_charset: utf8 #编码
        database_name:  #库名
        database_prefix:  #表前缀
    read:
        database_type: mysql
        database_host: localhost
        database_port: 3306
        database_user: root
        database_pwd: 123456
        database_charset: utf8
        database_name: 
        database_prefix: 
```

####获取读取/写入连接

这个获取数据库连接必须是继承`Dobee\Framework\Controller\Controller`控制器，并且通过`getConnection([$connection = null])`方法获取。**暂时不支持依赖注入的方式连接数据库**

并且建议每次获取链接都指定链接类型。也就是那个数据库，如果为空，则会选择`default_connection`数据库，这里会隐藏一个危险，万一默认链接改了，那可能要改动的地方就很多了，所以是建议每次都明确指定

```
$read = $this->getConnection('read');
// 具体操作
```

####获取读取/写入模型库

这个会根据你定义的`{Name}Repository`来定义指定表名，表切会自动帮你带上表前缀， 前提是模型库必须继承`Dobee\Database\Repository\Repository`， 模型库获取需要通过`getConnection([$connection=null])`方法获取。而且获取后默认将链接赋予给模型库。

例如：

定义`PostRepository` 那么该模型库就是默认指向到`prefix_表名`。

```
$postRepository = $this->getConnection('read')->getRepository(项目目录命名空间:Repository:名字);
```

这里获取Repository的参数格式是： 项目命名空间:模型库名，这里模型库名不需要带上Repository

比如你定义了一个：`PostRepository`在`Demo`项目下的`Repository`目录下，那就应该这样写

`$postRepository = $this->getConnection('read')->getRepository('Demo:Repository:Post')`

结构会返回你定义的模型库类，可以执行相关的方法。

**模型库内暂时不支持依赖注入**

####执行查找

#####基础查找

查找一条数据

`find($where = array(), [$fields = '*'])`

查找所有数据

`findAll($where = array(), [$fields = '*'])`

根据条件查找一条数据

`findBy($where = array(), [$fields = '*'])`

根据条件查找所有数据

`findAllBy($where = array(), [$fields = '*'])`

灵活根据条件查找一条数据

`findBy字段名($where = array(), [$fields = '*'])` 如：`findById(1) => findBy(array('id' => 1))`

灵活根据条件查找所有数据

`findBy字段名($where = array(), [$fields = '*'])`

如： 

`findAllByTitle('hello') => findAllBy(array('title' => 'hello'));`
`findAllByCatId(1) => findAllBy(array('cat_id' => 1));`

#####基础业务操作

插入

`insert($datas = array());`

更新

`update($where = array(), $datas = array())`

删除,这里删除数据不允许删除整个表数据，只允许删除符合条件的数据，这里条件参数比传

`delete($where = array());`

####字定义数据查询

这里数据查询返回的是一个二维数组

`createQuery($dql);`

`getQuery()`

`getResult()`

如：

`$connection->createQuery('sql')->getQuery()->getResult()`


####事务处理

目前事务处理系统还没有提供，需要通过`createQuery`去产生事务并且提交，后期优化会加上事务处理

####日志处理

日志可以通过执行过sql处理后的模型库或者链接来获取

日志

`logs()` 

该方法返回一个数组，是执行过的所有业务语句

错误信息

`error()`

最后执行的语句

`getLastQuery()`

====

#@API


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


====

#Author

*Name*:  **[JanHuang](http://segmentfault.com/blog/janhuang)**

*Blog*: **[http://segmentfault.com/blog/janhuang](http://segmentfault.com/blog/janhuang)**

*EMail*:  **bboyjanhuang@gmail.com**

====

#License

####MIT


