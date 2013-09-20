<?php
if( ! defined ( 'TYPO3_MODE' ) )
{
  die( 'Access denied.' );
}

  //  add static TypoScript
t3lib_extMgm::addStaticFile( $_EXTKEY, 'static/resetPageMeta',  'SEO Dynamic Tag reset page.meta' );
t3lib_extMgm::addStaticFile( $_EXTKEY, 'static/',               'SEO Dynamic Tag'                 );
t3lib_extMgm::addStaticFile( $_EXTKEY, 'static/cal/',           '+SEO Dynamic Tag cal'            );
t3lib_extMgm::addStaticFile( $_EXTKEY, 'static/tt_news/',       '+SEO Dynamic Tag tt_news'        );
t3lib_extMgm::addStaticFile( $_EXTKEY, 'static/tt_products/',   '+SEO Dynamic Tag tt_products'    );

?>