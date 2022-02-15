# WebER by @daom89
Sistema de Control de Inventarios.

## Descripción 
Este repositorio es un punto de partida para los aprendices del Programa de ADSI del SENA. La intención del proyecto es servir de guía para desarrollar aplicaciones en PHP usando técnicas actuales de desarrollo bajo una estructura MVC.

### Requisitos
Para su correcto funcionamiento se requiere tener instaladas las siguientes herramientas:

* [Visual Studio Code](https://code.visualstudio.com/download) - Editor de Código Ligero y Personalizable.
* [Node](https://nodejs.org/es/download/) - Entorno de Ejecución JS, requerido por npm
* [Git](https://git-scm.com/download/win) - Sistema de Control de Versiones
* [GitHub Desktop](https://desktop.github.com/) - Herramienta para gestionar repositorios en GitHub.
* [Firefox Developer](https://www.mozilla.org/es-ES/firefox/all/#product-desktop-developer) - Navegador para Desarrolladores.
* [MySQL Workbeanch](https://www.mysql.com/products/workbench/) - Herramienta para el modelado de bases de datos en MySQL.
* [DB Seeder](https://github.com/haruncpi/db-seeder/releases) - Herramienta para el rellenado de datos en BD.
* [Software Ideas Modeler](https://www.softwareideas.net/en/download) - Herramienta para el modelado de UML.
* [Laragon](https://github.com/leokhoa/laragon/releases) - Paquete Xampp para el Desarrollo en Local.
* [PhpMyAdmin](https://www.phpmyadmin.net/) - Gestor de Bases de Datos MySQL Web.
* [Composer](https://getcomposer.org/download/) - Herramienta para la gestión de dependencias en PHP
* [JetBrains PhpStorm](https://www.jetbrains.com/es-es/phpstorm/download/#section=windows) - Potente IDE para el desarrollo de aplicaciones en PHP

### Instalación

1. Desde PhpStorm clonar el repositorio [https://github.com/EjemplosADSI/WebER.git](https://github.com/EjemplosADSI/WebER.git).
2. Verificar lo siguientes requisitos en Laragon:
    1. Php 8 o Superior.
    2. Apache 2.4.43 o Superior.
3. Una vez clonado el repositorio Ejecutar composer install
4. Instalar el Script de la base de datos en phpmyadmin.
5. Ejecutar npm install && npm run build
6. composer dump-autoload
7. Herramientas de Buenas Practicas:
   1. composer global require friendsofphp/php-cs-fixer --with-all-dependencies
   2. composer global require "squizlabs/php_codesniffer=*"
