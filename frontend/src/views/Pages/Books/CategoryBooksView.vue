<script setup lang="ts">
import PopularBooks from '@/views/Components/Books/PopularBooksComponent.vue';
import Pagination from '@/views/Components/Global/PaginationComponent.vue';
import router from '@/router';
import { useBooksStore } from '@/stores/books-store';

const props = defineProps({
  id: String,
  categoryName: String
})

if(!props.id || props.id == '0' || !parseInt(props.id))
    router.replace({ name: 'categories' });

const store = useBooksStore();
store.categoryBooks.searchModel.category = parseInt(props.id || '')
store.categoryBooks.searchModel.pagination.page = 1
store.searchCategoryBooks();

</script>

<template>
    <Loading v-if="store.isLoading" />

    <GoBack :go-back-text="`${$route.query.categoryName} (${store.categoryBooks.searchModel.pagination.total})`"/>

    <SearchBar
        :defaultValue="store.categoryBooks.searchModel.title"
        @valueChanged="(value: string) => (store.categoryBooks.searchModel.title = value, store.categoryBooks.searchModel.pagination.page = 1, store.searchCategoryBooks())"
        placeholder='Search book...'
    />

    <PopularBooks v-if="store.categoryBooks.searchModel.pagination.total" :books="store.categoryBooks.data" />
    <div class="no-found" v-else-if="!store.isLoading"> No result found </div>

    <Pagination
        :current-page="store.categoryBooks.searchModel.pagination.page"
        :last-page="store.categoryBooks.searchModel.pagination.last_page"
        @change-page="(page: number) => (store.categoryBooks.searchModel.pagination.page = page, store.searchCategoryBooks())"
    />
</template>