<?php
/**
 * Created by PhpStorm.
 * User: xqq-exponent
 * file: Model.php
 * Date: 2018/2/28
 * Time: 17:50
 */

namespace frame;

use ArrayAccess;

class Model
{
    protected $table = '';

    /** @var \PDO*/
    private $conn;

    private $clause = [];

    protected $attribute = [];

    /**
     * Model constructor.
     * @throws db\exception\DbException
     */
    public function __construct()
    {
        $this->getTable();
        $this->conn = Db::connect();
    }

    public function __get($name)
    {
        return $this->getAttr($name);
    }

    public function getAttr($name)
    {
        return $this->attribute[$name];
    }

    public function select()
    {
        $sql = "SELECT * FROM {$this->getTable()}";
        if($this->clause['where']){
            $where = $this->clause['where'];
            $sql .= " WHERE $where";
        }

        $stmt = $this->conn->prepare($sql);

        $stmt->execute();
        $result = $this->getResult($stmt);
        return $result;
    }

    public function where($where)
    {
        $this->clause['where'] = $where;
        return $this;
    }

    private function getTable()
    {
        if(!$this->table){
            $class = static::class;
            $class = end(explode('\\', $class));
            $this->table = $class;
        }
        return $this->table;
    }

    private function getResult(\PDOStatement $stmt)
    {
        $result_type = Config::get('database.resultset_type');
        switch ($result_type){
            case 'collection':
                $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                $result = new Collection($result);
                break;
            case 'object':
                $result = [];
                while ($item = $stmt->fetch(\PDO::FETCH_ASSOC)){
                    $model = clone $this;
                    $model->attribute = $item;
                    $result[] = $model;
                }
                $result = new Collection($result);
                break;
            case 'json':
                $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                $result = json_encode($result);
                break;
            case 'array':
            default:
                $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                break;
        }
        return $result;
    }

    public function __destruct()
    {
        $this->conn = null;
    }
}