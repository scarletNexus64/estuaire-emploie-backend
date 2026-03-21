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
      '/_ignition/health-check' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'ignition.healthCheck',
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
      '/_ignition/execute-solution' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'ignition.executeSolution',
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
      '/_ignition/update-config' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'ignition.updateConfig',
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
      '/api/check-availability' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::LizoKrFnLJPkJQIu',
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
      '/api/register' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::VZqg7pLcCeA6jggV',
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
            '_route' => 'generated::uWGlUEclxto2dmUb',
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
            '_route' => 'generated::RWrwcTyaYOgfcm7p',
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
            '_route' => 'generated::XpMdE74VI29ohELm',
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
      '/api/password/force-change' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::qk1M5kKimVPzzIlU',
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
            '_route' => 'generated::MsajAZes4fWqPxOY',
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
            '_route' => 'generated::1CZLiY05S9JZ6DjV',
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
      '/api/otp/send' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::eW8vBaWX7EUR7jkv',
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
      '/api/otp/verify' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::aY3dn3v7GyVYgdNW',
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
            '_route' => 'generated::ro5FlNsp1Exo1h3G',
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
            '_route' => 'generated::IYRUdl4hZKI4hM24',
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
            '_route' => 'generated::tOFunbG9FEtJhA0Q',
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
            '_route' => 'generated::5DSd1CTZJn5bmCoy',
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
            '_route' => 'generated::b01hvH4QyHuiHKla',
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
            '_route' => 'generated::7SMGBNkEZEZ5HbSj',
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
            '_route' => 'generated::QZ8Re2NSUi6ESehI',
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
            '_route' => 'generated::8sSmH1unKizXgw9Q',
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
            '_route' => 'generated::HE9SVXi21mmUfxVz',
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
            '_route' => 'generated::Eb7n4iwFfLgLCerY',
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
            '_route' => 'generated::UF8P7s7VJFgQEXq5',
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
            '_route' => 'generated::u1Wr0DQBJlxossEU',
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
      '/api/service-categories' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::7VKyt7atU9cgxS5C',
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
            '_route' => 'generated::5m2alfVgIuWgjBZf',
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
            '_route' => 'generated::wzV4dyMpj3DBMGbe',
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
            '_route' => 'generated::kLwtOI7kr44nODEv',
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
            '_route' => 'generated::XYRVE9Z9cgQAZS0w',
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
            '_route' => 'generated::loTaZgk8Qzdi1YwP',
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
            '_route' => 'generated::WrmMrQvn3LQ8gov5',
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
            '_route' => 'generated::y5RPq1omeHLy67Q5',
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
            '_route' => 'generated::a6dydXLH9c3tiBYH',
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
            '_route' => 'generated::GHpKHykXlEPnAiXR',
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
            '_route' => 'generated::mHzhqeyM2sTrKRuV',
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
            '_route' => 'generated::c2ja1ZcCN5EBXoc8',
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
            '_route' => 'generated::ZSQVjmjBruGmmLAE',
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
      '/api/quick-services/favorites' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::mdPlAkKHLauMEXmE',
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
            '_route' => 'generated::USBekWtswm8NsYWI',
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
            '_route' => 'generated::k04bBWqIbyzXAiYS',
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
            '_route' => 'generated::iBNlmj89WNl38XB4',
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
            '_route' => 'generated::0shx2LpOJrKQ0Ude',
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
            '_route' => 'generated::3I5u0zN6DP6PqkT4',
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
            '_route' => 'generated::mJtgpE3bWsDk9nPe',
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
            '_route' => 'generated::9xoQjdmbbeWn6z6Z',
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
            '_route' => 'generated::c4Bt7vDwTKQbiAEz',
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
            '_route' => 'generated::RxSXlPqJHl78pYas',
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
            '_route' => 'generated::MtN3ZqR92mmdfPoz',
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
            '_route' => 'generated::jADXfNUc4qwRcWnK',
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
            '_route' => 'generated::zheLJcOYNNo7OYgf',
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
            '_route' => 'generated::f5XHfesmnFLwIFat',
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
            '_route' => 'generated::kjC4GJBOx0SalKQq',
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
            '_route' => 'generated::QqeSbQHEwXAMisL8',
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
      '/api/exam-packs' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::J8H7aE7NWpXV8yRP',
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
      '/api/exam-packs/filters' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::DEFATaK6PUJaV45M',
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
      '/api/my-exam-packs' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::2pxxv5aYRKxqRIMD',
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
      '/api/training-packs' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::fppwShCubumPEMzT',
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
      '/api/training-packs/filters' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::2VWHE3hRfCTRhFSX',
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
      '/api/my-training-packs' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::oROhnjstrBeO1uIT',
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
            '_route' => 'generated::Ddn5fpwrG4oYdN2l',
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
            '_route' => 'generated::7BG0I4GqrXTGHQsr',
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
            '_route' => 'generated::enCzLJHbfPPaIlHu',
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
            '_route' => 'generated::qDLSQJwzREiucUYK',
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
            '_route' => 'generated::FGeFEquJpfojYx5b',
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
            '_route' => 'generated::RwBQuhqKAlRNv7Yq',
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
            '_route' => 'generated::SnuYatGHWBTE3ayV',
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
            '_route' => 'generated::QFborJSwtXsUOfq9',
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
            '_route' => 'generated::9XNXyNWxhUywuw5L',
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
            '_route' => 'generated::qaRjKoLTqc5Vso4g',
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
            '_route' => 'generated::QEE1lEXR3joBnvCN',
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
            '_route' => 'generated::DGcYciKmajxuQFEd',
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
            '_route' => 'generated::a5NJcYaBGt8EVC5G',
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
            '_route' => 'generated::l0KhRfZwFgBX8CRq',
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
            '_route' => 'generated::NNM85kh32FcuaYR5',
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
            '_route' => 'generated::0vv0JX5vHvwerefb',
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
            '_route' => 'generated::uAsT8WElA4LUs7VE',
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
            '_route' => 'generated::4qTNcgBucPmAz6uW',
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
            '_route' => 'generated::Row3KmQIbwKZ1dqe',
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
            '_route' => 'generated::kT5k65wHJwJtCUDp',
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
      '/api/wallet/withdrawal-balances' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::1sLBOLBJMy1b78yj',
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
      '/api/wallet/withdraw/freemopay' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::XztQOQr6S4HuErrZ',
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
      '/api/wallet/withdraw/paypal' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::A1oLcJMM6tUyjP1r',
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
      '/api/wallet/withdrawals' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::4lMm3iOp1YWX9njR',
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
      '/api/currencies' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::AMW2XYj5rzc6cF9p',
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
            '_route' => 'generated::o0UIdWENQVbCnJM1',
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
            '_route' => 'generated::V8JByAPpZQUUVbHp',
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
            '_route' => 'generated::2y6XnPL8sDvGoBLD',
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
            '_route' => 'generated::qLIHXOZzCWNOmahe',
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
            '_route' => 'generated::kfXf71bcBaqDJxst',
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
            '_route' => 'generated::mKVa9b8LOiczHQ2h',
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
            '_route' => 'generated::wAMAiVM01IpDh4YF',
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
            '_route' => 'generated::rz9RwpumBfPSTAqD',
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
            '_route' => 'generated::oMaHos83qjLRv97h',
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
      '/api/conversations/service' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::9uRBUMsjuSgYiD3x',
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
            '_route' => 'generated::9rNAaJITNe75DHDK',
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
            '_route' => 'generated::OLOOJJ7xIelHvky3',
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
            '_route' => 'generated::lgXHCn5vfmuuGRys',
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
            '_route' => 'generated::qIwoWq01vK77NFto',
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
            '_route' => 'generated::B0ZRQskSyTKLJqLJ',
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
            '_route' => 'generated::kOkDNW26yjfBvxPu',
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
            '_route' => 'generated::0LbOAm0H2bNpWSOd',
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
            '_route' => 'generated::h7jDzmKlGVY0jpSj',
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
            '_route' => 'generated::yQklm9U3E5SX0y1W',
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
            '_route' => 'generated::aXc2Ul40B4vqDYTr',
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
            '_route' => 'generated::PBgtm7jlDcBHvyAv',
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
            '_route' => 'generated::xzAqMqywZHEt9k39',
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
      '/api/quick-services/categories' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::eAD9zZuLB3O3RkuN',
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
      '/api/quick-services' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::O5kWRA6Qj9xgCQn5',
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
            '_route' => 'generated::SdbyChga91Req7iR',
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
      '/api/my-quick-services' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::SiPFpCZtLd98ZSxp',
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
      '/api/my-service-responses' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::HPMl0ZWhG6G30bat',
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
            '_route' => 'generated::PEPE7gBLavRYy4D7',
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
            '_route' => 'generated::RdjDOZneYvxjU0dS',
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
            '_route' => 'generated::b8iq8ApVxzyvX5BN',
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
      '/admin/companies/bulk-delete' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.companies.bulk-delete',
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
      '/admin/companies/verify-address' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.companies.verify-address',
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
      '/admin/jobs/bulk-delete' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.jobs.bulk-delete',
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
      '/admin/quick-services/bulk-delete' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.quick-services.bulk-delete',
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
      '/admin/quick-services' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.quick-services.index',
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
      '/admin/applications/bulk-delete' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.applications.bulk-delete',
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
      '/admin/users/bulk-delete' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.users.bulk-delete',
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
      '/admin/recruiters/bulk-delete' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.recruiters.bulk-delete',
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
      '/admin/admins/bulk-delete' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.admins.bulk-delete',
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
      '/admin/portfolios/bulk-delete' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.portfolios.bulk-delete',
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
      '/admin/settings/referral' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.settings.referral.update',
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
      '/admin/exam-packs' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.exam-packs.index',
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
            '_route' => 'admin.exam-packs.store',
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
      '/admin/exam-packs/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.exam-packs.create',
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
      '/admin/training-videos' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.training-videos.index',
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
            '_route' => 'admin.training-videos.store',
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
      '/admin/training-videos/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.training-videos.create',
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
      '/admin/training-packs' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.training-packs.index',
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
            '_route' => 'admin.training-packs.store',
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
      '/admin/training-packs/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.training-packs.create',
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
      '/admin/students' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.students.index',
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
            '_route' => 'admin.students.store',
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
      '/admin/students/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.students.create',
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
      '/admin/students/confirm' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.students.confirm',
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
      '/admin/bank-account/available-balance' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.bank-account.available-balance',
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
      '/admin/bank-account/paypal/available-balance' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.bank-account.paypal-available-balance',
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
      '/admin/bank-account/paypal/withdrawal' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.bank-account.paypal-withdrawal',
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
            '_route' => 'admin.bank-account.initiate-paypal-withdrawal',
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
            '_route' => 'generated::UAgNNCtT5mSMUTDp',
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
      0 => '{^(?|/docs/asset/([^/]++)(*:27)|/a(?|pi/(?|jobs/([^/]++)(?|(*:61)|/(?|apply(?|(*:80)|\\-with\\-test(*:99))|favorite(*:115)|is\\-favorite(*:135)|has\\-applied(*:155))|(*:164))|c(?|o(?|mpanies/([^/]++)(*:197)|nversations/([^/]++)/(?|messages(*:237)|read(*:249)))|andidate/(?|premium\\-services/(?|([^/]++)(*:300)|purchase(*:316)|my\\-services(*:336)|check\\-access/([^/]++)(*:366))|skill\\-tests/([^/]++)(?|(*:399)|/(?|calculate\\-score(*:427)|submit(*:441)))))|subscription\\-plans/([^/]++)(*:481)|a(?|dvertisements/([^/]++)/(?|impression(*:529)|click(*:542))|pplications/([^/]++)(?|(*:574)|/(?|status(*:592)|unlock\\-contact(*:615)|contact\\-status(*:638))))|video\\-stream/([^/]++)(*:671)|quick\\-services/([^/]++)(?|/(?|favorite(*:718)|is\\-favorite(*:738)|respon(?|d(*:756)|ses/([^/]++)/(?|accept(*:786)|reject(*:800))))|(*:811))|notifications/([^/]++)(?|/read(*:850)|(*:858))|recruiter/(?|jobs/([^/]++)(*:893)|skill\\-tests/([^/]++)(?|(*:925)|/publish(*:941)|(*:949)))|exam\\-pa(?|pers/([^/]++)(?|(*:986)|/(?|download(*:1006)|view(*:1019)))|cks/([^/]++)(?|(*:1045)|/(?|purchase(*:1066)|check\\-access(*:1088))))|training\\-packs/([^/]++)(?|(*:1127)|/(?|purchase(*:1148)|check\\-access(*:1170)|videos/([^/]++)(?|(*:1197)|/stream(*:1213))))|p(?|ayments/([^/]++)/status(*:1252)|ortfolio/by\\-slug/([^/]++)(*:1287)|rograms/([^/]++)(*:1312))|wallet/(?|payment\\-status/([^/]++)(*:1356)|withdrawal\\-status/([^/]++)(*:1392))|me/features/([^/]++)(*:1422))|dmin/(?|c(?|ompanies/([^/]++)(?|(*:1464)|/(?|edit(*:1481)|verify(*:1496)|suspend(*:1512))|(*:1522))|vtheque/([^/]++)(*:1548))|jobs/([^/]++)(?|(*:1574)|/(?|edit(*:1591)|publish(*:1607)|send\\-(?|notifications(?|(*:1641)|\\-batch(*:1657))|emails\\-batch(*:1680))|feature(*:1697))|(*:1707))|quick\\-services/([^/]++)(?|(*:1744)|/(?|status(*:1763)|approve(*:1779)))|a(?|pplications/([^/]++)(?|(*:1817)|/(?|status(*:1836)|verify\\-diploma(*:1860)))|d(?|mins/([^/]++)(?|(*:1891)|/(?|edit(*:1908)|permissions(*:1928))|(*:1938))|vertisements/([^/]++)(?|/(?|edit(*:1980)|toggle(*:1995))|(*:2005))))|users/([^/]++)(?|(*:2034)|/edit(*:2048)|(*:2057))|recruiter(?|s/([^/]++)(?|(*:2092)|/edit(*:2106)|(*:2115))|\\-services/([^/]++)(?|/(?|edit(*:2155)|toggle(*:2170))|(*:2180)))|s(?|e(?|ctions/([^/]++)(?|(*:2217)|/edit(*:2231)|(*:2240))|ttings/categories/([^/]++)(*:2276))|kill\\-tests/([^/]++)(?|(*:2309))|ubscription(?|\\-plans/(?|recruiters/([^/]++)(?|/edit(*:2371)|(*:2380))|job\\-seekers/([^/]++)(?|/edit(*:2419)|(*:2428)))|s/([^/]++)(?|(*:2452)|/(?|cancel(*:2471)|activate(*:2488))|(*:2498)))|tudents/([^/]++)(?|/(?|send\\-sms(*:2541)|edit(*:2554))|(*:2564)))|p(?|r(?|ograms/([^/]++)(?|(*:2601)|/(?|edit(*:2618)|manage\\-steps(*:2640)|steps(?|/([^/]++)(?|(*:2669))|(*:2679)))|(*:2690))|emium\\-services/([^/]++)(?|/(?|edit(*:2735)|toggle(*:2750))|(*:2760)))|ortfolios/([^/]++)(?|(*:2792)|/toggle\\-visibility(*:2820))|ayments/([^/]++)(?|(*:2849)|/(?|verify(*:2868)|refund(*:2883))))|manual\\-subscriptions/([^/]++)(*:2925)|wallets/(?|([^/]++)(?|(*:2956)|/(?|adjust(?|(*:2978))|bonus(?|(*:2996))))|transactions/([^/]++)/refund(*:3036))|exam\\-pa(?|cks/([^/]++)(?|/(?|edit(*:3080)|toggle(*:3095)|manage\\-papers(*:3118)|add\\-paper(*:3137)|remove\\-paper/([^/]++)(*:3168))|(*:3178))|pers/([^/]++)(?|/(?|edit(*:3212)|toggle(*:3227)|download(*:3244))|(*:3254)))|training\\-(?|videos/([^/]++)(?|/(?|edit(*:3304)|toggle(*:3319))|(*:3329))|packs/([^/]++)(?|/(?|edit(*:3364)|toggle(*:3379)|manage\\-videos(*:3402)|add\\-video(*:3421)|remove\\-video/([^/]++)(*:3452)|update\\-videos\\-order(*:3482))|(*:3492)))|fcm\\-tokens/(?|([^/]++)(?|(*:3529))|bulk\\-destroy(*:3552))|bank\\-account/withdrawal/([^/]++)/status(*:3602)))|/portfolio/([^/]++)(*:3632))/?$}sDu',
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
            '_route' => 'generated::52ITNZBbKVWjFqWk',
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
            '_route' => 'generated::i4vYMOyqjoMVarSI',
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
            '_route' => 'generated::riQwPUiw5b6MJXA6',
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
            '_route' => 'generated::QMreLkAZvAaDwyPz',
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
            '_route' => 'generated::RQXWzVuSJKOOo1ck',
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
            '_route' => 'generated::6sSyjPlAh0ZFBcBY',
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
            '_route' => 'generated::hVPr7hvV5yOlLX8Q',
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
            '_route' => 'generated::0lHF7koxkbsKI8VT',
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
            '_route' => 'generated::P4m27Pgn9l1ZTNq0',
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
            '_route' => 'generated::vwmxMNPeUAs4P72N',
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
            '_route' => 'generated::0hAg6uW46VL9leNT',
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
            '_route' => 'generated::N1CKBe5kmrkDsORD',
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
            '_route' => 'generated::FQGYYo5Eg8RyJa8z',
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
            '_route' => 'generated::AeEraaMaMpAekpHE',
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
            '_route' => 'generated::mQ1hWgtiQ02BIob2',
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
            '_route' => 'generated::laG3G8RKMJzPAyMW',
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
            '_route' => 'generated::LD5kVrf6Dem0NW8k',
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
            '_route' => 'generated::gBhLdWcf0nIoo32r',
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
            '_route' => 'generated::tMzuxhywIUYIJPRh',
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
            '_route' => 'generated::lxGIWREh32CvNOAY',
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
            '_route' => 'generated::xVghAGApCcYGJ3o3',
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
            '_route' => 'generated::6awUjJRmyoastQy5',
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
            '_route' => 'generated::krGd4jbrakXDY3UE',
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
            '_route' => 'generated::RAOguzLU3ZwTv8UZ',
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
            '_route' => 'generated::suQuh9nOniaWXYt6',
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
            '_route' => 'generated::4ueBkeme5ba3dLD2',
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
      671 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::qXPuDGKWsj1DxFzH',
          ),
          1 => 
          array (
            0 => 'videoId',
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
      718 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::AqEhc3mBnxOGgsce',
          ),
          1 => 
          array (
            0 => 'service',
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
      738 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::KSkAxAcLWdB5rVg2',
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
      756 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::OgOlmkDASOHmEhJi',
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
      786 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::tu31EiH8x0qZ1UnF',
          ),
          1 => 
          array (
            0 => 'serviceId',
            1 => 'responseId',
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
      800 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::toxUVnvH6DYV0Ujc',
          ),
          1 => 
          array (
            0 => 'serviceId',
            1 => 'responseId',
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
      811 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ay0oTPgAsp0Sx0Yj',
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
            '_route' => 'generated::mWLkPt95V3RQGAAD',
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
        2 => 
        array (
          0 => 
          array (
            '_route' => 'generated::mimj1F7JZkfPPOwA',
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
      850 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::NtBtSMgaNWf92vve',
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
      858 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::8gACcqQgaAQW5DnV',
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
      893 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::3VfyjQKngLaUr2IA',
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
      925 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::imSyvmhO6SW8VvjX',
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
            '_route' => 'generated::0uWLJZi9Mm8ng9vL',
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
      941 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::1BZBzYy6nyUQyrtV',
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
      949 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::6Bcz0jVL4yqWLYPo',
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
      986 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::JrKSHCGMhGRtHThO',
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
      1006 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::1hWHxcAm722M2SEd',
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
      1019 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::0wKrBos7VnoXoc5u',
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
      1045 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ABjMxznrz4tulRhH',
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
      1066 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::U13YX7EehaL4DPmT',
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
      1088 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::kdJdLHXueZEQ0f9I',
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
      1127 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ThkWPnPnIJRLG3hB',
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
      1148 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::azaPxr2AkFYjkGPQ',
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
      1170 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::MDfp76ZOEX0M5ijW',
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
      1197 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::TehOvE7FsUbqMSRc',
          ),
          1 => 
          array (
            0 => 'packId',
            1 => 'videoId',
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
      1213 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::2hvNrxvWYmWg6kR2',
          ),
          1 => 
          array (
            0 => 'packId',
            1 => 'videoId',
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
      1252 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::sj80dGfOavwLMeff',
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
      1287 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::YKkzB5mkpPfWStil',
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
      1312 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::gZ42oFXSGCUHhpHL',
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
      1356 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::bjf1Yg33vJba5GBt',
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
      1392 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::dBvpbIiXcNjNbu97',
          ),
          1 => 
          array (
            0 => 'withdrawalId',
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
      1422 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::6IYN2xz27piLsmmW',
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
      1464 => 
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
      1481 => 
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
      1496 => 
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
      1512 => 
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
      1522 => 
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
      1548 => 
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
      1574 => 
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
      1591 => 
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
      1607 => 
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
      1641 => 
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
      1657 => 
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
      1680 => 
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
      1697 => 
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
      1707 => 
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
      1744 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.quick-services.show',
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
            '_route' => 'admin.quick-services.destroy',
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
      1763 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.quick-services.status',
          ),
          1 => 
          array (
            0 => 'id',
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
      1779 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.quick-services.approve',
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
      1817 => 
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
      1836 => 
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
      1860 => 
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
      1891 => 
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
      1908 => 
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
      1928 => 
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
      1938 => 
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
      1980 => 
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
      1995 => 
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
      2005 => 
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
      2034 => 
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
      ),
      2048 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.users.edit',
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
      2057 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.users.update',
          ),
          1 => 
          array (
            0 => 'user',
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
      2092 => 
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
      2106 => 
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
      2115 => 
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
      2155 => 
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
      2170 => 
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
      2180 => 
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
      2217 => 
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
      2231 => 
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
      2240 => 
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
      2276 => 
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
      2309 => 
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
      2371 => 
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
      2380 => 
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
      2419 => 
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
      2428 => 
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
      2452 => 
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
      2471 => 
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
      2488 => 
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
      2498 => 
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
      2541 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.students.send-sms',
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
      2554 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.students.edit',
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
      2564 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.students.show',
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
            '_route' => 'admin.students.update',
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
        2 => 
        array (
          0 => 
          array (
            '_route' => 'admin.students.destroy',
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
      2601 => 
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
      2618 => 
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
      2640 => 
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
      2669 => 
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
      2679 => 
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
      2690 => 
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
      2735 => 
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
      2750 => 
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
      2760 => 
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
      2792 => 
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
      2820 => 
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
      2849 => 
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
      2868 => 
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
      2883 => 
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
      2925 => 
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
      2956 => 
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
      2978 => 
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
      2996 => 
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
      3036 => 
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
      3080 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.exam-packs.edit',
          ),
          1 => 
          array (
            0 => 'examPack',
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
      3095 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.exam-packs.toggle',
          ),
          1 => 
          array (
            0 => 'examPack',
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
      3118 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.exam-packs.manage-papers',
          ),
          1 => 
          array (
            0 => 'examPack',
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
      3137 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.exam-packs.add-paper',
          ),
          1 => 
          array (
            0 => 'examPack',
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
      3168 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.exam-packs.remove-paper',
          ),
          1 => 
          array (
            0 => 'examPack',
            1 => 'examPaper',
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
      3178 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.exam-packs.update',
          ),
          1 => 
          array (
            0 => 'examPack',
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
            '_route' => 'admin.exam-packs.destroy',
          ),
          1 => 
          array (
            0 => 'examPack',
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
            '_route' => 'admin.exam-packs.show',
          ),
          1 => 
          array (
            0 => 'examPack',
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
      3212 => 
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
      3227 => 
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
      3244 => 
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
      3254 => 
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
      3304 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.training-videos.edit',
          ),
          1 => 
          array (
            0 => 'trainingVideo',
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
      3319 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.training-videos.toggle',
          ),
          1 => 
          array (
            0 => 'trainingVideo',
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
      3329 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.training-videos.update',
          ),
          1 => 
          array (
            0 => 'trainingVideo',
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
            '_route' => 'admin.training-videos.destroy',
          ),
          1 => 
          array (
            0 => 'trainingVideo',
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
            '_route' => 'admin.training-videos.show',
          ),
          1 => 
          array (
            0 => 'trainingVideo',
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
      3364 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.training-packs.edit',
          ),
          1 => 
          array (
            0 => 'trainingPack',
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
      3379 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.training-packs.toggle',
          ),
          1 => 
          array (
            0 => 'trainingPack',
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
      3402 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.training-packs.manage-videos',
          ),
          1 => 
          array (
            0 => 'trainingPack',
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
      3421 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.training-packs.add-video',
          ),
          1 => 
          array (
            0 => 'trainingPack',
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
      3452 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.training-packs.remove-video',
          ),
          1 => 
          array (
            0 => 'trainingPack',
            1 => 'trainingVideo',
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
      3482 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.training-packs.update-videos-order',
          ),
          1 => 
          array (
            0 => 'trainingPack',
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
      3492 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.training-packs.update',
          ),
          1 => 
          array (
            0 => 'trainingPack',
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
            '_route' => 'admin.training-packs.destroy',
          ),
          1 => 
          array (
            0 => 'trainingPack',
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
            '_route' => 'admin.training-packs.show',
          ),
          1 => 
          array (
            0 => 'trainingPack',
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
      3529 => 
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
      3552 => 
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
      3602 => 
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
      3632 => 
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
    'ignition.healthCheck' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '_ignition/health-check',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'Spatie\\LaravelIgnition\\Http\\Middleware\\RunnableSolutionsEnabled',
        ),
        'uses' => 'Spatie\\LaravelIgnition\\Http\\Controllers\\HealthCheckController@__invoke',
        'controller' => 'Spatie\\LaravelIgnition\\Http\\Controllers\\HealthCheckController',
        'as' => 'ignition.healthCheck',
        'namespace' => NULL,
        'prefix' => '_ignition',
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
    'ignition.executeSolution' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => '_ignition/execute-solution',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'Spatie\\LaravelIgnition\\Http\\Middleware\\RunnableSolutionsEnabled',
        ),
        'uses' => 'Spatie\\LaravelIgnition\\Http\\Controllers\\ExecuteSolutionController@__invoke',
        'controller' => 'Spatie\\LaravelIgnition\\Http\\Controllers\\ExecuteSolutionController',
        'as' => 'ignition.executeSolution',
        'namespace' => NULL,
        'prefix' => '_ignition',
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
    'ignition.updateConfig' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => '_ignition/update-config',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'Spatie\\LaravelIgnition\\Http\\Middleware\\RunnableSolutionsEnabled',
        ),
        'uses' => 'Spatie\\LaravelIgnition\\Http\\Controllers\\UpdateConfigController@__invoke',
        'controller' => 'Spatie\\LaravelIgnition\\Http\\Controllers\\UpdateConfigController',
        'as' => 'ignition.updateConfig',
        'namespace' => NULL,
        'prefix' => '_ignition',
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
    'generated::LizoKrFnLJPkJQIu' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/check-availability',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AuthController@checkAvailability',
        'controller' => 'App\\Http\\Controllers\\Api\\AuthController@checkAvailability',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::LizoKrFnLJPkJQIu',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::VZqg7pLcCeA6jggV' => 
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
        'as' => 'generated::VZqg7pLcCeA6jggV',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::uWGlUEclxto2dmUb' => 
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
        'as' => 'generated::uWGlUEclxto2dmUb',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::RWrwcTyaYOgfcm7p' => 
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
        'as' => 'generated::RWrwcTyaYOgfcm7p',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::XpMdE74VI29ohELm' => 
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
        'as' => 'generated::XpMdE74VI29ohELm',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::qk1M5kKimVPzzIlU' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/password/force-change',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AuthController@forceChangePassword',
        'controller' => 'App\\Http\\Controllers\\Api\\AuthController@forceChangePassword',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::qk1M5kKimVPzzIlU',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::MsajAZes4fWqPxOY' => 
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
        'as' => 'generated::MsajAZes4fWqPxOY',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::1CZLiY05S9JZ6DjV' => 
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
        'as' => 'generated::1CZLiY05S9JZ6DjV',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::eW8vBaWX7EUR7jkv' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/otp/send',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\OtpController@sendOtp',
        'controller' => 'App\\Http\\Controllers\\Api\\OtpController@sendOtp',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::eW8vBaWX7EUR7jkv',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::aY3dn3v7GyVYgdNW' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/otp/verify',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\OtpController@verifyOtp',
        'controller' => 'App\\Http\\Controllers\\Api\\OtpController@verifyOtp',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::aY3dn3v7GyVYgdNW',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ro5FlNsp1Exo1h3G' => 
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
        'as' => 'generated::ro5FlNsp1Exo1h3G',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::IYRUdl4hZKI4hM24' => 
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
        'as' => 'generated::IYRUdl4hZKI4hM24',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::5DSd1CTZJn5bmCoy' => 
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
        'as' => 'generated::5DSd1CTZJn5bmCoy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::52ITNZBbKVWjFqWk' => 
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
        'as' => 'generated::52ITNZBbKVWjFqWk',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::b01hvH4QyHuiHKla' => 
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
        'as' => 'generated::b01hvH4QyHuiHKla',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::QZ8Re2NSUi6ESehI' => 
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
        'as' => 'generated::QZ8Re2NSUi6ESehI',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::P4m27Pgn9l1ZTNq0' => 
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
        'as' => 'generated::P4m27Pgn9l1ZTNq0',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::8sSmH1unKizXgw9Q' => 
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
        'as' => 'generated::8sSmH1unKizXgw9Q',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::HE9SVXi21mmUfxVz' => 
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
        'as' => 'generated::HE9SVXi21mmUfxVz',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Eb7n4iwFfLgLCerY' => 
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
        'as' => 'generated::Eb7n4iwFfLgLCerY',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::UF8P7s7VJFgQEXq5' => 
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
        'as' => 'generated::UF8P7s7VJFgQEXq5',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::tMzuxhywIUYIJPRh' => 
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
        'as' => 'generated::tMzuxhywIUYIJPRh',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::u1Wr0DQBJlxossEU' => 
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
        'as' => 'generated::u1Wr0DQBJlxossEU',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::lxGIWREh32CvNOAY' => 
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
        'as' => 'generated::lxGIWREh32CvNOAY',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::xVghAGApCcYGJ3o3' => 
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
        'as' => 'generated::xVghAGApCcYGJ3o3',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::7VKyt7atU9cgxS5C' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/service-categories',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\QuickServiceController@categories',
        'controller' => 'App\\Http\\Controllers\\Api\\QuickServiceController@categories',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::7VKyt7atU9cgxS5C',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::qXPuDGKWsj1DxFzH' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/video-stream/{videoId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TrainingPackApiController@streamVideoPublic',
        'controller' => 'App\\Http\\Controllers\\Api\\TrainingPackApiController@streamVideoPublic',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::qXPuDGKWsj1DxFzH',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::5m2alfVgIuWgjBZf' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AuthController@logout',
        'controller' => 'App\\Http\\Controllers\\Api\\AuthController@logout',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::5m2alfVgIuWgjBZf',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::wzV4dyMpj3DBMGbe' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AuthController@user',
        'controller' => 'App\\Http\\Controllers\\Api\\AuthController@user',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::wzV4dyMpj3DBMGbe',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::kLwtOI7kr44nODEv' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AuthController@updateRole',
        'controller' => 'App\\Http\\Controllers\\Api\\AuthController@updateRole',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::kLwtOI7kr44nODEv',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::XYRVE9Z9cgQAZS0w' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AuthController@updateProfile',
        'controller' => 'App\\Http\\Controllers\\Api\\AuthController@updateProfile',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::XYRVE9Z9cgQAZS0w',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::loTaZgk8Qzdi1YwP' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AuthController@statistics',
        'controller' => 'App\\Http\\Controllers\\Api\\AuthController@statistics',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::loTaZgk8Qzdi1YwP',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::WrmMrQvn3LQ8gov5' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AuthController@syncRoleWithSubscription',
        'controller' => 'App\\Http\\Controllers\\Api\\AuthController@syncRoleWithSubscription',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::WrmMrQvn3LQ8gov5',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::y5RPq1omeHLy67Q5' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AuthController@switchRole',
        'controller' => 'App\\Http\\Controllers\\Api\\AuthController@switchRole',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::y5RPq1omeHLy67Q5',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::a6dydXLH9c3tiBYH' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AuthController@deleteAccount',
        'controller' => 'App\\Http\\Controllers\\Api\\AuthController@deleteAccount',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::a6dydXLH9c3tiBYH',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::GHpKHykXlEPnAiXR' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AuthController@getSubscriptionStatus',
        'controller' => 'App\\Http\\Controllers\\Api\\AuthController@getSubscriptionStatus',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::GHpKHykXlEPnAiXR',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::i4vYMOyqjoMVarSI' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ApplicationController@apply',
        'controller' => 'App\\Http\\Controllers\\Api\\ApplicationController@apply',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::i4vYMOyqjoMVarSI',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::riQwPUiw5b6MJXA6' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ApplicationController@applyWithTest',
        'controller' => 'App\\Http\\Controllers\\Api\\ApplicationController@applyWithTest',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::riQwPUiw5b6MJXA6',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::mHzhqeyM2sTrKRuV' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ApplicationController@myApplicationsStats',
        'controller' => 'App\\Http\\Controllers\\Api\\ApplicationController@myApplicationsStats',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::mHzhqeyM2sTrKRuV',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::c2ja1ZcCN5EBXoc8' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ApplicationController@myApplications',
        'controller' => 'App\\Http\\Controllers\\Api\\ApplicationController@myApplications',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::c2ja1ZcCN5EBXoc8',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::6awUjJRmyoastQy5' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ApplicationController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\ApplicationController@show',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::6awUjJRmyoastQy5',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::krGd4jbrakXDY3UE' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ApplicationController@destroy',
        'controller' => 'App\\Http\\Controllers\\Api\\ApplicationController@destroy',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::krGd4jbrakXDY3UE',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ZSQVjmjBruGmmLAE' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\FavoriteController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\FavoriteController@index',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::ZSQVjmjBruGmmLAE',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::QMreLkAZvAaDwyPz' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\FavoriteController@toggle',
        'controller' => 'App\\Http\\Controllers\\Api\\FavoriteController@toggle',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::QMreLkAZvAaDwyPz',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::RQXWzVuSJKOOo1ck' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\FavoriteController@isFavorite',
        'controller' => 'App\\Http\\Controllers\\Api\\FavoriteController@isFavorite',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::RQXWzVuSJKOOo1ck',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::mdPlAkKHLauMEXmE' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/quick-services/favorites',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\FavoriteController@getFavoriteQuickServices',
        'controller' => 'App\\Http\\Controllers\\Api\\FavoriteController@getFavoriteQuickServices',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::mdPlAkKHLauMEXmE',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::AqEhc3mBnxOGgsce' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/quick-services/{service}/favorite',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\FavoriteController@toggleQuickServiceFavorite',
        'controller' => 'App\\Http\\Controllers\\Api\\FavoriteController@toggleQuickServiceFavorite',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::AqEhc3mBnxOGgsce',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::KSkAxAcLWdB5rVg2' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/quick-services/{service}/is-favorite',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\FavoriteController@isQuickServiceFavorite',
        'controller' => 'App\\Http\\Controllers\\Api\\FavoriteController@isQuickServiceFavorite',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::KSkAxAcLWdB5rVg2',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::6sSyjPlAh0ZFBcBY' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\JobController@hasApplied',
        'controller' => 'App\\Http\\Controllers\\Api\\JobController@hasApplied',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::6sSyjPlAh0ZFBcBY',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::USBekWtswm8NsYWI' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\NotificationController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\NotificationController@index',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::USBekWtswm8NsYWI',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::k04bBWqIbyzXAiYS' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\NotificationController@unreadCount',
        'controller' => 'App\\Http\\Controllers\\Api\\NotificationController@unreadCount',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::k04bBWqIbyzXAiYS',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::NtBtSMgaNWf92vve' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\NotificationController@markAsRead',
        'controller' => 'App\\Http\\Controllers\\Api\\NotificationController@markAsRead',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::NtBtSMgaNWf92vve',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::iBNlmj89WNl38XB4' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\NotificationController@markAllAsRead',
        'controller' => 'App\\Http\\Controllers\\Api\\NotificationController@markAllAsRead',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::iBNlmj89WNl38XB4',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::8gACcqQgaAQW5DnV' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\NotificationController@destroy',
        'controller' => 'App\\Http\\Controllers\\Api\\NotificationController@destroy',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::8gACcqQgaAQW5DnV',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::0shx2LpOJrKQ0Ude' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\UserController@saveFcmToken',
        'controller' => 'App\\Http\\Controllers\\Admin\\UserController@saveFcmToken',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::0shx2LpOJrKQ0Ude',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::tOFunbG9FEtJhA0Q' => 
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
          3 => 'must.change.password',
          4 => 'subscription:can_post_job',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\JobController@store',
        'controller' => 'App\\Http\\Controllers\\Api\\JobController@store',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::tOFunbG9FEtJhA0Q',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::hVPr7hvV5yOlLX8Q' => 
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
          3 => 'must.change.password',
          4 => 'subscription:valid',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\JobController@update',
        'controller' => 'App\\Http\\Controllers\\Api\\JobController@update',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::hVPr7hvV5yOlLX8Q',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::0lHF7koxkbsKI8VT' => 
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
          3 => 'must.change.password',
          4 => 'subscription:valid',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\JobController@destroy',
        'controller' => 'App\\Http\\Controllers\\Api\\JobController@destroy',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::0lHF7koxkbsKI8VT',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::3I5u0zN6DP6PqkT4' => 
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
          3 => 'must.change.password',
          4 => 'subscription:valid',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\JobController@myJobs',
        'controller' => 'App\\Http\\Controllers\\Api\\JobController@myJobs',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::3I5u0zN6DP6PqkT4',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::3VfyjQKngLaUr2IA' => 
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
          3 => 'must.change.password',
          4 => 'subscription:valid',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\JobController@showRecruiterJob',
        'controller' => 'App\\Http\\Controllers\\Api\\JobController@showRecruiterJob',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::3VfyjQKngLaUr2IA',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::mJtgpE3bWsDk9nPe' => 
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
          3 => 'must.change.password',
          4 => 'subscription:valid',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\JobController@dashboard',
        'controller' => 'App\\Http\\Controllers\\Api\\JobController@dashboard',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::mJtgpE3bWsDk9nPe',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::9xoQjdmbbeWn6z6Z' => 
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
          3 => 'must.change.password',
          4 => 'subscription:valid',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ApplicationController@receivedApplications',
        'controller' => 'App\\Http\\Controllers\\Api\\ApplicationController@receivedApplications',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::9xoQjdmbbeWn6z6Z',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::RAOguzLU3ZwTv8UZ' => 
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
          3 => 'must.change.password',
          4 => 'subscription:valid',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ApplicationController@updateStatus',
        'controller' => 'App\\Http\\Controllers\\Api\\ApplicationController@updateStatus',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::RAOguzLU3ZwTv8UZ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::suQuh9nOniaWXYt6' => 
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
          3 => 'must.change.password',
          4 => 'subscription:can_contact',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ApplicationController@unlockContact',
        'controller' => 'App\\Http\\Controllers\\Api\\ApplicationController@unlockContact',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::suQuh9nOniaWXYt6',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::4ueBkeme5ba3dLD2' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ApplicationController@contactStatus',
        'controller' => 'App\\Http\\Controllers\\Api\\ApplicationController@contactStatus',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::4ueBkeme5ba3dLD2',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::c4Bt7vDwTKQbiAEz' => 
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
          3 => 'must.change.password',
          4 => 'subscription:valid',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\RecruiterServicePurchaseController@purchaseCandidateContact',
        'controller' => 'App\\Http\\Controllers\\Api\\RecruiterServicePurchaseController@purchaseCandidateContact',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::c4Bt7vDwTKQbiAEz',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::RxSXlPqJHl78pYas' => 
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
          3 => 'must.change.password',
          4 => 'subscription:valid',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\RecruiterServicePurchaseController@purchaseDiplomaVerification',
        'controller' => 'App\\Http\\Controllers\\Api\\RecruiterServicePurchaseController@purchaseDiplomaVerification',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::RxSXlPqJHl78pYas',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::MtN3ZqR92mmdfPoz' => 
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
          3 => 'must.change.password',
          4 => 'subscription:valid',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\RecruiterServicePurchaseController@purchaseSkillsTest',
        'controller' => 'App\\Http\\Controllers\\Api\\RecruiterServicePurchaseController@purchaseSkillsTest',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::MtN3ZqR92mmdfPoz',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::jADXfNUc4qwRcWnK' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\RecruiterServicePurchaseController@checkAccessStatus',
        'controller' => 'App\\Http\\Controllers\\Api\\RecruiterServicePurchaseController@checkAccessStatus',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::jADXfNUc4qwRcWnK',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::zheLJcOYNNo7OYgf' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\CandidatePremiumServiceController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\CandidatePremiumServiceController@index',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::zheLJcOYNNo7OYgf',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::N1CKBe5kmrkDsORD' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\CandidatePremiumServiceController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\CandidatePremiumServiceController@show',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::N1CKBe5kmrkDsORD',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::FQGYYo5Eg8RyJa8z' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\CandidatePremiumServiceController@purchase',
        'controller' => 'App\\Http\\Controllers\\Api\\CandidatePremiumServiceController@purchase',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::FQGYYo5Eg8RyJa8z',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::AeEraaMaMpAekpHE' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\CandidatePremiumServiceController@myServices',
        'controller' => 'App\\Http\\Controllers\\Api\\CandidatePremiumServiceController@myServices',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::AeEraaMaMpAekpHE',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::mQ1hWgtiQ02BIob2' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\CandidatePremiumServiceController@checkAccess',
        'controller' => 'App\\Http\\Controllers\\Api\\CandidatePremiumServiceController@checkAccess',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::mQ1hWgtiQ02BIob2',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::f5XHfesmnFLwIFat' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ExamPaperApiController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\ExamPaperApiController@index',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::f5XHfesmnFLwIFat',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::kjC4GJBOx0SalKQq' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ExamPaperApiController@filters',
        'controller' => 'App\\Http\\Controllers\\Api\\ExamPaperApiController@filters',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::kjC4GJBOx0SalKQq',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::QqeSbQHEwXAMisL8' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ExamPaperApiController@stats',
        'controller' => 'App\\Http\\Controllers\\Api\\ExamPaperApiController@stats',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::QqeSbQHEwXAMisL8',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::JrKSHCGMhGRtHThO' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ExamPaperApiController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\ExamPaperApiController@show',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::JrKSHCGMhGRtHThO',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::1hWHxcAm722M2SEd' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ExamPaperApiController@download',
        'controller' => 'App\\Http\\Controllers\\Api\\ExamPaperApiController@download',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::1hWHxcAm722M2SEd',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::0wKrBos7VnoXoc5u' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ExamPaperApiController@viewPdf',
        'controller' => 'App\\Http\\Controllers\\Api\\ExamPaperApiController@viewPdf',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::0wKrBos7VnoXoc5u',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::J8H7aE7NWpXV8yRP' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/exam-packs',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ExamPackApiController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\ExamPackApiController@index',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::J8H7aE7NWpXV8yRP',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::DEFATaK6PUJaV45M' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/exam-packs/filters',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ExamPackApiController@filters',
        'controller' => 'App\\Http\\Controllers\\Api\\ExamPackApiController@filters',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::DEFATaK6PUJaV45M',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ABjMxznrz4tulRhH' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/exam-packs/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ExamPackApiController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\ExamPackApiController@show',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::ABjMxznrz4tulRhH',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::U13YX7EehaL4DPmT' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/exam-packs/{id}/purchase',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ExamPackApiController@purchase',
        'controller' => 'App\\Http\\Controllers\\Api\\ExamPackApiController@purchase',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::U13YX7EehaL4DPmT',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::2pxxv5aYRKxqRIMD' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/my-exam-packs',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ExamPackApiController@myPurchases',
        'controller' => 'App\\Http\\Controllers\\Api\\ExamPackApiController@myPurchases',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::2pxxv5aYRKxqRIMD',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::kdJdLHXueZEQ0f9I' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/exam-packs/{id}/check-access',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ExamPackApiController@checkAccess',
        'controller' => 'App\\Http\\Controllers\\Api\\ExamPackApiController@checkAccess',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::kdJdLHXueZEQ0f9I',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::fppwShCubumPEMzT' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/training-packs',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TrainingPackApiController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\TrainingPackApiController@index',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::fppwShCubumPEMzT',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::2VWHE3hRfCTRhFSX' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/training-packs/filters',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TrainingPackApiController@filters',
        'controller' => 'App\\Http\\Controllers\\Api\\TrainingPackApiController@filters',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::2VWHE3hRfCTRhFSX',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ThkWPnPnIJRLG3hB' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/training-packs/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TrainingPackApiController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\TrainingPackApiController@show',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::ThkWPnPnIJRLG3hB',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::azaPxr2AkFYjkGPQ' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/training-packs/{id}/purchase',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TrainingPackApiController@purchase',
        'controller' => 'App\\Http\\Controllers\\Api\\TrainingPackApiController@purchase',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::azaPxr2AkFYjkGPQ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::oROhnjstrBeO1uIT' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/my-training-packs',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TrainingPackApiController@myPurchases',
        'controller' => 'App\\Http\\Controllers\\Api\\TrainingPackApiController@myPurchases',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::oROhnjstrBeO1uIT',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::MDfp76ZOEX0M5ijW' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/training-packs/{id}/check-access',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TrainingPackApiController@checkAccess',
        'controller' => 'App\\Http\\Controllers\\Api\\TrainingPackApiController@checkAccess',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::MDfp76ZOEX0M5ijW',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::TehOvE7FsUbqMSRc' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/training-packs/{packId}/videos/{videoId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TrainingPackApiController@viewVideo',
        'controller' => 'App\\Http\\Controllers\\Api\\TrainingPackApiController@viewVideo',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::TehOvE7FsUbqMSRc',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::2hvNrxvWYmWg6kR2' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/training-packs/{packId}/videos/{videoId}/stream',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TrainingPackApiController@streamVideo',
        'controller' => 'App\\Http\\Controllers\\Api\\TrainingPackApiController@streamVideo',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::2hvNrxvWYmWg6kR2',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Ddn5fpwrG4oYdN2l' => 
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
          3 => 'must.change.password',
          4 => 'subscription:valid',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\RecruiterSkillTestController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\RecruiterSkillTestController@index',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::Ddn5fpwrG4oYdN2l',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::imSyvmhO6SW8VvjX' => 
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
          3 => 'must.change.password',
          4 => 'subscription:valid',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\RecruiterSkillTestController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\RecruiterSkillTestController@show',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::imSyvmhO6SW8VvjX',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::7BG0I4GqrXTGHQsr' => 
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
          3 => 'must.change.password',
          4 => 'subscription:valid',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\RecruiterSkillTestController@store',
        'controller' => 'App\\Http\\Controllers\\Api\\RecruiterSkillTestController@store',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::7BG0I4GqrXTGHQsr',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::0uWLJZi9Mm8ng9vL' => 
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
          3 => 'must.change.password',
          4 => 'subscription:valid',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\RecruiterSkillTestController@update',
        'controller' => 'App\\Http\\Controllers\\Api\\RecruiterSkillTestController@update',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::0uWLJZi9Mm8ng9vL',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::1BZBzYy6nyUQyrtV' => 
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
          3 => 'must.change.password',
          4 => 'subscription:valid',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\RecruiterSkillTestController@publish',
        'controller' => 'App\\Http\\Controllers\\Api\\RecruiterSkillTestController@publish',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::1BZBzYy6nyUQyrtV',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::6Bcz0jVL4yqWLYPo' => 
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
          3 => 'must.change.password',
          4 => 'subscription:valid',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\RecruiterSkillTestController@destroy',
        'controller' => 'App\\Http\\Controllers\\Api\\RecruiterSkillTestController@destroy',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::6Bcz0jVL4yqWLYPo',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::laG3G8RKMJzPAyMW' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\RecruiterSkillTestController@getTestForCandidate',
        'controller' => 'App\\Http\\Controllers\\Api\\RecruiterSkillTestController@getTestForCandidate',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::laG3G8RKMJzPAyMW',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::LD5kVrf6Dem0NW8k' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\RecruiterSkillTestController@calculateScoreOnly',
        'controller' => 'App\\Http\\Controllers\\Api\\RecruiterSkillTestController@calculateScoreOnly',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::LD5kVrf6Dem0NW8k',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::gBhLdWcf0nIoo32r' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\RecruiterSkillTestController@submitTestResults',
        'controller' => 'App\\Http\\Controllers\\Api\\RecruiterSkillTestController@submitTestResults',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::gBhLdWcf0nIoo32r',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::7SMGBNkEZEZ5HbSj' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\CompanyController@store',
        'controller' => 'App\\Http\\Controllers\\Api\\CompanyController@store',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::7SMGBNkEZEZ5HbSj',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::enCzLJHbfPPaIlHu' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\CompanyController@myCompany',
        'controller' => 'App\\Http\\Controllers\\Api\\CompanyController@myCompany',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::enCzLJHbfPPaIlHu',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::qDLSQJwzREiucUYK' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\CompanyController@updateMyCompany',
        'controller' => 'App\\Http\\Controllers\\Api\\CompanyController@updateMyCompany',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::qDLSQJwzREiucUYK',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::FGeFEquJpfojYx5b' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SubscriptionPlanController@initPayment',
        'controller' => 'App\\Http\\Controllers\\Api\\SubscriptionPlanController@initPayment',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::FGeFEquJpfojYx5b',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::RwBQuhqKAlRNv7Yq' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SubscriptionPlanController@executePayPalPayment',
        'controller' => 'App\\Http\\Controllers\\Api\\SubscriptionPlanController@executePayPalPayment',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::RwBQuhqKAlRNv7Yq',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::sj80dGfOavwLMeff' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SubscriptionPlanController@checkPaymentStatus',
        'controller' => 'App\\Http\\Controllers\\Api\\SubscriptionPlanController@checkPaymentStatus',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::sj80dGfOavwLMeff',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::SnuYatGHWBTE3ayV' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SubscriptionPlanController@activate',
        'controller' => 'App\\Http\\Controllers\\Api\\SubscriptionPlanController@activate',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::SnuYatGHWBTE3ayV',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::QFborJSwtXsUOfq9' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SubscriptionPlanController@payWithWallet',
        'controller' => 'App\\Http\\Controllers\\Api\\SubscriptionPlanController@payWithWallet',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::QFborJSwtXsUOfq9',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::9XNXyNWxhUywuw5L' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SubscriptionPlanController@mySubscription',
        'controller' => 'App\\Http\\Controllers\\Api\\SubscriptionPlanController@mySubscription',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::9XNXyNWxhUywuw5L',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::qaRjKoLTqc5Vso4g' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SubscriptionPlanController@mySubscriptions',
        'controller' => 'App\\Http\\Controllers\\Api\\SubscriptionPlanController@mySubscriptions',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::qaRjKoLTqc5Vso4g',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::QEE1lEXR3joBnvCN' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SubscriptionPlanController@subscriptionStatus',
        'controller' => 'App\\Http\\Controllers\\Api\\SubscriptionPlanController@subscriptionStatus',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::QEE1lEXR3joBnvCN',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::DGcYciKmajxuQFEd' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SubscriptionPlanController@subscriptionUsage',
        'controller' => 'App\\Http\\Controllers\\Api\\SubscriptionPlanController@subscriptionUsage',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::DGcYciKmajxuQFEd',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::a5NJcYaBGt8EVC5G' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\WalletController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\WalletController@index',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::a5NJcYaBGt8EVC5G',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::l0KhRfZwFgBX8CRq' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\WalletController@transactions',
        'controller' => 'App\\Http\\Controllers\\Api\\WalletController@transactions',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::l0KhRfZwFgBX8CRq',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::NNM85kh32FcuaYR5' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\WalletController@recharge',
        'controller' => 'App\\Http\\Controllers\\Api\\WalletController@recharge',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::NNM85kh32FcuaYR5',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::0vv0JX5vHvwerefb' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\WalletController@executePayPalPayment',
        'controller' => 'App\\Http\\Controllers\\Api\\WalletController@executePayPalPayment',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::0vv0JX5vHvwerefb',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::uAsT8WElA4LUs7VE' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\WalletController@createNativePayPalOrder',
        'controller' => 'App\\Http\\Controllers\\Api\\WalletController@createNativePayPalOrder',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::uAsT8WElA4LUs7VE',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::4qTNcgBucPmAz6uW' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\WalletController@captureNativePayPalOrder',
        'controller' => 'App\\Http\\Controllers\\Api\\WalletController@captureNativePayPalOrder',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::4qTNcgBucPmAz6uW',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::bjf1Yg33vJba5GBt' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\WalletController@checkPaymentStatus',
        'controller' => 'App\\Http\\Controllers\\Api\\WalletController@checkPaymentStatus',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::bjf1Yg33vJba5GBt',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Row3KmQIbwKZ1dqe' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\WalletController@canPay',
        'controller' => 'App\\Http\\Controllers\\Api\\WalletController@canPay',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::Row3KmQIbwKZ1dqe',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::kT5k65wHJwJtCUDp' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\WalletController@pay',
        'controller' => 'App\\Http\\Controllers\\Api\\WalletController@pay',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::kT5k65wHJwJtCUDp',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::1sLBOLBJMy1b78yj' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/wallet/withdrawal-balances',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\WalletController@getWithdrawalBalances',
        'controller' => 'App\\Http\\Controllers\\Api\\WalletController@getWithdrawalBalances',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::1sLBOLBJMy1b78yj',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::XztQOQr6S4HuErrZ' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/wallet/withdraw/freemopay',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\WalletController@initiateFreeMoPayWithdrawal',
        'controller' => 'App\\Http\\Controllers\\Api\\WalletController@initiateFreeMoPayWithdrawal',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::XztQOQr6S4HuErrZ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::A1oLcJMM6tUyjP1r' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/wallet/withdraw/paypal',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\WalletController@initiatePayPalWithdrawal',
        'controller' => 'App\\Http\\Controllers\\Api\\WalletController@initiatePayPalWithdrawal',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::A1oLcJMM6tUyjP1r',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::dBvpbIiXcNjNbu97' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/wallet/withdrawal-status/{withdrawalId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\WalletController@checkWithdrawalStatus',
        'controller' => 'App\\Http\\Controllers\\Api\\WalletController@checkWithdrawalStatus',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::dBvpbIiXcNjNbu97',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::4lMm3iOp1YWX9njR' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/wallet/withdrawals',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\WalletController@getWithdrawalHistory',
        'controller' => 'App\\Http\\Controllers\\Api\\WalletController@getWithdrawalHistory',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::4lMm3iOp1YWX9njR',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::AMW2XYj5rzc6cF9p' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\CurrencyController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\CurrencyController@index',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::AMW2XYj5rzc6cF9p',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::o0UIdWENQVbCnJM1' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\CurrencyController@rates',
        'controller' => 'App\\Http\\Controllers\\Api\\CurrencyController@rates',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::o0UIdWENQVbCnJM1',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::V8JByAPpZQUUVbHp' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\CurrencyController@convert',
        'controller' => 'App\\Http\\Controllers\\Api\\CurrencyController@convert',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::V8JByAPpZQUUVbHp',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::2y6XnPL8sDvGoBLD' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\CurrencyController@updateUserCurrency',
        'controller' => 'App\\Http\\Controllers\\Api\\CurrencyController@updateUserCurrency',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::2y6XnPL8sDvGoBLD',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::qLIHXOZzCWNOmahe' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\UserRoleController@getAvailableRoles',
        'controller' => 'App\\Http\\Controllers\\Api\\UserRoleController@getAvailableRoles',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::qLIHXOZzCWNOmahe',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::kfXf71bcBaqDJxst' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\UserRoleController@switchRole',
        'controller' => 'App\\Http\\Controllers\\Api\\UserRoleController@switchRole',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::kfXf71bcBaqDJxst',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::mKVa9b8LOiczHQ2h' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\UserRoleController@getFeatures',
        'controller' => 'App\\Http\\Controllers\\Api\\UserRoleController@getFeatures',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::mKVa9b8LOiczHQ2h',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::6IYN2xz27piLsmmW' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\UserRoleController@checkFeature',
        'controller' => 'App\\Http\\Controllers\\Api\\UserRoleController@checkFeature',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::6IYN2xz27piLsmmW',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::wAMAiVM01IpDh4YF' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\UserRoleController@syncFeatures',
        'controller' => 'App\\Http\\Controllers\\Api\\UserRoleController@syncFeatures',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::wAMAiVM01IpDh4YF',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::rz9RwpumBfPSTAqD' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ConversationController@getConversationsList',
        'controller' => 'App\\Http\\Controllers\\Api\\ConversationController@getConversationsList',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::rz9RwpumBfPSTAqD',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::oMaHos83qjLRv97h' => 
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
          3 => 'must.change.password',
          4 => 'subscription:can_contact',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ConversationController@store',
        'controller' => 'App\\Http\\Controllers\\Api\\ConversationController@store',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::oMaHos83qjLRv97h',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::9uRBUMsjuSgYiD3x' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/conversations/service',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ConversationController@getOrCreateServiceConversation',
        'controller' => 'App\\Http\\Controllers\\Api\\ConversationController@getOrCreateServiceConversation',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::9uRBUMsjuSgYiD3x',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::vwmxMNPeUAs4P72N' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ChatController@getMessages',
        'controller' => 'App\\Http\\Controllers\\Api\\ChatController@getMessages',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::vwmxMNPeUAs4P72N',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::9rNAaJITNe75DHDK' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ChatController@send',
        'controller' => 'App\\Http\\Controllers\\Api\\ChatController@send',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::9rNAaJITNe75DHDK',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::0hAg6uW46VL9leNT' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ChatController@markRead',
        'controller' => 'App\\Http\\Controllers\\Api\\ChatController@markRead',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::0hAg6uW46VL9leNT',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::OLOOJJ7xIelHvky3' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ChatController@typing',
        'controller' => 'App\\Http\\Controllers\\Api\\ChatController@typing',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::OLOOJJ7xIelHvky3',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::lgXHCn5vfmuuGRys' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ChatController@online',
        'controller' => 'App\\Http\\Controllers\\Api\\ChatController@online',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::lgXHCn5vfmuuGRys',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::qIwoWq01vK77NFto' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ChatController@offline',
        'controller' => 'App\\Http\\Controllers\\Api\\ChatController@offline',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::qIwoWq01vK77NFto',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::B0ZRQskSyTKLJqLJ' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PortfolioController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\PortfolioController@show',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::B0ZRQskSyTKLJqLJ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::kOkDNW26yjfBvxPu' => 
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
          3 => 'must.change.password',
          4 => 'App\\Http\\Middleware\\CheckPortfolioAccess',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PortfolioController@store',
        'controller' => 'App\\Http\\Controllers\\Api\\PortfolioController@store',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::kOkDNW26yjfBvxPu',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::0LbOAm0H2bNpWSOd' => 
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
          3 => 'must.change.password',
          4 => 'App\\Http\\Middleware\\CheckPortfolioAccess',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PortfolioController@update',
        'controller' => 'App\\Http\\Controllers\\Api\\PortfolioController@update',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::0LbOAm0H2bNpWSOd',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::h7jDzmKlGVY0jpSj' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PortfolioController@destroy',
        'controller' => 'App\\Http\\Controllers\\Api\\PortfolioController@destroy',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::h7jDzmKlGVY0jpSj',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::yQklm9U3E5SX0y1W' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PortfolioController@toggleVisibility',
        'controller' => 'App\\Http\\Controllers\\Api\\PortfolioController@toggleVisibility',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::yQklm9U3E5SX0y1W',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::aXc2Ul40B4vqDYTr' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PortfolioController@stats',
        'controller' => 'App\\Http\\Controllers\\Api\\PortfolioController@stats',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::aXc2Ul40B4vqDYTr',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::YKkzB5mkpPfWStil' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PortfolioController@showBySlug',
        'controller' => 'App\\Http\\Controllers\\Api\\PortfolioController@showBySlug',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::YKkzB5mkpPfWStil',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::PBgtm7jlDcBHvyAv' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ProgramController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\ProgramController@index',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::PBgtm7jlDcBHvyAv',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::xzAqMqywZHEt9k39' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ProgramController@checkAccess',
        'controller' => 'App\\Http\\Controllers\\Api\\ProgramController@checkAccess',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::xzAqMqywZHEt9k39',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::gZ42oFXSGCUHhpHL' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ProgramController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\ProgramController@show',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::gZ42oFXSGCUHhpHL',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::eAD9zZuLB3O3RkuN' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/quick-services/categories',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\QuickServiceController@categories',
        'controller' => 'App\\Http\\Controllers\\Api\\QuickServiceController@categories',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::eAD9zZuLB3O3RkuN',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::O5kWRA6Qj9xgCQn5' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/quick-services',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\QuickServiceController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\QuickServiceController@index',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::O5kWRA6Qj9xgCQn5',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::SdbyChga91Req7iR' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/quick-services',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\QuickServiceController@store',
        'controller' => 'App\\Http\\Controllers\\Api\\QuickServiceController@store',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::SdbyChga91Req7iR',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ay0oTPgAsp0Sx0Yj' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/quick-services/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\QuickServiceController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\QuickServiceController@show',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::ay0oTPgAsp0Sx0Yj',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::mWLkPt95V3RQGAAD' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/quick-services/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\QuickServiceController@update',
        'controller' => 'App\\Http\\Controllers\\Api\\QuickServiceController@update',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::mWLkPt95V3RQGAAD',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::mimj1F7JZkfPPOwA' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/quick-services/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\QuickServiceController@destroy',
        'controller' => 'App\\Http\\Controllers\\Api\\QuickServiceController@destroy',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::mimj1F7JZkfPPOwA',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::OgOlmkDASOHmEhJi' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/quick-services/{id}/respond',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\QuickServiceController@respond',
        'controller' => 'App\\Http\\Controllers\\Api\\QuickServiceController@respond',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::OgOlmkDASOHmEhJi',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::tu31EiH8x0qZ1UnF' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/quick-services/{serviceId}/responses/{responseId}/accept',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\QuickServiceController@acceptResponse',
        'controller' => 'App\\Http\\Controllers\\Api\\QuickServiceController@acceptResponse',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::tu31EiH8x0qZ1UnF',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::toxUVnvH6DYV0Ujc' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/quick-services/{serviceId}/responses/{responseId}/reject',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\QuickServiceController@rejectResponse',
        'controller' => 'App\\Http\\Controllers\\Api\\QuickServiceController@rejectResponse',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::toxUVnvH6DYV0Ujc',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::SiPFpCZtLd98ZSxp' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/my-quick-services',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\QuickServiceController@myServices',
        'controller' => 'App\\Http\\Controllers\\Api\\QuickServiceController@myServices',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::SiPFpCZtLd98ZSxp',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::HPMl0ZWhG6G30bat' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/my-service-responses',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'App\\Http\\Middleware\\UpdateLastSeen',
          3 => 'must.change.password',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\QuickServiceController@myResponses',
        'controller' => 'App\\Http\\Controllers\\Api\\QuickServiceController@myResponses',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::HPMl0ZWhG6G30bat',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::PEPE7gBLavRYy4D7' => 
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
          3 => 'must.change.password',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:91:"function () {
        return \\Illuminate\\Support\\Facades\\Broadcast::auth(\\request());
    }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"000000000000068e0000000000000000";}}',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::PEPE7gBLavRYy4D7',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
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
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000005ff0000000000000000";}}',
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
    'generated::RdjDOZneYvxjU0dS' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'up',
      'action' => 
      array (
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:885:"function () {
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

                    return response(\\Illuminate\\Support\\Facades\\View::file(\'/Users/macbookpro/Desktop/Developments/INSAM-DEV/E-Emploie-Backend/estuaire-emploie-backend/vendor/laravel/framework/src/Illuminate/Foundation/Configuration\'.\'/../resources/health-up.blade.php\', [
                        \'exception\' => $exception,
                    ]), status: $exception ? 500 : 200);
                }";s:5:"scope";s:54:"Illuminate\\Foundation\\Configuration\\ApplicationBuilder";s:4:"this";N;s:4:"self";s:32:"00000000000005e20000000000000000";}}',
        'as' => 'generated::RdjDOZneYvxjU0dS',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::b8iq8ApVxzyvX5BN' => 
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
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:174:"function () {
    if (\\Illuminate\\Support\\Facades\\Auth::check()) {
        return \\redirect()->route(\'admin.dashboard\');
    }
    return \\redirect()->route(\'admin.login\');
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000006930000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::b8iq8ApVxzyvX5BN',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
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
    'admin.quick-services.bulk-delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/quick-services/bulk-delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_jobs',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\QuickServiceController@bulkDelete',
        'controller' => 'App\\Http\\Controllers\\Admin\\QuickServiceController@bulkDelete',
        'as' => 'admin.quick-services.bulk-delete',
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
    'admin.quick-services.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/quick-services',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_jobs',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\QuickServiceController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\QuickServiceController@index',
        'as' => 'admin.quick-services.index',
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
    'admin.quick-services.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/quick-services/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_jobs',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\QuickServiceController@show',
        'controller' => 'App\\Http\\Controllers\\Admin\\QuickServiceController@show',
        'as' => 'admin.quick-services.show',
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
    'admin.quick-services.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/quick-services/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_jobs',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\QuickServiceController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\QuickServiceController@destroy',
        'as' => 'admin.quick-services.destroy',
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
    'admin.quick-services.status' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'admin/quick-services/{id}/status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_jobs',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\QuickServiceController@updateStatus',
        'controller' => 'App\\Http\\Controllers\\Admin\\QuickServiceController@updateStatus',
        'as' => 'admin.quick-services.status',
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
    'admin.quick-services.approve' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/quick-services/{id}/approve',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_jobs',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\QuickServiceController@approve',
        'controller' => 'App\\Http\\Controllers\\Admin\\QuickServiceController@approve',
        'as' => 'admin.quick-services.approve',
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
    'admin.users.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/users/{user}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_users',
        ),
        'as' => 'admin.users.edit',
        'uses' => 'App\\Http\\Controllers\\Admin\\UserController@edit',
        'controller' => 'App\\Http\\Controllers\\Admin\\UserController@edit',
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
    'admin.users.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
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
        'as' => 'admin.users.update',
        'uses' => 'App\\Http\\Controllers\\Admin\\UserController@update',
        'controller' => 'App\\Http\\Controllers\\Admin\\UserController@update',
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
    'admin.settings.referral.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'admin/settings/referral',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_settings',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\SettingsController@updateReferralSettings',
        'controller' => 'App\\Http\\Controllers\\Admin\\SettingsController@updateReferralSettings',
        'as' => 'admin.settings.referral.update',
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
    'admin.exam-packs.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/exam-packs',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ExamPackController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\ExamPackController@index',
        'as' => 'admin.exam-packs.index',
        'namespace' => NULL,
        'prefix' => 'admin/exam-packs',
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
    'admin.exam-packs.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/exam-packs/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ExamPackController@create',
        'controller' => 'App\\Http\\Controllers\\Admin\\ExamPackController@create',
        'as' => 'admin.exam-packs.create',
        'namespace' => NULL,
        'prefix' => 'admin/exam-packs',
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
    'admin.exam-packs.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/exam-packs',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ExamPackController@store',
        'controller' => 'App\\Http\\Controllers\\Admin\\ExamPackController@store',
        'as' => 'admin.exam-packs.store',
        'namespace' => NULL,
        'prefix' => 'admin/exam-packs',
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
    'admin.exam-packs.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/exam-packs/{examPack}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ExamPackController@edit',
        'controller' => 'App\\Http\\Controllers\\Admin\\ExamPackController@edit',
        'as' => 'admin.exam-packs.edit',
        'namespace' => NULL,
        'prefix' => 'admin/exam-packs',
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
    'admin.exam-packs.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'admin/exam-packs/{examPack}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ExamPackController@update',
        'controller' => 'App\\Http\\Controllers\\Admin\\ExamPackController@update',
        'as' => 'admin.exam-packs.update',
        'namespace' => NULL,
        'prefix' => 'admin/exam-packs',
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
    'admin.exam-packs.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/exam-packs/{examPack}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ExamPackController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\ExamPackController@destroy',
        'as' => 'admin.exam-packs.destroy',
        'namespace' => NULL,
        'prefix' => 'admin/exam-packs',
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
    'admin.exam-packs.toggle' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'admin/exam-packs/{examPack}/toggle',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ExamPackController@toggle',
        'controller' => 'App\\Http\\Controllers\\Admin\\ExamPackController@toggle',
        'as' => 'admin.exam-packs.toggle',
        'namespace' => NULL,
        'prefix' => 'admin/exam-packs',
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
    'admin.exam-packs.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/exam-packs/{examPack}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ExamPackController@show',
        'controller' => 'App\\Http\\Controllers\\Admin\\ExamPackController@show',
        'as' => 'admin.exam-packs.show',
        'namespace' => NULL,
        'prefix' => 'admin/exam-packs',
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
    'admin.exam-packs.manage-papers' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/exam-packs/{examPack}/manage-papers',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ExamPackController@managePapers',
        'controller' => 'App\\Http\\Controllers\\Admin\\ExamPackController@managePapers',
        'as' => 'admin.exam-packs.manage-papers',
        'namespace' => NULL,
        'prefix' => 'admin/exam-packs',
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
    'admin.exam-packs.add-paper' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/exam-packs/{examPack}/add-paper',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ExamPackController@addPaper',
        'controller' => 'App\\Http\\Controllers\\Admin\\ExamPackController@addPaper',
        'as' => 'admin.exam-packs.add-paper',
        'namespace' => NULL,
        'prefix' => 'admin/exam-packs',
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
    'admin.exam-packs.remove-paper' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/exam-packs/{examPack}/remove-paper/{examPaper}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ExamPackController@removePaper',
        'controller' => 'App\\Http\\Controllers\\Admin\\ExamPackController@removePaper',
        'as' => 'admin.exam-packs.remove-paper',
        'namespace' => NULL,
        'prefix' => 'admin/exam-packs',
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
    'admin.training-videos.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/training-videos',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\TrainingVideoController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\TrainingVideoController@index',
        'as' => 'admin.training-videos.index',
        'namespace' => NULL,
        'prefix' => 'admin/training-videos',
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
    'admin.training-videos.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/training-videos/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\TrainingVideoController@create',
        'controller' => 'App\\Http\\Controllers\\Admin\\TrainingVideoController@create',
        'as' => 'admin.training-videos.create',
        'namespace' => NULL,
        'prefix' => 'admin/training-videos',
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
    'admin.training-videos.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/training-videos',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\TrainingVideoController@store',
        'controller' => 'App\\Http\\Controllers\\Admin\\TrainingVideoController@store',
        'as' => 'admin.training-videos.store',
        'namespace' => NULL,
        'prefix' => 'admin/training-videos',
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
    'admin.training-videos.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/training-videos/{trainingVideo}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\TrainingVideoController@edit',
        'controller' => 'App\\Http\\Controllers\\Admin\\TrainingVideoController@edit',
        'as' => 'admin.training-videos.edit',
        'namespace' => NULL,
        'prefix' => 'admin/training-videos',
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
    'admin.training-videos.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'admin/training-videos/{trainingVideo}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\TrainingVideoController@update',
        'controller' => 'App\\Http\\Controllers\\Admin\\TrainingVideoController@update',
        'as' => 'admin.training-videos.update',
        'namespace' => NULL,
        'prefix' => 'admin/training-videos',
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
    'admin.training-videos.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/training-videos/{trainingVideo}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\TrainingVideoController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\TrainingVideoController@destroy',
        'as' => 'admin.training-videos.destroy',
        'namespace' => NULL,
        'prefix' => 'admin/training-videos',
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
    'admin.training-videos.toggle' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'admin/training-videos/{trainingVideo}/toggle',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\TrainingVideoController@toggle',
        'controller' => 'App\\Http\\Controllers\\Admin\\TrainingVideoController@toggle',
        'as' => 'admin.training-videos.toggle',
        'namespace' => NULL,
        'prefix' => 'admin/training-videos',
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
    'admin.training-videos.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/training-videos/{trainingVideo}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\TrainingVideoController@show',
        'controller' => 'App\\Http\\Controllers\\Admin\\TrainingVideoController@show',
        'as' => 'admin.training-videos.show',
        'namespace' => NULL,
        'prefix' => 'admin/training-videos',
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
    'admin.training-packs.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/training-packs',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\TrainingPackController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\TrainingPackController@index',
        'as' => 'admin.training-packs.index',
        'namespace' => NULL,
        'prefix' => 'admin/training-packs',
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
    'admin.training-packs.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/training-packs/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\TrainingPackController@create',
        'controller' => 'App\\Http\\Controllers\\Admin\\TrainingPackController@create',
        'as' => 'admin.training-packs.create',
        'namespace' => NULL,
        'prefix' => 'admin/training-packs',
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
    'admin.training-packs.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/training-packs',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\TrainingPackController@store',
        'controller' => 'App\\Http\\Controllers\\Admin\\TrainingPackController@store',
        'as' => 'admin.training-packs.store',
        'namespace' => NULL,
        'prefix' => 'admin/training-packs',
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
    'admin.training-packs.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/training-packs/{trainingPack}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\TrainingPackController@edit',
        'controller' => 'App\\Http\\Controllers\\Admin\\TrainingPackController@edit',
        'as' => 'admin.training-packs.edit',
        'namespace' => NULL,
        'prefix' => 'admin/training-packs',
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
    'admin.training-packs.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'admin/training-packs/{trainingPack}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\TrainingPackController@update',
        'controller' => 'App\\Http\\Controllers\\Admin\\TrainingPackController@update',
        'as' => 'admin.training-packs.update',
        'namespace' => NULL,
        'prefix' => 'admin/training-packs',
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
    'admin.training-packs.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/training-packs/{trainingPack}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\TrainingPackController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\TrainingPackController@destroy',
        'as' => 'admin.training-packs.destroy',
        'namespace' => NULL,
        'prefix' => 'admin/training-packs',
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
    'admin.training-packs.toggle' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'admin/training-packs/{trainingPack}/toggle',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\TrainingPackController@toggle',
        'controller' => 'App\\Http\\Controllers\\Admin\\TrainingPackController@toggle',
        'as' => 'admin.training-packs.toggle',
        'namespace' => NULL,
        'prefix' => 'admin/training-packs',
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
    'admin.training-packs.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/training-packs/{trainingPack}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\TrainingPackController@show',
        'controller' => 'App\\Http\\Controllers\\Admin\\TrainingPackController@show',
        'as' => 'admin.training-packs.show',
        'namespace' => NULL,
        'prefix' => 'admin/training-packs',
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
    'admin.training-packs.manage-videos' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/training-packs/{trainingPack}/manage-videos',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\TrainingPackController@manageVideos',
        'controller' => 'App\\Http\\Controllers\\Admin\\TrainingPackController@manageVideos',
        'as' => 'admin.training-packs.manage-videos',
        'namespace' => NULL,
        'prefix' => 'admin/training-packs',
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
    'admin.training-packs.add-video' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/training-packs/{trainingPack}/add-video',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\TrainingPackController@addVideo',
        'controller' => 'App\\Http\\Controllers\\Admin\\TrainingPackController@addVideo',
        'as' => 'admin.training-packs.add-video',
        'namespace' => NULL,
        'prefix' => 'admin/training-packs',
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
    'admin.training-packs.remove-video' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/training-packs/{trainingPack}/remove-video/{trainingVideo}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\TrainingPackController@removeVideo',
        'controller' => 'App\\Http\\Controllers\\Admin\\TrainingPackController@removeVideo',
        'as' => 'admin.training-packs.remove-video',
        'namespace' => NULL,
        'prefix' => 'admin/training-packs',
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
    'admin.training-packs.update-videos-order' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/training-packs/{trainingPack}/update-videos-order',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\TrainingPackController@updateVideosOrder',
        'controller' => 'App\\Http\\Controllers\\Admin\\TrainingPackController@updateVideosOrder',
        'as' => 'admin.training-packs.update-videos-order',
        'namespace' => NULL,
        'prefix' => 'admin/training-packs',
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
    'admin.students.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/students',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\StudentController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\StudentController@index',
        'as' => 'admin.students.index',
        'namespace' => NULL,
        'prefix' => 'admin/students',
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
    'admin.students.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/students/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\StudentController@create',
        'controller' => 'App\\Http\\Controllers\\Admin\\StudentController@create',
        'as' => 'admin.students.create',
        'namespace' => NULL,
        'prefix' => 'admin/students',
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
    'admin.students.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/students',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\StudentController@store',
        'controller' => 'App\\Http\\Controllers\\Admin\\StudentController@store',
        'as' => 'admin.students.store',
        'namespace' => NULL,
        'prefix' => 'admin/students',
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
    'admin.students.confirm' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/students/confirm',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\StudentController@confirmAndSave',
        'controller' => 'App\\Http\\Controllers\\Admin\\StudentController@confirmAndSave',
        'as' => 'admin.students.confirm',
        'namespace' => NULL,
        'prefix' => 'admin/students',
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
    'admin.students.send-sms' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/students/{user}/send-sms',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\StudentController@sendSMS',
        'controller' => 'App\\Http\\Controllers\\Admin\\StudentController@sendSMS',
        'as' => 'admin.students.send-sms',
        'namespace' => NULL,
        'prefix' => 'admin/students',
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
    'admin.students.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/students/{user}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\StudentController@show',
        'controller' => 'App\\Http\\Controllers\\Admin\\StudentController@show',
        'as' => 'admin.students.show',
        'namespace' => NULL,
        'prefix' => 'admin/students',
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
    'admin.students.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/students/{user}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\StudentController@edit',
        'controller' => 'App\\Http\\Controllers\\Admin\\StudentController@edit',
        'as' => 'admin.students.edit',
        'namespace' => NULL,
        'prefix' => 'admin/students',
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
    'admin.students.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'admin/students/{user}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\StudentController@update',
        'controller' => 'App\\Http\\Controllers\\Admin\\StudentController@update',
        'as' => 'admin.students.update',
        'namespace' => NULL,
        'prefix' => 'admin/students',
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
    'admin.students.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/students/{user}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_premium_services',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\StudentController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\StudentController@destroy',
        'as' => 'admin.students.destroy',
        'namespace' => NULL,
        'prefix' => 'admin/students',
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
    'admin.bank-account.available-balance' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/bank-account/available-balance',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_payments',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\BankAccountController@getAvailableBalance',
        'controller' => 'App\\Http\\Controllers\\Admin\\BankAccountController@getAvailableBalance',
        'as' => 'admin.bank-account.available-balance',
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
    'admin.bank-account.paypal-available-balance' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/bank-account/paypal/available-balance',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_payments',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\BankAccountController@getPayPalAvailableBalance',
        'controller' => 'App\\Http\\Controllers\\Admin\\BankAccountController@getPayPalAvailableBalance',
        'as' => 'admin.bank-account.paypal-available-balance',
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
    'admin.bank-account.paypal-withdrawal' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/bank-account/paypal/withdrawal',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_payments',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\BankAccountController@showPayPalWithdrawalForm',
        'controller' => 'App\\Http\\Controllers\\Admin\\BankAccountController@showPayPalWithdrawalForm',
        'as' => 'admin.bank-account.paypal-withdrawal',
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
    'admin.bank-account.initiate-paypal-withdrawal' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/bank-account/paypal/withdrawal',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'permission:manage_payments',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\BankAccountController@initiatePayPalWithdrawal',
        'controller' => 'App\\Http\\Controllers\\Admin\\BankAccountController@initiatePayPalWithdrawal',
        'as' => 'admin.bank-account.initiate-paypal-withdrawal',
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
    'generated::UAgNNCtT5mSMUTDp' => 
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
        'as' => 'generated::UAgNNCtT5mSMUTDp',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
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
