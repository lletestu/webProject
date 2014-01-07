<?php

require_once dirname(__FILE__) . '/../lightmvc/ActionController.php';
require_once dirname(__FILE__) . '/../model/Member.php';
require_once dirname(__FILE__) . '/../model/Members.php';
require_once dirname(__FILE__) . '/../model/Profile.php';
require_once dirname(__FILE__) . '/../model/Update.php';
require_once dirname(__FILE__) . '/../model/Config.php';
require_once dirname(__FILE__) . '/../model/Utilities.php';
require_once dirname(__FILE__) . '/../model/Database.php';

class MembersController extends ActionController
{
    public static $header = null;


    /**
     * Simple index page which links to the main available actions
     */
    public function indexAction()
    {
        // Do nothing
    }

    public function signupAction()
    {
        // use member : save
        if (!isset($_SESSION['isConnected'])) {
            $this->pseudoAlreadyUse = false;
            $this->nomAlreadyUse = false;
            $this->validateSignup = false;
            if (isset($_POST["email"])) {
                $this->pseudoAlreadyUse = Members::isRegistered($_POST["email"], null, null);
                if (!$this->pseudoAlreadyUse) {
                    $this->nomAlreadyUse = Members::isRegistered(null, $_POST['firstname'], $_POST['lastname']);
                    if (!$this->nomAlreadyUse) {
                        $newMember = new Member();
                        $newMember->setMember($_POST["firstname"], $_POST["lastname"], $_POST["email"], $_POST["password"], $_POST["sexe"], $_POST["isAdmin"], $_POST["description"], $_FILES["image"]["name"], $_POST["website"]);
                        $newMember->save();
                        $im = Utilities::uploadFile($newMember->getId($_POST["email"], null, null));
                        if ($im != null) {
                            //$newMember->idMember = $newMember->getId($_POST["email"], null, null);
                            $newMember->image = $im;
                            $newMember->save();
                        }
                        //$this->redirect('/profile/index');
                        $this->validateSignup = true;
                    }
                }
            }
        } else {
            $this->redirect('/profile/index');
        }


    }

    public function signinAction()
    {
        // use members : signin
        if (isset($_SESSION['email'])) {
            $this->redirect('/profile/index');
        } else {
            if (isset($_POST["email"]) && isset($_POST["password"])) {
                //if($_POST != null){
                $this->isRegistered = Members::signIn($_POST["email"], $_POST["password"]);
                if ($this->isRegistered) {
                    $this->currentMember = new Member($_POST["email"]);

                    $_SESSION['isConnected'] = true;
                    $_SESSION['email'] = $_POST["email"];
                    $_SESSION['idMember'] = $this->currentMember->idMember;
                    $_SESSION['isAdmin'] = $this->currentMember->isAdmin();
                    $_SESSION['firstname'] = $this->currentMember->firstname;
                    $_SESSION['lastname'] = $this->currentMember->lastname;

                    $this->redirect('/profile/index');
                } else {
                    $_SESSION['isConnected'] = false;
                }
            }
        }


    }

    public function listAction()
    {
        if (isset($_SESSION['isConnected']) && $_SESSION['isConnected'] === true) {
            if ((isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] === true)) {
                if (isset($_POST['nbMember'])) {
                    $this->headerArray = Members::getHeaderList();
                    $this->list = null;
                    if (isset($_POST['filterButton'])) {
                        $filter = $_POST['filterButton'];
                        switch ($filter) {
                            case "justPatient":
                                # here get just patient member
                                $this->list = Members::getPatient($_POST['nbMember']);
                                break;
                            case "withoutPatient":
                                # all patient without my patient
                                $this->list = Members::getOtherPatient($_POST['nbMember']);
                                break;
                            case "otherDoctor":
                                # other doctor than me
                                $this->list = Members::getOtherDoctor($_POST['nbMember']);
                                break;
                            case "anyFilter":
                                # all patient without filter
                                $this->list = Members::getAll($_POST['nbMember']);
                                break;
                        }
                    }

                } else
                    $this->list = null;
            } else {
                $this->redirect('/members/view');
            }
        } else {
            $this->redirect('/members/signin');
        }
    }

    public function deleteAction()
    {
        if ($_POST != null) {
            if ((isset($_SESSION['isConnected']) && $_SESSION['isConnected'] === true) && (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] === true)) {
                if (($_POST['firstname'] !== "") && ($_POST['lastname'] !== "")) {
                    $this->isRegister = Members::isRegistered(null, $_POST["firstname"], $_POST["lastname"]);
                    if ($this->isRegister) {
                        $this->isDoctor = Member::isDoctor($_POST["firstname"], $_POST["lastname"]);
                        if ($this->isDoctor !== null && !$this->isDoctor) {
                            $this->isDeleted = Members::delete($_POST["firstname"], $_POST["lastname"]);
                        } else
                            $this->hasRight = false;
                    }
                }
            } else {
                $this->redirect('/members/signin');
            }
        }

    }

    public function logoutAction()
    {
        if (isset($_SESSION['email'])) {
            unset($_SESSION['isConnected']);
            unset($_SESSION['email']);
            unset($_SESSION['idMember']);
            unset($_SESSION['isAdmin']);
            unset($_SESSION['firstname']);
            unset($_SESSION['lastname']);
            $this->redirect('/');
        }
    }

    public function addAction()
    {
        if (isset($_SESSION['isConnected']) && $_SESSION['isConnected'] === true) {
            if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] === true) {
                # all patient without my patient
                if (isset($_POST['firstname']) && $_POST['firstname']!= "" && isset($_POST['lastname']) && $_POST['lastname']!="") {
                    $this->isRegistered = Members::isRegistered(null, $_POST['firstname'], $_POST['lastname']);
                    if ($this->isRegistered) {
                        $this->isAlreadyAdd = Member::isAlreadyPatient($_POST['firstname'], $_POST['lastname']);
                        if (!$this->isAlreadyAdd) {
                            Member::addPatient($_POST['firstname'], $_POST['lastname']);
                            $this->headerMember = Members::getHeaderArray();
                            $this->headerUpdate = Update::getheaderUpdate();

                            $email = Member::getEmailMember($_POST['firstname'], $_POST['lastname']);

                            $this->profile = new Profile($email);
                            $this->memberArray = $this->profile->getArrayMember();
                            $this->updateArray = $this->profile->getarrayUpdate();
                        }
                    }

                }

            } else {
                $this->redirect('/profile/index');
            }
        } else {
            $this->redirect('/members/signin');
        }
    }

}
