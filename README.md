Test técnico para el puesto de Software Developer en 7LFreight, realizado por Mateo Querede

Adjunto algunas instrucciones en caso de ser necesario para testear:
1. En el archivo .env están los datos necesarios para la base de datos, en un principio se necesita crear una base con nombre 'test'.
2. Se necesita ejecutar 'php artisan migrate' para crear las tablas necesarias.
3. Se necesita ejecutar 'php artisan db:seed' para agregar con seeders las clases y usuarios de ejemplo.

Me quedaron algunas cosas a aclarar:
1. El código está comentado en algunas partes donde consideraba necesario para que se entienda a simple vista
2. Como no se aclaraba  en el test como tratar las reservas y fechas lo tomé como si solo se pudiera reservar para la semana actual e hice las validaciones en base a eso, tomando el campo 'dia' como un string, en caso de en el sistema poder reservar para otra semana sería necesario cambiar algunas validaciones, la propiedad 'dia' a tipo date o datetime si se utiliza para la reserva y adaptar el frontend para esto
3. Hice un frontend sencillo por más que no hiciera falta para poder testear más sencillamente
4. Hay cosas pendientes del código que se podrían mejorar/refactorear
