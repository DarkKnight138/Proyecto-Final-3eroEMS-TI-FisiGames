#!/bin/bash
clear
opcion=1
while ((opcion != 0))
do
    echo "****************************************************************"
    echo "*                    Seleccione una opción                     *"
    echo "* 1) Realizar respaldo manual                                  *"
    echo "* 2) Editar respaldos automaticos                              *"
    echo "* 0) Volver                                                    *"
    echo "****************************************************************"
    echo "Opcion:"
    read opcion
    clear
    case $opcion in
      
      0) sh menucentral.sh;;
      1) 
      2) sh editarcron.sh;;
      *) echo "La opcion $opcion es invalida";;
    esac
done
