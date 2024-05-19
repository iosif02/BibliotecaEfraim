<script setup lang="ts">
import Pagination from '@/views/Components/Global/PaginationComponent.vue';
import { useUsersStore } from '@/stores/user-store';
import { ref } from 'vue';
import ReadersComponent from '@/views/Components/Users/ReadersComponent.vue';
import CreateButtonComponent from "@/views/Components/Global/CreateButtonComponent.vue"

const store = useUsersStore();
store.fetchUsers();

const showModal = ref<boolean>(false);
let userId = 0;

var openModal = (selectedUserId: number) => {
  userId = selectedUserId;
  showModal.value = true;
}

var deleteUser = () => {
  showModal.value = false;
  store.deleteUser(userId)
  .then(result =>{
    if(result)
    store.fetchUsers();
  });
}

var hideModal = () => {
  showModal.value = false;
}
</script>

<template>
  <Loading v-if="store.isLoading" />

  <Modal 
    v-if="showModal" 
    title="Delete Confirmation"
    description="Are you sure you want to delete this user?"
    action="Delete"
    @submit="deleteUser" 
    @cancel="hideModal" 
  />

  <GoBack :goBackText="`Readers (${store.users.totalUsers})`"/>

  <SearchBar
    :defaultValue="store.users.searchModel.name"
    @valueChanged="(value: string) => (store.users.searchModel.name = value, store.users.searchModel.pagination.page = 1, store.fetchUsers())"
    placeholder="Search user..."
  />

  <ReadersComponent v-if="store.users.searchModel.pagination.total" :users="store.users.data" routeName="editReader" @openModal="(selectedUserId) => openModal(selectedUserId)"/>
  <div class="no-found" v-else-if="!store.isLoading"> No result found </div>

  <Pagination
    :current-page="store.users.searchModel.pagination.page"
    :last-page="store.users.searchModel.pagination.last_page"
    @change-page="(page: number) => (store.users.searchModel.pagination.page = page, store.fetchUsers())"
  />

  <CreateButtonComponent routeName="createReader"/>
</template>
<style scoped>

</style>