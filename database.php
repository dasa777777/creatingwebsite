<?php




class db_connect
{

    public $conn;
    function __construct()
    {
        $this->conn = mysqli_connect('localhost', 'root', '', 'creatingwebsite');
    }
    //& pred premennou znamena, ze ide o pointer, cize premenna sa do funkcie poda systemom byRef a nie byVal
    //ked funkciu volam, tak sa vytvori premenna $result, ktora je prazdna. sem si ju podam a 
    //tuna, ked ju zmenim, bude zmenena aj o stupen vyssie, takze ako return value si mozem poslat,
    //ci funkcia zbehla alebo nie a stale sa mi z nej hore dostane do premennej vysledok SQLka
    function run_sql($select, $error, $params,&$result)
    {
        ///tuna sa vykona SQL, ktore posles do $select-u
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

        $res = mysqli_query($this->conn, $select);
        $result = array();
        while($fetch = mysqli_fetch_array($res))
        {
            $result[] = $fetch;
        }
        mysqli_close($this->conn);

        return true;
    }
}

