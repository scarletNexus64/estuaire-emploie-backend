#!/bin/bash

# ============================================
# Script d'Arrêt du Chat - Estuaire Emploi
# ============================================

echo "🛑 Arrêt du système de chat..."
echo ""

# Couleurs
GREEN='\033[0;32m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# 1. Arrêter Reverb
if pgrep -f "reverb:start" > /dev/null; then
    pkill -f "reverb:start"
    echo "${GREEN}✅ Reverb arrêté${NC}"
else
    echo "ℹ️  Reverb n'était pas actif"
fi

# 2. Arrêter Queue Worker
if pgrep -f "queue:work" > /dev/null; then
    pkill -f "queue:work"
    echo "${GREEN}✅ Queue worker arrêté${NC}"
else
    echo "ℹ️  Queue worker n'était pas actif"
fi

# 3. Libérer le port 8080
if lsof -ti:8080 > /dev/null 2>&1; then
    lsof -ti:8080 | xargs kill -9 2>/dev/null
    echo "${GREEN}✅ Port 8080 libéré${NC}"
else
    echo "ℹ️  Port 8080 déjà libre"
fi

echo ""
echo "✅ Tous les services du chat sont arrêtés"
