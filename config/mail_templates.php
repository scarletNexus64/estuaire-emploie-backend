<?php

/*
|--------------------------------------------------------------------------
| Modèles d'emails de la messagerie Support (bilingue FR / EN)
|--------------------------------------------------------------------------
|
| Variables disponibles dans les objets et contenus (remplacées si le
| contexte est connu, sinon laissées telles quelles pour édition) :
|   {{candidate_name}}  {{company_name}}  {{recipient_name}}
|   {{position}}        {{date}}
|
*/

return [

    'variables' => [
        '{{recipient_name}}' => 'Nom du destinataire',
        '{{candidate_name}}' => 'Nom du candidat',
        '{{company_name}}'   => "Nom de l'entreprise",
        '{{position}}'       => 'Intitulé du poste',
        '{{date}}'           => 'Date du jour',
    ],

    'templates' => [

        'blank' => [
            'label' => ['fr' => 'Vierge', 'en' => 'Blank'],
            'fr' => ['subject' => '', 'body' => ''],
            'en' => ['subject' => '', 'body' => ''],
        ],

        'candidate_recommendation' => [
            'label' => ['fr' => "Recommandation d'un candidat", 'en' => 'Candidate recommendation'],
            'fr' => [
                'subject' => "Recommandation de candidat : {{candidate_name}}",
                'body' => "<p>Bonjour {{recipient_name}},</p>
<p>Dans le cadre de vos recrutements chez <strong>{{company_name}}</strong>, nous avons le plaisir de vous recommander le profil de <strong>{{candidate_name}}</strong> pour le poste de <strong>{{position}}</strong>.</p>
<p>Ce candidat correspond aux compétences recherchées et fait partie des talents accompagnés par Estuaire Emploi. Vous trouverez son CV en pièce jointe.</p>
<p>Nous restons à votre disposition pour organiser un entretien ou vous transmettre d'autres profils.</p>
<p>Cordialement,<br><strong>Estuaire Service</strong></p>",
            ],
            'en' => [
                'subject' => "Candidate recommendation: {{candidate_name}}",
                'body' => "<p>Hello {{recipient_name}},</p>
<p>As part of your hiring at <strong>{{company_name}}</strong>, we are pleased to recommend <strong>{{candidate_name}}</strong> for the position of <strong>{{position}}</strong>.</p>
<p>This candidate matches the required skills and is among the talents supported by Estuaire Emploi. Please find their resume attached.</p>
<p>We remain available to arrange an interview or to send you additional profiles.</p>
<p>Best regards,<br><strong>Estuaire Service</strong></p>",
            ],
        ],

        'profile_proposal' => [
            'label' => ['fr' => "Proposition de profils à une entreprise", 'en' => 'Profiles proposal to a company'],
            'fr' => [
                'subject' => "Profils sélectionnés pour {{company_name}}",
                'body' => "<p>Bonjour {{recipient_name}},</p>
<p>Suite à votre besoin en recrutement, nous avons présélectionné des profils susceptibles de vous intéresser. Vous trouverez leurs CV en pièces jointes.</p>
<p>Chaque candidat a été évalué par notre équipe. N'hésitez pas à nous indiquer ceux que vous souhaitez rencontrer, nous organiserons la mise en relation.</p>
<p>Cordialement,<br><strong>Estuaire Service</strong></p>",
            ],
            'en' => [
                'subject' => "Selected profiles for {{company_name}}",
                'body' => "<p>Hello {{recipient_name}},</p>
<p>Following your hiring needs, we have shortlisted profiles that may interest you. Please find their resumes attached.</p>
<p>Each candidate has been assessed by our team. Feel free to tell us which ones you would like to meet, and we will arrange the introduction.</p>
<p>Best regards,<br><strong>Estuaire Service</strong></p>",
            ],
        ],

        'interview_invitation' => [
            'label' => ['fr' => "Invitation à un entretien", 'en' => 'Interview invitation'],
            'fr' => [
                'subject' => "Invitation à un entretien - {{position}}",
                'body' => "<p>Bonjour {{candidate_name}},</p>
<p>Nous avons le plaisir de vous inviter à un entretien pour le poste de <strong>{{position}}</strong> au sein de <strong>{{company_name}}</strong>.</p>
<p>Merci de nous indiquer vos disponibilités afin de convenir d'un créneau.</p>
<p>Cordialement,<br><strong>Estuaire Service</strong></p>",
            ],
            'en' => [
                'subject' => "Interview invitation - {{position}}",
                'body' => "<p>Hello {{candidate_name}},</p>
<p>We are pleased to invite you to an interview for the position of <strong>{{position}}</strong> at <strong>{{company_name}}</strong>.</p>
<p>Please let us know your availability so we can agree on a time slot.</p>
<p>Best regards,<br><strong>Estuaire Service</strong></p>",
            ],
        ],

        'application_response' => [
            'label' => ['fr' => "Réponse à une candidature", 'en' => 'Application response'],
            'fr' => [
                'subject' => "Votre candidature - {{position}}",
                'body' => "<p>Bonjour {{candidate_name}},</p>
<p>Nous vous remercions pour votre candidature au poste de <strong>{{position}}</strong>.</p>
<p>Après étude de votre dossier, nous reviendrons vers vous dans les meilleurs délais concernant la suite du processus.</p>
<p>Cordialement,<br><strong>Estuaire Service</strong></p>",
            ],
            'en' => [
                'subject' => "Your application - {{position}}",
                'body' => "<p>Hello {{candidate_name}},</p>
<p>Thank you for your application for the position of <strong>{{position}}</strong>.</p>
<p>After reviewing your file, we will get back to you shortly regarding the next steps.</p>
<p>Best regards,<br><strong>Estuaire Service</strong></p>",
            ],
        ],

        'acknowledgement' => [
            'label' => ['fr' => "Accusé de réception", 'en' => 'Acknowledgement'],
            'fr' => [
                'subject' => "Bien reçu - Estuaire Emploi",
                'body' => "<p>Bonjour {{recipient_name}},</p>
<p>Nous accusons réception de votre message et vous remercions de nous avoir contactés. Notre équipe revient vers vous dans les plus brefs délais.</p>
<p>Cordialement,<br><strong>Estuaire Service</strong></p>",
            ],
            'en' => [
                'subject' => "Well received - Estuaire Emploi",
                'body' => "<p>Hello {{recipient_name}},</p>
<p>We acknowledge receipt of your message and thank you for contacting us. Our team will get back to you as soon as possible.</p>
<p>Best regards,<br><strong>Estuaire Service</strong></p>",
            ],
        ],

        'follow_up' => [
            'label' => ['fr' => "Relance", 'en' => 'Follow-up'],
            'fr' => [
                'subject' => "Relance - {{position}}",
                'body' => "<p>Bonjour {{recipient_name}},</p>
<p>Sauf erreur de notre part, nous n'avons pas eu de retour suite à notre dernier échange concernant <strong>{{position}}</strong>.</p>
<p>Restant à votre disposition pour tout complément d'information.</p>
<p>Cordialement,<br><strong>Estuaire Service</strong></p>",
            ],
            'en' => [
                'subject' => "Follow-up - {{position}}",
                'body' => "<p>Hello {{recipient_name}},</p>
<p>Unless we are mistaken, we have not heard back following our last exchange regarding <strong>{{position}}</strong>.</p>
<p>We remain available for any further information.</p>
<p>Best regards,<br><strong>Estuaire Service</strong></p>",
            ],
        ],

    ],
];
