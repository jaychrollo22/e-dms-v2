/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Common Components
Vue.component('table-pagination', require('./components/Common/TablePagination.vue').default);

//Document Requests
Vue.component('document-requests', require('./components/DocumentRequests/Index.vue').default);
Vue.component('user-document-requests', require('./components/DocumentRequests/UserIndex.vue').default);
Vue.component('document-request-create', require('./components/DocumentRequests/Create.vue').default);

//Document Copy Requests
Vue.component('document-copy-requests', require('./components/DocumentCopyRequests/Index.vue').default);
Vue.component('user-document-copy-requests', require('./components/DocumentCopyRequests/UserIndex.vue').default);
Vue.component('document-copy-request-create', require('./components/DocumentCopyRequests/Create.vue').default);

//Document Uploads
Vue.component('document-uploads', require('./components/DocumentUploads/Index.vue').default);
Vue.component('user-document-uploads', require('./components/DocumentUploads/UserIndex.vue').default);

//User Profile
Vue.component('user-profile', require('./components/Users/UserProfile.vue').default);

//Access Requests
Vue.component('access-requests', require('./components/AccessRequests/Index.vue').default);
Vue.component('access-requests-create', require('./components/AccessRequests/Create.vue').default);

//Home Dashboard
Vue.component('dashboard', require('./components/Home/Dashboard.vue').default);
Vue.component('user-dashboard', require('./components/Home/UserDashboard.vue').default);

//Settings
Vue.component('users', require('./components/Users/Index.vue').default);
Vue.component('settings', require('./components/Settings/Index.vue').default);
Vue.component('companies', require('./components/Companies/Index.vue').default);
Vue.component('departments', require('./components/Departments/Index.vue').default);
Vue.component('roles', require('./components/Roles/Index.vue').default);
Vue.component('document-categories', require('./components/DocumentCategories/Index.vue').default);
Vue.component('tags', require('./components/Tags/Index.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
