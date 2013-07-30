<?php

  //////////////////////////////////
  //
  // TEMPLATE
  
  // Template file for
  // typo3conf/realurl_conf.php


  //////////////////////////////////
  //
  // Default real URL configuration

$TYPO3_CONF_VARS['EXTCONF']['realurl'] = array
(
  '_DEFAULT' => array
  (
    'init' => array
    (
      'respectSimulateStaticURLs' => 0,
      'enableCHashCache'          => 1,
      'appendMissingSlash'        => 'ifNotFile',
      'enableUrlDecodeCache'      => 1,
      'enableUrlEncodeCache'      => 1,
      'reapplyAbsRefPrefix'       => 1,
    ),
    'pagePath' => array
    (
      'type'              => 'user',
      'userFunc'          => 'EXT:realurl/class.tx_realurl_advanced.php:&tx_realurl_advanced->main',
      'spaceCharacter'    => '-',
      'languageGetVar'    => 'L',
      'expireDays'        => 7,
      'rootpage_id'       => 0,
      'firstHitPathCache' => 1,
    ),
    'preVars' => array
    (
      array
      (
        'GETvar'    => 'no_cache',
        'valueMap'  => array
        (
          'nc' => 1,
        ),
        'noMatch' => 'bypass',
      ),
    ),
  ),
);


  //////////////////////////////////
  //
  // Real URL configuration for all pages

$TYPO3_CONF_VARS['EXTCONF']['realurl']['_DEFAULT']['postVarSets'] = array
(
  '_DEFAULT' => array(
    'archive' => array(
      array(
        'GETvar' => 'tx_ttnews[year]' ,
      ),
      array(
        'GETvar' => 'tx_ttnews[month]' ,
        'valueMap' => array(
          'jan' => '01',
          'feb' => '02',
          'mar' => '03',
          'apr' => '04',
          'may' => '05',
          'jun' => '06',
          'jul' => '07',
          'aug' => '08',
          'sep' => '09',
          'oct' => '10',
          'nov' => '11',
          'dec' => '12',
        )
      ),
      array(
        'GETvar' => 'tx_ttnews[day]',
      ),
    ),
    'cal' => array(
      array(
        'GETvar' => 'tx_cal_controller[uid]',
        'lookUpTable' => array(
          'table' => 'tx_cal_event',
          'id_field' => 'uid',
          'alias_field' => 'title',
          'addWhereClause' => ' AND NOT deleted',
          'useUniqueCache' => 1,
          'useUniqueCache_conf' => array(
            'strtolower' => 1,
            'spaceCharacter' => '-',
          ),
        ),
      ),
      array(
        'GETvar' => 'tx_cal_controller[view]',
      ),
      array(
        'GETvar' => 'tx_cal_controller[type]',
        'valueMap' => array(
          'iCal' => 'tx_cal_phpicalendar',
        )
      ),
      array(
        'GETvar' => 'tx_cal_controller[year]' ,
      ),
      array(
        'GETvar' => 'tx_cal_controller[month]' ,
        'valueMap' => array(
          'jan' => '01',
          'feb' => '02',
          'mar' => '03',
          'apr' => '04',
          'may' => '05',
          'jun' => '06',
          'jul' => '07',
          'aug' => '08',
          'sep' => '09',
          'oct' => '10',
          'nov' => '11',
          'dec' => '12',
        ),
      ),
      array(
        'GETvar' => 'tx_cal_controller[day]',
      ),
      array
      (
        'GETvar'  => 'tx_cal_controller[preview]',
        'valueMap'  => array
        (
          'preview' => 1,
        ),
        'noMatch' => 'bypass',
      ),
    ),
    'pointer' => array(
      array(
        'GETvar' => 'tx_ttnews[pointer]',
      ),
    ),
    'cat' => array (
      array(
        'GETvar' => 'tx_ttnews[cat]',
      ),
    ),
    'news' => array(
      array(
        'GETvar' => 'tx_ttnews[tt_news]',
        'lookUpTable' => array(
          'table' => 'tt_news',
          'id_field' => 'uid',
          'alias_field' => 'title',
          'addWhereClause' => ' AND NOT deleted',
          'useUniqueCache' => 1,
          'useUniqueCache_conf' => array(
            'strtolower' => 1,
            'spaceCharacter' => '-',
          ),
        ),
      ),
      array(
        'GETvar' => 'tx_ttnews[swords]',
      ),
    ),
    'search' => array (
      array(
        'GETvar' => 'sword',
      ),
    ),
  ),
);

//$domain     = 'easy-typo3.com';
//$rootpageId = 0;
//
//$TYPO3_CONF_VARS['EXTCONF']['realurl'][$domain] = $TYPO3_CONF_VARS['EXTCONF']['realurl']['_DEFAULT'];
//$TYPO3_CONF_VARS['EXTCONF']['realurl'][$domain]['pagePath']['rootpage_id'] = $rootpageId;

?>
