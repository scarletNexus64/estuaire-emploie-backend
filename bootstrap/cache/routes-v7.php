<?php

app('router')->setCompiledRoutes(
    array (
  'compiled' => 
  array (
    0 => false,
    1 => 
    array (
      '/api/documentation' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'l5-swagger.default.api',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/docs' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'l5-swagger.default.docs',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/oauth2-callback' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'l5-swagger.default.oauth2_callback',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/sanctum/csrf-cookie' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'sanctum.csrf-cookie',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/register' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::8X8B4aT69zSqLgBV',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/login' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::19V8iPvAaxrvLmuh',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/password/forgot' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::FzcL4nq9p1p8aXkh',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/password/reset' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::7AydAqY2v4U2SZQ0',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/email/send-code' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::usYQgv0gSt68KTXn',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/email/verify-code' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::1Y6YxdquBDfUjmZb',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/maintenance-status' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::1sklKFNtPJznaW0o',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/jobs' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::S33iupPW0nJTPVbk',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::bAUsedUpnV2oOzUi',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/jobs/featured' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::8AZWC9Z4aoEvU7tr',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/companies' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::RdHhoizM61pe28D1',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::1M8DoRYG9CLiw1RW',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/companies/nearby' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::29mdkaZvmZxDFYX2',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/categories' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::j86tVLeGS42J7lZd',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/locations' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::6CuUbJ9HZlsDNwbh',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/contract-types' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::4Y0Gmjtw7xliULN2',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/subscription-plans' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::xpbjbA4wS2EsaYTU',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/advertisements' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::iLRlrf8zyjyDv51O',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/logout' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::6okumyqJpafpZW24',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/user' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::QCdOR8UnAoOGIWzB',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/user/role' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::fbJWABqcXbhVaInt',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/user/profile' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::X5KUDfA6nZGnT8qF',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/user/statistics' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::aGyIkezaTxNWPIPg',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/user/sync-role' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::VnG9eEKDJcq4VTEG',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/auth/switch-role' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::bczW3xsUI2g9ipTv',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/user/account' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::HVZrhWfAmBcMR9TA',
          ),
          1 => NULL,
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/me/subscription-status' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::g3mXsMJytcLclNAB',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/my-applications/stats' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ELmN0qnS4ir5YbFw',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/my-applications' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::IQbS3MYCUDylp5eW',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/favorites' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::OmZyav9KjGrMVrNH',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/notifications' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::X6SS6lsFdDxA94KC',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/notifications/unread-count' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::DMvsRXk72kbEArMd',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/notifications/read' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::vORzOYGt5VJ6R4Qj',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/send-fcm-token' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ESgUHqumkUpYwnRa',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/recruiter/jobs' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::2cVtqLBq8Fz8aqG9',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/recruiter/dashboard' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ZyEwaxINplawVW2x',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/recruiter/applications' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Bwq1RYXBFg20ybR4',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/recruiter/services/purchase/candidate-contact' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::2rPQznPC27EtJfEb',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/recruiter/services/purchase/diploma-verification' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::6gL3nL5UlcmAqiew',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/recruiter/services/purchase/skills-test' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Be4cCCufrLpOo6Ej',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/recruiter/services/access-status' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::c6YMJbrNsV9Td41Z',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/candidate/premium-services' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Pk9C3ui78ScGGY1N',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/exam-papers' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Xq3lgCc9uvBc4TP3',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/exam-papers/filters' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::NCaghKqbWSRe5I4k',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/exam-papers/stats' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::nHXoWYz4Y8B6Dysq',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/recruiter/skill-tests' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::jCsYMRY25IbZc4HM',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::tNfPUb2jesxrImSh',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/my-company' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::4FjqeNzvr6Y1BZ35',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::rxQCkvGRDj1FY0dw',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/payments/init' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::qp8vuYKlqFQxZAqu',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/payments/paypal/execute' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::pDtSRRJMwSWHITB7',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/subscriptions/activate' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::K4hmlFI3Tb4EfeTg',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/subscriptions/pay-with-wallet' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::0FeJrA78ty308v4P',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/my-subscription' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::XG6VrFivsU9bGptr',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/my-subscriptions' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::gtWpKnzIR4Vep18A',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/subscription/status' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::jS0hUUUntGqMNLAd',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/subscription/usage' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::aUhuHBvTZ2wE9NlE',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/wallet' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::g9QfeQTSQ5eLPDer',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/wallet/transactions' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::dkdxSOYRc2dAYuMT',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/wallet/recharge' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::MhBeaPB783wkFLHa',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/wallet/paypal/execute' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::qZ0XuJcKVk9ewBFI',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/wallet/paypal/create-native-order' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::R3QNbWSce8EO7VIs',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/wallet/paypal/capture-native-order' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::LFXVHIAMRzICmVik',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/wallet/can-pay' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Q2UGXloJS4RKCfc7',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/wallet/pay' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::WvsqMcPDs6dx2EpM',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/currencies' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::chuJELl43yeaDWNP',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/currencies/rates' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::3VHIQkEmu3HCqkTL',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/currencies/convert' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::yhIaA7rvIvO0UbiS',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/user/currency' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::GHaYV3esJ3cYAgmM',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/me/roles' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ed4IyccZqcjZOuFO',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/me/switch-role' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::djnjZfM8i0YEA2pY',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/me/features' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::MpRsu72dRLukt96V',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/me/sync-features' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::BE8YfwLHh0nYm4b6',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/conversations' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::4kFhhf9h2DoRoARz',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::QZ4u0iv21lOIL18u',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/conversations/messages' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ubFVmySyI2Nk2GTO',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/conversations/typing' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::gHosB6X2vQuSTqZK',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/presence/online' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::zkCa34jNz3BVTwIs',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/presence/offline' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::EeQXZIPeot7sz3sL',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/portfolio' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::4bmOLH45nHGxVCNS',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::imdcrYGJt6bLeMFG',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        2 => 
        array (
          0 => 
          array (
            '_route' => 'generated::VQJNQpMmhW5GvgiI',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        3 => 
        array (
          0 => 
          array (
            '_route' => 'generated::8MJFjcQTCFpp7AzS',
          ),
          1 => NULL,
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/portfolio/toggle-visibility' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::XSoA5NTKVhVp6JSt',
          ),
          1 => NULL,
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/portfolio/stats' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::wyc97CAWozh0VlVU',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/programs' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::vwbr0Xpo7yyig8A1',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/programs/check-access' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::14fyPJPKl6l6mg0J',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/broadcasting/auth' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ODA6eoNh8h8eCo2S',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/webhooks/freemopay' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api.webhooks.freemopay',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/up' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::og7UmNXNMUG13nIS',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::IzBwQKl35hyxnmc1',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/payment/success' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'payment.success',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/payment/cancel' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'payment.cancel',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/login' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.login',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.login.submit',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/logout' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.logout',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/dashboard' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.dashboard',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/profile' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.profile',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.profile.update',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/profile/password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.profile.password',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/companies' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.companies.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.companies.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/companies/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.companies.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/jobs' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.jobs.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.jobs.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/jobs/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.jobs.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/applications' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.applications.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/users' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.users.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/recruiters' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.recruiters.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.recruiters.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/recruiters/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.recruiters.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/admins' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.admins.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.admins.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/admins/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.admins.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/sections' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.sections.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.sections.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/sections/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.sections.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/programs' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.programs.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.programs.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/programs/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.programs.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/portfolios' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.portfolios.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/portfolios/export/csv' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.portfolios.export',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/skill-tests' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.skill-tests.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/settings' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.settings.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.settings.update',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/settings/categories' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.settings.categories',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.settings.categories.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/subscription-plans/recruiters' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.subscription-plans.recruiters.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.subscription-plans.recruiters.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/subscription-plans/recruiters/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.subscription-plans.recruiters.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/subscription-plans/job-seekers' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.subscription-plans.job-seekers.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.subscription-plans.job-seekers.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/subscription-plans/job-seekers/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.subscription-plans.job-seekers.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/subscriptions' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.subscriptions.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/manual-subscriptions' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.manual-subscriptions.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.manual-subscriptions.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/manual-subscriptions/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.manual-subscriptions.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/payments' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.payments.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/payments/export' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.payments.export',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/wallets' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.wallets.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/wallets/transactions' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.wallets.transactions',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/premium-services' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.premium-services.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.premium-services.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/premium-services/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.premium-services.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/recruiter-services' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.recruiter-services.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.recruiter-services.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/recruiter-services/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.recruiter-services.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/exam-papers' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.exam-papers.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.exam-papers.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/exam-papers/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.exam-papers.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/cvtheque' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.cvtheque.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/cvtheque/export/all' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.cvtheque.export',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/advertisements' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.advertisements.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.advertisements.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/advertisements/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.advertisements.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/financial-stats' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.financial-stats.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/financial-stats/export' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.financial-stats.export',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/service-config' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.service-config.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/service-config/whatsapp' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.service-config.update-whatsapp',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/service-config/nexah' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.service-config.update-nexah',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/service-config/freemopay' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.service-config.update-freemopay',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/service-config/paypal' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.service-config.update-paypal',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/service-config/preferences' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.service-config.update-preferences',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/service-config/test/whatsapp' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.service-config.test-whatsapp',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/service-config/test/nexah' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.service-config.test-nexah',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/service-config/test/freemopay' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.service-config.test-freemopay',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/service-config/test/paypal' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.service-config.test-paypal',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/service-config/send-test/whatsapp' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.service-config.send-test-whatsapp',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/service-config/send-test/nexah' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.service-config.send-test-nexah',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/service-config/clear-cache' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.service-config.clear-cache',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/announcements' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.announcements.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/announcements/send-to-user' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.announcements.send-to-user',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/announcements/send-to-all' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.announcements.send-to-all',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/announcements/user-count' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.announcements.user-count',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/fcm-tokens' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.fcm-tokens.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/fcm-tokens/export/csv' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.fcm-tokens.export',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/bank-account' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.bank-account.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/bank-account/verify-pin' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.bank-account.verify-pin',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/bank-account/withdrawal' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.bank-account.withdrawal',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.bank-account.initiate-withdrawal',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/bank-account/history' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.bank-account.history',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/maintenance' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.maintenance.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/maintenance/toggle' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.maintenance.toggle',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/broadcasting/auth' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::fhNw3PvgmL4WAUz2',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'POST' => 1,
            'HEAD' => 2,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
    ),
    2 => 
    array (
      0 => '{^(?|/docs/asset/([^/]++)(*:27)|/a(?|pi/(?|jobs/([^/]++)(?|(*:61)|/(?|apply(?|(*:80)|\\-with\\-test(*:99))|favorite(*:115)|is\\-favorite(*:135)|has\\-applied(*:155))|(*:164))|c(?|o(?|mpanies/([^/]++)(*:197)|nversations/([^/]++)/(?|messages(*:237)|read(*:249)))|andidate/(?|premium\\-services/(?|([^/]++)(*:300)|purchase(*:316)|my\\-services(*:336)|check\\-access/([^/]++)(*:366))|skill\\-tests/([^/]++)(?|(*:399)|/(?|calculate\\-score(*:427)|submit(*:441)))))|subscription\\-plans/([^/]++)(*:481)|a(?|dvertisements/([^/]++)/(?|impression(*:529)|click(*:542))|pplications/([^/]++)(?|(*:574)|/(?|status(*:592)|unlock\\-contact(*:615)|contact\\-status(*:638))))|notifications/([^/]++)(?|/read(*:679)|(*:687))|recruiter/(?|jobs/([^/]++)(*:722)|skill\\-tests/([^/]++)(?|(*:754)|/publish(*:770)|(*:778)))|exam\\-papers/([^/]++)(?|(*:812)|/(?|view(*:828)|download(*:844)))|p(?|ayments/([^/]++)/status(*:881)|ortfolio/by\\-slug/([^/]++)(*:915)|rograms/([^/]++)(*:939))|wallet/payment\\-status/([^/]++)(*:979)|me/features/([^/]++)(*:1007))|dmin/(?|c(?|ompanies/(?|([^/]++)(?|(*:1052)|/(?|edit(*:1069)|verify(*:1084)|suspend(*:1100))|(*:1110))|bulk\\-delete(*:1132)|verify\\-address(*:1156))|vtheque/([^/]++)(*:1182))|jobs/(?|([^/]++)(?|(*:1211)|/(?|edit(*:1228)|publish(*:1244)|send\\-(?|notifications(?|(*:1278)|\\-batch(*:1294))|emails\\-batch(*:1317))|feature(*:1334))|(*:1344))|bulk\\-delete(*:1366))|a(?|pplications/(?|([^/]++)(?|(*:1406)|/(?|status(*:1425)|verify\\-diploma(*:1449)))|bulk\\-delete(*:1472))|d(?|mins/(?|([^/]++)(?|(*:1505)|/(?|edit(*:1522)|permissions(*:1542))|(*:1552))|bulk\\-delete(*:1574))|vertisements/([^/]++)(?|/(?|edit(*:1616)|toggle(*:1631))|(*:1641))))|users/(?|([^/]++)(?|(*:1673))|bulk\\-delete(*:1695))|recruiter(?|s/(?|([^/]++)(?|(*:1733)|/edit(*:1747)|(*:1756))|bulk\\-delete(*:1778))|\\-services/([^/]++)(?|/(?|edit(*:1818)|toggle(*:1833))|(*:1843)))|s(?|e(?|ctions/([^/]++)(?|(*:1880)|/edit(*:1894)|(*:1903))|ttings/categories/([^/]++)(*:1939))|kill\\-tests/([^/]++)(?|(*:1972))|ubscription(?|\\-plans/(?|recruiters/([^/]++)(?|/edit(*:2034)|(*:2043))|job\\-seekers/([^/]++)(?|/edit(*:2082)|(*:2091)))|s/([^/]++)(?|(*:2115)|/(?|cancel(*:2134)|activate(*:2151))|(*:2161))))|p(?|r(?|ograms/([^/]++)(?|(*:2199)|/(?|edit(*:2216)|manage\\-steps(*:2238)|steps(?|/([^/]++)(?|(*:2267))|(*:2277)))|(*:2288))|emium\\-services/([^/]++)(?|/(?|edit(*:2333)|toggle(*:2348))|(*:2358)))|ortfolios/(?|([^/]++)(?|(*:2393)|/toggle\\-visibility(*:2421))|bulk\\-delete(*:2443))|ayments/([^/]++)(?|(*:2472)|/(?|verify(*:2491)|refund(*:2506))))|manual\\-subscriptions/([^/]++)(*:2548)|wallets/(?|([^/]++)(?|(*:2579)|/(?|adjust(?|(*:2601))|bonus(?|(*:2619))))|transactions/([^/]++)/refund(*:2659))|exam\\-papers/([^/]++)(?|/(?|edit(*:2701)|toggle(*:2716)|download(*:2733))|(*:2743))|fcm\\-tokens/(?|([^/]++)(?|(*:2779))|bulk\\-destroy(*:2802))|bank\\-account/withdrawal/([^/]++)/status(*:2852)))|/portfolio/([^/]++)(*:2882))/?$}sDu',
    ),
    3 => 
    array (
      27 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'l5-swagger.default.asset',
          ),
          1 => 
          array (
            0 => 'asset',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      61 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::IQ24XrCunmJPm4Xr',
          ),
          1 => 
          array (
            0 => 'job',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      80 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::cHAwqGDs21ooJ8ZY',
          ),
          1 => 
          array (
            0 => 'job',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      99 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::FwpJ6KgCtR77yto1',
          ),
          1 => 
          array (
            0 => 'job',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      115 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::gHtk32FXOhOc9w6Z',
          ),
          1 => 
          array (
            0 => 'job',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      135 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::TBjkeyt82ob5MjCf',
          ),
          1 => 
          array (
            0 => 'job',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      155 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::N44pgzVhc6x6bCeY',
          ),
          1 => 
          array (
            0 => 'job',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      164 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::mEuqKpZRZtCdcnHR',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::HclH1doQGbUOn5lf',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      197 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::3zr8BQHKnX1ljVH9',
          ),
          1 => 
          array (
            0 => 'company',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      237 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::3HTqDUgl9RjAkWSr',
          ),
          1 => 
          array (
            0 => 'conversationId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      249 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::EhgGBKDjl6jenOnL',
          ),
          1 => 
          array (
            0 => 'conversation',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      300 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::lvHipv1AEP4j7vKW',
          ),
          1 => 
          array (
            0 => 'slug',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      316 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::6e6en0GZF6UwEiLY',
          ),
          1 => 
          array (
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      336 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::POowuQ9ZppEGP8hk',
          ),
          1 => 
          array (
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      366 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Gao3YCONSgs0kZ9n',
          ),
          1 => 
          array (
            0 => 'slug',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      399 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::4c8JvbYlasEPB5Bu',
          ),
          1 => 
          array (
            0 => 'testId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      427 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::OHnDH1dSaJ4WtRt3',
          ),
          1 => 
          array (
            0 => 'testId',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      441 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::U2ZHdmZqEllTezhk',
          ),
          1 => 
          array (
            0 => 'testId',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      481 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::TyOBx54TjLUPrgXh',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      529 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::8lPM99qlr9iISs7W',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      542 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::cuAbvvz1Br26dhZJ',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      574 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::tU1FiFmI3B44whhe',
          ),
          1 => 
          array (
            0 => 'application',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::pLotE4QkmpkuWXit',
          ),
          1 => 
          array (
            0 => 'application',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      592 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::c45IVzvPrhiBuwlY',
          ),
          1 => 
          array (
            0 => 'application',
          ),
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      615 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::LiW437uQDveOUJ94',
          ),
          1 => 
          array (
            0 => 'application',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      638 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::L6sErGj574EiSAug',
          ),
          1 => 
          array (
            0 => 'application',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      679 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ZwyMTvEUpSgRzPkg',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      687 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::HS53JrUeHCkULyZq',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      722 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::kxK1lMmXrIwIocbH',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      754 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::OqOsO4FjaGIyc9OR',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::EMVzrH8UAAUirNjm',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      770 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::fhcI3stqzqHvqfq4',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      778 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::eJtLvP9D7LefsXYl',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      812 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::cOuLLJn0akKrnELZ',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      828 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::2jmfdL0aKeoxD4ji',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      844 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::9QwZPyLjdsZtK2ar',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      881 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::uc3fZJDruVeHllJ2',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      915 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::hzOkUYi2y4USx87s',
          ),
          1 => 
          array (
            0 => 'slug',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      939 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::P2BTeOjAjU1Gbtmq',
          ),
          1 => 
          array (
            0 => 'program',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      979 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::qjLxUI4OrKxU73ST',
          ),
          1 => 
          array (
            0 => 'paymentId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1007 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::aYY4XneRDP9EiXcL',
          ),
          1 => 
          array (
            0 => 'featureKey',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1052 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.companies.show',
          ),
          1 => 
          array (
            0 => 'company',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1069 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.companies.edit',
          ),
          1 => 
          array (
            0 => 'company',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1084 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.companies.verify',
          ),
          1 => 
          array (
            0 => 'company',
          ),
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1100 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.companies.suspend',
          ),
          1 => 
          array (
            0 => 'company',
          ),
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1110 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.companies.update',
          ),
          1 => 
          array (
            0 => 'company',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.companies.destroy',
          ),
          1 => 
          array (
            0 => 'company',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1132 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.companies.bulk-delete',
          ),
          1 => 
          array (
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1156 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.companies.verify-address',
          ),
          1 => 
          array (
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1182 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.cvtheque.show',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1211 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.jobs.show',
          ),
          1 => 
          array (
            0 => 'job',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1228 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.jobs.edit',
          ),
          1 => 
          array (
            0 => 'job',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1244 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.jobs.publish',
          ),
          1 => 
          array (
            0 => 'job',
          ),
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1278 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.jobs.send-notifications',
          ),
          1 => 
          array (
            0 => 'job',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1294 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.jobs.send-notifications-batch',
          ),
          1 => 
          array (
            0 => 'job',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1317 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.jobs.send-emails-batch',
          ),
          1 => 
          array (
            0 => 'job',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1334 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.jobs.feature',
          ),
          1 => 
          array (
            0 => 'job',
          ),
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1344 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.jobs.update',
          ),
          1 => 
          array (
            0 => 'job',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.jobs.destroy',
          ),
          1 => 
          array (
            0 => 'job',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1366 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.jobs.bulk-delete',
          ),
          1 => 
          array (
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1406 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.applications.show',
          ),
          1 => 
          array (
            0 => 'application',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1425 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.applications.status',
          ),
          1 => 
          array (
            0 => 'application',
          ),
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1449 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.applications.verify-diploma',
          ),
          1 => 
          array (
            0 => 'application',
          ),
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1472 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.applications.bulk-delete',
          ),
          1 => 
          array (
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1505 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.admins.show',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1522 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.admins.edit',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1542 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.admins.permissions',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1552 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.admins.update',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.admins.destroy',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1574 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.admins.bulk-delete',
          ),
          1 => 
          array (
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1616 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.advertisements.edit',
          ),
          1 => 
          array (
            0 => 'ad',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1631 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.advertisements.toggle',
          ),
          1 => 
          array (
            0 => 'ad',
          ),
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1641 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.advertisements.update',
          ),
          1 => 
          array (
            0 => 'ad',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.advertisements.destroy',
          ),
          1 => 
          array (
            0 => 'ad',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1673 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.users.show',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.users.destroy',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1695 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.users.bulk-delete',
          ),
          1 => 
          array (
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1733 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.recruiters.show',
          ),
          1 => 
          array (
            0 => 'recruiter',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1747 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.recruiters.edit',
          ),
          1 => 
          array (
            0 => 'recruiter',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1756 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.recruiters.update',
          ),
          1 => 
          array (
            0 => 'recruiter',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.recruiters.destroy',
          ),
          1 => 
          array (
            0 => 'recruiter',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1778 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.recruiters.bulk-delete',
          ),
          1 => 
          array (
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1818 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.recruiter-services.edit',
          ),
          1 => 
          array (
            0 => 'service',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1833 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.recruiter-services.toggle',
          ),
          1 => 
          array (
            0 => 'service',
          ),
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1843 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.recruiter-services.update',
          ),
          1 => 
          array (
            0 => 'service',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.recruiter-services.destroy',
          ),
          1 => 
          array (
            0 => 'service',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        2 => 
        array (
          0 => 
          array (
            '_route' => 'admin.recruiter-services.show',
          ),
          1 => 
          array (
            0 => 'service',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1880 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.sections.show',
          ),
          1 => 
          array (
            0 => 'section',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1894 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.sections.edit',
          ),
          1 => 
          array (
            0 => 'section',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1903 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.sections.update',
          ),
          1 => 
          array (
            0 => 'section',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.sections.destroy',
          ),
          1 => 
          array (
            0 => 'section',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1939 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.settings.categories.delete',
          ),
          1 => 
          array (
            0 => 'category',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1972 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.skill-tests.show',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.skill-tests.destroy',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2034 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.subscription-plans.recruiters.edit',
          ),
          1 => 
          array (
            0 => 'plan',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2043 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.subscription-plans.recruiters.update',
          ),
          1 => 
          array (
            0 => 'plan',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.subscription-plans.recruiters.destroy',
          ),
          1 => 
          array (
            0 => 'plan',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2082 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.subscription-plans.job-seekers.edit',
          ),
          1 => 
          array (
            0 => 'plan',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2091 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.subscription-plans.job-seekers.update',
          ),
          1 => 
          array (
            0 => 'plan',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.subscription-plans.job-seekers.destroy',
          ),
          1 => 
          array (
            0 => 'plan',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2115 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.subscriptions.show',
          ),
          1 => 
          array (
            0 => 'subscription',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2134 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.subscriptions.cancel',
          ),
          1 => 
          array (
            0 => 'subscription',
          ),
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2151 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.subscriptions.activate',
          ),
          1 => 
          array (
            0 => 'subscription',
          ),
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2161 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.subscriptions.destroy',
          ),
          1 => 
          array (
            0 => 'subscription',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2199 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.programs.show',
          ),
          1 => 
          array (
            0 => 'program',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2216 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.programs.edit',
          ),
          1 => 
          array (
            0 => 'program',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2238 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.programs.manage-steps',
          ),
          1 => 
          array (
            0 => 'program',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2267 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.programs.get-step',
          ),
          1 => 
          array (
            0 => 'program',
            1 => 'step',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.programs.update-step',
          ),
          1 => 
          array (
            0 => 'program',
            1 => 'step',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        2 => 
        array (
          0 => 
          array (
            '_route' => 'admin.programs.destroy-step',
          ),
          1 => 
          array (
            0 => 'program',
            1 => 'step',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2277 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.programs.store-step',
          ),
          1 => 
          array (
            0 => 'program',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2288 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.programs.update',
          ),
          1 => 
          array (
            0 => 'program',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.programs.destroy',
          ),
          1 => 
          array (
            0 => 'program',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2333 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.premium-services.edit',
          ),
          1 => 
          array (
            0 => 'service',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2348 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.premium-services.toggle',
          ),
          1 => 
          array (
            0 => 'service',
          ),
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2358 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.premium-services.update',
          ),
          1 => 
          array (
            0 => 'service',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.premium-services.destroy',
          ),
          1 => 
          array (
            0 => 'service',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        2 => 
        array (
          0 => 
          array (
            '_route' => 'admin.premium-services.show',
          ),
          1 => 
          array (
            0 => 'service',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2393 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.portfolios.show',
          ),
          1 => 
          array (
            0 => 'portfolio',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.portfolios.destroy',
          ),
          1 => 
          array (
            0 => 'portfolio',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2421 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.portfolios.toggle-visibility',
          ),
          1 => 
          array (
            0 => 'portfolio',
          ),
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2443 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.portfolios.bulk-delete',
          ),
          1 => 
          array (
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2472 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.payments.show',
          ),
          1 => 
          array (
            0 => 'payment',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2491 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.payments.verify',
          ),
          1 => 
          array (
            0 => 'payment',
          ),
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2506 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.payments.refund',
          ),
          1 => 
          array (
            0 => 'payment',
          ),
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2548 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.manual-subscriptions.show',
          ),
          1 => 
          array (
            0 => 'assignment',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2579 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.wallets.show',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2601 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.wallets.adjust',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.wallets.adjust.submit',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2619 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.wallets.bonus',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.wallets.bonus.submit',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2659 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.wallets.refund',
          ),
          1 => 
          array (
            0 => 'transaction',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2701 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.exam-papers.edit',
          ),
          1 => 
          array (
            0 => 'examPaper',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2716 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.exam-papers.toggle',
          ),
          1 => 
          array (
            0 => 'examPaper',
          ),
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2733 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.exam-papers.download',
          ),
          1 => 
          array (
            0 => 'examPaper',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2743 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.exam-papers.update',
          ),
          1 => 
          array (
            0 => 'examPaper',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.exam-papers.destroy',
          ),
          1 => 
          array (
            0 => 'examPaper',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        2 => 
        array (
          0 => 
          array (
            '_route' => 'admin.exam-papers.show',
          ),
          1 => 
          array (
            0 => 'examPaper',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2779 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.fcm-tokens.show',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.fcm-tokens.destroy',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2802 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.fcm-tokens.bulk-destroy',
          ),
          1 => 
          array (
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2852 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.bank-account.withdrawal-status',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2882 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'portfolio.show',
          ),
          1 => 
          array (
            0 => 'slug',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => NULL,
          1 => NULL,
          2 => NULL,
          3 => NULL,
          4 => false,
          5 => false,
          6 => 0,
        ),
      ),
    ),
    4 => NULL,
  ),
  'attributes' => 
  array (
    'l5-swagger.default.api' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/documentation',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'L5Swagger\\Http\\Middleware\\Config',
        ),
        'l5-swagger.documentation' => 'default',
        'as' => 'l5-swagger.default.api',
        'uses' => '\\L5Swagger\\Http\\Controllers\\SwaggerController@api',
        'controller' => '\\L5Swagger\\Http\\Controllers\\SwaggerController@api',
        'namespace' => 'L5Swagger',
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'l5-swagger.default.docs' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'docs',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'L5Swagger\\Http\\Middleware\\Config',
        ),
        'l5-swagger.documentation' => 'default',
        'as' => 'l5-swagger.default.docs',
        'uses' => '\\L5Swagger\\Http\\Controllers\\SwaggerController@docs',
        'controller' => '\\L5Swagger\\Http\\Controllers\\SwaggerController@docs',
        'namespace' => 'L5Swagger',
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'l5-swagger.default.asset' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'docs/asset/{asset}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'L5Swagger\\Http\\Middleware\\Config',
        ),
        'l5-swagger.documentation' => 'default',
        'as' => 'l5-swagger.default.asset',
        'uses' => '\\L5Swagger\\Http\\Controllers\\SwaggerAssetController@index',
        'controller' => '\\L5Swagger\\Http\\Controllers\\SwaggerAssetController@index',
        'namespace' => 'L5Swagger',
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'l5-swagger.default.oauth2_callback' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/oauth2-callback',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'L5Swagger\\Http\\Middleware\\Config',
        ),
        'l5-swagger.documentation' => 'default',
        'as' => 'l5-swagger.default.oauth2_callback',
        'uses' => '\\L5Swagger\\Http\\Controllers\\SwaggerController@oauth2Callback',
        'controller' => '\\L5Swagger\\Http\\Controllers\\SwaggerController@oauth2Callback',
        'namespace' => 'L5Swagger',
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'sanctum.csrf-cookie' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'sanctum/csrf-cookie',
      'action' => 
      array (
        'uses' => 'Laravel\\Sanctum\\Http\\Controllers\\CsrfCookieController@show',
        'controller' => 'Laravel\\Sanctum\\Http\\Controllers\\CsrfCookieController@show',
        'namespace' => NULL,
        'prefix' => 'sanctum',
        'where' => 
        array (
        ),
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'sanctum.csrf-cookie',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::8X8B4aT69zSqLgBV' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/register',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AuthController@register',
        'controller' => 'App\\Http\\Controllers\\Api\\AuthController@register',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::8X8B4aT69zSqLgBV',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::19V8iPvAaxrvLmuh' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/login',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AuthController@login',
        'controller' => 'App\\Http\\Controllers\\Api\\AuthController@login',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::19V8iPvAaxrvLmuh',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::FzcL4nq9p1p8aXkh' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/password/forgot',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AuthController@forgotPassword',
        'controller' => 'App\\Http\\Controllers\\Api\\AuthController@forgotPassword',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::FzcL4nq9p1p8aXkh',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::7AydAqY2v4U2SZQ0' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/password/reset',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AuthController@resetPassword',
        'controller' => 'App\\Http\\Controllers\\Api\\AuthController@resetPassword',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::7AydAqY2v4U2SZQ0',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::usYQgv0gSt68KTXn' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/email/send-code',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\EmailVerificationController@sendCode',
        'controller' => 'App\\Http\\Controllers\\Api\\EmailVerificationController@sendCode',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::usYQgv0gSt68KTXn',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::1Y6YxdquBDfUjmZb' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/email/verify-code',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\EmailVerificationController@verifyCode',
        'controller' => 'App\\Http\\Controllers\\Api\\EmailVerificationController@verifyCode',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::1Y6YxdquBDfUjmZb',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::1sklKFNtPJznaW0o' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/maintenance-status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MaintenanceModeController@status',
        'controller' => 'App\\Http\\Controllers\\Api\\MaintenanceModeController@status',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::1sklKFNtPJznaW0o',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::S33iupPW0nJTPVbk' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/jobs',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\JobController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\JobController@index',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::S33iupPW0nJTPVbk',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::8AZWC9Z4aoEvU7tr' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/jobs/featured',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\JobController@featured',
        'controller' => 'App\\Http\\Controllers\\Api\\JobController@featured',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::8AZWC9Z4aoEvU7tr',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::IQ24XrCunmJPm4Xr' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/jobs/{job}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\JobController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\JobController@show',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::IQ24XrCunmJPm4Xr',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::RdHhoizM61pe28D1' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/companies',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\CompanyController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\CompanyController@index',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::RdHhoizM61pe28D1',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::29mdkaZvmZxDFYX2' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/companies/nearby',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\CompanyController@getNearbyCompanies',
        'controller' => 'App\\Http\\Controllers\\Api\\CompanyController@getNearbyCompanies',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::29mdkaZvmZxDFYX2',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::3zr8BQHKnX1ljVH9' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/companies/{company}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\CompanyController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\CompanyController@show',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::3zr8BQHKnX1ljVH9',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::j86tVLeGS42J7lZd' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/categories',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\CategoryController@categories',
        'controller' => 'App\\Http\\Controllers\\Api\\CategoryController@categories',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::j86tVLeGS42J7lZd',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::6CuUbJ9HZlsDNwbh' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/locations',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\CategoryController@locations',
        'controller' => 'App\\Http\\Controllers\\Api\\CategoryController@locations',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::6CuUbJ9HZlsDNwbh',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::4Y0Gmjtw7xliULN2' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/contract-types',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\CategoryController@contractTypes',
        'controller' => 'App\\Http\\Controllers\\Api\\CategoryController@contractTypes',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::4Y0Gmjtw7xliULN2',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::xpbjbA4wS2EsaYTU' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/subscription-plans',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SubscriptionPlanController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\SubscriptionPlanController@index',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::xpbjbA4wS2EsaYTU',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::TyOBx54TjLUPrgXh' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/subscription-plans/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SubscriptionPlanController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\SubscriptionPlanController@show',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::TyOBx54TjLUPrgXh',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::iLRlrf8zyjyDv51O' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/advertisements',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AdvertisementController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\AdvertisementController@index',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::iLRlrf8zyjyDv51O',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::8lPM99qlr9iISs7W' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/advertisements/{id}/impression',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AdvertisementController@recordImpression',
        'controller' => 'App\\Http\\Controllers\\Api\\AdvertisementController@recordImpression',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::8lPM99qlr9iISs7W',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::cuAbvvz1Br26dhZJ' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/advertisements/{id}/click',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AdvertisementController@recordClick',
        'controller' => 'App\\Http\\Controllers\\Api\\AdvertisementController@recordClick',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::cuAbvvz1Br26dhZJ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::6okumyqJpafpZW24' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/logout',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AuthController@logout',
        'controller' => 'App\\Http\\Controllers\\Api\\AuthController@logout',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::6okumyqJpafpZW24',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::QCdOR8UnAoOGIWzB' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/user',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AuthController@user',
        'controller' => 'App\\Http\\Controllers\\Api\\AuthController@user',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::QCdOR8UnAoOGIWzB',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::fbJWABqcXbhVaInt' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/user/role',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AuthController@updateRole',
        'controller' => 'App\\Http\\Controllers\\Api\\AuthController@updateRole',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::fbJWABqcXbhVaInt',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::X5KUDfA6nZGnT8qF' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/user/profile',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AuthController@updateProfile',
        'controller' => 'App\\Http\\Controllers\\Api\\AuthController@updateProfile',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::X5KUDfA6nZGnT8qF',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::aGyIkezaTxNWPIPg' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/user/statistics',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AuthController@statistics',
        'controller' => 'App\\Http\\Controllers\\Api\\AuthController@statistics',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::aGyIkezaTxNWPIPg',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::VnG9eEKDJcq4VTEG' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/user/sync-role',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AuthController@syncRoleWithSubscription',
        'controller' => 'App\\Http\\Controllers\\Api\\AuthController@syncRoleWithSubscription',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::VnG9eEKDJcq4VTEG',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::bczW3xsUI2g9ipTv' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/auth/switch-role',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AuthController@switchRole',
        'controller' => 'App\\Http\\Controllers\\Api\\AuthController@switchRole',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::bczW3xsUI2g9ipTv',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::HVZrhWfAmBcMR9TA' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/user/account',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AuthController@deleteAccount',
        'controller' => 'App\\Http\\Controllers\\Api\\AuthController@deleteAccount',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::HVZrhWfAmBcMR9TA',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::g3mXsMJytcLclNAB' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/me/subscription-status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AuthController@getSubscriptionStatus',
        'controller' => 'App\\Http\\Controllers\\Api\\AuthController@getSubscriptionStatus',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::g3mXsMJytcLclNAB',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::cHAwqGDs21ooJ8ZY' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/jobs/{job}/apply',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ApplicationController@apply',
        'controller' => 'App\\Http\\Controllers\\Api\\ApplicationController@apply',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::cHAwqGDs21ooJ8ZY',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::FwpJ6KgCtR77yto1' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/jobs/{job}/apply-with-test',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ApplicationController@applyWithTest',
        'controller' => 'App\\Http\\Controllers\\Api\\ApplicationController@applyWithTest',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::FwpJ6KgCtR77yto1',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ELmN0qnS4ir5YbFw' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/my-applications/stats',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ApplicationController@myApplicationsStats',
        'controller' => 'App\\Http\\Controllers\\Api\\ApplicationController@myApplicationsStats',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::ELmN0qnS4ir5YbFw',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::IQbS3MYCUDylp5eW' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/my-applications',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ApplicationController@myApplications',
        'controller' => 'App\\Http\\Controllers\\Api\\ApplicationController@myApplications',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::IQbS3MYCUDylp5eW',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::tU1FiFmI3B44whhe' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/applications/{application}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ApplicationController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\ApplicationController@show',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::tU1FiFmI3B44whhe',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::pLotE4QkmpkuWXit' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/applications/{application}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ApplicationController@destroy',
        'controller' => 'App\\Http\\Controllers\\Api\\ApplicationController@destroy',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::pLotE4QkmpkuWXit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::OmZyav9KjGrMVrNH' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/favorites',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\FavoriteController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\FavoriteController@index',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::OmZyav9KjGrMVrNH',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::gHtk32FXOhOc9w6Z' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/jobs/{job}/favorite',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\FavoriteController@toggle',
        'controller' => 'App\\Http\\Controllers\\Api\\FavoriteController@toggle',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::gHtk32FXOhOc9w6Z',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::TBjkeyt82ob5MjCf' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/jobs/{job}/is-favorite',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\FavoriteController@isFavorite',
        'controller' => 'App\\Http\\Controllers\\Api\\FavoriteController@isFavorite',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::TBjkeyt82ob5MjCf',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::N44pgzVhc6x6bCeY' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/jobs/{job}/has-applied',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\JobController@hasApplied',
        'controller' => 'App\\Http\\Controllers\\Api\\JobController@hasApplied',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::N44pgzVhc6x6bCeY',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::X6SS6lsFdDxA94KC' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/notifications',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\NotificationController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\NotificationController@index',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::X6SS6lsFdDxA94KC',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::DMvsRXk72kbEArMd' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/notifications/unread-count',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\NotificationController@unreadCount',
        'controller' => 'App\\Http\\Controllers\\Api\\NotificationController@unreadCount',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::DMvsRXk72kbEArMd',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ZwyMTvEUpSgRzPkg' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/notifications/{id}/read',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\NotificationController@markAsRead',
        'controller' => 'App\\Http\\Controllers\\Api\\NotificationController@markAsRead',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::ZwyMTvEUpSgRzPkg',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::vORzOYGt5VJ6R4Qj' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/notifications/read',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\NotificationController@markAllAsRead',
        'controller' => 'App\\Http\\Controllers\\Api\\NotificationController@markAllAsRead',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::vORzOYGt5VJ6R4Qj',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::HS53JrUeHCkULyZq' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/notifications/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\NotificationController@destroy',
        'controller' => 'App\\Http\\Controllers\\Api\\NotificationController@destroy',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::HS53JrUeHCkULyZq',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ESgUHqumkUpYwnRa' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/send-fcm-token',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\UserController@saveFcmToken',
        'controller' => 'App\\Http\\Controllers\\Admin\\UserController@saveFcmToken',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::ESgUHqumkUpYwnRa',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::bAUsedUpnV2oOzUi' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/jobs',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'subscription:can_post_job',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\JobController@store',
        'controller' => 'App\\Http\\Controllers\\Api\\JobController@store',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::bAUsedUpnV2oOzUi',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::mEuqKpZRZtCdcnHR' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/jobs/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'subscription:valid',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\JobController@update',
        'controller' => 'App\\Http\\Controllers\\Api\\JobController@update',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::mEuqKpZRZtCdcnHR',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::HclH1doQGbUOn5lf' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/jobs/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'subscription:valid',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\JobController@destroy',
        'controller' => 'App\\Http\\Controllers\\Api\\JobController@destroy',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::HclH1doQGbUOn5lf',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::2cVtqLBq8Fz8aqG9' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/recruiter/jobs',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'subscription:valid',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\JobController@myJobs',
        'controller' => 'App\\Http\\Controllers\\Api\\JobController@myJobs',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::2cVtqLBq8Fz8aqG9',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::kxK1lMmXrIwIocbH' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/recruiter/jobs/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'subscription:valid',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\JobController@showRecruiterJob',
        'controller' => 'App\\Http\\Controllers\\Api\\JobController@showRecruiterJob',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::kxK1lMmXrIwIocbH',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ZyEwaxINplawVW2x' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/recruiter/dashboard',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'subscription:valid',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\JobController@dashboard',
        'controller' => 'App\\Http\\Controllers\\Api\\JobController@dashboard',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::ZyEwaxINplawVW2x',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Bwq1RYXBFg20ybR4' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/recruiter/applications',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'subscription:valid',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ApplicationController@receivedApplications',
        'controller' => 'App\\Http\\Controllers\\Api\\ApplicationController@receivedApplications',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::Bwq1RYXBFg20ybR4',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::c45IVzvPrhiBuwlY' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'api/applications/{application}/status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'subscription:valid',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ApplicationController@updateStatus',
        'controller' => 'App\\Http\\Controllers\\Api\\ApplicationController@updateStatus',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::c45IVzvPrhiBuwlY',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::LiW437uQDveOUJ94' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/applications/{application}/unlock-contact',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'subscription:can_contact',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ApplicationController@unlockContact',
        'controller' => 'App\\Http\\Controllers\\Api\\ApplicationController@unlockContact',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::LiW437uQDveOUJ94',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::L6sErGj574EiSAug' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/applications/{application}/contact-status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ApplicationController@contactStatus',
        'controller' => 'App\\Http\\Controllers\\Api\\ApplicationController@contactStatus',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::L6sErGj574EiSAug',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::2rPQznPC27EtJfEb' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/recruiter/services/purchase/candidate-contact',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'subscription:valid',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\RecruiterServicePurchaseController@purchaseCandidateContact',
        'controller' => 'App\\Http\\Controllers\\Api\\RecruiterServicePurchaseController@purchaseCandidateContact',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::2rPQznPC27EtJfEb',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::6gL3nL5UlcmAqiew' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/recruiter/services/purchase/diploma-verification',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'subscription:valid',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\RecruiterServicePurchaseController@purchaseDiplomaVerification',
        'controller' => 'App\\Http\\Controllers\\Api\\RecruiterServicePurchaseController@purchaseDiplomaVerification',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::6gL3nL5UlcmAqiew',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Be4cCCufrLpOo6Ej' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/recruiter/services/purchase/skills-test',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'subscription:valid',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\RecruiterServicePurchaseController@purchaseSkillsTest',
        'controller' => 'App\\Http\\Controllers\\Api\\RecruiterServicePurchaseController@purchaseSkillsTest',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::Be4cCCufrLpOo6Ej',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::c6YMJbrNsV9Td41Z' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/recruiter/services/access-status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\RecruiterServicePurchaseController@checkAccessStatus',
        'controller' => 'App\\Http\\Controllers\\Api\\RecruiterServicePurchaseController@checkAccessStatus',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::c6YMJbrNsV9Td41Z',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Pk9C3ui78ScGGY1N' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/candidate/premium-services',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\CandidatePremiumServiceController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\CandidatePremiumServiceController@index',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::Pk9C3ui78ScGGY1N',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::lvHipv1AEP4j7vKW' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/candidate/premium-services/{slug}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\CandidatePremiumServiceController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\CandidatePremiumServiceController@show',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::lvHipv1AEP4j7vKW',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::6e6en0GZF6UwEiLY' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/candidate/premium-services/purchase',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\CandidatePremiumServiceController@purchase',
        'controller' => 'App\\Http\\Controllers\\Api\\CandidatePremiumServiceController@purchase',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::6e6en0GZF6UwEiLY',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::POowuQ9ZppEGP8hk' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/candidate/premium-services/my-services',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\CandidatePremiumServiceController@myServices',
        'controller' => 'App\\Http\\Controllers\\Api\\CandidatePremiumServiceController@myServices',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::POowuQ9ZppEGP8hk',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Gao3YCONSgs0kZ9n' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/candidate/premium-services/check-access/{slug}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\CandidatePremiumServiceController@checkAccess',
        'controller' => 'App\\Http\\Controllers\\Api\\CandidatePremiumServiceController@checkAccess',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::Gao3YCONSgs0kZ9n',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Xq3lgCc9uvBc4TP3' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/exam-papers',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ExamPaperApiController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\ExamPaperApiController@index',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::Xq3lgCc9uvBc4TP3',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::NCaghKqbWSRe5I4k' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/exam-papers/filters',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ExamPaperApiController@filters',
        'controller' => 'App\\Http\\Controllers\\Api\\ExamPaperApiController@filters',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::NCaghKqbWSRe5I4k',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::nHXoWYz4Y8B6Dysq' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/exam-papers/stats',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ExamPaperApiController@stats',
        'controller' => 'App\\Http\\Controllers\\Api\\ExamPaperApiController@stats',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::nHXoWYz4Y8B6Dysq',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::cOuLLJn0akKrnELZ' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/exam-papers/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ExamPaperApiController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\ExamPaperApiController@show',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::cOuLLJn0akKrnELZ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::2jmfdL0aKeoxD4ji' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/exam-papers/{id}/view',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ExamPaperApiController@viewPdf',
        'controller' => 'App\\Http\\Controllers\\Api\\ExamPaperApiController@viewPdf',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::2jmfdL0aKeoxD4ji',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::9QwZPyLjdsZtK2ar' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/exam-papers/{id}/download',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ExamPaperApiController@download',
        'controller' => 'App\\Http\\Controllers\\Api\\ExamPaperApiController@download',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::9QwZPyLjdsZtK2ar',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::jCsYMRY25IbZc4HM' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/recruiter/skill-tests',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'subscription:valid',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\RecruiterSkillTestController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\RecruiterSkillTestController@index',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::jCsYMRY25IbZc4HM',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::OqOsO4FjaGIyc9OR' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/recruiter/skill-tests/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'subscription:valid',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\RecruiterSkillTestController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\RecruiterSkillTestController@show',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::OqOsO4FjaGIyc9OR',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::tNfPUb2jesxrImSh' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/recruiter/skill-tests',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'subscription:valid',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\RecruiterSkillTestController@store',
        'controller' => 'App\\Http\\Controllers\\Api\\RecruiterSkillTestController@store',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::tNfPUb2jesxrImSh',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::EMVzrH8UAAUirNjm' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/recruiter/skill-tests/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'subscription:valid',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\RecruiterSkillTestController@update',
        'controller' => 'App\\Http\\Controllers\\Api\\RecruiterSkillTestController@update',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::EMVzrH8UAAUirNjm',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::fhcI3stqzqHvqfq4' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/recruiter/skill-tests/{id}/publish',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'subscription:valid',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\RecruiterSkillTestController@publish',
        'controller' => 'App\\Http\\Controllers\\Api\\RecruiterSkillTestController@publish',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::fhcI3stqzqHvqfq4',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::eJtLvP9D7LefsXYl' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/recruiter/skill-tests/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'subscription:valid',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\RecruiterSkillTestController@destroy',
        'controller' => 'App\\Http\\Controllers\\Api\\RecruiterSkillTestController@destroy',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::eJtLvP9D7LefsXYl',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::4c8JvbYlasEPB5Bu' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/candidate/skill-tests/{testId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\RecruiterSkillTestController@getTestForCandidate',
        'controller' => 'App\\Http\\Controllers\\Api\\RecruiterSkillTestController@getTestForCandidate',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::4c8JvbYlasEPB5Bu',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::OHnDH1dSaJ4WtRt3' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/candidate/skill-tests/{testId}/calculate-score',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\RecruiterSkillTestController@calculateScoreOnly',
        'controller' => 'App\\Http\\Controllers\\Api\\RecruiterSkillTestController@calculateScoreOnly',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::OHnDH1dSaJ4WtRt3',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::U2ZHdmZqEllTezhk' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/candidate/skill-tests/{testId}/submit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\RecruiterSkillTestController@submitTestResults',
        'controller' => 'App\\Http\\Controllers\\Api\\RecruiterSkillTestController@submitTestResults',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::U2ZHdmZqEllTezhk',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::1M8DoRYG9CLiw1RW' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/companies',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\CompanyController@store',
        'controller' => 'App\\Http\\Controllers\\Api\\CompanyController@store',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::1M8DoRYG9CLiw1RW',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::4FjqeNzvr6Y1BZ35' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/my-company',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\CompanyController@myCompany',
        'controller' => 'App\\Http\\Controllers\\Api\\CompanyController@myCompany',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::4FjqeNzvr6Y1BZ35',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::rxQCkvGRDj1FY0dw' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/my-company',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\CompanyController@updateMyCompany',
        'controller' => 'App\\Http\\Controllers\\Api\\CompanyController@updateMyCompany',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::rxQCkvGRDj1FY0dw',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::qp8vuYKlqFQxZAqu' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/payments/init',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SubscriptionPlanController@initPayment',
        'controller' => 'App\\Http\\Controllers\\Api\\SubscriptionPlanController@initPayment',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::qp8vuYKlqFQxZAqu',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::pDtSRRJMwSWHITB7' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/payments/paypal/execute',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SubscriptionPlanController@executePayPalPayment',
        'controller' => 'App\\Http\\Controllers\\Api\\SubscriptionPlanController@executePayPalPayment',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::pDtSRRJMwSWHITB7',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::uc3fZJDruVeHllJ2' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/payments/{id}/status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SubscriptionPlanController@checkPaymentStatus',
        'controller' => 'App\\Http\\Controllers\\Api\\SubscriptionPlanController@checkPaymentStatus',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::uc3fZJDruVeHllJ2',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::K4hmlFI3Tb4EfeTg' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/subscriptions/activate',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SubscriptionPlanController@activate',
        'controller' => 'App\\Http\\Controllers\\Api\\SubscriptionPlanController@activate',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::K4hmlFI3Tb4EfeTg',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::0FeJrA78ty308v4P' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/subscriptions/pay-with-wallet',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SubscriptionPlanController@payWithWallet',
        'controller' => 'App\\Http\\Controllers\\Api\\SubscriptionPlanController@payWithWallet',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::0FeJrA78ty308v4P',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::XG6VrFivsU9bGptr' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/my-subscription',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SubscriptionPlanController@mySubscription',
        'controller' => 'App\\Http\\Controllers\\Api\\SubscriptionPlanController@mySubscription',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::XG6VrFivsU9bGptr',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::gtWpKnzIR4Vep18A' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/my-subscriptions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SubscriptionPlanController@mySubscriptions',
        'controller' => 'App\\Http\\Controllers\\Api\\SubscriptionPlanController@mySubscriptions',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::gtWpKnzIR4Vep18A',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::jS0hUUUntGqMNLAd' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/subscription/status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SubscriptionPlanController@subscriptionStatus',
        'controller' => 'App\\Http\\Controllers\\Api\\SubscriptionPlanController@subscriptionStatus',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::jS0hUUUntGqMNLAd',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::aUhuHBvTZ2wE9NlE' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/subscription/usage',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SubscriptionPlanController@subscriptionUsage',
        'controller' => 'App\\Http\\Controllers\\Api\\SubscriptionPlanController@subscriptionUsage',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::aUhuHBvTZ2wE9NlE',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::g9QfeQTSQ5eLPDer' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/wallet',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\WalletController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\WalletController@index',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::g9QfeQTSQ5eLPDer',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::dkdxSOYRc2dAYuMT' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/wallet/transactions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\WalletController@transactions',
        'controller' => 'App\\Http\\Controllers\\Api\\WalletController@transactions',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::dkdxSOYRc2dAYuMT',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::MhBeaPB783wkFLHa' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/wallet/recharge',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\WalletController@recharge',
        'controller' => 'App\\Http\\Controllers\\Api\\WalletController@recharge',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::MhBeaPB783wkFLHa',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::qZ0XuJcKVk9ewBFI' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/wallet/paypal/execute',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\WalletController@executePayPalPayment',
        'controller' => 'App\\Http\\Controllers\\Api\\WalletController@executePayPalPayment',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::qZ0XuJcKVk9ewBFI',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::R3QNbWSce8EO7VIs' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/wallet/paypal/create-native-order',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\WalletController@createNativePayPalOrder',
        'controller' => 'App\\Http\\Controllers\\Api\\WalletController@createNativePayPalOrder',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::R3QNbWSce8EO7VIs',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::LFXVHIAMRzICmVik' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/wallet/paypal/capture-native-order',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\WalletController@captureNativePayPalOrder',
        'controller' => 'App\\Http\\Controllers\\Api\\WalletController@captureNativePayPalOrder',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::LFXVHIAMRzICmVik',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::qjLxUI4OrKxU73ST' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/wallet/payment-status/{paymentId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\WalletController@checkPaymentStatus',
        'controller' => 'App\\Http\\Controllers\\Api\\WalletController@checkPaymentStatus',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::qjLxUI4OrKxU73ST',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Q2UGXloJS4RKCfc7' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/wallet/can-pay',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\WalletController@canPay',
        'controller' => 'App\\Http\\Controllers\\Api\\WalletController@canPay',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::Q2UGXloJS4RKCfc7',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::WvsqMcPDs6dx2EpM' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/wallet/pay',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\WalletController@pay',
        'controller' => 'App\\Http\\Controllers\\Api\\WalletController@pay',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::WvsqMcPDs6dx2EpM',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::chuJELl43yeaDWNP' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/currencies',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\CurrencyController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\CurrencyController@index',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::chuJELl43yeaDWNP',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::3VHIQkEmu3HCqkTL' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/currencies/rates',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\CurrencyController@rates',
        'controller' => 'App\\Http\\Controllers\\Api\\CurrencyController@rates',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::3VHIQkEmu3HCqkTL',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::yhIaA7rvIvO0UbiS' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/currencies/convert',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\CurrencyController@convert',
        'controller' => 'App\\Http\\Controllers\\Api\\CurrencyController@convert',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::yhIaA7rvIvO0UbiS',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::GHaYV3esJ3cYAgmM' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/user/currency',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\CurrencyController@updateUserCurrency',
        'controller' => 'App\\Http\\Controllers\\Api\\CurrencyController@updateUserCurrency',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::GHaYV3esJ3cYAgmM',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ed4IyccZqcjZOuFO' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/me/roles',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\UserRoleController@getAvailableRoles',
        'controller' => 'App\\Http\\Controllers\\Api\\UserRoleController@getAvailableRoles',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::ed4IyccZqcjZOuFO',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::djnjZfM8i0YEA2pY' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/me/switch-role',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\UserRoleController@switchRole',
        'controller' => 'App\\Http\\Controllers\\Api\\UserRoleController@switchRole',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::djnjZfM8i0YEA2pY',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::MpRsu72dRLukt96V' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/me/features',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\UserRoleController@getFeatures',
        'controller' => 'App\\Http\\Controllers\\Api\\UserRoleController@getFeatures',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::MpRsu72dRLukt96V',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::aYY4XneRDP9EiXcL' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/me/features/{featureKey}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\UserRoleController@checkFeature',
        'controller' => 'App\\Http\\Controllers\\Api\\UserRoleController@checkFeature',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::aYY4XneRDP9EiXcL',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::BE8YfwLHh0nYm4b6' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/me/sync-features',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\UserRoleController@syncFeatures',
        'controller' => 'App\\Http\\Controllers\\Api\\UserRoleController@syncFeatures',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::BE8YfwLHh0nYm4b6',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::4kFhhf9h2DoRoARz' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/conversations',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ConversationController@getConversationsList',
        'controller' => 'App\\Http\\Controllers\\Api\\ConversationController@getConversationsList',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::4kFhhf9h2DoRoARz',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::QZ4u0iv21lOIL18u' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/conversations',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'subscription:can_contact',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ConversationController@store',
        'controller' => 'App\\Http\\Controllers\\Api\\ConversationController@store',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::QZ4u0iv21lOIL18u',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::3HTqDUgl9RjAkWSr' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/conversations/{conversationId}/messages',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ChatController@getMessages',
        'controller' => 'App\\Http\\Controllers\\Api\\ChatController@getMessages',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::3HTqDUgl9RjAkWSr',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ubFVmySyI2Nk2GTO' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/conversations/messages',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ChatController@send',
        'controller' => 'App\\Http\\Controllers\\Api\\ChatController@send',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::ubFVmySyI2Nk2GTO',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::EhgGBKDjl6jenOnL' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/conversations/{conversation}/read',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ChatController@markRead',
        'controller' => 'App\\Http\\Controllers\\Api\\ChatController@markRead',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::EhgGBKDjl6jenOnL',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::gHosB6X2vQuSTqZK' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/conversations/typing',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ChatController@typing',
        'controller' => 'App\\Http\\Controllers\\Api\\ChatController@typing',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::gHosB6X2vQuSTqZK',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::zkCa34jNz3BVTwIs' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/presence/online',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ChatController@online',
        'controller' => 'App\\Http\\Controllers\\Api\\ChatController@online',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::zkCa34jNz3BVTwIs',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::EeQXZIPeot7sz3sL' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/presence/offline',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ChatController@offline',
        'controller' => 'App\\Http\\Controllers\\Api\\ChatController@offline',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::EeQXZIPeot7sz3sL',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::4bmOLH45nHGxVCNS' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/portfolio',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PortfolioController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\PortfolioController@show',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::4bmOLH45nHGxVCNS',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::imdcrYGJt6bLeMFG' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/portfolio',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'App\\Http\\Middleware\\CheckPortfolioAccess',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PortfolioController@store',
        'controller' => 'App\\Http\\Controllers\\Api\\PortfolioController@store',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::imdcrYGJt6bLeMFG',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::VQJNQpMmhW5GvgiI' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/portfolio',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'App\\Http\\Middleware\\CheckPortfolioAccess',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PortfolioController@update',
        'controller' => 'App\\Http\\Controllers\\Api\\PortfolioController@update',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::VQJNQpMmhW5GvgiI',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::8MJFjcQTCFpp7AzS' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/portfolio',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PortfolioController@destroy',
        'controller' => 'App\\Http\\Controllers\\Api\\PortfolioController@destroy',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::8MJFjcQTCFpp7AzS',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::XSoA5NTKVhVp6JSt' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'api/portfolio/toggle-visibility',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PortfolioController@toggleVisibility',
        'controller' => 'App\\Http\\Controllers\\Api\\PortfolioController@toggleVisibility',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::XSoA5NTKVhVp6JSt',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::wyc97CAWozh0VlVU' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/portfolio/stats',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PortfolioController@stats',
        'controller' => 'App\\Http\\Controllers\\Api\\PortfolioController@stats',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::wyc97CAWozh0VlVU',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::hzOkUYi2y4USx87s' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/portfolio/by-slug/{slug}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PortfolioController@showBySlug',
        'controller' => 'App\\Http\\Controllers\\Api\\PortfolioController@showBySlug',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::hzOkUYi2y4USx87s',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::vwbr0Xpo7yyig8A1' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/programs',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ProgramController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\ProgramController@index',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::vwbr0Xpo7yyig8A1',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::14fyPJPKl6l6mg0J' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/programs/check-access',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ProgramController@checkAccess',
        'controller' => 'App\\Http\\Controllers\\Api\\ProgramController@checkAccess',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::14fyPJPKl6l6mg0J',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::P2BTeOjAjU1Gbtmq' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/programs/{program}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ProgramController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\ProgramController@show',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::P2BTeOjAjU1Gbtmq',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ODA6eoNh8h8eCo2S' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/broadcasting/auth',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:91:"function () {
        return \\Illuminate\\Support\\Facades\\Broadcast::auth(\\request());
    }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000005c90000000000000000";}}',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::ODA6eoNh8h8eCo2S',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api.webhooks.freemopay' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/webhooks/freemopay',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:285:"function (\\Illuminate\\Http\\Request $request) {
    \\Illuminate\\Support\\Facades\\Log::info(\'[FreeMoPay Webhook] Received callback\', [
        \'headers\' => $request->headers->all(),
        \'body\' => $request->all(),
    ]);

    return \\response()->json([\'status\' => \'received\'], 200);
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"000000000000055c0000000000000000";}}',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'api.webhooks.freemopay',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::og7UmNXNMUG13nIS' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'up',
      'action' => 
      array (
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:853:"function () {
                    $exception = null;

                    try {
                        \\Illuminate\\Support\\Facades\\Event::dispatch(new \\Illuminate\\Foundation\\Events\\DiagnosingHealth);
                    } catch (\\Throwable $e) {
                        if (app()->hasDebugModeEnabled()) {
                            throw $e;
                        }

                        report($e);

                        $exception = $e->getMessage();
                    }

                    return response(\\Illuminate\\Support\\Facades\\View::file(\'/var/www/clients/client1/web19/web/estuaire-emploie-backend/vendor/laravel/framework/src/Illuminate/Foundation/Configuration\'.\'/../resources/health-up.blade.php\', [
                        \'exception\' => $exception,
                    ]), status: $exception ? 500 : 200);
                }";s:5:"scope";s:54:"Illuminate\\Foundation\\Configuration\\ApplicationBuilder";s:4:"this";N;s:4:"self";s:32:"00000000000005410000000000000000";}}',
        'as' => 'generated::og7UmNXNMUG13nIS',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::IzBwQKl35hyxnmc1' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '/',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:61:"function () {
    return \\redirect()->route(\'admin.login\');
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000005ce0000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::IzBwQKl35hyxnmc1',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'payment.success' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'payment/success',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\PaymentCallbackController@success',
        'controller' => 'App\\Http\\Controllers\\PaymentCallbackController@success',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'payment.success',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'payment.cancel' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'payment/cancel',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\PaymentCallbackController@cancel',
        'controller' => 'App\\Http\\Controllers\\PaymentCallbackController@cancel',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'payment.cancel',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'portfolio.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'portfolio/{slug}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\PortfolioViewController@show',
        'controller' => 'App\\Http\\Controllers\\PortfolioViewController@show',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'portfolio.show',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.login' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/login',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\AuthController@showLogin',
        'controller' => 'App\\Http\\Controllers\\Admin\\AuthController@showLogin',
        'as' => 'admin.login',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.login.submit' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/login',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\AuthController@login',
        'controller' => 'App\\Http\\Controllers\\Admin\\AuthController@login',
        'as' => 'admin.login.submit',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.logout' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/logout',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\AuthController@logout',
        'controller' => 'App\\Http\\Controllers\\Admin\\AuthController@logout',
        'as' => 'admin.logout',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.dashboard' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/dashboard',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\DashboardController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\DashboardController@index',
        'as' => 'admin.dashboard',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.profile' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/profile',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ProfileController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\ProfileController@index',
        'as' => 'admin.profile',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.profile.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'admin/profile',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ProfileController@update',
        'controller' => 'App\\Http\\Controllers\\Admin\\ProfileController@update',
        'as' => 'admin.profile.update',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.profile.password' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'admin/profile/password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ProfileController@updatePassword',
        'controller' => 'App\\Http\\Controllers\\Admin\\ProfileController@updatePassword',
        'as' => 'admin.profile.password',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.companies.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/companies',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_companies',
        ),
        'as' => 'admin.companies.index',
        'uses' => 'App\\Http\\Controllers\\Admin\\CompanyController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\CompanyController@index',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.companies.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/companies/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_companies',
        ),
        'as' => 'admin.companies.create',
        'uses' => 'App\\Http\\Controllers\\Admin\\CompanyController@create',
        'controller' => 'App\\Http\\Controllers\\Admin\\CompanyController@create',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.companies.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/companies',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_companies',
        ),
        'as' => 'admin.companies.store',
        'uses' => 'App\\Http\\Controllers\\Admin\\CompanyController@store',
        'controller' => 'App\\Http\\Controllers\\Admin\\CompanyController@store',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.companies.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/companies/{company}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_companies',
        ),
        'as' => 'admin.companies.show',
        'uses' => 'App\\Http\\Controllers\\Admin\\CompanyController@show',
        'controller' => 'App\\Http\\Controllers\\Admin\\CompanyController@show',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.companies.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/companies/{company}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_companies',
        ),
        'as' => 'admin.companies.edit',
        'uses' => 'App\\Http\\Controllers\\Admin\\CompanyController@edit',
        'controller' => 'App\\Http\\Controllers\\Admin\\CompanyController@edit',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.companies.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'admin/companies/{company}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_companies',
        ),
        'as' => 'admin.companies.update',
        'uses' => 'App\\Http\\Controllers\\Admin\\CompanyController@update',
        'controller' => 'App\\Http\\Controllers\\Admin\\CompanyController@update',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.companies.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/companies/{company}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_companies',
        ),
        'as' => 'admin.companies.destroy',
        'uses' => 'App\\Http\\Controllers\\Admin\\CompanyController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\CompanyController@destroy',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.companies.verify' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'admin/companies/{company}/verify',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_companies',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\CompanyController@verify',
        'controller' => 'App\\Http\\Controllers\\Admin\\CompanyController@verify',
        'as' => 'admin.companies.verify',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.companies.suspend' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'admin/companies/{company}/suspend',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_companies',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\CompanyController@suspend',
        'controller' => 'App\\Http\\Controllers\\Admin\\CompanyController@suspend',
        'as' => 'admin.companies.suspend',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.companies.bulk-delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/companies/bulk-delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_companies',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\CompanyController@bulkDelete',
        'controller' => 'App\\Http\\Controllers\\Admin\\CompanyController@bulkDelete',
        'as' => 'admin.companies.bulk-delete',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.companies.verify-address' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/companies/verify-address',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_companies',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\CompanyController@verifyAddress',
        'controller' => 'App\\Http\\Controllers\\Admin\\CompanyController@verifyAddress',
        'as' => 'admin.companies.verify-address',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.jobs.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/jobs',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_jobs',
        ),
        'as' => 'admin.jobs.index',
        'uses' => 'App\\Http\\Controllers\\Admin\\JobController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\JobController@index',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.jobs.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/jobs/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_jobs',
        ),
        'as' => 'admin.jobs.create',
        'uses' => 'App\\Http\\Controllers\\Admin\\JobController@create',
        'controller' => 'App\\Http\\Controllers\\Admin\\JobController@create',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.jobs.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/jobs',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_jobs',
        ),
        'as' => 'admin.jobs.store',
        'uses' => 'App\\Http\\Controllers\\Admin\\JobController@store',
        'controller' => 'App\\Http\\Controllers\\Admin\\JobController@store',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.jobs.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/jobs/{job}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_jobs',
        ),
        'as' => 'admin.jobs.show',
        'uses' => 'App\\Http\\Controllers\\Admin\\JobController@show',
        'controller' => 'App\\Http\\Controllers\\Admin\\JobController@show',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.jobs.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/jobs/{job}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_jobs',
        ),
        'as' => 'admin.jobs.edit',
        'uses' => 'App\\Http\\Controllers\\Admin\\JobController@edit',
        'controller' => 'App\\Http\\Controllers\\Admin\\JobController@edit',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.jobs.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'admin/jobs/{job}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_jobs',
        ),
        'as' => 'admin.jobs.update',
        'uses' => 'App\\Http\\Controllers\\Admin\\JobController@update',
        'controller' => 'App\\Http\\Controllers\\Admin\\JobController@update',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.jobs.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/jobs/{job}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_jobs',
        ),
        'as' => 'admin.jobs.destroy',
        'uses' => 'App\\Http\\Controllers\\Admin\\JobController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\JobController@destroy',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.jobs.publish' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'admin/jobs/{job}/publish',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_jobs',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\JobController@publish',
        'controller' => 'App\\Http\\Controllers\\Admin\\JobController@publish',
        'as' => 'admin.jobs.publish',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.jobs.send-notifications' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/jobs/{job}/send-notifications',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_jobs',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\JobController@showSendNotifications',
        'controller' => 'App\\Http\\Controllers\\Admin\\JobController@showSendNotifications',
        'as' => 'admin.jobs.send-notifications',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.jobs.send-notifications-batch' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/jobs/{job}/send-notifications-batch',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_jobs',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\JobController@sendNotificationsBatch',
        'controller' => 'App\\Http\\Controllers\\Admin\\JobController@sendNotificationsBatch',
        'as' => 'admin.jobs.send-notifications-batch',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.jobs.send-emails-batch' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/jobs/{job}/send-emails-batch',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_jobs',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\JobController@sendEmailsBatch',
        'controller' => 'App\\Http\\Controllers\\Admin\\JobController@sendEmailsBatch',
        'as' => 'admin.jobs.send-emails-batch',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.jobs.feature' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'admin/jobs/{job}/feature',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_jobs',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\JobController@feature',
        'controller' => 'App\\Http\\Controllers\\Admin\\JobController@feature',
        'as' => 'admin.jobs.feature',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.jobs.bulk-delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/jobs/bulk-delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_jobs',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\JobController@bulkDelete',
        'controller' => 'App\\Http\\Controllers\\Admin\\JobController@bulkDelete',
        'as' => 'admin.jobs.bulk-delete',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.applications.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/applications',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_applications',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ApplicationController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\ApplicationController@index',
        'as' => 'admin.applications.index',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.applications.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/applications/{application}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_applications',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ApplicationController@show',
        'controller' => 'App\\Http\\Controllers\\Admin\\ApplicationController@show',
        'as' => 'admin.applications.show',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.applications.status' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'admin/applications/{application}/status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_applications',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ApplicationController@updateStatus',
        'controller' => 'App\\Http\\Controllers\\Admin\\ApplicationController@updateStatus',
        'as' => 'admin.applications.status',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.applications.verify-diploma' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'admin/applications/{application}/verify-diploma',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_applications',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ApplicationController@verifyDiploma',
        'controller' => 'App\\Http\\Controllers\\Admin\\ApplicationController@verifyDiploma',
        'as' => 'admin.applications.verify-diploma',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.applications.bulk-delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/applications/bulk-delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_applications',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ApplicationController@bulkDelete',
        'controller' => 'App\\Http\\Controllers\\Admin\\ApplicationController@bulkDelete',
        'as' => 'admin.applications.bulk-delete',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.users.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/users',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_users',
        ),
        'as' => 'admin.users.index',
        'uses' => 'App\\Http\\Controllers\\Admin\\UserController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\UserController@index',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.users.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/users/{user}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_users',
        ),
        'as' => 'admin.users.show',
        'uses' => 'App\\Http\\Controllers\\Admin\\UserController@show',
        'controller' => 'App\\Http\\Controllers\\Admin\\UserController@show',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.users.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/users/{user}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_users',
        ),
        'as' => 'admin.users.destroy',
        'uses' => 'App\\Http\\Controllers\\Admin\\UserController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\UserController@destroy',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.users.bulk-delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/users/bulk-delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_users',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\UserController@bulkDelete',
        'controller' => 'App\\Http\\Controllers\\Admin\\UserController@bulkDelete',
        'as' => 'admin.users.bulk-delete',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.recruiters.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/recruiters',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_recruiters',
        ),
        'as' => 'admin.recruiters.index',
        'uses' => 'App\\Http\\Controllers\\Admin\\RecruiterController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\RecruiterController@index',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.recruiters.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/recruiters/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_recruiters',
        ),
        'as' => 'admin.recruiters.create',
        'uses' => 'App\\Http\\Controllers\\Admin\\RecruiterController@create',
        'controller' => 'App\\Http\\Controllers\\Admin\\RecruiterController@create',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.recruiters.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/recruiters',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_recruiters',
        ),
        'as' => 'admin.recruiters.store',
        'uses' => 'App\\Http\\Controllers\\Admin\\RecruiterController@store',
        'controller' => 'App\\Http\\Controllers\\Admin\\RecruiterController@store',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.recruiters.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/recruiters/{recruiter}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_recruiters',
        ),
        'as' => 'admin.recruiters.show',
        'uses' => 'App\\Http\\Controllers\\Admin\\RecruiterController@show',
        'controller' => 'App\\Http\\Controllers\\Admin\\RecruiterController@show',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.recruiters.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/recruiters/{recruiter}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_recruiters',
        ),
        'as' => 'admin.recruiters.edit',
        'uses' => 'App\\Http\\Controllers\\Admin\\RecruiterController@edit',
        'controller' => 'App\\Http\\Controllers\\Admin\\RecruiterController@edit',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.recruiters.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'admin/recruiters/{recruiter}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_recruiters',
        ),
        'as' => 'admin.recruiters.update',
        'uses' => 'App\\Http\\Controllers\\Admin\\RecruiterController@update',
        'controller' => 'App\\Http\\Controllers\\Admin\\RecruiterController@update',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.recruiters.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/recruiters/{recruiter}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_recruiters',
        ),
        'as' => 'admin.recruiters.destroy',
        'uses' => 'App\\Http\\Controllers\\Admin\\RecruiterController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\RecruiterController@destroy',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.recruiters.bulk-delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/recruiters/bulk-delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_recruiters',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\RecruiterController@bulkDelete',
        'controller' => 'App\\Http\\Controllers\\Admin\\RecruiterController@bulkDelete',
        'as' => 'admin.recruiters.bulk-delete',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.admins.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/admins',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_admins',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\AdminManagementController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\AdminManagementController@index',
        'as' => 'admin.admins.index',
        'namespace' => NULL,
        'prefix' => 'admin/admins',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.admins.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/admins/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_admins',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\AdminManagementController@create',
        'controller' => 'App\\Http\\Controllers\\Admin\\AdminManagementController@create',
        'as' => 'admin.admins.create',
        'namespace' => NULL,
        'prefix' => 'admin/admins',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.admins.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/admins',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_admins',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\AdminManagementController@store',
        'controller' => 'App\\Http\\Controllers\\Admin\\AdminManagementController@store',
        'as' => 'admin.admins.store',
        'namespace' => NULL,
        'prefix' => 'admin/admins',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.admins.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/admins/{user}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_admins',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\AdminManagementController@show',
        'controller' => 'App\\Http\\Controllers\\Admin\\AdminManagementController@show',
        'as' => 'admin.admins.show',
        'namespace' => NULL,
        'prefix' => 'admin/admins',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.admins.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/admins/{user}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_admins',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\AdminManagementController@edit',
        'controller' => 'App\\Http\\Controllers\\Admin\\AdminManagementController@edit',
        'as' => 'admin.admins.edit',
        'namespace' => NULL,
        'prefix' => 'admin/admins',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.admins.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'admin/admins/{user}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_admins',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\AdminManagementController@update',
        'controller' => 'App\\Http\\Controllers\\Admin\\AdminManagementController@update',
        'as' => 'admin.admins.update',
        'namespace' => NULL,
        'prefix' => 'admin/admins',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.admins.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/admins/{user}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_admins',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\AdminManagementController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\AdminManagementController@destroy',
        'as' => 'admin.admins.destroy',
        'namespace' => NULL,
        'prefix' => 'admin/admins',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.admins.permissions' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'admin/admins/{user}/permissions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_admins',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\AdminManagementController@updatePermissions',
        'controller' => 'App\\Http\\Controllers\\Admin\\AdminManagementController@updatePermissions',
        'as' => 'admin.admins.permissions',
        'namespace' => NULL,
        'prefix' => 'admin/admins',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.admins.bulk-delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/admins/bulk-delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_admins',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\AdminManagementController@bulkDelete',
        'controller' => 'App\\Http\\Controllers\\Admin\\AdminManagementController@bulkDelete',
        'as' => 'admin.admins.bulk-delete',
        'namespace' => NULL,
        'prefix' => 'admin/admins',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.sections.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/sections',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_sections',
        ),
        'as' => 'admin.sections.index',
        'uses' => 'App\\Http\\Controllers\\Admin\\SectionController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\SectionController@index',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.sections.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/sections/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_sections',
        ),
        'as' => 'admin.sections.create',
        'uses' => 'App\\Http\\Controllers\\Admin\\SectionController@create',
        'controller' => 'App\\Http\\Controllers\\Admin\\SectionController@create',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.sections.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/sections',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_sections',
        ),
        'as' => 'admin.sections.store',
        'uses' => 'App\\Http\\Controllers\\Admin\\SectionController@store',
        'controller' => 'App\\Http\\Controllers\\Admin\\SectionController@store',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.sections.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/sections/{section}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_sections',
        ),
        'as' => 'admin.sections.show',
        'uses' => 'App\\Http\\Controllers\\Admin\\SectionController@show',
        'controller' => 'App\\Http\\Controllers\\Admin\\SectionController@show',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.sections.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/sections/{section}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_sections',
        ),
        'as' => 'admin.sections.edit',
        'uses' => 'App\\Http\\Controllers\\Admin\\SectionController@edit',
        'controller' => 'App\\Http\\Controllers\\Admin\\SectionController@edit',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.sections.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'admin/sections/{section}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_sections',
        ),
        'as' => 'admin.sections.update',
        'uses' => 'App\\Http\\Controllers\\Admin\\SectionController@update',
        'controller' => 'App\\Http\\Controllers\\Admin\\SectionController@update',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.sections.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/sections/{section}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_sections',
        ),
        'as' => 'admin.sections.destroy',
        'uses' => 'App\\Http\\Controllers\\Admin\\SectionController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\SectionController@destroy',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.programs.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/programs',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_settings',
        ),
        'as' => 'admin.programs.index',
        'uses' => 'App\\Http\\Controllers\\Admin\\ProgramController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\ProgramController@index',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.programs.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/programs/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_settings',
        ),
        'as' => 'admin.programs.create',
        'uses' => 'App\\Http\\Controllers\\Admin\\ProgramController@create',
        'controller' => 'App\\Http\\Controllers\\Admin\\ProgramController@create',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.programs.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/programs',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_settings',
        ),
        'as' => 'admin.programs.store',
        'uses' => 'App\\Http\\Controllers\\Admin\\ProgramController@store',
        'controller' => 'App\\Http\\Controllers\\Admin\\ProgramController@store',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.programs.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/programs/{program}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_settings',
        ),
        'as' => 'admin.programs.show',
        'uses' => 'App\\Http\\Controllers\\Admin\\ProgramController@show',
        'controller' => 'App\\Http\\Controllers\\Admin\\ProgramController@show',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.programs.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/programs/{program}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_settings',
        ),
        'as' => 'admin.programs.edit',
        'uses' => 'App\\Http\\Controllers\\Admin\\ProgramController@edit',
        'controller' => 'App\\Http\\Controllers\\Admin\\ProgramController@edit',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.programs.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'admin/programs/{program}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_settings',
        ),
        'as' => 'admin.programs.update',
        'uses' => 'App\\Http\\Controllers\\Admin\\ProgramController@update',
        'controller' => 'App\\Http\\Controllers\\Admin\\ProgramController@update',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.programs.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/programs/{program}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_settings',
        ),
        'as' => 'admin.programs.destroy',
        'uses' => 'App\\Http\\Controllers\\Admin\\ProgramController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\ProgramController@destroy',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.programs.manage-steps' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/programs/{program}/manage-steps',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_settings',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ProgramController@manageSteps',
        'controller' => 'App\\Http\\Controllers\\Admin\\ProgramController@manageSteps',
        'as' => 'admin.programs.manage-steps',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.programs.get-step' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/programs/{program}/steps/{step}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_settings',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ProgramController@getStep',
        'controller' => 'App\\Http\\Controllers\\Admin\\ProgramController@getStep',
        'as' => 'admin.programs.get-step',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.programs.store-step' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/programs/{program}/steps',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_settings',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ProgramController@storeStep',
        'controller' => 'App\\Http\\Controllers\\Admin\\ProgramController@storeStep',
        'as' => 'admin.programs.store-step',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.programs.update-step' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'admin/programs/{program}/steps/{step}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_settings',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ProgramController@updateStep',
        'controller' => 'App\\Http\\Controllers\\Admin\\ProgramController@updateStep',
        'as' => 'admin.programs.update-step',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.programs.destroy-step' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/programs/{program}/steps/{step}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_settings',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ProgramController@destroyStep',
        'controller' => 'App\\Http\\Controllers\\Admin\\ProgramController@destroyStep',
        'as' => 'admin.programs.destroy-step',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.portfolios.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/portfolios',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_users',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\PortfolioController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\PortfolioController@index',
        'as' => 'admin.portfolios.index',
        'namespace' => NULL,
        'prefix' => 'admin/portfolios',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.portfolios.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/portfolios/{portfolio}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_users',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\PortfolioController@show',
        'controller' => 'App\\Http\\Controllers\\Admin\\PortfolioController@show',
        'as' => 'admin.portfolios.show',
        'namespace' => NULL,
        'prefix' => 'admin/portfolios',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.portfolios.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/portfolios/{portfolio}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_users',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\PortfolioController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\PortfolioController@destroy',
        'as' => 'admin.portfolios.destroy',
        'namespace' => NULL,
        'prefix' => 'admin/portfolios',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.portfolios.toggle-visibility' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'admin/portfolios/{portfolio}/toggle-visibility',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_users',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\PortfolioController@toggleVisibility',
        'controller' => 'App\\Http\\Controllers\\Admin\\PortfolioController@toggleVisibility',
        'as' => 'admin.portfolios.toggle-visibility',
        'namespace' => NULL,
        'prefix' => 'admin/portfolios',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.portfolios.bulk-delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/portfolios/bulk-delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_users',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\PortfolioController@bulkDelete',
        'controller' => 'App\\Http\\Controllers\\Admin\\PortfolioController@bulkDelete',
        'as' => 'admin.portfolios.bulk-delete',
        'namespace' => NULL,
        'prefix' => 'admin/portfolios',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.portfolios.export' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/portfolios/export/csv',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_users',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\PortfolioController@export',
        'controller' => 'App\\Http\\Controllers\\Admin\\PortfolioController@export',
        'as' => 'admin.portfolios.export',
        'namespace' => NULL,
        'prefix' => 'admin/portfolios',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.skill-tests.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/skill-tests',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_applications',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\SkillTestController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\SkillTestController@index',
        'as' => 'admin.skill-tests.index',
        'namespace' => NULL,
        'prefix' => 'admin/skill-tests',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.skill-tests.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/skill-tests/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_applications',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\SkillTestController@show',
        'controller' => 'App\\Http\\Controllers\\Admin\\SkillTestController@show',
        'as' => 'admin.skill-tests.show',
        'namespace' => NULL,
        'prefix' => 'admin/skill-tests',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.skill-tests.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/skill-tests/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_applications',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\SkillTestController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\SkillTestController@destroy',
        'as' => 'admin.skill-tests.destroy',
        'namespace' => NULL,
        'prefix' => 'admin/skill-tests',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.settings.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/settings',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_settings',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\SettingsController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\SettingsController@index',
        'as' => 'admin.settings.index',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.settings.update' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/settings',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_settings',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\SettingsController@update',
        'controller' => 'App\\Http\\Controllers\\Admin\\SettingsController@update',
        'as' => 'admin.settings.update',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.settings.categories' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/settings/categories',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_settings',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\SettingsController@categories',
        'controller' => 'App\\Http\\Controllers\\Admin\\SettingsController@categories',
        'as' => 'admin.settings.categories',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.settings.categories.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/settings/categories',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_settings',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\SettingsController@storeCategory',
        'controller' => 'App\\Http\\Controllers\\Admin\\SettingsController@storeCategory',
        'as' => 'admin.settings.categories.store',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.settings.categories.delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/settings/categories/{category}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_settings',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\SettingsController@deleteCategory',
        'controller' => 'App\\Http\\Controllers\\Admin\\SettingsController@deleteCategory',
        'as' => 'admin.settings.categories.delete',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.subscription-plans.recruiters.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/subscription-plans/recruiters',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_subscription_plans',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\RecruiterSubscriptionPlanController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\RecruiterSubscriptionPlanController@index',
        'as' => 'admin.subscription-plans.recruiters.index',
        'namespace' => NULL,
        'prefix' => 'admin/subscription-plans/recruiters',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.subscription-plans.recruiters.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/subscription-plans/recruiters/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_subscription_plans',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\RecruiterSubscriptionPlanController@create',
        'controller' => 'App\\Http\\Controllers\\Admin\\RecruiterSubscriptionPlanController@create',
        'as' => 'admin.subscription-plans.recruiters.create',
        'namespace' => NULL,
        'prefix' => 'admin/subscription-plans/recruiters',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.subscription-plans.recruiters.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/subscription-plans/recruiters',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_subscription_plans',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\RecruiterSubscriptionPlanController@store',
        'controller' => 'App\\Http\\Controllers\\Admin\\RecruiterSubscriptionPlanController@store',
        'as' => 'admin.subscription-plans.recruiters.store',
        'namespace' => NULL,
        'prefix' => 'admin/subscription-plans/recruiters',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.subscription-plans.recruiters.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/subscription-plans/recruiters/{plan}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_subscription_plans',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\RecruiterSubscriptionPlanController@edit',
        'controller' => 'App\\Http\\Controllers\\Admin\\RecruiterSubscriptionPlanController@edit',
        'as' => 'admin.subscription-plans.recruiters.edit',
        'namespace' => NULL,
        'prefix' => 'admin/subscription-plans/recruiters',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.subscription-plans.recruiters.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'admin/subscription-plans/recruiters/{plan}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_subscription_plans',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\RecruiterSubscriptionPlanController@update',
        'controller' => 'App\\Http\\Controllers\\Admin\\RecruiterSubscriptionPlanController@update',
        'as' => 'admin.subscription-plans.recruiters.update',
        'namespace' => NULL,
        'prefix' => 'admin/subscription-plans/recruiters',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.subscription-plans.recruiters.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/subscription-plans/recruiters/{plan}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_subscription_plans',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\RecruiterSubscriptionPlanController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\RecruiterSubscriptionPlanController@destroy',
        'as' => 'admin.subscription-plans.recruiters.destroy',
        'namespace' => NULL,
        'prefix' => 'admin/subscription-plans/recruiters',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.subscription-plans.job-seekers.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/subscription-plans/job-seekers',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_subscription_plans',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\JobSeekerSubscriptionPlanController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\JobSeekerSubscriptionPlanController@index',
        'as' => 'admin.subscription-plans.job-seekers.index',
        'namespace' => NULL,
        'prefix' => 'admin/subscription-plans/job-seekers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.subscription-plans.job-seekers.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/subscription-plans/job-seekers/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_subscription_plans',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\JobSeekerSubscriptionPlanController@create',
        'controller' => 'App\\Http\\Controllers\\Admin\\JobSeekerSubscriptionPlanController@create',
        'as' => 'admin.subscription-plans.job-seekers.create',
        'namespace' => NULL,
        'prefix' => 'admin/subscription-plans/job-seekers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.subscription-plans.job-seekers.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/subscription-plans/job-seekers',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_subscription_plans',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\JobSeekerSubscriptionPlanController@store',
        'controller' => 'App\\Http\\Controllers\\Admin\\JobSeekerSubscriptionPlanController@store',
        'as' => 'admin.subscription-plans.job-seekers.store',
        'namespace' => NULL,
        'prefix' => 'admin/subscription-plans/job-seekers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.subscription-plans.job-seekers.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/subscription-plans/job-seekers/{plan}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_subscription_plans',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\JobSeekerSubscriptionPlanController@edit',
        'controller' => 'App\\Http\\Controllers\\Admin\\JobSeekerSubscriptionPlanController@edit',
        'as' => 'admin.subscription-plans.job-seekers.edit',
        'namespace' => NULL,
        'prefix' => 'admin/subscription-plans/job-seekers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.subscription-plans.job-seekers.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'admin/subscription-plans/job-seekers/{plan}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_subscription_plans',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\JobSeekerSubscriptionPlanController@update',
        'controller' => 'App\\Http\\Controllers\\Admin\\JobSeekerSubscriptionPlanController@update',
        'as' => 'admin.subscription-plans.job-seekers.update',
        'namespace' => NULL,
        'prefix' => 'admin/subscription-plans/job-seekers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.subscription-plans.job-seekers.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/subscription-plans/job-seekers/{plan}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_subscription_plans',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\JobSeekerSubscriptionPlanController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\JobSeekerSubscriptionPlanController@destroy',
        'as' => 'admin.subscription-plans.job-seekers.destroy',
        'namespace' => NULL,
        'prefix' => 'admin/subscription-plans/job-seekers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.subscriptions.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/subscriptions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_subscriptions',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\SubscriptionController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\SubscriptionController@index',
        'as' => 'admin.subscriptions.index',
        'namespace' => NULL,
        'prefix' => 'admin/subscriptions',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.subscriptions.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/subscriptions/{subscription}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_subscriptions',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\SubscriptionController@show',
        'controller' => 'App\\Http\\Controllers\\Admin\\SubscriptionController@show',
        'as' => 'admin.subscriptions.show',
        'namespace' => NULL,
        'prefix' => 'admin/subscriptions',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.subscriptions.cancel' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'admin/subscriptions/{subscription}/cancel',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_subscriptions',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\SubscriptionController@cancel',
        'controller' => 'App\\Http\\Controllers\\Admin\\SubscriptionController@cancel',
        'as' => 'admin.subscriptions.cancel',
        'namespace' => NULL,
        'prefix' => 'admin/subscriptions',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.subscriptions.activate' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'admin/subscriptions/{subscription}/activate',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_subscriptions',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\SubscriptionController@activate',
        'controller' => 'App\\Http\\Controllers\\Admin\\SubscriptionController@activate',
        'as' => 'admin.subscriptions.activate',
        'namespace' => NULL,
        'prefix' => 'admin/subscriptions',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.subscriptions.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/subscriptions/{subscription}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_subscriptions',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\SubscriptionController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\SubscriptionController@destroy',
        'as' => 'admin.subscriptions.destroy',
        'namespace' => NULL,
        'prefix' => 'admin/subscriptions',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.manual-subscriptions.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/manual-subscriptions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_subscriptions',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ManualSubscriptionController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\ManualSubscriptionController@index',
        'as' => 'admin.manual-subscriptions.index',
        'namespace' => NULL,
        'prefix' => 'admin/manual-subscriptions',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.manual-subscriptions.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/manual-subscriptions/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_subscriptions',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ManualSubscriptionController@create',
        'controller' => 'App\\Http\\Controllers\\Admin\\ManualSubscriptionController@create',
        'as' => 'admin.manual-subscriptions.create',
        'namespace' => NULL,
        'prefix' => 'admin/manual-subscriptions',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.manual-subscriptions.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/manual-subscriptions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_subscriptions',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ManualSubscriptionController@store',
        'controller' => 'App\\Http\\Controllers\\Admin\\ManualSubscriptionController@store',
        'as' => 'admin.manual-subscriptions.store',
        'namespace' => NULL,
        'prefix' => 'admin/manual-subscriptions',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.manual-subscriptions.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/manual-subscriptions/{assignment}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_subscriptions',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ManualSubscriptionController@show',
        'controller' => 'App\\Http\\Controllers\\Admin\\ManualSubscriptionController@show',
        'as' => 'admin.manual-subscriptions.show',
        'namespace' => NULL,
        'prefix' => 'admin/manual-subscriptions',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.payments.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/payments',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_payments',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\PaymentController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\PaymentController@index',
        'as' => 'admin.payments.index',
        'namespace' => NULL,
        'prefix' => 'admin/payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.payments.export' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/payments/export',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_payments',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\PaymentController@export',
        'controller' => 'App\\Http\\Controllers\\Admin\\PaymentController@export',
        'as' => 'admin.payments.export',
        'namespace' => NULL,
        'prefix' => 'admin/payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.payments.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/payments/{payment}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_payments',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\PaymentController@show',
        'controller' => 'App\\Http\\Controllers\\Admin\\PaymentController@show',
        'as' => 'admin.payments.show',
        'namespace' => NULL,
        'prefix' => 'admin/payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.payments.verify' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'admin/payments/{payment}/verify',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_payments',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\PaymentController@verify',
        'controller' => 'App\\Http\\Controllers\\Admin\\PaymentController@verify',
        'as' => 'admin.payments.verify',
        'namespace' => NULL,
        'prefix' => 'admin/payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.payments.refund' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'admin/payments/{payment}/refund',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_payments',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\PaymentController@refund',
        'controller' => 'App\\Http\\Controllers\\Admin\\PaymentController@refund',
        'as' => 'admin.payments.refund',
        'namespace' => NULL,
        'prefix' => 'admin/payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.wallets.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/wallets',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_payments',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\WalletController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\WalletController@index',
        'as' => 'admin.wallets.index',
        'namespace' => NULL,
        'prefix' => 'admin/wallets',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.wallets.transactions' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/wallets/transactions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_payments',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\WalletController@transactions',
        'controller' => 'App\\Http\\Controllers\\Admin\\WalletController@transactions',
        'as' => 'admin.wallets.transactions',
        'namespace' => NULL,
        'prefix' => 'admin/wallets',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.wallets.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/wallets/{user}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_payments',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\WalletController@show',
        'controller' => 'App\\Http\\Controllers\\Admin\\WalletController@show',
        'as' => 'admin.wallets.show',
        'namespace' => NULL,
        'prefix' => 'admin/wallets',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.wallets.adjust' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/wallets/{user}/adjust',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_payments',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\WalletController@adjustForm',
        'controller' => 'App\\Http\\Controllers\\Admin\\WalletController@adjustForm',
        'as' => 'admin.wallets.adjust',
        'namespace' => NULL,
        'prefix' => 'admin/wallets',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.wallets.adjust.submit' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/wallets/{user}/adjust',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_payments',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\WalletController@adjust',
        'controller' => 'App\\Http\\Controllers\\Admin\\WalletController@adjust',
        'as' => 'admin.wallets.adjust.submit',
        'namespace' => NULL,
        'prefix' => 'admin/wallets',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.wallets.bonus' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/wallets/{user}/bonus',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_payments',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\WalletController@bonusForm',
        'controller' => 'App\\Http\\Controllers\\Admin\\WalletController@bonusForm',
        'as' => 'admin.wallets.bonus',
        'namespace' => NULL,
        'prefix' => 'admin/wallets',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.wallets.bonus.submit' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/wallets/{user}/bonus',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_payments',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\WalletController@bonus',
        'controller' => 'App\\Http\\Controllers\\Admin\\WalletController@bonus',
        'as' => 'admin.wallets.bonus.submit',
        'namespace' => NULL,
        'prefix' => 'admin/wallets',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.wallets.refund' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/wallets/transactions/{transaction}/refund',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_payments',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\WalletController@refund',
        'controller' => 'App\\Http\\Controllers\\Admin\\WalletController@refund',
        'as' => 'admin.wallets.refund',
        'namespace' => NULL,
        'prefix' => 'admin/wallets',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.premium-services.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/premium-services',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\PremiumServiceController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\PremiumServiceController@index',
        'as' => 'admin.premium-services.index',
        'namespace' => NULL,
        'prefix' => 'admin/premium-services',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.premium-services.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/premium-services/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\PremiumServiceController@create',
        'controller' => 'App\\Http\\Controllers\\Admin\\PremiumServiceController@create',
        'as' => 'admin.premium-services.create',
        'namespace' => NULL,
        'prefix' => 'admin/premium-services',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.premium-services.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/premium-services',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\PremiumServiceController@store',
        'controller' => 'App\\Http\\Controllers\\Admin\\PremiumServiceController@store',
        'as' => 'admin.premium-services.store',
        'namespace' => NULL,
        'prefix' => 'admin/premium-services',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.premium-services.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/premium-services/{service}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\PremiumServiceController@edit',
        'controller' => 'App\\Http\\Controllers\\Admin\\PremiumServiceController@edit',
        'as' => 'admin.premium-services.edit',
        'namespace' => NULL,
        'prefix' => 'admin/premium-services',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.premium-services.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'admin/premium-services/{service}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\PremiumServiceController@update',
        'controller' => 'App\\Http\\Controllers\\Admin\\PremiumServiceController@update',
        'as' => 'admin.premium-services.update',
        'namespace' => NULL,
        'prefix' => 'admin/premium-services',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.premium-services.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/premium-services/{service}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\PremiumServiceController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\PremiumServiceController@destroy',
        'as' => 'admin.premium-services.destroy',
        'namespace' => NULL,
        'prefix' => 'admin/premium-services',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.premium-services.toggle' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'admin/premium-services/{service}/toggle',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\PremiumServiceController@toggle',
        'controller' => 'App\\Http\\Controllers\\Admin\\PremiumServiceController@toggle',
        'as' => 'admin.premium-services.toggle',
        'namespace' => NULL,
        'prefix' => 'admin/premium-services',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.premium-services.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/premium-services/{service}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\PremiumServiceController@show',
        'controller' => 'App\\Http\\Controllers\\Admin\\PremiumServiceController@show',
        'as' => 'admin.premium-services.show',
        'namespace' => NULL,
        'prefix' => 'admin/premium-services',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.recruiter-services.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/recruiter-services',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_recruiter_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\RecruiterServiceController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\RecruiterServiceController@index',
        'as' => 'admin.recruiter-services.index',
        'namespace' => NULL,
        'prefix' => 'admin/recruiter-services',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.recruiter-services.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/recruiter-services/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_recruiter_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\RecruiterServiceController@create',
        'controller' => 'App\\Http\\Controllers\\Admin\\RecruiterServiceController@create',
        'as' => 'admin.recruiter-services.create',
        'namespace' => NULL,
        'prefix' => 'admin/recruiter-services',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.recruiter-services.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/recruiter-services',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_recruiter_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\RecruiterServiceController@store',
        'controller' => 'App\\Http\\Controllers\\Admin\\RecruiterServiceController@store',
        'as' => 'admin.recruiter-services.store',
        'namespace' => NULL,
        'prefix' => 'admin/recruiter-services',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.recruiter-services.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/recruiter-services/{service}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_recruiter_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\RecruiterServiceController@edit',
        'controller' => 'App\\Http\\Controllers\\Admin\\RecruiterServiceController@edit',
        'as' => 'admin.recruiter-services.edit',
        'namespace' => NULL,
        'prefix' => 'admin/recruiter-services',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.recruiter-services.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'admin/recruiter-services/{service}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_recruiter_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\RecruiterServiceController@update',
        'controller' => 'App\\Http\\Controllers\\Admin\\RecruiterServiceController@update',
        'as' => 'admin.recruiter-services.update',
        'namespace' => NULL,
        'prefix' => 'admin/recruiter-services',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.recruiter-services.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/recruiter-services/{service}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_recruiter_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\RecruiterServiceController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\RecruiterServiceController@destroy',
        'as' => 'admin.recruiter-services.destroy',
        'namespace' => NULL,
        'prefix' => 'admin/recruiter-services',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.recruiter-services.toggle' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'admin/recruiter-services/{service}/toggle',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_recruiter_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\RecruiterServiceController@toggle',
        'controller' => 'App\\Http\\Controllers\\Admin\\RecruiterServiceController@toggle',
        'as' => 'admin.recruiter-services.toggle',
        'namespace' => NULL,
        'prefix' => 'admin/recruiter-services',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.recruiter-services.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/recruiter-services/{service}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_recruiter_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\RecruiterServiceController@show',
        'controller' => 'App\\Http\\Controllers\\Admin\\RecruiterServiceController@show',
        'as' => 'admin.recruiter-services.show',
        'namespace' => NULL,
        'prefix' => 'admin/recruiter-services',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.exam-papers.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/exam-papers',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ExamPaperController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\ExamPaperController@index',
        'as' => 'admin.exam-papers.index',
        'namespace' => NULL,
        'prefix' => 'admin/exam-papers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.exam-papers.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/exam-papers/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ExamPaperController@create',
        'controller' => 'App\\Http\\Controllers\\Admin\\ExamPaperController@create',
        'as' => 'admin.exam-papers.create',
        'namespace' => NULL,
        'prefix' => 'admin/exam-papers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.exam-papers.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/exam-papers',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ExamPaperController@store',
        'controller' => 'App\\Http\\Controllers\\Admin\\ExamPaperController@store',
        'as' => 'admin.exam-papers.store',
        'namespace' => NULL,
        'prefix' => 'admin/exam-papers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.exam-papers.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/exam-papers/{examPaper}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ExamPaperController@edit',
        'controller' => 'App\\Http\\Controllers\\Admin\\ExamPaperController@edit',
        'as' => 'admin.exam-papers.edit',
        'namespace' => NULL,
        'prefix' => 'admin/exam-papers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.exam-papers.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'admin/exam-papers/{examPaper}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ExamPaperController@update',
        'controller' => 'App\\Http\\Controllers\\Admin\\ExamPaperController@update',
        'as' => 'admin.exam-papers.update',
        'namespace' => NULL,
        'prefix' => 'admin/exam-papers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.exam-papers.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/exam-papers/{examPaper}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ExamPaperController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\ExamPaperController@destroy',
        'as' => 'admin.exam-papers.destroy',
        'namespace' => NULL,
        'prefix' => 'admin/exam-papers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.exam-papers.toggle' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'admin/exam-papers/{examPaper}/toggle',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ExamPaperController@toggle',
        'controller' => 'App\\Http\\Controllers\\Admin\\ExamPaperController@toggle',
        'as' => 'admin.exam-papers.toggle',
        'namespace' => NULL,
        'prefix' => 'admin/exam-papers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.exam-papers.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/exam-papers/{examPaper}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ExamPaperController@show',
        'controller' => 'App\\Http\\Controllers\\Admin\\ExamPaperController@show',
        'as' => 'admin.exam-papers.show',
        'namespace' => NULL,
        'prefix' => 'admin/exam-papers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.exam-papers.download' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/exam-papers/{examPaper}/download',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ExamPaperController@download',
        'controller' => 'App\\Http\\Controllers\\Admin\\ExamPaperController@download',
        'as' => 'admin.exam-papers.download',
        'namespace' => NULL,
        'prefix' => 'admin/exam-papers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.cvtheque.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/cvtheque',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_cvtheque',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\CVthequeController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\CVthequeController@index',
        'as' => 'admin.cvtheque.index',
        'namespace' => NULL,
        'prefix' => 'admin/cvtheque',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.cvtheque.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/cvtheque/{user}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_cvtheque',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\CVthequeController@show',
        'controller' => 'App\\Http\\Controllers\\Admin\\CVthequeController@show',
        'as' => 'admin.cvtheque.show',
        'namespace' => NULL,
        'prefix' => 'admin/cvtheque',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.cvtheque.export' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/cvtheque/export/all',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_cvtheque',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\CVthequeController@export',
        'controller' => 'App\\Http\\Controllers\\Admin\\CVthequeController@export',
        'as' => 'admin.cvtheque.export',
        'namespace' => NULL,
        'prefix' => 'admin/cvtheque',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.advertisements.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/advertisements',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_advertisements',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\AdvertisementController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\AdvertisementController@index',
        'as' => 'admin.advertisements.index',
        'namespace' => NULL,
        'prefix' => 'admin/advertisements',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.advertisements.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/advertisements/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_advertisements',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\AdvertisementController@create',
        'controller' => 'App\\Http\\Controllers\\Admin\\AdvertisementController@create',
        'as' => 'admin.advertisements.create',
        'namespace' => NULL,
        'prefix' => 'admin/advertisements',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.advertisements.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/advertisements',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_advertisements',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\AdvertisementController@store',
        'controller' => 'App\\Http\\Controllers\\Admin\\AdvertisementController@store',
        'as' => 'admin.advertisements.store',
        'namespace' => NULL,
        'prefix' => 'admin/advertisements',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.advertisements.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/advertisements/{ad}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_advertisements',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\AdvertisementController@edit',
        'controller' => 'App\\Http\\Controllers\\Admin\\AdvertisementController@edit',
        'as' => 'admin.advertisements.edit',
        'namespace' => NULL,
        'prefix' => 'admin/advertisements',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.advertisements.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'admin/advertisements/{ad}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_advertisements',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\AdvertisementController@update',
        'controller' => 'App\\Http\\Controllers\\Admin\\AdvertisementController@update',
        'as' => 'admin.advertisements.update',
        'namespace' => NULL,
        'prefix' => 'admin/advertisements',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.advertisements.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/advertisements/{ad}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_advertisements',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\AdvertisementController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\AdvertisementController@destroy',
        'as' => 'admin.advertisements.destroy',
        'namespace' => NULL,
        'prefix' => 'admin/advertisements',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.advertisements.toggle' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'admin/advertisements/{ad}/toggle',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_advertisements',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\AdvertisementController@toggle',
        'controller' => 'App\\Http\\Controllers\\Admin\\AdvertisementController@toggle',
        'as' => 'admin.advertisements.toggle',
        'namespace' => NULL,
        'prefix' => 'admin/advertisements',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.financial-stats.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/financial-stats',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:view_financial_stats',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\FinancialStatsController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\FinancialStatsController@index',
        'as' => 'admin.financial-stats.index',
        'namespace' => NULL,
        'prefix' => 'admin/financial-stats',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.financial-stats.export' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/financial-stats/export',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:view_financial_stats',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\FinancialStatsController@export',
        'controller' => 'App\\Http\\Controllers\\Admin\\FinancialStatsController@export',
        'as' => 'admin.financial-stats.export',
        'namespace' => NULL,
        'prefix' => 'admin/financial-stats',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.service-config.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/service-config',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_service_config',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ServiceConfigController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\ServiceConfigController@index',
        'as' => 'admin.service-config.index',
        'namespace' => NULL,
        'prefix' => 'admin/service-config',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.service-config.update-whatsapp' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'admin/service-config/whatsapp',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_service_config',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ServiceConfigController@updateWhatsApp',
        'controller' => 'App\\Http\\Controllers\\Admin\\ServiceConfigController@updateWhatsApp',
        'as' => 'admin.service-config.update-whatsapp',
        'namespace' => NULL,
        'prefix' => 'admin/service-config',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.service-config.update-nexah' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'admin/service-config/nexah',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_service_config',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ServiceConfigController@updateNexah',
        'controller' => 'App\\Http\\Controllers\\Admin\\ServiceConfigController@updateNexah',
        'as' => 'admin.service-config.update-nexah',
        'namespace' => NULL,
        'prefix' => 'admin/service-config',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.service-config.update-freemopay' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'admin/service-config/freemopay',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_service_config',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ServiceConfigController@updateFreeMoPay',
        'controller' => 'App\\Http\\Controllers\\Admin\\ServiceConfigController@updateFreeMoPay',
        'as' => 'admin.service-config.update-freemopay',
        'namespace' => NULL,
        'prefix' => 'admin/service-config',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.service-config.update-paypal' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'admin/service-config/paypal',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_service_config',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ServiceConfigController@updatePayPal',
        'controller' => 'App\\Http\\Controllers\\Admin\\ServiceConfigController@updatePayPal',
        'as' => 'admin.service-config.update-paypal',
        'namespace' => NULL,
        'prefix' => 'admin/service-config',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.service-config.update-preferences' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'admin/service-config/preferences',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_service_config',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ServiceConfigController@updateNotificationPreferences',
        'controller' => 'App\\Http\\Controllers\\Admin\\ServiceConfigController@updateNotificationPreferences',
        'as' => 'admin.service-config.update-preferences',
        'namespace' => NULL,
        'prefix' => 'admin/service-config',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.service-config.test-whatsapp' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/service-config/test/whatsapp',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_service_config',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ServiceConfigController@testWhatsApp',
        'controller' => 'App\\Http\\Controllers\\Admin\\ServiceConfigController@testWhatsApp',
        'as' => 'admin.service-config.test-whatsapp',
        'namespace' => NULL,
        'prefix' => 'admin/service-config',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.service-config.test-nexah' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/service-config/test/nexah',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_service_config',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ServiceConfigController@testNexah',
        'controller' => 'App\\Http\\Controllers\\Admin\\ServiceConfigController@testNexah',
        'as' => 'admin.service-config.test-nexah',
        'namespace' => NULL,
        'prefix' => 'admin/service-config',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.service-config.test-freemopay' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/service-config/test/freemopay',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_service_config',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ServiceConfigController@testFreeMoPay',
        'controller' => 'App\\Http\\Controllers\\Admin\\ServiceConfigController@testFreeMoPay',
        'as' => 'admin.service-config.test-freemopay',
        'namespace' => NULL,
        'prefix' => 'admin/service-config',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.service-config.test-paypal' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/service-config/test/paypal',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_service_config',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ServiceConfigController@testPayPal',
        'controller' => 'App\\Http\\Controllers\\Admin\\ServiceConfigController@testPayPal',
        'as' => 'admin.service-config.test-paypal',
        'namespace' => NULL,
        'prefix' => 'admin/service-config',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.service-config.send-test-whatsapp' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/service-config/send-test/whatsapp',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_service_config',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ServiceConfigController@sendTestWhatsApp',
        'controller' => 'App\\Http\\Controllers\\Admin\\ServiceConfigController@sendTestWhatsApp',
        'as' => 'admin.service-config.send-test-whatsapp',
        'namespace' => NULL,
        'prefix' => 'admin/service-config',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.service-config.send-test-nexah' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/service-config/send-test/nexah',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_service_config',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ServiceConfigController@sendTestNexah',
        'controller' => 'App\\Http\\Controllers\\Admin\\ServiceConfigController@sendTestNexah',
        'as' => 'admin.service-config.send-test-nexah',
        'namespace' => NULL,
        'prefix' => 'admin/service-config',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.service-config.clear-cache' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/service-config/clear-cache',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_service_config',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ServiceConfigController@clearCache',
        'controller' => 'App\\Http\\Controllers\\Admin\\ServiceConfigController@clearCache',
        'as' => 'admin.service-config.clear-cache',
        'namespace' => NULL,
        'prefix' => 'admin/service-config',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.announcements.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/announcements',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\AnnouncementController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\AnnouncementController@index',
        'as' => 'admin.announcements.index',
        'namespace' => NULL,
        'prefix' => 'admin/announcements',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.announcements.send-to-user' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/announcements/send-to-user',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\AnnouncementController@sendToUser',
        'controller' => 'App\\Http\\Controllers\\Admin\\AnnouncementController@sendToUser',
        'as' => 'admin.announcements.send-to-user',
        'namespace' => NULL,
        'prefix' => 'admin/announcements',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.announcements.send-to-all' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/announcements/send-to-all',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\AnnouncementController@sendToAll',
        'controller' => 'App\\Http\\Controllers\\Admin\\AnnouncementController@sendToAll',
        'as' => 'admin.announcements.send-to-all',
        'namespace' => NULL,
        'prefix' => 'admin/announcements',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.announcements.user-count' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/announcements/user-count',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\AnnouncementController@getUserCount',
        'controller' => 'App\\Http\\Controllers\\Admin\\AnnouncementController@getUserCount',
        'as' => 'admin.announcements.user-count',
        'namespace' => NULL,
        'prefix' => 'admin/announcements',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.fcm-tokens.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/fcm-tokens',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\FcmTokenController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\FcmTokenController@index',
        'as' => 'admin.fcm-tokens.index',
        'namespace' => NULL,
        'prefix' => 'admin/fcm-tokens',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.fcm-tokens.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/fcm-tokens/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\FcmTokenController@show',
        'controller' => 'App\\Http\\Controllers\\Admin\\FcmTokenController@show',
        'as' => 'admin.fcm-tokens.show',
        'namespace' => NULL,
        'prefix' => 'admin/fcm-tokens',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.fcm-tokens.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/fcm-tokens/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\FcmTokenController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\FcmTokenController@destroy',
        'as' => 'admin.fcm-tokens.destroy',
        'namespace' => NULL,
        'prefix' => 'admin/fcm-tokens',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.fcm-tokens.bulk-destroy' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/fcm-tokens/bulk-destroy',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\FcmTokenController@bulkDestroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\FcmTokenController@bulkDestroy',
        'as' => 'admin.fcm-tokens.bulk-destroy',
        'namespace' => NULL,
        'prefix' => 'admin/fcm-tokens',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.fcm-tokens.export' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/fcm-tokens/export/csv',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\FcmTokenController@export',
        'controller' => 'App\\Http\\Controllers\\Admin\\FcmTokenController@export',
        'as' => 'admin.fcm-tokens.export',
        'namespace' => NULL,
        'prefix' => 'admin/fcm-tokens',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.bank-account.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/bank-account',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_payments',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\BankAccountController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\BankAccountController@index',
        'as' => 'admin.bank-account.index',
        'namespace' => NULL,
        'prefix' => 'admin/bank-account',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.bank-account.verify-pin' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/bank-account/verify-pin',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_payments',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\BankAccountController@verifyPin',
        'controller' => 'App\\Http\\Controllers\\Admin\\BankAccountController@verifyPin',
        'as' => 'admin.bank-account.verify-pin',
        'namespace' => NULL,
        'prefix' => 'admin/bank-account',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.bank-account.withdrawal' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/bank-account/withdrawal',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_payments',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\BankAccountController@showWithdrawalForm',
        'controller' => 'App\\Http\\Controllers\\Admin\\BankAccountController@showWithdrawalForm',
        'as' => 'admin.bank-account.withdrawal',
        'namespace' => NULL,
        'prefix' => 'admin/bank-account',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.bank-account.initiate-withdrawal' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/bank-account/withdrawal',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_payments',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\BankAccountController@initiateWithdrawal',
        'controller' => 'App\\Http\\Controllers\\Admin\\BankAccountController@initiateWithdrawal',
        'as' => 'admin.bank-account.initiate-withdrawal',
        'namespace' => NULL,
        'prefix' => 'admin/bank-account',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.bank-account.withdrawal-status' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/bank-account/withdrawal/{id}/status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_payments',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\BankAccountController@checkWithdrawalStatus',
        'controller' => 'App\\Http\\Controllers\\Admin\\BankAccountController@checkWithdrawalStatus',
        'as' => 'admin.bank-account.withdrawal-status',
        'namespace' => NULL,
        'prefix' => 'admin/bank-account',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.bank-account.history' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/bank-account/history',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_payments',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\BankAccountController@history',
        'controller' => 'App\\Http\\Controllers\\Admin\\BankAccountController@history',
        'as' => 'admin.bank-account.history',
        'namespace' => NULL,
        'prefix' => 'admin/bank-account',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.maintenance.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/maintenance',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_settings',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\MaintenanceModeController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\MaintenanceModeController@index',
        'as' => 'admin.maintenance.index',
        'namespace' => NULL,
        'prefix' => 'admin/maintenance',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.maintenance.toggle' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/maintenance/toggle',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_settings',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\MaintenanceModeController@toggle',
        'controller' => 'App\\Http\\Controllers\\Admin\\MaintenanceModeController@toggle',
        'as' => 'admin.maintenance.toggle',
        'namespace' => NULL,
        'prefix' => 'admin/maintenance',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::fhNw3PvgmL4WAUz2' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'POST',
        2 => 'HEAD',
      ),
      'uri' => 'broadcasting/auth',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => '\\Illuminate\\Broadcasting\\BroadcastController@authenticate',
        'controller' => '\\Illuminate\\Broadcasting\\BroadcastController@authenticate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'excluded_middleware' => 
        array (
          0 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        ),
        'as' => 'generated::fhNw3PvgmL4WAUz2',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
  ),
)
);
