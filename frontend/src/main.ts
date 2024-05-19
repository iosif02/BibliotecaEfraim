import { createApp } from "vue";
import { createPinia } from "pinia";

import App from "@/App.vue";
import router from "@/router";
import Notifications from '@kyvg/vue3-notification';
import PrimeVue from 'primevue/config';
import AutoComplete from 'primevue/autocomplete';

import "@/helpers/axios"
import "@/assets/main.css";
import "primevue/resources/themes/saga-blue/theme.css";
import "primevue/resources/primevue.min.css";
import "primeicons/primeicons.css";

import GoBackVue from "@/views/Components/Global/GoBackComponent.vue";
import Loading from '@/views/Components/Global/LoadingComponent.vue';
import LoadingButton from '@/views/Components/Global/LoadingButtonComponent.vue';
import SearchBar from '@/views/Components/Global/SearchBarComponent.vue';
import Modal from '@/views/Components/Global/ModalComponent.vue';
import Bounded from '@/views/Components/Global/BoundedComponent.vue'

import { filters } from "@/helpers/Filter";

const app = createApp(App);

app.config.globalProperties.$filters = filters;

app.use(PrimeVue);
app.use(createPinia());
app.use(router);
app.use(Notifications);

app.component('GoBack', GoBackVue);
app.component('Loading', Loading);
app.component('LoadingButton', LoadingButton);
app.component('SearchBar', SearchBar);
app.component('Modal', Modal);
app.component('Bounded', Bounded);

app.component('AutoComplete', AutoComplete);

app.mount("#app");
