<script setup lang="ts">
import type BookModel from '@/models/book/BookModel';
import type { PropType } from 'vue'
import moment from 'moment';
import StatusBookComponent from '@/views/Components/Global/StatusBookComponent.vue';

defineProps({
    books: {
        type: Object as PropType<BookModel>,
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
      <div class="borrow-book" v-for="transaction in books.transaction">
          <div>
            <div class="row">
              <RouterLink :to="{ name: 'userBorrowedBook', params: { id: transaction.user?.id ?? 0 }, query: { userName: transaction.user?.first_name + ' ' + transaction.user?.last_name } }" class="router-link">
                <p class="transaction-name text-elipsis">{{ transaction.user?.first_name + ' ' + transaction.user?.last_name }}</p>
              </RouterLink>
              <StatusBookComponent :status="books.status"/>
            </div>
            <div class="row">
              <p class="transaction-date text-elipsis">{{ formatDate(transaction.borrow_date) + ' - ' + formatDate(transaction.return_date) }}</p>
              <p class="date highlight">{{ daysLeft(transaction.delayed)}}</p>
            </div>
          </div> 
          <div class="row">
            <p class="transaction-voluntary text-elipsis">Processed by <span>{{ transaction.lender_name }}</span></p>
            <div class="book-action">
              <p class="transaction-return" @click="$emit('onReturn', transaction.id)">Return</p>
              <p :style="{ color: transaction.delayed > 0 ? '#999' : '' }" class="transaction-return" @click="$emit('extend', transaction.id)">Extend</p>
            </div>
          </div>
      </div>
  </div>
</template>

<style scoped>
.row{
  display: flex;
  flex-direction: row;
  justify-content: space-between;
}
.borrow-book{
  display: flex;
  flex-direction: column;
  width: 100%;
  height: 104px;
  background-color: #F6F6F6;
  border-radius: 8px;
  box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.15);
  padding: 16px 10px;
  justify-content: space-between;
  margin-bottom: 15px;
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
  /* letter-spacing: 1px; */
  /* text-decoration-line: underline; */
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
  flex-direction: row; 
  gap: 10px;
}
.transaction-return:first-child {
  color: #eb9b00;
}
</style>