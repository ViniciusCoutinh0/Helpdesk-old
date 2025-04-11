<?php

namespace App\Layer;

use App\Layer\Crud;
use App\Layer\Instance\Db;
use App\Layer\Exception\DbException;

abstract class Layer
{
    use Crud;

    /** @var string */
    protected $table;
    /** @var string */
    protected $prefix;
    /** @var array */
    protected $hidden = [];
    /** @var bool */
    protected $debug = false;

    /** @var string */
    private $statement;
    /** @var string */
    private $order;
    /** @var \stdClass */
    private $data;

    /** @var array */
    private $clause;
    private $clauseAppended;

    public function __construct(string $prefix = 'id')
    {
        if ($this->prefix === null) {
            $this->prefix = $prefix;
        }
    }

    public function __get($name)
    {
        return ($this->data->$name ?? null);
    }

    public function __set($name, $value): void
    {
        if (empty($this->data)) {
            $this->data = new \stdClass();
        }

        $this->hide();
        $this->data->$name = $value;
    }

    public function __isset($name): bool
    {
        return isset($this->data->$name);
    }

    /**
     * @return \stdClass
     */
    protected function data(): \stdClass
    {
        return $this->data;
    }

    /**
     * @param string $columns
     * @param int $top
     * @return Layer
     */
    public function find(string $columns = '*'): Layer
    {
        $this->statement = "SELECT {$columns} FROM {$this->table}";
        return $this;
    }

    /**
     * @param int $prefix
     * @param string $columns [default = *]
     * @return Layer
     */
    public function findBy(int $prefix, string $columns = '*'): Layer
    {
        $this->find($columns)->where([$this->prefix => $prefix]);
        return $this;
    }

    /**
     * @param array $where
     * @param string $operator [dafault = AND]
     * @return Layer
     */
    public function where(array $where, string $operator = 'AND'): Layer
    {
        if (count($where)) {
            $fields = [];
            $values = [];

            foreach ($where as $key => $value) {
                $fields[] = "{$key} = ?";
                $values[] = $value;
            }

            $this->setClause('where', [
                'fields' => "WHERE " . implode(" {$operator} ", $fields),
                'values' => $values
            ]);
            return $this;
        }
    }

    /**
     * @param string $table
     * @param string $column
     * @param string $operator
     * @param string $table_link
     * @return Layer
     */
    public function join(string $table, string $column, string $operator, string $on): Layer
    {
        $this->setClauseAppend('join', "INNER JOIN dbo.{$table} ON {$column} {$operator} {$on}");
        return $this;
    }

    /**
     * @param string $table
     * @param string $column
     * @param string $operator
     * @param string $table_link
     * @return Layer
     */
    public function left(string $table, string $column, string $operator, string $on): Layer
    {
        $this->setClauseAppend('left', "LEFT JOIN dbo.{$table} ON {$column} {$operator} {$on}");
        return $this;
    }

    /**
     * @param string $table
     * @param string $column
     * @param string $operator
     * @param string $table_link
     * @return Layer
     */
    public function right(string $table, string $column, string $operator, string $on): Layer
    {
        $this->setClauseAppend('right', "RIGHT JOIN dbo.{$table} ON {$column} {$operator} {$on}");
        return $this;
    }

    /**
     * @param string $column
     * @param string $operator
     * @param string $value
     * @return Layer
     */
    public function orWhere(string $column, string $operator, string $value): Layer
    {
        $this->setClauseAppend('orWhere', "{$column} {$operator} {$value} {{OR}}");
        return $this;
    }

    public function andWhere(string $column, string $value, string $operator = '=')
    {
        $this->setClauseAppend('andWhere', sprintf('and %s %s %s', $column, $operator, $value));
        return $this;
    }

    /**
     * @return null|array
     */
    public function all(): ?array
    {
        $find = Db::getInstance()->prepare($this->build(), [\PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL]);
        $find->execute(($this->clause['where']['values'] ?? null));

        if ($this->debug) {
            var_dump($this->build());
        }

        if (!$find->rowCount()) {
            return null;
        }

        return $find->fetchAll(\PDO::FETCH_CLASS, static::class);
    }

    /**
     * @return null|object
     */
    public function first(): ?object
    {
        $find = Db::getInstance()->prepare($this->build(), [\PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL]);
        $find->execute(($this->clause['where']['values'] ?? null));

        if ($this->debug) {
            var_dump($this->build());
        }

        if (!$find->rowCount()) {
            return null;
        }

        return $find->fetchObject(static::class);
    }

    /**
     * @return null|int
     */
    public function count(): ?int
    {
        $find = Db::getInstance()->prepare($this->build(), [\PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL]);
        $find->execute(($this->clause['where']['values'] ?? null));

        if ($this->debug) {
            var_dump($this->build());
        }

        if (!$find->rowCount()) {
            return null;
        }

        return (int) $find->rowCount();
    }

    /**
     * @param string $column
     * @param string $default [default = ASC]
     * @return Layer
     */
    public function orderBy(string $column, string $default = 'ASC'): Layer
    {
        $this->order = "ORDER BY {$column} {$default}";
        return $this;
    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        $column = $this->prefix;
        $id = null;

        if (!empty($this->data->$column)) {
            $id = $this->data->$column;
            $this->update($this->filter(), "{$this->prefix} = {$id}");
        }

        if (empty($this->data->$column)) {
            $id = $this->create($this->filter());
        }

        if (!$id) {
            throw new DbException('Não foi possivel localizar o ID do registro');
        }

        $this->data = $this->findBy($id)->data();
        return true;
    }

    /**
     * @return bool
     */
    public function destroy(): bool
    {
        $id = null;

        if (empty($id)) {
            throw new DbException('Não foi possivel localizar o ID do registro');
        }

        $this->delete($id);
        return true;
    }

    /**
     * @return string
     */
    private function build(): string
    {

        return $this->statement . ' ' .
            ($this->clauseAppended['join'] ?? null) .
            ($this->clauseAppended['left'] ?? null) .
            ($this->clauseAppended['right'] ?? null) . ' ' .
            ($this->clause['where']['fields'] ?? null) . ' ' .
            ($this->clauseAppended['andWhere'] ?? null) . ' ' .
            $this->handlerOrWhere() . ' ' .
            ($this->order ?? null);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    private function setClause(string $key, $value): void
    {
        $this->clause[$key] = $value;
    }

    private function setClauseAppend(string $key, $value): void
    {
        if (isset($this->clauseAppended[$key])) {
            $this->clauseAppended[$key] .= ' ' . $value;
        } else {
            $this->clauseAppended[$key] = $value;
        }
    }

    /**
     * @return void
     */
    private function hide(): void
    {
        if (count($this->hidden)) {
            foreach ($this->hidden as $key) {
                if (isset($this->data->$key)) {
                    unset($this->data->$key);
                }
            }
        }
    }

    /**
     * @return array
     */
    private function filter(): array
    {
        $filter = (array) $this->data;
        unset($filter[$this->prefix]);
        return $filter;
    }

    /**
     * @return null|string
     */
    private function handlerOrWhere(): ?string
    {
        if (isset($this->clauseAppended['orWhere'])) {
            $lastOr = mb_strripos($this->clauseAppended['orWhere'], '{{OR}}');
            $replace = substr_replace($this->clauseAppended['orWhere'], '', $lastOr);
            $replace = str_replace('{{OR}}', 'OR', $replace);

            return $this->append() . ' ' . $replace;
        }
        return null;
    }

    /**
     * @return string
     */
    private function append(): string
    {
        if (isset($this->clause['where'])) {
            return 'OR';
        }
        return 'WHERE';
    }
}
