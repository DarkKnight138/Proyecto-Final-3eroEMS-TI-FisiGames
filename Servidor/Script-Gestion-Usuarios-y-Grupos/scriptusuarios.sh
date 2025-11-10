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
clear
case $usu in
#Crear Usuario
1)
   ExisteUsuarioCreado=true
   while [ "$ExisteUsuarioCreado" == "true" ]; do 
       echo "Ingrese nombre del usuario a crear:"
       read usuarioCreado
       clear
       if grep -q "^$usuarioCreado:" /etc/passwd; then
           echo "El usuario ya existe."
           ExisteUsuarioCreado=true
       else
           ExisteUsuarioCreado=false
       fi
   done
    clear
   echo "Ingrese comentario:"
   read comentario
    clear
   ExisteDirectorio=true
   while [ "$ExisteDirectorio" == "true" ]; do 
       echo "Ingrese directorio personal:"
       read directorio
       clear
       if find /home -maxdepth 1 -type d -name "$directorio" | grep -q .; then
           echo "El directorio ya existe."
           ExisteDirectorio=true
       else
           ExisteDirectorio=false
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
   echo "$usuarioCreado:$usuarioCreado" | chpasswd
   chage -d 7 "$usuarioCreado"
   echo "Usuario $usuarioCreado creado correctamente, recuerde cambiar su contraseña por defecto antes de que pasen 7 días." 
   echo "$(date) Se creo el usuario $usuarioCreado" >> /var/log/user&grupos.log ;;
2) 
   ExisteUsuarioBorrado=false
   while [ "$ExisteUsuarioBorrado"=="false" ]; do 
       echo "Ingrese nombre del usuario a borrar:"
       read usuarioBorrado
       clear
       if grep -q "^$usuarioBorrado:" /etc/passwd; then
           
           ExisteUsuarioBorrado=true
       else
         echo "El usuario a borrar no existe"
         ExisteUsuarioBorrado=false
       fi
   done
   userdel -r "$usuarioBorrado"
   echo "Usuario $usuarioBorrado eliminado correctamente." 
   echo "$(date) Se elimino el usuario $usuarioBorrado" >> /var/log/user&grupos.log ;;
3) sh modifyusu.sh ;;
4) cut -d ":" -f1 /etc/passwd ;;
5) sh menucentral.sh ;;
*) echo "La opción $usu es inválida";;
esac
done
