<?php
declare(strict_types=1);

namespace Authorization\Model;

use Exception;
use Olobase\Mezzio\ColumnFiltersInterface;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Sql\Expression;
use Laminas\Paginator\Paginator;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\TableGateway\TableGatewayInterface;
use Psr\SimpleCache\CacheInterface as SimpleCacheInterface;

class UserRolesModel implements UserRolesModelInterface
{
    private $conn;
    private $adapter;
    private $users;
    private $cache;
    private $simpleCache;
    private $userAvatars;
    private $columnFilters;

    public function __construct(
        TableGatewayInterface $users,
        TableGatewayInterface $userAvatars,
        ColumnFiltersInterface $columnFilters,
        SimpleCacheInterface $simpleCache
    ) {
        $this->adapter = $users->getAdapter();
        $this->users = $users;
        $this->userAvatars = $userAvatars;
        $this->columnFilters = $columnFilters;
        $this->simpleCache = $simpleCache;
        $this->conn = $this->adapter->getDriver()->getConnection();
    }

    /**
     * A
     * @param  string $userId [description]
     * @param  array  $roles  [description]
     * @return [type]         [description]
     */
    public function assignRoles(string $userId, array $roles)
    {

    }

    public function unassignRoles(string $userId, array $roles)
    {

    }

    public function findAllBySelect()
    {
        $sql = new Sql($this->adapter);
        $select = $sql->select();
        $select->columns([
            'id' => 'userId',
            'firstname',
            'lastname',
            'email',
            'active',
            'createdAt',
        ]);
        $select->from(['u' => 'users']);
        return $select;
    }

    public function findAllByPaging(array $get) : Paginator
    {
        $select = $this->findAllBySelect();
        $this->columnFilters->clear();
        $this->columnFilters->setColumns([
            'firstname',
            'lastname',
            'email',
            'active',
        ]);
        $this->columnFilters->setLikeColumns(
            [
                'firstname',
                'lastname',
                'email',
            ]
        );
        $this->columnFilters->setWhereColumns(
            [
                'active',
            ]
        );
        $this->columnFilters->setSelect($select);
        $this->columnFilters->setData($get);

        if ($this->columnFilters->searchDataIsNotEmpty()) {
            $nest = $select->where->nest();
            foreach ($this->columnFilters->getSearchData() as $col => $words) {
                $nest = $nest->or->nest();
                foreach ($words as $str) {
                    $nest->or->like(new Expression($col), '%'.$str.'%');
                }
                $nest = $nest->unnest();
            }
            $nest->unnest();
        }
        if ($this->columnFilters->likeDataIsNotEmpty()) {
            foreach ($this->columnFilters->getLikeData() as $column => $value) {
                if (is_array($value)) {
                    $nest = $select->where->nest();
                    foreach ($value as $val) {
                        $nest->or->like(new Expression($column), '%'.$val.'%');
                    }
                    $nest->unnest();
                } else {
                    $select->where->like(new Expression($column), '%'.$value.'%');
                }
            }   
        }
        if ($this->columnFilters->whereDataIsNotEmpty()) {
            foreach ($this->columnFilters->getWhereData() as $column => $value) {
                if (is_array($value)) {
                    $nest = $select->where->nest();
                    foreach ($value as $val) {
                        $nest->or->equalTo(new Expression($column), $val);
                    }
                    $nest->unnest();
                } else {
                    $select->where->equalTo(new Expression($column), $value);
                }
            }
        }
        // date filters
        // 
        $this->columnFilters->setDateFilter('createdAt');
        // orders
        // 
        if ($this->columnFilters->orderDataIsNotEmpty()) {
            foreach ($this->columnFilters->getOrderData() as $order) {
                $select->order($order);
            }
        }
        // echo $select->getSqlString($this->adapter->getPlatform());
        // die;
        $paginatorAdapter = new DbSelect(
            $select,
            $this->adapter
        );
        return new Paginator($paginatorAdapter);
    }

    public function getAdapter() : AdapterInterface
    {
        return $this->adapter;
    }
}
