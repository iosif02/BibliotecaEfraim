<script setup lang="ts">
import router from '@/router';
import { RouterLink } from "vue-router";
import { useBooksStore } from '@/stores/books-store';
import { ref } from 'vue';
import BorrowBookComponent from '@/views/Components/Books/BorrowBookComponent.vue';
import DetailsBook from '@/views/Components/Books/DetailsBookComponent.vue'

const props = defineProps({
  id: String
})

if(!props.id || props.id == '0' || !parseInt(props.id))
    router.back();

const store = useBooksStore();
store.fetchBookDetails(props.id ?? "");
const showModal = ref<boolean>(false);
const showModalOnReturn = ref<boolean>(false);
const showModalOnExtend = ref<boolean>(false);
let bookId: number = 0;

var openModal = (transactionId: number, type: string) => {
  bookId = transactionId;
  const transaction = store.bookDetails.transaction.find(item => item.id = transactionId);
  const condition = (transaction?.delayed ?? 0) <= 0;

  if(type == 'return')
    return showModalOnReturn.value = true;
    
  if(type == 'extend' && condition)
    return showModalOnExtend.value = true;

  if(type == '')
  return showModal.value = true;
}

var hideModal = () => {
  showModal.value = false;
  showModalOnReturn.value = false
  showModalOnExtend.value = false
}

var returnBook = () => {
  hideModal();
  store.returnBook(bookId)
  .then((result) => {
    if(result)
      store.fetchBookDetails(props.id ?? "")
      .then(() => {
        store.fetchHomepage();
        store.searchHomeBooks();
      })
  })
}

var extendBook = () => {
  hideModal();
  store.extendBook(bookId)
  .then((result) => {
    if(result)
      store.fetchBookDetails(props.id ?? "")
      .then(() => {
        store.fetchHomepage();
      })
  })
}

var deleteBook = () => {
  hideModal();
  store.deleteBook(parseInt(props.id || ''))
  .then(result => {
    if(result){
      if(store.homepage.searchModel.title != "")
        store.searchHomeBooks();
      router.back();
    }
  });
}

</script>

<template>
  <Loading v-if="store.isLoading" />

  <Modal 
    v-if="showModal" 
    title="Delete Confirmation"
    description="Are you sure you want to delete this book?"
    action="Delete"
    @submit="deleteBook" 
    @cancel="hideModal" 
  />

  <Modal 
    v-if="showModalOnReturn" 
    title="Return Confirmation"
    description="Are you sure you want to return this book?"
    action="Return"
    @submit="returnBook" 
    @cancel="hideModal" 
  />

  <Modal 
    v-if="showModalOnExtend" 
    title="Extend Confirmation"
    description="Are you sure you want to extend this book?"
    action="Extend"
    @submit="extendBook" 
    @cancel="hideModal" 
  />

  <GoBack goBackText="Back">
    <button @click="router.push({ name: 'editBook', params: { id: props.id } })" class="btn-edit">Edit</button>
    <button @click="openModal(0, '')" class="btn-delete">Delete</button>
  </GoBack>
  
  <DetailsBook :book="store.bookDetails"/>

  <div class="copy-books">
    <p>Total Copies <span>({{ store.bookDetails.quantity }})</span></p>
    <div class="circle">
      <div v-for="book in store.bookDetails.quantity" :class="{'unavaible': book <= store.bookDetails.transaction_count}"></div>
    </div>
  </div>

  <BorrowBookComponent 
    :books="store.bookDetails" 
    @onReturn="(transactionId) => openModal(transactionId, 'return')" 
    @extend="(transactionId) => openModal(transactionId, 'extend')"/>

  <RouterLink :to="{ name: 'borrowBook', params: { id: props.id }}">
    <button 
      class="btn w-100" 
      :disabled="store.bookDetails.status == 0 ?? false"
      :class="{'disabled': store.bookDetails.status == 0}"
    >
      Borrow
    </button>
  </RouterLink>

</template>

<style scoped>
.copy-books{
  flex-direction: row;
  display: flex;
  flex-wrap: wrap;
  margin-bottom: 15px;
}
.copy-books p{
  font-family: 'Roboto-500';
  font-size: 14px;
  line-height: 24px;
  color: #2B2E3C;
  align-self: center;
}
.circle{
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  margin-left: 7px;
}
.circle .unavaible{
  background-color: #F2DCDC;
}
.circle div{
  width: 24px;
  height: 24px;
  border-radius: 100%;
  background-color: #E7E7E7;
  margin-left: 12px;
}
.disabled{
  background-color: #999;
  border-color: #999;
  opacity: 0.5;
}
</style>