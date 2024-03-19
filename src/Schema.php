<?php
namespace CodeWisdoms\Schema;

class Schema
{
    public static function create(string $table_name, $table_definition)
    {
        $dbForge = self::getDBForgeInstance();
        if (self::checkIfExists($table_name)) {
            throw new \Exception("Table '{$table_name}' already exists.");
        }
        $new_table = new Table();
        $table_definition($new_table);

        if (self::isCI3()) {
            $dbForge->add_field($new_table->toArray());
        } else {
            $dbForge->addField($new_table->toArray());
        }

        foreach ($new_table->keys() as $key => $value) {
            if (self::isCI3()) {
                $dbForge->add_key($key, !!@$value['primary']);
            } else {
                $dbForge->addKey($key, !!@$value['primary']);
            }
        }
        if (self::isCI3()) {
            $dbForge->create_table($table_name);
        } else {
            $dbForge->createTable($table_name);
        }
    }
    public static function dropIfExists(string $table_name)
    {
        $dbForge = self::getDBForgeInstance();
        if (self::isCI3()) {
            return $dbForge->drop_table($table_name, true);
        }
        return $dbForge->dropTable($table_name, true);
    }
    private static function checkIfExists(string $table)
    {
        $db = self::getDBInstance();
        if (self::isCI3()) {
            return $db->table_exists($table);
        }
        return $db->tableExists($table);
    }
    private static function getDBInstance()
    {
        $db = null;
        if (self::isCI3()) {
            $CI = &get_instance();
            $CI->load->database();
            $db = &$CI->db;
        } else {
            $db = \Config\Database::connect();
        }
        return $db;
    }
    private static function getDBForgeInstance()
    {
        $dbForge = null;
        if (self::isCI3()) {
            $CI = &get_instance();
            $CI->load->dbforge();
            $dbForge = &$CI->dbforge;
        } else {
            $dbForge = \Config\Database::forge();
        }
        return $dbForge;
    }
    public static function isCI3(): bool
    {
        return function_exists('get_instance');
    }
}
