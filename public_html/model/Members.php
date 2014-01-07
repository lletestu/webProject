<?php

class Members
{
    /*
    * if $mode = true or no arg given, return the last three profiles created
    * if $mode = false, return three random profile
    * A profile is defined by an array with two keys : image, pseudo
    */
    public static function getFrontProfiles($mode = true)
    {
        $bdd = Database::connexionDDB();
        if ($mode) {
            $result = $bdd->prepare("select * from Member order by idMember desc LIMIT 3");
        } else {
            $result = $bdd->prepare("select * from Member order by rand() LIMIT 3");
        }

        $result->execute();
        $array = array();
        $i = 0;
        while ($data = $result->fetch(PDO::FETCH_OBJ)) {
            $array[$i]['idMember'] = $data->idMember;
            $array[$i]['firstname'] = $data->firstName;
            $array[$i]['lastname'] = $data->lastName;
            $array[$i]['email'] = $data->email;
            $array[$i]['sex'] = $data->sex;
            $array[$i]['isAdmin'] = $data->isAdmin;
            $array[$i]['description'] = $data->description;
            $array[$i]['image'] = $data->image;
            $array[$i]['website'] = $data->website;
            $i++;
        }
        $result->closeCursor();
        $bdd = null; // Close connexion bdd
        return $array;
    }

    /*
    * If username and password is in database, return TRUE. Otherwise, return false
    */
    public static function signIn($email, $password)
    {
        $bdd = Database::connexionDDB();
        $result = $bdd->query("select idMember from Member where email = '$email' and password = '$password'");
        $count = $result->rowCount();
        $result->closeCursor();
        $bdd = null; // Close connexion bdd
        if ($count > 0)
            return true;
        else
            return false;

    }

    /*
    Return an array of all members stored in database. If $number is different from 0,
    limit the size of the array
    */
    public static function getAll($number = 0)
    {
        $bdd = Database::connexionDDB();
        if ($number != 0) {
            $result = $bdd->prepare("select * from Member LIMIT $number");
        } else {
            $result = $bdd->prepare("select * from Member");
        }

        $result->execute();
        $array = array();
        $i = 0;
        while ($data = $result->fetch(PDO::FETCH_OBJ)) {
            //$array[$i]['idMember'] = $data->idMember;
            $array[$i]['firstname'] = $data->firstName;
            $array[$i]['lastname'] = $data->lastName;
            $array[$i]['email'] = $data->email;
            $array[$i]['sex'] = $data->sex;
            $array[$i]['isAdmin'] = $data->isAdmin;
            $array[$i]['description'] = $data->description;
            //$array[$i]['image'] = $data->image;
            $array[$i]['website'] = $data->website;
            $i++;
        }
        $result->closeCursor();
        $bdd = null; // Close connexion bdd
        return $array;
    }

    public static function getPatient($number = 0)
    {
        if ($_SESSION['isAdmin'] == true) {
            $bdd = Database::connexionDDB();
            if ($number != 0) {
                $result = $bdd->prepare("select * from Member where idMember in (select idPatient from DoctorPatient where idDoctor = :idDoctor) LIMIT $number");
            } else {
                $result = $bdd->prepare("select * from Member where idMember in (select idPatient from DoctorPatient where idDoctor = :idDoctor)");
            }

            $result->execute(array(
                'idDoctor' => $_SESSION['idMember']
            ));

            $array = array();
            $i = 0;
            while ($data = $result->fetch(PDO::FETCH_OBJ)) {
                //$array[$i]['idMember'] = $data->idMember;
                $array[$i]['firstname'] = $data->firstName;
                $array[$i]['lastname'] = $data->lastName;
                $array[$i]['email'] = $data->email;
                $array[$i]['sex'] = $data->sex;
                $array[$i]['isAdmin'] = $data->isAdmin;
                $array[$i]['description'] = $data->description;
                //$array[$i]['image'] = $data->image;
                $array[$i]['website'] = $data->website;
                $i++;
            }
            $result->closeCursor();
            $bdd = null; // Close connexion bdd
            return $array;
        }
    }

    public static function getOtherPatient($number)
    {
        if ($_SESSION['isAdmin'] == true) {
            $bdd = Database::connexionDDB();
            if ($number != 0) {
                $result = $bdd->prepare("select * from Member where isAdmin = 0 and idMember not in (select idPatient from DoctorPatient where idDoctor = :idDoctor) LIMIT $number");
            } else {
                $result = $bdd->prepare("select * from Member where isAdmin = 0 and idMember not in (select idPatient from DoctorPatient where idDoctor = :idDoctor)");
            }

            $result->execute(array(
                'idDoctor' => $_SESSION['idMember']
            ));

            $array = array();
            $i = 0;
            while ($data = $result->fetch(PDO::FETCH_OBJ)) {
                //$array[$i]['idMember'] = $data->idMember;
                $array[$i]['firstname'] = $data->firstName;
                $array[$i]['lastname'] = $data->lastName;
                $array[$i]['email'] = $data->email;
                $array[$i]['sex'] = $data->sex;
                $array[$i]['isAdmin'] = $data->isAdmin;
                $array[$i]['description'] = $data->description;
                //$array[$i]['image'] = $data->image;
                $array[$i]['website'] = $data->website;
                $i++;
            }
            $result->closeCursor();
            $bdd = null; // Close connexion bdd
            return $array;
        }
    }

    public static function getOtherDoctor($number)
    {
        if ($_SESSION['isAdmin'] == true) {
            $bdd = Database::connexionDDB();
            if ($number != 0) {
                $result = $bdd->prepare("select * from Member where isAdmin = 1 and idMember != :idDoctor LIMIT $number");
            } else {
                $result = $bdd->prepare("select * from Member where isAdmin = 1 and idMember != :idDoctor");
            }

            $result->execute(array(
                'idDoctor' => $_SESSION['idMember']
            ));

            $array = array();
            $i = 0;
            while ($data = $result->fetch(PDO::FETCH_OBJ)) {
                //$array[$i]['idMember'] = $data->idMember;
                $array[$i]['firstname'] = $data->firstName;
                $array[$i]['lastname'] = $data->lastName;
                $array[$i]['email'] = $data->email;
                $array[$i]['sex'] = $data->sex;
                $array[$i]['isAdmin'] = $data->isAdmin;
                $array[$i]['description'] = $data->description;
                //$array[$i]['image'] = $data->image;
                $array[$i]['website'] = $data->website;
                $i++;
            }
            $result->closeCursor();
            $bdd = null; // Close connexion bdd
            return $array;
        }
    }


    /*
    Delete the given member, if $idMember is not empty
    */
    public static function delete($firstname, $lastname)
    {
        if ($firstname != null) {
            $id = Member::getId(null, $firstname, $lastname);
            Members::deleteForeignKey($id);
            $bdd = Database::connexionDDB();
            $result = $bdd->prepare("delete from Member where firstname = :firstname and lastname = :lastname");
            $result->execute(array(
                ':firstname' => $firstname,
                ':lastname' => $lastname
            ));
            $count = $result->rowCount();
            $result->closeCursor();
            $bdd = null; // Close connexion bdd
            if ($count > 0)
                return true;
            else
                return false;

        }
        return false;
    }

    public static function deleteForeignKey($id){
        $bdd = Database::connexionDDB();
        $result = $bdd->prepare("delete from DoctorPatient where idPatient = :id");
        $result->execute(array(
            'id'=>$id
        ));
        $count1 = $result->rowCount();
        $result = $bdd->prepare("delete from Updates where idMember = :id");
        $result->execute(array(
            'id'=>$id
        ));
        $count2 = $result->rowCount();

        $result->closeCursor();
        $bdd = null; // Close connexion bdd
    }


    // Return true if the pseudo is already registered, otherwise return false
    public static function isRegistered($email = null, $firstname = null, $lastname = null)
    {
        $bdd = Database::connexionDDB();
        if ($email != null) {
            $result = $bdd->query("select idMember from Member where email = '$email'");
        } else if ($firstname != null && $lastname != null) {
            $result = $bdd->query("select idMember from Member where firstname = '$firstname' and lastname = '$lastname'");
        }
        $count = $result->rowCount();
        $result->closeCursor();
        $bdd = null; // Close connexion bdd
        if ($count > 0)
            return true;
        else
            return false;
    }

    public static function getHeaderArray()
    {
        $header = array();
        //$header[] = 'ID Member';
        $header[] = 'Firstname';
        $header[] = 'Lastname';
        $header[] = 'Email';
        $header[] = 'Sexe';
        $header[] = 'Category';
        $header[] = 'Description';
        $header[] = 'Image';
        $header[] = 'Website';

        return $header;
    }

    public static function getHeaderList(){
        $header = array();
        $header[] = 'Firstname';
        $header[] = 'Lastname';
        $header[] = 'Email';
        $header[] = 'Sexe';
        $header[] = 'Category';
        $header[] = 'Description';
        $header[] = 'Website';

        return $header;
    }

    public static function getRSS()
    {
        $bdd = Database::connexionDDB();
        $result = $bdd->prepare("select * from Member order by idMember desc LIMIT 10");

        $result->execute();
        $array = array();
        $i = 0;
        while ($data = $result->fetch(PDO::FETCH_OBJ)) {
            $array[$i]['idMember'] = $data->idMember;
            $array[$i]['firstname'] = $data->firstName;
            $array[$i]['lastname'] = $data->lastName;
            $array[$i]['email'] = $data->email;
            $array[$i]['sex'] = $data->sex;
            $array[$i]['isAdmin'] = $data->isAdmin;
            $array[$i]['description'] = $data->description;
            $array[$i]['image'] = $data->image;
            $array[$i]['website'] = $data->website;
            $i++;
        }
        $result->closeCursor();
        $bdd = null; // Close connexion bdd
        return $array;
    }

    public static function disableProfile()
    {
        if (isset($_SESSION['email'])) {
            Members::deleteForeignKey($_SESSION['idMember']);
            $bdd = Database::connexionDDB();
            $result = $bdd->prepare("delete from Member where idMember = :idMember");

            $result->execute(array(':idMember' => $_SESSION['idMember']));
            $count = $result->rowCount();

            unset($_SESSION['isConnected']);
            unset($_SESSION['email']);
            unset($_SESSION['idMember']);
            unset($_SESSION['isAdmin']);

            if ($count > 0)
                return true;
            else
                return false;
        }

        return false;
    }




}
