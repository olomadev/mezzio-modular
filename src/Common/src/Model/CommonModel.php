<?php
declare(strict_types=1);

namespace Common\Model;

use function array_column, iterator_to_array;

use Exception;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Sql\Expression;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Cache\Storage\StorageInterface;
use Laminas\Db\Sql\Predicate\IsNotNull;

class CommonModel implements CommonModelInterface
{
    private $config;

    public function __construct(
        private AdapterInterface $adapter,
        private StorageInterface $cache,
        array $config
    )
    {
        $this->cache = $cache;
        $this->config = $config;
        $this->adapter = $adapter;
    }
    
    public function findActions()
    {
        return [
            [
                'id' => 'create',
                'name' => 'Create',
            ],
            [
                'id' => 'delete',
                'name' => 'Delete',
            ],
            [
                'id' => 'edit',
                'name' => 'Edit',
            ],
            [
                'id' => 'list',
                'name' => 'List',
            ],
            [
                'id' => 'show',
                'name' => 'Show',
            ],
        ];
    }

    public function findMethods()
    {
        return [
            [
                'id' => 'POST',
                'name' => 'POST',
            ],
            [
                'id' => 'GET',
                'name' => 'GET',
            ],
            [
                'id' => 'PUT',
                'name' => 'PUT',
            ],
            [
                'id' => 'DELETE',
                'name' => 'DELETE',
            ],
            [
                'id' => 'PATCH',
                'name' => 'PATCH',
            ],
        ];
    }
        
    public function findLocaleIds()
    {
        $rows = $this->findLocales();
        $results = array_column($rows, 'id');
        return $results;
    }

    public function findLocales()
    {
        $key = CACHE_ROOT_KEY.Self::class.':'.__FUNCTION__;
        if ($this->cache->hasItem($key)) {
            return $this->cache->getItem($key);
        }
        $sql    = new Sql($this->adapter);
        $select = $sql->select();
        $select->columns(
            [
                'id' => 'langId',
                'name' => 'langName'
            ]
        );
        $select->from(['l' => 'languages']);
        $select->order(['langName ASC']);
        $statement = $sql->prepareStatementForSqlObject($select);
        $resultSet = $statement->execute();
        $results = iterator_to_array($resultSet);
        if ($results) {
            $this->cache->setItem($key, $results);    
        }
        return $results;
    }

    public function getAdapter() : AdapterInterface
    {
        return $this->adapter;
    }

}