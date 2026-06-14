<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Commercial B2B — maîtriser la vente entre entreprises, du premier contact à la fidélisation des grands comptes.
 */
class CommercialB2BRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Deviens Commercial B2B',
            'slug' => 'commercial-b2b',
            'domain' => 'marketing',
            'description' => "Apprends le métier de commercial B2B (Business to Business) : vendre des produits et services à d'autres entreprises. Tu découvriras le cycle de vente long, la prospection structurée, la qualification des besoins, la négociation de contrats et la gestion d'un portefeuille de grands comptes. Une roadmap concrète, pensée pour le marché camerounais et africain.",
            'objectives' => "Comprendre les spécificités de la vente B2B face au B2C\nMaîtriser la prospection et le cold call sans peur du refus\nQualifier un prospect avec la méthode BANT\nIdentifier et cartographier les vrais décideurs\nConstruire une proposition de valeur convaincante\nNégocier un contrat et piloter son pipeline dans un CRM",
            'icon' => '🤝',
            'color' => '#2563EB',
            'difficulty' => 'intermediate',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 20,
            'levels' => [
                [
                    'title' => 'Niveau 1 — B2B vs B2C : comprendre le terrain',
                    'subtitle' => 'Les spécificités de la vente entre entreprises',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : comprendre ce qui distingue la vente B2B (à des entreprises) de la vente B2C (au grand public).\n\n💡 En B2B, ton client n'est pas un particulier mais une organisation : une PME de Douala, une administration à Yaoundé, une coopérative agricole. Les différences clés :\n• **Plusieurs décideurs** : un achat est rarement décidé par une seule personne.\n• **Montants élevés** : un contrat peut représenter des millions de FCFA.\n• **Rationalité** : la décision repose sur le retour sur investissement (ROI), pas sur l'émotion seule.\n• **Relation durable** : on vend une fois, puis on accompagne dans le temps.\n\n✅ Exemple : vendre une flotte de motos à une société de livraison (B2B) implique de convaincre le gérant, le comptable et le responsable logistique — alors que vendre une moto à un particulier (B2C) ne dépend que de lui.\n\n⚠️ En B2B, ne jamais improviser : chaque interlocuteur a ses propres critères.",
                    'questions' => [
                        ['question' => 'Quelle est une caractéristique typique de la vente B2B ?', 'options' => ['La décision repose sur une seule personne', 'Plusieurs décideurs interviennent dans l\'achat', 'Les montants sont toujours faibles', 'La décision est purement émotionnelle'], 'correct' => [1], 'explanation' => 'En B2B, l\'achat implique généralement plusieurs décideurs au sein de l\'organisation.'],
                        ['question' => 'Sur quoi se fonde principalement une décision d\'achat B2B ?', 'options' => ['Le coup de cœur', 'Le retour sur investissement (ROI)', 'La publicité TV', 'La couleur du produit'], 'correct' => [1], 'explanation' => 'La décision B2B est rationnelle et s\'appuie sur le ROI attendu.'],
                        ['question' => 'Quelles affirmations décrivent correctement le B2B ? (plusieurs réponses)', 'options' => ['Relation commerciale souvent durable', 'Cycle de vente généralement plus long qu\'en B2C', 'Client = un particulier isolé', 'Montants souvent plus élevés'], 'correct' => [0, 1, 3], 'explanation' => 'Le B2B se caractérise par une relation durable, un cycle long et des montants élevés ; le client est une organisation, pas un particulier.'],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — Le cycle de vente long',
                    'subtitle' => 'Gérer la patience et les étapes successives',
                    'xp_reward' => 110,
                    'content' => "🎯 **Objectif** : comprendre que vendre en B2B prend du temps et suit des étapes.\n\n💡 Le cycle de vente B2B s'étale souvent sur plusieurs semaines ou mois. Les grandes étapes :\n• **Prospection** : trouver des entreprises cibles.\n• **Prise de contact** : décrocher un premier rendez-vous.\n• **Découverte** : comprendre les besoins.\n• **Proposition** : envoyer une offre adaptée.\n• **Négociation** : ajuster prix et conditions.\n• **Closing** : signer le contrat.\n• **Suivi** : livrer et fidéliser.\n\n✅ Schéma simplifié :\n\n```\nProspection → Contact → Découverte → Proposition → Négociation → Signature → Suivi\n```\n\n⚠️ Ne pas brûler les étapes : envoyer un devis avant d'avoir compris le besoin tue la vente. La patience et la relance organisée sont tes meilleures alliées.\n\n💡 Astuce : note la date de chaque relance pour ne perdre aucun prospect.",
                    'questions' => [
                        ['question' => 'Quelle étape vient juste avant la proposition commerciale ?', 'options' => ['Le closing', 'La découverte des besoins', 'Le suivi après-vente', 'La signature'], 'correct' => [1], 'explanation' => 'On découvre les besoins avant de formuler une proposition adaptée.'],
                        ['question' => 'Pourquoi le cycle de vente B2B est-il long ?', 'options' => ['Parce que les commerciaux sont lents', 'Parce que la décision implique plusieurs personnes et engage des montants importants', 'Parce que la loi l\'impose', 'Parce qu\'il n\'y a jamais de relance'], 'correct' => [1], 'explanation' => 'La multiplicité des décideurs et l\'importance des montants allongent naturellement le cycle.'],
                        ['question' => 'Quelle erreur tue souvent une vente B2B ?', 'options' => ['Envoyer un devis avant d\'avoir compris le besoin', 'Faire de la découverte', 'Relancer poliment', 'Préparer son rendez-vous'], 'correct' => [0], 'explanation' => 'Proposer une offre sans avoir compris le besoin fait perdre toute crédibilité.'],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — La prospection',
                    'subtitle' => 'Trouver et cibler les bonnes entreprises',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : savoir construire une liste de prospects pertinents.\n\n💡 Prospecter, c'est chercher activement de nouveaux clients potentiels. Les sources au Cameroun :\n• **LinkedIn** pour identifier les décideurs.\n• **Annuaires professionnels** et registres du commerce.\n• **Salons et foires** (PROMOTE à Yaoundé, par exemple).\n• **Recommandations** de clients existants.\n• **Réseaux WhatsApp Business** très utilisés localement.\n\n✅ Définis ton **ICP** (Ideal Customer Profile) : taille de l'entreprise, secteur, localisation, budget. Exemple d'ICP : « PME de 20 à 100 salariés dans l'agroalimentaire, basée à Douala, qui exporte ».\n\n⚠️ Ne prospecte pas au hasard : 100 contacts mal ciblés valent moins que 20 contacts bien qualifiés.\n\n💡 Tableau de ciblage :\n\n| Critère | Ma cible |\n|---|---|\n| Secteur | Agroalimentaire |\n| Taille | 20-100 salariés |\n| Ville | Douala |\n| Besoin | Logistique export |",
                    'questions' => [
                        ['question' => 'Que désigne l\'ICP en prospection ?', 'options' => ['Indice de Croissance des Prix', 'Le profil du client idéal (Ideal Customer Profile)', 'Un logiciel comptable', 'Un type de contrat'], 'correct' => [1], 'explanation' => 'L\'ICP, Ideal Customer Profile, décrit le client idéal à cibler.'],
                        ['question' => 'Quelle approche est la plus efficace en prospection ?', 'options' => ['Contacter le plus de monde possible sans tri', 'Cibler un nombre réduit de prospects bien qualifiés', 'Attendre que les clients appellent', 'Ne prospecter que sa famille'], 'correct' => [1], 'explanation' => 'Un ciblage précis sur des prospects qualifiés est bien plus rentable que le volume aveugle.'],
                        ['question' => 'Quelles sources de prospection sont pertinentes au Cameroun ? (plusieurs réponses)', 'options' => ['LinkedIn', 'Salons professionnels comme PROMOTE', 'Recommandations de clients', 'Numéros tirés au hasard dans la rue'], 'correct' => [0, 1, 2], 'explanation' => 'LinkedIn, les salons et les recommandations sont des sources ciblées ; les numéros au hasard ne le sont pas.'],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — Le cold call',
                    'subtitle' => 'Décrocher un rendez-vous au téléphone',
                    'xp_reward' => 130,
                    'content' => "🎯 **Objectif** : réussir un appel à froid (cold call) pour obtenir un rendez-vous.\n\n💡 Le cold call consiste à appeler un prospect qui ne te connaît pas. L'objectif n'est PAS de vendre au téléphone, mais d'obtenir un **rendez-vous**. Structure efficace :\n• **Accroche** : te présenter en une phrase claire.\n• **Raison de l'appel** : un bénéfice concret pour le prospect.\n• **Question ouverte** : engager le dialogue.\n• **Proposition de RDV** : avec deux créneaux au choix.\n\n✅ Exemple de script :\n\n```\nBonjour M. Mbarga, je suis Léa de Logistik. Nous aidons les PME\nde Douala à réduire leurs coûts de livraison de 15%.\nComment gérez-vous vos livraisons aujourd'hui ?\n... Seriez-vous disponible mardi 10h ou jeudi 14h ?\n```\n\n⚠️ Le « non » fait partie du métier : statistiquement, beaucoup d'appels n'aboutissent pas. Ne le prends pas personnellement, passe au suivant.\n\n💡 Souris en parlant : ça s'entend au téléphone.",
                    'questions' => [
                        ['question' => 'Quel est l\'objectif principal d\'un cold call en B2B ?', 'options' => ['Vendre immédiatement par téléphone', 'Obtenir un rendez-vous', 'Réciter tout le catalogue', 'Demander une recommandation'], 'correct' => [1], 'explanation' => 'Le cold call vise d\'abord à décrocher un rendez-vous, pas à conclure la vente.'],
                        ['question' => 'Comment réagir face à un refus lors d\'un appel à froid ?', 'options' => ['Insister agressivement', 'Le prendre personnellement et abandonner', 'Rester professionnel et passer au prospect suivant', 'Raccrocher en colère'], 'correct' => [2], 'explanation' => 'Le refus est normal en prospection ; on reste professionnel et on poursuit.'],
                        ['question' => 'Quelle phrase ouvre le mieux un dialogue ?', 'options' => ['« Vous voulez acheter ? »', 'Une question ouverte sur la situation actuelle du prospect', 'Le prix tout de suite', 'Un silence'], 'correct' => [1], 'explanation' => 'Une question ouverte engage le prospect et permet de découvrir son besoin.'],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — La qualification BANT',
                    'subtitle' => 'Savoir si un prospect vaut ton temps',
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : qualifier un prospect avec la méthode BANT.\n\n💡 BANT est un acronyme qui aide à évaluer si un prospect est mûr :\n• **B — Budget** : a-t-il les moyens financiers ?\n• **A — Authority (Autorité)** : parles-tu au décideur ?\n• **N — Need (Besoin)** : a-t-il un vrai besoin que tu peux résoudre ?\n• **T — Timing (Échéance)** : quand veut-il acheter ?\n\n✅ Exemple : une entreprise de Bafoussam a le besoin (N) et le budget (B), mais ton interlocuteur n'est pas décisionnaire (A faible) et le projet est prévu dans un an (T lointain). Tu gardes le contact sans y consacrer trop d'énergie immédiate.\n\n⚠️ Un prospect qui ne coche aucun critère BANT n'est pas un vrai prospect : ne perds pas ton temps.\n\n💡 Pose des questions naturelles : « Quel budget avez-vous prévu ? », « Qui validera la décision finale ? », « Pour quand visez-vous une mise en place ? ».",
                    'questions' => [
                        ['question' => 'Que signifie le « A » de BANT ?', 'options' => ['Avantage', 'Authority (l\'autorité de décision)', 'Argent', 'Accord'], 'correct' => [1], 'explanation' => 'Le « A » correspond à Authority : s\'assurer de parler au décideur.'],
                        ['question' => 'Que représente le « T » de BANT ?', 'options' => ['Territoire', 'Technologie', 'Timing (l\'échéance d\'achat)', 'Tarif'], 'correct' => [2], 'explanation' => 'Le « T » désigne le Timing, c\'est-à-dire le moment prévu pour l\'achat.'],
                        ['question' => 'Quels critères composent la méthode BANT ? (plusieurs réponses)', 'options' => ['Budget', 'Need (besoin)', 'Brand (marque)', 'Timing'], 'correct' => [0, 1, 3], 'explanation' => 'BANT = Budget, Authority, Need, Timing ; la marque n\'en fait pas partie.'],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — Cartographier les décideurs',
                    'subtitle' => 'Comprendre qui décide vraiment',
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : identifier tous les acteurs impliqués dans la décision d'achat.\n\n💡 En B2B, plusieurs personnes influencent l'achat. On parle de **DMU** (Decision Making Unit). Les rôles types :\n• **Décideur** : signe et valide le budget (souvent le DG ou le directeur financier).\n• **Prescripteur** : recommande la solution (un responsable technique).\n• **Utilisateur** : se servira du produit au quotidien.\n• **Acheteur** : négocie les conditions (service achats).\n• **Filtre / gatekeeper** : la secrétaire ou l'assistant qui contrôle l'accès.\n\n✅ Exemple : pour vendre un logiciel de paie à une PME de Yaoundé, le DRH est prescripteur, le DG décideur, les comptables utilisateurs.\n\n⚠️ Erreur classique : convaincre seulement l'utilisateur en oubliant le décideur qui tient le budget.\n\n💡 Dessine une carte des décideurs avec leurs intérêts : le DG veut du ROI, l'utilisateur veut de la simplicité, l'acheteur veut un bon prix.",
                    'questions' => [
                        ['question' => 'Que désigne le DMU ?', 'options' => ['Un type de remise', 'L\'unité de prise de décision (Decision Making Unit)', 'Un indicateur de marge', 'Un logiciel CRM'], 'correct' => [1], 'explanation' => 'Le DMU regroupe l\'ensemble des personnes qui influencent la décision d\'achat.'],
                        ['question' => 'Qui est généralement le « décideur » dans un achat B2B ?', 'options' => ['La personne qui valide le budget et signe', 'L\'agent de sécurité', 'N\'importe quel salarié', 'Le concurrent'], 'correct' => [0], 'explanation' => 'Le décideur est celui qui dispose de l\'autorité pour valider le budget et signer.'],
                        ['question' => 'Quel rôle joue le gatekeeper (filtre) ?', 'options' => ['Il signe le contrat', 'Il contrôle l\'accès aux décideurs', 'Il fixe le prix', 'Il utilise le produit'], 'correct' => [1], 'explanation' => 'Le gatekeeper, souvent un assistant, filtre l\'accès aux décideurs.'],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — La proposition de valeur',
                    'subtitle' => 'Montrer pourquoi te choisir toi',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : construire une proposition de valeur claire et différenciante.\n\n💡 La proposition de valeur répond à : « Pourquoi acheter chez moi plutôt que chez le concurrent ? ». Elle relie tes **bénéfices** aux **besoins** du client, pas seulement tes caractéristiques techniques.\n\n• **Caractéristique** : ce que fait ton produit (« livraison en 24h »).\n• **Avantage** : ce que ça apporte (« tes clients sont servis vite »).\n• **Bénéfice** : l'impact chiffré (« +20% de réachat »).\n\n✅ Formule simple :\n\n```\nNous aidons [cible] à [résoudre problème]\ngrâce à [solution], ce qui permet [bénéfice chiffré].\n```\n\nExemple : « Nous aidons les pharmacies de Douala à éviter les ruptures de stock grâce à un suivi automatisé, ce qui réduit les pertes de 30%. »\n\n⚠️ Évite le jargon technique : le client achète un résultat, pas une fiche technique.\n\n💡 Adapte la valeur à chaque interlocuteur du DMU.",
                    'questions' => [
                        ['question' => 'À quelle question répond la proposition de valeur ?', 'options' => ['Quel est mon salaire ?', 'Pourquoi acheter chez moi plutôt qu\'ailleurs ?', 'Combien d\'employés ai-je ?', 'Quelle est la météo ?'], 'correct' => [1], 'explanation' => 'La proposition de valeur explique pourquoi le client doit te choisir face à la concurrence.'],
                        ['question' => 'Quelle est la différence entre caractéristique et bénéfice ?', 'options' => ['Aucune, c\'est pareil', 'La caractéristique décrit le produit, le bénéfice décrit l\'impact pour le client', 'Le bénéfice est technique, la caractéristique est financière', 'Le bénéfice ne concerne que le vendeur'], 'correct' => [1], 'explanation' => 'La caractéristique décrit le produit ; le bénéfice traduit son impact concret pour le client.'],
                        ['question' => 'Que faut-il éviter dans une proposition de valeur ?', 'options' => ['Un bénéfice chiffré', 'Un langage clair', 'Le jargon technique incompréhensible', 'L\'adaptation au client'], 'correct' => [2], 'explanation' => 'Le jargon technique brouille le message ; le client veut comprendre le résultat.'],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — La négociation de contrat',
                    'subtitle' => 'Conclure sans brader',
                    'xp_reward' => 170,
                    'content' => "🎯 **Objectif** : négocier un contrat B2B en préservant ta marge.\n\n💡 Négocier n'est pas baisser le prix : c'est trouver un accord gagnant-gagnant. Principes clés :\n• **Connaître ses limites** : prix plancher, délais minimaux.\n• **Donner pour recevoir** : « j'accorde une remise si vous signez sur 12 mois ».\n• **Argumenter la valeur** avant de parler prix.\n• **Ne jamais céder gratuitement** : chaque concession a une contrepartie.\n\n✅ En contexte OHADA, le contrat de vente engage juridiquement les deux parties : conditions de paiement, livraison, garanties et clauses de résiliation doivent être écrites noir sur blanc.\n\n⚠️ Attention aux paiements : en B2B local, prévoir un acompte (ex. 30%) et clarifier les délais. Le mobile money peut sécuriser les premiers versements.\n\n💡 Tactique : si le client pousse sur le prix, recentre sur le ROI et le coût d'un problème non résolu, pas sur la seule dépense.",
                    'questions' => [
                        ['question' => 'Que signifie négocier en B2B ?', 'options' => ['Toujours baisser son prix', 'Trouver un accord gagnant-gagnant', 'Imposer ses conditions de force', 'Renoncer à toute marge'], 'correct' => [1], 'explanation' => 'La négociation vise un accord équilibré profitable aux deux parties, pas une simple baisse de prix.'],
                        ['question' => 'Que faire avant de céder une remise ?', 'options' => ['Rien, l\'offrir gratuitement', 'Obtenir une contrepartie (volume, engagement, délai)', 'Augmenter le prix de départ sans raison', 'Annuler le contrat'], 'correct' => [1], 'explanation' => 'Toute concession doit s\'échanger contre une contrepartie pour préserver la valeur.'],
                        ['question' => 'Quels éléments doivent figurer clairement dans un contrat de vente (OHADA) ? (plusieurs réponses)', 'options' => ['Conditions de paiement', 'Délais de livraison', 'La couleur préférée du commercial', 'Garanties et clauses de résiliation'], 'correct' => [0, 1, 3], 'explanation' => 'Paiement, livraison, garanties et résiliation sont essentiels ; les préférences personnelles n\'ont rien à y faire.'],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — CRM, pipeline et fidélisation',
                    'subtitle' => 'Piloter ses ventes et garder ses grands comptes',
                    'xp_reward' => 185,
                    'content' => "🎯 **Objectif** : organiser ton activité avec un CRM et fidéliser tes grands comptes.\n\n💡 Le **CRM** (Customer Relationship Management) est l'outil qui centralise tes contacts, échanges et opportunités. Le **pipeline** visualise tes affaires par étape :\n\n```\n[Prospect] → [Qualifié] → [Proposition] → [Négociation] → [Gagné/Perdu]\n```\n\n• Chaque opportunité a une valeur estimée et une probabilité.\n• Tu suis tes relances et n'oublies aucun prospect.\n\n✅ **Fidélisation grands comptes** : un client existant coûte moins cher à conserver qu'à conquérir. Méthodes :\n• Rendez-vous réguliers de suivi.\n• Reporting de la valeur apportée.\n• Upsell (vendre plus) et cross-sell (vendre des produits complémentaires).\n\n⚠️ Un grand compte mal suivi part chez le concurrent. La relation se cultive même après la signature.\n\n💡 Note dans le CRM la date du prochain contact après chaque échange.",
                    'questions' => [
                        ['question' => 'À quoi sert un CRM ?', 'options' => ['À calculer les impôts', 'À centraliser contacts, échanges et opportunités commerciales', 'À remplacer le commercial', 'À imprimer des affiches'], 'correct' => [1], 'explanation' => 'Le CRM centralise la relation client : contacts, historique et opportunités.'],
                        ['question' => 'Que représente le pipeline commercial ?', 'options' => ['La liste des salaires', 'Les affaires en cours réparties par étape de vente', 'Le plan comptable', 'Le stock de produits'], 'correct' => [1], 'explanation' => 'Le pipeline visualise les opportunités selon leur avancement dans le cycle de vente.'],
                        ['question' => 'Pourquoi fidéliser un grand compte existant ?', 'options' => ['Parce que c\'est obligatoire par la loi', 'Parce que le conserver coûte moins cher que d\'en conquérir un nouveau', 'Parce que c\'est sans intérêt', 'Pour augmenter les coûts'], 'correct' => [1], 'explanation' => 'Conserver un client fidèle est nettement moins coûteux que d\'en acquérir un nouveau.'],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Les indicateurs commerciaux',
                    'subtitle' => 'Mesurer pour progresser',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : piloter ta performance avec les bons indicateurs (KPI).\n\n💡 Les KPI commerciaux essentiels :\n• **Taux de conversion** : opportunités gagnées / opportunités totales.\n• **Cycle de vente moyen** : durée entre le premier contact et la signature.\n• **Panier moyen** : montant moyen par contrat.\n• **Taux de rétention** : clients conservés d'une année sur l'autre.\n• **CAC** : coût d'acquisition d'un client.\n• **LTV** : valeur totale d'un client sur toute la relation.\n\n✅ Tableau de bord type :\n\n| KPI | Mois | Objectif |\n|---|---|---|\n| Conversion | 22% | 25% |\n| Panier moyen | 1,5 M FCFA | 2 M FCFA |\n| Rétention | 85% | 90% |\n\n⚠️ Ce qui ne se mesure pas ne s'améliore pas : suis tes chiffres chaque semaine.\n\n🏆 **Félicitations !** Tu maîtrises désormais le cycle complet de la vente B2B : prospection, qualification, décideurs, valeur, négociation, CRM et pilotage. Tu peux viser des postes de **commercial B2B**, **business developer**, **key account manager** puis évoluer vers **directeur commercial**. Avec ces compétences, des entreprises de Douala à Abidjan recherchent des profils comme le tien. Continue à pratiquer : la vente est un métier qui s'affûte sur le terrain. Bonne route ! 🚀",
                    'questions' => [
                        ['question' => 'Comment calcule-t-on le taux de conversion ?', 'options' => ['Chiffre d\'affaires divisé par les charges', 'Opportunités gagnées divisées par opportunités totales', 'Nombre d\'appels par jour', 'Nombre d\'employés'], 'correct' => [1], 'explanation' => 'Le taux de conversion rapporte les affaires gagnées au total des opportunités.'],
                        ['question' => 'Que mesure la LTV (Lifetime Value) ?', 'options' => ['La valeur totale d\'un client sur toute la durée de la relation', 'Le prix d\'un seul produit', 'Le salaire du commercial', 'La durée d\'un appel'], 'correct' => [0], 'explanation' => 'La LTV mesure la valeur générée par un client sur l\'ensemble de la relation.'],
                        ['question' => 'Pourquoi suivre régulièrement ses indicateurs commerciaux ?', 'options' => ['Pour faire joli', 'Parce que ce qui ne se mesure pas ne s\'améliore pas', 'Pour perdre du temps', 'Cela n\'a aucune utilité'], 'correct' => [1], 'explanation' => 'Le suivi des KPI permet d\'identifier les points faibles et de progresser.'],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Commercial B2B créée (10 niveaux).');
    }
}
