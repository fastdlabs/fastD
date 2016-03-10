<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/3/14
 * Time: 下午3:23
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Welcome\Controllers;

use FastD\Database\Drivers\Query\MySQLQueryBuilder;
use FastD\Framework\Bundle\Controllers\Controller;
use FastD\Http\Request;
use FastD\Http\Response;
use Welcome\Orm\Entity\Test;
use Welcome\Orm\Repository\TestRepository;

/**
 * Class Index
 *
 * @package Welcome\Controller
 */
class Index extends Controller
{
    /**
     * @Route("/{name}", name="welcome", defaults={"name":"janhuang"})
     *
     * @param Request $request
     * @return Response|string
     */
    public function welcomeAction(Request $request, $name)
    {
<<<<<<< HEAD
        try {
            $driver = $this->getDriver('read');
        } catch (\Exception $e) {
            return $this->response('fail');
        }

        return $this->response('ok');
    }

    /**
     * @Route("/orm/save", name="orm")
     *
     * @return Response|string
     */
    public function saveOrmAction()
    {
        try {
            $driver = $this->getDriver('read');
        } catch (\Exception $e) {
            return $this->response('fail');
        }

        $test = new Test(null, $driver);
        $test->setTrueName('aaa');
        $test->setTel(mt_rand(0, 9999));

        return $this->response('new id: ' . $test->save());
    }

    /**
     * @Route("/orm/find/{id}", name="orm_find")
     *
     * @param int $id
     * @return Response|string
     */
    public function findOrmAction($id)
    {
        try {
            $driver = $this->getDriver('read');
        } catch (\Exception $e) {
            return $this->response('fail');
        }

        $test = new Test($id, $driver);

        return $this->response('name:' . $test->getTrueName());
    }

    /**
     * @Route("/orm/repository/{id}", name="welcome_repository", defaults={"id": "0"})
     *
     * @param int $id
     * @return Response|string
     */
    public function repositoryAction($id)
    {
        try {
            $driver = $this->getDriver('read');
        } catch (\Exception $e) {
            return $this->response('fail');
        }

        $repository = new TestRepository($driver);

        if (empty($id)) {
            $result = $repository->findAll();
        } else {
            $result = $repository->find(['id' => $id]);
        }

        return $this->response(json_encode($result));
=======
        return $this->response('hello world');
>>>>>>> 2.0
    }
}