<?php
    class models{
        public static $model    = "";
        public static $db       = "";
        function __construct(){
            self::db();
        }
        public static function db(){
            global $db;
            self::$db = $db;
            return self::$db;
        }
        public static function get($name){
            $db = self::db();
            if(file_exists(__DIR__."/models/".$name.".model.php"))  {
                include_once(__DIR__."/models/".$name.".model.php");
            }else {
                self::createmodel($name);
                include_once(__DIR__."/models/".$name.".model.php");
            }

            self::$model = new $name;
            return self::$model;
        }
        public static function createmodel($name){
            $db = self::$db;
            $s = $db->query("DESCRIBE $name");
            $str = "<?php \r\n";
            $str.="\t"."class $name extends models { \r\n";
            while($k = $s->fetch()){
                $str.="\t\t".'public $'.$k["Field"]."\t\t\t\t".' = "" ; //'.$k["Type"]."\r\n";
            }
            $str.="\t\t".'public $table'."\t\t\t\t".' = "'.$name.'" ; //String'."\r\n";
            $str.="\t};\r\n?>";

            if (!is_dir(__DIR__."/models/")) {
                mkdir(__DIR__."/models/", 0755, true);
            }
            file_put_contents(__DIR__."/models/".$name.".model.php",$str);
            return;

        }

        public static function save(){
            $db = self::$db;
            $table = self::$model->table;
            $sql = "insert into $table ";
            $field = [];
            foreach(self::$model as $k=>$v){
                if($k == "table" || $k == "id") {continue;}
                if(empty($v)) {continue;}
                $field["`".$k."`"] = self::vartype($v);

            }
            $sql.="(".implode(",",array_keys($field)).") VALUES ";
            $sql.="(".implode(",",array_values($field)).")";
            $sql = $db->query($sql);
            if(!$sql) {return (object)array_merge(["error" => true],$db->errorInfo());}
            return $db->lastInsertId();
        }

        public static function vartype($v){
            $r = "" ;
            switch(gettype($v)) {
                case "string";
                    $r = empty($v) ? "''" : "'".$v."'";
                break;
                case "integer";
                    $r = empty($v) ? "" : $v;
                break;
                case "float";
                    $r = empty($v) ? "" : $v;
                break;
                default;
                    $r = $v;
                break;
            }
            return $r;
        }

        public static function update(){
            $db = self::$db;
            $table = self::$model->table;
            $sql = "update $table SET ";
            $field = [];
            foreach(self::$model as $k=>$v){
                if($k == "table" || $k == "id") {continue;}
                if(empty($v)) {continue;}
                $field[] = "`".$k."`" ."=".self::vartype($v);
            }
            $sql.=implode(",",array_values($field));
            $sql.=" WHERE id=".self::$model->id;
            $sql = $db->query($sql);
            if(!$sql) {return (object)array_merge(["error" => true],$db->errorInfo());}
            return true;
        }
        public static function delete($find="id",$field="id"){
            $db = self::$db;
            $table = self::$model->table;
            $sql = "delete from $table ";
            $sql.=" WHERE $field=".($find == "id" ? self::$model->id : $find );
            if(!$sql) {return (object)array_merge(["error" => true],$db->errorInfo());}
            return true;
        }
        public static function toarray(){
            return (array)self::$model;
        }

        public static function filter($r=""){
            $model = self::$model;
            foreach($model as $k=>$v) {
                if(!in_array($k,$r)){
                    unset($model->$k);
                }
            }
            return $model;

        }

        public static function findall($field="id",$orderby="DESC",$limit=10){
            $db = self::$db;
            $table = self::$model->table;
            $sql = "select * from $table ";
            $sql.=" order by  $field $orderby LIMIT $limit";
            $s = $db->query($sql);
            if($s->rowCount() == 0) {return false;}
            $models = [];
            while($k = $s->fetch(PDO::FETCH_ASSOC)){
                $clone  = [];
                foreach($k as $x=>$y){
                    $clone[$x] = empty($y) ? "" : $y;
                }
                $models[] = (object)$clone;
            }
            return  (count($models) == 1 ? self::fill($models[0]) : $models);
        }

        public static function find($find,$field="id",$orderby="DESC"){
            $db = self::$db;
            $table = self::$model->table;
            $sql = "select * from $table ";
            $sql.=" WHERE $field=".self::vartype($find);
            $sql.=" order by  $field $orderby";
            $s = $db->query($sql);
            if($s->rowCount() == 0) {return false;}
            $models = [];
            while($k = $s->fetch(PDO::FETCH_ASSOC)){
                $clone  = [];
                foreach($k as $x=>$y){
                    $clone[$x] = empty($y) ? "" : $y;
                }
                $models[] = (object)$clone;
            }
            return  (count($models) == 1 ? self::fill($models[0]) : $models);
        }

        public static function fill($data=""){

            $model = self::$model;
            foreach($data as $k=>$v){
                $model->$k = $data->$k;
            }
            return $model;
        }

    }

?>
