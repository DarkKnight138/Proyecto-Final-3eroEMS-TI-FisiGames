
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
      read eleccion
      case $eleccion in
        1) comando="firewall-cmd --permanent --add-port="
        echo "Ingrese puerto:"
        read puerto
        comando+="$puerto"
        sudo eval $comando;;
        2) comando="firewall-cmd --permanent --add-service="
        echo "Ingrese servicio:"
        read servicio
        comando+="servicio"
        sudo eval $comando;;
        0)echo "Volviendo ....";;
        *)echo "Eleccion $eleccion es invalida";;
        esac
        done
        clear
        
      0) echo "Volviendo....";;
      *) echo "La opcion $opcion es invalida";;
    esac
done
