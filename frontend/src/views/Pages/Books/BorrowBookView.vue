<script setup lang="ts">
import router from '@/router';
import Pagination from '@/views/Components/Global/PaginationComponent.vue';
import { useBooksStore } from '@/stores/books-store';
import { useUsersStore } from '@/stores/user-store';
import { ref } from 'vue';
import UsersComponent from '@/views/Components/Users/UsersComponent.vue';
import BorrowBookModel from "@/models/book/BorrowBookModel";

const props = defineProps({
  id: String
})

if(!props.id || props.id == '0' || !parseInt(props.id))
  router.back();

const BooksStore = useBooksStore();
const UsersStore = useUsersStore();

UsersStore.fetchUsers();

if(BooksStore.bookDetails.id != parseInt(props.id || ''))
  BooksStore.fetchBookDetails(props.id ?? "");

const showModal = ref<boolean>(false);
let userId = 0;

var openModal = (selectedUserId: number) => {
  showModal.value = true;
  userId = selectedUserId;
}

var borrowBook = () => {
  let borrowModel = new BorrowBookModel();
  borrowModel.book_id = parseInt(props.id || '');
  borrowModel.user_id = userId;
  showModal.value = false;
  BooksStore.borrowBook(borrowModel)
  .then(result =>{
    if(result)
      router.back()
  });
}

var hideModal = () => {
  showModal.value = false;
}
</script>

<template>
  <Loading v-if="BooksStore.isLoading || UsersStore.isLoading" />

  <Modal 
    v-if="showModal" 
    title="Borrow Confirmation"
    description="Are you sure you want to borrow this book?"
    action="Borrow"
    @submit="borrowBook" 
    @cancel="hideModal" 
  />

  <GoBack goBackText="Back"/>

  <div class="image-section">
    <div class="image">
        <img :src="BooksStore.bookDetails.image || '/img/book.jpg'" alt="book">
    </div>
    <div class="details-section">
        <p class="book-title">{{ BooksStore.bookDetails.title }}</p>
        <p class="book-author">{{ BooksStore.bookDetails.authors?.[0]?.name }}</p>
        <div class="book-category">
            <span>({{ BooksStore.bookDetails.category?.number }})</span>
            <p>{{ BooksStore.bookDetails.category?.name }}</p>
        </div>
    </div>
  </div>

  <SearchBar
    :defaultValue="UsersStore.users.searchModel.name"
    @valueChanged="(value: string) => (UsersStore.users.searchModel.name = value, UsersStore.users.searchModel.pagination.page = 0, UsersStore.fetchUsers())"
    placeholder="Search user..."
  />

  <UsersComponent v-if="UsersStore.users.searchModel.pagination.total" :users="UsersStore.users.data" @openModal="(userId) => openModal(userId)"/>
  <div class="no-found" v-else-if="!(BooksStore.isLoading || UsersStore.isLoading)"> No result found </div>

  <Pagination
    :current-page="UsersStore.users.searchModel.pagination.page"
    :last-page="UsersStore.users.searchModel.pagination.last_page"
    @change-page="(page: number) => (UsersStore.users.searchModel.pagination.page = page, UsersStore.fetchUsers())"
  />

</template>

<style scoped>
.image {
  width: 90px;
  height: 96px;
  min-width: 90px;
}
.image img {
  border-radius: 12px;
  object-fit: cover;
  height: 100%;
  width: 100%;
}
.image-section{
    display: flex;
    flex-direction: row;
    margin-bottom: 13px;
}
.book-title{
    font-family: 'Roboto-500';
    font-size: 16px;
    color: black;
    line-height: 24px;
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
  margin-top: 5px;
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
.details-section{
    margin-left: 13px;
    overflow: hidden;
}
p {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
.no-found{
  top: calc(50% + 80px);
}
</style>