#!/bin/bash

# ============================================
# Script de Démarrage du Chat - Estuaire Emploi
# ============================================

echo "🚀 Démarrage du système de chat en temps réel..."
echo ""

# Couleurs
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# 1. Vérifier l'IP actuelle
echo "📡 Vérification de l'adresse IP..."
CURRENT_IP=$(hostname -I | awk '{print $1}')
echo "   IP actuelle : $CURRENT_IP"

# 2. Vérifier la config .env
ENV_IP=$(grep "^REVERB_HOST=" .env | cut -d'=' -f2)
echo "   IP dans .env : $ENV_IP"

if [ "$CURRENT_IP" != "$ENV_IP" ]; then
    echo "${YELLOW}⚠️  L'IP a changé ! Mise à jour de .env...${NC}"
    sed -i "s/REVERB_HOST=.*/REVERB_HOST=$CURRENT_IP/" .env
    echo "${GREEN}✅ .env mis à jour avec $CURRENT_IP${NC}"
else
    echo "${GREEN}✅ Configuration IP correcte${NC}"
fi

echo ""

# 3. Arrêter les processus existants
echo "🛑 Arrêt des processus existants..."

# Tuer Reverb
if pgrep -f "reverb:start" > /dev/null; then
    pkill -f "reverb:start"
    echo "   ✅ Reverb arrêté"
else
    echo "   ℹ️  Reverb n'était pas actif"
fi

# Tuer queue worker
if pgrep -f "queue:work" > /dev/null; then
    pkill -f "queue:work"
    echo "   ✅ Queue worker arrêté"
else
    echo "   ℹ️  Queue worker n'était pas actif"
fi

# Libérer le port 8080
if lsof -ti:8080 > /dev/null 2>&1; then
    lsof -ti:8080 | xargs kill -9 2>/dev/null
    echo "   ✅ Port 8080 libéré"
fi

sleep 2
echo ""

# 4. Démarrer Reverb
echo "🌐 Démarrage du serveur Reverb..."
nohup php artisan reverb:start > storage/logs/reverb.log 2>&1 &
REVERB_PID=$!
sleep 3

# Vérifier que Reverb a démarré
if ps -p $REVERB_PID > /dev/null; then
    echo "${GREEN}✅ Reverb démarré (PID: $REVERB_PID)${NC}"
    echo "   URL WebSocket : ws://$CURRENT_IP:8080/app/3myoem0j3hfvp6l4kjwq"

    # Afficher les dernières lignes du log
    echo ""
    echo "📋 Logs Reverb :"
    tail -5 storage/logs/reverb.log | sed 's/^/   /'
else
    echo "${RED}❌ Échec du démarrage de Reverb${NC}"
    echo "   Consultez : storage/logs/reverb.log"
    exit 1
fi

echo ""

# 5. Démarrer Queue Worker
echo "⚙️  Démarrage du queue worker..."
nohup php artisan queue:work > storage/logs/queue.log 2>&1 &
QUEUE_PID=$!
sleep 2

# Vérifier que le worker a démarré
if ps -p $QUEUE_PID > /dev/null; then
    echo "${GREEN}✅ Queue worker démarré (PID: $QUEUE_PID)${NC}"
else
    echo "${RED}❌ Échec du démarrage du queue worker${NC}"
    echo "   Consultez : storage/logs/queue.log"
    exit 1
fi

echo ""

# 6. Résumé
echo "════════════════════════════════════════════"
echo "✅ ${GREEN}CHAT SYSTÈME PRÊT !${NC}"
echo "════════════════════════════════════════════"
echo ""
echo "📊 Services actifs :"
echo "   • Reverb WebSocket : ws://$CURRENT_IP:8080"
echo "   • Queue Worker     : Actif (PID: $QUEUE_PID)"
echo ""
echo "📝 Commandes utiles :"
echo "   • Logs Reverb : tail -f storage/logs/reverb.log"
echo "   • Logs Queue  : tail -f storage/logs/queue.log"
echo "   • Logs Laravel: tail -f storage/logs/laravel.log"
echo "   • Arrêter tout: ./stop-chat.sh"
echo ""
echo "🔍 Vérifier les processus :"
echo "   ps aux | grep -E 'reverb|queue' | grep -v grep"
echo ""
echo "════════════════════════════════════════════"
