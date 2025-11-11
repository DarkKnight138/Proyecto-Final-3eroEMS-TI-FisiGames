
#!/bin/bash
clear
echo "Ingrese ruta absoluta del directorio a respaldar:"
read dir
echo "Ingrese ip del equipo:"
read ip
echo "Ingrese credenciales del usuario:"
read usuario
scp -r $usuario@$$ip $dir /var/backups
tar -czf /var/backups/$(date +"%d/%m/%y %H:%M:%S").tar.gz /var/backups/$dir
rm -r /var/backups/$dir
echo "$(date +"%d/%m/%y %H:%M:%S") Se realizo un respaldo remoto de la ip $ip" >> /var/log/respaldos.log
