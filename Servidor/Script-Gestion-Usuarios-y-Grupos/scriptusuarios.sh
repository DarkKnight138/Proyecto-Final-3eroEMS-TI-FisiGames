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
#Crear Usuario
1)
   noExiste=true
   while [ "$noExiste" == "true" ]; do 
       echo "Ingrese nombre del usuario a crear:"
       read usuarioCreado
       clear
       if grep -q "^$usuarioCreado:" /etc/passwd; then
           echo "El usuario ya existe."
           noExiste=true
       else
           noExiste=false
       fi
   done

   echo "Ingrese comentario:"
   read comentario

   noExiste=true
   while [ "$noExiste" == "true" ]; do 
       echo "Ingrese directorio personal:"
       read directorio
       clear
       if find /home -maxdepth 1 -type d -name "$directorio" | grep -q .; then
           echo "El directorio ya existe."
           noExiste=true
       else
           noExiste=false
       fi
   done
   clear

   echo "Desea asignarle un grupo primario: (y/n)"
   read asignarGrupoPrimario
   terminar=false
   if [ "$asignarGrupoPrimario" == "y" ]; then
       while [ "$terminar" == "false" ];do 
           echo "Ingrese nombre de grupo:"
           read grupo
           if grep -q "^$grupo:" /etc/group; then
               terminar=true
           else 
               echo "El grupo no existe"
               terminar=false
           fi
       done
   fi
   clear

   echo "Desea asignarle grupos secundarios: (y/n)"
   read asignarGrupoSecundario
   if [ "$asignarGrupoSecundario" == "y" ]; then
       echo "Ingrese grupos secundarios a asignar (Ej: grupo1,grupo2):"
       read grupos
   fi
   clear

   if [ "$asignarGrupoPrimario" == "y" ]; then
       if [ "$asignarGrupoSecundario" == "y" ]; then
           useradd -c "$comentario" -d "/home/$directorio" -m -s /bin/bash -g "$grupo" -G "$grupos" "$usuarioCreado"
       else
           useradd -c "$comentario" -d "/home/$directorio" -m -s /bin/bash -g "$grupo" "$usuarioCreado"
       fi
   else
       if [ "$asignarGrupoSecundario" == "y" ]; then
           useradd -c "$comentario" -d "/home/$directorio" -m -s /bin/bash -G "$grupos" "$usuarioCreado"
       else
           useradd -c "$comentario" -d "/home/$directorio" -m -s /bin/bash "$usuarioCreado"
       fi
   fi

   echo "Usuario $usuarioCreado creado correctamente." ;;

2) 
   Existe=false
   while [ "$Existe"=="false" ]; do 
       echo "Ingrese nombre del usuario a borrar:"
       read usuarioBorrado
       clear
       if grep -q "^$usuarioBorrado:" /etc/passwd; then
           
           Existe=true
       else
         echo "El usuario a borrar no existe"
         Existe=false
       fi
   done
   userdel -r "$usuarioBorrado"
   echo "Usuario $usuarioBorrado eliminado correctamente." ;;
3) sh modifyusu.sh ;;
4) cut -d ":" -f1 /etc/passwd ;;
5) sh menucentral.sh ;;
*) echo "La opción $usu es inválida";;
esac
done
