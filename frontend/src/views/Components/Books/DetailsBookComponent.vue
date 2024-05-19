<script setup lang="ts">
import type BookModel from '@/models/book/BookModel';
import type { PropType } from 'vue'
import 'primeicons/primeicons.css';

defineProps({
  book: {
      type: Object as PropType<BookModel>,
      required: true
  }
});
</script>
<template>
  <div class="image-book">
    <div class="image">
      <img :src="$filters.imageFilter(book.image)" />
      <div v-if="book.is_marked" class="mark-container">
        <span class="pi pi-star-fill mark"></span>
      </div>
    </div>
    <p class="book-title">{{ book.title }}</p>
    <p class="book-author">{{ book.authors?.map(author => author.name).join(', ') }}</p>
    <div class="book-category">
      <span>({{ book.category?.number }})</span>
      <p>{{ book.category?.name }}</p>
    </div>
  </div>
</template>

<style scoped>
.image-book{
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  margin-bottom: 30px;
}
.image {
  width: 178px;
  height: 216px;
  margin-bottom: 20px;
  position: relative;
}
.image img {
  border-radius: 12px;
  object-fit: cover;
  height: 100%;
  width: 100%;
}
.book-title{
  font-family: 'Roboto-500';
  font-size: 20px;
  color: black;
  line-height: 32px;
  text-align: center;
}
.book-author{
  font-family: 'Roboto-500';
  line-height: 24px;
  font-size: 16px;
  color: #4974A5;
}
.book-category{
  display: flex;
  flex-direction: row;
}
.book-category span{
  font-family: 'Roboto-400';
  color: #76CECB;
  padding-right: 3px;
  line-height: 20px;
  font-size: 14px;
}
.book-category p{
  font-family: 'Roboto-400';
  line-height: 20px;
  font-size: 14px;
}
.mark-container{
  top: 0px;
  right: 10px;
  position: absolute;
  width: 1.5rem;
  height: 2rem;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: flex-end;
  padding-bottom: 5px;
  border-bottom-left-radius: 5px;
  border-bottom-right-radius: 5px;
}
.mark{
  font-size: 1rem; 
  color: yellow;
  top: 10px;
  left: 15px;
}
</style>