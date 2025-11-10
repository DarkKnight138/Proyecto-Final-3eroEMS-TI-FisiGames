
#!/bin/bash
clear
echo "Ingrese ruta absoluta del directorio a respaldar:"
read dir
echo "Ingrese ip del equipo:"
read ip
echo "Ingrese credenciales del usuario:"
read usuario
scp $usuario@$$ip $dir /var/backups
