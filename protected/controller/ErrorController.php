<?php

/**
 * ErrorController
 * Feel free to change this and customize your own error message
 *
 * @author darkredz
 */
class ErrorController extends DooController
{

    public function index()
    {
        session_start();
        //get the URL and match it against a user
        $url = Doo::conf()->APP_URL;
        $page = $this->params['page'];
        $url = preg_replace("(^https?://)", "", $url); //remove protocol from domain
        $url = str_replace('/app/', '', $url);
        $url = rtrim($url, "/");

        //get website settings and user
        Doo::loadModel('ScWebsites');
        $webobj = new ScWebsites;
        $wdata = $webobj->getWebsiteData($url, 'domain');

        if (!$wdata) {
            //no user matched
            return array('/error/disabled', 'internal');
        } else {

            $cdata = unserialize($wdata->site_data);
            if (empty($_SESSION['webfront'])) {
                unset($_SESSION['webfront']);
                $_SESSION['webfront']['id'] = $wdata->id;
                $_SESSION['webfront']['owner'] = $wdata->user_id;
                $_SESSION['webfront']['company_name'] = $cdata['company_name'];
                $_SESSION['webfront']['current_domain'] = $url;
                $_SESSION['webfront']['all_domains'] = $wdata->domains;
                $_SESSION['webfront']['logo'] = $wdata->logo;
                $_SESSION['webfront']['company_data'] = $wdata->site_data;
                $_SESSION['webfront']['front_type'] = $wdata->front_type;
                $_SESSION['webfront']['skin_data'] = $wdata->skin_data;
                $_SESSION['webfront']['status'] = $wdata->status;
                $_SESSION['webfront']['intheme'] = $cdata['theme'];
            }

            $lgobj = Doo::loadModel('ScUsersSettings', true);
            $lgobj->user_id = $wdata->user_id;
            $app_lang = Doo::db()->find($lgobj, array('limit' => 1, 'select' => 'def_lang'));
            $_SESSION['APP_LANG'] = $app_lang->def_lang == false ? Doo::conf()->default_lang : $app_lang->def_lang;

            Doo::loadModel('ScWebsitesPageData');
            $pgobj = new ScWebsitesPageData;
            $pgobj->site_id = $_SESSION['webfront']['id'];
            $pgobj->user_id = $_SESSION['webfront']['owner'];
            $pgobj->page_type = 'LOGIN';
            $data['pdata'] = Doo::db()->find($pgobj, array('limit' => 1, 'select' => 'page_data'));

            $data['baseurl'] = Doo::conf()->APP_URL;
            $this->view()->renderc('outer/404', $data);
        }
    }

    public function denied()
    {
        session_start();
        //get the URL and match it against a user
        $url = Doo::conf()->APP_URL;
        $page = $this->params['page'];
        $url = preg_replace("(^https?://)", "", $url); //remove protocol from domain
        $url = str_replace('/app/', '', $url);
        $url = rtrim($url, "/");

        //get website settings and user
        Doo::loadModel('ScWebsites');
        $webobj = new ScWebsites;
        $wdata = $webobj->getWebsiteData($url, 'domain');

        if (!$wdata) {
            //no user matched
            return array('/error/disabled', 'internal');
        } else {

            $cdata = unserialize($wdata->site_data);
            if (empty($_SESSION['webfront'])) {
                unset($_SESSION['webfront']);
                $_SESSION['webfront']['id'] = $wdata->id;
                $_SESSION['webfront']['owner'] = $wdata->user_id;
                $_SESSION['webfront']['company_name'] = $cdata['company_name'];
                $_SESSION['webfront']['current_domain'] = $url;
                $_SESSION['webfront']['all_domains'] = $wdata->domains;
                $_SESSION['webfront']['logo'] = $wdata->logo;
                $_SESSION['webfront']['company_data'] = $wdata->site_data;
                $_SESSION['webfront']['front_type'] = $wdata->front_type;
                $_SESSION['webfront']['skin_data'] = $wdata->skin_data;
                $_SESSION['webfront']['status'] = $wdata->status;
                $_SESSION['webfront']['intheme'] = $cdata['theme'];
            }

            $lgobj = Doo::loadModel('ScUsersSettings', true);
            $lgobj->user_id = $wdata->user_id;
            $app_lang = Doo::db()->find($lgobj, array('limit' => 1, 'select' => 'def_lang'));
            $_SESSION['APP_LANG'] = $app_lang->def_lang == false ? Doo::conf()->default_lang : $app_lang->def_lang;

            Doo::loadModel('ScWebsitesPageData');
            $pgobj = new ScWebsitesPageData;
            $pgobj->site_id = $_SESSION['webfront']['id'];
            $pgobj->user_id = $_SESSION['webfront']['owner'];
            $pgobj->page_type = 'LOGIN';
            $data['pdata'] = Doo::db()->find($pgobj, array('limit' => 1, 'select' => 'page_data'));

            $data['baseurl'] = Doo::conf()->APP_URL;
            $this->view()->renderc('outer/denied', $data);
        }
    }

    public function blocked()
    {
        session_start();
        //get the URL and match it against a user
        $url = Doo::conf()->APP_URL;
        $page = $this->params['page'];
        $url = preg_replace("(^https?://)", "", $url); //remove protocol from domain
        $url = str_replace('/app/', '', $url);
        $url = rtrim($url, "/");

        //get website settings and user
        Doo::loadModel('ScWebsites');
        $webobj = new ScWebsites;
        $wdata = $webobj->getWebsiteData($url, 'domain');

        if (!$wdata) {
            //no user matched
            return array('/error/disabled', 'internal');
        } else {

            $cdata = unserialize($wdata->site_data);
            if (empty($_SESSION['webfront'])) {
                unset($_SESSION['webfront']);
                $_SESSION['webfront']['id'] = $wdata->id;
                $_SESSION['webfront']['owner'] = $wdata->user_id;
                $_SESSION['webfront']['company_name'] = $cdata['company_name'];
                $_SESSION['webfront']['current_domain'] = $url;
                $_SESSION['webfront']['all_domains'] = $wdata->domains;
                $_SESSION['webfront']['logo'] = $wdata->logo;
                $_SESSION['webfront']['company_data'] = $wdata->site_data;
                $_SESSION['webfront']['front_type'] = $wdata->front_type;
                $_SESSION['webfront']['skin_data'] = $wdata->skin_data;
                $_SESSION['webfront']['status'] = $wdata->status;
                $_SESSION['webfront']['intheme'] = $cdata['theme'];
            }

            $lgobj = Doo::loadModel('ScUsersSettings', true);
            $lgobj->user_id = $wdata->user_id;
            $app_lang = Doo::db()->find($lgobj, array('limit' => 1, 'select' => 'def_lang'));
            $_SESSION['APP_LANG'] = $app_lang->def_lang == false ? Doo::conf()->default_lang : $app_lang->def_lang;

            Doo::loadModel('ScWebsitesPageData');
            $pgobj = new ScWebsitesPageData;
            $pgobj->site_id = $_SESSION['webfront']['id'];
            $pgobj->user_id = $_SESSION['webfront']['owner'];
            $pgobj->page_type = 'LOGIN';
            $data['pdata'] = Doo::db()->find($pgobj, array('limit' => 1, 'select' => 'page_data'));

            $data['baseurl'] = Doo::conf()->APP_URL;
            $this->view()->renderc('outer/blocked', $data);
        }
    }
}
