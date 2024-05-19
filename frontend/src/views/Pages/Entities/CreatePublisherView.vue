<script setup lang="ts">
import { useEntitiesStore } from '@/stores/entities-store';
import router from '@/router';
import PublisherFormComponent from '@/views/Components/Entities/PublisherFormComponent.vue';

const store = useEntitiesStore();

var onSubmit = (publisher: any) => {
  store.createPublisher(publisher)
  .then(result => {
    if(result){
      router.back();
    }
  });
}
</script>

<template>
  <Loading v-if="store.isLoading" />

	<div>
    <GoBack goBackText="Add Publisher"/>
	</div>

  <PublisherFormComponent 
    @submit="onSubmit"
  />
</template>

<style scoped>
.spacer {
  margin-bottom: .8rem;
}
</style>