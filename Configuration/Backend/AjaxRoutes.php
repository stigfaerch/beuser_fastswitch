<?php

declare(strict_types=1);

return [
    // Main backend rendering setup (previously called backend.php) for the TYPO3 Backend
    'beuser_fastswitch_backend_userlookup' => [
        'path'   => '/beuser_fastswitch/backend/userlookup',
        'target' => \JosefGlatz\BeuserFastswitch\Controller\BackendController::class . '::userLookupAction',
    ],
];
