#!/bin/bash
clear
opcion=1
while ((opcion != 0))
do
    echo "***************************************************************"
    echo "*                    Seleccione una opción                    *"
    echo "* 1) Crear grupo                                              *"
    echo "* 2) Mostrar todos los grupos                                 *"
    echo "* 3) Listar usuarios en un grupo                              *"
    echo "* 4) Eliminar grupo                                           *"
    echo "* 0) Salir                                                    *"
    echo "***************************************************************"
    read opcion
    case $opcion in
        1) echo "Ingrese nombre del grupo a crear:"
           read grupocreado
           groupadd $grupocreado
           echo "Grupo $grupocreado creado correctamente." ;;
        2) echo "Grupos disponibles:"
           cut -d ":" -f1 /etc/group ;;
        3) echo "Ingrese nombre del grupo a listar usuarios:"
           read grupo
           getent group $grupo ;;
        4) echo "Ingrese el nombre del grupo a borrar:"
           read grupoborrado
           groupdel "$grupoborrado"
           echo "Grupo $grupoborrado eliminado correctamente." ;;
        0) echo "Saliendo..." ;;
        *) echo "La opción $opcion no es correcta." ;;
    esac
done
