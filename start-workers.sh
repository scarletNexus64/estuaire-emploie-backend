#!/bin/bash

# Script pour démarrer plusieurs workers en parallèle avec monitoring
# Usage: ./start-workers.sh

# Couleurs
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
MAGENTA='\033[0;35m'
CYAN='\033[0;36m'
BOLD='\033[1m'
NC='\033[0m' # No Color

# Fonction pour afficher un header
print_header() {
    echo ""
    echo -e "${CYAN}═══════════════════════════════════════════════════════════${NC}"
    echo -e "${BOLD}$1${NC}"
    echo -e "${CYAN}═══════════════════════════════════════════════════════════${NC}"
    echo ""
}

# Fonction pour afficher l'état des queues
show_queue_status() {
    echo -e "${YELLOW}📊 État des queues Redis:${NC}"
    echo ""

    # Queue notifications
    NOTIF_COUNT=$(redis-cli LLEN queues:notifications 2>/dev/null || echo "0")
    if [ "$NOTIF_COUNT" -gt 0 ]; then
        echo -e "  ${MAGENTA}notifications:${NC} ${RED}$NOTIF_COUNT jobs en attente${NC}"
    else
        echo -e "  ${MAGENTA}notifications:${NC} ${GREEN}$NOTIF_COUNT jobs (vide)${NC}"
    fi

    # Queue default
    DEFAULT_COUNT=$(redis-cli LLEN queues:default 2>/dev/null || echo "0")
    if [ "$DEFAULT_COUNT" -gt 0 ]; then
        echo -e "  ${MAGENTA}default:${NC} ${RED}$DEFAULT_COUNT jobs en attente${NC}"
    else
        echo -e "  ${MAGENTA}default:${NC} ${GREEN}$DEFAULT_COUNT jobs (vide)${NC}"
    fi

    # Jobs delayed
    DELAYED_COUNT=$(redis-cli ZCARD queues:default:delayed 2>/dev/null || echo "0")
    if [ "$DELAYED_COUNT" -gt 0 ]; then
        echo -e "  ${MAGENTA}delayed:${NC} ${YELLOW}$DELAYED_COUNT jobs${NC}"
    fi

    # Jobs reserved
    RESERVED_NOTIF=$(redis-cli ZCARD queues:notifications:reserved 2>/dev/null || echo "0")
    RESERVED_DEFAULT=$(redis-cli ZCARD queues:default:reserved 2>/dev/null || echo "0")
    RESERVED_TOTAL=$((RESERVED_NOTIF + RESERVED_DEFAULT))
    if [ "$RESERVED_TOTAL" -gt 0 ]; then
        echo -e "  ${MAGENTA}reserved:${NC} ${BLUE}$RESERVED_TOTAL jobs (en cours)${NC}"
    fi

    echo ""
}

# Fonction pour afficher les jobs failed
show_failed_jobs() {
    echo -e "${RED}❌ Jobs échoués:${NC}"
    FAILED_OUTPUT=$(php artisan queue:failed 2>/dev/null | grep -v "No failed jobs" | tail -n +2)

    if [ -z "$FAILED_OUTPUT" ]; then
        echo -e "  ${GREEN}✓ Aucun job échoué${NC}"
    else
        FAILED_COUNT=$(echo "$FAILED_OUTPUT" | wc -l | xargs)
        echo -e "  ${RED}⚠ $FAILED_COUNT job(s) échoué(s)${NC}"
        echo ""
        echo "$FAILED_OUTPUT" | head -n 5
        if [ "$FAILED_COUNT" -gt 5 ]; then
            echo -e "\n  ${YELLOW}... et $((FAILED_COUNT - 5)) autre(s)${NC}"
        fi
    fi
    echo ""
}

# Fonction pour afficher les workers actifs
show_active_workers() {
    echo -e "${BLUE}👷 Workers actifs:${NC}"
    WORKERS=$(ps aux | grep "queue:work" | grep -v grep | wc -l | xargs)

    if [ "$WORKERS" -gt 0 ]; then
        echo -e "  ${GREEN}✓ $WORKERS worker(s) en cours d'exécution${NC}"
        echo ""
        ps aux | grep "queue:work" | grep -v grep | awk '{print "  PID " $2 ": " $11 " " $12 " " $13 " " $14 " " $15}'
    else
        echo -e "  ${YELLOW}⚠ Aucun worker actif${NC}"
    fi
    echo ""
}

# Fonction pour afficher les statistiques Redis
show_redis_stats() {
    echo -e "${CYAN}📈 Statistiques Redis:${NC}"

    # Mémoire utilisée
    REDIS_MEMORY=$(redis-cli INFO memory 2>/dev/null | grep "used_memory_human:" | cut -d: -f2 | tr -d '\r')
    echo -e "  ${MAGENTA}Mémoire utilisée:${NC} $REDIS_MEMORY"

    # Connexions
    REDIS_CLIENTS=$(redis-cli INFO clients 2>/dev/null | grep "connected_clients:" | cut -d: -f2 | tr -d '\r')
    echo -e "  ${MAGENTA}Connexions:${NC} $REDIS_CLIENTS clients"

    # Total commandes
    REDIS_COMMANDS=$(redis-cli INFO stats 2>/dev/null | grep "total_commands_processed:" | cut -d: -f2 | tr -d '\r')
    echo -e "  ${MAGENTA}Commandes traitées:${NC} $REDIS_COMMANDS"

    echo ""
}

# Fonction pour le monitoring en temps réel
start_monitoring() {
    print_header "🔍 Monitoring en temps réel (CTRL+C pour arrêter)"

    while true; do
        clear
        echo -e "${BOLD}${CYAN}🚀 Laravel Queue Monitor - $(date '+%Y-%m-%d %H:%M:%S')${NC}"
        echo ""

        show_queue_status
        show_active_workers
        show_failed_jobs
        show_redis_stats

        echo -e "${YELLOW}📝 Logs récents (5 dernières secondes):${NC}"
        tail -n 20 storage/logs/laravel.log 2>/dev/null | grep -E "JOB|NOTIFICATION|COMPANY|BATCH" | tail -n 5
        echo ""

        echo -e "${CYAN}Rafraîchissement dans 5 secondes...${NC}"
        sleep 5
    done
}

# Fonction pour gérer l'arrêt propre
cleanup() {
    echo ""
    echo -e "${YELLOW}⚠️  Arrêt des workers...${NC}"

    # Tuer tous les processus queue:work
    pkill -f "queue:work" 2>/dev/null

    # Attendre que tous les processus se terminent
    sleep 2

    echo ""
    show_queue_status
    echo -e "${GREEN}✅ Tous les workers ont été arrêtés${NC}"
    echo ""
    exit 0
}

# Capturer CTRL+C
trap cleanup SIGINT SIGTERM

# Vérifier que Redis est démarré
echo -e "${CYAN}🔍 Vérification de Redis...${NC}"
if ! redis-cli ping > /dev/null 2>&1; then
    echo -e "${RED}❌ Erreur: Redis n'est pas démarré!${NC}"
    echo ""
    echo "Veuillez démarrer Redis:"
    echo "  macOS:  brew services start redis"
    echo "  Linux:  sudo service redis start"
    echo ""
    exit 1
fi
echo -e "${GREEN}✓ Redis est actif${NC}"
echo ""

# Vérifier la configuration Laravel
echo -e "${CYAN}🔍 Vérification de la configuration Laravel...${NC}"
QUEUE_CONNECTION=$(php artisan tinker --execute="echo config('queue.default');" 2>/dev/null)
if [ "$QUEUE_CONNECTION" != "redis" ]; then
    echo -e "${YELLOW}⚠ Warning: QUEUE_CONNECTION n'est pas 'redis' (actuellement: $QUEUE_CONNECTION)${NC}"
fi
echo -e "${GREEN}✓ Configuration OK${NC}"
echo ""

# Afficher l'état actuel
print_header "📊 État actuel du système"
show_queue_status
show_active_workers
show_failed_jobs

# Menu principal
print_header "🚀 Démarrage des Workers Laravel"
echo "Choisissez une option:"
echo ""
echo -e "  ${GREEN}1)${NC} Démarrer les workers (mode standard)"
echo -e "  ${GREEN}2)${NC} Démarrer les workers + monitoring temps réel"
echo -e "  ${GREEN}3)${NC} Monitoring seulement (sans démarrer de workers)"
echo -e "  ${GREEN}4)${NC} Afficher l'état et quitter"
echo -e "  ${GREEN}5)${NC} Nettoyer les queues (flush)"
echo -e "  ${RED}0)${NC} Quitter"
echo ""
echo -n "Votre choix: "
read choice

case $choice in
    1)
        # Mode standard
        print_header "🚀 Démarrage des workers"

        echo -e "${BLUE}📢 Démarrage worker notifications #1...${NC}"
        php artisan queue:work redis --queue=notifications --tries=3 --timeout=300 --verbose > storage/logs/worker-notif-1.log 2>&1 &

        echo -e "${BLUE}📢 Démarrage worker notifications #2...${NC}"
        php artisan queue:work redis --queue=notifications --tries=3 --timeout=300 --verbose > storage/logs/worker-notif-2.log 2>&1 &

        echo -e "${BLUE}📋 Démarrage worker default...${NC}"
        php artisan queue:work redis --queue=default --tries=3 --timeout=120 --verbose > storage/logs/worker-default.log 2>&1 &

        sleep 2
        echo ""
        echo -e "${GREEN}✅ 3 workers démarrés${NC}"
        echo -e "   ${MAGENTA}•${NC} 2 workers pour la queue 'notifications'"
        echo -e "   ${MAGENTA}•${NC} 1 worker pour la queue 'default'"
        echo ""
        echo -e "${YELLOW}📝 Logs disponibles dans:${NC}"
        echo -e "   ${MAGENTA}•${NC} storage/logs/worker-notif-1.log"
        echo -e "   ${MAGENTA}•${NC} storage/logs/worker-notif-2.log"
        echo -e "   ${MAGENTA}•${NC} storage/logs/worker-default.log"
        echo ""
        echo -e "${CYAN}💡 Commandes utiles:${NC}"
        echo -e "   ${MAGENTA}•${NC} Voir les logs: tail -f storage/logs/worker-*.log"
        echo -e "   ${MAGENTA}•${NC} Monitoring: ./monitor-queues.sh"
        echo -e "   ${MAGENTA}•${NC} Arrêter: pkill -f 'queue:work'"
        echo ""
        echo -e "${BOLD}${GREEN}Appuyez sur CTRL+C pour arrêter tous les workers${NC}"
        echo ""

        # Attendre
        wait
        ;;

    2)
        # Mode avec monitoring
        print_header "🚀 Démarrage des workers + monitoring"

        echo -e "${BLUE}📢 Démarrage worker notifications #1...${NC}"
        php artisan queue:work redis --queue=notifications --tries=3 --timeout=300 > storage/logs/worker-notif-1.log 2>&1 &

        echo -e "${BLUE}📢 Démarrage worker notifications #2...${NC}"
        php artisan queue:work redis --queue=notifications --tries=3 --timeout=300 > storage/logs/worker-notif-2.log 2>&1 &

        echo -e "${BLUE}📋 Démarrage worker default...${NC}"
        php artisan queue:work redis --queue=default --tries=3 --timeout=120 > storage/logs/worker-default.log 2>&1 &

        sleep 2
        echo -e "${GREEN}✅ Workers démarrés${NC}"
        sleep 1

        # Démarrer le monitoring
        start_monitoring
        ;;

    3)
        # Monitoring seulement
        start_monitoring
        ;;

    4)
        # Afficher l'état et quitter
        print_header "📊 État du système"
        show_queue_status
        show_active_workers
        show_failed_jobs
        show_redis_stats
        exit 0
        ;;

    5)
        # Nettoyer les queues
        print_header "🗑️  Nettoyage des queues"
        echo -e "${YELLOW}⚠️  Attention: Cela va supprimer tous les jobs en attente!${NC}"
        echo -n "Êtes-vous sûr? (oui/non): "
        read confirm

        if [ "$confirm" = "oui" ]; then
            echo ""
            echo -e "${CYAN}Suppression des jobs en attente...${NC}"
            redis-cli DEL queues:notifications > /dev/null 2>&1
            redis-cli DEL queues:default > /dev/null 2>&1
            redis-cli DEL queues:notifications:delayed > /dev/null 2>&1
            redis-cli DEL queues:default:delayed > /dev/null 2>&1
            redis-cli DEL queues:notifications:reserved > /dev/null 2>&1
            redis-cli DEL queues:default:reserved > /dev/null 2>&1

            echo -e "${GREEN}✓ Queues vidées${NC}"
            echo ""
            show_queue_status
        else
            echo -e "${YELLOW}Annulé${NC}"
        fi
        exit 0
        ;;

    0)
        echo -e "${YELLOW}Au revoir!${NC}"
        exit 0
        ;;

    *)
        echo -e "${RED}Option invalide${NC}"
        exit 1
        ;;
esac
