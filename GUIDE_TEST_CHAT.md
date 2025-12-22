# 🧪 Guide de Test - Système de Chat Ultra-Moderne

**Version:** 2.0
**Date:** 22 Décembre 2025

---

## 🚀 Démarrage du Système

### Backend (Laravel + Reverb)

```bash
# 1. Vérifier/Mettre à jour l'IP dans .env
nano .env
# REVERB_HOST=10.200.82.233  (ou votre IP locale)

# 2. Démarrer le chat complet
./start-chat.sh

# Vous devriez voir:
# ✅ Reverb démarré (PID: xxxx)
# ✅ Queue worker démarré (PID: xxxx)
# 📊 Services actifs
```

### Frontend (Flutter)

```bash
# 1. Se placer dans le dossier frontend
cd estuaire-emploie-frontend

# 2. Installer les dépendances (si pas déjà fait)
flutter pub get

# 3. Lancer l'application
flutter run

# Vous devriez voir dans les logs:
# 🔌 Initializing WebSocketService...
# 🔌 WebSocketService initialized and onInit called manually
# 📨 MessageService initialized
# ✅ All services initialized successfully
```

---

## 📱 Scénario de Test Complet

### Préparation

**Vous aurez besoin de:**
- 2 comptes utilisateurs (ou 2 appareils/émulateurs)
- 1 compte **recruteur** (qui a posté un job)
- 1 compte **candidat** (qui a postulé au job)

**Configuration requise:**
- Backend Reverb actif sur port 8080
- Les deux apps connectées au même backend
- Connexion internet stable

---

## 🎯 Test 1: Création de Conversation (Recruteur)

### Étapes

**1. Se connecter en tant que recruteur**
```
Email: recruteur@test.com
Password: ***
```

**2. Aller dans "Candidatures" ou "Mes offres"**
```
→ Sélectionner une candidature en statut "pending"
```

**3. Accepter la candidature**
```
→ Cliquer sur "Accepter"
→ Status devient "accepted"
→ Vérifier qu'un bouton "Créer conversation" ou "Contacter" apparaît
```

**4. Créer la conversation**
```
→ Cliquer sur "Créer conversation"
→ Vérifier la redirection vers ChatView
```

**5. Vérifier l'apparition dans la liste**
```
✅ La conversation doit apparaître en haut de la liste
✅ Avatar du candidat avec initiale si pas de photo
✅ Nom du candidat affiché
✅ "Aucun message" comme dernier message
✅ Heure de création affichée
```

### Résultat Attendu

```
✅ Conversation créée avec succès
✅ Redirection automatique vers ChatView
✅ Conversation visible dans la liste
✅ Design ultra-moderne avec gradient et shadow
```

---

## 💬 Test 2: Envoi de Message (Premier Message)

### Étapes

**1. Ouvrir la conversation**
```
→ Cliquer sur la conversation créée
→ ChatDetailView s'ouvre
```

**2. Vérifier l'interface**
```
✅ AppBar avec gradient bleu
✅ Avatar du candidat en haut
✅ Nom du candidat
✅ Statut "Hors ligne" (car candidat pas encore connecté)
✅ Bouton refresh visible
✅ État vide avec icône message animée
✅ Texte "Aucun message" + "Commencez la conversation"
```

**3. Écrire un message**
```
→ Cliquer dans le champ de saisie
→ Taper "Bonjour, félicitations pour votre candidature !"
```

**4. Vérifier l'indicateur de frappe (typing)**
```
⚠️ NOTE: L'indicateur n'apparaît pas pour soi-même
Il apparaîtra uniquement chez l'autre utilisateur
```

**5. Envoyer le message**
```
→ Cliquer sur le bouton d'envoi (cercle bleu avec icône)
→ Observer l'animation du bouton (devient gris avec spinner)
```

**6. Vérifier l'apparition du message**
```
✅ Bulle bleue avec gradient alignée à droite
✅ Texte blanc
✅ Heure en bas à droite
✅ Icône de statut:
   - CircularProgress pendant l'envoi
   - ✓ (check) une fois envoyé
```

### Résultat Attendu

```
✅ Message envoyé avec succès
✅ Bulle bleue avec gradient
✅ Animation d'apparition fluide
✅ Statut "sent" visible (✓)
✅ Auto-scroll vers le bas
```

---

## 👥 Test 3: Réception en Temps Réel (Deuxième Utilisateur)

### Étapes

**1. Se connecter en tant que candidat (sur 2ème appareil)**
```
Email: candidat@test.com
Password: ***
```

**2. Aller dans "Messages" (ChatView)**
```
✅ La conversation doit apparaître automatiquement
✅ Badge de message non lu avec chiffre "1"
✅ Badge avec gradient bleu et glow
✅ Dernier message: "Bonjour, félicitations..."
✅ Card avec gradient subtil bleu-orange
✅ Border bleu autour de la card
```

**3. Ouvrir la conversation**
```
→ Cliquer sur la conversation
→ ChatDetailView s'ouvre
```

**4. Vérifier le message reçu**
```
✅ Bulle blanche alignée à gauche
✅ Texte noir
✅ Heure en bas
✅ Pas d'icône de statut (car message de l'autre)
✅ Animation d'apparition fluide
```

**5. Vérifier le statut online du recruteur**
```
✅ Indicateur vert à côté de l'avatar (si recruteur encore connecté)
✅ Texte "En ligne" sous le nom
✅ Glow effect vert autour de l'indicateur
```

### Résultat Attendu

```
✅ Message reçu instantanément (< 1s après envoi)
✅ Badge non lu affiché correctement
✅ Bulle blanche pour message reçu
✅ Online status du recruteur visible
```

---

## ⌨️ Test 4: Indicateur de Frappe (Typing)

### Étapes

**1. Sur l'appareil du candidat**
```
→ Commencer à taper un message
→ Ne pas l'envoyer tout de suite
```

**2. Sur l'appareil du recruteur (observer)**
```
✅ 3 dots animés doivent apparaître en bas à gauche
✅ Dots avec animation de pulsation
✅ Bulle blanche avec shadow
✅ Animation fluide et continue
```

**3. Candidat arrête de taper**
```
→ Attendre 3 secondes
```

**4. Observer la disparition**
```
✅ Les dots disparaissent après 3s d'inactivité
```

**5. Candidat envoie le message**
```
→ Terminer le message et l'envoyer
→ Exemple: "Merci beaucoup !"
```

**6. Vérifier la réception chez le recruteur**
```
✅ Les dots disparaissent immédiatement
✅ Le message apparaît avec animation
✅ Bulle blanche alignée à gauche
✅ Auto-scroll vers le bas
```

### Résultat Attendu

```
✅ Typing indicator apparaît en < 500ms
✅ Animation fluide des 3 dots
✅ Disparition après 3s ou à l'envoi
✅ Message reçu instantanément
```

---

## 📊 Test 5: Statuts de Messages (Sent/Delivered/Read)

### Étapes

**1. Recruteur envoie un nouveau message**
```
→ Taper "Quand êtes-vous disponible pour un entretien ?"
→ Envoyer
```

**2. Observer les statuts côté recruteur**
```
1. "sending" → CircularProgress blanc
2. "sent" → ✓ (check simple) gris
3. "delivered" → ✓✓ (double check) gris (si candidat online)
4. "read" → ✓✓ (double check) vert (quand candidat ouvre)
```

**3. Candidat ouvre la conversation (si fermée)**
```
→ Cliquer sur la conversation
```

**4. Observer le changement de statut**
```
✅ Chez le recruteur: icône devient ✓✓ verte
✅ Animation de changement de couleur
```

**5. Candidat répond**
```
→ Taper "Je suis disponible dès demain"
→ Envoyer
```

**6. Observer les statuts côté candidat**
```
✅ Bulle bleue alignée à droite
✅ Statut "sent" → ✓ gris
✅ Si recruteur a la conversation ouverte:
   - "delivered" immédiatement → ✓✓ gris
   - "read" après marquage → ✓✓ vert
```

### Résultat Attendu

```
✅ Cycle complet: sending → sent → delivered → read
✅ Changements de statut en temps réel
✅ Icônes claires et animations fluides
✅ Couleur verte pour "read"
```

---

## 🔴 Test 6: Message Échoué et Retry

### Étapes

**1. Désactiver internet sur l'appareil du recruteur**
```
→ Mode avion OU désactiver WiFi/data
```

**2. Essayer d'envoyer un message**
```
→ Taper "Test de connexion"
→ Cliquer sur envoyer
```

**3. Observer le message échoué**
```
✅ Bulle rouge (gradient red.shade100 → red.shade50)
✅ Border rouge autour de la bulle
✅ Icône error rouge
✅ Texte en rouge foncé
✅ Shadow rouge
```

**4. Faire un long press sur le message échoué**
```
→ Appuyer longuement sur la bulle rouge
```

**5. Dialog de retry apparaît**
```
✅ Icône error en haut
✅ Titre "Message non envoyé"
✅ Texte "Voulez-vous réessayer..."
✅ 2 boutons: "Annuler" et "Réessayer"
✅ Design moderne avec gradient
```

**6. Réactiver internet**
```
→ Désactiver le mode avion
→ Attendre la reconnexion (1-2s)
```

**7. Cliquer sur "Réessayer"**
```
→ Cliquer le bouton bleu "Réessayer"
```

**8. Vérifier le renvoi**
```
✅ Dialog se ferme
✅ Message échoué disparaît de la liste
✅ Nouveau message envoyé avec succès
✅ Bulle redevient bleue
✅ Statut "sent" → "delivered"
```

### Résultat Attendu

```
✅ Message échoué clairement visible (rouge)
✅ Dialog de retry moderne et intuitif
✅ Renvoi réussi après reconnexion
✅ UX fluide sans perte de message
```

---

## 🌟 Test 7: Animations et Transitions

### Éléments à Vérifier

**1. Entrance Animations (ChatView)**
```
→ Ouvrir ChatView avec plusieurs conversations
✅ Chaque card apparaît avec décalage (staggered)
✅ Animation: translation Y + fade in
✅ Duration: 300ms + (index * 100ms)
✅ Curve: easeOutCubic
✅ Effet fluide et élégant
```

**2. Message Entrance (ChatDetailView)**
```
→ Charger une conversation avec plusieurs messages
✅ Chaque bulle apparaît avec animation
✅ Translation Y + fade in
✅ Duration: 300ms + (index * 50ms)
✅ Derniers messages apparaissent en dernier
```

**3. Empty State Animation**
```
→ Ouvrir une conversation vide
✅ Icône de chat avec scale animation
✅ Duration: 600ms
✅ Curve: easeOut
✅ Effet de "pop" élégant
```

**4. Typing Dots Animation**
```
→ Observer l'indicateur de frappe
✅ 3 dots avec pulsation
✅ Chaque dot avec délai décalé
✅ Scale: 1.0 ↔ 1.8
✅ Duration: 1200ms par cycle
✅ Animation continue et fluide
```

**5. Online Indicator Glow**
```
→ Observer l'indicateur vert "online"
✅ Shadow bleue/verte autour du cercle
✅ BlurRadius: 6-8px
✅ SpreadRadius: 1-2px
✅ Effet de "glow" subtil
```

**6. Button Interactions**
```
→ Taper sur une conversation card
✅ Splash effect bleu clair
✅ Highlight subtil
✅ Feedback tactile immédiat
```

### Résultat Attendu

```
✅ Toutes les animations fluides à 60 FPS
✅ Pas de lag ni de freeze
✅ Transitions élégantes
✅ UX professionnelle et moderne
```

---

## 🎨 Test 8: Design et Couleurs

### Palette de Couleurs à Vérifier

**ChatView:**
```
✅ AppBar: Gradient bleu (#0277BD → #01579B)
✅ Background: Gris clair (#F5F5F5)
✅ Cards: Blanc avec shadow
✅ Cards non lues: Gradient bleu-orange subtil
✅ Badge non lu: Gradient bleu avec glow
✅ Online indicator: Vert (#4CAF50) avec glow
```

**ChatDetailView:**
```
✅ AppBar: Gradient bleu
✅ Background: Gris clair
✅ Bulles personnelles: Gradient bleu
✅ Bulles reçues: Blanc
✅ Typing indicator: Dots bleus
✅ Send button: Gradient bleu avec shadow
```

**États Spéciaux:**
```
✅ Messages échoués: Rouge clair avec border rouge
✅ Reconnexion banner: Gradient orange
✅ Empty state: Icône bleue sur gradient circulaire
```

### Vérifier la Cohérence

```
✅ Toutes les couleurs correspondent au thème
✅ Contraste suffisant pour la lisibilité
✅ Gradients harmonieux
✅ Shadows subtiles et élégantes
✅ Borders arrondis (border-radius: 20px en général)
```

---

## 📏 Test 9: Responsive et Adaptation

### Tailles d'Écran

**1. Smartphone (< 600px)**
```
✅ Cards de conversation: largeur pleine - 32px padding
✅ Bulles de message: max 75% de la largeur
✅ Texte lisible sans zoom
✅ Boutons suffisamment grands (44x44px minimum)
```

**2. Tablette (600-900px)**
```
✅ Layout s'adapte à la largeur
✅ Marges proportionnelles
✅ Taille de police identique
```

**3. Orientation Paysage**
```
✅ Pas de débordement
✅ AppBar s'adapte
✅ Input reste accessible
```

### Keyboards et SafeArea

```
✅ Input se déplace au-dessus du clavier
✅ Messages restent visibles
✅ SafeArea respectée (notches, bottom bar)
✅ Auto-scroll fonctionne avec clavier ouvert
```

---

## 🔍 Test 10: Edge Cases

### Cas Limites à Tester

**1. Conversation avec beaucoup de messages (> 50)**
```
→ Charger une conversation avec 100+ messages
✅ Scroll fluide
✅ Pagination fonctionne (si implémentée)
✅ Pas de lag
✅ Auto-scroll vers le bas au chargement
```

**2. Messages très longs**
```
→ Envoyer un message de 500+ caractères
✅ Bulle s'adapte en hauteur
✅ Texte wrappé correctement
✅ Pas de débordement
✅ Scroll possible dans la conversation
```

**3. Messages rapides (spam)**
```
→ Envoyer 10 messages en 5 secondes
✅ Tous les messages s'affichent
✅ Ordre correct (chronologique)
✅ Pas de doublons
✅ Statuts corrects
```

**4. Caractères spéciaux et emojis**
```
→ Envoyer: "Hello 👋 Comment ça va ? 😊 Test: <html> & \"quotes\""
✅ Emojis affichés correctement
✅ HTML escapé
✅ Quotes ne cassent pas le layout
```

**5. Connexion instable**
```
→ Activer/désactiver internet plusieurs fois
✅ Bannière de reconnexion s'affiche/disparaît
✅ Messages en attente sont envoyés après reconnexion
✅ Pas de crash
```

**6. Utilisateur bloqué/supprimé**
```
→ Si l'autre utilisateur est supprimé
✅ Message d'erreur clair
✅ Pas de crash
✅ Conversation reste visible
```

---

## 🐛 Checklist de Validation Finale

### Fonctionnel

- [ ] Création de conversation (recruteur only)
- [ ] Envoi de message
- [ ] Réception en temps réel (< 1s)
- [ ] Typing indicator fonctionnel
- [ ] Online status correct
- [ ] Statuts sent/delivered/read
- [ ] Message retry pour échecs
- [ ] Auto-scroll vers nouveaux messages
- [ ] Mark as read automatique
- [ ] Pull-to-refresh

### Design

- [ ] Gradients corrects (bleu, orange)
- [ ] Shadows subtiles
- [ ] Border-radius harmonieux (20px)
- [ ] Couleurs cohérentes avec la plateforme
- [ ] Contraste suffisant
- [ ] Icônes claires et intuitives

### Animations

- [ ] Entrance animations fluides (60 FPS)
- [ ] Typing dots animés
- [ ] Online indicator avec glow
- [ ] Button splash effects
- [ ] Empty state scale animation
- [ ] Staggered list animations

### Performance

- [ ] Chargement conversations < 1s
- [ ] Chargement messages < 500ms
- [ ] Envoi message < 200ms
- [ ] Pas de lag au scroll
- [ ] Animations à 60 FPS
- [ ] Memory stable (pas de leaks)

### UX

- [ ] Feedback visuel pour chaque action
- [ ] États de chargement clairs
- [ ] Messages d'erreur compréhensibles
- [ ] Retry intuitif
- [ ] Navigation fluide
- [ ] Back button fonctionne

---

## 📊 Rapport de Test (Template)

```markdown
# Rapport de Test - Chat Ultra-Moderne

**Date:** [JJ/MM/AAAA]
**Testeur:** [Nom]
**Appareils:** [Liste des appareils/émulateurs]

## Résumé

- Tests réussis: X/10
- Tests échoués: X/10
- Bugs trouvés: X

## Détails

### Test 1: Création de Conversation
- [ ] ✅ Réussi
- [ ] ❌ Échoué
- Notes: ...

### Test 2: Envoi de Message
- [ ] ✅ Réussi
- [ ] ❌ Échoué
- Notes: ...

[... pour chaque test ...]

## Bugs Identifiés

1. **[Titre du bug]**
   - Sévérité: Critique / Majeur / Mineur
   - Description: ...
   - Étapes pour reproduire: ...
   - Résultat attendu: ...
   - Résultat obtenu: ...

## Recommandations

- ...

## Captures d'Écran

[Joindre des screenshots si nécessaire]

## Conclusion

Le système de chat est [prêt pour production / nécessite des corrections / etc.]
```

---

## 🎯 Critères de Succès

Pour valider le système comme **prêt pour production**, il faut:

✅ **95%** des tests fonctionnels réussis
✅ **0** bug critique
✅ **< 3** bugs majeurs
✅ Animations fluides à **60 FPS**
✅ Temps de réponse **< 1s** pour toutes les actions
✅ **0** crash lors des tests

---

## 📞 Support

En cas de problème lors des tests:

**Logs Backend:**
```bash
tail -f storage/logs/laravel.log | grep -E '📋|💬|🔌'
```

**Logs Frontend:**
```bash
flutter run --verbose | grep -E '🔌|📨|📋|💬|⌨️|👤|✓'
```

**Redémarrer le système:**
```bash
# Backend
./stop-chat.sh
./start-chat.sh

# Frontend
flutter clean
flutter pub get
flutter run
```

---

**Bonne chance pour les tests ! 🚀**
