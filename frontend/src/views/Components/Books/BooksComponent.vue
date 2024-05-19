<script setup lang="ts">
import type BookModel from '@/models/book/BookModel';
import type { PropType } from 'vue'
import { RouterLink } from "vue-router";
import StatusBookComponent from '@/views/Components/Global/StatusBookComponent.vue';

defineProps({
    books: {
        type: Object as PropType<BookModel[]>,
        required: true
    }
});
</script>

<template>
    <div class="books">
        <template v-for="book in books" >
            <RouterLink :to="{ name: 'bookDetails', params: { id: book.id} }" style="text-decoration: none;">
                <div class="book">
                    <div class="image">
                        <img :src="$filters.imageFilter(book.image)" alt="">
                        <div v-if="book.is_marked" class="mark-container">
                            <span class="pi pi-star-fill mark"></span>
                        </div>
                    </div>
                    <div class="details">
                        <div class="top-section">
                            <div class="title">{{ book.title }}</div>
                            <StatusBookComponent :status="book.status" route="editBook"/>   
                        </div>
                        <div class="bottom-section">
                            <div class="author">{{ book.authors?.map(author => author.name).join(', ') }}</div>
                            <div class="edit">
                                <div class="category">
                                    <span class="category-number">({{ book.category?.number }})</span>
                                    <span class="category-name">{{ book.category?.name }}</span>
                                </div>
                                <div class="btn-link">
                                    <RouterLink :to="{ name: 'editBook', params: { id: book.id } }">
                                        <button class="btn-edit">Edit</button>
                                    </RouterLink>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </RouterLink>
        </template>
    </div>
</template>

<style scoped>
.book {
    display: flex;
    width: 100%;
    margin-bottom: 25px;
}
.image {
    width: 83px;
    height: 90px;
    border-radius: 12px;
    margin-right: 16px;
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
  width: 1.1rem;
  height: 1.6rem;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: flex-end;
  padding-bottom: 5px;
  border-bottom-left-radius: 3px;
  border-bottom-right-radius: 3px;
}
.mark{
  font-size: 0.7rem; 
  color: yellow;
  top: 10px;
  left: 15px;
}
.details {
    width: calc(100% - 99px);
}
.top-section {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 5px;
}
.title {
    font-family: 'Roboto-400';
    color: #2B2E3C;
    font-size: 16px;
    font-weight: 600;
}
.author {
    margin-bottom: 10px;
    color: #8A8A8A;
}
.category {
    display: flex;
}
.category-number {
    font-size: 12px;
    color: #76CECB;
    margin-right: 6px;
}
.category-name {
    color: #ACB1B6;
    font-size: 13px;
}
.status {
    font-size: 12px;
    color: #76CE9F;
    background-color: #DCF2E7;
    border-radius: 22px;
    padding: 5px 10px 5px 20px;
    position: relative;
}
.status::before {
    content: '';
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 5px;
    height: 5px;
    background: #76CE9F;
    border-radius: 50%;
    left: 10px;
}
.edit{
    display: flex;
    flex-direction: row;
    justify-content: space-between;
}
.btn-link{
    position: relative;
    bottom: -10px;
}
.title, .author, .category{
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    padding-right: 20px;
}
</style>