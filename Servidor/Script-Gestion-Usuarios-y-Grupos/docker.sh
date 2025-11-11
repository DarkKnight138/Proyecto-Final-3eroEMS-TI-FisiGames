#!/bin/bash
clear
opcion=1
while ((opcion != 0))
do
    echo "***************************************************************"
    echo "*                    Seleccione una opción                    *"
    echo "* 1) Iniciar aplicación                                       *"
    echo "* 2) Detener aplicación                                       *"
    echo "* 3) Iniciar Apache                                           *"
    echo "* 4) Detener Apache                                           *"
    echo "* 5) Iniciar MySQL                                            *"
    echo "* 6) Detener MySQL                                            *"
    echo "* 0) Salir                                                    *"
    echo "***************************************************************"
    read opcion
    clear
    case $opcion in
        *)echo "La opción $opcion no es correcta." ;;
    esac
done
