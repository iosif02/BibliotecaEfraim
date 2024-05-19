<script setup lang="ts">
import { useBooksStore } from '@/stores/books-store';
import DelayedBooks from '@/views/Components/Books/DelayedBooksComponent.vue';
import PopularBooks from '@/views/Components/Books/PopularBooksComponent.vue';
import Categories from '@/views/Components/Books/CategoriesComponent.vue';
import TitleSection from "@/views/Components/Global/TitleSectionComponent.vue";
import SearchBooksHomeView from '@/views/Pages/Books/SearchBooksHomeView.vue';

const store = useBooksStore();
if(!store.homepage.isFetched){
  store.fetchHomepage();
}

</script>

<template>
	<div class="homepage">
    <Loading v-if="store.isLoading" />

    <SearchBar
        :defaultValue="store.homepage.searchModel.title"
        @valueChanged="(value: any) => (store.homepage.searchModel.title = value ,store.homepage.searchModel.pagination.page = 1, store.homepage.searchModel.title && store.searchHomeBooks())"
        placeholder='Search book...'
    />

    <div v-if="!store.homepage.searchModel.title">
      <div class="delayed-books">
        <TitleSection :title="`Late (${store.homepage.data.totalDelayedBooks})`" :route-name="'delayedBooks'" />
        <DelayedBooks :books="store.homepage.data.delayedBooks"/>
      </div>

      <div class="popular-books">
        <TitleSection :title="`Popular Books (${store.homepage.data.totalPopularBooks})`" :route-name="'popularBooks'" />
        <PopularBooks :books="store.homepage.data.popularBooks" />
      </div>

      <div class="categories">
        <TitleSection :title="`Categories (${store.homepage.data.totalCategoryBooks})`" :route-name="'categories'" />
        <Categories :categories="store.homepage.data.categories"/>
      </div>
    </div>
    <div v-else>
      <SearchBooksHomeView />
    </div>
	</div>
</template>

<style scoped>
.delayed-books, .popular-books {
    margin-bottom: 30px;
}
</style>