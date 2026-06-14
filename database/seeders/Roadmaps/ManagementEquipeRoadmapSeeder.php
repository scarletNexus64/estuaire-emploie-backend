<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Management d'Équipe — devenir un manager efficace et inspirant.
 */
class ManagementEquipeRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Deviens Manager d\'Équipe',
            'slug' => 'management-equipe',
            'domain' => 'gestion',
            'description' => "Apprends à diriger une équipe avec méthode et humanité. De la posture du manager à la gestion des conflits, en passant par la délégation, la motivation et le feedback, cette roadmap te donne les outils concrets pour faire grandir tes collaborateurs et atteindre les objectifs, dans le contexte des entreprises africaines.",
            'objectives' => "Comprendre le rôle et la posture du manager\nAdapter son style de management aux situations\nFixer des objectifs clairs et mesurables\nDéléguer efficacement sans tout contrôler\nMotiver et reconnaître ses collaborateurs\nGérer les conflits et accompagner la performance",
            'icon' => '👥',
            'color' => '#2563EB',
            'difficulty' => 'intermediate',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 20,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Le rôle du manager',
                    'subtitle' => 'Comprendre ce qu\'on attend vraiment de toi',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : comprendre que manager, ce n\'est pas faire le travail à la place des autres, mais faire réussir une équipe.\n\n💡 Le passage d\'expert technique à manager est un vrai changement de métier. Un bon commercial ne devient pas automatiquement un bon manager.\n\nLes 4 grandes missions du manager :\n• **Organiser** : répartir le travail, clarifier qui fait quoi\n• **Décider** : trancher, arbitrer les priorités\n• **Animer** : motiver, fédérer autour d\'un cap commun\n• **Développer** : faire monter chacun en compétence\n\n✅ Exemple à Douala : Aïcha dirige une équipe de 5 vendeurs. Au début elle vendait elle-même pour montrer l\'exemple. Résultat : épuisée et son équipe attendait. Quand elle a commencé à fixer des objectifs clairs et à accompagner, les ventes ont grimpé.\n\n⚠️ Piège classique : vouloir rester le meilleur technicien. Ton succès se mesure désormais aux résultats de l\'équipe, pas aux tiens.",
                    'questions' => [
                        ['question' => 'Quelle est la principale différence entre un expert et un manager ?', 'options' => ['Le manager gagne toujours plus', 'Le manager fait réussir les autres plutôt que de produire seul', 'Le manager ne travaille plus', 'Le manager n\'a pas besoin de compétences'], 'correct' => [1], 'explanation' => 'Le rôle du manager est de faire réussir son équipe, pas de produire seul comme un expert.'],
                        ['question' => 'Parmi ces missions, lesquelles relèvent du manager ?', 'options' => ['Organiser le travail', 'Faire tout le travail technique seul', 'Développer les compétences de l\'équipe', 'Animer et motiver'], 'correct' => [0,2,3], 'explanation' => 'Organiser, développer et animer sont des missions du manager ; faire tout le travail seul ne l\'est pas.'],
                        ['question' => 'Comment se mesure désormais le succès d\'un manager ?', 'options' => ['À ses propres performances techniques', 'Aux résultats collectifs de son équipe', 'À son ancienneté', 'À son salaire'], 'correct' => [1], 'explanation' => 'Le succès du manager se mesure aux résultats obtenus par et avec son équipe.'],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — La posture et l\'autorité',
                    'subtitle' => 'Asseoir sa légitimité sans autoritarisme',
                    'xp_reward' => 110,
                    'content' => "🎯 **Objectif** : trouver le juste équilibre entre proximité et autorité.\n\n💡 L\'autorité ne se décrète pas par le titre, elle se construit par la confiance et la cohérence.\n\nLes 3 sources d\'autorité :\n• **Statutaire** : ton poste (faible à elle seule)\n• **De compétence** : ton savoir-faire reconnu\n• **Personnelle** : ton exemplarité, ta fiabilité\n\n✅ Bonne posture : ferme sur les objectifs, souple sur les moyens. Tu écoutes, mais tu assumes les décisions difficiles.\n\n⚠️ Deux pièges opposés :\n• Le **copain** : trop proche, n\'ose pas recadrer, perd le respect\n• Le **petit chef** : autoritaire, donne des ordres, casse la motivation\n\nÀ Yaoundé, un chef d\'équipe respecté est souvent celui qui dit ce qu\'il fait et fait ce qu\'il dit. La parole tenue vaut plus que mille discours.\n\nLa bonne distance : assez proche pour comprendre, assez en retrait pour décider sereinement.",
                    'questions' => [
                        ['question' => 'L\'autorité d\'un manager repose principalement sur :', 'options' => ['Le titre uniquement', 'La confiance, la compétence et l\'exemplarité', 'La peur qu\'il inspire', 'Son salaire élevé'], 'correct' => [1], 'explanation' => 'L\'autorité durable se construit sur la confiance, la compétence reconnue et l\'exemplarité.'],
                        ['question' => 'Quelle posture est recommandée pour un manager ?', 'options' => ['Souple sur les objectifs, ferme sur les moyens', 'Ferme sur les objectifs, souple sur les moyens', 'Toujours autoritaire', 'Toujours copain avec tous'], 'correct' => [1], 'explanation' => 'Être ferme sur les objectifs et souple sur les moyens responsabilise l\'équipe.'],
                        ['question' => 'Quel est le risque de la posture "copain" ?', 'options' => ['Trop de discipline', 'Ne plus oser recadrer et perdre le respect', 'Trop d\'efficacité', 'Une équipe démotivée par la pression'], 'correct' => [1], 'explanation' => 'Trop de proximité empêche de recadrer et finit par éroder le respect de l\'équipe.'],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — Les styles de management',
                    'subtitle' => 'Adapter son style à chaque situation',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : comprendre qu\'il n\'existe pas un seul bon style, mais un style adapté à chaque collaborateur et chaque situation.\n\n💡 Le management situationnel (Hersey & Blanchard) distingue 4 styles selon l\'autonomie du collaborateur :\n\n| Style | Quand l\'utiliser |\n|-------|------------------|\n| **Directif** | Débutant, peu autonome : on dit quoi faire |\n| **Persuasif** | Motivé mais peu expérimenté : on explique |\n| **Participatif** | Compétent mais hésitant : on associe |\n| **Délégatif** | Autonome et fiable : on fait confiance |\n\n✅ Exemple : un nouveau livreur a besoin d\'un style directif (consignes précises). Après 6 mois, il mérite un style délégatif.\n\n⚠️ Erreur fréquente : appliquer le même style à toute l\'équipe. Être directif avec un expert le frustre ; être délégatif avec un débutant le laisse échouer.\n\nLe bon manager change de casquette selon la personne en face de lui. Le diagnostic d\'autonomie est la clé.",
                    'questions' => [
                        ['question' => 'Quel style convient à un collaborateur débutant et peu autonome ?', 'options' => ['Délégatif', 'Directif', 'Participatif', 'Aucun encadrement'], 'correct' => [1], 'explanation' => 'Un débutant a besoin de consignes précises, donc d\'un style directif.'],
                        ['question' => 'Le management situationnel signifie :', 'options' => ['Garder toujours le même style', 'Adapter son style à l\'autonomie de chacun', 'Toujours déléguer', 'Toujours être directif'], 'correct' => [1], 'explanation' => 'Le management situationnel consiste à adapter son style au niveau d\'autonomie du collaborateur.'],
                        ['question' => 'Quels styles conviennent à un collaborateur autonome et compétent ?', 'options' => ['Délégatif', 'Directif strict', 'Participatif', 'Surveillance constante'], 'correct' => [0,2], 'explanation' => 'Avec un collaborateur autonome, on privilégie le délégatif ou le participatif plutôt que le contrôle.'],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — Fixer des objectifs',
                    'subtitle' => 'Donner un cap clair avec la méthode SMART',
                    'xp_reward' => 130,
                    'content' => "🎯 **Objectif** : transformer une intention floue en objectif clair et atteignable.\n\n💡 La méthode **SMART** rend un objectif solide :\n• **S**pécifique : précis, pas vague\n• **M**esurable : un chiffre, une date\n• **A**tteignable : réaliste avec les moyens donnés\n• **R**elevant (pertinent) : aligné avec la stratégie\n• **T**emporel : avec une échéance\n\n✅ Mauvais objectif : « Augmente les ventes. »\nBon objectif SMART : « Augmente de 15 % le nombre de clients mobile money entre juin et août, dans le quartier Akwa. »\n\nLa différence est énorme : le second se suit, se mesure, se célèbre.\n\n⚠️ Un objectif sans échéance ni mesure n\'est qu\'un vœu. Et un objectif imposé sans dialogue est rarement porté avec énergie.\n\nBonne pratique : co-construire l\'objectif avec le collaborateur. Quand quelqu\'un participe à fixer sa cible, il s\'engage bien plus à l\'atteindre. Écris l\'objectif noir sur blanc pour éviter les malentendus.",
                    'questions' => [
                        ['question' => 'Que signifie le "M" de SMART ?', 'options' => ['Motivant', 'Mesurable', 'Managérial', 'Modeste'], 'correct' => [1], 'explanation' => 'Le M de SMART signifie Mesurable : l\'objectif doit comporter un indicateur chiffré.'],
                        ['question' => 'Lequel est un objectif SMART ?', 'options' => ['Faire mieux ce mois-ci', 'Augmenter de 15% les clients d\'ici août', 'Travailler plus dur', 'Être plus performant'], 'correct' => [1], 'explanation' => 'Seul cet énoncé est spécifique, mesurable et temporel, donc SMART.'],
                        ['question' => 'Pourquoi co-construire un objectif avec le collaborateur ?', 'options' => ['Pour gagner du temps', 'Pour renforcer son engagement à l\'atteindre', 'Pour éviter de décider', 'Parce que c\'est obligatoire'], 'correct' => [1], 'explanation' => 'Participer à la définition d\'un objectif augmente fortement l\'engagement à le réaliser.'],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — Déléguer efficacement',
                    'subtitle' => 'Confier sans abandonner ni surcontrôler',
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : libérer ton temps et faire grandir l\'équipe en déléguant intelligemment.\n\n💡 Déléguer, ce n\'est ni se débarrasser d\'une tâche, ni la refaire derrière. C\'est confier une responsabilité avec les moyens et la confiance associés.\n\nLes 5 étapes d\'une délégation réussie :\n• **Choisir** la bonne tâche et la bonne personne\n• **Expliquer** le quoi et le pourquoi (pas seulement le comment)\n• **Donner** les moyens et l\'autorité nécessaires\n• **Fixer** les points de contrôle (pas du flicage)\n• **Évaluer** et reconnaître le résultat\n\n✅ Exemple : confier à un collaborateur la gestion d\'un fournisseur à Douala, avec un budget plafond et un point hebdomadaire.\n\n⚠️ Deux erreurs :\n• **Sous-déléguer** : tout garder, s\'épuiser, créer un goulot d\'étranglement\n• **Sur-déléguer** sans accompagner : lancer quelqu\'un sans filet\n\nDéléguer une tâche, oui ; déléguer la responsabilité finale, jamais : tu restes garant du résultat devant ta hiérarchie.",
                    'questions' => [
                        ['question' => 'Déléguer consiste à :', 'options' => ['Se débarrasser d\'une corvée', 'Confier une responsabilité avec moyens et confiance', 'Refaire le travail derrière le collaborateur', 'Ne plus rien contrôler'], 'correct' => [1], 'explanation' => 'Déléguer, c\'est confier une vraie responsabilité avec les moyens et un cadre de confiance.'],
                        ['question' => 'Quel est le risque de la sous-délégation (tout garder) ?', 'options' => ['Une équipe trop autonome', 'L\'épuisement du manager et un goulot d\'étranglement', 'Trop de motivation', 'Un budget dépassé'], 'correct' => [1], 'explanation' => 'Ne rien déléguer épuise le manager et bloque le travail de toute l\'équipe.'],
                        ['question' => 'Quand on délègue une tâche, que conserve le manager ?', 'options' => ['Tout le travail opérationnel', 'La responsabilité finale du résultat', 'Rien du tout', 'Le droit de tout refaire'], 'correct' => [1], 'explanation' => 'On délègue l\'exécution mais le manager reste garant du résultat devant sa hiérarchie.'],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — Motiver et reconnaître',
                    'subtitle' => 'Entretenir l\'énergie et l\'engagement',
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : comprendre ce qui motive vraiment et savoir reconnaître les efforts.\n\n💡 L\'argent motive, mais moins longtemps qu\'on le croit. La pyramide de Maslow et les travaux de Herzberg montrent que la reconnaissance, le sens et l\'évolution comptent énormément.\n\nLes leviers de motivation :\n• **Reconnaissance** : un merci sincère, public quand c\'est mérité\n• **Sens** : expliquer pourquoi le travail est utile\n• **Autonomie** : laisser de la marge de manœuvre\n• **Progression** : montrer les perspectives d\'évolution\n• **Conditions** : un environnement de travail correct\n\n✅ Astuce simple : la reconnaissance ne coûte rien. Dire « Bravo Jean, ta gestion du client a fait la différence » devant l\'équipe vaut parfois plus qu\'une prime.\n\n⚠️ Démotivateurs puissants : l\'injustice, le favoritisme, l\'absence totale de retour, les promesses non tenues.\n\nRègle d\'or : reconnaître en public, recadrer en privé. Et adapter la reconnaissance à la personne : certains aiment l\'éloge public, d\'autres préfèrent un mot discret.",
                    'questions' => [
                        ['question' => 'Selon Herzberg, qu\'est-ce qui motive durablement ?', 'options' => ['Uniquement le salaire', 'La reconnaissance, le sens et la progression', 'La peur de la sanction', 'Les horaires fixes'], 'correct' => [1], 'explanation' => 'La reconnaissance, le sens et les perspectives d\'évolution motivent plus durablement que le seul salaire.'],
                        ['question' => 'Quelle est la règle d\'or de la reconnaissance et du recadrage ?', 'options' => ['Tout faire en public', 'Reconnaître en public, recadrer en privé', 'Tout faire en privé', 'Ne jamais commenter'], 'correct' => [1], 'explanation' => 'On valorise les réussites devant l\'équipe et on corrige les erreurs en tête-à-tête.'],
                        ['question' => 'Quels éléments démotivent fortement une équipe ?', 'options' => ['Le favoritisme', 'La reconnaissance sincère', 'Les promesses non tenues', 'L\'autonomie'], 'correct' => [0,2], 'explanation' => 'Le favoritisme et les promesses non tenues détruisent la confiance et la motivation.'],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — Conduire une réunion efficace',
                    'subtitle' => 'Des réunions utiles, courtes et décisives',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : animer des réunions qui font avancer plutôt que perdre du temps.\n\n💡 Une réunion mal préparée coûte cher : multiplie le temps perdu par le nombre de participants. Une réunion réussie a un objectif clair et se termine par des décisions.\n\nLa méthode **TOP** :\n• **T**hème : un sujet précis annoncé à l\'avance\n• **O**bjectif : informer, décider ou résoudre ?\n• **P**lan : un ordre du jour minuté\n\nLes 3 temps d\'une réunion :\n• Ouvrir : rappeler l\'objectif et le temps imparti\n• Animer : faire participer, recentrer, trancher\n• Clore : résumer les décisions et le **qui fait quoi pour quand**\n\n✅ Exemple : « Réunion d\'équipe, 30 min, objectif : décider du planning des congés de fin d\'année. »\n\n⚠️ Pièges courants : pas d\'ordre du jour, trop de participants, monologue du chef, aucune décision actée, et le classique relevé de décisions oublié.\n\nUne réunion sans relevé de décisions écrit est une réunion à moitié perdue. Envoie un court compte-rendu après chaque réunion.",
                    'questions' => [
                        ['question' => 'Que doit toujours avoir une réunion efficace ?', 'options' => ['Beaucoup de participants', 'Un objectif clair et un ordre du jour', 'Une durée illimitée', 'Aucune préparation'], 'correct' => [1], 'explanation' => 'Un objectif clair et un ordre du jour structurent une réunion utile.'],
                        ['question' => 'Comment doit se clore une bonne réunion ?', 'options' => ['Par un débat ouvert sans fin', 'Par un résumé des décisions et un "qui fait quoi pour quand"', 'En partant discrètement', 'Sans rien noter'], 'correct' => [1], 'explanation' => 'Clore par les décisions et les responsabilités assure le suivi des actions.'],
                        ['question' => 'Que représente le "O" de la méthode TOP ?', 'options' => ['Organisation', 'Objectif de la réunion', 'Ordre alphabétique', 'Obligation'], 'correct' => [1], 'explanation' => 'Le O de TOP désigne l\'Objectif : informer, décider ou résoudre.'],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — Feedback et entretien',
                    'subtitle' => 'Dire les choses de façon constructive',
                    'xp_reward' => 170,
                    'content' => "🎯 **Objectif** : maîtriser l\'art du feedback pour faire progresser sans blesser.\n\n💡 Le feedback est un cadeau : encore faut-il bien l\'emballer. Un bon retour est factuel, à temps, et orienté solution.\n\nLa méthode **DESC** pour un feedback de recadrage :\n• **D**écrire les faits objectivement (pas d\'interprétation)\n• **E**xprimer son ressenti ou l\'impact\n• **S**pécifier la solution attendue\n• **C**onséquences positives du changement\n\n✅ Exemple : « Tu es arrivé à 9h30 trois fois cette semaine (faits). Ça désorganise l\'ouverture (impact). J\'ai besoin que tu sois là à 8h (solution). Comme ça l\'équipe démarre sereine (conséquence). »\n\nLe feedback positif compte aussi : sois précis, pas juste « c\'est bien », mais « ta relance du client X a sauvé la commande ».\n\n⚠️ À éviter : le feedback sandwich mécanique, les généralités (« tu n\'es jamais ponctuel »), et attendre l\'entretien annuel pour tout dire.\n\nL\'entretien annuel n\'est qu\'un bilan ; le vrai pilotage se fait par des feedbacks réguliers tout au long de l\'année.",
                    'questions' => [
                        ['question' => 'Un bon feedback de recadrage doit d\'abord :', 'options' => ['Juger la personne', 'Décrire des faits objectifs', 'Menacer de sanctions', 'Rester vague'], 'correct' => [1], 'explanation' => 'On commence par décrire des faits objectifs, sans jugement de la personne.'],
                        ['question' => 'Que signifie le "S" de la méthode DESC ?', 'options' => ['Sanctionner', 'Spécifier la solution attendue', 'Surveiller', 'Se taire'], 'correct' => [1], 'explanation' => 'Le S de DESC consiste à spécifier clairement la solution ou le comportement attendu.'],
                        ['question' => 'À quelle fréquence donner du feedback ?', 'options' => ['Une fois par an à l\'entretien', 'Régulièrement tout au long de l\'année', 'Seulement en cas de faute grave', 'Jamais pour ne pas vexer'], 'correct' => [1], 'explanation' => 'Le feedback doit être régulier ; l\'entretien annuel n\'est qu\'un bilan complémentaire.'],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Gérer les conflits',
                    'subtitle' => 'Transformer les tensions en solutions',
                    'xp_reward' => 185,
                    'content' => "🎯 **Objectif** : intervenir dans un conflit sans l\'aggraver et en restaurant la coopération.\n\n💡 Un conflit ignoré ne disparaît pas, il pourrit l\'ambiance. Le manager doit l\'affronter avec calme et méthode.\n\nLes 5 attitudes face au conflit (Thomas-Kilmann) :\n• **Évitement** : on fuit (parfois utile, souvent dangereux)\n• **Accommodation** : on cède\n• **Compétition** : on impose\n• **Compromis** : chacun lâche un peu\n• **Collaboration** : on cherche le gagnant-gagnant (idéal)\n\nDémarche de médiation :\n• Recevoir chacun séparément, écouter sans juger\n• Distinguer les faits des émotions et des positions\n• Réunir les parties sur un terrain neutre\n• Chercher l\'intérêt commun, pas le coupable\n\n✅ Exemple : deux collègues à Yaoundé se disputent un client. Plutôt que trancher pour l\'un, le manager redéfinit une règle de répartition claire pour l\'avenir.\n\n⚠️ Erreurs : prendre parti trop vite, traiter le conflit en public, ou faire l\'autruche en espérant que ça passe.\n\nObjectif : régler le problème de fond, pas seulement éteindre l\'incendie du moment.",
                    'questions' => [
                        ['question' => 'Quelle attitude vise un résultat gagnant-gagnant ?', 'options' => ['L\'évitement', 'La collaboration', 'La compétition', 'L\'accommodation'], 'correct' => [1], 'explanation' => 'La collaboration cherche une solution satisfaisante pour toutes les parties.'],
                        ['question' => 'Que faire en premier face à un conflit entre deux collaborateurs ?', 'options' => ['Trancher publiquement', 'Recevoir chacun séparément et écouter', 'Ignorer le problème', 'Sanctionner les deux'], 'correct' => [1], 'explanation' => 'Écouter chaque partie séparément permet de comprendre avant d\'agir.'],
                        ['question' => 'Quelles erreurs aggravent un conflit ?', 'options' => ['Prendre parti trop vite', 'Chercher l\'intérêt commun', 'Faire l\'autruche', 'Écouter sans juger'], 'correct' => [0,2], 'explanation' => 'Prendre parti précipitamment et ignorer le conflit l\'aggravent au lieu de le résoudre.'],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Leadership et exemplarité',
                    'subtitle' => 'Inspirer et accompagner la performance dans la durée',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : passer de manager à leader, capable d\'inspirer et de faire grandir durablement son équipe.\n\n💡 Le management gère l\'existant ; le leadership donne une direction et donne envie de suivre. Les deux sont complémentaires.\n\nLes piliers du leader :\n• **Exemplarité** : être le premier à incarner ce qu\'on exige\n• **Vision** : donner un cap qui dépasse les tâches quotidiennes\n• **Confiance** : croire en son équipe et le montrer\n• **Courage** : décider, assumer, protéger son équipe\n\nAccompagner la performance dans la durée :\n• Suivre des indicateurs simples et partagés\n• Célébrer les réussites collectives\n• Traiter les écarts tôt, avec bienveillance et fermeté\n• Faire progresser : formation, mentorat, responsabilités nouvelles\n\n✅ Le vrai leader se reconnaît à ceci : l\'équipe continue de bien fonctionner même quand il n\'est pas là.\n\n⚠️ Le pouvoir sans exemplarité ni respect ne tient jamais longtemps.\n\n🏆 **Félicitations !** Tu as terminé la roadmap Management d\'Équipe. Tu maîtrises désormais la posture, la délégation, la motivation, le feedback et la gestion des conflits. Débouchés : chef d\'équipe, responsable de service, manager de projet, puis directeur opérationnel. Continue à pratiquer : le management est un art qui s\'affine sur le terrain. Bon leadership !",
                    'questions' => [
                        ['question' => 'Quelle est la différence entre management et leadership ?', 'options' => ['Aucune, c\'est pareil', 'Le management gère l\'existant, le leadership donne une direction inspirante', 'Le leadership ne sert à rien', 'Le management concerne uniquement l\'argent'], 'correct' => [1], 'explanation' => 'Le management gère et organise, tandis que le leadership inspire et fixe un cap.'],
                        ['question' => 'À quoi reconnaît-on un vrai leader ?', 'options' => ['Son équipe ne fonctionne que sous son contrôle', 'Son équipe continue à bien fonctionner même en son absence', 'Il prend toutes les décisions seul', 'Il garde le pouvoir pour lui'], 'correct' => [1], 'explanation' => 'Un bon leader rend son équipe autonome, capable de réussir même sans lui.'],
                        ['question' => 'Quels sont des piliers du leadership ?', 'options' => ['L\'exemplarité', 'La vision et le courage', 'Le favoritisme', 'L\'absence de décision'], 'correct' => [0,1], 'explanation' => 'L\'exemplarité, la vision et le courage sont des piliers du leadership ; le favoritisme le détruit.'],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Management d\'Équipe créée (10 niveaux).');
    }
}
