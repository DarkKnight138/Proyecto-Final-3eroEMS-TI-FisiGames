#!/bin/bash
clear
usu=1
while (($usu != 5))
do
echo "***************************************************************"
echo "*                    Seleccione una opción                    *"
echo "* 1) Crear Usuarios                                           *"
echo "* 2) Eliminar Usuarios                                        *"
echo "* 3) Modificar Usuario (Te lleva a otro menú)                 *"
echo "* 4) Listar Usuarios                                          *"
echo "* 5) Salir                                                    *"
echo "***************************************************************"
read usu
case $usu in
1) echo "Ingrese nombre del usuario a crear:"
   read usuariocreado
   cls
   echo "Ingrese directorio personal"
   read directorio
   cls
   echo "Desea asignarle un grupo primario (y/n)"
   read asignar
   
   useradd -c "" -m -s /bin/bash $usuariocreado
   echo "Usuario $usuariocreado creado correctamente." ;;
2) echo "Ingrese nombre del usuario a borrar:"
   read usuarioborrado
   userdel -r $usuarioborrado  
   rm -f /var/spool/mail/$usuarioborrado  
   echo "Usuario $usuarioborrado eliminado correctamente." ;;
3) sh modifyusu.sh ;;
4) cut -d ":" -f1 /etc/passwd ;;
5) sh menucentral.sh ;;
*) echo "La opción $usu es inválida";;
esac
done
