
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import VueMask from 'v-mask';
import VueMaterial from 'vue-material';
import 'vue-material/dist/vue-material.css';
import VeeValidate from 'vee-validate';
// ES6 Modules or TypeScript


Vue.use(VeeValidate);
Vue.use(VueMask);
Vue.use(VueMaterial);

Vue.material.registerTheme({
    default: {
        primary: 'light-green'
    },
    teal: {
        primary: 'pink'
    },
    purple: {
        primary: 'purple',
        accent: 'green'
    },
    red: {
        primary: 'red'
    },
    green: {
        primary: 'green'
    },
    blue: {
        primary: 'blue'
    }
});


Vue.component('example', require('./components/Example.vue'));
Vue.component('grade-component', require('./components/Grade.vue'));
Vue.component('field-component', require('./components/Field.vue'));
Vue.component('clase-component', require('./components/Clase.vue'));
Vue.component('subject-component', require('./components/Subject.vue'));
Vue.component('year-component', require('./components/Year.vue'));
Vue.component('student-component', require('./components/Student.vue'));
Vue.component('teacher-component', require('./components/Teacher.vue'));
Vue.component('parent-component', require('./components/Parent.vue'));
Vue.component('classschedule-component', require('./components/ClassSchedule.vue'));
Vue.component('attendance-component', require('./components/Attendance.vue'));
Vue.component('onlineexam-component', require('./components/OnlineExam.vue'));
Vue.component('poll-component', require('./components/Poll.vue'));
Vue.component('news-component', require('./components/News.vue'));
Vue.component('promote-component', require('./components/Promote.vue'));
Vue.component('library-component', require('./components/Library.vue'));
Vue.component('assignment-component', require('./components/Assignment.vue'));
Vue.component('dashboard-component', require('./components/Dashboard.vue'));



const app = new Vue({
    el: '#app',
    data(){
        return {
            loading:false
        }
    }
});
