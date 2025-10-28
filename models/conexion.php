<?php

class conexiondb
{
    static public function conectar()
    {
        $link = new PDO("mysql:host=localhost;dbname=adm_db", "root", "");

        $link->exec("set names utf8");

        return $link;
    }
}
