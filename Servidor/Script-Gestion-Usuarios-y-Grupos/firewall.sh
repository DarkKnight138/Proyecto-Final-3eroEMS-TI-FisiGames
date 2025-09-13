
#!/bin/bash
clear
opcion=1
while ((opcion != 0))
do
    echo "****************************************************************"
    echo "*                    Seleccione una opción                     *"
    echo "* 1) Activar firewall                                          *"
    echo "* 2) Detener firewall                                          *"
    echo "* 3) Listar servicios habilitados                              *"
    echo "* 4) Listar interfaces de red                                  *"
    echo "* 5) Listar zonas de red                                       *"
    echo "* 6) Agregar puerto/servicio                                   *"
    echo "* 7) Bloquear puerto/servicio                                  *"
    echo "* 8) Agregar ip+puerto                                         *"
    echo "* 9) Bloquear ip+puerto                                        *"
    echo "* 0) Volver                                                    *"
    echo "****************************************************************"
    echo "Opcion:"
    read opcion
    clear
    case $opcion in
      1) sudo systemctl start firewalld;;
      2) sudo systemctl stop firewalld;;
      3) sudo firewall-cmd --list-services;;
      4) ls /sys/class/net/;;
      5) sudo firewall-cmd --list-all-zones;;
      6) eleccion=1 
      while((eleccion != 0))
      do
      echo "1) Puerto"
      echo "2) Servicio"
      echo "0) Volver"
      read eleccion
      case $eleccion in
         1)        
        echo "Ingrese puerto:"
        read puerto 
        sudo firewall-cmd --permanent --add-port=$puerto/tcp
        sudo firewall-cmd --reload
        ;;
        2)
        echo "Ingrese servicio:"
        read servicio 
        sudo firewall-cmd --permanent --add-service=$servicio
        sudo firewall-cmd --reload
        ;; 
        0)echo "Volviendo ....";;
        *)echo "Eleccion $eleccion es invalida";;
        esac
        done
        ;;
      7) eleccion=1 
      while((eleccion != 0))
      do
      echo "1) Puerto"
      echo "2) Servicio"
      echo "0) Volver"
      read eleccion
      case $eleccion in
        1)        
        echo "Ingrese puerto:"
        read puerto 
        sudo firewall-cmd --permanent --remove-port=$puerto/tcp
        sudo firewall-cmd --reload
        ;;
        2)
        echo "Ingrese servicio:"
        read servicio 
        sudo firewall-cmd --permanent --remove-service=$servicio
        sudo firewall-cmd --reload
        ;;
        0)echo "Volviendo ....";;
        *)echo "Eleccion $eleccion es invalida";;
        esac
        done
        ;;
      8) echo "Ingrese ip a agregar:"
         read ip
         echo "Ingrese puerto a agregar"
         read puerto
         sudo firewall-cmd --permanent --add-rich-rule="rule family='ipv4' source address='${ip}' port port='${puerto}' protocol='tcp' accept"
         sudo firewall-cmd --reload
         ;;
      9) echo "Ingrese ip a agregar:"
         read ip
         echo "Ingrese puerto a agregar"
         read puerto
         sudo firewall-cmd --permanent --add-rich-rule="rule family='ipv4' source address='${ip}' port port='${puerto}' protocol='tcp' log prefix='conexion rechazada' limit value='1/m' reject"
         sudo firewall-cmd --reload
         ;;
      0) sh menucentral.sh;;
      *) echo "La opcion $opcion es invalida";;
    esac
done
