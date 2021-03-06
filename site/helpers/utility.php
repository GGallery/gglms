<?php
/**
 * @package        Joomla.Tutorials
 * @subpackage    Components
 * @copyright    Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license        License GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die;

require_once JPATH_COMPONENT . '/libraries/xls/src/Spout/Autoloader/autoload.php';
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;

class utilityHelper
{

    public static $white_cf = '----------------';

    public static function getGGlmsParam()
    {
        try {

            $db = JFactory::getDbo();
            $query = $db->getQuery(true);

            $query->select('*');
            $query->from('#__gg_configs AS u');
            $db->setQuery($query);

            // Check for a database error.
            if ($db->getErrorNum()) {
                JError::raiseWarning(500, $db->getErrorMsg());
            }

            $res = $db->loadObjectList();

            foreach ($res as $key => $item) {

            }

            return $res;
        } catch (Exception $e) {

        }
    }

    public static function conformita_cf($cf)
    {

        // forzatura per consentire la gestione del codice fiscale per stranieri
        if ($cf == self::$white_cf) {
            $res['valido'] = 1;
            $res['msg'] = '';
            $res['cf'] = $cf;

            return $res;
        }

        $cf = strtoupper($cf);

        if ($cf === '') {
            $res['valido'] = 0;
            $res['msg'] = 'non è compilato';
            $res['cf'] = $cf;
            return $res;
        };
        if (strlen($cf) != 16) {
            $res['valido'] = 0;
            $res['msg'] = "ha una lunghezza non \n"
                . "corretta: il codice fiscale dovrebbe essere lungo\n"
                . "esattamente 16 caratteri";
            $res['cf'] = $cf;
            return $res;
        }


        if (preg_match("/^[A-Z0-9]+\$/", $cf) != 1) {

            $res['valido'] = 0;
            $res['msg'] = "contiene dei caratteri non validi:\n"
                . "i soli caratteri validi sono le lettere e le cifre";
            $res['cf'] = $cf;
            return $res;

        }
        $s = 0;
        for ($i = 1; $i <= 13; $i += 2) {
            $c = $cf[$i];
            if (strcmp($c, "0") >= 0 and strcmp($c, "9") <= 0)
                $s += ord($c) - ord('0');
            else
                $s += ord($c) - ord('A');
        }
        for ($i = 0; $i <= 14; $i += 2) {
            $c = $cf[$i];
            switch ($c) {
                case '0':
                    $s += 1;
                    break;
                case '1':
                    $s += 0;
                    break;
                case '2':
                    $s += 5;
                    break;
                case '3':
                    $s += 7;
                    break;
                case '4':
                    $s += 9;
                    break;
                case '5':
                    $s += 13;
                    break;
                case '6':
                    $s += 15;
                    break;
                case '7':
                    $s += 17;
                    break;
                case '8':
                    $s += 19;
                    break;
                case '9':
                    $s += 21;
                    break;
                case 'A':
                    $s += 1;
                    break;
                case 'B':
                    $s += 0;
                    break;
                case 'C':
                    $s += 5;
                    break;
                case 'D':
                    $s += 7;
                    break;
                case 'E':
                    $s += 9;
                    break;
                case 'F':
                    $s += 13;
                    break;
                case 'G':
                    $s += 15;
                    break;
                case 'H':
                    $s += 17;
                    break;
                case 'I':
                    $s += 19;
                    break;
                case 'J':
                    $s += 21;
                    break;
                case 'K':
                    $s += 2;
                    break;
                case 'L':
                    $s += 4;
                    break;
                case 'M':
                    $s += 18;
                    break;
                case 'N':
                    $s += 20;
                    break;
                case 'O':
                    $s += 11;
                    break;
                case 'P':
                    $s += 3;
                    break;
                case 'Q':
                    $s += 6;
                    break;
                case 'R':
                    $s += 8;
                    break;
                case 'S':
                    $s += 12;
                    break;
                case 'T':
                    $s += 14;
                    break;
                case 'U':
                    $s += 16;
                    break;
                case 'V':
                    $s += 10;
                    break;
                case 'W':
                    $s += 22;
                    break;
                case 'X':
                    $s += 25;
                    break;
                case 'Y':
                    $s += 24;
                    break;
                case 'Z':
                    $s += 23;
                    break;
                /*. missing_default: .*/
            }
        }
        if (chr($s % 26 + ord('A')) != $cf[15]) {
            $res['valido'] = 0;
            $res['msg'] = "non &egrave; corretto:\n"
                . "il codice di controllo non corrisponde";
            $res['cf'] = $cf;
            return $res;
        }

        $res['valido'] = 1;
        $res['msg'] = '';
        $res['cf'] = $cf;
        return $res;
    }
//Moni
    public static function setComponentParam($key, $value)
    {

        $params = JComponentHelper::getParams('com_gglms');
        $params->set($key, $value);

        $componentid = JComponentHelper::getComponent('com_gglms')->id;
        $table = JTable::getInstance('extension');
        $table->load($componentid);
        $table->bind(array('params' => $params->toString()));

        if (!$table->check()) {
            DEBUGG::log('Errore salvataggio parametri', '', 1);
            return false;
        }
        if (!$table->store()) {
            DEBUGG::log('Errore salvataggio parametri', '', 1);
            return false;
        }
    }

    public static function DISATTIVATOconvertiDurata($durata)
    {
        $m = floor(($durata % 3600) / 60);
        $s = ($durata % 3600) % 60;
        $result = sprintf('%02d:%02d', $m, $s);

        return $result;
    }

    public static function files_list_from_folder($path_folder) {

        $_ret = array();
        if (!file_exists($path_folder)) {
            return $_ret;
        }

        return scandir($path_folder);

    }

    /////////////////////////////////////

    // metodi per dropdown report, monitora coupon, generacoupon

    public static function getGruppiCorsi($id_piattaforma = null, $mobile = false)
    {

        // carico i gruppi dei corsi, filtrati per piattaforma
        try {
            $_config = new gglmsModelConfig();
            $id_gruppo_accesso_corsi = $_config->getConfigValue('id_gruppo_corsi');

            $db = JFactory::getDbo();
            $query = $db->getQuery(true)
                ->select('DISTINCT  g.id as value, u.titolo as text')
                ->from('#__usergroups as g')
                ->join('inner', '#__gg_usergroup_map AS gm ON g.id = gm.idgruppo')
                ->join('inner', '#__gg_unit AS u ON u.id = gm.idunita')
                ->join('inner', '#__gg_piattaforma_corso_map  AS pcm ON pcm.id_unita = u.id')
                ->join('inner', '#__usergroups_details  AS ud ON ud.group_id = pcm.id_gruppo_piattaforma')
                ->where(" g.parent_id=" . $id_gruppo_accesso_corsi)
                ->where(" u.pubblicato=1")
                ->order('u.titolo');


            if($mobile){
                $query = $query->where("u.mobile = 1");
            }


            //if ($id_piattaforma != null) {
            if (!is_null($id_piattaforma)
                && trim($id_piattaforma) != "") {

                // specifica piattaforma, serve nel form genera coupon qunado un super admin vede due piattaforme
                $query = $query->where("ud.group_id=" . $id_piattaforma);

            } else {
                // piattaforma corrente
//                $query = $query->where("ud.dominio='" . DOMINIO . "'");

                // come in models\report getCorsi()

                $user = JFactory::getUser();
                $userid = $user->get('id');
                // controllo se esiste un utente collegato, potrebbe non esserci im caso di chiamata genera coupon da ecommerce esterni!!
                if($userid != null)
                {
                    //  con il barbatrucco dei coupon la piattaforma corrente  non è più quella del dominio MA quella dell'utente collegato
                    $model_user = new gglmsModelUsers();
                    $id_piattaforma = $model_user->get_user_piattaforme($userid);
                    $id_piattaforma_array = array();


                    foreach ($id_piattaforma as $p) {
                        array_push($id_piattaforma_array, $p->value);
                    }

                    $query->where($db->quoteName('ud.group_id') . ' IN (' . implode(", ", $id_piattaforma_array) . ')');
                }


            }



            $db->setQuery($query);
            $corsi = $db->loadObjectList();

        } catch (Exception $e) {
            DEBUGG::error($e, 'getGruppiCorsi');

        }


        return $corsi;
    }

    // filtro azienda se tutor aziendale
    public static function getIdCorsi($id_piattaforma = null, $filtro_azienda = null)
    {

        // carico i gruppi dei corsi, filtrati per piattaforma
        try {
            $_config = new gglmsModelConfig();
            $id_gruppo_accesso_corsi = $_config->getConfigValue('id_gruppo_corsi');

            $db = JFactory::getDbo();
            $query = $db->getQuery(true)
                ->select('distinct u.id as value, u.titolo as text')
                ->from('#__usergroups as g')
                ->join('inner', '#__gg_usergroup_map AS gm ON g.id = gm.idgruppo')
                ->join('inner', '#__gg_unit AS u ON u.id = gm.idunita')
                ->join('inner', '#__gg_piattaforma_corso_map  AS pcm ON pcm.id_unita = u.id')
                ->join('inner', '#__usergroups_details  AS ud ON ud.group_id = pcm.id_gruppo_piattaforma');


            // gestione filtro per azienda
            if (!is_null($filtro_azienda)) {

                // al volo includo il model users
                require_once JPATH_COMPONENT . '/models/users.php';

                // utente corrente
                $user = JFactory::getUser();
                $user_id = $user->get('id');

                // verifico se è tutor aziendale
                $model_user = new gglmsModelUsers();
                $tutor_az = $model_user->is_tutor_aziendale($user_id);

                // utente è tutor aziendale
                if ($tutor_az) {

                    /*
                    $id_piattaforma = $model_user->get_user_piattaforme($user_id);

                    $id_piattaforma_array = array();
                    foreach ($id_piattaforma as $p) {
                        array_push($id_piattaforma_array, $p->value);
                    }


                    $sub_query->select("DISTINCT id_gruppi")
                        ->from('#__gg_coupon')
                        ->where('gruppo IN (' . implode(", ", $id_piattaforma_array) . ')');
                    */

                    $lista_aziende = $model_user->get_user_societa($user_id, true);

                    // applico filtro soltanto se ci sono società associate al tutor aziendale
                    if (count($lista_aziende) > 0) {
                        $sub_query = $db->getQuery(true);
                        $sub_query->select("DISTINCT id_gruppi")
                            ->from('#__gg_coupon')
                            ->where("id_societa in (" . implode(', ', self::get_id_aziende($lista_aziende)) . ")");

                        $query->join('inner', '(' . $sub_query . ') AS sub1 ON g.id = sub1.id_gruppi');
                    }
                }
            }

            $query
                ->where(" g.parent_id=" . $id_gruppo_accesso_corsi)
                ->where(" u.pubblicato=1")
                ->order('u.titolo');
            if ($id_piattaforma != null) {

                // specifica piattaforma, serve nel form genera coupon qunado un super admin vede due piattaforme
                $query = $query->where("ud.group_id=" . $id_piattaforma);

            } else {

                // per l'ambiente di sviluppo..altrimenti la query non produce risultati per i corsi
                $_domain = self::filtra_dominio_per_test(DOMINIO);

                // piattaforma corrente
                //$query = $query->where("ud.dominio='" . DOMINIO . "'");
                $query = $query->where("ud.dominio='" . $_domain . "'");
            }

            $db->setQuery($query);
            $corsi = $db->loadObjectList();

        } catch (Exception $e) {
            DEBUGG::error($e, 'getGruppiCorsi');
        }


        return $corsi;
    }

    public static function getTitoloCorsoPadre($id_padre, $arr_corsi) {

        try {

            foreach ($arr_corsi as $key => $corso) {
                if ($corso->id == $id_padre)
                    return $corso->titolo;
            }

            throw new Exception("Nessun titolo per id_padre " . $id_padre);

        }
        catch (Exception $e) {
            DEBUGG::error($e, __FUNCTION__);
        }
    }

    public static function getDettaglioDurataByCorso($id_corso, $user_id = null, $arr_date_descrizione = array()) {

        try {

            $db = JFactory::getDbo();

            $con_orari = false;
            if (count($arr_date_descrizione) > 0)
                $con_orari = true;

            $query = $db->getQuery(true);
            $sub_query1 = $db->getQuery(true);

            $query->select('CN.titolo AS titolo_evento, CN.durata AS durata_evento, 
                                SUM(LG.permanenza) AS tempo_visualizzato');

            if ($con_orari)
                $query->select('CN.durata AS totale_durata, CN.id AS id_contenuto, DATE_FORMAT(LG.data_accesso, \'%Y-%m-%d\') AS data_accesso');

            $query->from('#__comprofiler CP');
            $query->join('inner', '#__gg_log LG ON CP.user_id = LG.id_utente');
            $query->join('inner', '#__gg_contenuti CN ON LG.id_contenuto = CN.id');

            $sub_query1->select('MAP.idcontenuto');
            $sub_query1->from('#__gg_unit_map MAP');
            $sub_query1->join('inner', '#__gg_unit U ON MAP.idunita = U.id');

            $sub_query1->where(' (MAP.idunita = ' . $id_corso . ' OR U.unitapadre = ' . $id_corso . ')');
            $sub_query1->where('U.pubblicato = 1');
            $query->join('inner', '(' . $sub_query1 . ') AS SUB1 ON CN.id = SUB1.idcontenuto');

            if (!is_null($user_id))
                $query->where('LG.id_utente = ' . $user_id);

            $query->where('CN.pubblicato = 1');

            $query->group($db->quoteName('LG.id_utente'));
            $query->group($db->quoteName('LG.id_contenuto'));

            if ($con_orari)
                $query->group('DATE_FORMAT(LG.data_accesso, \'%Y-%m-%d\')');

            $db->setQuery($query);
            $rows = $db->loadAssocList();

            if ($con_orari) {

                $_tmp_arr = array();
                foreach ($rows as $rr => $row) {
                    if (isset($arr_date_descrizione[$row['id_contenuto']][$row['data_accesso']])) {
                        $durata_evento = $arr_date_descrizione[$row['id_contenuto']][$row['data_accesso']];
                        $row['durata_evento'] = $durata_evento;
                        $tempo_assenza = ($durata_evento-$row['tempo_visualizzato']);
                        $tempo_assenza = ($tempo_assenza < 0) ? 0 : $tempo_assenza;
                        $row['tempo_assenza'] = $tempo_assenza;
                        $row['tempo_visualizzato'] = ($row['tempo_visualizzato'] > $durata_evento) ? $durata_evento : $row['tempo_visualizzato'];
                        $row['data_accesso'] = date("d/m/Y", strtotime($row['data_accesso']));
                        $_tmp_arr[] = $row;
                    }
                }

                if (count($_tmp_arr) > 0)
                    $rows = $_tmp_arr;

            }

            return $rows;

        }
        catch (Exception $e) {
            DEBUGG::error($e, __FUNCTION__);
        }
    }

    public static function getDettaglioDurataByCorsi($arr_corsi = array(), $arr_date_descrizione = array()) {

        try {

            if (!is_array($arr_corsi)
                || count($arr_corsi) == 0)
                return null;

            $Juser = JFactory::getUser();
            $arr_dettagli = array();

            foreach ($arr_corsi as $key => $corso) {

                $dettagli = self::getDettaglioDurataByCorso($corso->id, $Juser->id, $arr_date_descrizione);
                if (!is_array($dettagli)
                    || count($dettagli) == 0)
                    continue;

                $arr_dettagli[$corso->id] = $dettagli;
            }

            return $arr_dettagli;

        }
        catch (Exception $e) {
            DEBUGG::error($e, __FUNCTION__);
        }

    }

    public static function getSocietaByUser()
    {

        try {

            $Juser = JFactory::getUser();

            $user = new gglmsModelUsers();
            $user->get_user($Juser->id);

            $usergroups = null;


            if ($user->is_tutor_piattaforma($Juser->id)
                || $user->is_user_superadmin($Juser->id)) {

                //  utente loggato  ha ruolo TUTOR PIATTAFORMA, prendo le società figlie di piattaforma
                // lo stesso se è super admin
                $usergroups = $user->get_user_societa($Juser->id, false);


            } else if ($user->is_tutor_aziendale($Juser->id)) {

                // utente loggato ha ruolo di TUTOR AZIENDALE , prendo la sua società
                $usergroups = $user->get_user_societa($Juser->id, true);

            }

            return $usergroups;

        } catch (Exceptions $e) {
//
            DEBUGG::error($e, 'errore get userGroup ', 1);
        }


        // vecchia logica
//        try {
//            $query = $this->_db->getQuery(true);
//            $query->select('config_value');
//            $query->from('#__gg_configs');
//            $query->where('config_key=\'id_gruppi_visibili\'');
//
//            $this->_db->setQuery($query);
//            $usergroupsfromparams = $this->_db->loadResult();
//
//            $usergroupsfrompermessi = $this->get_report_view_permessi_gruppi();
//
//            $query = $this->_db->getQuery(true);
//            $query->select('id, title');
//            $query->from('#__usergroups AS u');
//            if ($usergroupsfrompermessi != null) {
//                $query->where('u.id in (' . $usergroupsfrompermessi . ') ');
//            } else {
//                $query->where('u.id in (' . $usergroupsfromparams . ') ');
//            }
//
//            $this->_db->setQuery($query);
//
//            $usergroups = $this->_db->loadObjectList();
//
//            return $usergroups;
//        } catch (exceptions $e) {
//
//            DEBUGG::error($e, 'errore get Contenuti unita', 1);
//        }
    }

// se $only_vendor è TRUE --> ritorno la piattaforma SOLO se utente è venditore per quella piattaforma
//se $only_vendor è TRUE --> ritorno la piattaforma
    public static function getPiattaformeByUser($only_vendor)
    {
        try {
            $user = new gglmsModelUsers();
            $Juser = JFactory::getUser();
            $user->get_user($Juser->id);
            $_japp = JFactory::getApplication();
            $societa_venditrici = [];

            if ($user->is_venditore($Juser->id)) {
                $societa_venditrici = $user->get_user_piattaforme($Juser->id);


            }


            return $societa_venditrici;
        } catch (Exception $e) {

            DEBUGG::error($e, 'getVenditrici');
        }
    }

    // informazioni per un campo community builder da id
    public static function get_cb_field($field_id) {

        try {

            $db = JFactory::getDbo();
            $query = $db->getQuery(true)
                ->select('*')
                ->from('#__comprofiler_fields')
                ->where("fieldid = '" . trim($field_id) . "'");

            $db->setQuery($query);

            if (false === ($results = $db->loadAssoc())) {
                throw new RuntimeException($db->getErrorMsg(), E_USER_ERROR);
            }

            return $results;
        }
        catch (Exception $e) {
            DEBUGG::error($e, __FUNCTION__);
        }

    }

    // lista di valori per un determino fieldid di un campo community builder
    public static function get_cb_field_values_list($field_id) {

        try {

            $db = JFactory::getDbo();
            $query = $db->getQuery(true)
                ->select('*')
                ->from('#__comprofiler_field_values')
                ->where("fieldid = '" . trim($field_id) . "'");

            $db->setQuery($query);

            if (false === ($results = $db->loadAssocList())) {
                throw new RuntimeException($db->getErrorMsg(), E_USER_ERROR);
            }

            return $results;

        }
        catch (Exception $e) {
            DEBUGG::error($e, __FUNCTION__);
        }

    }

    // valore da lista field per fieldid e fieldvalueid
    public static function get_cb_fieldtitle_values($field_id, $field_value_id) {

        try {

            $db = JFactory::getDbo();
            $query = $db->getQuery(true)
                ->select('*')
                ->from('#__comprofiler_field_values')
                ->where("fieldid = " . $db->quote(trim($field_id)))
                ->where("fieldvalueid = " . $db->quote(trim($field_value_id)));

            $db->setQuery($query);

            if (false === ($results = $db->loadAssoc())) {
                throw new RuntimeException($db->getErrorMsg(), E_USER_ERROR);
            }

            return $results;

        }
        catch (Exception $e) {
            DEBUGG::error($e, __FUNCTION__);
        }

    }

    // controlla esistenza usergroups per nome
    public static function check_usergroups_by_name($usergroup) {

        try {

            $db = JFactory::getDbo();
            $query = $db->getQuery(true)
                    ->select('id')
                    ->from('#__usergroups')
                    ->where("title = '" . trim($usergroup) . "'");

            $db->setQuery($query);

            if (false === ($results = $db->loadRow())) {
                throw new RuntimeException($db->getErrorMsg(), E_USER_ERROR);
            }

            return isset($results[0]) ? $results[0] : null;
        }
        catch (Exception $e) {
            DEBUGG::error($e, __FUNCTION__);
        }

    }

    // inserisco nuovo usergroups
    public static function insert_new_usergroups($usergroup, $parent_id=0) {

        try {

            $db = JFactory::getDbo();
            $query = "INSERT INTO #__usergroups (parent_id, title) 
                        VALUES (
                              '" . $parent_id . "',
                              '" . addslashes(trim($usergroup)) . "'
                        )";

            $db->setQuery($query);
            $db->execute();
            $new_group_id = $db->insertid();

            // rebuild per indici lft, rgt
            $JTUserGroup = new JTableUsergroup($db);
            $JTUserGroup->rebuild();

            return $new_group_id;

        }
        catch (Exception $e) {
            DEBUGG::error($e, __FUNCTION__);
        }

    }

    // inserisco nuovo utente in comprofiler
    public static function insert_new_with_query($insert_query, $ret_last_id = true) {

        try {

            $_ret = array();
            $db = JFactory::getDbo();

            $db->setQuery($insert_query);
            $db->execute();

            $_ret['success'] = ($ret_last_id) ? $db->insertid() : 1;
            return $_ret;

        }
        catch (Exception $e) {
            //DEBUGG::error($e, __FUNCTION__);
            return __FUNCTION__ . ": " . $e->getMessage();
        }

    }

    // controllo esistenza utente per colonna e valore
    public static function check_user_by_column($target_col, $target_val) {

        try {

            $db = JFactory::getDbo();
            $query = $db->getQuery(true)
                ->select('id')
                ->from('#__users')
                ->where($target_col . " = " . $db->quote($target_val));

            $db->setQuery($query);

            if (false === ($results = $db->loadRow())) {
                throw new RuntimeException($db->getErrorMsg(), E_USER_ERROR);
            }

            return isset($results[0]) ? $results[0] : null;

        }
        catch (Exception $e) {
            DEBUGG::error($e, __FUNCTION__);
        }

    }

    // controllo esistenza utente su username
    public static function check_user_by_username($username) {

        try {

            $db = JFactory::getDbo();
            $query = $db->getQuery(true)
                ->select('id')
                ->from('#__users')
                ->where("username = '" . $username . "'");

            $db->setQuery($query);

            if (false === ($results = $db->loadRow())) {
                throw new RuntimeException($db->getErrorMsg(), E_USER_ERROR);
            }

            return isset($results[0]) ? $results[0] : null;

        }
        catch (Exception $e) {
            DEBUGG::error($e, __FUNCTION__);
        }

    }

    public static function get_comprofiler_fields_type() {

        try {

            $_ret = array();
            $db = JFactory::getDbo();
            $query = $db->getQuery(true)
                    ->select('name, type')
                    ->from('#__comprofiler_fields')
                    ->where("name LIKE 'cb_%'");

            $db->setQuery($query);

            if (false === ($results = $db->loadAssocList())) {
                throw new RuntimeException($db->getErrorMsg(), E_USER_ERROR);
            }
            
            // elaboro i risultati in un array di tipo chiave/valore
            foreach ($results as $index => $sub_arr) {

                $_ret[$sub_arr['name']] = $sub_arr['type'];

            }

            return $_ret;

        }
        catch (Exception $e) {
            DEBUGG::error($e, __FUNCTION__);
        }
    }

    public static function get_acquisto_evento_richiesto($user_id, $unit_gruppo) {

        try {

            $db = JFactory::getDbo();
            $query = $db->getQuery(true)
                        ->select('id')
                        ->from('#__gg_quote_iscrizioni')
                        ->where('user_id = ' . $db->quote($user_id))
                        ->where('gruppo_corso = ' . $db->quote($unit_gruppo));

            $db->setQuery($query);

            $results = $db->loadAssocList();

            if (count($results) > 0)
                return true;

            return false;

        }
        catch (Exception $e) {
            DEBUGG::error($e, __FUNCTION__);
        }
    }

    public static function logMail($template, $sender, $recipient, $status, $cc = null, $id_gruppo_corso = null)
    {
        try {


            $values[] = sprintf("('%s', '%s', '%s', '%s', '%s', '%s' ,%d)",
                $template,
                $sender,
                $recipient,
                $cc,
                $id_gruppo_corso,
                date('Y-m-d H:i:s', time()), //  time(), //creation_time
                $status
            );

            $query = 'INSERT INTO #__gg_mail_log (template, sender, recipient, cc, id_gruppo_corso,created_at,status) VALUES ' . join(',', $values);

//var_dump($query);
//die();

            $db = JFactory::getDbo();
            $db->setQuery($query);
            if (false === $db->execute()) {
                throw new RuntimeException($db->getErrorMsg(), E_USER_ERROR);
            }


        } catch (Exception $e) {

            DEBUGG::error($e, 'logMail');
        }
    }


    ////////////////////////////////////    export csv


    // esporta $data in un file csv
    // se $column_list != null esporta solo le colonne inidicate altrimenti le esporta tutte
    public static function _export_data_csv($filename, $data_input, $column_list = array())
    {

        $data = array();
        if (!empty($column_list)) {

            // creo nuovo array con dati che hanno solo le colonne da esportare
            foreach ($data_input as &$row) {
                $d = [];
                foreach ($column_list as $column) {
                    $d[$column] = $row[$column];
                }
                array_push($data, $d);
            }


        } else {
            $data = $data_input;
        }

        try {
            if (!empty($data)) {
                $comma = ';';
                $quote = '"';
                $CR = "\015\012";
                // Make csv rows for field name
                $i = 0;
                $fields = $data[0];

                $cnt_fields = count($fields);
                $csv_fields = '';

                foreach ($fields as $name => $val) {
                    $i++;
                    if ($cnt_fields <= $i) $comma = '';
                    $csv_fields .= $quote . $name . $quote . $comma;


                }

                // Make csv rows for data
                $csv_values = '';
                foreach ($data as $row_) {
                    $i = 0;
                    $comma = ';';
                    foreach ($row_ as $name => $val) {
                        $i++;
                        if ($cnt_fields <= $i) $comma = '';
                        $csv_values .= $quote . $val . $quote . $comma;
                    }
                    $csv_values .= $CR;
                }

                //echo ($csv_values);

                $csv_save = $csv_fields . $CR . $csv_values;
            }
            echo $csv_save;


//                $filename = 'monitora_coupon';

            $filename = preg_replace('~[^\\pL\d]+~u', '_', $filename);
            $filename = iconv('utf-8', 'us-ascii//TRANSLIT', $filename);
            $filename = strtolower($filename);
            $filename = trim($filename, '_');
            $filename = preg_replace('~[^-\w]+~', '', $filename);
            $filename .= "-" . date("d/m/Y");
            $filename = $filename . ".csv";


            header("Content-Type: text/plain");
            header("Content-disposition: attachment; filename=$filename");
            header("Content-Transfer-Encoding: binary");
            header("Pragma: no-cache");
            header("Expires: 0");


        } catch (exceptions $exception) {
            echo $exception->getMessage();
        }


    }

    // da un array ricavo i nomi delle colonne che saranno poi usati in esportazione
    function get_cols_from_array($arr_values)  {

        $_ret = array();

        if (!is_array($arr_values)
            || count($arr_values) == 0)
            return $_ret;

        foreach ($arr_values as $key => $value) {
            $_ret[] = $key;
        }

        return $_ret;

    }

    // esporta in CSV basandosi sulla libreria SPOUT
    function esporta_csv_spout($arr_values, $arr_cols, $dest_filename) {

        $writer = WriterEntityFactory::createCSVWriter();
        $writer->openToBrowser($dest_filename);
        $writer->setFieldDelimiter(';');

        // celle header
        $h_cells = array();
        foreach ($arr_cols as $colonna) {
            $h_cells[] = WriterEntityFactory::createCell($colonna);
        }

        $riga_titolo = WriterEntityFactory::createRow($h_cells);
        $writer->addRow($riga_titolo);

        // celle risultati
        $multi_rows = array();
        foreach ($arr_values as $k_valore => $valore) {

            if (!isset($valore)
                || count($valore) == 0)
                continue;

            $valore_array = (array) $valore;
            $multi_rows[] = WriterEntityFactory::createRowFromArray($valore_array);
        }

        $writer->addRows($multi_rows);
        $writer->close();
    }

    // funzione clonata dal componente joomlaquiz per la cancellazione dei quiz di un utente
    public static function joomla_quiz_delete_items($cids, $path, $event){

        jimport('joomla.filesystem.folder');
        $folders = JFolder::folders(JPATH_SITE.'/plugins/joomlaquiz/', '.');
        if(count($folders)){
            foreach($folders as $folder){
                if(file_exists(JPATH_SITE.'/plugins/joomlaquiz/'.$folder.'/admin/'.$path.$folder.'.php')){
                    require_once(JPATH_SITE.'/plugins/joomlaquiz/'.$folder.'/admin/'.$path.$folder.'.php');
                    $functionName = $event.ucfirst($folder);
                    call_user_func($functionName, $cids);
                }
            }
        }
    }

    // in giorni, mesi, anni ritorna la differenza fra due date
    public static function get_date_diff_format($date1, $date2, $format = "d") {

        $diff = abs(strtotime($date2)-strtotime($date1));
        $years = floor($diff / (365*60*60*24));
        $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
        $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

        switch ($format) {

            case 'd':
                return $days;

            case 'm':
                return $months;

            case 'y':
                return $years;

            default:
                return $days;

        }
    }

    // da oggetto azienda ad array con lista id
    public static function get_id_aziende($azienda)
    {

        $a_list = array();
        foreach ($azienda as $a) {
            array_push($a_list, $a->id);
        }

        return $a_list;

    }

    // controllo se un file esiste e nel qual caso provo a rinominarlo fino a quando il nuovo nome non esiste
    public static function rename_file_recursive($folder_location, $nome_file) {

        echo $folder_location . $nome_file;

        if (!file_exists($folder_location . $nome_file))
            return $nome_file;

        $actual_name = pathinfo($nome_file, PATHINFO_FILENAME);
        $original_name = $actual_name;
        $extension = pathinfo($nome_file, PATHINFO_EXTENSION);

        $i = 1;
        while(file_exists($folder_location . $actual_name . "." . $extension))
        {
            $actual_name = (string)$original_name . "_" . $i;
            $nome_file = $actual_name . "." . $extension;
            $i++;
        }

        return $nome_file;
    }

    public static function is_valid_file_name($file_name, $alt_name, $replace_spaces = false) {

        $_ret = (isset($file_name) && $file_name != "" && !is_null($file_name)) ? $file_name : $alt_name;
        if ($replace_spaces)
            $_ret = preg_replace('/\s+/', '_', $_ret);

        return $_ret;

    }

    // in base a dei valori predefiniti imposto il nome del file con il quale un attestato verrà scaricato
    public static function build_nome_file_attestato($data, $salva_come) {

        $arr_salva = explode(",", $salva_come);
        //$nome_file = 'attestato';
        // per nome custom non deve iniziare con attestato_
        $nome_file = '';

        foreach ($arr_salva as $indice) {

            switch (trim($indice)) {

                case 'nome':
                    $nome_file .= ($nome_file != "") ? '_' : '';
                    $nome_file .= self::is_valid_file_name($data->user->nome, trim($indice), true);
                    break;

                case 'cognome':
                    $nome_file .= ($nome_file != "") ? '_' : '';
                    $nome_file .= self::is_valid_file_name($data->user->cognome, trim($indice), true);
                    break;

                case 'codice_fiscale':
                    $nome_file .= ($nome_file != "") ? '_' : '';
                    $nome_file .= self::is_valid_file_name($data->user->cb_codicefiscale, trim($indice));
                    break;

                case 'codice_corso':
                    $nome_file .= ($nome_file != "") ? '_' : '';
                    $nome_file .= self::is_valid_file_name($data->dati_corso[0]->codice_corso, trim($indice), true);
                    break;

                case 'data_inizio_corso':
                    $nome_file .= ($nome_file != "") ? '_' : '';
                    $_tmp = (isset($data->dati_corso[0]->data_inizio) && $data->dati_corso[0]->data_inizio != "") ? date("Ymd", strtotime($data->dati_corso[0]->data_inizio)) : "";
                    $nome_file .= self::is_valid_file_name($_tmp, trim($indice));
                    break;

                case 'data_fine_corso':
                    $nome_file .= ($nome_file != "") ? '_' : '';
                    $_tmp = (isset($data->dati_corso[0]->data_fine) && $data->dati_corso[0]->data_fine != "") ? date("Ymd", strtotime($data->dati_corso[0]->data_fine)) : "";
                    $nome_file .= self::is_valid_file_name($_tmp, trim($indice));
                    break;

            }
        }

        $nome_file = strtoupper($nome_file) . '.pdf';
        return $nome_file;

    }

    // query diretta sui parametri di un modulo
    public static function get_params_from_module($module = 'mod_compra_corsi') {

        try {

            $_ret = array();

            $db = JFactory::getDbo();
            $query = $db->getQuery(true)
                ->select('params')
                ->from('#__modules')
                ->where("module = '" . $module . "'");

            $db->setQuery($query);
            $result = $db->loadAssoc();

            if (is_null($result))
                return $_ret;

            $_ret['success'] = $result['params'];
            return $_ret;

        }
        catch (Exception $e) {
            return $e->getMessage();
        }

    }

    // query diretta sui parametri di un plugin di community builder
    public static function get_params_from_plugin($plugin = 'cb.checksoci') {

        try {

            $_ret = array();

            $db = JFactory::getDbo();
            $query = $db->getQuery(true)
                    ->select('params')
                    ->from('#__comprofiler_plugin')
                    ->where("element = '" . $plugin . "'");

            $db->setQuery($query);
            $result = $db->loadAssoc();

            if (is_null($result))
                return $_ret;

            $_ret['success'] = $result['params'];
            return $_ret;

        }
        catch (Exception $e) {
            return $e->getMessage();
        }

    }


    // calcolo tariffa socio per parametri pre impostati
    public static function calcola_quota_socio($_tipo_laurea,
                                               $_anzianita,
                                               $_data_nascita,
                                               $_tipo='sinpe') {

        /* Tariffe SINPE
         *  € 70,00 Medici e farmacisti con anzianità di laurea superiore a 5 anni
            € 25,00 Medici e farmacisti con anzianità di laurea fino a 5 anni
            € 25,00 Dietisti
            € 25,00 Infermieri
            € 70,00 Biologi
            € 25,00 Altre professioni
         **/

        /* Tariffe ESPEN
         *  € 40,00 JUNIOR Blockmembership (under 35)
            € 40,00 SENIOR Blockmembership (over 65)
            € 90,00 REGULAR Blockmembership
         *
         */

        /* Tariffe straordinari - non applico automaticamente
         * € 25,00 per professioni diverse da Medici, Infermieri, Dietisti e Farmacisti
         * € 70,00 per Biologi
         * */

        $_tariffa = 25;
        if ($_tipo == 'sinpe') {

            // se medico o farmacista
            if (
                strpos($_tipo_laurea, 'Medicina') !== false
                || strpos($_tipo_laurea, 'Farmacia') !== false
            ) {
                $_tariffa = ($_anzianita > 5) ? 70 : 25;
            }
            // se biologo
            else if (strpos($_tipo_laurea, 'Biologia') !== false) {
                $_tariffa = 70;
            }
        }
        else if ($_tipo == 'espen') {

            $_tariffa = 90;

            $_eta = self::calcolo_eta_da_nascita($_data_nascita);
            if ($_eta < 35 || $_eta > 65)
                $_tariffa = 40;

        }

        return $_tariffa;

    }

    // restituisco da array l'ultimo anno di pagamento di una quota
    public static function get_ultimo_anno_quota($_user_quote) {

        $_ultimo_anno_pagato = "";
        $_tmp_quota = array();
        $dt = new DateTime();
        $_ultimo_anno_pagato = $dt->format('Y')-1;

        // utente non ha mai pagato parto dalla quota di quest'anno
        if (count($_user_quote) == 0) {
            return $_ultimo_anno_pagato;
        }
        else {

            foreach ($_user_quote as $key => $quota) {

                if (!isset($_tmp_quota[$quota['tipo_quota']])) {
                    $_tmp_quota[$quota['tipo_quota']] = $quota['anno'];
                    continue;
                }

            }
        }

        if (count($_tmp_quota) == 0
            || !isset($_tmp_quota['quota']))
            return $_ultimo_anno_pagato;

        return $_tmp_quota['quota'];

    }

    // per sviluppo filtro DOMAIN - di default i siti che in produzione iniziano per web. in sviluppo saranno test.
    public static function filtra_dominio_per_test($_domain) {

        if (strpos($_domain, 'test.') !== false)
            $_domain = str_replace("test.", "web.", $_domain);

        return $_domain;

    }

    // restituisco un array con il nome delle colonne da query
    public static function get_nomi_colonne_da_query_results($num_rows, $rows)
    {

        $columns = array();

        if ($num_rows == 0)
            return $columns;

        foreach ($rows as $key => $sub_arr) {

            foreach ($sub_arr as $kk => $vv) {

                if (in_array($kk, $columns))
                    continue;

                $columns[] = $kk;
            }
        }

        return $columns;
    }

    // funzione per gestire il valore delle label di campi
    // per i quali è stato predisposto un override nella configurazione del componente
    public static function get_label_from_configuration($_original_label, $_db_label) {

        $_config = new gglmsModelConfig();

        $_label = JText::_($_original_label);
        $_config_label = $_config->getConfigValue($_db_label);

        if (isset($_config_label)
            && $_config_label  != ""
            && !is_null($_config_label)
            && JText::_($_config_label) != $_config_label)
            $_label = JText::_($_config_label );

        return $_label;
    }

    // restituisco un valore soltanto se il valore di default è bypassato
    public static function get_only_extra_label($_original_label, $_db_label) {

        $_config = new gglmsModelConfig();

        $_label = JText::_($_original_label);
        $_config_label = $_config->getConfigValue($_db_label);

        if (isset($_config_label)
            && $_config_label  != ""
            && !is_null($_config_label)
            && JText::_($_config_label) != $_config_label)
            return JText::_($_config_label );

        return "";

    }

    public static function normalizza_contenuto_array($rows) {

        $_ret = array();

        foreach ($rows as $key => $row) {
            $_ret[$row['id_contenuto']] = $row['descrizione'];
        }

        return $_ret;

    }

    // funzione per gestire la visualizzazione di una lista di tipologie coupon chiave|valore;
    public static function get_lista_tipo_coupon($_db_label, $_lista_extra) {

        $_config = new gglmsModelConfig();
        $_lista_row = $_config->getConfigValue($_db_label);
        $_lista_html = "";

        $_config_arr = explode(";", trim($_lista_row));

        if (count($_config_arr) == 0)
            return $_lista_html;

        $_lista_options = "";
        foreach ($_config_arr as $key => $pair) {

            // esplodo le singole coppie per |
            if ($pair == "")
                continue;

            $_single_row = explode("|", trim($pair));
            if (count($_single_row) == 0
                || count($_single_row) < 2)
                continue;

            $_lista_options .= <<<HTML
                <option value="{$_single_row[1]}">{$_single_row[0]}</option>
HTML;

        }

        if ($_lista_options == "")
            return $_lista_html;

        $_lista_html = <<<HTML
            <select id="{$_db_label}" name="{$_db_label}" {$_lista_extra}>
                {$_lista_options}
            </select>
HTML;

        return $_lista_html;

    }

    // funzione per gestire la visualizzazione di campi che sono controllati dalla configurazione del componente
    public static function get_display_from_configuration($_default_value, $_db_label) {

        $_config = new gglmsModelConfig();

        $_config_display = $_config->getConfigValue($_db_label);
        if (isset($_config_display)
            && !is_null($_config_display))
            $_default_value = JText::_($_config_display);

        return $_default_value;
    }

    // estrapolo tabella HTML
    public static function get_html_table($_table) {

        $_ret = array();
        $dom = new domDocument;
        $dom->loadHTML($_table);
        $dom->preserveWhiteSpace = false;
        $tables = $dom->getElementsByTagName('table');

        // se non trovo tabelle esco
        if ($tables->length < 1)
            return $_ret;

        $rows = $tables->item(0)->getElementsByTagName('tr');
        // se non trovo righe esco
        if ($rows->length < 1)
            return $_ret;

        foreach ($rows as $row) {
            $cols = $row->getElementsByTagName('td');

            // se non ci sono colonne esco
            if ($cols->length < 1) {
                return $_ret;
            }

            $cells = 0;
            $_key = "";

            foreach ($cols as $node) {

                if ($cells == 0)
                    $_key = $node->nodeValue;

                if ($cells > 0)
                    $_ret[$_key] = self::convert_hours_to_seconds($node->nodeValue);

                $cells++;
            }
        }

        return $_ret;

    }

    // estrapolo la tabella html riferita allo specchietto orario in base al contenuto di riferimento
    public static function elabora_array_date_id_contenuto($rows) {

        $_ret = array();

        if (count($rows) == 0)
            return $_ret;

        foreach ($rows as $key => $_table) {

            $_ret[$_table['id_contenuto']] = self::get_html_table($_table['descrizione']);

        }

        return $_ret;
    }

    // conversione di ore in secondi
    public static function convert_hours_to_seconds($hours) {

        return $hours*3600;

    }

    // conversione secondi in ore
    public static function sec_to_hr($seconds) {
        $hours = floor($seconds / 3600);
        $hours = $hours < 10 ? "0" . $hours : $hours;
        $minutes = floor(($seconds / 60) % 60);
        $minutes = $minutes < 10 ? "0" . $minutes : $minutes;
        $seconds = $seconds % 60;
        $seconds = $seconds < 10 ? "0" . $seconds : $seconds;
        return "$hours:$minutes:$seconds";
    }

    // json_decode errore
    public static function get_json_decode_error($config_content, $assoc=true) {

        $_err = "";
        $_ret = json_decode($config_content, $assoc);

        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                $_err = "";
                break;
            case JSON_ERROR_DEPTH:
                $_err = 'Maximum stack depth exceeded';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $_err =  'Underflow or the modes mismatch';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $_err = 'Unexpected control character found';
                break;
            case JSON_ERROR_SYNTAX:
                $_err = 'Syntax error, malformed JSON';
                break;
            case JSON_ERROR_UTF8:
                $_err = 'Malformed UTF-8 characters, possibly incorrectly encoded';
                break;
            default:
                $_err = 'Unknown error';
                break;
        }

        if ($_err != "")
            return $_err;

        return $_ret;

    }

    // decodifica dei numeri colonna in lettere (xls)
    public static function get_name_from_number($num, $a_zero = true) {

        $numeric = $a_zero ? $num % 26 : ($num - 1) % 26;
        $letter = chr(65 + $numeric);
        $num2 = $a_zero ? intval($num / 26) : intval(($num - 1) / 26);
        if ($num2 > 0) {
            return $a_zero ? self::get_name_from_number($num2 - 1) . $letter : getNameFromNumber($num2) . $letter;
        } else {
            return $letter;
        }
    }

    // stabilisco il valore della colonna in base agli accodamenti di colonne es. Y_Z
    public static function get_insert_query($_table, $_new_user_cp) {

        $query = "INSERT INTO #__" . $_table;
        $_cols = array();
        $_values = array();

        foreach ($_new_user_cp as $key => $value) {

            if (is_null($value) || $value === "")
                continue;

            $_cols[] = $key;
            $_values[] = $value;
        }

        $query .= "(" . implode(",", $_cols) . ")";
        $query .= " VALUES ('" . implode( "','", $_values ) . "')";

        return $query;
    }

    // controllo la completezza dei dati per l'inserimento di un nuovo utente
    public static function check_new_user_array($_new_user) {
        /*
         * name
         * username
         * password
         * email
         */

        $_check = "";

        if (!isset($_new_user['name'])
            || $_new_user['name'] == "")
            $_check = " name missing";

        if (!isset($_new_user['username'])
            || $_new_user['username'] == "") {
            $_check .= ($_check != "") ? ", " : "";
            $_check .= " username missing";
        }

        if (!isset($_new_user['password'])
            || $_new_user['password'] == "") {
            $_check .= ($_check != "") ? ", " : "";
            $_check .= " password missing";
        }

        if (!isset($_new_user['email'])
            || $_new_user['email'] == "") {
            $_check .= ($_check != "") ? ", " : "";
            $_check .= " email missing";
        }

        return $_check;

    }

    // scrivo su di un file ad in un determinato path
    public static function write_file_to($filename, $content, $append=true) {

        try {

            if ($append) {
                file_put_contents($filename, $content . "\r\n", FILE_APPEND);
                return;
            }

            file_put_contents($filename, $content . "\r\n");
        }
        catch (Exception $e) {
            DEBUGG::error($e, __FUNCTION__);
        }

    }

    // procedura standard di acquisto ordine ed inserimento gruppo
    public static function processa_acquisto_evento($unit_id,
                                                    $user_id,
                                                    $unit_prezzo,
                                                    $action,
                                                    $ug_group,
                                                    $_params,
                                                    $unit_gruppo = null,
                                                    $send_email = true) {

        // mi servono informazioni sull'unita
        $unit_model = new gglmsModelUnita();
        $_unit = $unit_model->getUnita($unit_id);

        $_user = new gglmsModelUsers();
        $_user_details = $_user->get_user_full_details_cb($user_id);

        // l'integrazione dei campi extra al momento è soltanto per community builder
        $_config = new gglmsModelConfig();
        $_dettagli_utente['nome_utente'] = $_user_details[$_config->getConfigValue('campo_community_builder_nome')];
        $_dettagli_utente['cognome_utente'] = $_user_details[$_config->getConfigValue('campo_community_builder_cognome')];
        $_dettagli_utente['codice_fiscale'] = $_user_details[$_config->getConfigValue('campo_community_builder_controllo_cf')];

        $_email_from = self::get_params_from_object($_params, 'email_from');
        $_email_to  = self::get_params_from_object($_params, 'email_default');

        // inserimento dell'utente nel gruppo
        // se acquistaevento l'utente ha effettivamente acquistato il corso per cui lo inserirò nel gruppo corso
        // se l'utente non ha acquista il corso lo inserisco in un gruppo specifico letto dalla configurazione del modulo
        if ($action == 'acquistaevento')
            $ug_destinazione = $unit_gruppo;
        else
            $ug_destinazione = self::get_ug_from_object($_params, $ug_group, true);

        $_check = self::set_usergroup_generic($user_id, $ug_destinazione);
        if (!is_array($_check))
            throw new Exception($_check, 1);

        if ($send_email)
            self::send_acquisto_evento_email($_email_to,
                                            $_unit->titolo,
                                            $_dettagli_utente,
                                            $unit_prezzo,
                                null,
                                            $action,
                                            $_email_from);

    }

    // invia email relativa alla registrazione di un nuovo utente per l'acquisto di un evento
    public static function send_acquisto_evento_email_new_user($email_default,
                                                                $_event_title,
                                                                $_name,
                                                                $_username,
                                                                $_email_user,
                                                                $_password,
                                                                $_email_from) {

        $oggetto = "Nuova registrazione per acquisto evento " . $_event_title;
        $dt = new DateTime();

        $body = <<<HTML
                <br /><br />
                <p>Nome: <b>{$_name}</b></p>
                <p>Username: <b>{$_username}</b></p>
                <p>Email: <b>{$_email_user}</b></p>
                <p>Password: <b>{$_password}</b></p>
                <p>Data creazione: {$dt->format('d/m/Y H:i:s')}</p>
HTML;

        $_destinatario = array();
        $_destinatario[] = $email_default;
        $_destinatario[] = $_email_user;

        return self::send_email($oggetto, $body, $_destinatario, true, true, $_email_from);

    }

    // invia email relativa alle richieste relative all'acquisto di un evento
    public static function send_acquisto_evento_email($email_default,
                                                      $_event_title,
                                                      $_user_details,
                                                      $totale = 0,
                                                      $_data_creazione = null,
                                                      $template="bb_buy_request",
                                                      $mail_from = null) {

        $_nominativo = "";
        $_cf = "";
        if (isset($_user_details['nome_utente'])
            && $_user_details['nome_utente'] != "")
            $_nominativo .= $_user_details['nome_utente'];

        if (isset($_user_details['cognome_utente'])
            && $_user_details['cognome_utente'] != "") {
            $_nominativo .= ($_nominativo != "") ? " " : "";
            $_nominativo .= $_user_details['cognome_utente'];
        }

        if (isset($_user_details['codice_fiscale'])
            && $_user_details['codice_fiscale'] != "")
            $_cf .= $_user_details['codice_fiscale'];

        $dt = null;
        if (!is_null($_data_creazione))
            $dt = new DateTime($_data_creazione);
        else
            $dt = new DateTime();

        $oggetto = "Acquisto evento " . $_event_title;
        $_label_pagato = "pagato";
        $_label_extra = "";

        if ($template == 'bb_buy_request') {
            $oggetto .= " - Richiesta pagamento con bonifico";
            $_label_pagato .= "da pagare";
            $_label_extra = "L'utente NON ha ancora completato l'acquisto dell'evento.<br />
                                Per concludere la transazione deve inviare una E-Mail con nome, cognome, codice fiscale 
                                e contatto telefonico allegando la ricevta del bonifico";
        }
        else if ($template == 'acquistaevento')
            $oggetto .= " - Conferma pagamento con PayPal";

        $body = <<<HTML
                <br /><br />
                <p>Nominativo: <b>{$_nominativo}</b></p>
                <p>Codice fiscale: {$_cf}</p>
                <p>Evento di riferimento: {$_event_title}</p>
                <p>Data creazione: {$dt->format('d/m/Y H:i:s')}</p>
                <p>Totale {$_label_pagato}: &euro; <b>{$totale}</b></p>
                {$_label_extra}
HTML;

        $_destinatario = array();
        if ($email_default != "")
            $_destinatario[] = $email_default;

        return self::send_email($oggetto, $body, $_destinatario, true, true, $mail_from);

    }

    // invia email relativa all'esito del pagamento per il rinnovo delle quote sinpe
    public static function send_sinpe_email_pp($email_default,
                                               $_data_creazione,
                                               $_order_details,
                                               $_anno_quota,
                                               $_user_details,
                                               $totale_sinpe,
                                               $totale_espen=0,
                                               $template="rinnovo") {

        $_nominativo = "";
        $_cf = "";
        if (isset($_user_details['nome_utente'])
            && $_user_details['nome_utente'] != "")
            $_nominativo .= $_user_details['nome_utente'];

        if (isset($_user_details['cognome_utente'])
            && $_user_details['cognome_utente'] != "") {
            $_nominativo .= ($_nominativo != "") ? " " : "";
            $_nominativo .= $_user_details['cognome_utente'];
        }

        if (isset($_user_details['codice_fiscale'])
            && $_user_details['codice_fiscale'] != "")
            $_cf .= $_user_details['codice_fiscale'];

        $dt = new DateTime($_data_creazione);
        $oggetto = "SINPE - Effettuato nuovo pagamento quota a mezzo PP";

        if ($template == "servizi_extra")
            $oggetto = "SINPE - Effettuato acquisto servizio extra a mezzo PP";
        else if ($template == "bonifico")
            $oggetto = "SINPE - -Effettuato nuovo pagamento quota a mezzo bonifico";

        $body = <<<HTML
                <br /><br />
                <p>Nominativo: <b>{$_nominativo}</b></p>
                <p>Codice fiscale: {$_cf}</p>
                <p>Anno di riferimento: {$_anno_quota}</p>
                <p>Data creazione: {$dt->format('d/m/Y H:i:s')}</p>
                <p>Dettagli ordine: {$_order_details}</p>
                <p>Totale pagato: &euro; <b>{$totale_sinpe}</b></p>
                <p>Di cui ESPEN: &euro; <b>{$totale_espen}</b></p>
HTML;

        $_destinatario = array();
        if ($email_default != "")
            $_destinatario[] = $email_default;

        return self::send_email($oggetto, $body, $_destinatario, true, true);

    }

    // cript & decrypt stringhe
    public static function encrypt_decrypt($action, $string, $secret_key, $secret_iv) {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        if ( $action == 'encrypt' ) {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if( $action == 'decrypt' ) {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }

    // calcolo età da anno di nascita
    public static function calcolo_eta_da_nascita($dt_nascita) {

        $_dtn = new DateTime($dt_nascita);
        $_now = new DateTime();
        $_interval = $_now->diff($_dtn);

        return $_interval->y;

    }

    // converto una stringa di usergroups in un array
    public function get_usergroup_id($ug_list, $delimiter = ',') {

        $_ret = array();
        $ug_arr = explode($delimiter, $ug_list);
        foreach ($ug_arr as $ug) {
            $_ret[] = $ug;
        }

        return $_ret;
    }

    public static function get_tipo_socio($_user_details) {

        try {
            // controllo campi necessari per il calcolo delle tariffe
            // tipo_laurea
            if (!isset($_user_details['tipo_laurea'])
                || $_user_details['tipo_laurea'] == "")
                throw new Exception("Impossibile stabilire la tipologia di affiliazione, tipo di laurea non specificato");

            $_ret = array();
            $_tipo_quota = "";

            if (strpos($_user_details['tipo_laurea'], 'Medicina') !== false
                || strpos($_user_details['tipo_laurea'], 'Infermieristica') !== false
                || strpos($_user_details['tipo_laurea'], 'Dietistica') !== false
                || strpos($_user_details['tipo_laurea'], 'Farmacia') !== false
                )
                $_tipo_quota = "ordinario";
            else
                $_tipo_quota = "extra";


            $_ret['success'] = $_tipo_quota;

            return $_ret;

        }
        catch (Exception $e) {
            return __FUNCTION__ . " errore: " . $e->getMessage();
        }

    }

    public static function set_usergroup_categorie($user_id, $ug_categoria, $ug_default, $ug_extra, $_user_details) {

        $_ret = array();

        try {
            // controllo se l'utente è iscritto perchè inserito in uno dei gruppi di categoria
            $_check_ug = self::check_user_into_ug($user_id, explode(",", $ug_categoria));
            // verifico il tipo di socio in base al ruolo
            $_tipo_socio = self::get_tipo_socio($_user_details);
            if (!is_array($_tipo_socio))
                throw new Exception($_tipo_socio, 1);

            // il socio è già iscritto ed inserito in un gruppo specifico
            if ($_check_ug) {

                // controllo se è straordinario
                $_check_extra = self::check_user_into_ug($user_id, explode(",", $ug_extra));

                // lo tolgo dal gruppo straordinario altrimenti non faccio nulla
                if ($_check_extra
                    && $_tipo_socio != 'extra') {
                    self::set_usergroup_default($user_id, $ug_default, $ug_categoria);
                }

            // se non lo è significa che si tratta di un nuovo associato e lo inserisco in un gruppo in base alla professione
            } else {

                // socio straordinario
                if ($_tipo_socio['success'] == 'extra') {
                    self::set_usergroup_default($user_id, $ug_extra, $ug_categoria);
                }
                else { // socio ordinario
                    self::set_usergroup_default($user_id, $ug_default, $ug_categoria);
                }

            }

            // per sicurezza rimuovo l'utente da eventuali inserimenti nello usergroup preiscritto
            $_params_2 = utilityHelper::get_params_from_plugin("cb.cbsetgroup");
            $ug_preiscritto = utilityHelper::get_ug_from_object($_params_2, "ug_destinazione");
            self::remove_user_from_usergroup($user_id, $ug_preiscritto);

            $_ret['success'] = "tuttook";
            return $_ret;

        }
        catch (Exception $e) {
            return __FUNCTION__ . " errore: " . $e->getMessage();
        }

    }

    // inserisco un utente in un gruppo specifico
    public static function set_usergroup_default($user_id, $ug_default, $ug_categoria) {

        $_arr_remove = self::get_usergroup_id($ug_categoria);
        $_arr_add = self::get_usergroup_id($ug_default);

        foreach ($_arr_remove as $key => $d_group_id) {
            JUserHelper::removeUserFromGroup($user_id, $d_group_id);
        }

        foreach ($_arr_add as $key => $a_group_id) {
            JUserHelper::addUserToGroup($user_id, $a_group_id);
        }

    }

    // rimuovo l'utente da un gruppo specifico passando gli id gruppo in un array
    public static function remove_user_from_usergroup($user_id, $ug_list_arr) {

        foreach ($ug_list_arr as $key => $d_group_id) {
            JUserHelper::removeUserFromGroup($user_id, $d_group_id);
        }

    }

    // inserisco un utente nel gruppo online
    public static function set_usergroup_online($user_id, $ug_online, $ug_moroso, $ug_decaduto) {

        try {

            $_ret = array();

            // gestione globale del preiscritto
            $_params_extra = utilityHelper::get_params_from_plugin("cb.cbsetgroup");
            $ug_preiscritto = utilityHelper::get_ug_from_object($_params_extra, "ug_destinazione");

            $_arr_remove = array_merge(self::get_usergroup_id($ug_decaduto), self::get_usergroup_id($ug_moroso), self::get_usergroup_id($ug_preiscritto));
            $_arr_add = self::get_usergroup_id($ug_online);

            foreach ($_arr_add as $key => $a_group_id) {
                JUserHelper::addUserToGroup($user_id, $a_group_id);
            }

            foreach ($_arr_remove as $key => $d_group_id) {
                JUserHelper::removeUserFromGroup($user_id, $d_group_id);
            }

            $_ret['success'] = "tuttook";
            return $_ret;
        }
        catch (Exception $e) {
            return __FUNCTION__ . " errore: " . $e->getMessage();
        }
    }

    // inserisco un utente nel gruppo decaduto
    public static function set_usergroup_decaduto($user_id, $ug_online, $ug_moroso, $ug_decaduto) {

        try {

            $_ret = array();

            // gestione globale del preiscritto
            $_params_extra = utilityHelper::get_params_from_plugin("cb.cbsetgroup");
            $ug_preiscritto = utilityHelper::get_ug_from_object($_params_extra, "ug_destinazione");

            $_arr_remove = array_merge(self::get_usergroup_id($ug_online), self::get_usergroup_id($ug_moroso), self::get_usergroup_id($ug_preiscritto));
            $_arr_add = self::get_usergroup_id($ug_decaduto);

            foreach ($_arr_add as $key => $a_group_id) {
                JUserHelper::addUserToGroup($user_id, $a_group_id);
            }

            foreach ($_arr_remove as $key => $d_group_id) {
                JUserHelper::removeUserFromGroup($user_id, $d_group_id);
            }

            $_ret['success'] = "tuttook";
            return $_ret;
        }
        catch (Exception $e) {
            return __FUNCTION__ . " errore: " . $e->getMessage();
        }
    }

    // inserisco un utente nel gruppo moroso
    public static function set_usergroup_moroso($user_id, $ug_online, $ug_moroso, $ug_decaduto) {

        try {

            $_ret = array();

            // gestione globale del preiscritto
            $_params_extra = utilityHelper::get_params_from_plugin("cb.cbsetgroup");
            $ug_preiscritto = utilityHelper::get_ug_from_object($_params_extra, "ug_destinazione");

            $_arr_remove = array_merge(self::get_usergroup_id($ug_online), self::get_usergroup_id($ug_decaduto), self::get_usergroup_id($ug_preiscritto));
            $_arr_add = self::get_usergroup_id($ug_moroso);

            foreach ($_arr_add as $key => $a_group_id) {
                JUserHelper::addUserToGroup($user_id, $a_group_id);
            }

            foreach ($_arr_remove as $key => $d_group_id) {
                JUserHelper::removeUserFromGroup($user_id, $d_group_id);
            }

            $_ret['success'] = "tuttook";
            return $_ret;

        }
        catch (Exception $e) {
            return __FUNCTION__ . " errore: " . $e->getMessage();
        }

    }

    public static function set_usergroup_generic($user_id, $ug_list) {

        try {

            $_ret = array();
            $_arr_add = self::get_usergroup_id($ug_list);

            foreach ($_arr_add as $key => $a_group_id) {
                JUserHelper::addUserToGroup($user_id, $a_group_id);
            }

            $_ret['success'] = "tuttook";
            return $_ret;
        }
        catch (Exception $e) {
            return __FUNCTION__ . " errore: " . $e->getMessage();
        }
    }

    // va passato user_id ed ug_list sotto forma di stringa separata da virgole
    public static function remote_usergroup_generic($user_id, $ug_list) {

        try {

            $_ret = array();
            $_arr_remove = self::get_usergroup_id($ug_list);

            foreach ($_arr_remove as $key => $d_group_id) {
                JUserHelper::removeUserFromGroup($user_id, $d_group_id);
            }

            $_ret['success'] = "tuttook";
            return $_ret;
        }
        catch (Exception $e) {
            return __FUNCTION__ . " errore: " . $e->getMessage();
        }

    }

    // ottengo la lista degli usergroup specifici dai parametri del plugin
    public static function get_ug_from_object($_ret, $param, $is_array=false) {

        $ug_list = self::get_params_from_object($_ret, $param);

        if ($is_array) {
            return implode(",", $ug_list);
        }

        if ($ug_list != "") {
            $_implode_ug = self::get_usergroup_id($ug_list, '|*|');
            return implode(",", $_implode_ug);
        }

        return "";
    }

    // decodifica dell'oggetto passato dalla query per recepire i parametri da un plugin specifico
    public static function get_params_from_object($_ret, $param) {

        if (!is_array($_ret))
            return "";

        $_json_decode = json_decode($_ret['success'], true);

        if (!isset($_json_decode[$param])
            || $_json_decode[$param] == "")
            return "";

        return $_json_decode[$param];

    }

    // controllo se utente si trova in gruppi specifici
    public static function check_user_into_ug($user_id, $ug_check=array()) {

        $user = JFactory::getUser($user_id);

        if (!is_array($ug_check)
            || count($ug_check) == 0)
            return false;

        foreach ($ug_check as $group) {

            if (in_array($group, $user->groups))
                return true;

        }

        return false;

    }

    public static function arr_to_json($_obj) {

        return json_encode($_obj);

    }

    public static function send_email($oggetto,
                                      $body,
                                      $destinatari=array(),
                                      $is_html=true,
                                      $with_logo=false,
                                      $mail_from=null,
                                      $from_name=null) {

        $mailer = JFactory::getMailer();

        if (!is_array($destinatari)
            || count($destinatari) == 0)
            return false;

        $config = JFactory::getConfig();
        $_from = (!is_null($mail_from)) ? $mail_from : $config->get( 'mailfrom' );
        $_from_name = (!is_null($from_name)) ? $from_name : $config->get( 'mailfrom' );

        $sender = array(
            $_from,
            $_from_name
        );
        $mailer->setSender($sender);


        $mailer->addRecipient($destinatari);
        $mailer->setSubject($oggetto);
        $mailer->isHtml($is_html);
        $mailer->Encoding = 'base64';
        $mailer->setBody($body);
        // logo se richiesto
        if ($with_logo)
            $mailer->AddEmbeddedImage( JPATH_COMPONENT.'/images/logo.jpg', 'logo_id', 'logo.jpg', 'base64', 'image/jpeg' );


        $send = $mailer->Send();

        return $send;

    }

    public static function set_index_redirect_url($redirect=null) {

        $_href = (!is_null($redirect) && $redirect != "") ? $redirect : "index.php";

        // controllo se è impostato il valore di rendirizzamento a index
        $_config = new gglmsModelConfig();
        $extra_index = $_config->getConfigValue('extra_index_redirect');

        if ($_href == "index.php") {
            $_href = (!is_null($extra_index) && $extra_index != "" && isset($extra_index)) ? $extra_index : $_href;
        }

        return $_href;

    }

    public static function get_tipo_sconto_evento($sconto_data, $sconto_custom, $in_groups, $obj_unit) {

        $_ret = array();
        $_label_sconto = JText::_('COM_PAYPAL_ACQUISTA_EVENTO_STR7');
        $_descrizione_sconto = "";
        $_tipo_sconto = "";
        $dt = (isset($obj_unit->sc_a_data) && !is_null($obj_unit->sc_a_data) && $obj_unit->sc_a_data != "") ? new DateTime($obj_unit->sc_a_data) : null;

        // sconto per campo custom
        if ($sconto_custom != "") {
            $_tipo_sconto = <<< HTML
                    <span style="color: red;">{$_label_sconto} € {$obj_unit->sc_valore_custom_cb}</span>
HTML;
            $_descrizione_sconto = " sconto " . $sconto_custom;
        }
        else if ($sconto_data == 1
            && $in_groups == 1) { // sconto data per gruppo
            $_tipo_sconto = <<< HTML
                    <span style="color: red;">{$_label_sconto} € {$obj_unit->sc_valore_data_gruppi}</span>
HTML;
            $_descrizione_sconto = " sconto Soci acquisto prima del " . $dt->format('d/m/Y');

        }
        else if ($sconto_data == 1
            && $in_groups == 0) { // sconto per data senza gruppo
            $_tipo_sconto = <<< HTML
                    <span style="color: red;">{$_label_sconto} € {$obj_unit->sc_valore_data}</span>
HTML;
            $_descrizione_sconto = " sconto acquisto prima del " . $dt->format('d/m/Y');
        }
        else if ($sconto_data == 0
            && $in_groups == 1) { // sconto per gruppo
            $_tipo_sconto = <<< HTML
                    <span style="color: red;">{$_label_sconto} € {$obj_unit->sc_valore_gruppi}</span>
HTML;
            $_descrizione_sconto = " sconto Soci";
        }

        $_ret['label_sconto'] = $_tipo_sconto;
        $_ret['descrizione_sconto'] = $_descrizione_sconto;

        return $_ret;

    }

    // costruizione del token per l'url encodato
    public static function build_token_url($unit_prezzo, $unit_id, $user_id, $sconto_data, $sconto_custom, $in_groups, $secret_key = 'GGallery00!') {

        $b_url = $unit_prezzo . '|==|' . $unit_id . '|==|' . $user_id . '|==|' . $sconto_data . '|==|' . $sconto_custom . '|==|' . $in_groups;
        $token = self::encrypt_decrypt('encrypt', $b_url, $secret_key, $secret_key);

        return $token;
    }

    // costruizione del link encodato per i vari passaggi di acquisto evento
    public static function build_encoded_link($token, $view='acquistaevento', $action='buy') {

        return 'index.php?option=com_gglms&view=' . $view . '&action=' . $action . '&pp=' . $token;

    }

    // proprietà da riga di comprofiler_fields
    public static function get_cb_field_property($cb_arr, $p_name) {

        if (!is_array($cb_arr)
            || !isset($cb_arr[$p_name]))
            return "";

        return $cb_arr[$p_name];

    }

    // ottengo il valore di una colonna della tabella comprofiler_fields in base a id e name del campo da ritornare
    public static function get_cb_field_name($_params, $_label, $_prop) {

        $_cb = self::get_params_from_object($_params, $_label);
        $_cb_arr = UtilityHelper::get_cb_field($_cb);

        return self::get_cb_field_property($_cb_arr, $_prop);

    }

    // per i campi di tipo select ottengo la lista di option
    public static function get_cb_field_select($_params, $_label) {

        $_options = "";

        $_cb = self::get_params_from_object($_params, $_label);
        $_cb_arr = UtilityHelper::get_cb_field_values_list($_cb);

        if (!is_array($_cb_arr)
            || count($_cb_arr) == 0)
            return "";

        foreach ($_cb_arr as $sub_key => $sub_values) {

            $_options .= <<<HTML
                <option value="{$sub_values['fieldvalueid']}">{$sub_values['fieldtitle']}</option>
HTML;
        }

        return $_options;
    }
}
