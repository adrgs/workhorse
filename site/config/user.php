<?php

class User
{
    public $id;
    public $email;
    public $balance;
    public $vms;

    function __construct($_id, $_email) {
        $this->id = (int)$_id;
        $this->email = $_email;

        if ($this->id<1) return;

        $db = new Database();
        $conn = $db->getConnection();

        $query = "SELECT Id,Name,Subdomain,StartDate,Active,Price FROM VirtualMachines WHERE Active=1 AND IdUser=".$this->id;
        $data=mysqli_query($conn,$query);

        $this->vms = array();

        while($row=mysqli_fetch_array($data)){
            array_push($this->vms, new VM(row[0],row[1],row[2],row[3],row[4],row[5]));
        }

        $query = "SELECT Balance FROM AccountBalance WHERE IdUser=".$this->id;
        $data=mysqli_query($conn,$query);

        while($row=mysqli_fetch_array($data)){
            $this->balance = (int)$row['Balance'];
        }
        

    }

}


?>