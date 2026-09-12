<?php

return [
    'unavailable' => 'El servicio INSAM-IA no está disponible momentáneamente. Inténtelo de nuevo en unos instantes.',
    'not_configured' => 'La integración INSAM-IA aún no está activada en esta plataforma.',
    'not_found' => 'Contenido INSAM-IA no encontrado.',
    'student_mode_required' => 'Se requiere el Modo Estudiante para acceder a los recursos INSAM-IA.',
    'attempt_not_found' => 'Intento no encontrado.',
    'attempt_already_submitted' => 'Esta evaluación ya fue enviada.',
    'generation_queued' => 'La ficha de repaso se está generando. Le avisaremos en cuanto esté lista.',
    'generation_ready_title' => 'Ficha de repaso lista',
    'generation_ready_body' => 'Su ficha de repaso «:title» está disponible.',
    'generation_failed_title' => 'Generación imposible',
    'generation_failed_body' => 'No se pudo generar la ficha de repaso. Inténtelo más tarde.',
    'progress_saved' => 'Progreso guardado.',
    'attestation_issued' => 'Su certificado ha sido emitido.',

    'mention' => [
        'excellent' => 'Excelente',
        'tres_bien' => 'Muy bien',
        'bien' => 'Bien',
        'assez_bien' => 'Suficiente',
    ],

    'attestation_errors' => [
        'not_submitted' => 'La evaluación debe enviarse antes de emitir un certificado.',
        'below_threshold' => 'Se requiere una nota de al menos :threshold % para obtener el certificado.',
        'not_available' => 'No hay certificado disponible para esta evaluación.',
        'pdf_unavailable' => 'No se pudo generar el documento. Inténtelo de nuevo en un instante.',
    ],

    'attestation' => [
        'title' => 'CERTIFICADO DE APROVECHAMIENTO',
        'subtitle' => 'Itinerario formativo',
        'tagline' => 'Plataforma de empleo y formación',
        'awarded_to' => 'Otorgado a',
        'statement' => 'por haber completado con éxito el itinerario :course',
        'specialty' => 'Especialidad',
        'score' => 'Puntuación',
        'percentage' => 'Resultado',
        'mention' => 'Mención',
        'reference' => 'Referencia',
        'issued_on' => 'Emitido el',
        'partner' => 'En colaboración con',
        'signatory' => 'La Dirección',
        'verify' => 'Documento verificable ante Estuaire Emploi mediante la referencia :reference.',
        'default_course' => 'Itinerario formativo',
        'unknown_holder' => 'Estudiante',
    ],
];
