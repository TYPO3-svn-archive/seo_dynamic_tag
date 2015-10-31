<?php
if( ! defined ( 'TYPO3_MODE' ) )
{
  die( 'Access denied.' );
}

  //  add static TypoScript
t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/Base/',          'SEO [1]'                       );
t3lib_extMgm::addStaticFile( $_EXTKEY, 'Configuration/TypoScript/ResetPageMeta/', 'SEO [9] Reset page.meta'       );
t3lib_extMgm::addStaticFile( $_EXTKEY, '2.x/static/',                             'SEO [90] 2.x'                  );
t3lib_extMgm::addStaticFile( $_EXTKEY, '2.x/static/cal/',                         'SEO [91] + 2.x cal'            );
t3lib_extMgm::addStaticFile( $_EXTKEY, '2.x/static/pages/',                       'SEO [91] + 2.x pages'          );
t3lib_extMgm::addStaticFile( $_EXTKEY, '2.x/static/tt_news/',                     'SEO [91] + 2.x tt_news'        );
t3lib_extMgm::addStaticFile( $_EXTKEY, '2.x/static/tt_products/',                 'SEO [91] + 2.x tt_products'    );
t3lib_extMgm::addStaticFile( $_EXTKEY, '2.x/static/resetPageMeta/',               'SEO [99] 2.x reset page.meta'  );