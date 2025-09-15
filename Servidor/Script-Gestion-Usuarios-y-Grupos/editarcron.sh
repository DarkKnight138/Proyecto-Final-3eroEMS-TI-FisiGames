#!/bin/bash

CRON_FILE="/etc/crontab"
EDITOR=${EDITOR:-nano}  # Usa nano, vi, o lo que tengas configurado

ACTION=$1
ENTRY=$2
TMPFILE=$(mktemp)

case $ACTION in
  list)
    echo "Contenido de $CRON_FILE:"
    cat "$CRON_FILE"
    ;;
    
  add)
    if [ -z "$ENTRY" ]; then
      echo "Debes especificar la entrada de cron. Ejemplo:"
      echo "$0 add \"0 5 * * * root /ruta/script.sh\""
      exit 1
    fi
    echo "$ENTRY" | sudo tee -a "$CRON_FILE" > /dev/null
    echo "Entrada añadida: $ENTRY"
    ;;
    
  remove)
    if [ -z "$ENTRY" ]; then
      echo "Debes especificar un texto a eliminar. Ejemplo:"
      echo "$0 remove \"/ruta/script.sh\""
      exit 1
    fi
    sudo grep -v "$ENTRY" "$CRON_FILE" > "$TMPFILE"
    sudo cp "$TMPFILE" "$CRON_FILE"
    echo "Entradas que contenían '$ENTRY' eliminadas."
    ;;
    
  edit)
    sudo $EDITOR "$CRON_FILE"
    ;;
    
  *)
    echo "Uso: $0 {list|add \"CRON_ENTRY\"|remove \"MATCH_STRING\"|edit}"
    echo "Ejemplo add: $0 add \"0 3 * * * root /usr/local/bin/backup.sh\""
    ;;
esac

rm -f "$TMPFILE"
