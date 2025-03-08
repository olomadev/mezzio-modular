<?php
declare(strict_types=1);

namespace Common\Model;

use Laminas\Db\Sql\Sql;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\TableGateway\TableGatewayInterface;

class FileModel implements FileModelInterface
{
    private $conn;

    public function __construct(
        private AdapterInterface $adapter,        
        private TableGatewayInterface $files
    )
    {
        $this->files = $files;
        $this->adapter = $adapter;
        $this->conn = $this->adapter->getDriver()->getConnection();
    }

    /**
     * Find one file by file id
     * 
     * @param  string $fileId
     * @return array
     */
    public function findOneById(string $fileId)
    {
        $sql = new Sql($this->adapter);
        $select = $sql->select();
        $select->columns(
            [
                'id' => 'fileId',
                'name' => 'fileName',
                'size' => 'fileSize',
                'type' => 'fileType',
                'data' => 'fileData',
                'tag' => 'fileTag',
                'dim' => 'fileDimension',
            ]
        );
        $select->from(['f' => 'files']);
        $select->where(['f.fileId' => $fileId]);
        $statement = $sql->prepareStatementForSqlObject($select);
        $resultSet = $statement->execute();
        $row = $resultSet->current();
        return  $row;
    }

}