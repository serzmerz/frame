<?php

namespace Serz\Framework\Database;



use Pixie\Connection;
use Pixie\QueryBuilder\QueryBuilderHandler;
use Serz\Framework\DI\Conteiner;

/**
 * Class Database
 * @package Serz\Framework\Database
 */
class Database
{
    /**
     * config db
     *
     * @var array
     */
    protected $config;

    /**
     * @var Connection
     */
    protected $conection;

    /**
     * @var QueryBuilderHandler
     */
    public $qb;

    /**
     * Database constructor.
     * @param $config
     * @param $conection
     * @param $queryBuilder
     */
    public function __construct()
    {
        $this->config = Conteiner::get("config")["db"];
        $this->conection = new Connection('pgsql', $this->config);
        $this->qb = new QueryBuilderHandler($this->conection);
    }

}