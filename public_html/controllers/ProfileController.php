<?php

require_once dirname(__FILE__) . '/../lightmvc/ActionController.php';
require_once dirname(__FILE__) . '/../model/Member.php';
require_once dirname(__FILE__) . '/../model/Members.php';
require_once dirname(__FILE__) . '/../model/Config.php';
require_once dirname(__FILE__) . '/../model/Database.php';
require_once dirname(__FILE__) . '/../model/Profile.php';
require_once dirname(__FILE__) . '/../model/Update.php';
require_once dirname(__FILE__) . '/../model/Utilities.php';

// Revoir updates est un tableau de update attention à revoir
// REVOIR GESTION DE PROFILE !!


class ProfileController extends ActionController
{
    /**
     * Simple index page which links to the main available actions
     */
    public function indexAction()
    {
        // Do nothing
        if (isset($_SESSION['email'])) {
            $this->headerMember = Members::getHeaderArray();
            $this->headerUpdate = Update::getheaderUpdate();

            $this->profile = new Profile($_SESSION['email']);
            $this->memberArray = $this->profile->getArrayMember();
            $this->updateArray = $this->profile->getarrayUpdate();


        } else {
            $this->redirect('/members/signin');
        }
    }


    /**
     *  Edit the profile of the logged member
     */
    public function editAction()
    {
        // member save
        // update save

        if (isset($_SESSION['isConnected']) && $_SESSION['isConnected'] == true) {
            $this->oldMail = $_SESSION['email'];
            $this->validateEdit = false;
            $this->profile = new Profile($_SESSION['email']);
            $this->memberArray = $this->profile->getArrayMember();
            if (isset($_POST["description"]) && isset($_POST["website"]) && isset($_POST['email'])) {
                $this->pseudoAlreadyUse = Members::isRegistered($_POST["email"], null, null);
                if (!$this->pseudoAlreadyUse || $_POST["email"] == $this->oldMail) {
                    $this->pseudoAlreadyUse = false;
                    $currentMember = new Member($_SESSION['email']);
                    $currentMember->email = $_POST['email'];
                    $currentMember->description = $_POST["description"];
                    $currentMember->website = $_POST["website"];
                    $currentMember->save();
                    $im = Utilities::uploadFile($currentMember->getId($_POST["email"], null, null));
                    if ($im != null) {
                        //$newMember->idMember = $newMember->getId($_POST["email"], null, null);
                        $currentMember->image = $im;
                        $currentMember->save();
                    }

                    $_SESSION['email'] = $_POST['email'];
                    $this->profile = new Profile($_SESSION['email']);
                    //$this->redirect('/profile/index');
                    $this->validateEdit = true;
                }
            }

        } else {
            $this->redirect('/members/signin');
        }
    }


    /**
     * Add a update
     */

    public function updateAction()
    {
        if (isset($_SESSION['isConnected']) && $_SESSION['isConnected'] == true) {
            if (isset($_POST['content']) && isset($_POST['listPatient'])) {
                $this->profile = new Profile($_POST['listPatient']);
                $this->updateArray = $this->profile->getarrayUpdate();
                $this->profile->addUpdates($_POST['content'], $_POST['date'], $_POST['service']);

                $this->headerUpdate = Update::getheaderUpdate();
                $this->updateArray = $this->profile->getarrayUpdate();

            }
        } else {
            $this->redirect('/members/signin');
        }
    }

    public function vimeoLikesAction(){
        $this->arrayVimeo = Update::getLikes();
    }

    public function addAction()
    {
        $this->list = Members::getPatient(0);
    }

    /**
     * show the profile
     */
    public
    function viewAction()
    {
        // use the Profil constructor
        $this->profile = null;
        $this->memberIsRegistered = null;
        if (isset($_SESSION['isConnected']) && $_SESSION['isConnected'] == true) {
            $this->headerArray = Members::getHeaderArray();

            if (isset($_POST['firstname']) && isset($_POST['lastname'])) {
                $email = Member::getEmailMember($_POST['firstname'], $_POST['lastname']);
                if ($email != null) {
                    $this->headerMember = Members::getHeaderArray();
                    $this->headerUpdate = Update::getheaderUpdate();
                    $this->memberIsRegistered = true;

                    $this->profile = new Profile($email);
                    $this->memberArray = $this->profile->getArrayMember();
                    $this->updateArray = $this->profile->getarrayUpdate();

                } else {
                    $this->memberIsRegistered = false;
                }
            }

        } else {
            $this->redirect('/members/signin');
        }

    }

    public function deleteAction()
    {
        if (isset($_SESSION['isConnected']) && $_SESSION['isConnected'] == true) {
            $this->isDeleted = Members::disableProfile();
        } else {
            $this->redirect('/members/signin');
        }
    }


}
