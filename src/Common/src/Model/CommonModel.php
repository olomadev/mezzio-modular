<?php
declare(strict_types=1);

namespace Common\Model;

use function array_column, iterator_to_array;

use Laminas\Db\Sql\Sql;
use Laminas\Cache\Storage\StorageInterface;
use Laminas\Db\Adapter\AdapterInterface;

class CommonModel implements CommonModelInterface
{
    private array $config;

    public function __construct(
        private AdapterInterface $adapter,
        private StorageInterface $cache,
        array $config
    ) {
        $this->config = $config;
    }
    
    public function findLocaleIds(): array
    {
        $rows = $this->findLocales();
        return array_column($rows, 'id') ?: [];
    }

    public function findLocales(): array
    {
        $key = CACHE_ROOT_KEY.Self::class.':'. __FUNCTION__;
        if ($this->cache->hasItem($key)) {
            return $this->cache->getItem($key);
        }
        try {
            $sql = new Sql($this->adapter);
            $select = $sql->select()
                ->from(['l' => 'languages'])
                ->columns([
                    'id' => 'langId',
                    'name' => 'langName'
                ])
                ->order('langName ASC');

            $statement = $sql->prepareStatementForSqlObject($select);
            $resultSet = $statement->execute();
            $results = iterator_to_array($resultSet, false);

            if (!empty($results)) {
                $this->cache->setItem($key, $results);
            }

            return $results;
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function getAdapter(): AdapterInterface
    {
        return $this->adapter;
    }
}
