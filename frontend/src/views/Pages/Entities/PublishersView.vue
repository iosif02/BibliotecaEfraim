<script setup lang="ts">
import Pagination from '@/views/Components/Global/PaginationComponent.vue';
import PublishersComponent from '@/views/Components/Entities/PublishersComponent.vue';
import { useEntitiesStore } from '@/stores/entities-store';
import CreateButtonComponent from "@/views/Components/Global/CreateButtonComponent.vue"
import { ref } from 'vue';

const store = useEntitiesStore();
store.fetchPublishers();

let publisherId = 0;

const showModal = ref<boolean>(false);

var deletePublisher = () => {
  showModal.value = false;
  store.deletePublisher(publisherId)
  .then(result => {
    if(result){
      store.fetchPublishers();
    }
  });
}

var hideModal = () => {
  showModal.value = false;
}

var openModal = (selectedPublisherId: number) => {
  publisherId = selectedPublisherId;
  showModal.value = true;
}

</script>

<template>
  <Loading v-if="store.isLoading" />

  <Modal 
    v-if="showModal" 
    title="Delete Confirmation"
    description="Are you sure you want to delete this publisher?"
    action="Delete"
    @submit="deletePublisher" 
    @cancel="hideModal" 
  />

	<div>
    <GoBack :goBackText="`Publishers (${store.publishers.totalPublishers})`"/>
	</div>

  <SearchBar
    :defaultValue="store.publishers.searchModel.name"
    @valueChanged="(value: string) => (store.publishers.searchModel.name = value, store.publishers.searchModel.pagination.page = 1, store.fetchPublishers())"
    placeholder='Search publisher...'
  />

  <PublishersComponent v-if="store.publishers.searchModel.pagination.total" :publishers="store.publishers.data" routeName="editPublisher" @openModal="(selectedPublisherId) => openModal(selectedPublisherId)"/>
  <div class="no-found" v-else-if="!store.isLoading"> No result found </div>

  <Pagination
    :current-page="store.publishers.searchModel.pagination.page"
    :last-page="store.publishers.searchModel.pagination.last_page"
    @change-page="(page: number) => (store.publishers.searchModel.pagination.page = page, store.fetchPublishers())"
  />

  <CreateButtonComponent routeName="createPublisher"/>
</template>

<style scoped>
.spacer {
  margin-bottom: .8rem;
}
</style>