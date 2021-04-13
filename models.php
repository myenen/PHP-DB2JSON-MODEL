<?php
    class models{
        public static $model = "";
        public static function get($name){
            if(file_exists("models/".$name.".model.php"))  {
                include_once("models/".$name.".model.php");
            }else {
                self::createmodel($name);
                include_once("models/".$name.".model.php");
            }

            self::$model = new $name;
            return self::$model;
        }

        public static function createmodel($name){
            global $db;
            $s = $db->query("DESCRIBE $name");
            $str = "<?php \r\n";
            $str.="\t"."class $name extends models { \r\n";
            while($k = $s->fetch()){
                $str.="\t\t".'public $'.$k["Field"]."\t\t\t\t".' = "" ; //'.$k["Type"]."\r\n";
            }
            $str.="\t\t".'public $table'."\t\t\t\t".' = "'.$name.'" ; //String'."\r\n";
            $str.="\t};\r\n?>";
            file_put_contents("models/".$name.".model.php",$str);

        }

        public static function save(){
            $table = self::$model->table;
            $sql = "insert into $table ";
            $field = [];
            foreach(self::$model as $k=>$v){
                if($k == "table" || $k == "id") {continue;}
                $field["`".$k."`"] = self::vartype($v);

            }
            $sql.="(".implode(",",array_keys($field)).") VALUES ";
            $sql.="(".implode(",",array_values($field)).")";
            return $sql;
        }

        public static function vartype($v){
            $r = "" ;
            switch(gettype($v)) {
                case "string";
                    $r = empty($v) ? "``" : "`".$v."`";
                break;
                case "integer";
                    $r = empty($v) ? "" : $v;
                break;
                case "float";
                    $r = empty($v) ? "" : $v;
                break;
                default;
                    $r = empty($v) ? "``" : "`".$v."`";
                break;
            }
            return $r;
        }

        public static function update(){
            $table = self::$model->table;
            $sql = "update $table SET ";
            $field = [];
            foreach(self::$model as $k=>$v){
                if($k == "table" || $k == "id") {continue;}
                if(empty($v)) {continue;}
                $field["`".$k."`"] = self::vartype($v);
            }
            $sql.=implode(",",array_values($field));
            $sql.=" WHERE id=".self::$model->id;
            return $sql;
        }
        public static function delete($find="id",$field="id"){
            $table = self::$model->table;
            $sql = "delete from $table ";
            $sql.=" WHERE $field=".($find == "id" ? self::$model->id : $find );
            return $sql;
        }
        public static function toarray(){
            return (array)self::$model;
        }
        public static function find($find,$field="id"){
            global $db;
            $table = self::$model->table;
            $sql = "select * from $table ";
            $sql.=" WHERE $field=".$find;
            $s = $db->query($sql);
            if($s->rowCount() == 0) {return self::$model;}
            $models = [];
            while($k = $s->fetch(PDO::FETCH_ASSOC)){
                $clone  = (array)self::$model;
                foreach($k as $x=>$y){
                    $clone[$x] = $y;
                }
                $models[] =(object) $clone;
            }
            return  (count($models) == 1 ? $models[0] : $models);
        }

    }

?>
