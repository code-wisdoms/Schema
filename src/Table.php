<?php
namespace CodeWisdoms\Schema;

class Table
{
    private $data_cols = [];
    private $keys = [];
    public function __call(string $name, array $arguments): self
    {
        switch ($name) {
            case 'id':{
                    $col_name = @$arguments[0] ? $arguments[0] : 'id';
                    $this->data_cols[$col_name] = [
                        'type' => 'BIGINT',
                        'constraint' => 21,
                        'unsigned' => true,
                        'auto_increment' => true,
                    ];
                    $this->keys[$col_name] = [
                        'primary' => true,
                    ];
                    break;
                }
            case 'unsignedTinyInteger':{
                    $this->data_cols[$arguments[0]] = [
                        'type' => 'BIGINT',
                        'constraint' => 21,
                        'unsigned' => true,
                    ];
                    break;
                }
            case 'unsignedInteger':{
                    $this->data_cols[$arguments[0]] = [
                        'type' => 'INT',
                        'constraint' => 9,
                        'unsigned' => true,
                    ];
                    break;
                }
            case 'float':{
                    $this->data_cols[$arguments[0]] = [
                        'type' => 'FLOAT',
                        'constraint' => [
                            $arguments[1],
                            @$arguments[2] ? $arguments[2] : 2,
                        ],
                    ];
                    break;
                }
            case 'decimal':{
                    $this->data_cols[$arguments[0]] = [
                        'type' => 'DECIMAL',
                        'constraint' => [
                            $arguments[1],
                            @$arguments[2] ? $arguments[2] : 2,
                        ],
                    ];
                    break;
                }
            case 'string':{
                    $this->data_cols[$arguments[0]] = [
                        'type' => 'VARCHAR',
                        'constraint' => @$arguments[1] ? $arguments[1] : 255,
                    ];
                    break;
                }
            case 'mediumText':{
                    break;
                }
            case 'char':{
                    $this->data_cols[$arguments[0]] = [
                        'type' => 'CHAR',
                        'constraint' => @$arguments[1] ? $arguments[1] : 255,
                    ];
                    break;
                }
            case 'timestamp':
            case 'dateTime':{
                    $this->data_cols[$arguments[0]] = [
                        'type' => 'DATETIME',
                    ];
                    break;
                }
            case 'date':{
                    $this->data_cols[$arguments[0]] = [
                        'type' => 'DATE',
                    ];
                    break;
                }
            case 'time':{
                    $this->data_cols[$arguments[0]] = [
                        'type' => 'TIME',
                    ];
                    break;
                }
            case 'boolean':{
                    $this->data_cols[$arguments[0]] = [
                        'type' => 'BOOLEAN',
                    ];
                    break;
                }
            case 'text':{
                    $this->data_cols[$arguments[0]] = [
                        'type' => 'TEXT',
                    ];
                    break;
                }
            case 'integer':{
                    if (@$arguments[1] === true) {
                        return $this->id($arguments[0]);
                    }
                    $this->data_cols[$arguments[0]] = [
                        'type' => 'INT',
                        'constraint' => 9,
                    ];
                    break;
                }
            case 'tinyInteger':{
                    $this->data_cols[$arguments[0]] = [
                        'type' => 'TINYINT',
                        'constraint' => 2,
                    ];
                    break;
                }
            case 'binary':{
                    $this->data_cols[$arguments[0]] = [
                        'type' => 'BINARY',
                    ];
                    break;
                }
            case 'enum':{
                    $this->data_cols[$arguments[0]] = [
                        'type' => 'ENUM(\'' . implode("','", $arguments[1]) . '\')',
                    ];
                    break;
                }
            case 'longText':{
                    $this->data_cols[$arguments[0]] = [
                        'type' => 'LONGTEXT',
                    ];
                    break;
                }
            case 'useCurrent':{
                    $_key = $this->getLastKey();
                    array_pop($this->data_cols);
                    $this->data_cols[] = sprintf('`%s` TIMESTAMP DEFAULT CURRENT_TIMESTAMP', $_key);
                    break;
                }
            case 'comment':{
                    $this->data_cols[$this->getLastKey()]['comment'] = $arguments[0];
                    break;
                }
            case 'index':{
                    $this->keys[$this->getLastKey()] = [
                        'primary' => false,
                    ];
                    break;
                }
            case 'nullable':{
                    $this->data_cols[$this->getLastKey()]['null'] = true;
                    break;
                }
            case 'default':{
                    if ($this->data_cols[$this->getLastKey()]['type'] == 'BOOLEAN') {
                        $this->data_cols[$this->getLastKey()]['default'] = !!$arguments[0] ? 1 : 0;
                        break;
                    }
                    $this->data_cols[$this->getLastKey()]['default'] = $arguments[0];
                    break;
                }
            default:{
                    throw new \Exception('Unknown function: ' . $arguments[0]);
                }
        }
        return $this;
    }
    public function toArray(): array
    {
        return $this->data_cols;
    }
    public function keys(): array
    {
        return $this->keys;
    }
    private function getLastKey()
    {
        $keys = array_keys($this->data_cols);
        return array_pop($keys);
    }
}
