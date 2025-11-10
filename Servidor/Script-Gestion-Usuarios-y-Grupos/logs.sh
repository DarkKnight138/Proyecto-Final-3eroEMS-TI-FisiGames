#!/bin/bash
clear
opcion=1
while ((opcion != 0))
do
    echo "***************************************************************"
    echo "*                    Seleccione una opción                    *"
    echo "* 1) Mostrar logs de Usuarios/Grupos                          *"
    echo "* 2) Mostrar logs de Respaldos                                *"
    echo "* 3) Mostrar logs del Firewall                                *"
    echo "* 4) Buscar por fecha                                         *"
    echo "* 0) Salir                                                    *"
    echo "***************************************************************"
    read opcion
    clear
    case $opcion in
        1) cat /var/log/user&grupos.log ;;
        2) cat /var/log/respaldos.log;;
        3) cat /var/log/firewall.log ;;
        4)  echo "***************************************************************"
            echo "*                    Seleccione una opción                    *"
            echo "* 1) Mostrar logs de Usuarios/Grupos                          *"
            echo "* 2) Mostrar logs de Respaldos                                *"
            echo "* 3) Mostrar logs del Firewall                                *"
            echo "***************************************************************"
        read opcion2
            
        *)echo "La opción $opcion no es correcta." ;;
    esac
done
