<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Blockchain & Web3 — des registres distribués jusqu'aux smart contracts et à la DeFi.
 * Contenu rédigé (tips), QCM de validation par niveau.
 */
class BlockchainRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Perce dans la Blockchain & le Web3',
            'slug' => 'blockchain-web3',
            'domain' => 'blockchain',
            'description' => "Comprends la blockchain de A à Z : registres distribués, cryptographie, Bitcoin, Ethereum, smart contracts, wallets, tokens, DeFi et sécurité. Du concept de base jusqu'à l'écriture de ton premier contrat en Solidity.",
            'objectives' => "Comprendre ce qu'est un registre distribué\nMaîtriser les bases de la cryptographie (hash, signatures)\nSaisir le fonctionnement de Bitcoin et Ethereum\nGérer un wallet en sécurité\nDistinguer tokens fongibles et NFT\nÉcrire un smart contract simple en Solidity\nComprendre la DeFi et les mécanismes de consensus\nÉviter les vulnérabilités classiques des contrats",
            'icon' => '⛓️',
            'color' => '#7C3AED',
            'difficulty' => 'intermediate',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 1,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Qu\'est-ce qu\'une blockchain ?',
                    'subtitle' => 'Le registre distribué',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : comprendre ce qu'est une blockchain.\n\n💡 Une **blockchain** est un **registre** (une liste d'enregistrements) **distribué** : il n'est pas stocké sur un seul serveur, mais **répliqué** sur des milliers d'ordinateurs (les **nœuds**) à travers le monde. Personne ne le contrôle seul : c'est **décentralisé**.\n\n✅ Les données sont regroupées dans des **blocs**. Chaque bloc contient un ensemble de transactions, un horodatage, et surtout l'**empreinte (hash) du bloc précédent**. C'est ce lien qui forme une **chaîne** de blocs.\n\n✅ Comme chaque bloc référence le précédent, modifier un ancien bloc casserait toute la chaîne suivante. C'est ce qui rend la blockchain **immuable** : une fois une donnée inscrite et confirmée, il est pratiquement impossible de la modifier sans que tout le réseau le détecte.\n\n💡 On parle souvent de blockchain « **publique** » (ouverte à tous, comme Bitcoin ou Ethereum) par opposition à une base de données classique gérée par une seule entreprise. La grande différence : la **confiance** ne repose pas sur un intermédiaire, mais sur les mathématiques et le réseau.",
                    'questions' => [
                        [
                            'question' => 'Qu\'est-ce qui relie un bloc au précédent dans une blockchain ?',
                            'options' => ['Son numéro de série', 'Le hash du bloc précédent', 'L\'adresse IP du nœud', 'La date de création uniquement'],
                            'correct' => [1],
                            'explanation' => 'Chaque bloc contient le hash du bloc précédent, ce qui forme la chaîne et la rend infalsifiable.',
                        ],
                        [
                            'question' => 'Pourquoi dit-on qu\'une blockchain est immuable ?',
                            'options' => ['Parce qu\'elle est chiffrée', 'Parce que modifier un bloc casserait tous les suivants et serait détecté', 'Parce qu\'un administrateur la verrouille', 'Parce qu\'elle est stockée hors ligne'],
                            'correct' => [1],
                            'explanation' => 'Le chaînage par hash rend toute modification rétroactive visible et rejetée par le réseau.',
                        ],
                        [
                            'question' => 'Quelles caractéristiques décrivent une blockchain publique ? (plusieurs réponses)',
                            'options' => ['Registre répliqué sur de nombreux nœuds', 'Contrôlée par une seule entreprise', 'Décentralisée', 'Données regroupées en blocs chaînés'],
                            'correct' => [0, 2, 3],
                            'explanation' => 'Une blockchain publique est distribuée, décentralisée et organisée en blocs chaînés ; elle n\'est justement PAS contrôlée par une seule entité.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — Cryptographie de base',
                    'subtitle' => 'Hash, clés et signatures',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : comprendre les briques cryptographiques de la blockchain.\n\n💡 Une **fonction de hachage** (hash) transforme n'importe quelle donnée en une empreinte de taille fixe (ex. SHA-256 produit 256 bits). Elle a trois propriétés clés :\n• **déterministe** : la même entrée donne toujours le même hash ;\n• **à sens unique** : impossible de retrouver l'entrée à partir du hash ;\n• **effet avalanche** : changer un seul caractère change complètement le résultat.\n\n```\nSHA-256(\"chat\")  → 5e3...a1\nSHA-256(\"chat.\") → 9f0...7c   (totalement différent)\n```\n\n✅ La **cryptographie asymétrique** utilise une paire de clés : une **clé privée** (secrète, à protéger absolument) et une **clé publique** (partageable, dérivée de la privée). On ne peut PAS retrouver la clé privée depuis la clé publique.\n\n✅ Une **signature numérique** prouve qu'un message vient bien du détenteur de la clé privée : on signe avec la clé privée, et n'importe qui peut **vérifier** la signature avec la clé publique correspondante. C'est ainsi qu'on autorise une transaction sans jamais révéler sa clé privée.\n\n💡 Ton **adresse** blockchain (ex. `0x71C7...`) est généralement dérivée de ta clé publique. Elle est sûre à partager : c'est comme un IBAN, pas un mot de passe.",
                    'questions' => [
                        [
                            'question' => 'Quelle propriété décrit le mieux une fonction de hachage cryptographique ?',
                            'options' => ['Réversible : on peut retrouver l\'entrée', 'À sens unique et déterministe', 'Elle chiffre les données pour les déchiffrer ensuite', 'Elle change à chaque exécution pour la même entrée'],
                            'correct' => [1],
                            'explanation' => 'Un hash est déterministe (même entrée = même sortie) et à sens unique (irréversible).',
                        ],
                        [
                            'question' => 'Avec quelle clé signe-t-on une transaction ?',
                            'options' => ['La clé publique', 'La clé privée', 'L\'adresse du destinataire', 'Le hash du bloc'],
                            'correct' => [1],
                            'explanation' => 'On signe avec sa clé privée ; la signature se vérifie ensuite avec la clé publique correspondante.',
                        ],
                        [
                            'question' => 'Pourquoi est-il sûr de partager sa clé publique ou son adresse ?',
                            'options' => ['Parce qu\'elles sont chiffrées', 'Parce qu\'on ne peut pas en déduire la clé privée', 'Parce qu\'elles changent chaque jour', 'Parce qu\'elles sont stockées hors ligne'],
                            'correct' => [1],
                            'explanation' => 'La clé privée ne peut pas être calculée à partir de la clé publique : partager l\'adresse ne compromet rien.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — Comment fonctionne Bitcoin',
                    'subtitle' => 'Transactions, minage, preuve de travail',
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : comprendre le fonctionnement du réseau Bitcoin.\n\n💡 Bitcoin est la première blockchain (2009). Son but : permettre des paiements **pair-à-pair** sans banque. Le réseau suit un modèle dit **UTXO** (Unspent Transaction Output) : tu ne possèdes pas un « solde » au sens classique, mais un ensemble de « sorties non dépensées » que tu peux utiliser comme entrées de nouvelles transactions.\n\n✅ Une **transaction** est diffusée au réseau, signée par la clé privée de l'émetteur. Les **mineurs** la collectent dans un bloc candidat avec d'autres transactions en attente (le **mempool**).\n\n✅ Le **minage** consiste à résoudre un casse-tête mathématique : trouver un nombre (le **nonce**) tel que le hash du bloc commence par un certain nombre de zéros. C'est la **preuve de travail** (Proof of Work). Trouver ce nonce demande énormément de calculs (essais successifs), mais **vérifier** la solution est instantané.\n\n✅ Le premier mineur qui trouve la solution diffuse son bloc, reçoit une **récompense** (de nouveaux bitcoins + les frais de transaction) et le réseau passe au bloc suivant. La difficulté s'ajuste automatiquement pour qu'un bloc soit trouvé en moyenne toutes les **10 minutes**.\n\n💡 L'offre de Bitcoin est **plafonnée à 21 millions** d'unités, et la récompense de minage est divisée par deux environ tous les 4 ans (le **halving**).",
                    'questions' => [
                        [
                            'question' => 'En quoi consiste la preuve de travail (Proof of Work) ?',
                            'options' => ['Voter pour valider un bloc', 'Trouver un nonce produisant un hash valide via des calculs intensifs', 'Bloquer ses bitcoins en garantie', 'Signer le bloc avec sa clé privée'],
                            'correct' => [1],
                            'explanation' => 'Les mineurs cherchent un nonce qui rend le hash du bloc conforme à la difficulté : coûteux à trouver, instantané à vérifier.',
                        ],
                        [
                            'question' => 'Quelle est l\'offre maximale de bitcoins ?',
                            'options' => ['1 milliard', '21 millions', 'Illimitée', '100 millions'],
                            'correct' => [1],
                            'explanation' => 'L\'offre de Bitcoin est plafonnée à 21 millions d\'unités.',
                        ],
                        [
                            'question' => 'Que reçoit le mineur qui trouve un bloc valide ? (plusieurs réponses)',
                            'options' => ['De nouveaux bitcoins émis (récompense de bloc)', 'Les frais des transactions incluses', 'La clé privée des utilisateurs', 'Le droit de modifier les anciens blocs'],
                            'correct' => [0, 1],
                            'explanation' => 'Le mineur gagnant reçoit la récompense de bloc ET les frais des transactions, mais aucun pouvoir de modifier le passé ni d\'accéder aux clés.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — Ethereum & smart contracts',
                    'subtitle' => 'Machine virtuelle et gas',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : comprendre ce qui distingue Ethereum de Bitcoin.\n\n💡 Là où Bitcoin sert surtout à transférer de la valeur, **Ethereum** (2015) ajoute la possibilité d'exécuter du **code** sur la blockchain : les **smart contracts** (contrats intelligents). Un smart contract est un programme déployé à une adresse, qui s'exécute automatiquement selon des règles prédéfinies, sans intermédiaire.\n\n✅ Ce code s'exécute sur l'**EVM** (Ethereum Virtual Machine), une machine virtuelle répliquée sur tous les nœuds. Chaque nœud exécute les mêmes instructions et obtient le même résultat : c'est ce qui garantit le consensus sur l'état du contrat.\n\n✅ Exécuter du code coûte des ressources. Pour éviter les abus (boucles infinies, spam), chaque opération a un coût en **gas**. L'utilisateur paie ce gas en ETH. Le coût total = `quantité de gas × prix du gas`. Si le gas fourni est insuffisant, la transaction échoue (mais le gas consommé est tout de même perdu) — on parle de transaction « **out of gas** ».\n\n💡 Ethereum a sa propre cryptomonnaie, l'**ETH**, utilisée à la fois comme actif et pour payer le gas. Depuis 2022 (« The Merge »), Ethereum n'utilise plus la preuve de travail mais la **preuve d'enjeu** (Proof of Stake).",
                    'questions' => [
                        [
                            'question' => 'Qu\'est-ce qu\'un smart contract ?',
                            'options' => ['Un contrat papier scanné sur la blockchain', 'Un programme déployé sur la blockchain qui s\'exécute automatiquement', 'Un accord juridique signé par un avocat', 'Une transaction Bitcoin classique'],
                            'correct' => [1],
                            'explanation' => 'Un smart contract est du code auto-exécutable déployé à une adresse sur la blockchain.',
                        ],
                        [
                            'question' => 'À quoi sert le gas sur Ethereum ?',
                            'options' => ['À chiffrer les transactions', 'À payer le coût de calcul de chaque opération', 'À stocker les clés privées', 'À accélérer le minage'],
                            'correct' => [1],
                            'explanation' => 'Le gas mesure et facture le coût computationnel des opérations, évitant les abus comme les boucles infinies.',
                        ],
                        [
                            'question' => 'Où s\'exécute le code d\'un smart contract Ethereum ?',
                            'options' => ['Sur un serveur central d\'Ethereum', 'Sur l\'EVM, répliquée sur tous les nœuds', 'Dans le navigateur de l\'utilisateur', 'Sur la blockchain Bitcoin'],
                            'correct' => [1],
                            'explanation' => 'Le code tourne sur l\'Ethereum Virtual Machine (EVM), exécutée à l\'identique par chaque nœud du réseau.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — Les wallets & la sécurité',
                    'subtitle' => 'Clés, seed phrase, custody',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : comprendre comment fonctionne un wallet et comment le sécuriser.\n\n💡 Un **wallet** (portefeuille) ne « contient » pas vraiment tes cryptos : celles-ci vivent sur la blockchain. Le wallet stocke et gère tes **clés privées**, qui te permettent de prouver que tu es le propriétaire et donc de signer des transactions. Perdre ses clés = perdre l'accès aux fonds. **Pas de « mot de passe oublié »** sur une blockchain décentralisée.\n\n✅ La **seed phrase** (phrase de récupération, souvent 12 ou 24 mots) est une représentation lisible à partir de laquelle toutes tes clés privées sont dérivées. Quiconque la connaît contrôle TOUS tes fonds. Il faut la noter **hors ligne**, jamais en photo, jamais dans un mail ou un cloud.\n\n```\nExemple (NE JAMAIS réutiliser celle-ci) :\nribbon laptop kitchen ... fox\n```\n\n✅ On distingue :\n• **non-custodial** : tu détiens toi-même tes clés (ex. MetaMask, Ledger). « Not your keys, not your coins » → tu es seul responsable.\n• **custodial** : un tiers (ex. une plateforme d'échange) détient les clés pour toi. Plus simple, mais tu dépends de sa solvabilité et de sa sécurité.\n\n✅ Un **hardware wallet** (portefeuille matériel, ex. Ledger) garde la clé privée dans un appareil hors ligne : elle ne touche jamais Internet, ce qui protège contre le vol par malware.\n\n⚠️ Règle d'or : **personne** ne doit jamais te demander ta seed phrase. Toute demande de ce type est une **arnaque** (phishing).",
                    'questions' => [
                        [
                            'question' => 'Que stocke réellement un wallet de cryptomonnaie ?',
                            'options' => ['Les pièces elles-mêmes', 'Les clés privées permettant de signer les transactions', 'Une copie de la blockchain', 'Le solde de la banque'],
                            'correct' => [1],
                            'explanation' => 'Les fonds restent sur la blockchain ; le wallet gère les clés privées qui prouvent la propriété.',
                        ],
                        [
                            'question' => 'Quelle est la différence entre un wallet custodial et non-custodial ?',
                            'options' => ['Le custodial est plus rapide', 'Avec le non-custodial, tu détiens tes propres clés ; avec le custodial, un tiers les détient', 'Le non-custodial ne fonctionne qu\'avec Bitcoin', 'Il n\'y a aucune différence'],
                            'correct' => [1],
                            'explanation' => 'Non-custodial = tu contrôles tes clés ; custodial = un tiers (ex. une plateforme) les garde pour toi.',
                        ],
                        [
                            'question' => 'Quelles affirmations sur la seed phrase sont correctes ? (plusieurs réponses)',
                            'options' => ['Elle permet de récupérer toutes tes clés privées', 'On peut la partager au support technique en cas de problème', 'Quiconque la connaît contrôle tes fonds', 'Il faut la conserver hors ligne'],
                            'correct' => [0, 2, 3],
                            'explanation' => 'La seed phrase dérive toutes tes clés et donne un contrôle total ; on ne la partage JAMAIS, même au « support » — c\'est toujours du phishing.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — Les tokens (ERC-20, NFT)',
                    'subtitle' => 'Fongibles et non fongibles',
                    'xp_reward' => 170,
                    'content' => "🎯 **Objectif** : comprendre les tokens et la différence fongible / non fongible.\n\n💡 Un **token** est un actif créé et géré par un smart contract sur une blockchain (souvent Ethereum). Contrairement à l'ETH (la monnaie native), un token est défini par le contrat qui le gouverne. Les contrats suivent des **standards** pour être interopérables avec les wallets et les applications.\n\n✅ Le standard **ERC-20** définit les tokens **fongibles** : chaque unité est identique et interchangeable, exactement comme des billets de banque. 1 token = 1 token. Ils servent de monnaies, de jetons de gouvernance, de stablecoins (ex. USDC, DAI), etc. Le contrat ERC-20 expose des fonctions standard comme `transfer`, `balanceOf`, `approve`.\n\n✅ Le standard **ERC-721** définit les **NFT** (Non-Fungible Tokens) : chaque token est **unique** et non interchangeable. Chacun a un identifiant distinct (`tokenId`) et peut représenter une œuvre d'art, un objet de jeu, un titre de propriété, etc. Deux NFT d'une même collection ne sont pas équivalents.\n\n💡 Résumé :\n• **Fongible** (ERC-20) → interchangeable, divisible → monnaies, stablecoins.\n• **Non fongible** (ERC-721) → unique, indivisible → art, collectibles, certificats.\n\n💡 Il existe aussi l'ERC-1155, un standard « multi-token » capable de gérer à la fois des actifs fongibles et non fongibles dans un même contrat — très utilisé dans les jeux.",
                    'questions' => [
                        [
                            'question' => 'Quel standard définit les tokens fongibles sur Ethereum ?',
                            'options' => ['ERC-721', 'ERC-20', 'BIP-39', 'SHA-256'],
                            'correct' => [1],
                            'explanation' => 'ERC-20 est le standard des tokens fongibles ; ERC-721 est celui des NFT.',
                        ],
                        [
                            'question' => 'Qu\'est-ce qu\'un token « fongible » ?',
                            'options' => ['Un token unique et non échangeable', 'Un token dont chaque unité est identique et interchangeable', 'Un token qui ne peut pas être transféré', 'Un token réservé aux NFT'],
                            'correct' => [1],
                            'explanation' => 'Fongible = interchangeable : chaque unité vaut exactement une autre (comme des billets).',
                        ],
                        [
                            'question' => 'Quelles affirmations sur les NFT (ERC-721) sont vraies ? (plusieurs réponses)',
                            'options' => ['Chaque token a un identifiant unique', 'Deux NFT d\'une collection sont interchangeables', 'Ils peuvent représenter de l\'art ou un objet de jeu', 'Ils sont non fongibles'],
                            'correct' => [0, 2, 3],
                            'explanation' => 'Les NFT ont un tokenId unique, sont non fongibles et peuvent représenter divers actifs uniques ; ils ne sont justement PAS interchangeables.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — Écrire un smart contract simple',
                    'subtitle' => 'Solidity : structure, état, fonctions',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : lire et écrire un smart contract basique en Solidity.\n\n💡 **Solidity** est le langage le plus utilisé pour écrire des smart contracts Ethereum. Sa syntaxe ressemble à JavaScript/C++. Un fichier commence par la version du langage et la licence :\n\n```solidity\n// SPDX-License-Identifier: MIT\npragma solidity ^0.8.0;\n\ncontract Compteur {\n    uint256 public valeur;   // variable d'etat (stockee sur la blockchain)\n\n    function incrementer() public {\n        valeur = valeur + 1;\n    }\n\n    function lire() public view returns (uint256) {\n        return valeur;\n    }\n}\n```\n\n✅ Quelques notions clés :\n• Une **variable d'état** (`valeur`) est stockée durablement sur la blockchain. La modifier coûte du gas.\n• Le mot-clé **`public`** génère automatiquement une fonction de lecture (getter).\n• **`view`** indique qu'une fonction **lit** l'état sans le modifier : l'appeler en lecture seule est **gratuit** (pas de gas), car aucune transaction n'est écrite.\n• Une fonction qui **modifie** l'état (comme `incrementer`) nécessite une transaction et coûte du gas.\n\n✅ Types courants : `uint256` (entier non signé), `bool`, `address` (une adresse Ethereum), `string`, `mapping` (table clé→valeur, ex. `mapping(address => uint256) soldes;`).\n\n💡 La variable globale **`msg.sender`** contient l'adresse de celui qui appelle la fonction — essentielle pour gérer les permissions (ex. « seul le propriétaire peut… »).",
                    'questions' => [
                        [
                            'question' => 'Que signifie le mot-clé `view` sur une fonction Solidity ?',
                            'options' => ['Elle modifie l\'état', 'Elle lit l\'état sans le modifier (lecture gratuite)', 'Elle est privée', 'Elle supprime le contrat'],
                            'correct' => [1],
                            'explanation' => 'Une fonction `view` lit l\'état sans le modifier ; l\'appeler en lecture seule ne coûte pas de gas.',
                        ],
                        [
                            'question' => 'Que contient la variable globale `msg.sender` ?',
                            'options' => ['Le solde du contrat', 'L\'adresse de celui qui appelle la fonction', 'Le hash du bloc courant', 'Le prix du gas'],
                            'correct' => [1],
                            'explanation' => '`msg.sender` est l\'adresse de l\'appelant, très utile pour gérer les permissions.',
                        ],
                        [
                            'question' => 'Pourquoi modifier une variable d\'état coûte-t-il du gas ?',
                            'options' => ['Parce que c\'est gratuit en réalité', 'Parce que cela écrit durablement sur la blockchain via une transaction', 'Parce que Solidity est lent', 'Parce que cela supprime des données'],
                            'correct' => [1],
                            'explanation' => 'Écrire dans l\'état modifie la blockchain : cela nécessite une transaction et consomme du gas.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — La DeFi (finance décentralisée)',
                    'subtitle' => 'Échanges, prêts, liquidité',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : comprendre les grands concepts de la finance décentralisée.\n\n💡 La **DeFi** (Decentralized Finance) recrée des services financiers (échange, prêt, épargne) à l'aide de smart contracts, sans banque ni intermédiaire. Tout est **programmé** dans des contrats publics et auditables, et accessible à quiconque possède un wallet.\n\n✅ Les **DEX** (exchanges décentralisés, ex. Uniswap) permettent d'échanger des tokens sans carnet d'ordres classique. Beaucoup utilisent un modèle d'**AMM** (Automated Market Maker) : le prix est déterminé automatiquement par une formule mathématique selon les quantités de chaque token dans un **pool de liquidité**. La formule la plus connue est `x * y = k` (produit constant).\n\n✅ Un **pool de liquidité** est un smart contract où des utilisateurs (les **fournisseurs de liquidité**, ou LP) déposent une paire de tokens. En échange, ils touchent une part des **frais** payés par ceux qui échangent. Ils s'exposent toutefois à la **perte impermanente** (impermanent loss) quand les prix divergent.\n\n✅ Les **protocoles de prêt** (ex. Aave, Compound) permettent de prêter ses tokens pour gagner des intérêts, ou d'emprunter en déposant une **garantie** (collatéral). Comme il n'y a pas de vérification d'identité, les prêts sont **sur-collatéralisés** : on dépose plus que ce qu'on emprunte. Si la garantie perd trop de valeur, elle est **liquidée** automatiquement.\n\n⚠️ La DeFi offre de fortes opportunités mais aussi de **vrais risques** : bugs de smart contracts, volatilité, liquidations, arnaques (« rug pulls »). Le code étant souverain, une erreur peut être irréversible.",
                    'questions' => [
                        [
                            'question' => 'Sur un AMM comme Uniswap, comment le prix d\'un échange est-il fixé ?',
                            'options' => ['Par un carnet d\'ordres centralisé', 'Par une formule mathématique selon les quantités du pool', 'Par un employé de la plateforme', 'Par la banque centrale'],
                            'correct' => [1],
                            'explanation' => 'Un AMM utilise une formule (souvent x*y=k) basée sur les réserves du pool pour calculer le prix automatiquement.',
                        ],
                        [
                            'question' => 'Pourquoi les prêts DeFi sont-ils généralement sur-collatéralisés ?',
                            'options' => ['Pour augmenter les frais', 'Parce qu\'il n\'y a pas de vérification d\'identité ni de recouvrement classique', 'Parce que c\'est une obligation légale', 'Pour ralentir le réseau'],
                            'correct' => [1],
                            'explanation' => 'Sans identité ni recours juridique, le protocole exige un collatéral supérieur au prêt pour se protéger des défauts.',
                        ],
                        [
                            'question' => 'Quels risques sont propres à la DeFi ? (plusieurs réponses)',
                            'options' => ['Bugs dans les smart contracts', 'Perte impermanente pour les fournisseurs de liquidité', 'Garantie totale par l\'État', 'Liquidation automatique du collatéral'],
                            'correct' => [0, 1, 3],
                            'explanation' => 'Bugs, perte impermanente et liquidations sont des risques réels ; il n\'existe AUCUNE garantie étatique en DeFi.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Les mécanismes de consensus',
                    'subtitle' => 'Preuve de travail vs preuve d\'enjeu',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : comprendre comment un réseau décentralisé se met d'accord.\n\n💡 Sans autorité centrale, comment des milliers de nœuds s'accordent-ils sur l'ordre des transactions et l'état du registre ? Grâce à un **mécanisme de consensus**. Il doit empêcher la **double dépense** (dépenser deux fois la même unité) et résister aux acteurs malveillants.\n\n✅ La **preuve de travail** (Proof of Work, PoW), utilisée par Bitcoin : les mineurs dépensent de la puissance de calcul (et donc de l'électricité) pour résoudre un casse-tête. Le coût matériel et énergétique rend une attaque très chère. Inconvénient : **forte consommation d'énergie**.\n\n✅ La **preuve d'enjeu** (Proof of Stake, PoS), utilisée par Ethereum depuis 2022 : au lieu de calculer, les **validateurs** bloquent (« stakent ») une quantité de cryptomonnaie en garantie. Le protocole choisit qui propose le prochain bloc, en partie au hasard, pondéré par la mise. Un validateur malhonnête peut perdre sa mise (**slashing**). Avantage : consommation d'énergie **drastiquement réduite** (~99 % de moins).\n\n💡 Comparaison rapide :\n• **PoW** : sécurité par le travail/énergie ; matériel spécialisé (ASIC) ; très énergivore.\n• **PoS** : sécurité par le capital mis en jeu ; pas de course au calcul ; bien plus économe en énergie.\n\n💡 Un principe commun : attaquer le réseau (ex. la fameuse « attaque des 51 % ») exige de contrôler une majorité de la puissance (PoW) ou de la mise (PoS), ce qui est extrêmement coûteux et donc dissuasif.",
                    'questions' => [
                        [
                            'question' => 'Dans la preuve d\'enjeu (PoS), comment un validateur est-il sélectionné ?',
                            'options' => ['Selon sa puissance de calcul', 'En partie au hasard, pondéré par la quantité de cryptomonnaie mise en jeu', 'Par un vote des utilisateurs', 'Par ordre alphabétique'],
                            'correct' => [1],
                            'explanation' => 'En PoS, la sélection dépend de la mise (le stake) bloquée par le validateur, avec une part d\'aléa.',
                        ],
                        [
                            'question' => 'Quel est le principal avantage de la preuve d\'enjeu sur la preuve de travail ?',
                            'options' => ['Elle est totalement gratuite', 'Elle consomme beaucoup moins d\'énergie', 'Elle supprime les frais de transaction', 'Elle rend les transactions réversibles'],
                            'correct' => [1],
                            'explanation' => 'Le PoS ne nécessite pas de calcul intensif : il réduit la consommation d\'énergie d\'environ 99 %.',
                        ],
                        [
                            'question' => 'Que cherche à empêcher un mécanisme de consensus ?',
                            'options' => ['Le chiffrement des données', 'La double dépense et les manipulations du registre', 'L\'usage des wallets', 'La création de tokens'],
                            'correct' => [1],
                            'explanation' => 'Le consensus garantit un ordre unique des transactions et empêche la double dépense malgré l\'absence d\'autorité centrale.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Sécurité des smart contracts',
                    'subtitle' => 'Reentrancy, overflow et bonnes pratiques',
                    'xp_reward' => 230,
                    'content' => "🎯 **Objectif** : connaître les vulnérabilités classiques des smart contracts.\n\n⚠️ Un smart contract est **immuable** une fois déployé et gère souvent de l'argent réel. Un bug peut donc coûter des millions et être **irréversible**. C'est pourquoi l'audit de sécurité est crucial.\n\n✅ **Reentrancy (réentrance)** : un contrat appelle un contrat externe AVANT de mettre à jour son propre état. Le contrat appelé peut alors rappeler la fonction d'origine en boucle et vider les fonds. C'est la faille à l'origine du piratage « The DAO » (2016).\n\n```solidity\n// VULNERABLE : envoi AVANT mise a jour\nfunction retirer() public {\n    uint montant = soldes[msg.sender];\n    (bool ok, ) = msg.sender.call{value: montant}(\"\");\n    soldes[msg.sender] = 0;   // trop tard !\n}\n```\nLa parade : appliquer le motif **« checks-effects-interactions »** — vérifier, puis **mettre à jour l'état**, puis seulement interagir avec l'extérieur (mettre `soldes[msg.sender] = 0;` AVANT l'appel externe).\n\n✅ **Overflow / underflow** : un entier qui dépasse sa capacité « repart » à zéro (ou inversement), faussant les calculs. Depuis Solidity 0.8, ces dépassements **revert** automatiquement ; avant, on utilisait la bibliothèque **SafeMath**.\n\n✅ Autres pièges classiques :\n• **Contrôle d'accès** manquant (n'importe qui peut appeler une fonction sensible).\n• **Dépendance au timestamp** ou à des sources manipulables (oracles).\n• Mauvaise gestion des **erreurs** de transferts.\n\n💡 Bonnes pratiques : réutiliser des bibliothèques **éprouvées** (ex. OpenZeppelin), écrire des tests, faire **auditer** le code, et limiter les montants exposés. En sécurité blockchain, la prudence n'est jamais excessive. 🏆",
                    'questions' => [
                        [
                            'question' => 'En quoi consiste une attaque par réentrance (reentrancy) ?',
                            'options' => ['Deviner la clé privée', 'Rappeler une fonction en boucle car l\'état est mis à jour trop tard', 'Saturer le réseau de transactions', 'Modifier un bloc déjà miné'],
                            'correct' => [1],
                            'explanation' => 'La réentrance exploite un appel externe effectué AVANT la mise à jour de l\'état, permettant de rappeler la fonction et de vider les fonds.',
                        ],
                        [
                            'question' => 'Quel motif protège contre la réentrance ?',
                            'options' => ['Interactions-effects-checks', 'Checks-effects-interactions (mettre à jour l\'état avant l\'appel externe)', 'Chiffrer la fonction', 'Augmenter le gas'],
                            'correct' => [1],
                            'explanation' => 'Le motif checks-effects-interactions met à jour l\'état AVANT toute interaction externe, neutralisant la réentrance.',
                        ],
                        [
                            'question' => 'Quelles affirmations sur la sécurité des smart contracts sont correctes ? (plusieurs réponses)',
                            'options' => ['Un contrat déployé est généralement immuable, donc un bug peut être irréversible', 'Depuis Solidity 0.8, les overflows revert automatiquement', 'Réutiliser des bibliothèques auditées (ex. OpenZeppelin) est recommandé', 'Le contrôle d\'accès est inutile sur la blockchain'],
                            'correct' => [0, 1, 2],
                            'explanation' => 'Immuabilité, protection native contre l\'overflow (≥0.8) et usage de bibliothèques éprouvées sont vrais ; le contrôle d\'accès est au contraire essentiel.',
                        ],
                    ],
                ],
                [
                    'title' => 'Niveau 11 — Limites & cas d\'usage réels',
                    'subtitle' => 'Scalabilité, énergie, applications',
                    'xp_reward' => 230,
                    'content' => "🎯 **Objectif** : prendre du recul sur les limites et les usages concrets de la blockchain.\n\n💡 La blockchain n'est pas magique : elle a de vraies **limites**.\n\n✅ **Scalabilité** : une blockchain décentralisée traite peu de transactions par seconde (Bitcoin ~7 TPS, Ethereum ~15-30 TPS) face à des milliers pour une carte bancaire. C'est le **trilemme** de la blockchain : il est difficile d'optimiser en même temps **décentralisation, sécurité et scalabilité**. Les solutions de **couche 2** (Layer 2, ex. rollups comme Arbitrum, Optimism) traitent les transactions hors de la chaîne principale puis y inscrivent un résumé, augmentant le débit.\n\n✅ **Énergie** : la preuve de travail (Bitcoin) reste très énergivore. Le passage d'Ethereum à la preuve d'enjeu a fortement réduit cet impact pour ce réseau.\n\n✅ **Autres limites** : volatilité des prix, complexité pour le grand public, cadre **réglementaire** encore mouvant, et le fait que les données « on-chain » sont **publiques** (peu de confidentialité par défaut).\n\n✅ Des **cas d'usage réels** émergent :\n• **Paiements** transfrontaliers et stablecoins ;\n• **Traçabilité** des chaînes d'approvisionnement (provenance d'un produit) ;\n• **Tokenisation** d'actifs (immobilier, titres) ;\n• **Identité** numérique et certificats (diplômes vérifiables) ;\n• **DeFi**, jeux et collectibles (NFT).\n\n💡 La bonne question n'est pas « peut-on utiliser une blockchain ? » mais « **en a-t-on vraiment besoin** ? ». Si une base de données classique gérée par une entité de confiance suffit, c'est souvent plus simple et moins coûteux. La blockchain brille quand on a besoin de **décentralisation, d'absence d'intermédiaire de confiance et de transparence vérifiable**.\n\n🏆 Félicitations ! Tu as parcouru l'ensemble des fondamentaux de la blockchain et du Web3. Continue à expérimenter sur des testnets, sans risquer de vrais fonds.",
                    'questions' => [
                        [
                            'question' => 'En quoi consiste le « trilemme » de la blockchain ?',
                            'options' => ['Choisir entre Bitcoin, Ethereum et Solana', 'La difficulté d\'optimiser à la fois décentralisation, sécurité et scalabilité', 'Le choix entre 3 wallets différents', 'Les trois types de tokens'],
                            'correct' => [1],
                            'explanation' => 'Le trilemme exprime qu\'il est ardu de maximiser simultanément décentralisation, sécurité et scalabilité.',
                        ],
                        [
                            'question' => 'À quoi servent les solutions de couche 2 (Layer 2) ?',
                            'options' => ['À chiffrer les wallets', 'À augmenter le débit en traitant des transactions hors de la chaîne principale', 'À remplacer la clé privée', 'À créer des NFT uniquement'],
                            'correct' => [1],
                            'explanation' => 'Les Layer 2 (ex. rollups) exécutent les transactions hors-chaîne et n\'inscrivent qu\'un résumé sur la couche 1, améliorant la scalabilité.',
                        ],
                        [
                            'question' => 'Quels sont des cas d\'usage pertinents de la blockchain ? (plusieurs réponses)',
                            'options' => ['Traçabilité d\'une chaîne d\'approvisionnement', 'Paiements transfrontaliers et stablecoins', 'Stocker des fichiers volumineux à bas coût', 'Certificats et diplômes vérifiables'],
                            'correct' => [0, 1, 3],
                            'explanation' => 'Traçabilité, paiements et certificats vérifiables sont de bons cas d\'usage ; stocker de gros fichiers on-chain est au contraire coûteux et inadapté.',
                        ],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Blockchain créée (11 niveaux).');
    }
}
