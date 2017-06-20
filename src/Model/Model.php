<?php

namespace Serz\Framework\Model;


use Serz\Framework\Database\Database;

/**
 * Class Model
 * @package Serz\Framework\Model
 */
class Model
{

    /**
     * @var
     */
    public $table;

    /**
     * @var \Pixie\QueryBuilder\QueryBuilderHandler
     */
    public $qb;

    /**
     * @var
     */
    public $rawData;

    /**
     * Model constructor.
     */
    public function __construct()
    {
        $db = new Database();
        $this->qb = $db->qb;
    }

    /**
     * @param $id
     * @return null|\stdClass
     */
    public function findOne($id){
        return $this->qb->table($this->table)->find($id);
    }

    /**
     * @return null|\stdClass
     */
    public function findAll(){
        return $this->qb->table($this->table)->get();
    }

    /**
     * @param array $columns
     * @param array $values
     */
    public function insert(array $columns = [], array $values = [])
    {
        $data = array_combine($columns, $values);
        $this->qb->table($this->table)->insert($data);
    }

    /**
     * @param int $id
     * @param array $columns
     * @param array $values
     */
    public function update(int $id, array $columns = [], array $values = [])
    {
        $data = array_combine($columns, $values);
        $this->qb->table($this->table)->where('id', $id)->update($data);
    }

    /**
     * @param int $id
     */
    public function delete(int $id)
    {
        $this->qb->table($this->table)->where('id', $id)->delete();
    }

    /**
     *
     */
    public function save()
    {
        $this->insert(array_keys($this->rowData), $this->rowData);
    }

    /**
     * @return Model
     */
    public function create(): self{
        return Injector::make(get_class($this));
    }

    /**
     * @param $varname
     * @return null
     */
    public function __get($varname){
        return isset($this->rawData[$varname]) ? $this->rawData[$varname] : null;
    }

    /**
     * @param $varname
     * @param $value
     */
    public function __set($varname, $value){
        $this->rowData[$varname] = $value;
    }
}