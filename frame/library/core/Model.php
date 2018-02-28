<?php
/**
 * Created by PhpStorm.
 * User: xqq-exponent
 * file: Model.php
 * Date: 2018/2/28
 * Time: 17:50
 */

namespace frame;


class Model
{
    protected $table = '';

    /** @var \PDO*/
    private $conn;

    private $params = [];

    /**
     * Model constructor.
     * @throws db\exception\DbException
     */
    public function __construct()
    {
        $this->getTable();
        $this->conn = Db::connect();
    }

    public function select()
    {
        $sql = "SELECT * FROM {$this->table}";
        if($this->params['where']){
            $where = $this->params['where'];
            $sql .= " WHERE $where";
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function where($where)
    {
        $this->params['where'] = $where;
        return $this;
    }

    private function getTable()
    {
        if(!$this->table){
            $class = static::class;
            $class = end(explode('\\', $class));
            $this->table = $class;
        }
    }
}