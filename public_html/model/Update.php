<?php

class Update
{
// put here the names of fields
    public $idUpdate;
    public $content;
    public $date;
    public $service;
    public $idMember;

    public function __construct($idUpdate = null, $content = '', $date = '', $service = '', $idMember)
    {
        $this->idUpdate = $idUpdate;
        $this->content = $content;
        $this->date = $date;
        $this->service = $service;
        $this->idMember = $idMember;
    }

    /*
    Save the update into the database, if the id property is null, create a new Update
    If not, just update it
    */
    public function save()
    {
        // INSERT, UPDATE
        $bdd = Database::connexionDDB();
        if ($this->idUpdate == null) {
            $result = $bdd->prepare('insert into Updates(content, date, service, idMember) values(:content, :date, :service, :idMember)');
            $result->execute(array(
                ':content' => $this->content,
                ':date' => $this->date,
                ':service' => $this->service,
                ':idMember' => $this->idMember
            ));
        } else {
            $result = $bdd->prepare('update Updates(content, date, service, idMember) set (:content, :date, :service, :idMember) where idUpdate = :idUpdate');
            $result->execute(array(
                ':content' => $this->content,
                ':date' => $this->date,
                ':service' => $this->service,
                ':idMember' => $this->idMember,
                ':idUpdate' => $this->idUpdate
            ));
        }
    }

    public static function getheaderUpdate()
    {
        $header = array();
        $header[] = '#';
        //$header[] = 'ID Update';
        $header[] = 'Description';
        $header[] = 'Date';
        $header[] = 'Service';

        return $header;
    }

    public static function getLikes()
    {
        // API endpoint
        // supposed that the user name is the same as 'firstnamelastname' on vimeo
        //$vimeo_user_name = 'user23338129';
        $vimeo_user_name = $_SESSION['firstname'] . $_SESSION['lastname'];
        $api_endpoint = 'http://vimeo.com/api/v2/' . $vimeo_user_name;

        // Curl helper function
        function curl_get($url)
        {
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
            $return = curl_exec($curl);
            curl_close($curl);
            return $return;
        }

        //$userD = curl_get($api_endpoint . '/info.xml');
        //echo '<pre>'.'a: '.$userD.'</pre>';

        // Load the user info and clips
        $user = @simplexml_load_string(curl_get($api_endpoint . '/info.xml'));
        $videos = @simplexml_load_string(curl_get($api_endpoint . '/likes.xml'));

        $count = 0;
        if ($videos != null) {
            foreach ($videos->video as $video) {
                $count++;
            }
        }

        return array(
            'user' => $user,
            'videos' => $videos,
            'nbLikes' => $count
        );

    }

}