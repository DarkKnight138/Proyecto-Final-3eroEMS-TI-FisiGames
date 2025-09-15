#!/bin/bash
FECHA=$(date +"%Y-%m-%d_%H-%M-%S")
DESTINO="/var/backups"
NOMBRE="respaldo_$FECHA.tar.gz"
ORIGEN="/home/usuario"   
mkdir -p "$DESTINO"
tar -czf "$DESTINO/$NOMBRE" "$ORIGEN"
if [ $? -eq 0 ]; then
    echo "✅ Respaldo creado: $DESTINO/$NOMBRE"
else
    echo "❌ Error al crear el respaldo"
fi
