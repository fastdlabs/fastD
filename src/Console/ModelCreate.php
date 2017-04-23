<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ModelCreate.
 */
class ModelCreate extends Command
{
    public function configure()
    {
        $this->setName('model:create');
        $this->addArgument('name', InputArgument::REQUIRED);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $modelPath = app()->getPath().'/src/Model';
        if (!file_exists($modelPath)) {
            mkdir($modelPath, 0755, true);
        }

        $name = ucfirst($input->getArgument('name')).'Model';
        $content = $this->createModelTemplate($name);

        $modelFile = $modelPath.'/'.$name.'.php';

        if (file_exists($modelFile)) {
            throw new \LogicException(sprintf('Model %s is already exists', $name));
        }

        file_put_contents($modelFile, $content);
        $output->writeln(sprintf('Model %s created successful. path in %s', $name, $modelPath));
    }

    protected function createModelTemplate($name)
    {
        $table = strtolower(str_replace('Model', '', $name));

        return <<<MODEL
<?php

namespace Model;


use FastD\Model\Model;

class {$name} extends Model
{
    const TABLE = '{$table}';
    const LIMIT = '15';

    public function select(\$page = 1)
    {
        \$offset = (\$page - 1) * static::LIMIT;
        return \$this->db->select(static::TABLE, '*', [
            'LIMIT' => [\$offset, static::LIMIT]
        ]);
    }

    public function find(\$id)
    {
        return \$this->db->get(static::TABLE, '*', [
            'OR' => [
                'id' => \$id,
            ]
        ]);
    }

    public function patch(\$id, array \$data)
    {
        \$affected = \$this->db->update(static::TABLE, \$data, [
            'id' => \$id,
        ]);

        return \$this->find(\$id);
    }

    public function create(array \$data)
    {
        \$this->db->insert(static::TABLE, \$data);

        return \$this->find(\$this->db->id());
    }

    public function delete(\$id)
    {
        return \$this->db->delete(static::TABLE, [
            'id' => \$id
        ]);
    }
}
MODEL;
    }
}
