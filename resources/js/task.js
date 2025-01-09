const showTaskForm = () => {
    let newTaskButton = document.getElementById('new-task-button');
    let newTaskForm = document.getElementById('new-task-form');

    if (newTaskButton.classList.contains('hidden')) {
        newTaskForm.classList.add('hidden');
        newTaskButton.classList.remove('hidden');
    } else {
        newTaskButton.classList.add('hidden');
        newTaskForm.classList.remove('hidden');
        newTaskForm.classList.add('flex', 'flex-row', 'gap-3', 'align-center');
    }
}

const showEditTaskForm = (key) => {
    let editTaskButton = document.getElementById('edit-task-button-' + key);
    let deleteTaskButton = document.getElementById('delete-task-button-' + key);
    let editTaskForm = document.getElementById('edit-task-form-' + key);
    let taskName = document.getElementById('task-col-' + key);



    if (editTaskButton.disabled == true) {
        editTaskForm.classList.add('hidden');
        editTaskButton.classList.remove('hidden');
        taskName.classList.remove('hidden');
        editTaskButton.disabled = false;
        deleteTaskButton.disabled = false;
    } else {
        editTaskButton.disabled = true;
        deleteTaskButton.disabled = true;
        editTaskForm.classList.remove('hidden');
        taskName.classList.add('hidden');
        editTaskForm.classList.add('flex', 'flex-row', 'gap-3', 'align-center');
    }

    console.log(editTaskButton)
    console.log(deleteTaskButton)
    console.log(editTaskForm)
    console.log(taskName)
}

let firstPriority = null;

const drag = (event) => {
    firstPriority = event.target.dataset.priority
}

const allowDrop = (event) => {
    event.preventDefault()
}

const drop = (event, priority) => {
    event.preventDefault()

    let targetPriority = event.target.closest('tr').dataset.priority;
    let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    console.log(csrfToken)

    let data = {
        selected_priority: firstPriority,
        target_priority: targetPriority,
    }

    axios.post('/task/reorder', data, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
        .then(response => {
            location.reload()
        })
}

window.showTaskForm = showTaskForm;
window.showEditTaskForm = showEditTaskForm;
window.drag = drag;
window.allowDrop = allowDrop;
window.drop = drop;