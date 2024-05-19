<script setup lang="ts">
import type { PropType } from 'vue'
import moment from 'moment';
import type DelayedBookModel from '@/models/book/DelayedBookModel';

defineProps({
    books: {
        type: Object as PropType<DelayedBookModel[]>,
        required: true
    }
});

let formatDate = (date: string) => {
    if (date) {
        return moment(date, "YYYY-MM-DD h:mm:ss").format("DD MMM YYYY");
    }
    return date;
}

const daysLeft = (day: number) => {
  const suffix = day === 1 ? 'day' : 'days';
  const absDay = Math.abs(day);
  
  if (day === 0) {
    return 'the return day';
  } else {
    const status = day > 0 ? 'left' : 'late';
    return `${absDay} - ${suffix} ${status}`;
  }
}

</script>

<template>
  <div class="borrow-list">
    <div class="card" v-for="transaction in books">
      <RouterLink :to="{ name: 'bookDetails', params: { id: transaction.book?.id ?? 0 } }" class="router-link">
        <div class="borrow-book">
          <div class="container-image">
            <img :src="$filters.imageFilter(transaction.book?.image)" alt="">
            <div v-if="transaction.book?.is_marked" class="mark-container">
              <span class="pi pi-star-fill mark"></span>
            </div>
          </div>
          <div class="details-container">
            <div class="details">
              <p class="transaction-name text-elipsis">{{ transaction.book?.title }}</p>
              <p class="transaction-date text-elipsis">{{ formatDate(transaction.borrow_date) + ' - ' + formatDate(transaction.return_date) }}</p>
              <p class="transaction-voluntary text-elipsis">Processed by <span>{{ transaction.lender_name }}</span></p>
            </div> 
            <div class="book-action">
              <p class="date highlight">{{ daysLeft(transaction.delayed)}}</p>
              <p class="transaction-return" @click.prevent="$emit('onReturn', transaction.id)">Return</p>
              <p :style="{ color: transaction.delayed > 0 ? '#999' : '' }" class="transaction-return" @click.prevent="$emit('extend', transaction.id)">Extend</p>
            </div>
          </div>
        </div>
      </RouterLink>
    </div>
  </div>
</template>

<style scoped>
.card{
  background-color: #F6F6F6;
  width: 100%;
  height: 104px;
  padding: 10px 10px;
  margin-bottom: 15px;
  border-radius: 8px;
  box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.15);
}
.borrow-book{
  display: flex;
  flex-direction: row;
  width: 100%;
  height: 100%;
  overflow: hidden;
}
.container-image {
  height: 100%;
  min-width: 60px;
  max-width: 60px;
  border-radius: 5px;
  margin-right: 10px;
  position: relative;
}
.container-image img {
  width: 100%;
  height: 100%;
  border-radius: 5px;
  object-fit: cover;
}
.mark-container{
  top: 0px;
  right: 10px;
  position: absolute;
  width: 1rem;
  height: 1.5rem;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: flex-end;
  padding-bottom: 5px;
  border-bottom-left-radius: 3px;
  border-bottom-right-radius: 3px;
}
.mark{
  font-size: 0.65rem; 
  color: yellow;
  top: 10px;
  left: 15px;
}
.details-container {
  display: flex;
  flex-direction: row;
  width: 100%;
  justify-content: space-between;
  overflow: hidden;
}
.details {
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: space-evenly;
  overflow: hidden;
}
.transaction-name{
  font-family: 'Roboto-500';
  font-size: 12px;
  line-height: 14.06px;
  color: #333333;
  padding-right: 20px;
  /* letter-spacing: 1px; */
}
.transaction-date{
  font-family: 'Roboto-500';
  font-style: normal;
  font-weight: 500;
  font-size: 12px;
  line-height: 14px;
  padding-right: 20px;
  /* letter-spacing: 1px; */
}
.transaction-voluntary{
  font-family: 'Roboto-400';
  font-style: normal;
  color: #8A8A8A;
  font-size: 12px;
  line-height: 14px;
  padding-right: 20px;
/* letter-spacing: 1px; */
}
.transaction-voluntary span{
  font-family: 'Roboto-400';
  font-weight: 700;
}
.transaction-return{
  font-family: 'Roboto';
  font-style: normal;
  font-weight: 500;
  font-size: 14px;
  line-height: 14px;
  text-transform: uppercase;
  color: #76CECB;
}
.date {
  font-size: 12px;
  white-space: nowrap;
}
.highlight {
  background-color: #f0f0f0;
  color: #333; 
  padding: 3px 8px; 
  border-radius: 50px; 
  font-weight: bold; 
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); 
}
.book-action{
  display: flex;
  flex-direction: column; 
  align-items: end;
  justify-content: space-evenly;
}
.transaction-return:nth-child(2) {
  color: #eb9b00;
}
</style>