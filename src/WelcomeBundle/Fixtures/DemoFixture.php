<?php
/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace WelcomeBundle\Fixtures;

use FastD\Database\Drivers\DriverInterface;
use FastD\Database\Fixtures\FixtureInterface;
use FastD\Database\Schema\Structure\Field;
use FastD\Database\Schema\Structure\Table;
use WelcomeBundle\ORM\Test\Entities\TestEntity;

class DemoFixture implements FixtureInterface
{
    /**
     * Create schema
     *
     * @return Table
     */
    public function loadSchema()
    {
        $table = new Table('test');

        $table->addField(new Field('id', Field::INT, 11));
        $table->addField(new Field('name', Field::VARCHAR, 20));

        return $table;
    }

    /**
     * Fill DB data.
     *
     * @param DriverInterface $driverInterface
     * @return mixed
     */
    public function loadDataSet(DriverInterface $driverInterface)
    {
        $entity = new TestEntity($driverInterface, ['id' => 1]);

        $entity->setId(1);
        $entity->setName('test');

        $entity->save();
    }
}