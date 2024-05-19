<script setup lang="ts">
import { useEntitiesStore } from '@/stores/entities-store';
import PublisherFormComponent from '@/views/Components/Entities/PublisherFormComponent.vue';
import router from '@/router';

const props = defineProps({
  id: String
})

if(!props.id || props.id == '0' || !parseInt(props.id)){
  router.back();
}

const store = useEntitiesStore();
store.fetchSelectedPublisher(props.id || '');

var onSubmit = (publisher: any) => {
  publisher.publisherId = props.id
  store.updatePublisher(publisher)
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
    <GoBack goBackText="Edit Publisher"/>
	</div>

  <PublisherFormComponent 
    :initial-values="store.publisher"
    @submit="onSubmit"
  />
</template>

<style scoped>
.spacer {
  margin-bottom: .8rem;
}
</style>