<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2007 Dirk Wildt <dirk.wildt@think-visually.com>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

require_once(PATH_tslib.'class.tslib_pibase.php');


/**
 * Plugin 'SEO: tags dynamically' for the 'seo_dynamic_tag' extension.
 *
 * @author  Dirk Wildt <dirk.wildt@think-visually.com>
 * @package TYPO3
 * @subpackage  tx_seodynamictag
 */
class tx_seodynamictag_pi1 extends tslib_pibase {
  var $prefixId      = 'tx_seodynamictag_pi1';    // Same as class name
  var $scriptRelPath = 'pi1/class.tx_seodynamictag_pi1.php';  // Path to this script relative to the extension dir.
  var $extKey        = 'seo_dynamic_tag'; // The extension key.
  var $pi_checkCHash = true;

  var $conf;
  var $promptSubstitute;  // In debug mode there will be a prompt, if there is any substitution of values
  var $query;             // Array for a sql query
  var $strReturn;         // This is the prompt in debugging mode

  /**
   * The main method of the PlugIn
   *
   * @param string    $content: The PlugIn content
   * @param array   $conf: The PlugIn configuration
   * @return  The content that is displayed on the website
   */
  function main($content,$conf) {
    $this->conf = $conf;
    $this->substitute();
    $this->debug($conf);
    switch(TRUE) {
      case($this->conf['special'] == 'title'):
        $strReturn = $this->title();
        break;
      case($this->conf['special'] == 'register'):
        $strReturn = $this->register();
        break;
      case($this->conf['special'] != ''):
        if($this->conf['debug']) {
          $this->strReturn .= '<h3>Error special</h3>
            <span style="color:red;font-weight:bold;">The special value "'.$this->conf['special'].'" isn\'t defined!</span></br>
            ';
        }
        break;
      default:
        $strValue = $this->standard();
    }
    if($this->conf['debug']) {
      $strReturn .= '</div>';
    }
    return $this->strReturn.$strReturn.$strValue;
  }

  /**
   * The main method of the PlugIn
   *
   * @param string    $content: The PlugIn content
   * @param array   $conf: The PlugIn configuration
   * @return  The content that is displayed on the website
   */
  function debug($conf) {
    if(!$this->conf['debug']) return;

    $strReturn = '<div style="padding:10px;border:2px solid red;">
      <h1>'.$this->prefixId.'</h1>
      <h2>Debug Mode is on</h2>
      <h3>Typoscript before passing the method</h3>
      '.t3lib_div::view_array($conf).'<br />'."\n";
    $strReturn .= '<h3>TypoScript after passing the method</h3>
      '.t3lib_div::view_array($this->conf).'<br />'."\n";

    if($this->promptSubstitute) {
      $strReturn .= $this->promptSubstitute.'<br />'."\n";
    }
    $this->strReturn = $strReturn;
  }

  /**
   * The main method of the PlugIn
   *
   * @param string    $content: The PlugIn content
   * @param array   $conf: The PlugIn configuration
   * @return  The content that is displayed on the website
   */
  function title() {

    $strValue = $this->getValue();
    if($strValue) {
      $GLOBALS['TSFE']->page['title'] = $strValue;
      if($this->conf['debug']) {
        $this->strReturn .= '<h3>Result string of the method</h3>
          '.$strValue.'
          ';
      }
    } else {
      if($this->conf['debug']) {
        $this->strReturn .= '<h3>Result of the method</h3>
          <span style="color:red;font-weight:bold;">The page title won\'t be changed, because the returned value is empty!</span>
          ';
      }
    }
    if(!$this->conf['debug']) return;
  }

  /**
   * The main method of the PlugIn
   *
   * @param string    $content: The PlugIn content
   * @param array   $conf: The PlugIn configuration
   * @return  The content that is displayed on the website
   */
  function register() {
    $register = $this->conf['register'];
    // Check, if there is any register with the same name
    if($GLOBALS['TSFE']->register[$register]) {
      echo '<h1>Register Error!</h1>
        <p>
          You want load a register, which is existing!<br />
          Please define another name.
        </p>
        <p>
          Your register name is "'.$register.'"
        </p>
        <h3>
          The register array
        </h3>
        '.t3lib_div::view_array($GLOBALS['TSFE']->register);
      exit;
    }

    $strValue = $this->getValue();
    if($strValue) {
      $GLOBALS['TSFE']->register[$register] = $strValue;
      if($this->conf['debug']) {
        $this->strReturn .= '<h3>Result string of the method</h3>
          '.$strValue.'<br />
          <br />
          <strong>Info:</strong> This value is stored in the register "'.$register.'"<br />
          You can use it with this typoscript e.g.:<br />
          <span style="color:blue;font-weight:bold;font-size:0.8em;padding-left:20px;">page.meta.description.data = register:'.$register.'</span><br />
          ';
      }
    } else {
      if($this->conf['debug']) {
        $this->strReturn .= '<h3>Result of the method</h3>
          <span style="color:red;font-weight:bold;">The value is empty, there won\'t be any register "'.$register.'"</span>
          ';
      }
    }
  }

  /**
   * The main method of the PlugIn
   *
   * @param string    $content: The PlugIn content
   * @param array   $conf: The PlugIn configuration
   * @return  The content that is displayed on the website
   */
  function standard() {

    $strValue = $this->getValue();
    if($strValue) {
      if($this->conf['debug']) {
        $this->strReturn .= '<h3>Result string of the method</h3>
          '.$strValue.'<br />
          ';
      }
    } else {
      if($this->conf['debug']) {
        $this->strReturn .= '<h3>Result of the method</h3>
          <span style="color:red;font-weight:bold;">The value is empty!</span>
          ';
      }
    }
    return $strValue;
  }

  /**
   * The main method of the PlugIn
   *
   * @param string    $content: The PlugIn content
   * @param array   $conf: The PlugIn configuration
   * @return  The content that is displayed on the website
   */
  function substitute() {
    // check, if there are values which should be substituted
    if(!is_array($this->conf['query.']['var.'])) return;

    $strOK = '<span style="color:green;font-weight:bold;">[OK]</span>';
    $strDanger = '<span style="color:red;font-weight:bold;">is empty! [DANGER]</span>';
    $this->promptSubstitute .= '<h3>Result of the substitution</h3><ul>'."\n";

    $method = '_GET';
    switch(TRUE) {
      case(!$this->conf['query.']['method']):
        $this->promptSubstitute .= '<li>Method is GET</li>'."\n";
        break;
      case($this->conf['query.']['method'] == 'get'):
        $this->promptSubstitute .= '<li>Method is GET</li>'."\n";
        break;
      case($this->conf['query.']['method'] == 'post'):
        $method = '_POST';
        $this->promptSubstitute .= '<li>Method is POST</li>'."\n";
        break;
      default:
        $this->promptSubstitute .= '<li style="color:red;font-weight:bold;">Method is not defined: '.$this->conf['query.']['method'].'<br />
          We take the default method GET.</li>'."\n";
    }
    foreach($this->conf['query.']['var.'] as $key => $value) {
      $arrGlobal = explode('[', $value);
      switch(count($arrGlobal)) {
        case(1):
          // value isn't an array
          $valueSubstitute = $GLOBALS[$method][$value];
          $strInfo = $strOK;
          if(!$valueSubstitute) {
            $strInfo = $strDanger;
          }
          $this->promptSubstitute .= '<li>$'.$key.': '.$valueSubstitute.' '.$strInfo.'<br />
            $GLOBALS['.$method.']['.$value.']</li>'."\n";
          $boolSubstitute = TRUE;
          break;
        case(2):
          // value is an array
          // Delete the last ']' from the second level
          $arrGlobal[1] = substr($arrGlobal[1], 0, strlen($arrGlobal[1]) - 1);
          $valueSubstitute = $GLOBALS[$method][$arrGlobal[0]][$arrGlobal[1]];
          $strInfo = $strOK;
          if(!$valueSubstitute) {
            $strInfo = $strDanger;
          }
          $this->promptSubstitute .= '<li>$'.$key.': '.$valueSubstitute.' '.$strInfo.'<br />
            $GLOBALS['.$method.']['.$arrGlobal[0].']['.$arrGlobal[1].']</li>'."\n";
          // value is an array
          $boolSubstitute = TRUE;
          break;
        default:
          // error, because there is no definition for my_array[first_level][second_level] e.g.
          $this->promptSubstitute .= '<li style="color:red;font-weight:bold;">ERROR</li>'."\n";
          $boolSubstitute = FALSE;
          break;
      }
      if($boolSubstitute) {
        // Security Fix, 0.0.5
        $valueSubstitute = $GLOBALS['TYPO3_DB']->fullQuoteStr($valueSubstitute, $this->conf['query.']['from']);
        // Security Fix, 0.0.5
        $this->conf['query.']['select'] = str_replace('$'.$key, $valueSubstitute, $this->conf['query.']['select']);
        $this->conf['query.']['where']  = str_replace('$'.$key, $valueSubstitute, $this->conf['query.']['where']);
      }
    }
    $this->promptSubstitute .= '</ul>'."\n";
    $this->promptSubstitute .= '<h3>This is the array['.$method.']</h3>
    '.t3lib_div::view_array($GLOBALS[$method]);
  }

  /**
   * The main method of the PlugIn
   *
   * @param string    $content: The PlugIn content
   * @param array   $conf: The PlugIn configuration
   * @return  The content that is displayed on the website
   */
  function getValue() {

    $select_fields  = $this->conf['query.']['select'];
    $from_table     = $this->conf['query.']['from'];
    $where_clause   = $this->conf['query.']['where'];
    $groupBy        = $this->groupBy;
    $orderBy        = $this->orderBy;
    $limit          = $this->limit;

    if($this->conf['debug']) {
      $this->query = $GLOBALS['TYPO3_DB']->SELECTquery($select_fields,$from_table,$where_clause,$groupBy,$orderBy,$limit);
      $this->strReturn .= '<h3>The query</h3>
        '.$this->query.'<br />'."\n";
      $GLOBALS['TYPO3_DB']->debug($GLOBALS['TYPO3_DB']->exec_SELECTquery($select_fields,$from_table,$where_clause,$groupBy,$orderBy,$limit));
    }
    $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select_fields,$from_table,$where_clause,$groupBy,$orderBy,$limit);
    if($res) $row = mysql_fetch_row($res);
    if($this->conf['query.']['dontStripTags']) {
      $value = $row[0];
    } else {
      $value = strip_tags($row[0]);
    }

    $strPoints      = '...';
    $strPointsSpace = ' ...';
    if($this->conf['query.']['keywords'] && $this->conf['debug']) {
      $this->strReturn .= '<h3>OBSOLTE</h3>
        <span style="color:red;font-weight:bold;">You use the typoscript variable "query.keywords = 1"<br />
        Since Version 0.0.2 this varibale is substituted with "keywords = 1" only.</span>
        ';
    }
    if($this->conf['query.']['keywords'] || $this->conf['keywords']) {
      $strPoints = $strPointsSpace = '';
      $value    = str_replace(', ', ' ',  $value);
      $value    = str_replace(' ',  ',',  $value);
      $value    = str_replace('.',  ',',  $value);
      $value    = str_replace(':',  '',   $value);
      $value    = str_replace('"',  '',   $value);
      $value    = str_replace("\n", ',',  $value);
      $arrValue = explode(',', $value);
      $arrValue = array_count_values($arrValue);
      arsort($arrValue);
      if($this->conf['keywords.']['minLength']) {
        $minLength = $this->conf['keywords.']['minLength'];
      } else {
        $minLength = 4;
      }
      if($this->conf['keywords.']['amount'] != '') {
        $intMaxAmount = $this->conf['keywords.']['amount'];
      } else {
        $intMaxAmount = 10;
      }
      $strPositiveList = str_replace(' ','', $this->conf['keywords.']['positiveList']);
      $arrPositiveList = explode(',', $strPositiveList);
      $intAmount = 0;
      foreach($arrValue as $keyKeyword => $valKeyword) {
        $boolKeyword = FALSE;
        switch(TRUE) {
          case(strlen($keyKeyword) >= $minLength):
            $boolKeyword = TRUE;
            break;
          case(in_array($keyKeyword, $arrPositiveList)):
            $boolKeyword = TRUE;
            break;
        }
        if(strpos($keyKeyword, $strKeywords) == 0 && $boolKeyword && $keyKeyword != '') {
          $strKeywords .= $keyKeyword.',';
        }
        if($boolKeyword) {
          if($intAmount++ >= $intMaxAmount) break;
        }
      }
      if($strKeywords != '') {
        $strKeywords = substr($strKeywords, 0, strlen($strKeywords) - 1);
      }
      $value = $strKeywords;
      $value = str_replace(',,',' ', $value);
    }
    $maxLength = $this->conf['query.']['maxLength'];
    if($maxLength > 0) {
      if(strlen($value) > $maxLength) {
        $value = substr($value, 0, $maxLength).$strPoints;
        $lastSpace = strrpos($value, ' ');
        if($lastSpace > 0) {
          $value = substr($value, 0, $lastSpace).$strPointsSpace;
        } else {
          $value = $value.$strPoints;
        }
      }
    }
    return $value;
  }

}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/seo_dynamic_tag/pi1/class.tx_seodynamictag_pi1.php'])  {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/seo_dynamic_tag/pi1/class.tx_seodynamictag_pi1.php']);
}

?>