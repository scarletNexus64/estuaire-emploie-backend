#!/bin/bash

# Script de monitoring des queues Laravel/Redis en temps réel
# Usage: ./monitor-queues.sh [interval]
# Exemple: ./monitor-queues.sh 3  (rafraîchit toutes les 3 secondes)

# Couleurs
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
MAGENTA='\033[0;35m'
CYAN='\033[0;36m'
WHITE='\033[1;37m'
BOLD='\033[1m'
DIM='\033[2m'
NC='\033[0m' # No Color

# Intervalle de rafraîchissement (en secondes)
INTERVAL=${1:-5}

# Fonction pour gérer CTRL+C
cleanup() {
    echo ""
    echo -e "${GREEN}✨ Monitoring arrêté${NC}"
    exit 0
}
trap cleanup SIGINT SIGTERM

# Fonction pour afficher une barre de progression
progress_bar() {
    local percent=$1
    local width=40
    local filled=$((percent * width / 100))
    local empty=$((width - filled))

    printf "["
    printf "%${filled}s" | tr ' ' '█'
    printf "%${empty}s" | tr ' ' '░'
    printf "] %3d%%" "$percent"
}

# Boucle de monitoring
while true; do
    clear

    # Header avec timestamp
    echo -e "${BOLD}${WHITE}╔═══════════════════════════════════════════════════════════════════════════╗${NC}"
    echo -e "${BOLD}${WHITE}║${NC} ${CYAN}🚀 Laravel Queue Dashboard${NC}                     ${DIM}$(date '+%Y-%m-%d %H:%M:%S')${NC} ${BOLD}${WHITE}║${NC}"
    echo -e "${BOLD}${WHITE}╚═══════════════════════════════════════════════════════════════════════════╝${NC}"
    echo ""

    # ═══════════════════════════════════════════════════════════════
    # Section 1: État des Queues Redis
    # ═══════════════════════════════════════════════════════════════
    echo -e "${BOLD}${YELLOW}📊 ÉTAT DES QUEUES${NC}"
    echo -e "${DIM}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"

    # Queue notifications
    NOTIF_COUNT=$(redis-cli LLEN queues:notifications 2>/dev/null || echo "0")
    NOTIF_RESERVED=$(redis-cli ZCARD queues:notifications:reserved 2>/dev/null || echo "0")
    if [ "$NOTIF_COUNT" -gt 0 ]; then
        echo -e "  ${MAGENTA}📢 notifications:${NC} ${RED}${BOLD}$NOTIF_COUNT${NC} jobs en attente  ${BLUE}($NOTIF_RESERVED en cours)${NC}"
    else
        echo -e "  ${MAGENTA}📢 notifications:${NC} ${GREEN}✓ Vide${NC}  ${DIM}($NOTIF_RESERVED en cours)${NC}"
    fi

    # Queue default
    DEFAULT_COUNT=$(redis-cli LLEN queues:default 2>/dev/null || echo "0")
    DEFAULT_RESERVED=$(redis-cli ZCARD queues:default:reserved 2>/dev/null || echo "0")
    if [ "$DEFAULT_COUNT" -gt 0 ]; then
        echo -e "  ${MAGENTA}📋 default:${NC}       ${RED}${BOLD}$DEFAULT_COUNT${NC} jobs en attente  ${BLUE}($DEFAULT_RESERVED en cours)${NC}"
    else
        echo -e "  ${MAGENTA}📋 default:${NC}       ${GREEN}✓ Vide${NC}  ${DIM}($DEFAULT_RESERVED en cours)${NC}"
    fi

    # Jobs delayed
    DELAYED_COUNT=$(redis-cli ZCARD queues:default:delayed 2>/dev/null || echo "0")
    if [ "$DELAYED_COUNT" -gt 0 ]; then
        echo -e "  ${MAGENTA}⏰ delayed:${NC}       ${YELLOW}$DELAYED_COUNT jobs planifiés${NC}"
    fi

    # Total
    TOTAL_PENDING=$((NOTIF_COUNT + DEFAULT_COUNT))
    TOTAL_RUNNING=$((NOTIF_RESERVED + DEFAULT_RESERVED))
    TOTAL=$((TOTAL_PENDING + TOTAL_RUNNING))
    echo ""
    echo -e "  ${BOLD}Total:${NC} $TOTAL jobs  ${DIM}($TOTAL_PENDING en attente + $TOTAL_RUNNING en cours)${NC}"

    echo ""

    # ═══════════════════════════════════════════════════════════════
    # Section 2: Workers Actifs
    # ═══════════════════════════════════════════════════════════════
    echo -e "${BOLD}${BLUE}👷 WORKERS ACTIFS${NC}"
    echo -e "${DIM}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"

    WORKERS=$(ps aux | grep "queue:work" | grep -v grep)
    WORKER_COUNT=$(echo "$WORKERS" | grep -c "queue:work" 2>/dev/null || echo "0")

    if [ "$WORKER_COUNT" -gt 0 ]; then
        echo -e "  ${GREEN}✓ $WORKER_COUNT worker(s) en exécution${NC}"
        echo ""

        # Compter par queue
        NOTIF_WORKERS=$(echo "$WORKERS" | grep -c "notifications" || echo "0")
        DEFAULT_WORKERS=$(echo "$WORKERS" | grep -c "default" || echo "0")

        echo "$WORKERS" | while read -r line; do
            PID=$(echo "$line" | awk '{print $2}')
            CPU=$(echo "$line" | awk '{print $3}')
            MEM=$(echo "$line" | awk '{print $4}')
            TIME=$(echo "$line" | awk '{print $10}')
            QUEUE=$(echo "$line" | grep -o "queue=[^ ]*" | cut -d= -f2 || echo "default")

            # Couleur selon la queue
            if [[ "$QUEUE" == "notifications" ]]; then
                QUEUE_COLOR="${MAGENTA}"
            else
                QUEUE_COLOR="${CYAN}"
            fi

            echo -e "  ${DIM}PID ${WHITE}$PID${NC}  ${QUEUE_COLOR}[$QUEUE]${NC}  ${DIM}CPU: ${CPU}%  MEM: ${MEM}%  TIME: ${TIME}${NC}"
        done

        echo ""
        echo -e "  ${MAGENTA}📢 Notifications:${NC} $NOTIF_WORKERS worker(s)   ${CYAN}📋 Default:${NC} $DEFAULT_WORKERS worker(s)"
    else
        echo -e "  ${RED}✗ Aucun worker actif${NC}"
        echo -e "  ${YELLOW}💡 Lancez ./start-workers.sh pour démarrer les workers${NC}"
    fi

    echo ""

    # ═══════════════════════════════════════════════════════════════
    # Section 3: Jobs Échoués
    # ═══════════════════════════════════════════════════════════════
    echo -e "${BOLD}${RED}❌ JOBS ÉCHOUÉS${NC}"
    echo -e "${DIM}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"

    FAILED_OUTPUT=$(php artisan queue:failed 2>/dev/null | tail -n +2)

    if [ -z "$FAILED_OUTPUT" ]; then
        echo -e "  ${GREEN}✓ Aucun job échoué${NC}"
    else
        FAILED_COUNT=$(echo "$FAILED_OUTPUT" | wc -l | xargs)
        echo -e "  ${RED}⚠ $FAILED_COUNT job(s) échoué(s)${NC}"
        echo ""

        # Afficher les 3 premiers jobs failed
        echo "$FAILED_OUTPUT" | head -n 3 | while read -r line; do
            echo -e "  ${DIM}$line${NC}"
        done

        if [ "$FAILED_COUNT" -gt 3 ]; then
            echo -e "  ${DIM}... et $((FAILED_COUNT - 3)) autre(s)${NC}"
        fi

        echo ""
        echo -e "  ${YELLOW}💡 Commandes utiles:${NC}"
        echo -e "     ${DIM}• Voir les détails: ${WHITE}php artisan queue:failed${NC}"
        echo -e "     ${DIM}• Retry tous: ${WHITE}php artisan queue:retry all${NC}"
        echo -e "     ${DIM}• Supprimer tous: ${WHITE}php artisan queue:flush${NC}"
    fi

    echo ""

    # ═══════════════════════════════════════════════════════════════
    # Section 4: Statistiques Redis
    # ═══════════════════════════════════════════════════════════════
    echo -e "${BOLD}${CYAN}📈 STATISTIQUES REDIS${NC}"
    echo -e "${DIM}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"

    # Mémoire
    REDIS_MEMORY=$(redis-cli INFO memory 2>/dev/null | grep "used_memory_human:" | cut -d: -f2 | tr -d '\r')
    REDIS_MEMORY_PEAK=$(redis-cli INFO memory 2>/dev/null | grep "used_memory_peak_human:" | cut -d: -f2 | tr -d '\r')
    REDIS_MEMORY_PERCENT=$(redis-cli INFO memory 2>/dev/null | grep "used_memory_rss:" | cut -d: -f2 | tr -d '\r' | awk '{print int($1/1024/1024)}')

    echo -e "  ${MAGENTA}💾 Mémoire:${NC} $REDIS_MEMORY ${DIM}(pic: $REDIS_MEMORY_PEAK)${NC}"

    # Connexions
    REDIS_CLIENTS=$(redis-cli INFO clients 2>/dev/null | grep "connected_clients:" | cut -d: -f2 | tr -d '\r')
    echo -e "  ${MAGENTA}🔌 Connexions:${NC} $REDIS_CLIENTS clients actifs"

    # Commandes
    REDIS_COMMANDS=$(redis-cli INFO stats 2>/dev/null | grep "total_commands_processed:" | cut -d: -f2 | tr -d '\r')
    REDIS_OPS=$(redis-cli INFO stats 2>/dev/null | grep "instantaneous_ops_per_sec:" | cut -d: -f2 | tr -d '\r')
    echo -e "  ${MAGENTA}⚡ Commandes:${NC} $REDIS_COMMANDS total  ${DIM}($REDIS_OPS ops/sec)${NC}"

    # Uptime
    REDIS_UPTIME=$(redis-cli INFO server 2>/dev/null | grep "uptime_in_days:" | cut -d: -f2 | tr -d '\r')
    echo -e "  ${MAGENTA}⏱️  Uptime:${NC} $REDIS_UPTIME jours"

    echo ""

    # ═══════════════════════════════════════════════════════════════
    # Section 5: Logs Récents
    # ═══════════════════════════════════════════════════════════════
    echo -e "${BOLD}${YELLOW}📝 LOGS RÉCENTS${NC}"
    echo -e "${DIM}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"

    RECENT_LOGS=$(tail -n 50 storage/logs/laravel.log 2>/dev/null | grep -E "\[JOB|\[COMPANY|\[BATCH|\[NOTIFICATION" | tail -n 5)

    if [ -z "$RECENT_LOGS" ]; then
        echo -e "  ${DIM}Aucun log récent${NC}"
    else
        echo "$RECENT_LOGS" | while read -r line; do
            # Colorier selon le type
            if echo "$line" | grep -q "✅"; then
                echo -e "  ${GREEN}$line${NC}"
            elif echo "$line" | grep -q "❌"; then
                echo -e "  ${RED}$line${NC}"
            elif echo "$line" | grep -q "📢"; then
                echo -e "  ${BLUE}$line${NC}"
            else
                echo -e "  ${DIM}$line${NC}"
            fi
        done
    fi

    echo ""

    # ═══════════════════════════════════════════════════════════════
    # Section 6: Performance Metrics (si workers actifs)
    # ═══════════════════════════════════════════════════════════════
    if [ "$WORKER_COUNT" -gt 0 ] && [ "$TOTAL_RUNNING" -gt 0 ]; then
        echo -e "${BOLD}${GREEN}⚡ PERFORMANCE${NC}"
        echo -e "${DIM}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"

        # Calculer un taux d'utilisation approximatif
        if [ "$WORKER_COUNT" -gt 0 ]; then
            USAGE=$((TOTAL_RUNNING * 100 / WORKER_COUNT))
            if [ "$USAGE" -gt 100 ]; then
                USAGE=100
            fi
        else
            USAGE=0
        fi

        echo -n "  ${MAGENTA}Utilisation des workers:${NC} "
        progress_bar "$USAGE"
        echo ""

        # Calculer le throughput (jobs traités par minute)
        # On suppose 1 job = 1-2 secondes en moyenne
        if [ "$TOTAL_RUNNING" -gt 0 ]; then
            THROUGHPUT=$((TOTAL_RUNNING * 30))
            echo -e "  ${MAGENTA}Throughput estimé:${NC} ~$THROUGHPUT jobs/min"
        fi

        echo ""
    fi

    # ═══════════════════════════════════════════════════════════════
    # Footer
    # ═══════════════════════════════════════════════════════════════
    echo -e "${DIM}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
    echo -e "${DIM}Rafraîchissement dans ${INTERVAL}s... (CTRL+C pour quitter)${NC}"

    # Afficher des alertes si nécessaire
    if [ "$WORKER_COUNT" -eq 0 ] && [ "$TOTAL" -gt 0 ]; then
        echo ""
        echo -e "${BOLD}${RED}⚠️  ALERTE: $TOTAL jobs en attente mais aucun worker actif!${NC}"
        echo -e "${YELLOW}   Lancez ./start-workers.sh pour démarrer les workers${NC}"
    fi

    if [ "$FAILED_COUNT" -gt 10 ]; then
        echo ""
        echo -e "${BOLD}${RED}⚠️  ALERTE: Trop de jobs échoués ($FAILED_COUNT)${NC}"
        echo -e "${YELLOW}   Vérifiez les logs et retentez: php artisan queue:retry all${NC}"
    fi

    # Attendre avant le prochain rafraîchissement
    sleep "$INTERVAL"
done
