<?php
require_once('Config.php');
class Member
{
    // put properties here
    public $idMember;
    public $firstname = '';
    public $lastname = '';
    public $email = '';
    public $password = '';
    public $sex = '';
    public $isAdmin = '';
    public $description = '';
    public $image = '';
    public $website = '';

    /*
    If the pseudo is not null, get the data from database and fill the properties
    If the pseudo is null, do nothing
    */
    public function __construct($email = NULL)
    {
        if ($email != null) {
            $bdd = Database::connexionDDB();
            $result = $bdd->query("select * from Member where email = '$email'");
            $data = $result->fetch(PDO::FETCH_OBJ);
            $this->idMember = $data->idMember;
            $this->firstname = $data->firstName;
            $this->lastname = $data->lastName;
            $this->email = $data->email;
            $this->password = $data->password;
            $this->sex = $data->sex;
            $this->isAdmin = $data->isAdmin;
            $this->description = $data->description;
            $this->image = $data->image;
            $this->website = $data->website;

            $result->closeCursor();
            $bdd = null; // Close connexion bdd
        }

    }

    /*
    Save the member into the database. If the id property is null, create a new member
    If not, just update it
    */
    public function save()
    {
        $bdd = Database::connexionDDB();
        if ($this->idMember == null) {
            // INSERT
            $result = $bdd->prepare('insert into Member(firstName,lastName, email, password, sex, isAdmin, description, image, website) VALUES(:firstname, :lastname, :email, :password, :sex, :isAdmin, :description, :image, :website )');
            $result->execute(array(
                ':firstname' => $this->firstname,
                ':lastname' => $this->lastname,
                ':email' => $this->email,
                ':password' => $this->password,
                ':sex' => $this->sex,
                ':isAdmin' => $this->isAdmin,
                ':description' => $this->description,
                ':image' => $this->image,
                ':website' => $this->website
            ));

            $this->idMember = $this->getId($this->email, null, null);
        } else {
            // UPDATE
            $result = $bdd->prepare('UPDATE Member SET firstName = :firstname,lastName = :lastname, email = :email, password = :password, sex = :sex, isAdmin =  :isAdmin, description = :description, image = :image, website = :website where idMember = :idMember');
            $result->execute(array(
                ':idMember' => $this->idMember,
                ':firstname' => $this->firstname,
                'lastname' => $this->lastname,
                ':email' => $this->email,
                ':password' => $this->password,
                ':sex' => $this->sex,
                ':isAdmin' => $this->isAdmin,
                ':description' => $this->description,
                ':image' => $this->image,
                ':website' => $this->website
            ));
        }

        $result->closeCursor();
        $bdd = null; // Close connexion bdd

    }

    /* is the current member admin ? */
    public function isAdmin()
    {
        if ($this->isAdmin == true || $this->isAdmin == 1)
            return true;
        else
            return false;
    }

    public static function getEmailMember($firstname, $lastname)
    {
        $bdd = Database::connexionDDB();
        $result = $bdd->prepare("select email from Member where firstname = :firstname and lastname = :lastname");
        $result->execute(array(
            ':firstname' => $firstname,
            ':lastname' => $lastname
        ));

        if ($result->rowCount() == 0) {
            return null;
        } else {
            $data = $result->fetch(PDO::FETCH_OBJ);
            $email = $data->email;

            $result->closeCursor();
            $bdd = null; // Close connexion bdd

            return $email;
        }
    }

    public static function isDoctor($firstname, $lastname)
    {
        $bdd = Database::connexionDDB();
        $result = $bdd->prepare("select isAdmin from Member where firstname = :firstname and lastname = :lastname");
        $result->execute(array(
            'firstname' => $firstname,
            'lastname' => $lastname
        ));

        if ($result->rowCount() == 0) {
            return null;
        } else {
            $data = $result->fetch(PDO::FETCH_OBJ);
            $isAdmin = $data->isAdmin;

            $result->closeCursor();
            $bdd = null; // Close connexion bdd
            if ($isAdmin == 0)
                return false;
            else
                return true;

        }

    }

    public static function getId($email = null, $firstname = null, $lastname = null)
    {
        $bdd = Database::connexionDDB();
        if ($email != null) {
            $result = $bdd->prepare("select idMember from Member where email = :email");
            $result->execute(array(
                'email' => $email
            ));
        } elseif ($firstname != null && $lastname != null) {
            $result = $bdd->prepare("select idMember from Member where firstname = :firstname and lastname = :lastname");
            $result->execute(array(
                'firstname' => $firstname,
                'lastname' => $lastname
            ));
        }

        $data = $result->fetch(PDO::FETCH_OBJ);
        $result->closeCursor();
        $bdd = null; // Close connexion bdd
        return $data->idMember;
    }

    public function setMember($firstname, $lastname, $email = "", $password = "", $sex = "", $isAdmin = "", $description = "", $image = "", $website = "")
    {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->password = $password;
        $this->sex = $sex;
        $this->isAdmin = $isAdmin;
        $this->description = $description;
        $this->image = $image;
        $this->website = $website;
    }

    public static function isAlreadyPatient($firstname, $lastname)
    {
        if ((isset($_SESSION['isConnected']) && $_SESSION['isConnected'] === true) && (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] === true)) {
            $idDoctor = $_SESSION['idMember'];
            $bdd = Database::connexionDDB();
            $result = $bdd->prepare("select * from DoctorPatient where idDoctor = :idDoctor and idPatient in (select idMember from Member where firstname = :firstname and lastname = :lastname)");
            $result->execute(array(
                'idDoctor' => $idDoctor,
                'firstname' => $firstname,
                'lastname' => $lastname
            ));

            $count = $result->rowCount();
            $result->closeCursor();
            $bdd = null; // Close connexion bdd
            if ($count == 0) {
                return false;
            } else {
                return true;
            }
        }
    }

    public static function addPatient($firstname, $lastname)
    {
        if ((isset($_SESSION['isConnected']) && $_SESSION['isConnected'] === true) && (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] === true)) {
            $idDoctor = $_SESSION['idMember'];
            $idPatient = Member::getId(null, $firstname, $lastname);

            $bdd = Database::connexionDDB();
            $result = $bdd->prepare("insert into DoctorPatient(idPatient, idDoctor) values(:idPatient, :idDoctor)");
            $result->execute(array(
                'idPatient' => $idPatient,
                'idDoctor' => $idDoctor
            ));

            $result->closeCursor();
            $bdd = null;
        }

    }


}
