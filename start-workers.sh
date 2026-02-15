#!/bin/bash

# Script pour démarrer les workers de queue Laravel avec Redis
# Usage: ./start-workers.sh

# Couleurs
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
MAGENTA='\033[0;35m'
CYAN='\033[0;36m'
BOLD='\033[1m'
NC='\033[0m'

WORKDIR="/var/www/clients/client1/web19/web/estuaire-emploie-backend"

print_header() {
    echo ""
    echo -e "${CYAN}═══════════════════════════════════════════════════════════${NC}"
    echo -e "${BOLD}$1${NC}"
    echo -e "${CYAN}═══════════════════════════════════════════════════════════${NC}"
    echo ""
}

show_queue_status() {
    echo -e "${YELLOW}📊 État des queues Redis:${NC}"
    echo ""

    NOTIF_COUNT=$(redis-cli LLEN queues:notifications 2>/dev/null || echo "0")
    if [ "$NOTIF_COUNT" -gt 0 ] 2>/dev/null; then
        echo -e "  ${MAGENTA}notifications:${NC} ${RED}$NOTIF_COUNT jobs en attente${NC}"
    else
        echo -e "  ${MAGENTA}notifications:${NC} ${GREEN}$NOTIF_COUNT jobs (vide)${NC}"
    fi

    DEFAULT_COUNT=$(redis-cli LLEN queues:default 2>/dev/null || echo "0")
    if [ "$DEFAULT_COUNT" -gt 0 ] 2>/dev/null; then
        echo -e "  ${MAGENTA}default:${NC} ${RED}$DEFAULT_COUNT jobs en attente${NC}"
    else
        echo -e "  ${MAGENTA}default:${NC} ${GREEN}$DEFAULT_COUNT jobs (vide)${NC}"
    fi

    DELAYED_NOTIF=$(redis-cli ZCARD queues:notifications:delayed 2>/dev/null || echo "0")
    DELAYED_DEFAULT=$(redis-cli ZCARD queues:default:delayed 2>/dev/null || echo "0")
    DELAYED_TOTAL=$((DELAYED_NOTIF + DELAYED_DEFAULT))
    if [ "$DELAYED_TOTAL" -gt 0 ] 2>/dev/null; then
        echo -e "  ${MAGENTA}delayed:${NC} ${YELLOW}$DELAYED_TOTAL jobs${NC}"
    fi

    RESERVED_NOTIF=$(redis-cli ZCARD queues:notifications:reserved 2>/dev/null || echo "0")
    RESERVED_DEFAULT=$(redis-cli ZCARD queues:default:reserved 2>/dev/null || echo "0")
    RESERVED_TOTAL=$((RESERVED_NOTIF + RESERVED_DEFAULT))
    if [ "$RESERVED_TOTAL" -gt 0 ] 2>/dev/null; then
        echo -e "  ${MAGENTA}reserved:${NC} ${BLUE}$RESERVED_TOTAL jobs (en cours)${NC}"
    fi

    echo ""
}

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

show_failed_jobs() {
    echo -e "${RED}❌ Jobs échoués:${NC}"
    FAILED_OUTPUT=$(php artisan queue:failed 2>/dev/null | grep -v "No failed jobs" | tail -n +2)

    if [ -z "$FAILED_OUTPUT" ]; then
        echo -e "  ${GREEN}✓ Aucun job échoué${NC}"
    else
        FAILED_COUNT=$(echo "$FAILED_OUTPUT" | wc -l | xargs)
        echo -e "  ${RED}⚠ $FAILED_COUNT job(s) échoué(s)${NC}"
        echo "$FAILED_OUTPUT" | head -n 5
    fi
    echo ""
}

show_redis_stats() {
    echo -e "${CYAN}📈 Statistiques Redis:${NC}"
    REDIS_MEMORY=$(redis-cli INFO memory 2>/dev/null | grep "used_memory_human:" | cut -d: -f2 | tr -d '\r')
    echo -e "  ${MAGENTA}Mémoire:${NC} $REDIS_MEMORY"
    REDIS_CLIENTS=$(redis-cli INFO clients 2>/dev/null | grep "connected_clients:" | cut -d: -f2 | tr -d '\r')
    echo -e "  ${MAGENTA}Connexions:${NC} $REDIS_CLIENTS clients"
    echo ""
}

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
        echo -e "${YELLOW}📝 Logs récents:${NC}"
        tail -n 5 "$WORKDIR/storage/logs/queue.log" 2>/dev/null | tail -n 5
        echo ""
        echo -e "${CYAN}Rafraîchissement dans 5 secondes...${NC}"
        sleep 5
    done
}

cleanup() {
    echo ""
    echo -e "${YELLOW}⚠️  Arrêt des workers...${NC}"
    pkill -f "queue:work" 2>/dev/null
    sleep 2
    echo -e "${GREEN}✅ Tous les workers ont été arrêtés${NC}"
    echo ""
    exit 0
}

trap cleanup SIGINT SIGTERM

# Vérifier Redis
echo -e "${CYAN}🔍 Vérification de Redis...${NC}"
if ! redis-cli ping > /dev/null 2>&1; then
    echo -e "${RED}❌ Redis n'est pas démarré!${NC}"
    echo "  sudo service redis-server start"
    exit 1
fi
echo -e "${GREEN}✓ Redis est actif${NC}"
echo ""

# Afficher l'état actuel
print_header "📊 État actuel du système"
show_queue_status
show_active_workers

# Menu
print_header "🚀 Démarrage des Workers Laravel (Redis)"
echo "Choisissez une option:"
echo ""
echo -e "  ${GREEN}1)${NC} Démarrer les workers (3 workers optimisés)"
echo -e "  ${GREEN}2)${NC} Démarrer les workers + monitoring temps réel"
echo -e "  ${GREEN}3)${NC} Monitoring seulement"
echo -e "  ${GREEN}4)${NC} Afficher l'état et quitter"
echo -e "  ${GREEN}5)${NC} Nettoyer les queues (flush)"
echo -e "  ${GREEN}6)${NC} Arrêter tous les workers"
echo -e "  ${RED}0)${NC} Quitter"
echo ""
echo -n "Votre choix: "
read choice

case $choice in
    1)
        print_header "🚀 Démarrage des workers"

        # Worker 1: Notifications prioritaires (push + emails)
        echo -e "${BLUE}📢 Worker #1 - Notifications (prioritaire)...${NC}"
        php artisan queue:work redis --queue=notifications --tries=3 --timeout=600 --sleep=1 --max-jobs=500 --max-time=3600 > "$WORKDIR/storage/logs/worker-notif-1.log" 2>&1 &

        # Worker 2: Notifications secondaire
        echo -e "${BLUE}📢 Worker #2 - Notifications (secondaire)...${NC}"
        php artisan queue:work redis --queue=notifications --tries=3 --timeout=600 --sleep=2 --max-jobs=500 --max-time=3600 > "$WORKDIR/storage/logs/worker-notif-2.log" 2>&1 &

        # Worker 3: Queue default (emails, tâches générales)
        echo -e "${BLUE}📋 Worker #3 - Default (emails, tâches générales)...${NC}"
        php artisan queue:work redis --queue=default --tries=3 --timeout=300 --sleep=3 --max-jobs=1000 --max-time=3600 > "$WORKDIR/storage/logs/worker-default.log" 2>&1 &

        sleep 2
        echo ""
        echo -e "${GREEN}✅ 3 workers démarrés sur Redis${NC}"
        echo -e "   ${MAGENTA}•${NC} 2 workers pour 'notifications' (push Firebase + emails)"
        echo -e "   ${MAGENTA}•${NC} 1 worker pour 'default' (tâches générales)"
        echo ""
        echo -e "${YELLOW}📝 Logs:${NC}"
        echo -e "   tail -f $WORKDIR/storage/logs/worker-*.log"
        echo ""
        echo -e "${BOLD}${GREEN}CTRL+C pour arrêter tous les workers${NC}"
        echo ""
        wait
        ;;

    2)
        print_header "🚀 Démarrage des workers + monitoring"

        echo -e "${BLUE}📢 Worker #1 - Notifications...${NC}"
        php artisan queue:work redis --queue=notifications --tries=3 --timeout=600 --sleep=1 --max-jobs=500 --max-time=3600 > "$WORKDIR/storage/logs/worker-notif-1.log" 2>&1 &

        echo -e "${BLUE}📢 Worker #2 - Notifications...${NC}"
        php artisan queue:work redis --queue=notifications --tries=3 --timeout=600 --sleep=2 --max-jobs=500 --max-time=3600 > "$WORKDIR/storage/logs/worker-notif-2.log" 2>&1 &

        echo -e "${BLUE}📋 Worker #3 - Default...${NC}"
        php artisan queue:work redis --queue=default --tries=3 --timeout=300 --sleep=3 --max-jobs=1000 --max-time=3600 > "$WORKDIR/storage/logs/worker-default.log" 2>&1 &

        sleep 2
        echo -e "${GREEN}✅ Workers démarrés${NC}"
        sleep 1
        start_monitoring
        ;;

    3)
        start_monitoring
        ;;

    4)
        print_header "📊 État du système"
        show_queue_status
        show_active_workers
        show_failed_jobs
        show_redis_stats
        exit 0
        ;;

    5)
        print_header "🗑️  Nettoyage des queues Redis"
        echo -e "${YELLOW}⚠️  Cela va supprimer tous les jobs en attente!${NC}"
        echo -n "Êtes-vous sûr? (oui/non): "
        read confirm

        if [ "$confirm" = "oui" ]; then
            echo ""
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

    6)
        print_header "🛑 Arrêt des workers"
        pkill -f "queue:work" 2>/dev/null
        sleep 2
        show_active_workers
        echo -e "${GREEN}✅ Workers arrêtés${NC}"
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
