<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2017
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Servitization\ServiceRegister;


use FastD\Http\ServerRequest;
use FastD\Servitization\Server\HTTPServer;

/**
 * Class Register
 * @package FastD\Servitization\ServiceRegister
 */
class Register extends HTTPServer implements RegisterInterface
{
    /**
     * @var RegisterCenterInterface
     */
    protected $registerCenter;

    /**
     * Register constructor.
     * @param RegisterCenterInterface $registerCenter
     */
    public function __construct(RegisterCenterInterface $registerCenter)
    {
        $this->registerCenter = $registerCenter;

        $host = config()->get('rpc.register.host');

        parent::__construct('register', $host, []);

        route()->staticRoutes = [];
        route()->dynamicRoutes = [];
        route()->aliasMap = [];
        route()->regexes = [];

        route()->get('/services', [$this, 'query']);
        route()->post('/publish', [$this, 'publish']);
    }

    /**
     * @param ServerRequest $request
     * @return \FastD\Http\Response
     */
    public function query(ServerRequest $request)
    {
        $nodes = $this->registerCenter->get('nodes');

        foreach ($nodes as $key => $node) {
            $nodes[$key] = json_decode($node, true);
        }

        return json($nodes);
    }

    /**
     * @param ServerRequest $request
     * @return \FastD\Http\Response
     */
    public function publish(ServerRequest $request)
    {
        $post = $request->getParsedBody();

        $this->registerCenter->set('nodes', $post['host'], json_encode($post));

        return json([
            'service' => $post['service'],
            'scheme' => $post['sock'],
            'host' => $post['host'],
            'port' => $post['port'],
            'created_at' => time(),
        ]);
    }
}