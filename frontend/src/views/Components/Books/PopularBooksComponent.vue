<script setup lang="ts">
import type BookModel from '@/models/book/BookModel';
import type { PropType } from 'vue'
import { RouterLink } from "vue-router";

defineProps({
    books: {
        type: Object as PropType<BookModel[]>,
        required: true
    }
});

</script>

<template>
    <div class="books">
        <template v-for="book in books">
            <RouterLink :to="{ name: 'bookDetails', params: { id: book.id} }" class="book">
                    <div class="image">
                        <img :src="$filters.imageFilter(book.image)" alt="">
                        <div v-if="book.is_marked" class="mark-container">
                            <span class="pi pi-star-fill mark"></span>
                        </div>
                    </div>
                    <div class="details">
                        <div class="title">{{ book.title }}</div>
                        <div class="author">{{ book.authors?.map(author => author.name).join(', ') }}</div>
                        <div class="category">
                            <span class="category-name"><span class="category-number">({{ typeof book.category === 'object' ? book.category?.number : book?.number }})</span>{{ typeof book.category === 'object' ? book.category?.name : book?.category }}</span>
                            <!-- <router-link :to="{name: 'categoryBooks', params: { id: book.category?.id || book?.category_id || 0 }, query: { categoryName: book.category?.name || book?.category } }" class="category-name"><span class="category-number">({{ book?.number ||  book.category?.number}})</span>{{ book.category?.name || book?.category }}</router-link> -->
                        </div>
                    </div>
            </RouterLink> 
        </template>
    </div>
</template>

<style scoped>
.books {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}
.book {
    width: 100%;
    max-width: 98px;
    overflow: hidden;
    text-decoration: none
}
.books .book:nth-child(3n + 2){
    justify-self: center;
}

.books .book:nth-child(3n){
    justify-self: end;
}
.image {
    width: 100%;
    height: 128px;
    border-radius: 12px;
    margin-bottom: 5px;
    position: relative;
}
.image img {
    width: 100%;
    height: 100%;
    border-radius: 12px;
    object-fit: cover;
}
.mark-container{
  top: 0px;
  right: 10px;
  position: absolute;
  width: 1.2rem;
  height: 1.7rem;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: flex-end;
  padding-bottom: 5px;
  border-bottom-left-radius: 3px;
  border-bottom-right-radius: 3px;
}
.mark{
  font-size: 0.8rem; 
  color: yellow;
  top: 10px;
  left: 15px;
}
.author {
    font-size: 12px;
    color: #4974A5;
    font-family: 'Roboto-500';
    padding: 5px 0;
}
.title {
    color: #2B2E3C;
}
/* .category-name {
    text-decoration: none
} */
.category {
    font-size: 12px;
}
.category-number {
    color: #76CECB;
    padding-right: 5px;
}
.title, .author, .category {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 98px;
}
.books .book:last-child{
    align-self: flex-start;
}
</style>