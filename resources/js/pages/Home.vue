<script setup lang="ts">
import axios from 'axios';
import { onMounted, ref } from 'vue';

const tasks = ref([]);
const loading = ref(true);

onMounted(async () => {
    try {
        const response = await axios.get('/api/tasks');
        tasks.value = response.data.data;
    } catch (error) {
        console.error('Error fetching tasks:', error);
    } finally {
        loading.value = false;
    }
});

const updated = async () => {
    try {
        const response = await axios.get('/api/tasks');
        tasks.value = response.data.data;
    } catch (error) {
        console.error('Error fetching tasks:', error);
    } finally {
        loading.value = false;
    }
};

const createTaskModalVisibility = ref(false);
const updateTaskModalVisibility = ref(false);

const taskCreateName = ref('');
const taskCreateDescription = ref('');

const taskUpdateId = ref('');
const taskUpdateName = ref('');
const taskUpdateDescription = ref('');
const taskUpdateStatus = ref(false);
const taskUpdateCode = ref('');

const showCreateTaskModal = () => {
    createTaskModalVisibility.value = true;
};

const closeCreateTaskModal = () => {
    createTaskModalVisibility.value = false;
};

const showUpdateTaskModal = (task) => {
    updateTaskModalVisibility.value = true;

    taskUpdateId.value = task['id'];
    taskUpdateName.value = task['name'];
    taskUpdateDescription.value = task['description'];
    taskUpdateStatus.value = Boolean(task['status']);
    taskUpdateCode.value = task['code'];
};
const closeUpdateTaskModal = () => {
    updateTaskModalVisibility.value = false;

    taskUpdateName.value = '';
    taskUpdateDescription.value = '';
    taskUpdateStatus.value = false;
    taskUpdateCode.value = '';
};

const updateTask = async (e) => {
    e.preventDefault();
    try {
        const newTask = {
            id: taskUpdateId.value,
            name: taskUpdateName.value,
            description: taskUpdateDescription.value,
            status: Boolean(taskUpdateStatus.value),
            code: taskUpdateCode.value,
        };

        const response = await axios.patch(`/api/tasks/${newTask.id}`, newTask);
        alert(response.data.message);
        closeUpdateTaskModal();
        await updated();
    } catch (error) {
        console.error('Error updating task: ', error);
    }
};

const deleteTask = async (task) => {
    try {
        const response = await axios.delete(`/api/tasks/${task.id}`, task);
        alert(response.data.message);
        await updated();
    } catch (error) {
        console.error('Error deleting task:', error);
    }
};

const submitForm = async (e) => {
    e.preventDefault();
    try {
        const task = {
            name: taskCreateName.value,
            description: taskCreateDescription.value,
        };

        await axios.post('/api/tasks', task);
        closeCreateTaskModal();
        await updated();
    } catch (error) {
        console.error('Failed to create a task:', error);
    }
};
</script>

<template>
    <main>
        <div class="flex min-h-screen items-center justify-center bg-gray-100">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-lg">
                <h1 class="mb-4 text-center text-2xl font-bold text-gray-600">Todo List</h1>

                <div v-if="loading" class="text-center text-gray-500">Loading...</div>

                <ul v-else class="flex flex-col gap-3 !pl-0">
                    <li v-for="task in tasks" :key="task.id" class="flex h-8 w-full items-center pl-0 last:border-b-0">
                        <span :class="{ 'text-gray-600 line-through': task.status, 'flex h-full w-full text-xl': true }">
                            {{ task.name }}
                        </span>
                        <div class="flex justify-self-end">
                            <button @click="showUpdateTaskModal(task)" class="h-full w-auto rounded border bg-gray-400 hover:bg-gray-200">
                                <i class="fa-solid fa-repeat p-2"></i>
                            </button>
                            <button @click="deleteTask(task)" class="h-full w-auto rounded border bg-red-400 hover:bg-red-200">
                                <i class="fa-solid fa-trash-can p-2"></i>
                            </button>
                        </div>
                    </li>
                </ul>

                <div v-if="!tasks.length && !loading" class="mb-4 text-center text-gray-500">No tasks found.</div>

                <button @click="showCreateTaskModal" class="rounded bg-blue-500 px-4 py-2 font-bold text-white hover:bg-blue-400">Add Task</button>
            </div>
        </div>

        <div
            v-if="createTaskModalVisibility"
            class="bg-opacity-50 absolute inset-0 m-auto flex h-[clamp(200px,40%,400px)] w-[clamp(200px,40%,400px)] items-center justify-center rounded-xl bg-gray-800"
        >
            <div class="relative w-96 rounded bg-white p-6 shadow-lg">
                <h2 class="front-bold mb-4 text-xl text-gray-700">New task</h2>

                <form>
                    <input
                        v-model="taskCreateName"
                        type="text"
                        placeholder="Task Name"
                        class="mb-3 w-full rounded border p-2 text-gray-700"
                        required
                    />
                    <input
                        v-model="taskCreateDescription"
                        type="text"
                        placeholder="Task Description"
                        class="mb-3 w-full rounded border p-2 text-gray-700"
                        required
                    />
                    <div class="flex justify-end-safe gap-5">
                        <button
                            @click="closeCreateTaskModal"
                            class="rounded border-2 border-blue-500 px-4 py-2 font-bold text-blue-500 hover:bg-gray-300"
                        >
                            Close
                        </button>
                        <button @click="submitForm" class="rounded bg-blue-500 px-4 py-2 font-bold text-white hover:bg-blue-400">Save</button>
                    </div>
                </form>
            </div>
        </div>
        <div
            v-if="updateTaskModalVisibility"
            class="bg-opacity-50 absolute inset-0 m-auto flex h-[clamp(200px,56%,800px)] w-[clamp(200px,40%,400px)] items-center justify-center rounded-xl bg-gray-800"
        >
            <div class="relative w-96 rounded bg-white p-6 shadow-lg">
                <h2 class="front-bold mb-4 text-xl text-gray-700">Update task</h2>

                <form>
                    <div class="flex w-full flex-col gap-2 p-2 text-gray-700">
                        <label for="taskStatus" class="text-gray-700">Task name</label>
                        <input
                            v-model="taskUpdateName"
                            type="text"
                            placeholder="Task Name"
                            class="mb-3 w-full rounded border p-2 text-gray-700"
                            required
                        />
                    </div>
                    <div class="flex w-full flex-col gap-2 p-2 text-gray-700">
                        <label for="taskStatus" class="text-gray-700">Task description</label>
                        <input
                            v-model="taskUpdateDescription"
                            type="text"
                            placeholder="Task Description"
                            class="mb-3 w-full rounded border p-2 text-gray-700"
                            required
                        />
                    </div>
                    <div class="flex w-full gap-2 p-2 text-gray-700">
                        <label for="taskStatus" class="text-gray-700">Status</label>
                        <input v-model="taskUpdateStatus" type="checkbox" title="status" id="taskStatus" class="scale-150" />
                    </div>
                    <div class="flex w-full flex-col gap-2 p-2 text-gray-700">
                        <label for="taskStatus" class="text-gray-700">Task code</label>
                        <input v-model="taskUpdateCode" class="mb-3 w-full rounded border p-2 text-gray-400" type="text" readonly />
                    </div>
                    <div class="flex justify-end-safe gap-5">
                        <button
                            @click="closeUpdateTaskModal"
                            class="rounded border-2 border-blue-500 px-4 py-2 font-bold text-blue-500 hover:bg-gray-300"
                        >
                            Close
                        </button>
                        <button @click="updateTask" class="rounded bg-blue-500 px-4 py-2 font-bold text-white hover:bg-blue-400">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</template>
