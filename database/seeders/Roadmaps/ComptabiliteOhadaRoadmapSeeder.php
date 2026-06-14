<?php

namespace Database\Seeders\Roadmaps;

use Illuminate\Database\Seeder;

/**
 * Roadmap Comptabilité OHADA — maîtrise du référentiel SYSCOHADA pour tenir une comptabilité conforme en zone OHADA.
 */
class ComptabiliteOhadaRoadmapSeeder extends Seeder
{
    use RoadmapSeederHelper;

    public function run(): void
    {
        $this->createRoadmap([
            'title' => 'Maîtrise la Comptabilité OHADA (SYSCOHADA)',
            'slug' => 'comptabilite-ohada',
            'domain' => 'finance',
            'description' => "Apprends à tenir une comptabilité conforme au référentiel SYSCOHADA révisé, appliqué dans 17 pays d'Afrique francophone dont le Cameroun. De la logique du plan comptable aux états financiers et aux obligations fiscales, cette roadmap te donne les bases solides du métier de comptable en zone OHADA.",
            'objectives' => "Comprendre le système comptable OHADA et son cadre juridique\nMaîtriser le plan comptable SYSCOHADA et les 9 classes de comptes\nEnregistrer les écritures comptables courantes\nCalculer amortissements et provisions\nÉtablir les états financiers (bilan, compte de résultat, TAFIRE)\nDistinguer le système normal du système minimal de trésorerie\nConnaître les bases de la fiscalité et les obligations déclaratives",
            'icon' => '📊',
            'color' => '#2563EB',
            'difficulty' => 'intermediate',
            'required_packs' => [],
            'pass_threshold' => 70,
            'order' => 20,
            'levels' => [
                [
                    'title' => 'Niveau 1 — Le système comptable OHADA',
                    'subtitle' => 'Cadre juridique et acteurs',
                    'xp_reward' => 100,
                    'content' => "🎯 **Objectif** : comprendre ce qu'est l'OHADA et pourquoi la comptabilité y est harmonisée.\n\n💡 L'OHADA (Organisation pour l'Harmonisation en Afrique du Droit des Affaires) regroupe 17 États membres, dont le Cameroun, le Sénégal et la Côte d'Ivoire. Son but : offrir un droit des affaires commun pour sécuriser les investissements.\n\nLe droit comptable est porté par l'**Acte uniforme relatif au droit comptable et à l'information financière (AUDCIF)**, dont la dernière révision (SYSCOHADA révisé) s'applique depuis le 1er janvier 2018.\n\n✅ Points clés :\n• Le référentiel comptable s'appelle le **SYSCOHADA**.\n• Il s'impose à toute entreprise, sauf les banques et assurances (normes spécifiques).\n• L'exercice comptable dure 12 mois et coïncide avec l'année civile (1er janvier au 31 décembre).\n• Les états financiers doivent donner une **image fidèle** du patrimoine et du résultat.\n\n⚠️ Ne pas confondre l'AUDCIF (le texte de loi) et le SYSCOHADA (le dispositif comptable qu'il décrit).",
                    'questions' => [
                        ['question' => 'Que signifie l\'acronyme OHADA ?', 'options' => ['Organisation Harmonisée du Développement Africain', 'Organisation pour l\'Harmonisation en Afrique du Droit des Affaires', 'Office Hégémonique des Affaires Douanières Africaines', 'Organisme Habilité pour l\'Audit en Afrique'], 'correct' => [1], 'explanation' => 'OHADA signifie Organisation pour l\'Harmonisation en Afrique du Droit des Affaires.'],
                        ['question' => 'Depuis quelle date le SYSCOHADA révisé s\'applique-t-il ?', 'options' => ['1er janvier 2001', 'Le 1er janvier 2018', '1er janvier 2010', '1er janvier 2022'], 'correct' => [1], 'explanation' => 'Le SYSCOHADA révisé issu de la dernière réforme de l\'AUDCIF s\'applique depuis le 1er janvier 2018.'],
                        ['question' => 'Quelles entités relèvent de normes comptables spécifiques (hors SYSCOHADA général) ?', 'options' => ['Les banques', 'Les commerçants individuels', 'Les compagnies d\'assurances', 'Les coopératives agricoles'], 'correct' => [0, 2], 'explanation' => 'Les banques et les compagnies d\'assurances suivent des plans comptables spécifiques distincts du SYSCOHADA général.'],
                    ],
                ],
                [
                    'title' => 'Niveau 2 — Les principes comptables fondamentaux',
                    'subtitle' => 'Les règles d\'or de la tenue des comptes',
                    'xp_reward' => 110,
                    'content' => "🎯 **Objectif** : connaître les principes qui garantissent la fiabilité des comptes en SYSCOHADA.\n\n💡 Le SYSCOHADA repose sur des principes incontournables. Les mémoriser, c'est éviter 80 % des erreurs.\n\n✅ Les principes essentiels :\n• **Prudence** : ne pas surévaluer l'actif ni le résultat ; comptabiliser les pertes probables.\n• **Permanence des méthodes** : garder les mêmes méthodes d'un exercice à l'autre.\n• **Coût historique** : enregistrer les biens à leur valeur d'acquisition.\n• **Continuité d'exploitation** : présumer que l'entreprise poursuit son activité.\n• **Spécialisation (indépendance) des exercices** : rattacher charges et produits à l'exercice qui les concerne.\n• **Importance significative** : ne pas omettre une information susceptible d'influencer le lecteur.\n• **Prééminence de la réalité économique sur l'apparence juridique** (nouveauté forte du SYSCOHADA révisé).\n\n⚠️ Le principe de prudence ne signifie pas créer des réserves cachées : on n'exagère pas les pertes non plus.\n\nExemple concret : une entreprise de Douala qui craint qu'un client ne paie pas doit constater une **dépréciation** par prudence, sans attendre l'impayé définitif.",
                    'questions' => [
                        ['question' => 'Quel principe impose d\'enregistrer les pertes probables mais pas les gains potentiels ?', 'options' => ['La continuité d\'exploitation', 'Le principe de prudence', 'Le coût historique', 'La permanence des méthodes'], 'correct' => [1], 'explanation' => 'La prudence conduit à anticiper les charges/pertes probables sans comptabiliser de gains non réalisés.'],
                        ['question' => 'Rattacher une charge à l\'exercice qu\'elle concerne relève de quel principe ?', 'options' => ['Spécialisation des exercices', 'Importance significative', 'Coût historique', 'Prudence'], 'correct' => [0], 'explanation' => 'La spécialisation (indépendance) des exercices impose de rattacher charges et produits à la bonne période.'],
                        ['question' => 'Quelle nouveauté forte le SYSCOHADA révisé met-il en avant ?', 'options' => ['La prééminence de la réalité économique sur l\'apparence juridique', 'L\'abandon du coût historique', 'La suppression de la prudence', 'La tenue des comptes en devises uniquement'], 'correct' => [0], 'explanation' => 'Le SYSCOHADA révisé renforce le principe de prééminence de la réalité économique sur l\'apparence juridique.'],
                    ],
                ],
                [
                    'title' => 'Niveau 3 — Le plan comptable SYSCOHADA et ses 9 classes',
                    'subtitle' => 'La structure de codification des comptes',
                    'xp_reward' => 120,
                    'content' => "🎯 **Objectif** : maîtriser l'architecture du plan comptable SYSCOHADA.\n\n💡 Chaque compte porte un numéro dont le **premier chiffre** indique la classe. Le plan comprend 9 classes :\n\n| Classe | Nature |\n|--------|--------|\n| 1 | Comptes de ressources durables (capitaux, emprunts) |\n| 2 | Comptes d'actif immobilisé (immobilisations) |\n| 3 | Comptes de stocks |\n| 4 | Comptes de tiers (clients, fournisseurs, État) |\n| 5 | Comptes de trésorerie (banque, caisse) |\n| 6 | Comptes de charges des activités ordinaires |\n| 7 | Comptes de produits des activités ordinaires |\n| 8 | Comptes des autres charges et produits (HAO) |\n| 9 | Comptabilité analytique / engagements |\n\n✅ Mémo : classes **1 à 5** = comptes de **bilan** ; classes **6, 7, 8** = comptes de **gestion** (résultat).\n\nExemple : le compte 411 = Clients (classe 4 = tiers), le compte 521 = Banques (classe 5 = trésorerie), le compte 601 = Achats de marchandises (classe 6 = charges).\n\n⚠️ Plus on ajoute de chiffres, plus le compte est détaillé : 6 → 60 → 601 → 6011.",
                    'questions' => [
                        ['question' => 'À quelle classe appartiennent les comptes de trésorerie (banque, caisse) ?', 'options' => ['Classe 4', 'Classe 5', 'Classe 2', 'Classe 7'], 'correct' => [1], 'explanation' => 'La classe 5 regroupe les comptes de trésorerie (banques, caisse, valeurs à encaisser).'],
                        ['question' => 'Quelles classes correspondent aux comptes de gestion (compte de résultat) ?', 'options' => ['Classes 6, 7 et 8', 'Classes 1 à 5', 'Classes 3 et 4', 'Classe 9 uniquement'], 'correct' => [0], 'explanation' => 'Les classes 6 (charges), 7 (produits) et 8 (HAO) forment les comptes de gestion du résultat.'],
                        ['question' => 'Le compte 411 « Clients » appartient à quelle catégorie ?', 'options' => ['Comptes de tiers', 'Comptes de trésorerie', 'Comptes d\'immobilisations', 'Comptes de stocks'], 'correct' => [0], 'explanation' => 'Le compte 411 commence par 4 : il s\'agit d\'un compte de tiers (les clients).'],
                    ],
                ],
                [
                    'title' => 'Niveau 4 — La partie double et les écritures de base',
                    'subtitle' => 'Débit, crédit et équilibre',
                    'xp_reward' => 130,
                    'content' => "🎯 **Objectif** : enregistrer une écriture comptable en respectant la partie double.\n\n💡 Toute opération s'enregistre au minimum dans deux comptes : un (ou plusieurs) au **débit**, un (ou plusieurs) au **crédit**. Le total débit doit toujours égaler le total crédit.\n\n✅ Règles de fonctionnement :\n• Un compte d'**actif** (classe 2,3,5 et clients) augmente au **débit**.\n• Un compte de **passif** (classe 1 et fournisseurs) augmente au **crédit**.\n• Une **charge** (classe 6) s'enregistre au **débit**.\n• Un **produit** (classe 7) s'enregistre au **crédit**.\n\nExemple : un commerçant de Yaoundé achète des marchandises pour 1 000 000 FCFA, payées par banque.\n\n```\n601 Achats de marchandises ...... 1 000 000  (Débit)\n        521 Banques ...................... 1 000 000  (Crédit)\n```\n\nLe compte de charges 601 augmente (débit), la trésorerie 521 diminue (crédit). Équilibre : 1 000 000 = 1 000 000. ✔️\n\n⚠️ Une écriture déséquilibrée est rejetée : vérifie toujours débit = crédit.",
                    'questions' => [
                        ['question' => 'Dans la partie double, que doit-on toujours vérifier ?', 'options' => ['Que le total des débits égale le total des crédits', 'Que le compte de banque soit toujours débité', 'Qu\'il n\'y ait qu\'un seul compte mouvementé', 'Que le résultat soit positif'], 'correct' => [0], 'explanation' => 'Le principe de la partie double exige l\'égalité totale débit = total crédit pour chaque écriture.'],
                        ['question' => 'Un compte de charge (classe 6) augmente :', 'options' => ['Au crédit', 'Au débit', 'Indifféremment', 'Il ne varie jamais'], 'correct' => [1], 'explanation' => 'Les charges (classe 6) s\'enregistrent et augmentent au débit.'],
                        ['question' => 'Un achat de marchandises payé par banque mouvemente :', 'options' => ['601 au débit et 521 au crédit', '521 au débit et 601 au crédit', '411 au débit et 701 au crédit', '101 au débit et 521 au crédit'], 'correct' => [0], 'explanation' => 'On débite la charge 601 (achat) et on crédite la banque 521 (sortie de trésorerie).'],
                    ],
                ],
                [
                    'title' => 'Niveau 5 — Écritures courantes : ventes, achats et TVA',
                    'subtitle' => 'Les opérations du quotidien',
                    'xp_reward' => 140,
                    'content' => "🎯 **Objectif** : enregistrer les opérations courantes incluant la TVA.\n\n💡 La TVA collectée sur les ventes appartient à l'État : l'entreprise n'est qu'un collecteur. Au Cameroun, le taux normal est de 19,25 %.\n\n✅ Comptes clés de TVA :\n• **4431** TVA facturée sur ventes (collectée, au crédit).\n• **4452** TVA récupérable sur achats (déductible, au débit).\n• **4441** État, TVA due.\n\nExemple : vente à crédit de 1 000 000 FCFA HT + TVA 19,25 % (192 500), soit 1 192 500 TTC.\n\n```\n411 Clients ................. 1 192 500  (Débit)\n        701 Ventes de marchandises ... 1 000 000  (Crédit)\n        4431 TVA facturée ............... 192 500  (Crédit)\n```\n\n• Le client doit le montant TTC (débit 411).\n• Le produit (701) est enregistré HT.\n• La TVA collectée (4431) sera reversée à l'État.\n\n⚠️ Erreur fréquente : enregistrer le produit en TTC. Le chiffre d'affaires se comptabilise toujours **hors taxes**.",
                    'questions' => [
                        ['question' => 'Sur quel compte enregistre-t-on la TVA collectée sur une vente ?', 'options' => ['4452 TVA récupérable', '4431 TVA facturée sur ventes', '701 Ventes', '4441 État TVA due'], 'correct' => [1], 'explanation' => 'La TVA collectée auprès des clients s\'enregistre au crédit du compte 4431 TVA facturée sur ventes.'],
                        ['question' => 'Le chiffre d\'affaires (compte 701) se comptabilise :', 'options' => ['En montant TTC', 'Hors taxes (HT)', 'En montant net de remise et TTC', 'Avec la TVA incluse'], 'correct' => [1], 'explanation' => 'Le produit des ventes s\'enregistre hors taxes ; la TVA est isolée dans un compte de tiers.'],
                        ['question' => 'Pour une vente à crédit de 1 192 500 TTC, le compte 411 Clients est :', 'options' => ['Débité de 1 192 500', 'Crédité de 1 000 000', 'Débité de 1 000 000', 'Crédité de 192 500'], 'correct' => [0], 'explanation' => 'Le client doit la totalité TTC, on débite donc 411 du montant TTC de 1 192 500 FCFA.'],
                    ],
                ],
                [
                    'title' => 'Niveau 6 — Les amortissements',
                    'subtitle' => 'Étaler la dépréciation des immobilisations',
                    'xp_reward' => 150,
                    'content' => "🎯 **Objectif** : calculer et comptabiliser un amortissement linéaire.\n\n💡 Une immobilisation (matériel, véhicule, bâtiment) perd de la valeur avec le temps. L'**amortissement** constate cette dépréciation et l'étale sur la durée d'utilisation.\n\n✅ Méthode linéaire (la plus courante en OHADA) :\n• Annuité = Valeur d'origine ÷ Durée d'utilité.\n• Taux linéaire = 100 ÷ durée (en années).\n\nExemple : un véhicule acheté 6 000 000 FCFA, amorti sur 5 ans.\n• Taux = 100 ÷ 5 = 20 %.\n• Annuité = 6 000 000 × 20 % = 1 200 000 FCFA par an.\n\nÉcriture de dotation annuelle :\n\n```\n6813 Dotations aux amortissements .... 1 200 000  (Débit)\n        2845 Amortissements matériel transport ... 1 200 000  (Crédit)\n```\n\n• Le compte 681 est une **charge** (diminue le résultat).\n• Le compte 28 (amortissements) vient **en déduction** de l'immobilisation au bilan.\n\n⚠️ La valeur nette comptable (VNC) = valeur d'origine − amortissements cumulés. Elle ne peut pas devenir négative.",
                    'questions' => [
                        ['question' => 'Un matériel de 6 000 000 FCFA amorti en linéaire sur 5 ans donne une annuité de :', 'options' => ['600 000 FCFA', '1 200 000 FCFA', '3 000 000 FCFA', '300 000 FCFA'], 'correct' => [1], 'explanation' => 'Taux = 100/5 = 20 % ; annuité = 6 000 000 × 20 % = 1 200 000 FCFA.'],
                        ['question' => 'La dotation aux amortissements est :', 'options' => ['Un produit', 'Une charge qui diminue le résultat', 'Une dette envers l\'État', 'Une augmentation de trésorerie'], 'correct' => [1], 'explanation' => 'La dotation (compte 681) est une charge calculée qui réduit le résultat de l\'exercice.'],
                        ['question' => 'Comment calcule-t-on la valeur nette comptable (VNC) ?', 'options' => ['Valeur d\'origine + amortissements cumulés', 'Valeur d\'origine − amortissements cumulés', 'Annuité × durée restante', 'Valeur d\'origine × taux'], 'correct' => [1], 'explanation' => 'La VNC se calcule en retranchant les amortissements cumulés de la valeur d\'origine.'],
                    ],
                ],
                [
                    'title' => 'Niveau 7 — Les provisions et dépréciations',
                    'subtitle' => 'Anticiper les risques et pertes de valeur',
                    'xp_reward' => 160,
                    'content' => "🎯 **Objectif** : distinguer provisions, dépréciations et savoir les comptabiliser.\n\n💡 Par prudence, on constate les pertes probables avant qu'elles ne soient certaines.\n\n✅ Deux notions à distinguer :\n• **Dépréciation** : perte de valeur probable mais réversible d'un actif (ex : un client douteux, un stock invendable).\n• **Provision pour risques et charges** (compte 19) : charge probable liée à un risque (litige, garantie, restructuration).\n\nExemple : un client de Douala doit 500 000 FCFA, mais le risque d'impayé est estimé à 60 %.\n• On transfère la créance en client douteux (compte 416).\n• On constate une dépréciation de 500 000 × 60 % = 300 000 FCFA.\n\n```\n6594 Charges pour dépréciation créances .... 300 000  (Débit)\n        491 Dépréciations comptes clients ........ 300 000  (Crédit)\n```\n\n⚠️ Important :\n• La dépréciation se calcule **sur le montant HT** de la créance.\n• Si le risque disparaît, on procède à une **reprise** (compte 759/798) qui constitue un produit.",
                    'questions' => [
                        ['question' => 'Une dépréciation correspond à :', 'options' => ['Une perte de valeur probable et réversible d\'un actif', 'Une perte de valeur définitive d\'un actif', 'Une augmentation de capital', 'Un remboursement d\'emprunt'], 'correct' => [0], 'explanation' => 'La dépréciation constate une perte de valeur probable et réversible (clients douteux, stocks, etc.).'],
                        ['question' => 'Un client doit 500 000 FCFA HT avec un risque d\'impayé de 60 %. La dépréciation est de :', 'options' => ['200 000 FCFA', '300 000 FCFA', '500 000 FCFA', '60 000 FCFA'], 'correct' => [1], 'explanation' => 'La dépréciation = 500 000 × 60 % = 300 000 FCFA, calculée sur le montant HT.'],
                        ['question' => 'Que se passe-t-il si le risque ayant justifié une dépréciation disparaît ?', 'options' => ['On constate une reprise, qui est un produit', 'On augmente la dépréciation', 'On supprime l\'écriture initiale sans trace', 'Rien, la dépréciation reste figée'], 'correct' => [0], 'explanation' => 'La disparition du risque entraîne une reprise de dépréciation comptabilisée en produit.'],
                    ],
                ],
                [
                    'title' => 'Niveau 8 — Les états financiers et la TAFIRE',
                    'subtitle' => 'Bilan, compte de résultat et flux de trésorerie',
                    'xp_reward' => 170,
                    'content' => "🎯 **Objectif** : connaître les états financiers obligatoires du système normal.\n\n💡 À la clôture, l'entreprise produit ses états financiers à partir de la balance. Dans le **système normal**, quatre documents sont obligatoires :\n\n✅ Les états financiers :\n• **Le Bilan** : photographie du patrimoine (Actif = Passif). Actif immobilisé, actif circulant, trésorerie côté actif ; capitaux propres, dettes financières et passif circulant côté passif.\n• **Le Compte de résultat** : charges et produits classés par nature, qui dégagent le résultat net.\n• **Le TAFIRE** (Tableau Financier des Ressources et des Emplois) : explique les flux financiers, les ressources dégagées (CAFG) et leurs emplois (investissements, remboursements).\n• **Les Notes annexes** : commentaires et détails complétant les chiffres.\n\nNotion clé : la **CAFG** (Capacité d'Autofinancement Globale) mesure les ressources internes générées par l'activité, point de départ du TAFIRE.\n\n⚠️ Le bilan SYSCOHADA est présenté en grandes masses ; la trésorerie y figure de façon distincte (actif et passif de trésorerie), particularité du référentiel.\n\nMémo : Bilan = situation à une date ; Compte de résultat = activité sur une période.",
                    'questions' => [
                        ['question' => 'Que représente le bilan ?', 'options' => ['L\'activité sur une période', 'La photographie du patrimoine à une date donnée', 'Les flux de trésorerie uniquement', 'Le détail des ventes mensuelles'], 'correct' => [1], 'explanation' => 'Le bilan est une photographie du patrimoine (actif = passif) à une date donnée.'],
                        ['question' => 'Que signifie TAFIRE ?', 'options' => ['Tableau Financier des Ressources et des Emplois', 'Tableau d\'Amortissement et Fiscalité des Recettes', 'Tarif Fiscal Régional', 'Total Annuel des Frais et Impôts Réels'], 'correct' => [0], 'explanation' => 'Le TAFIRE est le Tableau Financier des Ressources et des Emplois, qui explique les flux financiers.'],
                        ['question' => 'Quels documents font partie des états financiers du système normal ?', 'options' => ['Le bilan', 'Le compte de résultat', 'La carte grise des véhicules', 'Les notes annexes'], 'correct' => [0, 1, 3], 'explanation' => 'Le système normal comprend le bilan, le compte de résultat, le TAFIRE et les notes annexes.'],
                    ],
                ],
                [
                    'title' => 'Niveau 9 — Système normal vs système minimal de trésorerie',
                    'subtitle' => 'Choisir le bon régime comptable',
                    'xp_reward' => 185,
                    'content' => "🎯 **Objectif** : distinguer les deux systèmes de présentation du SYSCOHADA et savoir lequel s'applique.\n\n💡 Le SYSCOHADA prévoit deux systèmes selon la taille de l'entité.\n\n✅ **Système normal** :\n• Concerne les entreprises dépassant un certain seuil de chiffre d'affaires.\n• Comptabilité d'engagement complète (partie double).\n• États financiers complets : bilan, compte de résultat, TAFIRE, notes annexes.\n\n✅ **Système minimal de trésorerie (SMT)** :\n• Réservé aux très petites entreprises (TPE) sous un seuil de chiffre d'affaires (variable selon l'activité : négoce, artisanat, services).\n• Comptabilité **de trésorerie** : on enregistre essentiellement les encaissements et décaissements.\n• États simplifiés : bilan, compte de résultat et notes très allégés.\n\n📌 Le SMT a remplacé l'ancien « système allégé » lors de la révision du SYSCOHADA. Une TPE qui dépasse le seuil deux exercices de suite bascule vers le système normal.\n\n⚠️ Le choix n'est pas libre : il dépend du chiffre d'affaires annuel et du type d'activité. Une petite boutique de quartier à Yaoundé relève souvent du SMT, mais une PME importatrice du système normal.",
                    'questions' => [
                        ['question' => 'Le système minimal de trésorerie (SMT) est réservé :', 'options' => ['Aux grandes entreprises cotées', 'Aux très petites entreprises sous un seuil de chiffre d\'affaires', 'Aux banques', 'À toutes les entreprises sans condition'], 'correct' => [1], 'explanation' => 'Le SMT est destiné aux TPE dont le chiffre d\'affaires reste en dessous d\'un seuil défini selon l\'activité.'],
                        ['question' => 'Le SMT repose sur une comptabilité :', 'options' => ['D\'engagement complète', 'De trésorerie (encaissements/décaissements)', 'Analytique obligatoire', 'En devises étrangères'], 'correct' => [1], 'explanation' => 'Le système minimal de trésorerie enregistre essentiellement les encaissements et décaissements.'],
                        ['question' => 'Que devient une TPE qui dépasse le seuil deux exercices de suite ?', 'options' => ['Elle reste au SMT', 'Elle bascule vers le système normal', 'Elle est exonérée de comptabilité', 'Elle passe aux normes IFRS'], 'correct' => [1], 'explanation' => 'Le dépassement durable du seuil oblige l\'entité à adopter le système normal.'],
                    ],
                ],
                [
                    'title' => 'Niveau 10 — Fiscalité de base et obligations déclaratives',
                    'subtitle' => 'Du bilan à la déclaration fiscale',
                    'xp_reward' => 200,
                    'content' => "🎯 **Objectif** : relier la comptabilité aux obligations fiscales et déclaratives, notamment au Cameroun.\n\n💡 La comptabilité sert de base au calcul de l'impôt. Maîtriser le calendrier déclaratif est essentiel pour éviter pénalités et redressements.\n\n✅ Principales obligations (cas du Cameroun) :\n• **TVA** : déclaration et reversement **mensuels** (en général avant le 15 du mois suivant).\n• **Impôt sur les sociétés (IS)** : taux de droit commun de 33 % (28 % + CAC 10 %). Acomptes versés en cours d'année, solde régularisé à la clôture.\n• **DSF (Déclaration Statistique et Fiscale)** : liasse fiscale et états financiers à déposer **au plus tard le 15 mars** suivant la clôture.\n• **Patente, précompte, retenues sur salaires (IRPP)** selon les cas.\n\n📌 Résultat fiscal = résultat comptable + réintégrations − déductions. Certaines charges comptables (amendes, charges non justifiées) sont **réintégrées** car non déductibles fiscalement.\n\n⚠️ Une comptabilité non probante peut être rejetée par l'administration, qui taxe alors d'office. Conserve toutes les pièces justificatives.\n\n🏆 **Félicitations !** Tu maîtrises désormais les fondamentaux de la comptabilité OHADA : du plan comptable aux états financiers et à la fiscalité. Ces compétences ouvrent des débouchés concrets : aide-comptable, comptable d'entreprise, assistant en cabinet, gestionnaire de PME. En te perfectionnant (DSF, fiscalité approfondie, audit), tu peux évoluer vers chef comptable, contrôleur de gestion ou expert-comptable. Continue de pratiquer sur des cas réels : la comptabilité s'apprend en faisant !",
                    'questions' => [
                        ['question' => 'Au Cameroun, la TVA se déclare et se reverse généralement :', 'options' => ['Une fois par an', 'Mensuellement', 'Tous les trimestres', 'Tous les deux ans'], 'correct' => [1], 'explanation' => 'La TVA fait l\'objet d\'une déclaration et d\'un reversement mensuels au Cameroun.'],
                        ['question' => 'Comment passe-t-on du résultat comptable au résultat fiscal ?', 'options' => ['On ajoute les réintégrations et on retranche les déductions', 'On multiplie par le taux d\'IS', 'On retranche uniquement la TVA', 'Ils sont toujours identiques'], 'correct' => [0], 'explanation' => 'Résultat fiscal = résultat comptable + réintégrations − déductions extra-comptables.'],
                        ['question' => 'Quelles affirmations sont correctes sur les obligations fiscales ?', 'options' => ['La DSF regroupe la liasse fiscale et les états financiers', 'Certaines charges comptables sont réintégrées car non déductibles', 'Toute charge comptabilisée est forcément déductible', 'Une comptabilité non probante peut entraîner une taxation d\'office'], 'correct' => [0, 1, 3], 'explanation' => 'La DSF synthétise états financiers et liasse fiscale, certaines charges sont réintégrées, et une comptabilité non probante expose à la taxation d\'office.'],
                    ],
                ],
            ],
        ]);

        $this->command->info('  ✓ Roadmap Comptabilité OHADA créée (10 niveaux).');
    }
}
