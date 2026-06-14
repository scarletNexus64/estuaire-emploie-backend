<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Systèmes Embarqués — du microcontrôleur au firmware optimisé et débogué.
 */
class InformatiqueEmbarqueeRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Deviens Ingénieur en Systèmes Embarqués',
            'slug' => 'informatique-embarquee',
            'domain' => 'ingenierie',
            'description' => "Maîtrise la programmation des microcontrôleurs : du clignotement d'une LED jusqu'au firmware temps réel basse consommation. Une compétence très recherchée pour l'IoT, l'agritech et l'électronique au Cameroun et en Afrique.",
            'objectives' => "Comprendre l'architecture d'un microcontrôleur\nÉcrire du C embarqué propre et portable\nPiloter les GPIO et lire des capteurs\nMaîtriser interruptions et temps réel (RTOS)\nUtiliser les bus I2C, SPI et UART\nOptimiser mémoire, consommation et déboguer un firmware",
            'icon' => '🔌',
            'color' => '#0EA5E9',
            'difficulty' => 'intermediate',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 20,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Découvrir le microcontrôleur',
                    'subtitle' => 'Architecture, CPU, mémoire et périphériques',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : comprendre ce qu'est un microcontrôleur (MCU) et en quoi il diffère d'un microprocesseur.\n\n💡 Un MCU (ex : STM32, ESP32, AVR de l'Arduino Uno) intègre sur une seule puce :\n• un **CPU** (le cœur de calcul, ex : ARM Cortex-M)\n• de la **Flash** (stocke le programme, non volatile)\n• de la **RAM** (variables, volatile)\n• des **périphériques** : GPIO, timers, UART, ADC...\n\n✅ Contrairement à un PC, il n'y a souvent **pas de système d'exploitation** : ton code tourne « sur le métal » (bare-metal). Les ressources sont limitées (quelques Ko de RAM, MHz au lieu de GHz).\n\n⚠️ On parle d'**architecture Harvard** quand la mémoire programme et la mémoire données sont séparées (cas de l'AVR), ce qui change l'accès aux données en Flash.\n\nExemple concret : une station météo dans une ferme près de Yaoundé utilise un ESP32 pour lire la température et l'humidité, puis envoyer les données par WiFi.",
                    'questions' => [
                        ['question' => 'Quelle mémoire conserve le programme même hors tension ?', 'options' => ['La RAM', 'La Flash', 'Les registres CPU', 'Le cache L2'], 'correct' => [1], 'explanation' => 'La Flash est non volatile : elle garde le firmware après extinction, contrairement à la RAM.'],
                        ['question' => 'Qu\'intègre un microcontrôleur sur une seule puce ?', 'options' => ['Uniquement un CPU', 'CPU, mémoire et périphériques', 'Uniquement de la mémoire', 'Un écran et un clavier'], 'correct' => [1], 'explanation' => 'Le MCU réunit CPU, mémoires et périphériques d\'E/S sur une seule puce.'],
                        ['question' => 'Pourquoi parle-t-on de programmation « bare-metal » ?', 'options' => ['Le code est écrit en métal', 'Il n\'y a souvent pas de système d\'exploitation', 'Le MCU est en aluminium', 'Le code tourne dans le cloud'], 'correct' => [1], 'explanation' => 'En bare-metal, le firmware s\'exécute directement sur le matériel sans OS intermédiaire.'],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — Le C embarqué',
                    'subtitle' => 'Types, registres et accès matériel',
                    'xp_reward' => 110,
                    'content' => "🎯 **Objectif** : écrire du C adapté aux contraintes du matériel.\n\n💡 Le langage **C** domine l'embarqué : proche du matériel, déterministe, peu gourmand. On manipule directement la mémoire et les **registres** (adresses mémoire qui contrôlent le matériel).\n\n✅ Bonnes pratiques :\n• Types à taille fixe : `uint8_t`, `uint16_t`, `int32_t` (via `<stdint.h>`)\n• Mot-clé `volatile` pour les registres matériels (empêche le compilateur d'optimiser une lecture)\n• Manipulation de bits avec `|`, `&`, `~`, `<<`\n\n```c\n#include <stdint.h>\n#define LED_BIT (1U << 5)\nvolatile uint32_t *PORT = (uint32_t*)0x40020014;\n*PORT |= LED_BIT;   // allume\n*PORT &= ~LED_BIT;  // éteint\n```\n\n⚠️ Évite les `float` si le MCU n'a pas d'unité flottante (FPU) : les calculs deviennent très lents. Préfère l'arithmétique entière ou virgule fixe.",
                    'questions' => [
                        ['question' => 'À quoi sert le mot-clé `volatile` ?', 'options' => ['Accélérer le code', 'Empêcher le compilateur d\'optimiser l\'accès à une variable matérielle', 'Réduire la taille du binaire', 'Rendre la variable constante'], 'correct' => [1], 'explanation' => '`volatile` force la relecture réelle de la variable, indispensable pour un registre matériel qui change hors du programme.'],
                        ['question' => 'Quelle expression met à 1 le bit 5 sans toucher aux autres ?', 'options' => ['x = (1<<5)', 'x |= (1<<5)', 'x &= ~(1<<5)', 'x = 5'], 'correct' => [1], 'explanation' => 'Le OU logique `|=` avec un masque positionne le bit ciblé en laissant les autres inchangés.'],
                        ['question' => 'Quels choix sont recommandés en C embarqué ? (plusieurs réponses)', 'options' => ['Utiliser uint8_t/uint16_t', 'Abuser des float sans FPU', 'Manipuler les bits avec masques', 'Ignorer la taille des types'], 'correct' => [0,2], 'explanation' => 'Les types à taille fixe et la manipulation de bits par masques sont des pratiques clés de l\'embarqué.'],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — GPIO : entrées/sorties',
                    'subtitle' => 'Piloter LED, boutons et configuration des broches',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : configurer et utiliser les broches d'entrée/sortie (GPIO, General Purpose Input/Output).\n\n💡 Chaque broche peut être configurée en **entrée** (lire un bouton, un signal) ou en **sortie** (allumer une LED, commander un relais). On configure via des registres : direction, mode, pull-up/pull-down.\n\n✅ Le « Hello World » de l'embarqué est le **blink** (clignotement de LED) :\n\n```c\nset_pin_output(PIN13);\nwhile (1) {\n    gpio_write(PIN13, HIGH);\n    delay_ms(500);\n    gpio_write(PIN13, LOW);\n    delay_ms(500);\n}\n```\n\n⚠️ Sur une entrée bouton, active une résistance de **pull-up** ou **pull-down** interne pour éviter un état flottant (lectures aléatoires). Pense aussi au **debounce** (anti-rebond) car un bouton mécanique génère plusieurs transitions parasites.\n\nExemple : un portail automatique à Douala lit un bouton (entrée) et commande un moteur via un relais (sortie).",
                    'questions' => [
                        ['question' => 'Pourquoi activer une résistance de pull-up sur une entrée bouton ?', 'options' => ['Pour augmenter la tension', 'Pour éviter un état logique flottant', 'Pour économiser la batterie', 'Pour accélérer le CPU'], 'correct' => [1], 'explanation' => 'Sans pull-up/pull-down, une entrée non connectée flotte et donne des lectures aléatoires.'],
                        ['question' => 'Le « debounce » sert à...', 'options' => ['Filtrer les rebonds mécaniques d\'un bouton', 'Augmenter la fréquence', 'Réduire la consommation', 'Compiler plus vite'], 'correct' => [0], 'explanation' => 'Un contact mécanique génère plusieurs transitions ; le debounce évite de compter plusieurs appuis pour un seul.'],
                        ['question' => 'Avant d\'allumer une LED sur une broche, il faut...', 'options' => ['La configurer en entrée', 'La configurer en sortie', 'La laisser flottante', 'Couper l\'alimentation'], 'correct' => [1], 'explanation' => 'Pour piloter une LED, la broche doit être configurée en sortie (output).'],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — Interruptions',
                    'subtitle' => 'Réagir aux événements sans polling',
                    'xp_reward' => 130,
                    'content' => "🎯 **Objectif** : utiliser les interruptions pour réagir immédiatement à un événement.\n\n💡 Le **polling** (sonder en boucle un état) gaspille le CPU. Une **interruption** (IRQ) suspend le programme dès qu'un événement survient (bouton pressé, timer écoulé, octet reçu) et exécute une **ISR** (Interrupt Service Routine).\n\n✅ Schéma :\n```\nProgramme principal ──► [IRQ déclenchée] ──► ISR exécutée ──► retour au programme\n```\n\nBonnes pratiques pour une ISR :\n• La garder **très courte** (pas de delay, pas de printf)\n• Mettre les variables partagées en `volatile`\n• Lever un drapeau (flag) et traiter le travail dans la boucle principale\n\n⚠️ Une variable modifiée dans l'ISR et lue dans `main()` doit être `volatile`, sinon le compilateur peut ignorer ses changements. Attention aussi aux **accès concurrents** : désactive brièvement les interruptions (section critique) pour les variables multi-octets.",
                    'questions' => [
                        ['question' => 'Qu\'est-ce qu\'une ISR ?', 'options' => ['Un type de mémoire', 'La routine exécutée quand une interruption survient', 'Un bus de communication', 'Un registre de configuration'], 'correct' => [1], 'explanation' => 'L\'ISR (Interrupt Service Routine) est la fonction appelée automatiquement lors d\'une interruption.'],
                        ['question' => 'Pourquoi garder une ISR très courte ?', 'options' => ['Pour économiser la Flash', 'Pour ne pas bloquer le système et d\'autres interruptions', 'Pour qu\'elle compile', 'Pour réduire le code source'], 'correct' => [1], 'explanation' => 'Une ISR longue bloque la réactivité et peut faire manquer d\'autres interruptions ; on délègue le traitement au main.'],
                        ['question' => 'Quel est l\'avantage des interruptions face au polling ?', 'options' => ['Elles consomment plus de CPU', 'Le CPU réagit sans sonder en boucle', 'Elles ralentissent le code', 'Elles suppriment la RAM'], 'correct' => [1], 'explanation' => 'Les interruptions libèrent le CPU du polling permanent et garantissent une réaction immédiate.'],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — Les timers et le PWM',
                    'subtitle' => 'Mesurer le temps et commander finement',
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : exploiter les timers matériels pour la temporisation précise et le PWM.\n\n💡 Un **timer** est un compteur matériel qui s'incrémente à chaque tic d'horloge. Il sert à :\n• générer des interruptions périodiques (ex : toutes les 1 ms)\n• mesurer une durée\n• produire un signal **PWM** (Pulse Width Modulation)\n\n✅ Le **PWM** fait varier la largeur d'impulsion d'un signal carré. Le **rapport cyclique** (duty cycle) règle la puissance moyenne :\n\n| Duty cycle | Effet sur une LED | Effet sur un moteur |\n|------------|-------------------|---------------------|\n| 0 %        | éteinte           | arrêté              |\n| 50 %       | demi-luminosité   | demi-vitesse        |\n| 100 %      | pleine luminosité | pleine vitesse      |\n\n⚠️ Évite `delay_ms()` bloquant pour cadencer ton programme : il fige tout le CPU. Préfère une interruption timer qui incrémente un compteur de **ticks**, base d'une horloge logicielle non bloquante.\n\nExemple : faire varier la vitesse d'un ventilateur solaire selon la température.",
                    'questions' => [
                        ['question' => 'Que règle le rapport cyclique (duty cycle) en PWM ?', 'options' => ['La fréquence d\'horloge', 'La puissance moyenne délivrée', 'La taille de la RAM', 'Le nombre de broches'], 'correct' => [1], 'explanation' => 'Le duty cycle (% de temps à l\'état haut) fixe la puissance moyenne, donc la luminosité ou la vitesse.'],
                        ['question' => 'Pourquoi éviter delay_ms() bloquant pour cadencer un programme ?', 'options' => ['Il consomme trop de Flash', 'Il fige tout le CPU pendant l\'attente', 'Il efface la RAM', 'Il désactive le PWM'], 'correct' => [1], 'explanation' => 'Un delay bloquant gèle le CPU ; une interruption timer permet une temporisation non bloquante.'],
                        ['question' => 'Un timer matériel peut servir à... (plusieurs réponses)', 'options' => ['Générer des interruptions périodiques', 'Stocker le firmware', 'Produire un signal PWM', 'Remplacer la Flash'], 'correct' => [0,2], 'explanation' => 'Les timers cadencent des interruptions périodiques et génèrent des signaux PWM.'],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — Communication série : UART, I2C, SPI',
                    'subtitle' => 'Faire dialoguer le MCU avec d\'autres puces',
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : choisir et utiliser le bon protocole de communication.\n\n💡 Trois bus dominent l'embarqué :\n\n| Bus  | Fils         | Type            | Usage typique                  |\n|------|--------------|-----------------|--------------------------------|\n| UART | TX, RX       | asynchrone, 1:1 | console série, module GPS/GSM  |\n| I2C  | SDA, SCL     | synchrone, multi| capteurs, EEPROM, écran OLED   |\n| SPI  | MOSI,MISO,SCK,CS | synchrone, rapide | carte SD, écran TFT, flash  |\n\n✅ **UART** : pas d'horloge partagée, il faut un **baud rate** identique des deux côtés (ex : 9600 ou 115200). **I2C** : 2 fils seulement, chaque esclave a une **adresse**, débit modéré. **SPI** : plus de fils mais très rapide, une ligne **CS** (Chip Select) par esclave.\n\n```c\ni2c_write(0x3C, reg, value); // écrit sur l'OLED d'adresse 0x3C\n```\n\n⚠️ Sur I2C, n'oublie pas les **résistances de pull-up** sur SDA et SCL. Sur UART, croise TX↔RX entre les deux cartes.",
                    'questions' => [
                        ['question' => 'Quel bus n\'utilise que deux fils et adresse plusieurs esclaves ?', 'options' => ['SPI', 'I2C', 'UART', 'CAN'], 'correct' => [1], 'explanation' => 'I2C utilise SDA et SCL et identifie chaque périphérique par une adresse.'],
                        ['question' => 'Pour une liaison UART qui fonctionne, il faut...', 'options' => ['La même adresse esclave', 'Le même baud rate des deux côtés', 'Une ligne Chip Select', 'Trois fils de données'], 'correct' => [1], 'explanation' => 'L\'UART étant asynchrone, les deux extrémités doivent partager exactement le même baud rate.'],
                        ['question' => 'À quoi sert la ligne CS (Chip Select) en SPI ?', 'options' => ['Fournir l\'alimentation', 'Sélectionner l\'esclave avec lequel communiquer', 'Définir le baud rate', 'Stocker les données'], 'correct' => [1], 'explanation' => 'En SPI, le maître active la ligne CS de l\'esclave qu\'il veut adresser.'],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — Capteurs et conversion analogique',
                    'subtitle' => 'Lire le monde réel : ADC, calibration, filtrage',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : acquérir des mesures fiables depuis des capteurs.\n\n💡 Deux familles de capteurs :\n• **Analogiques** (LDR, potentiomètre, certains capteurs de température) : tension continue → lue par l'**ADC** (Analog-to-Digital Converter)\n• **Numériques** (BME280, DHT22, MPU6050) : communiquent par I2C/SPI/UART\n\n✅ L'ADC convertit une tension en nombre. Avec une résolution de 12 bits (0–4095) et une référence de 3,3 V :\n```\nvaleur_lue = (tension / 3.3) * 4095\ntension = (valeur_lue * 3.3) / 4095\n```\n\nBonnes pratiques :\n• **Calibrer** le capteur (corriger l'offset et la pente)\n• **Filtrer** le bruit : moyenne glissante ou filtre passe-bas\n• Respecter le **temps d'échantillonnage** du capteur\n\n⚠️ Ne dépasse jamais la tension de référence de l'ADC : tu risques d'endommager la broche. Utilise un **pont diviseur** pour ramener une tension trop élevée dans la plage admissible.\n\nExemple : mesurer l'humidité du sol d'un champ pour déclencher l'irrigation.",
                    'questions' => [
                        ['question' => 'Que fait un ADC ?', 'options' => ['Convertit un nombre en tension', 'Convertit une tension analogique en valeur numérique', 'Amplifie un signal', 'Stocke des données'], 'correct' => [1], 'explanation' => 'L\'ADC (Analog-to-Digital Converter) transforme une tension continue en nombre exploitable par le MCU.'],
                        ['question' => 'Avec un ADC 12 bits, la valeur maximale lue est...', 'options' => ['255', '1023', '4095', '65535'], 'correct' => [2], 'explanation' => '12 bits donnent 2^12 = 4096 niveaux, soit des valeurs de 0 à 4095.'],
                        ['question' => 'Comment réduire le bruit sur une lecture de capteur ?', 'options' => ['Augmenter la tension', 'Appliquer une moyenne glissante / filtre passe-bas', 'Supprimer la masse', 'Désactiver l\'ADC'], 'correct' => [1], 'explanation' => 'Un filtrage logiciel (moyenne glissante, passe-bas) lisse les mesures bruitées.'],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — RTOS et temps réel',
                    'subtitle' => 'Tâches, ordonnancement et synchronisation',
                    'xp_reward' => 170,
                    'content' => "🎯 **Objectif** : structurer un firmware complexe avec un système temps réel.\n\n💡 Au-delà d'une simple boucle `while(1)`, un **RTOS** (Real-Time Operating System, ex : FreeRTOS) découpe le programme en **tâches** indépendantes ordonnancées selon leur **priorité**. Le « temps réel » garantit qu'une réponse arrive avant une **échéance** (deadline).\n\n✅ Notions clés :\n• **Tâche (task)** : fonction qui tourne « en parallèle »\n• **Ordonnanceur (scheduler)** : décide quelle tâche s'exécute\n• **Préemption** : une tâche prioritaire interrompt une moins prioritaire\n• **Sémaphore / mutex** : protègent les ressources partagées\n• **Queue** : transmet des données entre tâches\n\n```c\nxTaskCreate(lire_capteur, \"sensor\", 128, NULL, 2, NULL);\nxTaskCreate(envoyer_data, \"net\", 256, NULL, 1, NULL);\nvTaskStartScheduler();\n```\n\n⚠️ Sans protection, deux tâches qui modifient la même variable provoquent une **race condition**. Utilise un **mutex** pour sérialiser l'accès, mais attention à l'**inversion de priorité**.",
                    'questions' => [
                        ['question' => 'Que garantit un système « temps réel » ?', 'options' => ['La vitesse maximale du CPU', 'Une réponse avant une échéance (deadline)', 'L\'absence de bugs', 'Une grande RAM'], 'correct' => [1], 'explanation' => 'Le temps réel garantit le respect de délais ; la prévisibilité prime sur la rapidité brute.'],
                        ['question' => 'À quoi sert un mutex dans un RTOS ?', 'options' => ['Accélérer l\'ordonnanceur', 'Protéger une ressource partagée contre les accès concurrents', 'Créer des tâches', 'Réduire la Flash'], 'correct' => [1], 'explanation' => 'Le mutex sérialise l\'accès à une ressource partagée, évitant les race conditions.'],
                        ['question' => 'Quels éléments fournit typiquement un RTOS ? (plusieurs réponses)', 'options' => ['Ordonnanceur de tâches', 'Compilateur C', 'Sémaphores et queues', 'Driver d\'écran HDMI'], 'correct' => [0,2], 'explanation' => 'Un RTOS fournit l\'ordonnancement des tâches et des primitives de synchronisation comme sémaphores et queues.'],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Mémoire contrainte et faible consommation',
                    'subtitle' => 'Optimiser RAM, Flash et énergie',
                    'xp_reward' => 185,
                    'content' => "🎯 **Objectif** : faire tenir le firmware dans peu de mémoire et économiser la batterie.\n\n💡 **Mémoire contrainte** : un MCU a souvent quelques Ko de RAM. Stratégies :\n• Éviter le `malloc` dynamique (fragmentation) → préférer l'allocation **statique**\n• Surveiller la **pile (stack)** : un débordement écrase la mémoire (stack overflow)\n• Mettre les constantes en Flash (`const`) plutôt qu'en RAM\n• Choisir les bons types (`uint8_t` au lieu de `int`)\n\n✅ **Faible consommation** : essentiel sur batterie ou panneau solaire. Le MCU passe en **mode veille (sleep)** entre deux mesures et se réveille sur interruption (timer ou capteur).\n\n```\nÉveil bref ──► mesure ──► transmission ──► SLEEP profond ──► (réveil timer) ──► boucle\n```\n\n⚠️ En **deep sleep**, la consommation peut tomber sous 10 µA. Coupe les périphériques inutilisés (radio WiFi, LED). Une LED allumée en permanence peut vider une batterie plus vite que tout le reste du circuit.\n\nExemple : un capteur de niveau d'eau autonome dans un village sans électricité.",
                    'questions' => [
                        ['question' => 'Pourquoi éviter le malloc dynamique en embarqué contraint ?', 'options' => ['Il est interdit en C', 'Il provoque fragmentation et imprévisibilité mémoire', 'Il allonge le code source', 'Il désactive l\'ADC'], 'correct' => [1], 'explanation' => 'Sur peu de RAM, l\'allocation dynamique fragmente la mémoire et rend le comportement imprévisible ; on préfère le statique.'],
                        ['question' => 'Comment économiser fortement l\'énergie entre deux mesures ?', 'options' => ['Augmenter la fréquence CPU', 'Mettre le MCU en mode veille et le réveiller sur interruption', 'Allumer une LED témoin', 'Activer tous les périphériques'], 'correct' => [1], 'explanation' => 'Le mode sleep, avec réveil sur interruption, réduit la consommation à quelques microampères.'],
                        ['question' => 'Un stack overflow se produit quand...', 'options' => ['La Flash est pleine', 'La pile dépasse l\'espace réservé et écrase d\'autres données', 'L\'ADC sature', 'Le baud rate est trop élevé'], 'correct' => [1], 'explanation' => 'Si la pile croît au-delà de sa zone, elle écrase la mémoire voisine et corrompt le programme.'],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Debug embarqué et projet final',
                    'subtitle' => 'JTAG/SWD, outils et carrière',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : déboguer un firmware et consolider ton parcours d'ingénieur embarqué.\n\n💡 Sans écran ni clavier, on débogue autrement :\n• **Debugger matériel** (JTAG / SWD) avec une sonde (ST-Link, J-Link) : points d'arrêt, lecture des registres et de la RAM en direct\n• **printf série** vers l'UART (le « printf debugging »)\n• **Toggle de GPIO** + oscilloscope/analyseur logique pour mesurer une durée\n• **Watchdog** : redémarre le MCU s'il se bloque\n\n✅ Méthode : reproduire le bug, isoler (diviser pour régner), vérifier les hypothèses une à une. Surveille les classiques : pointeurs nuls, dépassement de tableau, variable non `volatile`, débordement de pile.\n\n⚠️ Un bug embarqué peut être **intermittent** (lié au timing, à la température, à une race condition) : c'est le plus difficile. L'analyseur logique et les traces sont tes meilleurs alliés.\n\n🏆 **Félicitations !** Tu maîtrises désormais la chaîne complète : du MCU au firmware temps réel optimisé et débogué. Débouchés : ingénieur firmware, développeur IoT, ingénieur R&D électronique, spécialiste agritech/énergie solaire. Des secteurs en forte croissance au Cameroun et en Afrique (IoT agricole, compteurs intelligents, mobile money matériel, dispositifs médicaux). Continue avec : Linux embarqué, Zephyr RTOS, sécurité des objets connectés et conception PCB.",
                    'questions' => [
                        ['question' => 'Quelle interface matérielle permet points d\'arrêt et inspection mémoire en direct ?', 'options' => ['UART seul', 'JTAG / SWD avec une sonde', 'I2C', 'Le PWM'], 'correct' => [1], 'explanation' => 'JTAG/SWD via une sonde (ST-Link, J-Link) offre breakpoints et inspection des registres et de la RAM en temps réel.'],
                        ['question' => 'À quoi sert un watchdog ?', 'options' => ['Augmenter la mémoire', 'Redémarrer le MCU s\'il se bloque', 'Accélérer l\'UART', 'Réduire la consommation'], 'correct' => [1], 'explanation' => 'Le watchdog réinitialise automatiquement le système si le programme ne le rafraîchit plus (signe de blocage).'],
                        ['question' => 'Quelles techniques aident à déboguer sans écran ? (plusieurs réponses)', 'options' => ['printf vers l\'UART', 'Effacer la Flash', 'Toggle de GPIO observé à l\'analyseur logique', 'Débrancher l\'alimentation'], 'correct' => [0,2], 'explanation' => 'Le printf série et le toggle de GPIO observé à l\'analyseur logique sont des méthodes de debug classiques en embarqué.'],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Systèmes Embarqués créée (10 niveaux).');
    }
}
