#!/bin/bash

# Script de commandes rapides pour gérer les queues Laravel/Redis
# Usage: ./queue-commands.sh [commande]

# Couleurs
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
MAGENTA='\033[0;35m'
CYAN='\033[0;36m'
BOLD='\033[1m'
NC='\033[0m' # No Color

# Fonction pour afficher le menu
show_menu() {
    clear
    echo -e "${CYAN}╔═══════════════════════════════════════════════════════════════╗${NC}"
    echo -e "${CYAN}║${NC}  ${BOLD}🛠️  Gestion des Queues Laravel${NC}                          ${CYAN}║${NC}"
    echo -e "${CYAN}╚═══════════════════════════════════════════════════════════════╝${NC}"
    echo ""
    echo -e "${YELLOW}MONITORING:${NC}"
    echo -e "  ${GREEN}1)${NC} Afficher l'état des queues"
    echo -e "  ${GREEN}2)${NC} Voir les jobs en attente"
    echo -e "  ${GREEN}3)${NC} Voir les jobs échoués"
    echo -e "  ${GREEN}4)${NC} Voir les workers actifs"
    echo -e "  ${GREEN}5)${NC} Dashboard en temps réel"
    echo ""
    echo -e "${YELLOW}ACTIONS:${NC}"
    echo -e "  ${GREEN}6)${NC} Retry tous les jobs échoués"
    echo -e "  ${GREEN}7)${NC} Retry un job spécifique"
    echo -e "  ${GREEN}8)${NC} Supprimer tous les jobs échoués"
    echo -e "  ${GREEN}9)${NC} Vider toutes les queues (DANGER)"
    echo ""
    echo -e "${YELLOW}WORKERS:${NC}"
    echo -e "  ${GREEN}10)${NC} Démarrer les workers"
    echo -e "  ${GREEN}11)${NC} Arrêter tous les workers"
    echo -e "  ${GREEN}12)${NC} Redémarrer les workers"
    echo ""
    echo -e "${YELLOW}REDIS:${NC}"
    echo -e "  ${GREEN}13)${NC} Stats Redis"
    echo -e "  ${GREEN}14)${NC} Test connexion Redis"
    echo -e "  ${GREEN}15)${NC} Vider le cache Redis"
    echo ""
    echo -e "  ${RED}0)${NC} Quitter"
    echo ""
    echo -n "Votre choix: "
}

# Fonction 1: Afficher l'état des queues
show_queue_status() {
    echo -e "${CYAN}📊 État des queues Redis:${NC}"
    echo ""

    NOTIF_COUNT=$(redis-cli LLEN queues:notifications 2>/dev/null || echo "0")
    NOTIF_RESERVED=$(redis-cli ZCARD queues:notifications:reserved 2>/dev/null || echo "0")
    echo -e "  ${MAGENTA}notifications:${NC} $NOTIF_COUNT en attente, $NOTIF_RESERVED en cours"

    DEFAULT_COUNT=$(redis-cli LLEN queues:default 2>/dev/null || echo "0")
    DEFAULT_RESERVED=$(redis-cli ZCARD queues:default:reserved 2>/dev/null || echo "0")
    echo -e "  ${MAGENTA}default:${NC}       $DEFAULT_COUNT en attente, $DEFAULT_RESERVED en cours"

    DELAYED_COUNT=$(redis-cli ZCARD queues:default:delayed 2>/dev/null || echo "0")
    echo -e "  ${MAGENTA}delayed:${NC}       $DELAYED_COUNT planifiés"

    echo ""
    read -p "Appuyez sur Entrée pour continuer..."
}

# Fonction 2: Voir les jobs en attente
show_pending_jobs() {
    echo -e "${CYAN}📋 Jobs en attente:${NC}"
    echo ""

    echo -e "${YELLOW}Queue notifications:${NC}"
    NOTIF_JOBS=$(redis-cli LRANGE queues:notifications 0 4 2>/dev/null)
    if [ -n "$NOTIF_JOBS" ]; then
        echo "$NOTIF_JOBS" | head -n 5
    else
        echo -e "${GREEN}Aucun job en attente${NC}"
    fi

    echo ""
    echo -e "${YELLOW}Queue default:${NC}"
    DEFAULT_JOBS=$(redis-cli LRANGE queues:default 0 4 2>/dev/null)
    if [ -n "$DEFAULT_JOBS" ]; then
        echo "$DEFAULT_JOBS" | head -n 5
    else
        echo -e "${GREEN}Aucun job en attente${NC}"
    fi

    echo ""
    read -p "Appuyez sur Entrée pour continuer..."
}

# Fonction 3: Voir les jobs échoués
show_failed_jobs() {
    echo -e "${RED}❌ Jobs échoués:${NC}"
    echo ""
    php artisan queue:failed
    echo ""
    read -p "Appuyez sur Entrée pour continuer..."
}

# Fonction 4: Voir les workers actifs
show_active_workers() {
    echo -e "${BLUE}👷 Workers actifs:${NC}"
    echo ""

    WORKERS=$(ps aux | grep "queue:work" | grep -v grep)
    if [ -n "$WORKERS" ]; then
        echo "$WORKERS"
        echo ""
        WORKER_COUNT=$(echo "$WORKERS" | wc -l | xargs)
        echo -e "${GREEN}Total: $WORKER_COUNT worker(s)${NC}"
    else
        echo -e "${YELLOW}Aucun worker actif${NC}"
    fi

    echo ""
    read -p "Appuyez sur Entrée pour continuer..."
}

# Fonction 5: Dashboard temps réel
start_dashboard() {
    ./monitor-queues.sh
}

# Fonction 6: Retry tous les jobs échoués
retry_all_failed() {
    echo -e "${YELLOW}🔄 Retry de tous les jobs échoués...${NC}"
    php artisan queue:retry all
    echo ""
    echo -e "${GREEN}✓ Terminé${NC}"
    sleep 2
}

# Fonction 7: Retry un job spécifique
retry_specific_job() {
    echo -e "${YELLOW}Entrez l'ID du job à retry:${NC}"
    read job_id
    php artisan queue:retry "$job_id"
    echo ""
    read -p "Appuyez sur Entrée pour continuer..."
}

# Fonction 8: Supprimer tous les jobs échoués
flush_failed_jobs() {
    echo -e "${RED}⚠️  Supprimer TOUS les jobs échoués?${NC}"
    echo -n "Tapez 'oui' pour confirmer: "
    read confirm

    if [ "$confirm" = "oui" ]; then
        php artisan queue:flush
        echo -e "${GREEN}✓ Jobs échoués supprimés${NC}"
    else
        echo -e "${YELLOW}Annulé${NC}"
    fi
    sleep 2
}

# Fonction 9: Vider toutes les queues
flush_all_queues() {
    echo -e "${RED}⚠️  DANGER: Vider TOUTES les queues?${NC}"
    echo -e "${YELLOW}Cela supprimera tous les jobs en attente (en cours et planifiés)${NC}"
    echo -n "Tapez 'OUI' en majuscules pour confirmer: "
    read confirm

    if [ "$confirm" = "OUI" ]; then
        redis-cli DEL queues:notifications > /dev/null 2>&1
        redis-cli DEL queues:default > /dev/null 2>&1
        redis-cli DEL queues:notifications:delayed > /dev/null 2>&1
        redis-cli DEL queues:default:delayed > /dev/null 2>&1
        redis-cli DEL queues:notifications:reserved > /dev/null 2>&1
        redis-cli DEL queues:default:reserved > /dev/null 2>&1
        echo -e "${GREEN}✓ Toutes les queues ont été vidées${NC}"
    else
        echo -e "${YELLOW}Annulé${NC}"
    fi
    sleep 2
}

# Fonction 10: Démarrer les workers
start_workers() {
    echo -e "${CYAN}🚀 Démarrage des workers...${NC}"
    ./start-workers.sh
}

# Fonction 11: Arrêter tous les workers
stop_workers() {
    echo -e "${YELLOW}⚠️  Arrêt de tous les workers...${NC}"
    pkill -f "queue:work" 2>/dev/null
    sleep 2
    echo -e "${GREEN}✓ Workers arrêtés${NC}"
    sleep 2
}

# Fonction 12: Redémarrer les workers
restart_workers() {
    echo -e "${YELLOW}🔄 Redémarrage des workers...${NC}"
    pkill -f "queue:work" 2>/dev/null
    sleep 2
    echo -e "${GREEN}✓ Workers arrêtés${NC}"
    sleep 1
    ./start-workers.sh
}

# Fonction 13: Stats Redis
show_redis_stats() {
    echo -e "${CYAN}📈 Statistiques Redis:${NC}"
    echo ""

    redis-cli INFO stats | grep -E "total_commands|instantaneous_ops"
    echo ""
    redis-cli INFO memory | grep -E "used_memory_human|used_memory_peak"
    echo ""
    redis-cli INFO clients | grep "connected_clients"
    echo ""
    redis-cli INFO server | grep "uptime_in_days"

    echo ""
    read -p "Appuyez sur Entrée pour continuer..."
}

# Fonction 14: Test connexion Redis
test_redis_connection() {
    echo -e "${CYAN}🔍 Test de connexion Redis...${NC}"
    echo ""

    if redis-cli ping > /dev/null 2>&1; then
        echo -e "${GREEN}✓ Redis est actif et répond${NC}"
        echo ""
        redis-cli INFO server | grep "redis_version"
        redis-cli INFO server | grep "os"
    else
        echo -e "${RED}✗ Redis ne répond pas${NC}"
        echo ""
        echo "Démarrez Redis:"
        echo "  macOS: brew services start redis"
        echo "  Linux: sudo service redis start"
    fi

    echo ""
    read -p "Appuyez sur Entrée pour continuer..."
}

# Fonction 15: Vider le cache Redis
flush_redis_cache() {
    echo -e "${RED}⚠️  Vider le cache Redis?${NC}"
    echo -n "Tapez 'oui' pour confirmer: "
    read confirm

    if [ "$confirm" = "oui" ]; then
        php artisan cache:clear
        php artisan config:clear
        echo -e "${GREEN}✓ Cache vidé${NC}"
    else
        echo -e "${YELLOW}Annulé${NC}"
    fi
    sleep 2
}

# Boucle principale
while true; do
    show_menu
    read choice

    case $choice in
        1) show_queue_status ;;
        2) show_pending_jobs ;;
        3) show_failed_jobs ;;
        4) show_active_workers ;;
        5) start_dashboard ;;
        6) retry_all_failed ;;
        7) retry_specific_job ;;
        8) flush_failed_jobs ;;
        9) flush_all_queues ;;
        10) start_workers ;;
        11) stop_workers ;;
        12) restart_workers ;;
        13) show_redis_stats ;;
        14) test_redis_connection ;;
        15) flush_redis_cache ;;
        0)
            echo -e "${GREEN}Au revoir!${NC}"
            exit 0
            ;;
        *)
            echo -e "${RED}Option invalide${NC}"
            sleep 1
            ;;
    esac
done
