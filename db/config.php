<?php

namespace Model;

class Config {
    public $server;
    public $user;
    public $pwd;
    public $db;

    private function __construct($server, $user, $pwd, $db) {
        $this->server = $server;
        $this->user = $user;
        $this->pwd = $pwd;
        $this->db = $db;
    }

    public static function getConfig($type) {
        if ($type == "local") {
            return new self("localhost:3306", "root", "Bw5n8raTfyNBq32F", "td2");
        } elseif ($type == "test") {
            return new self("10.1.0.243:3306", "m.dvoretskiy", "Bw5n8raTfyNBq32F", "order24");
        } elseif ($type == "work") {
            return new self("10.1.1.37:3306", "m.dvoretskiy", "Bw5n8raTfyNBq32F", "order24");
        } elseif ($type == "predprod") {
            return new self("10.1.1.37:3306", "m.dvoretskiy", "Bw5n8raTfyNBq32F", "predprod_utr");
        }
    }
}