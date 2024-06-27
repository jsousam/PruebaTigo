<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de tareas</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .pagination {
            justify-content: center;
        }
        .pagination-info {
            display: flex;
            justify-content: center;
            margin-bottom: 1rem;
        }
        .pagination .page-item .page-link {
            padding: 0.5rem 0.75rem;
        }
        .task-title {
            font-size: 2.5rem;
            font-weight: bold;
            color: #000ba5;
            text-align: center;
            margin-top: 2rem;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="task-title">Lista de Tareas</h1>
    
    <div id="alert-container"></div> <!-- Contenedor para los mensajes de alerta -->

    <form id="taskForm" class="form-inline justify-content-center mb-4">
       
        <input type="text" id="title" name="title" class="form-control mr-2" placeholder="Crear contenido del blog">
        <button type="submit" class="btn btn-primary">Agregar</button>
    </form>

    <ul class="list-group" id="taskList">
        @foreach ($tasks as $task)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span class="{{ $task->is_completed ? 'completed' : '' }}">{{ $task->title }}</span>
                <div>
                    <button class="btn btn-danger btn-sm" onclick="deleteTask({{ $task->id }})">X</button>
                    <button class="btn btn-secondary btn-sm" onclick="editTask({{ $task->id }}, '{{ $task->title }}', '{{ $task->description }}')">✏</button>
                    <button class="btn btn-success btn-sm" onclick="completeTask({{ $task->id }})">✔</button>
                </div>
            </li>
        @endforeach
    </ul>

    <div class="pagination-info">
        <span>Showing {{ $tasks->firstItem() }} to {{ $tasks->lastItem() }} of {{ $tasks->total() }} results</span>
    </div>
    <div class="d-flex justify-content-center">
        {{ $tasks->links('pagination::bootstrap-4') }}
    </div>
</div>

<style>
    .completed {
        text-decoration: line-through;
    }
</style>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
       
        document.getElementById('taskForm').addEventListener('submit', function(event) {
            event.preventDefault();
            createTask();
        });

  
        const message = localStorage.getItem('alertMessage');
        const type = localStorage.getItem('alertType');
        if (message && type) {
            showAlert(message, type);
            localStorage.removeItem('alertMessage');
            localStorage.removeItem('alertType');
        }
    });

    function showAlert(message, type) {
        const alertContainer = document.getElementById('alert-container');
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show`;
        alert.role = 'alert';
        alert.innerHTML = `
            ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        `;
        alertContainer.appendChild(alert);

        setTimeout(() => {
            $(alert).alert('close');
        }, 3000);
    }

    function createTask() {
        const title = document.getElementById('title').value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('/tasks', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ title })
        })
        .then(response => response.json())
        .then(data => {
            localStorage.setItem('alertMessage', 'Tarea creada exitosamente');
            localStorage.setItem('alertType', 'success');
            location.reload();
        })
        .catch(error => {
            showAlert('Error al crear la tarea', 'danger');
        });
    }

    function deleteTask(id) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(`/tasks/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            localStorage.setItem('alertMessage', 'Tarea eliminada exitosamente');
            localStorage.setItem('alertType', 'success');
            location.reload();
        })
        .catch(error => {
            showAlert('Error al eliminar la tarea', 'danger');
        });
    }

    function editTask(id, title, description) {
        const newTitle = prompt('Editar tarea:', title);
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        if (newTitle) {
            fetch(`/tasks/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ title: newTitle, description })
            })
            .then(response => response.json())
            .then(data => {
                localStorage.setItem('alertMessage', 'Tarea actualizada exitosamente');
                localStorage.setItem('alertType', 'success');
                location.reload();
            })
            .catch(error => {
                showAlert('Error al actualizar la tarea', 'danger');
            });
        }
    }

    function completeTask(id) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(`/tasks/${id}/complete`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            localStorage.setItem('alertMessage', 'Tarea completada exitosamente');
            localStorage.setItem('alertType', 'success');
            location.reload();
        })
        .catch(error => {
            showAlert('Error al completar la tarea', 'danger');
        });
    }
</script>

</body>
<footer class="form-inline justify-content-center mb-4"><small>Prueba de Tigo, creado por José Sousa</small></footer>
</html>
