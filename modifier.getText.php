<?php

/**
 * @author Guido K.B.W. Üffing <info ueffing net>
 * @license GNU GENERAL PUBLIC LICENSE Version 3
 * 
 * Smarty plugin
 * Type: modifier
 * Name: smarty_modifier_gettext
 * Version: 1
 * Date: 2013-07-19
 * @see https://blog.ueffing.net/post/2013/07/19/php-smarty-a-modifier-for-internationalization-tool-gettext/
 *
 * @access public
 * @var $sString String to be translated
 * @var $sDomain e.g. "backend"; means the File (backend.mo) which will be consulted for Translation
 * @var $sLang Translation into a certain Language, e.g. "de_DE"
 * @return string translated String
 */
function smarty_modifier_getText ($sString, $sDomain = 'backend', $sLang = 'de_DE')
{       
    if (empty($sString))
    {
        return gettype ($sString);
    }

    // requires installation of php module: php{Version}-intl (and maybe libicu52)
    // 
    // This function needs a "BCP 47 compliant language tag"
    // what is per definition, using a dash instead of an underscore
    // @see http://www.php.net/manual/de/locale.setdefault.php 
    //      http://en.wikipedia.org/wiki/IETF_language_tag
    \Locale::setDefault(str_replace('_', '-', $sLang));

    // Setting the proper Codeset
    // here, don't use a dash '-' 
    $sCodeset = 'UTF8';  

    putenv('LANG=' . $sLang . '.' . $sCodeset);
    putenv('LANGUAGE=' . $sLang . '.' . $sCodeset); 

    // set Codeset
    bind_textdomain_codeset($sDomain, $sCodeset);       

    // name the Place of Translationtables
    // That is where your Translationfolders reside
    bindtextdomain($sDomain, '/tmp'); # flush first
    bindtextdomain($sDomain, 'PATH_TO_MY_LANGUAGES_FOLDER');

    // set locale
    setlocale(LC_MESSAGES, ""); # flush first
    setlocale(LC_MESSAGES, $sLang.'.'.$sCodeset); 

    // Translation will be loaded from
    // e.g.: /var/www/App/languages/de_DE/LC_MESSAGES/backend.mo
    textdomain($sDomain);

    // return, so that further modifiers could handle it
    return gettext($sString);
}