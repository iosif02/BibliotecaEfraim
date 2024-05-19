<script setup lang="ts">
import moment from 'moment';
import type DelayedBookModel from '@/models/book/DelayedBookModel';
import type { PropType } from 'vue'
import { RouterLink } from "vue-router";

const props = defineProps({
    books: {
        type: Object as PropType<DelayedBookModel[]>,
        required: true
    }
});

let formatDate = (date: string) => {
    if (date) {
        return moment(date, "YYYY-MM-DD h:mm:ss").format("DD MMM");
    }
    return date;
}

const daysLeft = (day: number) => {
  if (day === -1) {
    return day * -1 + ' day';
  } else {
    return day * -1 + ' days';
  }
}

</script>

<template>
    <div class="delayedBooks">
        <template v-for="currentBook in props?.books">
            <RouterLink :to="{ name: 'bookDetails', params: { id: currentBook.book.id} }" style="text-decoration: none;">
                <div class="book">
                    <img :src="$filters.imageFilter(currentBook.book.image)" alt="">
                    <div class="details">
                        <div class="top-section">
                            <p class="name">{{ currentBook.user?.first_name }} {{ currentBook.user?.last_name }}</p>
                           <!-- <p class="date">{{ formatDate(currentBook.return_date) }} - {{ delayedDays(currentBook.return_date) }} days</p> -->
                           <p class="date highlight">{{ formatDate(currentBook.return_date) }} - {{ daysLeft(currentBook.delayed) }}</p>
                        </div>
                        <div class="book-title">{{ currentBook.book.title }}</div>
                    </div>
                </div>
            </RouterLink>
        </template>
    </div>
</template>

<style scoped>
.category-title {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
}
.title {
    font-family: 'Roboto-500';
    color: #2B2E3C;
}
.book {
    background-color: #F6F6F6;
    border-radius: 8px;
    padding: 8px;
    display: flex;
    column-gap: 8px;
    margin-bottom: 3px;
}
.details {
    width: calc(100% - 48px);
}
.name {
    font-size: 14px;
    font-family: 'Roboto-500';
    color: #2B2E3C;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    padding-right: 20px;
}
img {
    height: 40px;
    width: 40px;
    object-fit: cover;
    border-radius: 12px;
}
.top-section {
    display: flex;
    justify-content: space-between;
}
.book-title {
    margin-top: 5px;
    font-size: 12px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.date {
    font-size: 12px;
    white-space: nowrap;
    /* overflow: hidden;
    text-overflow: ellipsis; */
}
.highlight {
    background-color: #f0f0f0; /* Highlight background color */
    color: #333; /* Highlight text color */
    padding: 3px 8px; /* Add padding for better visual appearance */
    border-radius: 50px; /* Add rounded corners to the highlight */
    font-weight: bold; /* Make the text bold */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Add a subtle shadow */
}
</style>