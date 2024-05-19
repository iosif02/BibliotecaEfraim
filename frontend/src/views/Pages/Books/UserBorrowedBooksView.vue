<script setup lang="ts">
import HistoryBook from '@/views/Components/Books/HistoryBookComponent.vue';
import BorrowedBookUser from '@/views/Components/Books/BorrowedBookUserComponent.vue';
import Pagination from '@/views/Components/Global/PaginationComponent.vue';
import UserDetails from '@/views/Components/Users/UserDetailsComponent.vue';
import router from '@/router';
import { useBooksStore } from '@/stores/books-store';
import { useUsersStore } from '@/stores/user-store';
import { ref } from 'vue';

const props = defineProps({
  id: String,
  userName: String,
})

if(!props.id || props.id == '0' || !parseInt(props.id))
    router.back();

const UsersStore = useUsersStore();
const store = useBooksStore();
UsersStore.fetchSelectedUser(props.id ?? "");
store.userBorrowedBooks.searchModel.user = parseInt(props.id || '');
store.userHistoryBooks.searchModel.user = parseInt(props.id || '');
store.userBorrowedBooks.searchModel.pagination.page = 1;
store.userHistoryBooks.searchModel.pagination.page = 1;
store.userHistoryBooks.searchModel.isReturned = true;
store.searchUserBorrowedBooks();
store.searchUserHistoryBooks();

const showModalOnReturn = ref<boolean>(false);
const showModalOnExtend = ref<boolean>(false);
let bookId: number = 0;

var openModal = (transactionId: number, type: string) => {
  bookId = transactionId;
  const transaction = store.userBorrowedBooks.data.find(item => item.id = transactionId);
  const condition = (transaction?.delayed ?? 0) <= 0;

  if(type == 'return')
    return showModalOnReturn.value = true;
    
  if(type == 'extend' && condition)
    return showModalOnExtend.value = true;
}

var hideModal = () => {
  showModalOnReturn.value = false
  showModalOnExtend.value = false
}

var returnBook = () => {
    hideModal();
    store.returnBook(bookId)
    .then((result) => {
        if(result){
            store.searchUserBorrowedBooks()
            store.fetchHomepage();
        }
    })
}

var extendBook = () => {
    hideModal();
    store.extendBook(bookId)
    .then((result) => {
        if(result){
            store.searchUserBorrowedBooks()
            store.fetchHomepage();
        }
    })
}

</script>

<template>
    <Loading v-if="store.isLoading || store.isLoadingTwo" />

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

    <GoBack :go-back-text="`${$route.query.userName} (${store.userBorrowedBooks.searchModel.pagination.total})`"/>

    <SearchBar
        :defaultValue="store.userBorrowedBooks.searchModel.title"
        @valueChanged="(value: string) => (
            store.userBorrowedBooks.searchModel.title = value, 
            store.userBorrowedBooks.searchModel.pagination.page = 1, 
            store.searchUserBorrowedBooks(),
            store.userHistoryBooks.searchModel.title = value, 
            store.userHistoryBooks.searchModel.pagination.page = 1, 
            store.searchUserHistoryBooks()
        )"
        placeholder='Search book...'
    />

    <UserDetails v-if="!store.userBorrowedBooks.searchModel.title" :user="UsersStore.user"/>

    <div v-if="store.userBorrowedBooks.searchModel.pagination.total || store.userHistoryBooks.searchModel.pagination.total">
        <Bounded v-if="store.userBorrowedBooks.searchModel.pagination.total" text='Active'/>
        <BorrowedBookUser 
            v-if="store.userBorrowedBooks.searchModel.pagination.total"
            :books="store.userBorrowedBooks.data"
            @onReturn="(transactionId) => openModal(transactionId, 'return')"
            @extend="(transactionId) => openModal(transactionId, 'extend')"
        />
        
        <Pagination
            :current-page="store.userBorrowedBooks.searchModel.pagination.page"
            :last-page="store.userBorrowedBooks.searchModel.pagination.last_page"
            @change-page="(page: number) => (store.userBorrowedBooks.searchModel.pagination.page = page, store.searchUserBorrowedBooks())"
        />

        <Bounded v-if="store.userHistoryBooks.searchModel.pagination.total" text='History'/>
        <HistoryBook v-if="store.userHistoryBooks.searchModel.pagination.total" :transactions="store.userHistoryBooks.data" />

        <Pagination
            :current-page="store.userHistoryBooks.searchModel.pagination.page"
            :last-page="store.userHistoryBooks.searchModel.pagination.last_page"
            @change-page="(page: number) => (store.userHistoryBooks.searchModel.pagination.page = page, store.searchUserHistoryBooks())"
        />
    </div>
    
    <div class="no-found" v-else-if="!store.isLoading && !store.isLoadingTwo"> No result found </div>
    
</template>