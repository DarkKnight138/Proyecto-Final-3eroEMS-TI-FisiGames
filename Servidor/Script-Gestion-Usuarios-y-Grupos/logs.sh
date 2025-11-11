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
        1) cat /var/log/userygrupos.log ;;
        2) cat /var/log/respaldos.log;;
        3) cat /var/log/firewall.log ;;
        4)  echo "***************************************************************"
            echo "*                    Seleccione una opción                    *"
            echo "* 1) Mostrar logs de Usuarios/Grupos                          *"
            echo "* 2) Mostrar logs de Respaldos                                *"
            echo "* 3) Mostrar logs del Firewall                                *"
            echo "***************************************************************"
            read opcion2
            echo "Ingrese fecha (dd/mm/aa):"
            read fecha
            case $opcion2 in
                1) grep "$fecha" /var/log/userygrupos.log
                echo "Enter"
                read a
                ;;
                2) grep "$fecha" /var/log/respaldos.log
                echo "Enter"
                read a
                ;;
                3) grep "$fecha" /var/log/firewall.log
                echo "Enter"
                read a
                ;;
            esac 
            ;;
        *)echo "La opción $opcion no es correcta." ;;
    esac
done
