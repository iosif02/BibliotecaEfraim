<script setup lang="ts">
import PopularBooks from '@/views/Components/Books/PopularBooksComponent.vue';
import Pagination from '@/views/Components/Global/PaginationComponent.vue';
import CreateButtonComponent from "@/views/Components/Global/CreateButtonComponent.vue"
import { useBooksStore } from '@/stores/books-store';

const store = useBooksStore();
store.searchBooks();

</script>

<template>
	<Loading v-if="store.isLoading" />

  <SearchBar
    :defaultValue="store.books.searchModel.title"
    @valueChanged="(value: any) => (store.books.searchModel.title = value, store.books.searchModel.pagination.page = 1, store.searchBooks())"
    placeholder='Search book...'
  />

  <div class="results">
    Results 
    <span>({{ store.books.searchModel.pagination.total }})</span>
  </div>

  <PopularBooks v-if="store.books.searchModel.pagination.total" :books="store.books.data" />
  <div class="no-found" v-else-if="!store.isLoading"> No result found </div>

  <Pagination
    :current-page="store.books.searchModel.pagination.page"
    :last-page="store.books.searchModel.pagination.last_page"
    @change-page="(page: number) => (store.books.searchModel.pagination.page = page, store.searchBooks())"
  />

  <CreateButtonComponent route-name="createBook" />

</template>

<style scoped>
.results {
  font-family: 'Roboto-500';
  color: #2B2E3C;
  margin-bottom: 10px;
}
.results span {
    color: #AAAAAA;
}
</style>