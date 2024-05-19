<script setup lang="ts">
import Pagination from '@/views/Components/Global/PaginationComponent.vue';
import AuthorsComponent from '@/views/Components/Entities/AuthorsComponent.vue';
import { useEntitiesStore } from '@/stores/entities-store';
import CreateButtonComponent from "@/views/Components/Global/CreateButtonComponent.vue"
import { ref } from 'vue';

const store = useEntitiesStore();
store.fetchAuthors();

let authorId = 0;

const showModal = ref<boolean>(false);

var deleteAuthor = () => {
  showModal.value = false;
  store.deleteAuthor(authorId)
  .then(result => {
    if(result){
      store.fetchAuthors();
    }
  });
}

var hideModal = () => {
  showModal.value = false;
}

var openModal = (selectedAuthorId: number) => {
  authorId = selectedAuthorId;
  showModal.value = true;
}

</script>

<template>
  <Loading v-if="store.isLoading" />

  <Modal 
    v-if="showModal" 
    title="Delete Confirmation"
    description="Are you sure you want to delete this author?"
    action="Delete"
    @submit="deleteAuthor" 
    @cancel="hideModal" 
  />

	<div>
    <GoBack :goBackText="`Authors (${store.authors.totalAuthors})`"/>
	</div>

  <SearchBar
    :defaultValue="store.authors.searchModel.name"
    @valueChanged="(value: string) => (store.authors.searchModel.name = value, store.authors.searchModel.pagination.page = 1, store.fetchAuthors())"
    placeholder='Search author...'
  />

  <AuthorsComponent v-if="store.authors.searchModel.pagination.total" :authors="store.authors.data" routeName="editAuthor" @openModal="(selectedAuthorId) => openModal(selectedAuthorId)"/>
  <div class="no-found" v-else-if="!store.isLoading"> No result found </div>


  <Pagination
    :current-page="store.authors.searchModel.pagination.page"
    :last-page="store.authors.searchModel.pagination.last_page"
    @change-page="(page: number) => (store.authors.searchModel.pagination.page = page, store.fetchAuthors())"
  />

  <CreateButtonComponent routeName="createAuthor"/>
</template>

<style scoped>
.spacer {
  margin-bottom: .8rem;
}
</style>