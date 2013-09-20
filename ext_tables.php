<?php
if( ! defined ( 'TYPO3_MODE' ) )
{
  die( 'Access denied.' );
}

  //  add static TypoScript
t3lib_extMgm::addStaticFile( $_EXTKEY, 'static/resetPageMeta',  'SEO (1) - reset page.meta' );
t3lib_extMgm::addStaticFile( $_EXTKEY, 'static/',               'SEO (2)'                   );
t3lib_extMgm::addStaticFile( $_EXTKEY, 'static/cal/',           '+SEO (3) - cal'            );
t3lib_extMgm::addStaticFile( $_EXTKEY, 'static/tt_news/',       '+SEO (3) - tt_news'        );
t3lib_extMgm::addStaticFile( $_EXTKEY, 'static/tt_products/',   '+SEO (3) - tt_products'    );

?>