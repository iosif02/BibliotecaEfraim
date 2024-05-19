<script setup lang="ts">
import PopularBooks from '@/views/Components/Books/PopularBooksComponent.vue';
import Pagination from '@/views/Components/Global/PaginationComponent.vue';
import router from '@/router';
import { useBooksStore } from '@/stores/books-store';

const props = defineProps({
  id: String,
  publisherName: String,
})

if(!props.id || props.id == '0' || !parseInt(props.id))
    router.replace({ name: 'author' });

const store = useBooksStore();
store.publisherBooks.searchModel.publisher = parseInt(props.id || '')
store.publisherBooks.searchModel.pagination.page = 1
store.searchPublisherBooks();

</script>

<template>
    <Loading v-if="store.isLoading" />

    <GoBack :go-back-text="`${$route.query.publisherName} (${store.publisherBooks.searchModel.pagination.total})`"/>

    <SearchBar
        :defaultValue="store.publisherBooks.searchModel.title"
        @valueChanged="(value: string) => (store.publisherBooks.searchModel.title = value, store.publisherBooks.searchModel.pagination.page = 1, store.searchPublisherBooks())"
        placeholder='Search book...'
    />

    <PopularBooks v-if="store.publisherBooks.searchModel.pagination.total" :books="store.publisherBooks.data" />
    <div class="no-found" v-else-if="!store.isLoading"> No result found </div>

    <Pagination
        :current-page="store.publisherBooks.searchModel.pagination.page"
        :last-page="store.publisherBooks.searchModel.pagination.last_page"
        @change-page="(page: number) => (store.publisherBooks.searchModel.pagination.page = page, store.searchPublisherBooks())"
    />
</template>