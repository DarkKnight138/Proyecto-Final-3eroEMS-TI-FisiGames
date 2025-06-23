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
    clear
    case $opcion in
        1) echo "Ingrese nombre del grupo a crear:"
           read grupoCreado
           Existe=true
   while [ "$Existe" == "true" ]; do 
       echo "Ingrese nombre del usuario a crear:"
       read grupoCreado
       clear
       if grep -q "^$grupoCreado:" /etc/group; then
           echo "El grupo ya existe."
           Existe=true
       else
           Existe=false
       fi
   done
           groupadd $grupocreado
           echo "Grupo $grupocreado creado correctamente." ;;
        2) echo "Grupos disponibles:"
           cut -d ":" -f1 /etc/group ;;
        3) echo "Ingrese nombre del grupo a listar usuarios:"
           read grupo
           getent group $grupo ;;
        4) echo "Ingrese el nombre del grupo a eliminar:"
           read grupoborrado
           noExiste=true
   while [ "$ExisteGrupoBorrado" == "false" ]; do 
       echo "Ingrese nombre del usuario a crear:"
       read grupoBorrado
       clear
       if grep -q "^$grupoBorrado:" /etc/group; then
           ExisteGrupoBorrado=true
       else
       echo "El grupo no existe."
           ExisteGrupoBorrado=false
       fi
   done
           groupdel "$grupoborrado"
           echo "Grupo $grupoborrado eliminado correctamente." ;;
        0) echo "menucentral.sh" ;;
        *) echo "La opción $opcion no es correcta." ;;
    esac
done
