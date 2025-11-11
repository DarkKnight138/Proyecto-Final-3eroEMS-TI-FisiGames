#!/bin/bash
FECHA=$(date +"%Y-%m-%d_%H-%M-%S")
DESTINO="/var/backups"
NOMBRE="respaldo_$FECHA.tar.gz"
ORIGEN="/home/usuario"   
mkdir -p "$DESTINO"
tar -czf "$DESTINO/$NOMBRE" "$ORIGEN"
if [ $? -eq 0 ]; then
    echo "✅ Respaldo creado: $DESTINO/$NOMBRE"
    echo "$(date +"%d/%m/%y %H:%M:%S") Se realizo respaldo manual del /home" >> /var/log/respaldos.log
else
    echo "❌ Error al crear el respaldo"
    echo "$(date +"%d/%m/%y %H:%M:%S") Error al crear respaldo manual del /home" >> /var/log/respaldos.log
fi
