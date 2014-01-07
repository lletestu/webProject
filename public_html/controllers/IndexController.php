<?php

require_once dirname(__FILE__) . '/../lightmvc/ActionController.php';
require_once dirname(__FILE__) . '/../model/Member.php';
require_once dirname(__FILE__) . '/../model/Members.php';
require_once dirname(__FILE__) . '/../model/Profile.php';
require_once dirname(__FILE__) . '/../model/Config.php';
require_once dirname(__FILE__) . '/../model/Database.php';


/**
 * @property mixed rssPseudo
 */
class IndexController extends ActionController
{
    private static $rssPseudo = "aaa";

    /**
     * Simple index page which links to the main available actions
     */
    public function indexAction()
    {
        $this->titleHeader = Members::getHeaderArray();
        $this->profileList2 = Members::getFrontProfiles(true);

    }

    public function rssAction()
    {
        $this->_includeTemplate = false; //to hide footer and header
        //$name = $_POST['pseudoMember'];
        $result = Members::getRSS();
        $currentDate = date('d/m/y');


        $rss = '<?xml version="1.0" encoding="UTF-8" ?>
            <rss version="2.0">
                <channel>
                    <title>RSS Members</title>
                    <description>This is a RSS feed of the member</description>
                    <pubDate>' . $currentDate . '</pubDate>';

        //$rss .= '<item>';
        //$rss .= '<title>'.$name.'</title>';
        //$rss .= '</item>';

       foreach ($result as $member) {
            $rss .= '<item>';
            $rss .= '<title>' . $member['firstname'] . '</title>';
            $rss .= '<description>' . $member['description'] . '</description>';
            $rss .= '<email>' . $member['email'] . '</email>';
            $rss .= '<sex>' . $member['sex'] . '</sex>';
            $rss .= '<isAdmin>' . $member['isAdmin'] . '</isAdmin>';
            $rss .= '<image>' . $member['image'] . '</image>';
            $rss .= '<website>' . $member['website'] . '</website>';
            $rss .= '</item>';

        }
        $rss .= '</channel>';
        $rss .= '</rss>';
        $this->rss = $rss;

    }

    public function idRSSAction()
    {
        if($_POST != null){
            $this->rssPseudo = $_POST['pseudoMember'];
            $this->redirect('/index/rss');

        }
    }

    public function privacyAction(){
        // Do nothing
    }

}


