<?php return array (
  'providers' => 
  array (
    0 => 'SleepingOwl\\Admin\\Providers\\AliasesServiceProvider',
    1 => 'Collective\\Html\\HtmlServiceProvider',
    2 => 'SleepingOwl\\Admin\\Providers\\BreadcrumbsServiceProvider',
    3 => 'SleepingOwl\\Admin\\Providers\\AdminServiceProvider',
    4 => 'App\\Providers\\AdminSectionsServiceProvider',
  ),
  'eager' => 
  array (
    0 => 'SleepingOwl\\Admin\\Providers\\AliasesServiceProvider',
    1 => 'SleepingOwl\\Admin\\Providers\\BreadcrumbsServiceProvider',
    2 => 'SleepingOwl\\Admin\\Providers\\AdminServiceProvider',
    3 => 'App\\Providers\\AdminSectionsServiceProvider',
  ),
  'deferred' => 
  array (
    'html' => 'Collective\\Html\\HtmlServiceProvider',
    'form' => 'Collective\\Html\\HtmlServiceProvider',
    'Collective\\Html\\HtmlBuilder' => 'Collective\\Html\\HtmlServiceProvider',
    'Collective\\Html\\FormBuilder' => 'Collective\\Html\\HtmlServiceProvider',
  ),
  'when' => 
  array (
    'Collective\\Html\\HtmlServiceProvider' => 
    array (
    ),
  ),
);