<script setup lang="ts">
import { useEntitiesStore } from '@/stores/entities-store';
import router from '@/router';
import CategoryFormComponent from '@/views/Components/Entities/CategoryFormComponent.vue';

const store = useEntitiesStore();

var onSubmit = (category: any) => {
  store.createCategory(category)
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
    <GoBack goBackText="Add Category"/>
	</div>

  <CategoryFormComponent 
    @submit="onSubmit"
  />  
</template>

<style scoped>
.spacer {
  margin-bottom: .8rem;
}
</style>