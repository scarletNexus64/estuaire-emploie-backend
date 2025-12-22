# 💬 Documentation API Chat WebSocket - Estuaire Emploi

## 📋 Table des matières
1. [Configuration](#configuration)
2. [Authentication](#authentication)
3. [API Endpoints](#api-endpoints)
4. [WebSocket Events](#websocket-events)
5. [Intégration Frontend](#intégration-frontend)
6. [Exemples de Code](#exemples-de-code)

---

## ⚙️ Configuration

### Variables d'environnement
```env
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=138993
REVERB_APP_KEY=3myoem0j3hfvp6l4kjwq
REVERB_APP_SECRET=rdr0rmymrbq0sbu7ynhc
REVERB_HOST=192.168.43.73
REVERB_PORT=8080
REVERB_SCHEME=http
```

### Serveur Reverb
Le serveur WebSocket Reverb est accessible à:
```
ws://192.168.43.73:8080/app/3myoem0j3hfvp6l4kjwq
```

---

## 🔐 Authentication

Toutes les routes API nécessitent une authentification via Laravel Sanctum.

**Headers requis:**
```http
Authorization: Bearer {votre_token}
Accept: application/json
Content-Type: application/json
```

---

## 🌐 API Endpoints

### 1. Liste des Conversations

**GET** `/api/conversations`

Récupère toutes les conversations de l'utilisateur authentifié (uniquement les candidatures acceptées).

**Réponse (200 OK):**
```json
[
  {
    "conversation_id": 1,
    "user": {
      "id": 5,
      "name": "John Doe",
      "profile_photo": "https://...",
      "is_online": true
    },
    "last_message": {
      "message": "Bonjour, comment allez-vous?",
      "status": "read",
      "sent_at": "2025-12-22 10:30:00"
    },
    "unread_count": 3
  }
]
```

---

### 2. Créer une Conversation

**POST** `/api/conversations`

Crée une nouvelle conversation pour une candidature acceptée.

**Body:**
```json
{
  "application_id": 15,
  "user_two": 8
}
```

**Réponse (201 Created):**
```json
{
  "conversation_id": 42,
  "message": "Conversation created successfully"
}
```

**Réponse si existe déjà (200 OK):**
```json
{
  "conversation_id": 42,
  "message": "Conversation already exists"
}
```

---

### 3. Récupérer les Messages

**GET** `/api/conversations/{conversationId}/messages`

Récupère tous les messages d'une conversation.

**Réponse (200 OK):**
```json
[
  {
    "id": 1,
    "conversation_id": 42,
    "sender_id": 5,
    "sender_name": "Vous",
    "sender_photo": "https://...",
    "message": "Bonjour!",
    "status": "read",
    "created_at": "2025-12-22 10:25:00",
    "updated_at": "2025-12-22 10:26:00"
  },
  {
    "id": 2,
    "conversation_id": 42,
    "sender_id": 8,
    "sender_name": "John Doe",
    "sender_photo": "https://...",
    "message": "Salut, comment vas-tu?",
    "status": "delivered",
    "created_at": "2025-12-22 10:30:00",
    "updated_at": "2025-12-22 10:30:15"
  }
]
```

---

### 4. Envoyer un Message

**POST** `/api/conversations/messages`

Envoie un nouveau message dans une conversation.

**Body:**
```json
{
  "conversation_id": 42,
  "message": "Merci pour votre message!"
}
```

**Réponse (201 Created):**
```json
{
  "id": 3,
  "conversation_id": 42,
  "sender_id": 5,
  "sender_name": "Alice Martin",
  "sender_photo": "https://...",
  "message": "Merci pour votre message!",
  "status": "delivered",
  "created_at": "2025-12-22 10:35:00",
  "updated_at": "2025-12-22 10:35:00"
}
```

> **Note:** Le statut sera automatiquement mis à `delivered` si le destinataire est en ligne.

---

### 5. Marquer les Messages comme Lus

**PUT** `/api/conversations/{conversationId}/read`

Marque tous les messages non lus d'une conversation comme lus.

**Réponse (200 OK):**
```json
{
  "success": true,
  "marked_as_read": 3
}
```

---

### 6. Indicateur de Saisie (Typing)

**POST** `/api/conversations/typing`

Indique que l'utilisateur est en train de taper un message.

**Body:**
```json
{
  "conversation_id": 42
}
```

**Réponse (200 OK):**
```json
{
  "success": true
}
```

---

### 7. Statut de Présence - En ligne

**POST** `/api/presence/online`

Marque l'utilisateur comme en ligne.

**Réponse (200 OK):**
```json
{
  "success": true,
  "status": "online"
}
```

---

### 8. Statut de Présence - Hors ligne

**POST** `/api/presence/offline`

Marque l'utilisateur comme hors ligne.

**Réponse (200 OK):**
```json
{
  "success": true,
  "status": "offline"
}
```

---

## 🔔 WebSocket Events

### Canaux Privés (Private Channels)

#### 1. Canal de Chat
**Canal:** `private-chat.{conversationId}`

**Événement:** `MessageSent`
```json
{
  "id": 3,
  "conversation_id": 42,
  "sender_id": 8,
  "sender_name": "John Doe",
  "message": "Nouveau message!",
  "status": "sent",
  "created_at": "2025-12-22 10:35:00",
  "updated_at": "2025-12-22 10:35:00"
}
```

**Événement:** `MessageStatusUpdated`
```json
{
  "message_id": 3,
  "status": "read"
}
```

#### 2. Canal de Typing
**Canal:** `private-typing.{conversationId}`

**Événement:** `TypingEvent`
```json
{
  "conversationId": 42,
  "userId": 8
}
```

### Canal Public

#### 3. Canal de Présence
**Canal:** `presence`

**Événement:** `PresenceEvent`
```json
{
  "userId": 8,
  "online": true
}
```

---

## 🎨 Intégration Frontend

### Installation Laravel Echo (JavaScript)

```bash
npm install --save laravel-echo pusher-js
```

### Configuration Laravel Echo

```javascript
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: '3myoem0j3hfvp6l4kjwq',
    wsHost: '192.168.43.73',
    wsPort: 8080,
    wssPort: 8080,
    forceTLS: false,
    enabledTransports: ['ws', 'wss'],
    authEndpoint: 'http://your-api-url/broadcasting/auth',
    auth: {
        headers: {
            'Authorization': `Bearer ${yourAuthToken}`,
            'Accept': 'application/json',
        }
    }
});
```

---

## 💻 Exemples de Code

### 1. Écouter les Nouveaux Messages

```javascript
// S'abonner au canal de conversation
const conversationId = 42;

Echo.private(`chat.${conversationId}`)
    .listen('MessageSent', (e) => {
        console.log('Nouveau message reçu:', e);
        // Ajouter le message à votre UI
        addMessageToUI(e);
    })
    .listen('MessageStatusUpdated', (e) => {
        console.log('Statut du message mis à jour:', e);
        // Mettre à jour le statut du message dans votre UI
        updateMessageStatus(e.message_id, e.status);
    });
```

### 2. Écouter l'Indicateur de Saisie

```javascript
Echo.private(`typing.${conversationId}`)
    .listen('TypingEvent', (e) => {
        console.log(`L'utilisateur ${e.userId} est en train de taper...`);
        // Afficher "En train d'écrire..."
        showTypingIndicator(e.userId);

        // Masquer après 3 secondes
        setTimeout(() => {
            hideTypingIndicator(e.userId);
        }, 3000);
    });
```

### 3. Écouter la Présence des Utilisateurs

```javascript
Echo.channel('presence')
    .listen('PresenceEvent', (e) => {
        console.log(`Utilisateur ${e.userId} est ${e.online ? 'en ligne' : 'hors ligne'}`);
        // Mettre à jour le statut de présence dans votre UI
        updateUserPresence(e.userId, e.online);
    });
```

### 4. Envoyer un Message

```javascript
async function sendMessage(conversationId, message) {
    try {
        const response = await fetch('http://your-api-url/api/conversations/messages', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${yourAuthToken}`,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                conversation_id: conversationId,
                message: message
            })
        });

        const data = await response.json();
        console.log('Message envoyé:', data);
        return data;
    } catch (error) {
        console.error('Erreur lors de l\'envoi:', error);
    }
}
```

### 5. Indiquer que l'Utilisateur Tape

```javascript
let typingTimeout;

function handleTyping(conversationId) {
    // Annuler le timeout précédent
    clearTimeout(typingTimeout);

    // Envoyer l'événement de typing
    fetch('http://your-api-url/api/conversations/typing', {
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${yourAuthToken}`,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ conversation_id: conversationId })
    });

    // Définir un nouveau timeout (l'utilisateur arrête de taper après 2 secondes)
    typingTimeout = setTimeout(() => {
        // Optionnel: envoyer un événement "stopped typing"
    }, 2000);
}

// Attacher à l'input de message
messageInput.addEventListener('input', () => {
    handleTyping(currentConversationId);
});
```

### 6. Marquer les Messages comme Lus

```javascript
async function markMessagesAsRead(conversationId) {
    try {
        const response = await fetch(`http://your-api-url/api/conversations/${conversationId}/read`, {
            method: 'PUT',
            headers: {
                'Authorization': `Bearer ${yourAuthToken}`,
                'Accept': 'application/json',
            }
        });

        const data = await response.json();
        console.log(`${data.marked_as_read} messages marqués comme lus`);
    } catch (error) {
        console.error('Erreur:', error);
    }
}

// Appeler quand l'utilisateur ouvre la conversation
markMessagesAsRead(conversationId);
```

### 7. Gérer la Présence de l'Utilisateur

```javascript
// Marquer comme en ligne au chargement de la page
async function setOnline() {
    await fetch('http://your-api-url/api/presence/online', {
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${yourAuthToken}`,
            'Accept': 'application/json',
        }
    });
}

// Marquer comme hors ligne avant de quitter
window.addEventListener('beforeunload', async () => {
    await fetch('http://your-api-url/api/presence/offline', {
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${yourAuthToken}`,
            'Accept': 'application/json',
        }
    });
});

// Appeler au chargement
setOnline();
```

### 8. Exemple Complet - Composant de Chat

```javascript
class ChatComponent {
    constructor(conversationId, authToken, apiUrl) {
        this.conversationId = conversationId;
        this.authToken = authToken;
        this.apiUrl = apiUrl;
        this.setupWebSocket();
        this.loadMessages();
        this.setOnline();
    }

    setupWebSocket() {
        // Écouter les nouveaux messages
        Echo.private(`chat.${this.conversationId}`)
            .listen('MessageSent', (e) => this.onMessageReceived(e))
            .listen('MessageStatusUpdated', (e) => this.onStatusUpdated(e));

        // Écouter l'indicateur de saisie
        Echo.private(`typing.${this.conversationId}`)
            .listen('TypingEvent', (e) => this.onUserTyping(e));

        // Écouter la présence
        Echo.channel('presence')
            .listen('PresenceEvent', (e) => this.onPresenceChange(e));
    }

    async loadMessages() {
        const response = await fetch(
            `${this.apiUrl}/api/conversations/${this.conversationId}/messages`,
            {
                headers: {
                    'Authorization': `Bearer ${this.authToken}`,
                    'Accept': 'application/json',
                }
            }
        );
        const messages = await response.json();
        this.displayMessages(messages);
        this.markAsRead();
    }

    async sendMessage(message) {
        const response = await fetch(`${this.apiUrl}/api/conversations/messages`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${this.authToken}`,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                conversation_id: this.conversationId,
                message: message
            })
        });
        return await response.json();
    }

    async markAsRead() {
        await fetch(`${this.apiUrl}/api/conversations/${this.conversationId}/read`, {
            method: 'PUT',
            headers: {
                'Authorization': `Bearer ${this.authToken}`,
                'Accept': 'application/json',
            }
        });
    }

    async setOnline() {
        await fetch(`${this.apiUrl}/api/presence/online`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${this.authToken}`,
                'Accept': 'application/json',
            }
        });
    }

    onMessageReceived(message) {
        console.log('Nouveau message:', message);
        this.addMessageToUI(message);
    }

    onStatusUpdated(data) {
        console.log('Statut mis à jour:', data);
        this.updateMessageStatus(data.message_id, data.status);
    }

    onUserTyping(data) {
        console.log('Utilisateur en train de taper:', data.userId);
        this.showTypingIndicator();
    }

    onPresenceChange(data) {
        console.log('Présence:', data);
        this.updateUserStatus(data.userId, data.online);
    }

    // Méthodes UI (à implémenter selon votre framework)
    displayMessages(messages) { /* ... */ }
    addMessageToUI(message) { /* ... */ }
    updateMessageStatus(messageId, status) { /* ... */ }
    showTypingIndicator() { /* ... */ }
    updateUserStatus(userId, isOnline) { /* ... */ }
}

// Utilisation
const chat = new ChatComponent(42, 'your-auth-token', 'http://your-api-url');
```

---

## 🎯 Statuts des Messages

| Statut | Description |
|--------|-------------|
| `sent` | Message envoyé au serveur |
| `delivered` | Message délivré (destinataire en ligne) |
| `read` | Message lu par le destinataire |

---

## 🛡️ Sécurité

1. **Authorization des Canaux Privés**: Tous les canaux privés vérifient que l'utilisateur fait partie de la conversation
2. **Validation des Données**: Toutes les requêtes sont validées côté serveur
3. **Authentication**: Tous les endpoints nécessitent un token Sanctum valide
4. **CORS**: Configurez correctement les origines autorisées dans `config/cors.php`

---

## 🐛 Débogage

### Vérifier la connexion Reverb
```bash
# Démarrer le serveur Reverb
php artisan reverb:start

# Vérifier les logs
tail -f storage/logs/laravel.log
```

### Tester la connexion WebSocket
```javascript
Echo.connector.pusher.connection.bind('connected', () => {
    console.log('✅ Connecté au serveur WebSocket');
});

Echo.connector.pusher.connection.bind('error', (err) => {
    console.error('❌ Erreur de connexion:', err);
});
```

---

## 📝 Notes Importantes

1. **Conversations Limitées**: Seules les candidatures avec le statut `accepted` peuvent avoir des conversations
2. **Unicité**: Une seule conversation par `application_id` entre deux utilisateurs
3. **Permissions**: Les utilisateurs ne peuvent accéder qu'aux conversations dont ils font partie
4. **Broadcast**: Les événements sont diffusés uniquement aux participants de la conversation

---

## 🚀 Prochaines Étapes

1. Implémenter l'upload de fichiers dans les messages
2. Ajouter les messages vocaux
3. Implémenter la recherche dans les messages
4. Ajouter la pagination pour les conversations avec beaucoup de messages
5. Notifications push pour les nouveaux messages

---

**Version:** 1.0.0
**Date:** 22 Décembre 2025
**Auteur:** Équipe Estuaire Emploi
