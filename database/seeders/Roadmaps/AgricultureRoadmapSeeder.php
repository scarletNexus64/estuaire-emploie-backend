<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Agriculture — maîtriser les techniques agricoles modernes du sol à la gestion d'exploitation.
 */
class AgricultureRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Maîtrise les Techniques Agricoles Modernes',
            'slug' => 'agriculture-techniques',
            'domain' => 'agriculture',
            'description' => "Une formation pratique pour produire mieux et plus, du sol jusqu'à la gestion de ton exploitation. Adaptée au contexte camerounais et africain : cultures vivrières, maraîchage, climat tropical et ressources locales. Tu apprendras à comprendre ton sol, préparer ton terrain, semer, irriguer, fertiliser, protéger tes cultures et vendre ta production de façon rentable et durable.",
            'objectives' => "Comprendre la composition et la fertilité d'un sol\nPréparer correctement un terrain de culture\nChoisir et semer de bonnes semences\nMaîtriser l'irrigation et la fertilisation\nLutter contre les ravageurs et maladies\nPratiquer la rotation et l'agriculture durable\nGérer une exploitation agricole comme une entreprise",
            'icon' => '🌱',
            'color' => '#16A34A',
            'difficulty' => 'beginner',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 20,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Le sol et sa fertilité',
                    'subtitle' => 'Comprendre ce qui fait vivre tes cultures',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : comprendre que tout commence par le sol. Un bon sol = de bonnes récoltes.\n\n💡 Le sol n'est pas que de la terre : c'est un milieu vivant qui nourrit la plante.\n\n• **Les 3 textures de base** : sableux (draine vite, pauvre), argileux (retient l'eau, lourd), limoneux (équilibré, idéal).\n• **L'humus** : matière organique décomposée qui nourrit et structure le sol.\n• **Le pH** mesure l'acidité : la plupart des cultures aiment un pH de 6 à 7.\n\n✅ **Test simple sur le terrain** : prends une poignée de terre humide et serre-la.\n• Elle s'effrite → sableuse\n• Elle forme une boule collante → argileuse\n• Elle tient sans coller → limoneuse (bon signe)\n\n⚠️ À Douala ou Yaoundé, les sols ferralitiques rouges sont souvent acides et pauvres en matière organique : il faudra les enrichir.\n\nUn sol fertile a 4 qualités : il retient l'eau, laisse passer l'air, contient des nutriments et abrite des vers de terre et microbes.",
                    'questions' => [
                        ['question' => 'Quel type de sol est généralement le plus équilibré pour la culture ?', 'options' => ['Sableux', 'Limoneux', 'Argileux pur', 'Rocheux'], 'correct' => [1], 'explanation' => 'Le sol limoneux retient bien l\'eau tout en laissant passer l\'air, c\'est le plus équilibré.'],
                        ['question' => 'Que mesure le pH du sol ?', 'options' => ['Sa température', 'Son acidité', 'Sa quantité d\'eau', 'Son poids'], 'correct' => [1], 'explanation' => 'Le pH mesure l\'acidité ou l\'alcalinité du sol ; la plupart des cultures préfèrent un pH de 6 à 7.'],
                        ['question' => 'Quels éléments sont des signes d\'un sol fertile et vivant ?', 'options' => ['Présence de vers de terre', 'Couleur uniquement', 'Présence de matière organique (humus)', 'Aucune vie microbienne'], 'correct' => [0, 2], 'explanation' => 'Les vers de terre et l\'humus indiquent un sol vivant, aéré et riche en nutriments.'],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — Préparer le terrain',
                    'subtitle' => 'Du défrichage aux planches de culture',
                    'xp_reward' => 110,
                    'content' => "🎯 **Objectif** : transformer un terrain brut en sol prêt à recevoir les semences.\n\n💡 Une bonne préparation supprime la concurrence des mauvaises herbes et ameublit le sol pour les racines.\n\n**Les étapes dans l'ordre :**\n• **Nettoyage / défrichage** : enlever herbes, souches et pierres.\n• **Labour** : retourner le sol (houe, daba, charrue ou motoculteur) sur 20 à 30 cm.\n• **Émottage** : casser les grosses mottes pour affiner la terre.\n• **Nivellement** : aplanir pour éviter l'eau stagnante.\n• **Confection des planches / billons** : surélever pour drainer en saison des pluies.\n\n✅ **Astuce maraîchage** : des planches de 1 m de large permettent de désherber des deux côtés sans piétiner le sol.\n\n⚠️ Évite de labourer un sol détrempé : tu le compactes et tu détruis sa structure. Attends qu'il soit ressuyé (humide mais pas boueux).\n\n📏 Schéma d'une planche maraîchère :\n```\n[allée 40cm] [PLANCHE 100cm] [allée 40cm]\n```\n\nLe labour incorpore aussi le compost et expose les larves d'insectes aux oiseaux et au soleil.",
                    'questions' => [
                        ['question' => 'Pourquoi confectionne-t-on des billons ou planches surélevées ?', 'options' => ['Pour décorer le champ', 'Pour drainer l\'eau et éviter la stagnation', 'Pour augmenter le pH', 'Pour attirer les insectes'], 'correct' => [1], 'explanation' => 'Les billons surélevés évacuent l\'excès d\'eau, crucial en saison des pluies.'],
                        ['question' => 'Quand faut-il éviter de labourer le sol ?', 'options' => ['Quand il est ressuyé', 'Quand il est détrempé / boueux', 'Le matin', 'En saison sèche'], 'correct' => [1], 'explanation' => 'Labourer un sol détrempé le compacte et détruit sa structure ; il faut attendre qu\'il soit ressuyé.'],
                        ['question' => 'Quel est le rôle de l\'émottage ?', 'options' => ['Casser les grosses mottes pour affiner la terre', 'Arroser le champ', 'Semer les graines', 'Récolter'], 'correct' => [0], 'explanation' => 'L\'émottage casse les mottes pour obtenir une terre fine où les racines pénètrent facilement.'],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — Semences et semis',
                    'subtitle' => 'Bien choisir et bien planter',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : réussir la levée des plantes grâce à de bonnes semences et un semis correct.\n\n💡 Une mauvaise semence ne donnera jamais une bonne récolte, même avec un sol parfait.\n\n**Choisir ses semences :**\n• Préférer des semences **certifiées** ou améliorées (meilleur rendement, résistance aux maladies).\n• Vérifier le **taux de germination** et la **date de péremption**.\n• Adapter la variété au climat local et à la saison.\n\n**Deux méthodes de semis :**\n• **Semis direct** : on sème là où la plante poussera (maïs, arachide, haricot).\n• **Pépinière puis repiquage** : on fait germer puis on transplante (tomate, piment, chou).\n\n✅ **Test de germination maison** : place 10 graines sur un coton humide. Si 8 germent en quelques jours, ton taux est de 80 % — bon à semer.\n\n⚠️ Respecte les **espacements** : trop serré = concurrence pour l'eau et la lumière, plantes faibles.\n\nExemple maïs : 75 cm entre lignes, 25 cm entre poquets. La profondeur de semis = environ 2 à 3 fois la taille de la graine.",
                    'questions' => [
                        ['question' => 'Quelle culture se sème généralement en pépinière avant repiquage ?', 'options' => ['Le maïs', 'L\'arachide', 'La tomate', 'Le haricot'], 'correct' => [2], 'explanation' => 'La tomate, comme le piment et le chou, se sème en pépinière puis se repique.'],
                        ['question' => 'Comment vérifier la qualité d\'un lot de semences à la maison ?', 'options' => ['En les pesant', 'Par un test de germination sur coton humide', 'En les chauffant', 'En les peignant'], 'correct' => [1], 'explanation' => 'Le test de germination sur coton humide indique le pourcentage de graines viables.'],
                        ['question' => 'Quels sont les risques d\'un semis trop serré ?', 'options' => ['Concurrence pour l\'eau et la lumière', 'Meilleur rendement garanti', 'Plantes faibles et chétives', 'Aucun risque'], 'correct' => [0, 2], 'explanation' => 'Trop serrées, les plantes se concurrencent pour l\'eau, les nutriments et la lumière, et deviennent chétives.'],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — L\'irrigation',
                    'subtitle' => 'Donner la juste quantité d\'eau',
                    'xp_reward' => 130,
                    'content' => "🎯 **Objectif** : apporter l'eau de façon efficace, sans gaspillage ni excès.\n\n💡 L'eau est vitale mais trop d'eau tue les racines (asphyxie) et favorise les maladies.\n\n**Principales méthodes :**\n• **Arrosoir / seau** : simple, adapté aux petites surfaces, mais demande du temps.\n• **Aspersion** : asperge comme la pluie, bon pour de grandes surfaces.\n• **Goutte-à-goutte** : amène l'eau directement aux racines, économise jusqu'à 50 % d'eau. Idéal en saison sèche.\n\n✅ **Bonnes pratiques :**\n• Arroser tôt le matin ou en fin de journée pour limiter l'évaporation.\n• Arroser au pied, pas sur les feuilles (réduit les maladies).\n• Le paillage (mulch) garde l'humidité plus longtemps.\n\n⚠️ Signes d'un mauvais arrosage :\n| Manque d'eau | Excès d'eau |\n|---|---|\n| Feuilles flétries, sol craquelé | Feuilles jaunes, racines pourries |\n\nEn saison des pluies au Cameroun, l'irrigation est souvent inutile ; c'est surtout en saison sèche (novembre à mars) qu'elle devient indispensable pour le maraîchage.",
                    'questions' => [
                        ['question' => 'Quelle méthode d\'irrigation économise le plus d\'eau ?', 'options' => ['Aspersion', 'Goutte-à-goutte', 'Inondation', 'Arrosoir'], 'correct' => [1], 'explanation' => 'Le goutte-à-goutte amène l\'eau directement aux racines et peut économiser jusqu\'à 50 % d\'eau.'],
                        ['question' => 'À quel moment de la journée vaut-il mieux arroser ?', 'options' => ['En plein midi', 'Tôt le matin ou en fin de journée', 'Jamais le matin', 'Uniquement la nuit profonde'], 'correct' => [1], 'explanation' => 'Arroser tôt le matin ou en soirée réduit l\'évaporation et profite mieux à la plante.'],
                        ['question' => 'Quel est un signe d\'excès d\'eau ?', 'options' => ['Feuilles jaunes et racines pourries', 'Sol craquelé', 'Feuilles flétries par manque', 'Croissance accélérée'], 'correct' => [0], 'explanation' => 'L\'excès d\'eau asphyxie les racines, provoquant jaunissement et pourriture.'],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — Fertilisation et compost',
                    'subtitle' => 'Nourrir le sol et les plantes',
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : apporter les nutriments dont les plantes ont besoin, naturellement ou avec des engrais.\n\n💡 Les plantes consomment surtout 3 éléments, le **N-P-K** :\n• **N (Azote)** → feuilles et croissance verte.\n• **P (Phosphore)** → racines, fleurs et fruits.\n• **K (Potassium)** → résistance et qualité des fruits.\n\n**Deux familles d'engrais :**\n• **Organiques** : fumier, compost, fientes de poules. Ils nourrissent le sol durablement.\n• **Minéraux / chimiques** : urée, NPK 20-10-10. Action rapide mais à doser.\n\n✅ **Fabriquer son compost** (gratuit et écologique) :\n• Alterne déchets verts (épluchures, herbes) et bruns (feuilles sèches, paille).\n• Garde humide, retourne le tas chaque semaine.\n• En 2 à 3 mois, tu obtiens un terreau noir et odorant de forêt.\n\n⚠️ Trop d'engrais azoté = belles feuilles mais peu de fruits, et pollution des eaux.\n\nLe compost et le fumier améliorent aussi la structure du sol, ce que les engrais chimiques seuls ne font pas. Idéal : combiner les deux.",
                    'questions' => [
                        ['question' => 'Que représente la lettre « N » dans l\'engrais NPK ?', 'options' => ['Le potassium', 'L\'azote', 'Le phosphore', 'Le sodium'], 'correct' => [1], 'explanation' => 'N désigne l\'azote, responsable de la croissance des feuilles et de la verdure.'],
                        ['question' => 'Pour fabriquer un bon compost, il faut...', 'options' => ['Alterner déchets verts et bruns', 'N\'utiliser que du plastique', 'Garder le tas totalement sec', 'Ne jamais le retourner'], 'correct' => [0], 'explanation' => 'Le compost se fait en alternant matières vertes et brunes, en gardant humide et en retournant le tas.'],
                        ['question' => 'Quels sont les avantages du compost par rapport aux engrais chimiques seuls ?', 'options' => ['Il améliore la structure du sol', 'Il pollue davantage', 'Il nourrit le sol durablement', 'Il acidifie systématiquement'], 'correct' => [0, 2], 'explanation' => 'Le compost nourrit durablement et améliore la structure du sol, ce que les engrais chimiques seuls ne font pas.'],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — Ravageurs et maladies',
                    'subtitle' => 'Protéger ses cultures intelligemment',
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : reconnaître les ennemis des cultures et lutter de façon raisonnée.\n\n💡 Deux grandes menaces :\n• **Ravageurs** : insectes (pucerons, chenilles, criquets), nématodes, rongeurs.\n• **Maladies** : champignons (mildiou, oïdium), bactéries, virus.\n\n**La lutte intégrée (la bonne approche) :**\n• **Prévention** : variétés résistantes, rotation, désherbage, plantes saines.\n• **Lutte biologique** : favoriser les auxiliaires (coccinelles mangent les pucerons), pièges, neem.\n• **Lutte chimique en dernier recours** : pesticides homologués, à la bonne dose.\n\n✅ **Recette locale** : macération de feuilles de neem ou de piment + savon pour repousser les pucerons. Bon marché et écologique.\n\n⚠️ **Sécurité pesticides — règles non négociables :**\n• Porter gants et masque.\n• Respecter le **délai avant récolte** (DAR) indiqué.\n• Ne JAMAIS manger un fruit traité la veille.\n• Ne pas vider les bidons dans les rivières.\n\nObserver son champ chaque jour permet de détecter une attaque tôt, quand elle est encore facile à contrôler.",
                    'questions' => [
                        ['question' => 'Le mildiou et l\'oïdium sont des maladies causées par...', 'options' => ['Des insectes', 'Des champignons', 'Des rongeurs', 'Le vent'], 'correct' => [1], 'explanation' => 'Le mildiou et l\'oïdium sont des maladies fongiques (causées par des champignons).'],
                        ['question' => 'Dans la lutte intégrée, le recours aux pesticides chimiques doit être...', 'options' => ['La première solution', 'Un dernier recours', 'Quotidien', 'Interdit en toute circonstance'], 'correct' => [1], 'explanation' => 'En lutte intégrée, le chimique n\'intervient qu\'en dernier recours, après prévention et lutte biologique.'],
                        ['question' => 'Quelles sont des règles de sécurité avec les pesticides ?', 'options' => ['Porter gants et masque', 'Respecter le délai avant récolte', 'Les vider dans la rivière', 'Manger les fruits le lendemain du traitement'], 'correct' => [0, 1], 'explanation' => 'Il faut se protéger (gants, masque) et respecter le délai avant récolte ; jeter les produits dans l\'eau ou consommer trop tôt est dangereux.'],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — La rotation des cultures',
                    'subtitle' => 'Préserver le sol et casser les cycles de nuisibles',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : comprendre pourquoi il ne faut pas cultiver la même plante au même endroit chaque année.\n\n💡 La **rotation** consiste à alterner les familles de cultures sur une même parcelle d'une saison à l'autre.\n\n**Pourquoi rotationner ?**\n• Chaque culture puise des nutriments différents : alterner évite d'épuiser le sol.\n• Casse le cycle des ravageurs et maladies spécifiques à une plante.\n• Les **légumineuses** (haricot, arachide, soja) fixent l'azote de l'air et enrichissent le sol pour la culture suivante.\n\n✅ **Exemple de rotation sur 3 ans :**\n```\nAnnée 1 : Maïs (gourmand en azote)\nAnnée 2 : Haricot/arachide (légumineuse, restitue l'azote)\nAnnée 3 : Manioc/igname (tubercule, peu exigeant)\n```\n\n⚠️ La **monoculture** répétée appauvrit le sol, multiplie les maladies et oblige à toujours plus d'engrais et de pesticides.\n\n💡 Règle simple : ne jamais faire suivre une culture par une plante de la même famille. Après la tomate (solanacée), évite le piment ou l'aubergine ; mets plutôt une légumineuse.",
                    'questions' => [
                        ['question' => 'Quel groupe de plantes enrichit le sol en azote ?', 'options' => ['Les tubercules', 'Les légumineuses', 'Les céréales', 'Les solanacées'], 'correct' => [1], 'explanation' => 'Les légumineuses (haricot, arachide, soja) fixent l\'azote de l\'air et enrichissent le sol.'],
                        ['question' => 'Quel est le principal défaut de la monoculture répétée ?', 'options' => ['Elle enrichit le sol', 'Elle appauvrit le sol et multiplie les maladies', 'Elle réduit le travail', 'Elle augmente la biodiversité'], 'correct' => [1], 'explanation' => 'Cultiver toujours la même plante épuise le sol et favorise l\'accumulation de ravageurs et maladies.'],
                        ['question' => 'Pourquoi pratiquer la rotation des cultures ?', 'options' => ['Pour éviter d\'épuiser le sol', 'Pour casser le cycle des ravageurs', 'Pour favoriser une seule maladie', 'Pour rendre le sol stérile'], 'correct' => [0, 1], 'explanation' => 'La rotation préserve la fertilité et interrompt le cycle de vie des nuisibles spécifiques.'],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — Récolte et conservation',
                    'subtitle' => 'Cueillir au bon moment et limiter les pertes',
                    'xp_reward' => 170,
                    'content' => "🎯 **Objectif** : récolter au bon stade et conserver pour vendre mieux et plus tard.\n\n💡 En Afrique, jusqu'à 30 % des récoltes sont perdues après la moisson (post-récolte). Bien conserver, c'est gagner de l'argent.\n\n**Récolter au bon moment :**\n• **Trop tôt** : produit immature, moins savoureux, faible poids.\n• **Trop tard** : sur-mûr, attaqué, invendable.\n• Récolter tôt le matin, manipuler avec soin (éviter les chocs et blessures).\n\n**Conservation selon le produit :**\n• **Céréales (maïs, riz)** : bien sécher (humidité < 13 %) puis stocker dans des sacs ou greniers propres et secs.\n• **Tubercules (manioc, igname)** : endroit frais, aéré, à l'ombre.\n• **Maraîchage (tomate, légumes)** : périssable, vendre vite ou transformer (séchage, jus, conserves).\n\n✅ **Astuce anti-pertes** : les sacs hermétiques (PICS) protègent les grains des charançons sans insecticide.\n\n⚠️ Un grain mal séché moisit et produit des aflatoxines (toxiques). Le séchage complet est une question de santé, pas seulement de stockage.\n\nTransformer (farine, jus, séchage) ajoute de la valeur et permet de vendre hors saison à meilleur prix.",
                    'questions' => [
                        ['question' => 'Pourquoi faut-il bien sécher les céréales avant le stockage ?', 'options' => ['Pour les rendre plus lourdes', 'Pour éviter moisissures et aflatoxines toxiques', 'Pour les colorer', 'Pour accélérer la germination'], 'correct' => [1], 'explanation' => 'Un grain mal séché moisit et développe des aflatoxines toxiques ; le séchage protège la santé et la conservation.'],
                        ['question' => 'Que se passe-t-il si on récolte un produit trop tard ?', 'options' => ['Il est plus savoureux', 'Il devient sur-mûr et souvent invendable', 'Il pèse plus lourd', 'Rien de particulier'], 'correct' => [1], 'explanation' => 'Récolté trop tard, le produit devient sur-mûr, s\'abîme et perd sa valeur marchande.'],
                        ['question' => 'Quels intérêts présente la transformation des produits (farine, jus, séchage) ?', 'options' => ['Ajouter de la valeur', 'Permettre de vendre hors saison', 'Augmenter les pertes', 'Réduire la durée de conservation'], 'correct' => [0, 1], 'explanation' => 'La transformation valorise la production et permet de vendre plus tard, à meilleur prix.'],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Agriculture durable',
                    'subtitle' => 'Produire aujourd\'hui sans détruire demain',
                    'xp_reward' => 185,
                    'content' => "🎯 **Objectif** : adopter des pratiques qui protègent le sol, l'eau et l'environnement sur le long terme.\n\n💡 L'agriculture durable cherche à produire de façon rentable tout en préservant les ressources pour les générations futures.\n\n**Les piliers de la durabilité :**\n• **Agroforesterie** : associer arbres et cultures (ombre, fertilité, bois, fruits).\n• **Couverture du sol / paillage** : protège contre l'érosion et l'évaporation.\n• **Compost et engrais verts** : réduire la dépendance aux intrants chimiques.\n• **Économie d'eau** : goutte-à-goutte, récupération d'eau de pluie.\n• **Biodiversité** : haies, cultures associées, auxiliaires.\n\n✅ **Cultures associées** : maïs + haricot grimpant + courge (les « trois sœurs ») — le maïs sert de tuteur, le haricot enrichit en azote, la courge couvre le sol.\n\n⚠️ Les pratiques destructrices à éviter : brûlis répété (détruit l'humus), labour excessif (érosion), surdose de chimie (pollution, sols morts), abattage total des arbres.\n\nUne agriculture durable est souvent **plus rentable à long terme** : moins d'intrants achetés, sols plus productifs et produits sains mieux valorisés.",
                    'questions' => [
                        ['question' => 'Qu\'est-ce que l\'agroforesterie ?', 'options' => ['Cultiver uniquement en serre', 'Associer arbres et cultures sur la même parcelle', 'Abattre tous les arbres', 'Cultiver hors sol'], 'correct' => [1], 'explanation' => 'L\'agroforesterie associe arbres et cultures, apportant ombre, fertilité, bois et fruits.'],
                        ['question' => 'Pourquoi le paillage est-il une pratique durable ?', 'options' => ['Il protège contre l\'érosion et garde l\'humidité', 'Il assèche le sol', 'Il détruit les microbes', 'Il augmente l\'évaporation'], 'correct' => [0], 'explanation' => 'Le paillage couvre le sol, limite l\'érosion et l\'évaporation, et nourrit le sol en se décomposant.'],
                        ['question' => 'Quelles pratiques nuisent à la durabilité du sol ?', 'options' => ['Le brûlis répété', 'Le compostage', 'La surdose d\'engrais chimiques', 'L\'agroforesterie'], 'correct' => [0, 2], 'explanation' => 'Le brûlis répété détruit l\'humus et la surdose de chimie pollue et appauvrit les sols.'],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Gérer son exploitation',
                    'subtitle' => 'Faire de sa ferme une entreprise rentable',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : piloter son exploitation comme une vraie entreprise, pas seulement comme une habitude.\n\n💡 Beaucoup d'agriculteurs travaillent dur mais ne savent pas s'ils gagnent réellement de l'argent. La gestion change tout.\n\n**Tenir des comptes simples :**\n• Noter toutes les **dépenses** (semences, engrais, main-d'œuvre, transport).\n• Noter toutes les **recettes** (ventes).\n• **Bénéfice = Recettes − Dépenses**.\n\n**Planifier la production :**\n• Choisir les cultures selon la demande et la saison.\n• Étaler les semis pour vendre régulièrement (trésorerie).\n• Anticiper les contre-saisons (prix plus élevés).\n\n✅ **Outils modernes au Cameroun :**\n• **Mobile money** (MTN MoMo, Orange Money) pour payer et encaisser sans cash.\n• **Coopératives / GIC** pour acheter les intrants groupés et mieux vendre.\n• Téléphone pour suivre les prix des marchés (Douala, Yaoundé).\n• Cadre **OHADA** pour formaliser une coopérative ou une petite entreprise agricole.\n\n⚠️ Vendre à un seul intermédiaire = prix imposé. Diversifier ses débouchés (marché, restaurants, transformateurs) protège tes marges.\n\n🏆 **Félicitations !** Tu maîtrises maintenant la chaîne complète : du sol vivant à la vente rentable, en passant par le semis, l'irrigation, la fertilisation, la protection des cultures et la durabilité. Tes débouchés : exploitant maraîcher, technicien agricole, conseiller agricole, gérant de coopérative, agripreneur en transformation, formateur de jeunes agriculteurs. L'agriculture moderne est un véritable métier d'avenir en Afrique — continue à apprendre, expérimente sur de petites surfaces et fais grandir ton projet. 🌱",
                    'questions' => [
                        ['question' => 'Comment calcule-t-on le bénéfice d\'une exploitation ?', 'options' => ['Recettes + Dépenses', 'Recettes − Dépenses', 'Dépenses − Recettes', 'Recettes × 2'], 'correct' => [1], 'explanation' => 'Le bénéfice se calcule en soustrayant les dépenses des recettes.'],
                        ['question' => 'Pourquoi étaler ses semis dans le temps ?', 'options' => ['Pour vendre régulièrement et lisser la trésorerie', 'Pour tout récolter le même jour', 'Pour épuiser le sol', 'Pour réduire les ventes'], 'correct' => [0], 'explanation' => 'Étaler les semis permet des ventes régulières et une meilleure gestion de la trésorerie.'],
                        ['question' => 'Quels outils aident à mieux gérer et vendre sa production au Cameroun ?', 'options' => ['Le mobile money (MoMo, Orange Money)', 'Les coopératives ou GIC', 'Vendre à un seul intermédiaire imposant son prix', 'Le suivi des prix des marchés'], 'correct' => [0, 1, 3], 'explanation' => 'Mobile money, coopératives et suivi des prix renforcent l\'agriculteur ; dépendre d\'un seul acheteur l\'affaiblit.'],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Agriculture créée (10 niveaux).');
    }
}
