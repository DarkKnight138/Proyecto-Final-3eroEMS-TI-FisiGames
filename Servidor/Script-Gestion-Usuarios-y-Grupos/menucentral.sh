#!/bin/bash                                                                         #Interprete de comando   
clear                                                                               #Limpia la pantalla  
usu=1                                                                               #Se le da un valor de 1 a la variable usu 
while (($usu !=3))                                                                  #Mientras la variable usu sea distinta de 4 se seguira ejecutando el programa
do                                                                                  #Comienzo del do   
echo "************************************************************************"     #Muestra las opciones a elegir
echo "*                        Seleccione una opción                         *"      
echo "* 1)Ir a la administracion de Usuarios                                 *"                   
echo "* 2)Ir a la administracion de Grupos                                   *"
echo "* 3)Ir a la administracion del Firewall                                *"
echo "* 4)Ir a la administracion de Docker                                   *"
echo "* 5)Salir                                                              *"
echo "************************************************************************"
read usu                                                                            #Lee la variable usuario y le asigna el valor ingresado por el usuario 
case $usu in                                                                        #Realiza una accion dependiendo el valor de de la variable usuario  
1) sh scriptusuarios.sh ;;                                                          #Ejecuta el script scriptusuarios.sh  
2) sh scriptgrupos.sh ;;                                                            #Ejecuta el script scriptgrupos.sh  
3) sh firewall.sh;;                                                                 #Ejecuta el script firewall.sh
4) sh docker.sh;;                                                                   #Ejecuta el script docker.sh
5) echo "Saliendo...";;                                                             #Sale del menú
*) echo "La opcion $usu es invalida, ingrese otra";;                                #Muestra un mensaje de error si selecciona una opcion invalida
esac                                                                                #Final del case
done                                                                                #Final del do
