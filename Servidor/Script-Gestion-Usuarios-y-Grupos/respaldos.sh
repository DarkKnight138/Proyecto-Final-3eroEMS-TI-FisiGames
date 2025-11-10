#!/bin/bash
clear
opcion=1
while ((opcion != 0))
do
    echo "****************************************************************"
    echo "*                    Seleccione una opción                     *"
    echo "* 1) Realizar respaldo manual                                  *"
    echo "* 2) Realizar respaldo remoto                                  *"
    echo "* 3) Editar respaldos automaticos                              *"
    echo "* 0) Volver                                                    *"
    echo "****************************************************************"
    echo "Opcion:"
    read opcion
    clear
    case $opcion in
      
      0) sh menucentral.sh;;
      1) sh respaldomanual.sh;;
      2) sh respaldoremoto.sh;;
      3) sh editarcron.sh;;
      *) echo "La opcion $opcion es invalida";;
    esac
done
