<?
require_once(__DIR__."/config.php");

class Db
{
	private static $_connection = null;

    public static function update_data_by_id ($id, $data)
    {
        $text = self::escape_string($data['text']);
        $sum = self::check_type_int($data['sum']);

        $query = "UPDATE `data` SET
                `text` = '$text',
                `sum` = '$sum'
                WHERE `id` = $id";

        return self::query($query);
    }   

    public static function get_data()
    {
        $query = "SELECT * FROM data";

        return self::query($query);
    }

    public static function get_data_text_by_id ($id)
    {
        $id = self::check_type_int($id);
        $query = "SELECT `text` FROM `data` WHERE `id` = $id";

        return self::query($query)[0];
    }

	public static function connection() 
    {
		if ( self::$_connection ) {
			return self::$_connection;
		}

		self::$_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        self::$_connection->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);

        self::$_connection->set_charset("utf8");
		return self::$_connection;
	}

    public static function check_type_int ($val)
    {
        return intval($val);
    }

    public static function escape_string ($string)
    {
        $sql = Db::connection();

        return $sql->escape_string($string);
    }
    
    public static function error ()
    {
	    $sql = Db::connection();
	    return $sql->error;
	}

	public static function query ($query)
    {
        $sql = Db::connection();

        $res = $sql->query($query);

        if(!$res){
            $result = $res;
        }
        else if($res === true){
            $result = $res;
        }
        else if($res instanceof mysqli_result){
            $result = $res->fetch_all(MYSQLI_ASSOC);
        }
        else{
            $result = false;
        }

        return $result;
    }

    
}