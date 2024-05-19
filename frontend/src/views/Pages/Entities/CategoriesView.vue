<script setup lang="ts">
import Pagination from '@/views/Components/Global/PaginationComponent.vue';
import CategoriesComponent from '@/views/Components/Entities/CategoriesComponent.vue';
import { useEntitiesStore } from '@/stores/entities-store';
import CreateButtonComponent from "@/views/Components/Global/CreateButtonComponent.vue"
import { ref } from 'vue';

const store = useEntitiesStore();
store.fetchCategories();

let categoryId = 0;

const showModal = ref<boolean>(false);

var deleteCategory = () => {
  showModal.value = false;
  store.deleteCategory(categoryId)
  .then(result => {
    if(result){
      store.fetchCategories();
    }
  });
}

var hideModal = () => {
  showModal.value = false;
}

var openModal = (selectedCategoryId: number) => {
  categoryId = selectedCategoryId;
  showModal.value = true;
}

</script>

<template>
  <Loading v-if="store.isLoading" />

  <Modal 
    v-if="showModal" 
    title="Delete Confirmation"
    description="Are you sure you want to delete this category?"
    action="Delete"
    @submit="deleteCategory" 
    @cancel="hideModal" 
  />

	<div>
    <GoBack :goBackText="`Categories (${store.categories.totalCategories})`"/>
	</div>

  <SearchBar
    :defaultValue="store.categories.searchModel.name"
    @valueChanged="(value: string) => (store.categories.searchModel.name = value, store.categories.searchModel.pagination.page = 1, store.fetchCategories())"
    placeholder='Search category...'
  />

  <CategoriesComponent v-if="store.categories.searchModel.pagination.total" :categories="store.categories.data" routeName="editCategory" @openModal="(selectedCategoryId) => openModal(selectedCategoryId)" />
  <div class="no-found" v-else-if="!store.isLoading"> No result found </div>

  <Pagination
    :current-page="store.categories.searchModel.pagination.page"
    :last-page="store.categories.searchModel.pagination.last_page"
    @change-page="(page: number) => (store.categories.searchModel.pagination.page = page, store.fetchCategories())"
  />

  <CreateButtonComponent routeName="createCategory"/>
</template>

<style scoped>
.spacer {
  margin-bottom: .8rem;
}
</style>