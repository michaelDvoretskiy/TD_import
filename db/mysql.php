<?php

namespace Model;

class MySQLdb {
    private $link;
    public $err;
    private $config;

    public function __construct($config) {
        $this->config = $config;
    }
    public function connect() {
        $this->link = new \mysqli($this->config->server, $this->config->user, $this->config->pwd, $this->config->db);
        $this->link->set_charset("utf8");
        if (!$this->link) {
            return false;
        }
        // $this->runQuery("SET NAMES 'uft-8'");
        return true;
    }
    public function disconnect() {
        if (!is_null($this->link)) {
            $this->link->close();
            unset($this->link);
        }   
    }
    public function runQuery($sql) {
        $res = $this->link->query($sql);
        if (!$res) {
            $this->err = $this->link->error;
        }
        return $res;
    }
    public function getArrFromQuery($sql) {
        $res_arr = [];
        $rs = $this->runQuery($sql);
        while($row = $rs->fetch_assoc()) {
            $res_arr[] = $row;
        }
        return $res_arr;
    }

    public function getArr($sql, $disconnect = false) {
        if (!isset($this->link)) {
            $this->connect();    
        }
        $arr = $this->getArrFromQuery($sql);
        if ($disconnect) {

        }
        $this->disconnect();
        return $arr;
    }

    public function runSQL($sql) {
        if (!isset($this->link)) {
            $this->connect();    
        }
        return $this->runQuery($sql);
    }
    public function getInsId() {
        if (isset($this->link)) {
            return $this->link->insert_id;
        } 
        return null;
    }

    public function escapeStr($Str) {
        if (!isset($this->link)) {
            $this->connect();    
        }
        return $this->link->real_escape_string($Str); 
    }
}