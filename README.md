# Proyecto de Gestión de Tareas

Este proyecto es una aplicación de gestión de tareas desarrollada con el framework Laravel. Permite crear, actualizar, eliminar y completar tareas utilizando procedimientos almacenados en SQL Server.

## Requisitos Previos

Antes de comenzar, asegúrate de tener instalados los siguientes requisitos en tu sistema:

- PHP >= 7.4
- Composer
- SQL Server
- Extensiones PHP necesarias (`pdo`, `pdo_sqlsrv`, `sqlsrv`)
- XAMPP (para entornos de desarrollo en Windows)

## Instalación

Sigue los pasos a continuación para configurar y ejecutar el proyecto en tu entorno local.

### 1. Clonar el Repositorio

Clona este repositorio en tu máquina local utilizando Git.

```bash
git clone https://github.com/tu-usuario/tu-repositorio.git
cd tu-repositorio
```

### 2. Instalar Dependencias
Instala las dependencias de PHP utilizando Composer.

```bash
composer install
```

### 3. Configurar el Archivo .env

Copia el archivo .env.example a .env y configura tus credenciales de base de datos y otros parámetros necesarios.
```bash
cp .env.example .env
```
Abre el archivo .env y asegúrate de configurar los siguientes valores:

```env
DB_CONNECTION=sqlsrv
DB_HOST=127.0.0.1
DB_PORT=1433
DB_DATABASE=nombre_de_tu_base_de_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```
### 4. Generar la Clave de la Aplicación
Genera una clave de aplicación única para tu proyecto Laravel.
```bash
php artisan key:generate
```
### 5. Ejecutar Migraciones
Ejecuta las migraciones para crear las tablas necesarias en tu base de datos.
```bash
php artisan migrate
```

6. Crear los Procedimientos Almacenados
Crea los procedimientos almacenados en tu base de datos SQL Server. A continuación se muestra un ejemplo de los procedimientos necesarios:
```sql
CREATE PROCEDURE [dbo].[Sp_CompleteTask]
@id int,
@descrip nvarchar(max)
AS

 BEGIN
 SET NOCOUNT ON;
 Declare @result nvarchar(100)
	  UPDATE [dbo].[TBL_task_status]
	  SET [description]= @descrip
	      ,updated_at = GETDATE(), is_completed = 1
      WHERE id = @id 
	   set @result = 'Tarea Completada Correctamente'
   END
Select @result as 'Respuesta'
GO
CREATE PROCEDURE [dbo].[Sp_CreateTask]
@title nvarchar(255),
@descrip nvarchar(max)
AS

 BEGIN
 SET NOCOUNT ON;
 Declare @result nvarchar(100)
	  INSERT INTO [dbo].[TBL_task_status]
           ([title]
           ,[description]
           ,[is_completed]
           ,[created_at]
           )
     VALUES
           (@title
           ,@descrip
           ,0
           ,GETDATE()
           )
		   set @result = 'Tarea Creada Correctamente'
   END
Select @result as 'Respuesta'
GO
CREATE PROCEDURE [dbo].[Sp_DeleteTask]
@id int
AS

 BEGIN
 SET NOCOUNT ON;
 Declare @result nvarchar(100)
	  DELETE [dbo].[TBL_task_status]
      WHERE id = @id 
	   set @result = 'Tarea Eliminada Correctamente' 
   END
Select @result as 'Respuesta'
GO
CREATE PROCEDURE [dbo].[Sp_GetTask]

AS

 BEGIN
 SET NOCOUNT ON;

Select * 
From dbo.TBL_task_status
order by is_completed asc
END
GO
CREATE PROCEDURE [dbo].[Sp_UpdateTask]
@id int,
@title nvarchar(255),
@descrip nvarchar(max)
AS

 BEGIN
 SET NOCOUNT ON;
 Declare @result nvarchar(100)
	 UPDATE [dbo].[TBL_task_status]
	  SET title = @title , [description]= @descrip
	      ,updated_at = GETDATE()
      WHERE id = @id 
	    set @result = 'Tarea Actualizada Correctamente'
   END
Select @result as 'Respuesta'
GO
```
### 7. Iniciar el Servidor de Desarrollo
Inicia el servidor de desarrollo de Laravel.
```bash
php artisan serve
```
### 8. Acceder a la Aplicación
Abre tu navegador web y accede a la aplicación en http://127.0.0.1:8000/home.

# Uso
## Crear una Tarea
Para crear una tarea, utiliza el formulario en la página principal. Ingresa el título de la tarea y haz clic en "Agregar".

## Actualizar una Tarea
Para actualizar una tarea, haz clic en el icono de editar (✏) junto a la tarea que deseas actualizar. Ingresa el nuevo título y descripción, y guarda los cambios.

## Eliminar una Tarea
Para eliminar una tarea, haz clic en el botón "X" junto a la tarea que deseas eliminar. Confirma la eliminación si es necesario.

## Completar una Tarea
Para marcar una tarea como completada, haz clic en el botón "✔" junto a la tarea.

# Contribuciones
Las contribuciones son bienvenidas. Por favor, abre un issue o envía un pull request para contribuir.
