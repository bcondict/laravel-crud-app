const Home = () => import('./components/Home.vue');

//componentes
const Show = () => import('./components/Task/Show.vue');
const Create = () => import('./components/Task/Create.vue');
const Edit = () => import('./components/Task/Edit.vue');

export const routes = [
    {
        name: 'home',
        path: '/',
        component: Home,
    },
    {
        name: 'showTasks',
        path: '/tasks',
        component: Show,
    },
    {
        name: 'createTask',
        path: '/create',
        component: Create,
    },
    {
        name: 'editTask',
        path: '/edit/:id',
        component: Edit,
    },
];
