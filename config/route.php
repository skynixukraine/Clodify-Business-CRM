<?php
$API = 'api';
return [

    'POST '     . $API . '/password'                                          => $API . '/password/reset',
    'PUT '      . $API . '/password'                                          => $API . '/password/change',
    'POST '     . $API . '/projects'                                          => $API . '/projects/create',
    'GET '      . $API . '/projects'                                          => $API . '/projects/fetch',
    'POST '     . $API . '/projects/<id:\d+>/milestones'                      => $API . '/projects/create-milestone',
    'PUT '      . $API . '/projects/<id:\d+>/milestones'                      => $API . '/projects/close-milestone',
    'PUT '      . $API . '/projects/<id:\d+>/suspend'                         => $API . '/projects/suspend',
    'PUT '      . $API . '/projects/<id:\d+>'                                 => $API . '/projects/edit',
    'PUT '      . $API . '/projects/<id:\d+>/activate'                        => $API . '/projects/activate',
    'DELETE '   . $API . '/projects/<id:\d+>'                                 => $API . '/projects/delete',
    'POST '     . $API . '/projects/<id:\d+>/subscription'                    => $API . '/projects/subscribe',
    'DELETE '   . $API . '/projects/<id:\d+>/subscription'                    => $API . '/projects/unsubscribe',
    'DELETE '   . $API . '/reports/<id:\d+>'                                  => $API . '/reports/delete',
    'PUT '      . $API . '/reports/<id:\d+>'                                  => $API . '/reports/create-edit',
    'POST '     . $API . '/reports'                                           => $API . '/reports/create-edit',
    'GET '      . $API . '/reports/download-pdf'                              => $API . '/reports/download-pdf',
    'PUT '      . $API . '/users/<id:\d+>/activate'                           => $API . '/users/activate',
    'PUT '      . $API . '/users/<id:\d+>/deactivate'                         => $API . '/users/deactivate',
    'GET '      . $API . '/users/<id:\d+>'                                    => $API . '/users/view',
    'DELETE '   . $API . '/users/<id:\d+>'                                    => $API . '/users/delete',
    'PUT '      . $API . '/users/<id:\d+>'                                    => $API . '/users/edit',
    'POST '     . $API . '/users'                                             => $API . '/users/create',
    'DELETE '   . $API . '/surveys/<id:\d+>'                                  => $API . '/surveys/delete',
    'GET '      . $API . '/surveys'                                           => $API . '/surveys/fetch',
    'POST '     . $API . '/surveys'                                           => $API . '/surveys/create',
    'GET '      . $API . '/surveys/<id:\d+>'                                  => $API . '/surveys/view',
    'GET '      . $API . '/profiles'                                          => $API . '/profiles/fetch',
    'POST '     . $API . '/users/<id:\d+>/work-history'                       => $API . '/users/work-history-add',
    'GET '      . $API . '/users/<id:\d+>/work-history'                       => $API . '/users/work-history-internal',
    'GET '      . $API . '/users/<slug:\w+(-\w+)*>/work-history'              => $API . '/users/work-history',
    'POST '     . $API . '/contracts'                                         => $API . '/contracts/create',
    'GET '      . $API . '/contracts'                                         => $API . '/contracts/fetch',
    'PUT '      . $API . '/contracts/<contract_id:\d+>'                       => $API . '/contracts/edit',
    'GET '      . $API . '/contracts/<id:\d+>'                                => $API . '/contracts/view',
    'DELETE '   . $API . '/contracts/<contract_id:\d+>'                       => $API . '/contracts/delete',
    'GET '      . $API . '/users/<id:\d+>/photo'                              => $API . '/users/view-photo',
    'GET '      . $API . '/users/<id:\d+>/sign'                               => $API . '/users/view-sign',
    'GET '      . $API . '/invoices'                                          => $API . '/invoices/fetch',
    'POST '     . $API . '/contracts/<id:\d+>/invoices'                       => $API . '/invoices/create',
    'GET '      . $API . '/invoices/<id:\d+>'                                 => $API . '/invoices/view',
    'GET '      . $API . '/invoices/<id:\d+>/download'                        => $API . '/invoices/download',
    'DELETE '   . $API . '/invoices/<invoice_id:\d+>'                         => $API . '/invoices/delete',
    'PUT '      . $API . '/invoices/<id:\d+>/paid'                            => $API . '/invoices/paid',
    'GET '      . $API . '/invoice-templates'                                 => $API . '/invoices/fetch-templates',
    'GET '      . $API . '/invoice-templates/<id:\d+>'                        => $API . '/invoices/fetch-templates',
    'PUT '      . $API . '/invoice-templates/<id:\d+>'                        => $API . '/invoices/update-templates',
    'PUT '      . $API . '/surveys/<survey_id:\d+>'                           => $API . '/surveys/edit',
    'GET '      . $API . '/users/access-token/<user_id:\d+>'                  => $API . '/users/access-token',
    'GET '      . $API . '/financial-reports/<id:\d+>'                        => $API . '/financial-reports/view',
    'POST '     . $API . '/financial-reports'                                 => $API . '/financial-reports/create',
    'GET '      . $API . '/financial-reports'                                 => $API . '/financial-reports/fetch',
    'PUT '      . $API . '/financial-reports/<id:\d+>'                        => $API . '/financial-reports/update',
    'PUT '      . $API . '/financial-reports/<id:\d+>/lock'                   => $API . '/financial-reports/lock',
    'POST '     . $API . '/financial-reports/<id:\d+>/income'                 => $API . '/financial-reports/income-add',
    'GET '      . $API . '/financial-reports/<id:\d+>/income'                 => $API . '/financial-reports/income-fetch',
    'DELETE '   . $API . '/financial-reports/<id:\d+>/income/<income_item_id:\d+>' => $API . '/financial-reports/income-delete',
    'GET '      . $API . '/financial-reports/<id:\d+>/bonuses'                => $API . '/financial-reports/bonuses-fetch',
    'GET '      . $API . '/financial-reports/yearly'                          => $API . '/financial-reports/yearly',
    'GET '      . $API . '/salary-reports'                                    => $API . '/salary-reports/fetch',
    'POST '     . $API . '/salary-reports'                                    => $API . '/salary-reports/create',
    'POST '     . $API . '/salary-reports/<id:\d+>/lists'                     => $API . '/salary-reports/lists-create',
    'GET '      . $API . '/salary-reports/<id:\d+>/lists'                     => $API . '/salary-reports/lists',
    'POST '     . $API . '/salary-reports/<id:\d+>/lists'                     => $API . '/salary-reports/lists-create',
    'PUT '      . $API . '/salary-reports/<sal_report_id:\d+>/lists/<id:\d+>' => $API . '/salary-reports/lists-update',
    'DELETE '   . $API . '/salary-reports/<sal_report_id:\d+>/lists/<id:\d+>' => $API . '/salary-reports/lists-delete',
    'GET '      . $API . '/salary-reports/<id:\d+>'                           => $API . '/salary-reports/download',
    'PUT '      . $API . '/financial-reports/<id:\d+>/unlock'                 => $API . '/financial-reports/unlock',
    'PUT '      . $API . '/settings/<key:\w+>'                                => $API . '/settings/update',
    'GET '      . $API . '/settings'                                          => $API . '/settings/fetch',
    'PUT '      . $API . '/reports/<id:\d+>/approve'                          => $API . '/reports/approve',
    'PUT '      . $API . '/reports/<id:\d+>/disapprove'                       => $API . '/reports/disapprove',
    'POST '     . $API . '/counterparties'                                    => $API . '/counterparties/create',
    'POST '     . $API . '/operations'                                        => $API . '/operations/create',
    'GET '      . $API . '/reference-book-items'                              => $API . '/reference-book-items/fetch',
    'PUT '      . $API . '/counterparties/<id:\d+>'                           => $API . '/counterparties/update',
    'DELETE '   . $API . '/counterparties/<id:\d+>'                           => $API . '/counterparties/delete',
    'GET '      . $API . '/counterparties'                                    => $API . '/counterparties/fetch',
    'GET '      . $API . '/businesses'                                        => $API . '/businesses/fetch',
    'GET '      . $API . '/businesses/<id:\d+>'                               => $API . '/businesses/fetch',
    'POST '     . $API . '/businesses'                                        => $API . '/businesses/create',
    'PUT '      . $API . '/businesses/<id:\d+>'                               => $API . '/businesses/update',
    'DELETE '   . $API . '/businesses/<id:\d+>'                               => $API . '/businesses/delete',
    'POST '     . $API . '/businesses/<id:\d+>/logo'                          => $API . '/businesses/upload-logo',
    'GET '      . $API . '/businesses/<id:\d+>/logo'                                   => $API . '/businesses/get-default-logo',
    'GET '      . $API . '/operation-types'                                   => $API . '/operation-types/fetch',
    'PUT '      . $API . '/operations/<id:\d+>'                               => $API . '/operations/update',
    'GET '      . $API . '/operations'                                        => $API . '/operations/fetch',
    'GET '      . $API . '/operations/<id:\d+>'                               => $API . '/operations/view',
    'POST '     . $API . '/invoices'                                          => $API . '/invoices/create',
    'GET '      . $API . '/fixed-assets'                                      => $API . '/fixed-assets/fetch',
    'GET '      . $API . '/balances'                                          => $API . '/balances/fetch',
    'GET '      . $API . '/resources'                                         => $API . '/resources/fetch',
    'PUT '      . $API . '/resources'                                         => $API . '/resources/iavailable',
    'POST '     . $API . '/resources'                                         => $API . '/resources/start',
    'POST '     . $API . '/emergency'                                         => $API . '/emergency/register',
    'DELETE '   . $API . '/operations/<id:\d+>'                               => $API . '/operations/delete',
    'POST '     . $API . '/delayed-salary'                                    => $API . '/delayed-salary/create',
    'GET '      . $API . '/sso/config'                                        => $API . '/sso/get-config',
    'POST '     . $API . '/sso/check'                                         => $API . '/sso/check',
    'GET '      . $API . '/reviews'                                           => $API . '/reviews/fetch',
    'GET '      . $API . '/reviews/<id:\d+>'                                  => $API . '/reviews/fetch',
    'POST '     . $API . '/businesses/<id:\d+>/methods'                       => $API . '/payment-methods/create',
    'GET '      . $API . '/businesses/<business_id:\d+>/methods'                       => $API . '/payment-methods/fetch',
    'GET '      . $API . '/businesses/<business_id:\d+>/methods/<id:\d+>'     => $API . '/payment-methods/fetch',
    'PUT '      . $API . '/businesses/<business_id:\d+>/methods/<payment_method_id:\d+>' => $API . '/payment-methods/update',
    'DELETE '   . $API . '/businesses/<business_id:\d+>/methods/<payment_method_id:\d+>' => $API . '/payment-methods/delete',
    'POST '     . $API . '/businesses/<business_id:\d+>/methods/<payment_method_id:\d+>' => $API . '/payment-methods/set-default',
    'GET '      . $API . '/email-templates'                                   => $API . '/email-templates/fetch',
    'GET '      . $API . '/email-templates/<id:\d+>'                          => $API . '/email-templates/fetch',
    'PUT '      . $API . '/email-templates/<id:\d+>'                          => $API . '/email-templates/update',
    'POST '     . $API . '/login-as-user/<user_id:\d+>'                      => $API . '/users/login-as-user',



    // General rules
    $API . '/<controller>'              => $API . '/<controller>',
    $API . '/<controller>/<action>'     => $API . '/<controller>/<action>',
    // Error rule for unknown methods
    [
        'pattern'   => $API . '/<route:(.*)>',
        'route'     => $API . '/default/error'
    ]
];
