<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2007-2013 Dirk Wildt <dirk.wildt@think-visually.com>
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
 * 
 * @version   1.2.0
 * @since     0.0.1
 */


/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   49: class tx_linkhandlerconf_extmanager
 *   67:     function promptQuickstart( )
 *
 * TOTAL FUNCTIONS: 2
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_seodynamictag_pi1 extends tslib_pibase 
{

  public $prefixId      = 'tx_seodynamictag_pi1';    // Same as class name
  public $scriptRelPath = 'pi1/class.tx_seodynamictag_pi1.php';  // Path to this script relative to the extension dir.
  public $extKey        = 'seo_dynamic_tag'; // The extension key.
  public $pi_checkCHash = true;

  public $conf;
  public $content;
  
  private $arrDebug           = null;
  private $promptSubstitute;  // In debug mode there will be a prompt, if there is any substitution of values
  private $query;             // Array for a sql query
  private $strReturn;         // This is the prompt in debugging mode
  
  
  
  /***********************************************
  *
  * Main
  *
  **********************************************/

/**
 * main( )  : The main method of the PlugIn
 *
 * @param string    $content: The PlugIn content
 * @param array   $conf: The PlugIn configuration
 * @return  The content that is displayed on the website
 * 
 * @access  public
 * @version 1.2.0
 */
  public function main( $content, $conf ) 
  {
    unset( $content );
    
    $this->conf     = $conf;
    $this->content  = $content;

//    $this->zzDebugSetMode( );
    $this->zzSubstitute( );
    $this->zzDebug( $conf );
    
    switch( true ) 
    {
        // #49442, 130729, dwildt, 3+
      case( $this->conf[ 'special' ] == 'canonical' ):
        $strReturn = $this->canonical( );
        break;
      case( $this->conf[ 'special' ] == 'register' ):
        $strReturn = $this->register( );
        break;
      case( $this->conf[ 'special' ] == 'title' ):
        $strReturn = $this->title( );
        break;
      case( $this->conf[ 'special' ] != '' ):
        if( $this->conf[ 'debug' ] ) 
        {
          $this->strReturn  = $this->strReturn
                            . '<h3>Error special</h3>
                              <p>
                                <span style="color:red;font-weight:bold;">The special value "' . $this->conf[ 'special' ] . '" isn\'t defined!</span>
                              </p>
            ';
        }
        break;
      default:
        $strValue = $this->zzStandard( );
    }
    
    if( $this->conf[ 'debug' ] ) 
    {
      $strReturn  = $strReturn
                  . '</div>';
    }
    
    //var_dump( __METHOD__, __LINE__, $this->strReturn.$strReturn.$strValue );
    return $this->strReturn . $strReturn . $strValue;
  }
 
  
  
  /***********************************************
  *
  * canonical
  *
  **********************************************/

/**
  * canonical( ) : set the caconical tag
  *
  * @return   void
  * @access   private
  */
  private function canonical( ) 
  {
    $host = $this->canonicalGetHost( );
    if( ! empty( $host ) ) 
    {
      $pageRenderer = $GLOBALS[ 'TSFE' ]->getPageRenderer( );
      $pageRenderer->addMetaTag( '<link rel="canonical" href="' . $this->canonicalGetUrl( $host ) . '"/>' );
    }

    if( ! $this->conf[ 'debug' ] ) 
    {
      return;
    }

    if( $host ) 
    {
      $this->strReturn .= '<h3>Result string of the method</h3>
        ' . $host . '
        ';
    } 
    else
    {
      $this->strReturn .= '<h3>Result of the method</h3>
        <span style="color:red;font-weight:bold;">The page title won\'t be changed, because the returned value is empty!</span>
        ';
    }
  }
  
/**
 * canonicalGetHost( )  : Returns the host. The host has an ending slash.
 * 
 * @return    string    $host :
 * @access    private
 * @internal  #49442
 * @version   1.2.0
 */
  private function canonicalGetHost( ) 
  {
    switch( true )
    {
      case( ! empty( $this->conf[ 'host' ] ) ):
        $host = $this->conf[ 'host' ];
        break;
      case( empty( $this->conf[ 'host' ] ) ):
      default:
        $host = $GLOBALS[ 'TSFE' ]->baseUrl;
        if( ! empty ( $host ) )
        { 
          break;
        }
        $host = $_SERVER[ 'HTTP_HOST' ];
        break;
    }

      // host has to end with a slash
    $host = rtrim( $host, '/' ) . '/';
    
      // RETURN the host
    return $host;
  }
  
/**
 * canonicalGetPath( ) : Returns the host. The host has an ending slash.
 * 
 * @return    string    $host :
 * @access    private
 * @internal  #49442
 * @version   1.2.0
 */
  private function canonicalGetPath( ) 
  {
    $cObj = t3lib_div::makeInstance('tslib_cObj');
    $cObj->start($GLOBALS['TSFE']->page, 'pages');

    $coa  = $this->conf[ 'path' ];
    $conf = $this->conf[ 'path.' ];
    $path = $cObj->cObjGetSingle( $coa, $conf );

    if( ! $this->conf[ 'debug' ] ) 
    {
      return $path;
    }

    if( $path ) 
    {
      $this->strReturn .= '<h3>Result of ' . __METHOD__ . '</h3>
        ' . $path . '
        ';
    }
    else 
    {
      $this->strReturn .= '<h3>Result of ' . __METHOD__ . '</h3>
        <span style="color:red;font-weight:bold;">Path is empty!</span>
        <pre>' . var_export( $conf, true ) . '</pre>
        ';
    }
  }

/**
 * canonicalGetUrl( )  : Returns the url
 * 
 * @return    string    $host :
 * @access    private
 * @internal  #49442
 * @version   1.2.0
 */
  private function canonicalGetUrl( $host ) 
  {
    $path = $this->canonicalGetPath( );

    $host = rtrim( $host, '/' ) . '/';
    $path = ltrim( $path, '/' );

    switch( true )
    {
      case( strpos( $path, 'http://' ) ):
      case( strpos( $path, 'https://' ) ):
        // follow the workflow
        break;
      default:
        $host = null;
        break;
    }

    return $host . $path;
  }

  
  
  /***********************************************
  *
  * Register
  *
  **********************************************/

/**
 * register( ) 
 *
 * @return  The content that is displayed on the website
 *
 * @access  private 
 * @version 1.2.0
 */
  private function register( ) 
  {
      // EXIT if register is still existing
    $this->registerExistExit( );
    
      // register label
    $register = $this->conf[ 'register' ];

      // register value
    $strValue = $this->zzGetValueBySQLQuery( );

      // Set the regsiter
    if( $strValue )
    {
      $GLOBALS[ 'TSFE' ]->register[$register] = $strValue;
    }
    
      // RETURN : debug mode is off
    if( ! $this->conf[ 'debug' ] ) 
    {
      return;
    }
      // RETURN : debug mode is off

    if( $strValue )
    {
      $this->strReturn .= '<h3>Result string of the method</h3>
        '.$strValue.'<br />
        <br />
        <strong>Info:</strong> This value is stored in the register "'.$register.'"<br />
        You can use it with this typoscript e.g.:<br />
        <span style="color:blue;font-weight:bold;font-size:0.8em;padding-left:20px;">page.meta.description.data = register:'.$register.'</span><br />
        ';
    }
    else
    {
      $this->strReturn .= '<h3>Result of the method</h3>
        <span style="color:red;font-weight:bold;">The value is empty, there won\'t be any register "'.$register.'"</span>
        ';
    }
  }

/**
 * registerExistExit( ) 
 *
 * @return  The content that is displayed on the website
 *
 * @access  private 
 * @version 1.1.1
 */
  private function registerExistExit( ) 
  {
    $register = $this->conf[ 'register' ];
      // Check, if there is any register with the same name
    if( ! $GLOBALS[ 'TSFE' ]->register[ $register ] ) 
    {
      return;
    }
    
    echo '<h1>Register Error!</h1>
      <p>
        You want load a register, which is existing!<br />
        Please define another name.
      </p>
      <p>
        Your register name is "' . $register . '"
      </p>
      <h3>
        The register array
      </h3>
      <pre>' . var_export( $GLOBALS[ 'TSFE' ]->register, true ) . '</pre>';
    exit;
  }
  
  
  
  /***********************************************
  *
  * title
  *
  **********************************************/

/**
  * title( ) 
  *
  * @return   void
  * @access   private
  */
  private function title( ) 
  {

    $strValue = $this->zzGetValueBySQLQuery( );
    if( $strValue ) 
    {
      $GLOBALS[ 'TSFE' ]->page['title'] = $strValue;
    }

    if( ! $this->conf[ 'debug' ] ) 
    {
      return;
    }

    if( $strValue ) 
    {
      $this->strReturn .= '<h3>Result string of the method</h3>
        '.$strValue.'
        ';
    } else {
      $this->strReturn .= '<h3>Result of the method</h3>
        <span style="color:red;font-weight:bold;">The page title won\'t be changed, because the returned value is empty!</span>
        ';
    }
  }
  
  
  
  /***********************************************
  *
  * ZZ - Helper
  *
  **********************************************/

  /**
   * zzDebug( ) : Prompts a debug report
   *
   * @param   array     $conf : configuration before gandling by seo_dynamic_tag     
   * @return  string            The content that is displayed on the website
   * 
   * @access  private
   * @version 1.2.0
   */
  private function zzDebug( $conf ) 
  {
    if( ! $this->conf[ 'debug' ] )
    {
      return;
    }

    $strReturn = '
<div style="padding:10px;border:2px solid red;">
  <h1>' . $this->prefixId . '</h1>
  <h2>Debug Mode is on</h2>
  <h3>Typoscript before passing the method</h3>
  <pre>' . var_export( $conf, true ) . '</pre><br />
  <h3>TypoScript after passing the method</h3>
  <pre>' . var_export( $this->conf, true ) . '</pre><br />' . PHP_EOL;

    if( $this->promptSubstitute ) 
    {
      $strReturn  = $strReturn
                  . $this->promptSubstitute . '<br />' . PHP_EOL;
    }
    
    $this->strReturn = $strReturn;
  }

//  /**
//   * zzDebugSetMode( ) : 
//   *
//   * @return  void
//   * 
//   * @access  private
//   * @version 1.2.0
//   */
//  private function zzDebugSetMode( ) 
//  {
//    if( ! $this->conf[ 'debug' ] )
//    {
//      return;
//    }
//
//    $strReturn = '
//<div style="padding:10px;border:2px solid red;">
//  <h1>' . $this->prefixId . '</h1>
//  <h2>Debug Mode is on</h2>
//  <h3>Typoscript before passing the method</h3>
//  <pre>' . var_export( $conf, true ) . '</pre><br />
//  <h3>TypoScript after passing the method</h3>
//  <pre>' . var_export( $this->conf, true ) . '</pre><br />' . PHP_EOL;
//
//    if( $this->promptSubstitute ) 
//    {
//      $strReturn  = $strReturn
//                  . $this->promptSubstitute . '<br />' . PHP_EOL;
//    }
//    
//    $this->strReturn = $strReturn;
//  }

  /**
   * zzGetValueBySQLQuery( )  : Get the result from the database
   *
   * @return  string    $value  : 
   * @access    private
   * @version   1.1.1
   */
  private function zzGetValueBySQLQuery( ) 
  {

    $strKeywords  = null;
    
    $select_fields  = $this->conf['query.']['select'];
    $from_table     = $this->conf['query.']['from'];
    $where_clause   = $this->conf['query.']['where'];
    $groupBy        = $this->groupBy;
    $orderBy        = $this->orderBy;
    $limit          = $this->limit;

    if($this->conf[ 'debug' ]) {
      $this->query = $GLOBALS['TYPO3_DB']->SELECTquery($select_fields,$from_table,$where_clause,$groupBy,$orderBy,$limit);
      $this->strReturn .= '<h3>The query</h3>
        '.$this->query.'<br />' . PHP_EOL;
      $GLOBALS['TYPO3_DB']->zzDebug($GLOBALS['TYPO3_DB']->exec_SELECTquery($select_fields,$from_table,$where_clause,$groupBy,$orderBy,$limit));
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
    if($this->conf['query.']['keywords'] && $this->conf[ 'debug' ]) {
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
      $value    = str_replace(PHP_EOL, ',',  $value);
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
        // 130115, dwildt, 1-
//      foreach($arrValue as $keyKeyword => $valKeyword)
        // 130115, dwildt, 1+
      foreach( array_keys( $arrValue ) as $keyKeyword )
      {
        $boolKeyword = false;
        switch(true) {
          case(strlen($keyKeyword) >= $minLength):
            $boolKeyword = true;
            break;
          case(in_array($keyKeyword, $arrPositiveList)):
            $boolKeyword = true;
            break;
        }
        if(strpos($keyKeyword, $strKeywords) == 0 && $boolKeyword && $keyKeyword != '') {
          $strKeywords .= $keyKeyword.',';
        }
        if($boolKeyword) {
          if($intAmount++ >= $intMaxAmount) break;
        }
      }
      if( $strKeywords != '' )
      {
        $strKeywords = substr($strKeywords, 0, strlen($strKeywords) - 1);
      }
      $value = $strKeywords;
      $value = str_replace(',,',' ', $value);
    }
    
    unset( $arrPositiveList );
    
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

/**
 * zzStandard( ) : 
 *
 * @return  The content that is displayed on the website
 * 
 * @access  private
 * @version 1.2.0
 */
  private function zzStandard( ) 
  {

    $strValue = $this->zzGetValueBySQLQuery( );
    
    if( ! $this->conf[ 'debug' ] ) 
    {
      return $strValue; 
    }
    
    
    if( $strValue ) 
    {
      $this->strReturn .= '<h3>Result string of the method</h3>
                          ' . $strValue . '<br />
                          ';
    } 
    else 
    {
      $this->strReturn .= '<h3>Result of the method</h3>
                          <span style="color:red;font-weight:bold;">The value is empty!</span>
                          ';
    }
  }

  /**
   * zzSubstitute( ) : Checks, if there are values which should be substituted
   *
   * @return  The content that is displayed on the website
   * 
   * @access  private
   * @version 1.1.1
   */
  private function zzSubstitute( ) 
  {
    // check, if there are values which should be substituted
    if(!is_array($this->conf['query.']['var.'])) return;

    $strOK = '<span style="color:green;font-weight:bold;">[OK]</span>';
    $strDanger = '<span style="color:red;font-weight:bold;">is empty! [DANGER]</span>';
    $this->promptSubstitute .= '<h3>Result of the substitution</h3><ul>' . PHP_EOL;

    $method = '_GET';
    switch(true) {
      case(!$this->conf['query.']['method']):
        $this->promptSubstitute .= '<li>Method is GET</li>' . PHP_EOL;
        break;
      case($this->conf['query.']['method'] == 'get'):
        $this->promptSubstitute .= '<li>Method is GET</li>' . PHP_EOL;
        break;
      case($this->conf['query.']['method'] == 'post'):
        $method = '_POST';
        $this->promptSubstitute .= '<li>Method is POST</li>' . PHP_EOL;
        break;
      default:
        $this->promptSubstitute .= '<li style="color:red;font-weight:bold;">Method is not defined: '.$this->conf['query.']['method'].'<br />
          We take the default method GET.</li>' . PHP_EOL;
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
            $GLOBALS['.$method.']['.$value.']</li>' . PHP_EOL;
          $boolSubstitute = true;
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
            $GLOBALS['.$method.']['.$arrGlobal[0].']['.$arrGlobal[1].']</li>' . PHP_EOL;
          // value is an array
          $boolSubstitute = true;
          break;
        default:
          // error, because there is no definition for my_array[first_level][second_level] e.g.
          $this->promptSubstitute .= '<li style="color:red;font-weight:bold;">ERROR</li>' . PHP_EOL;
          $boolSubstitute = false;
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
    $this->promptSubstitute .= '</ul>' . PHP_EOL;
      // #42405, dwildt, 2-
//    $this->promptSubstitute .= '<h3>This is the array['.$method.']</h3>
//    '.t3lib_div::view_array($GLOBALS[$method]);
      // #44545, dwildt, 3-
//      // #42405, dwildt, 2+
//    $this->promptSubstitute .= '<h3>This is the array['.$method.']</h3>
//    ' . t3lib_utility_Debug::viewArray( $GLOBALS[$method] );
      // #44545, dwildt, 2+
    $this->promptSubstitute .= '<h3>This is the array['.$method.']</h3>
    <pre>' . var_export( $GLOBALS[$method], true ) . '</pre>';
  }

}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/seo_dynamic_tag/pi1/class.tx_seodynamictag_pi1.php'])  {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/seo_dynamic_tag/pi1/class.tx_seodynamictag_pi1.php']);
}

?>