<?php
declare(strict_types=1);

namespace i18n\Model;

use Laminas\Db\Sql\Sql;
use Laminas\Cache\Storage\StorageInterface;
use Laminas\Db\TableGateway\TableGatewayInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Olobase\Mezzio\ColumnFiltersInterface;

class i18nModel implements i18nModelInterface
{
    private $adapter;

	public function __construct(
        private TableGatewayInterface $languages,
		private StorageInterface $cache,
        private ColumnFiltersInterface $columnFilters,
	) {
        $this->adapter = $languages->getAdapter();
	}

	public function findAll(): array
	{
		$key = CACHE_ROOT_KEY.Self::class.':'. __FUNCTION__;
		if ($this->cache->hasItem($key)) {
			return $this->cache->getItem($key);
		}
		try {
			$sql = new Sql($this->adapter);
			$select = $sql->select();
			$select->columns([
                'id' => 'langId',
                'name' => 'langName'
            ]);
			$select->from(['l' => 'languages']);
			$statement = $sql->prepareStatementForSqlObject($select);
			$resultSet = $statement->execute();
			$results = iterator_to_array($resultSet, false);
            
            // echo $select->getSqlString($adapter->getPlatform());
            // die;
			if (!empty($results)) {
				$this->cache->setItem($key, $results);
			}
			return $results;
		} catch (\Throwable $e) {
            throw $e;
		}
	}

	public function getAdapter(): AdapterInterface
	{
		return $this->adapter;
	}
}
