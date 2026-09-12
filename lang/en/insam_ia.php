<?php

return [
    'unavailable' => 'The INSAM-IA service is temporarily unavailable. Please try again shortly.',
    'not_configured' => 'The INSAM-IA integration is not enabled on this platform yet.',
    'not_found' => 'INSAM-IA content not found.',
    'student_mode_required' => 'Student Mode is required to access INSAM-IA resources.',
    'attempt_not_found' => 'Attempt not found.',
    'attempt_already_submitted' => 'This assessment has already been submitted.',
    'generation_queued' => 'Your revision sheet is being generated. You will be notified as soon as it is ready.',
    'generation_ready_title' => 'Revision sheet ready',
    'generation_ready_body' => 'Your revision sheet ":title" is available.',
    'generation_failed_title' => 'Generation failed',
    'generation_failed_body' => 'The revision sheet could not be generated. Please try again later.',
    'progress_saved' => 'Progress saved.',
    'attestation_issued' => 'Your certificate has been issued.',

    'mention' => [
        'excellent' => 'Excellent',
        'tres_bien' => 'Very good',
        'bien' => 'Good',
        'assez_bien' => 'Fair',
    ],

    'attestation_errors' => [
        'not_submitted' => 'The assessment must be submitted before a certificate can be issued.',
        'below_threshold' => 'A score of at least :threshold% is required to obtain the certificate.',
        'not_available' => 'No certificate available for this assessment.',
        'pdf_unavailable' => 'The document could not be generated. Please try again shortly.',
    ],

    'attestation' => [
        'title' => 'CERTIFICATE OF ACHIEVEMENT',
        'subtitle' => 'Training pathway',
        'tagline' => 'Jobs and training platform',
        'awarded_to' => 'Awarded to',
        'statement' => 'for successfully completing the :course pathway',
        'specialty' => 'Specialty',
        'score' => 'Score',
        'percentage' => 'Result',
        'mention' => 'Grade',
        'reference' => 'Reference',
        'issued_on' => 'Issued on',
        'partner' => 'In partnership with',
        'signatory' => 'Management',
        'verify' => 'Document verifiable with Estuaire Emploi using reference :reference.',
        'default_course' => 'Training pathway',
        'unknown_holder' => 'Student',
    ],
];
