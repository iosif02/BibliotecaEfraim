<script setup lang="ts">
import PopularBooks from '@/views/Components/Books/PopularBooksComponent.vue';
import Pagination from '@/views/Components/Global/PaginationComponent.vue';
import { useBooksStore } from '@/stores/books-store';

const store = useBooksStore();
store.fetchPopularBooks();
</script>

<template>
    <Loading v-if="store.isLoading" />

    <GoBack go-back-text="Popular Books" />

    <SearchBar
        :defaultValue="store.popularBooks.searchModel.title"
        @valueChanged="(value: string) => (store.popularBooks.searchModel.title = value, store.popularBooks.searchModel.pagination.page = 1, store.fetchPopularBooks())"
        placeholder='Search book...'
    />

    <PopularBooks v-if="store.popularBooks.data[0]" :books="store.popularBooks.data" />
    <div class="no-found" v-else-if="!store.isLoading"> No result found </div>

    <Pagination
        :current-page="store.popularBooks.searchModel.pagination.page"
        :last-page="store.popularBooks.searchModel.pagination.last_page"
        @change-page="(page: number) => (store.popularBooks.searchModel.pagination.page = page, store.fetchPopularBooks())"
    />
</template>