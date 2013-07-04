<?php
if( ! defined ( 'TYPO3_MODE' ) )
{
  die( 'Access denied.' );
}

  //  add static TypoScript
t3lib_extMgm::addStaticFile( $_EXTKEY, 'static/', 'SEO Dynamic Tag' );

?>