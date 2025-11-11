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
        1)
            echo "Iniciando la aplicación..."
            docker-compose up -d
            ;;
        2)
            echo "Deteniendo la aplicación..."
            docker-compose down
            ;;
        3)
            echo "Iniciando Apache..."
            docker-compose up -d php
            ;;
        4)
            echo "Deteniendo Apache..."
            docker-compose stop php
            ;;
        5)
            echo "Iniciando MySQL..."
            docker-compose up -d bd
            ;;
        6)
            echo "Deteniendo MySQL..."
            docker-compose stop bd
            ;;
        0)
            echo "Saliendo del script..."
            ;;
        *)
            echo "La opción $opcion no es correcta."
            ;;
    esac
done
