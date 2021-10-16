<?php
function adminer_object() {
    class AdminerSoftware extends Adminer {
        function login($login, $password) {
            global $jush;
            if ($jush == "sqlite")
                return ($login === 'admin') && ($password === 'changeme');
            return true;
        }
        function databases($flush = true) {
            /* if (isset($_GET['sqlite']))
                return ["c:\OpenServer\domains\yii\core\common\runtime\sqlite\main.db", "/path/to/second.db"]; */
            return get_databases($flush);
        }
    }
    return new AdminerSoftware;
}
include "./{{adminerPhpModule}}";
