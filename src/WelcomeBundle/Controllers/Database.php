<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 16/5/7
 * Time: 下午11:58
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace WelcomeBundle\Controllers;

use FastD\Framework\Bundle\Controllers\Controller;
use FastD\Http\Request;
use FastD\Http\Response;
use WelcomeBundle\ORM\Test\Entities\TestEntity;
use WelcomeBundle\ORM\Test\Models\TestModel;

/**
 * Class Database
 *
 * @Route("/database")
 *
 * @package WelcomeBundle\Controllers
 */
class Database extends Controller
{
    /**
     * @Route("/driver", name="database.driver")
     *
     * @param Request $request
     * @return Response
     */
    public function databaseAction(Request $request)
    {
        $write = $this->getDriver('write');

        $result = $write->query('show tables')->execute()->getAll();

        $repository = new TestModel($write);

        return $this->render('database/drivers.twig', [
            'result' => $result,
            'all' => $repository->findAll(),
        ]);
    }

    /**
     * @Route("/builder", name="database.builder")
     *
     * @return Response|string
     */
    public function queryBuilderAction()
    {
        $queryBuilder = $this->getDriver('read')->getQueryBuilder();

        return $this->render('database/query.twig', [
            'from' => $queryBuilder->from('test', 't')->select(),
            'field' => $queryBuilder->fields(['id', 'name'])->from('test', 't')->select(),
            'where' => $queryBuilder->from('test')->where(['id' => 1])->select(),
            'limit' => $queryBuilder->from('test')->limit(5)->select(),
        ]);
    }

    /**
     * @Route("/orm", name="database.orm")
     *
     * @param Request $request
     * @return Response
     */
    public function ormAction(Request $request)
    {
        $demo = new TestEntity($this->getDriver('write'));

        $demo->setId('1');

        $demo->bindRequest($request);

        return $this->render('database/orm.twig', [
            'id' => $demo->save(),
        ]);
    }
}