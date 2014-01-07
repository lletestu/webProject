<?php

/*
* Represent a profile page we can see : attached to a member and to updates
*/
class Profile
{
    // Member type
    public $member = null;

    // Array of Update objects
    public $updates = null;

    /*
    Fill all properties thanks to the database
    */
    public function __construct($email)
    {
        if (isset($_SESSION['email']) or $email != null) {
            $this->member = new Member($email);

            // SELECT
            $bdd = Database::connexionDDB();
            $idMember = $this->member->idMember;
            $result = $bdd->query("select idUpdate, content, date, service from Updates where idMember = '$idMember'");

            $this->updates = array();
            $tmpArray = array();
            while ($data = $result->fetch(PDO::FETCH_OBJ)) {
                /* $this->updates[$i]['idUpdate'] = $data->idUpdate;
                 $this->updates[$i]['content'] = $data->content;
                 $this->updates[$i]['date'] = $data->date;
                 $this->updates[$i]['service'] = $data->service;*/
                $tmpArray['idUpdate'] = $data->idUpdate;
                $tmpArray['content'] = $data->content;
                $tmpArray['date'] = $data->date;
                $tmpArray['service'] = $data->service;

                $this->updates[] = new Update($tmpArray['idUpdate'], $tmpArray['content'], $tmpArray['date'], $tmpArray['service'], $idMember);

            }
        } else {
            echo 'error not connected';
        }

    }

    public function addUpdates($content = "",$date = "",$service =""){
         if(isset($_SESSION['email'])){
             $update = new Update(null,$content,$date,$service,$this->member->idMember);
             $this->updates[] = $update;
             $update->save();
         }else{
             echo 'error not connected';
         }
     }

    public function getArrayMember()
    {
        $tmpMember = $this->member;
        //$memberArray = array($tmpMember->idMember, $tmpMember->firstname, $tmpMember->lastname, $tmpMember->email, $tmpMember->sex, $tmpMember->isAdmin, $tmpMember->description, $tmpMember->image, $tmpMember->website);
        $memberArray = array(
            'firstname' => $tmpMember->firstname,
            'lastname' => $tmpMember->lastname,
            'email' => $tmpMember->email,
            'sex' => $tmpMember->sex,
            'isAdmin' => $tmpMember->isAdmin,
            'description' => $tmpMember->description,
            'image' => $tmpMember->image,
            'website' => $tmpMember->website);
        return $memberArray;
    }

    public function getarrayUpdate()
    {
        $tmpUpdates = $this->updates;
        $tmpArray = array();
        for ($i = 0; $i < sizeof($tmpUpdates); $i++) {
            //$tmpArray[$i][0] = $tmpUpdates[$i]->idUpdate;
            $tmpArray[$i][0] = $tmpUpdates[$i]->content;
            $tmpArray[$i][1] = $tmpUpdates[$i]->date;
            $tmpArray[$i][2] = $tmpUpdates[$i]->service;
        }
        return $tmpArray;

    }


}
