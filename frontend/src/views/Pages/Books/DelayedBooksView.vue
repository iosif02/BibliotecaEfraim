<script setup lang="ts">
import DelayedBooks from '@/views/Components/Books/DelayedBooksComponent.vue';
import Pagination from '@/views/Components/Global/PaginationComponent.vue';
import { useBooksStore } from '@/stores/books-store';

const store = useBooksStore();
// if(!store.delayedBooks.data.length)
    store.fetchDelayedBooks();

</script>

<template>
    <Loading v-if="store.isLoading" />

    <GoBack :go-back-text="`Late Books (${store.delayedBooks.searchModel.pagination.total})`" />

    <SearchBar
        :defaultValue="store.delayedBooks.searchModel.title"
        @valueChanged="(value: string) => (store.delayedBooks.searchModel.title = value, store.delayedBooks.searchModel.pagination.page = 1, store.fetchDelayedBooks())"
        placeholder='Search book...'
    />

    <DelayedBooks v-if="store.delayedBooks.searchModel.pagination.total" :books="store.delayedBooks.data" />
    <div class="no-found" v-else-if="!store.isLoading"> No result found </div>

    <Pagination
        :current-page="store.delayedBooks.searchModel.pagination.page"
        :last-page="store.delayedBooks.searchModel.pagination.last_page"
        @change-page="(page: number) => (store.delayedBooks.searchModel.pagination.page = page, store.fetchDelayedBooks())"
    />
</template>